<?php

namespace App\Http\Controllers;

use App\Model\Lot;
use App\Model\LotList;
use App\User;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class HomeController extends Controller
{
  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
    $this->middleware('auth');
  }

  /**
   * Show the application dashboard.
   *
   * @return Renderable
   */
  public function index()
  {
    $dataMargeLot = [0 => 0];
    foreach (LotList::all() as $item) {
      $dataMargeLot[$item->id] = 0;
    }
    foreach (User::where('suspend', 0)->orderBy('lot', 'asc')->cursor() as $item) {
      ++$dataMargeLot[$item->lot];
    }

    $graphic = Lot::whereMonth('created_at', '<=', Carbon::now())->whereMonth('created_at', '>=', Carbon::now()->addMonths(-1))->where('credit', 0)->get();
    $graphicGroup = $graphic->groupBy(function ($item) {
      return (string)Carbon::parse($item->created_at)->format('d');
    })->map(function ($item) {
      $item->upgrade = $item->count();
      $item->newUser = 0;
      foreach ($item as $subItem) {
        $item->newUser = User::whereDay('created_at', $subItem->created_at)->count();
      }
      return $item;
    });

    $data = [
      'graphicGroup' => $graphicGroup,
      'lot' => $dataMargeLot,
      'totalUser'=>User::whereDay('suspend', 0)->count(),
      'online' => DB::table('oauth_access_tokens')->where('revoked', 0)->get()->count(),
      'newUser' => User::whereDay('created_at', Carbon::now())->get()->count(),
      'totalUpgrade' => Lot::whereDay('created_at', Carbon::now())->where('credit', 0)->count()
    ];

    return view('home', $data);
  }

  /**
   * @return Application|Factory|View|int
   */
  public function onlineUserView()
  {
    $dataUser = DB::table('oauth_access_tokens')->where('revoked', 0)->get();
    $dataUser->map(function ($item) {
      $item->user = User::find($item->user_id);
    });

    $data = [
      'data' => $dataUser
    ];

    return view('onlineUser', $data);
  }

  /**
   * @return Application|Factory|View
   */
  public function newUserView()
  {
    $dataUser = User::whereDay('created_at', Carbon::now())->get();

    $data = [
      'data' => $dataUser
    ];

    return view('newUser', $data);
  }

  /**
   * @return Application|Factory|View
   */
  public function totalUpgradeView()
  {
    $dataUser = Lot::whereDay('created_at', Carbon::now())->where('credit', 0)->get();
    $dataUser->map(function ($item) {
      $item->user = User::find($item->user_id);
    });

    $data = [
      'data' => $dataUser
    ];

    return view('totalLot', $data);
  }
}
