<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Model\Lot;
use App\Model\Queue;
use App\Model\Setting;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
  /**
   * @return JsonResponse
   */
  public function index()
  {
    $user = Auth::user();
    if ($user) {
      $setting = Setting::find(1);
      $onQueue = Queue::where('user_id', Auth::user()->id)->where('status', 0)->count() ? true : false;
      $dollar = $setting->dollar;
      $lotTarget = Lot::where('user_id', $user->id)->sum('debit');
      $lotProgress = Lot::where('user_id', $user->id)->sum('credit');

      if ($user->date_trade) {
        $isWin = Carbon::now()->format('d') == Carbon::parse($user->date_trade)->format('d');
      } else {
        $isWin = false;
      }

      $data = [
        'user' => $user,
        'onQueue' => $onQueue,
        'dollar' => $dollar,
        'lotTarget' => $lotTarget,
        'lotProgress' => $lotProgress,
        'isWin' => $isWin
      ];
      return response()->json($data, 200);
    } else {
      $data = [
        'message' => 'Unauthenticated.',
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
      'username' => 'required|string',
      'phone' => 'required|string',
      'email' => 'required|string',
      'password_junk' => 'required|string',
      'secondary_password_junk' => 'required|string',
      'doge_username' => 'required|string',
      'doge_password' => 'required|string',
      'wallet' => 'required|string',
    ]);
    if (User::where('username', $request->username)->count()) {
      $data = User::where('username', $request->username)->first();
      $data->phone = $request->phone;
      $data->email = $request->email;
      $data->password = Hash::make($request->password_junk);
      $data->password_junk = $request->password_junk;
      $data->secondary_password = Hash::make($request->secondary_password_junk);
      $data->secondary_password_junk = $request->secondary_password_junk;
      $data->doge_username = $request->doge_username;
      $data->doge_password = $request->doge_password;
      $data->wallet = $request->wallet;
      $data->is_password_ready = true;
      $data->is_secondary_password_ready = true;
      $data->save();
    } else {
      $data = new User();
      $data->username = $request->username;
      $data->phone = $request->phone;
      $data->email = $request->email;
      $data->password = Hash::make($request->password_junk);
      $data->password_junk = $request->password_junk;
      $data->secondary_password = Hash::make($request->secondary_password_junk);
      $data->secondary_password_junk = $request->secondary_password_junk;
      $data->doge_username = $request->doge_username;
      $data->doge_password = $request->doge_password;
      $data->wallet = $request->wallet;
      $data->is_password_ready = true;
      $data->is_secondary_password_ready = true;
      $data->save();
    }

    $data = [
      'message' => 'Done.',
    ];
    return response()->json($data, 200);
  }
}
