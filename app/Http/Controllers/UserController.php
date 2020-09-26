<?php

namespace App\Http\Controllers;

use App\Model\LogBalance;
use App\Model\Lot;
use App\Model\Queue;
use App\User;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class UserController extends Controller
{
  /**
   * @return Application|Factory|View
   */
  public function index()
  {
    $users = User::paginate(50);

    $data = [
      'users' => $users
    ];

    return view('user.index', $data);
  }

  /**
   * @param Request $request
   * @return Application|Factory|RedirectResponse|View
   * @throws ValidationException
   */
  public function find(Request $request)
  {
    $this->validate($request, [
      'username' => 'required|string',
    ]);

    $user = User::where('username', $request->username)->first();
    if ($user) {
      return redirect()->route('user.show', $user->id);
    }
    $user = User::where('email', $request->username)->first();
    if ($user) {
      return redirect()->route('user.show', $user->id);
    }
    $user = User::where('wallet', $request->username)->first();
    if ($user) {
      return redirect()->route('user.show', $user->id);
    }

    return redirect::back()->withErrors([
      'error' => 'undefined Username/Wallet'
    ]);
  }

  /**
   * @param $id
   * @param $status
   * @return RedirectResponse
   */
  public function suspend($id, $status)
  {
    $user = User::find($id);
    $user->suspend = $status;
    $user->save();

    return redirect()->back();
  }

  /**
   * @param $id
   * @return RedirectResponse
   */
  public function activate($id)
  {
    $user = User::find($id);
    $user->status = 2;
    $user->save();

    return redirect()->back();
  }

  /**
   * @param $id
   * @return Application|Factory|View
   */
  public function show($id)
  {
    $user = User::find($id);
    $onQueue = Queue::where('user_id', $user->id)->where('status', 0)->count();
    $logBalance = LogBalance::where('user_id', $user->id)->take(100)->get()->groupBy(function ($item) {
      return Carbon::parse($item->created_at)->format('d-m-Y');
    });

    $sponsorLine = $user->email;

    $data = [
      'user' => $user,
      'gradeTarget' => Lot::where('user_id', $id)->sum("debit"),
      'progressGrade' => Lot::where('user_id', $id)->sum("credit"),
      'onQueue' => $onQueue,
      'sponsorLine' => $sponsorLine,
      'logBalance' => $logBalance
    ];

    return view('user.show', $data);
  }

  /**
   * @param $id
   * @return Lot
   */
  public function lotList($id)
  {
    $gradeHistory = Lot::where('user_id', $id)->take(50)->orderBy('id', 'desc')->get();
    $gradeHistory->map(function ($item) {
      if ($item->user_id == 0) {
        $item->email = "Network Fee";
        $item->lot = "Network";
      } else {
        $item->email = User::find($item->from_user)->email;
        $item->lot = User::find($item->from_user)->lot;
      }

      $item->date = Carbon::parse($item->created_at)->format('d-M-Y H:i:s');
    });

    return $gradeHistory;
  }

  /**
   * @param $id
   * @return RedirectResponse
   */
  public function deleteBot($id)
  {
    $user = User::find($id);
    $user->date_trade = null;
    $user->save();

    return redirect('user/show/'.$id);
  }
}
