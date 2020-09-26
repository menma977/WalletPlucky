<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Model\LogBalance;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

class DogeController extends Controller
{
  /**
   * @param Request $request
   * @return JsonResponse
   * @throws ValidationException
   */
  public function store(Request $request)
  {
    $this->validate($request, [
      'wallet' => 'required|string',
      'amount' => 'required|numeric',
      'sessionCookie' => 'required|string',
      'secondary_password' => 'required|numeric'
    ]);

    if (Hash::check($request->secondary_password, Auth::user()->secondary_password)) {
      $response = Http::asForm()->post('https://www.999doge.com/api/web.aspx', [
        'a' => 'Withdraw',
        's' => $request->sessionCookie,
        'Amount' => $request->amount,
        'Address' => $request->wallet,
        'Totp' => '""',
        'Currency' => 'doge',
      ]);

      if ($response->successful()) {
        if (isset($response->json()['InsufficientFunds'])) {
          $data = [
            'message' => 'Insufficient Funds',
          ];
          return response()->json($data, 500);
        } else if (isset($response->json()['TooSmall'])) {
          $data = [
            'message' => 'Balance Too Small',
          ];
          return response()->json($data, 500);
        } else if (isset($response->json()['error'])) {
          $data = [
            'message' => $response->json()['error'],
          ];
          return response()->json($data, 500);
        } else {
          $logBalance = new LogBalance();
          $logBalance->user_id = Auth::id();
          $logBalance->wallet = $request->wallet;
          $logBalance->balance = $request->amount;
          $logBalance->save();
          $data = [
            'message' => $response->json(),
          ];
          return response()->json($data, 200);
        }
      } else {
        $data = [
          'message' => 'Failed to process data',
        ];
        return response()->json($data, 500);
      }
    } else {
      $data = [
        'message' => 'Wrong Secondary password',
      ];
      return response()->json($data, 500);
    }
  }

  /**
   * @return JsonResponse
   */
  public function update()
  {
    $user = User::find(Auth::user()->id);
    $user->date_trade = Carbon::now();
    $user->save();

    return response()->json(['message' => 'success'], 200);
  }
}
