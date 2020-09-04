<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Model\Lot;
use App\Model\LotList;
use App\Model\Queue;
use App\Model\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

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
      $lot = $user->lot > 0 ? LotList::find($user->lot) : null;
      $onQueue = Queue::where('user_id', Auth::user()->id)->where('status', false)->count() ? true : false;
      $dollar = $setting->dollar;
      $lotTarget = Lot::where('user_id', $user->id)->sum('debit');
      $lotProgress = Lot::where('user_id', $user->id)->sum('credit');

      $data = [
        'user' => $user,
        'lot' => $lot,
        'onQueue' => $onQueue,
        'dollar' => $dollar,
        'lotTarget' => $lotTarget,
        'lotProgress' => $lotProgress,
      ];
      return response()->json($data, 200);
    } else {
      $data = [
        'message' => 'Unauthenticated.',
      ];
      return response()->json($data, 500);
    }
  }
}
