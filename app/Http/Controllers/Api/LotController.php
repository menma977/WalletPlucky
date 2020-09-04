<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Model\Level;
use App\Model\Lot;
use App\Model\LotList;
use App\Model\Queue;
use App\Model\Setting;
use App\Model\WalletAdmin;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

class LotController extends Controller
{
  /**
   * @return JsonResponse
   */
  public function create()
  {
    $response = Http::asForm()->withHeaders([
      'X-Requested-With' => 'XMLHttpRequest',
      'Content-Type' => 'application/json'
    ])->post('https://pluckywin.com/api/trade/api.php', [
      'a' => 'CekUser',
      'username' => Auth::user()->username,
      'ref' => md5(Auth::user()->username . "b0d0nk111179"),
    ]);

    if ($response->successful()) {
      if ($response->json()['code'] === 500) {
        $data = [
          'message' => 'Error Data Response',
        ];

        return response()->json($data, 500);
      } else {
        $randomAdminWallet = WalletAdmin::all()->random(1)->first();
        $it = Setting::find(1)->first();
        $nextLot = LotList::find(Auth::user()->lot >= LotList::orderBy('id', 'desc')->get()->first()->id ? LotList::orderBy('id', 'desc')->get()->first()->id : Auth::user()->lot + 1);
        $totalValue = $nextLot->price;
        $level = Level::all();
        $dataQueue = array();
        $levelIndex = 1;
        $binary = $response->json();

        foreach ($binary['list'] as $id => $item) {
          if ($levelIndex > 7) {
            break;
          }

          //to do active user
          //$getDataUser = User::where('username', $item->username)->first();

          //simulate
          $getDataUser = User::where('username', Auth::user()->username)->first();
          $totalLot = Lot::where('user_id', $getDataUser->id)->sum('debit') - Lot::where('user_id', $getDataUser->id)->sum('credit');
          if ($totalLot >= 0 && $getDataUser->lot >= 0 && $getDataUser->lot >= $nextLot->id) {
            //for USER
            $totalValue -= $nextLot->price * $level->find($levelIndex)->percent / 100;
            $dataList = [
              'user' => $getDataUser->wallet,
              'id' => $getDataUser->id,
              'value' => $nextLot->price * $level->find($levelIndex)->percent / 100,
              'type' => 1
            ];
            array_push($dataQueue, $dataList);
            $levelIndex++;
          }
        }

        //for IT
        $itShare = $nextLot->price * $it->fee / 100;
        $dataList = [
          'user' => "Network Fee",
          'id' => $it->id,
          'value' => $nextLot->price * $it->fee / 100,
          'type' => 0
        ];
        $totalValue -= $itShare;
        array_push($dataQueue, $dataList);

        //for Admin
        $dataList = [
          'user' => $randomAdminWallet->wallet,
          'id' => $randomAdminWallet->id,
          'value' => $totalValue,
          'type' => 2
        ];
        array_push($dataQueue, $dataList);

        $data = [
          'nextLot' => $nextLot,
          'data' => $dataQueue,
        ];

        return response()->json($data, 200);
      }
    } else {
      $data = [
        'message' => 'Target Process Time Out',
      ];

      return response()->json($data, 500);
    }
  }

  /**
   * @param Request $request
   * @return JsonResponse
   * @throws ValidationException
   */
  public function store(Request $request)
  {
    $this->validate($request, [
      'grade' => 'required|numeric|exists:lot_lists,id',
      'balance' => 'required|numeric',
      'secondary_password' => 'required|numeric'
    ]);

    if (Queue::where('user_id', Auth::user()->id)->where('status', false)->count()) {
      $data = [
        'message' => 'LOT in process'
      ];

      return response()->json($data, 500);
    } else if (Hash::check($request->secondary_password, Auth::user()->secondary_password)) {
      $response = Http::asForm()->withHeaders([
        'X-Requested-With' => 'XMLHttpRequest',
        'Content-Type' => 'application/json'
      ])->post('https://pluckywin.com/api/trade/api.php', [
        'a' => 'CekUser',
        'username' => Auth::user()->username,
        'ref' => md5(Auth::user()->username . "b0d0nk111179"),
      ]);

      if ($response->successful()) {
        if ($response->json()['code'] === 500) {
          $data = [
            'message' => 'Error Data Response',
          ];

          return response()->json($data, 500);
        } else {
          $randomAdminWallet = WalletAdmin::all()->random(1)->first();
          $it = Setting::find(1)->first();
          $nextLot = LotList::find(Auth::user()->lot >= LotList::orderBy('id', 'desc')->get()->first()->id ? LotList::orderBy('id', 'desc')->get()->first()->id : Auth::user()->lot + 1);
          $totalValue = $nextLot->price;
          $level = Level::all();
          $dataQueue = array();
          $levelIndex = 1;
          $binary = $response->json();

          //for IT
          $queue = new Queue();
          $queue->user_id = Auth::user()->id;
          $queue->send_to = $it->id;
          $queue->value = $nextLot->price * $it->fee / 100;
          $queue->type = 0;
          $queue->save();
          $totalValue -= $queue->value;

          foreach ($binary['list'] as $id => $item) {
            if ($levelIndex > 7) {
              break;
            }

            //to do active user
            //$getDataUser = User::where('username', $item->username)->first();

            //simulate
            $getDataUser = User::where('username', Auth::user()->username)->first();
            $totalLot = Lot::where('user_id', $getDataUser->id)->sum('debit') - Lot::where('user_id', $getDataUser->id)->sum('credit');
            if ($totalLot >= 0 && $getDataUser->lot >= 0 && $getDataUser->lot >= $nextLot->id) {
              //for USER
              $queue = new Queue();
              $queue->user_id = Auth::user()->id;
              $queue->send_to = $getDataUser->id;
              $queue->value = $nextLot->price * $level->find($levelIndex)->percent / 100;
              $queue->type = 1;
              $queue->save();
              $totalValue -= $queue->value;
              $levelIndex++;
            }
          }

          //for SPONSOR
          $queue = new Queue();
          $queue->user_id = Auth::user()->id;
          $queue->send_to = $randomAdminWallet->id;
          $queue->value = $totalValue;
          $queue->type = 2;
          $queue->save();

          $data = [
            'nextLot' => $nextLot,
            'data' => $dataQueue,
          ];

          return response()->json($data, 200);
        }
      } else {
        $data = [
          'message' => 'Target Process Time Out',
        ];

        return response()->json($data, 500);
      }
    }

    $data = [
      'message' => 'Secondary password wrong'
    ];

    return response()->json($data, 500);
  }
}
