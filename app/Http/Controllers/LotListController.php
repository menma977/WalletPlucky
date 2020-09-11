<?php

namespace App\Http\Controllers;

use App\Model\LotList;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class LotListController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return Application|Factory|View|void
   */
  public function index()
  {
    $grade = LotList::all();

    $data = [
      'grade' => $grade
    ];

    return view('lot.index', $data);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param Request $request
   * @return RedirectResponse
   * @throws ValidationException
   */
  public function store(Request $request)
  {
    $this->validate($request, [
      'price' => 'required|numeric',
      'plucky' => 'required|numeric',
    ]);

    $grade = new LotList();
    if (strpos($request->price, ".") !== false || strpos($request->price, ",") !== false) {
      $rawBalance = str_replace(array(".", ","), "", $request->price);
      $rawBalance /= 100000000;
      $balance = number_format($rawBalance * 100000000, 0, '.', '');
    } else {
      $rawBalance = $request->price / 100000000;
      $balance = number_format($rawBalance * 100000000, 0, '.', '');
    }
    $grade->price = $balance;
    $grade->plucky = str_replace(",", ".", $request->plucky);
    $grade->save();

    return redirect()->back();
  }

  /**
   * Update the specified resource in storage.
   *
   * @param Request $request
   * @param $id
   * @return RedirectResponse
   * @throws ValidationException
   */
  public function update(Request $request, $id)
  {
    $this->validate($request, [
      'price' => 'required|numeric',
      'plucky' => 'required|numeric',
    ]);

    $grade = LotList::find($id);
    if (strpos($request->price, ".") !== false || strpos($request->price, ",") !== false) {
      $rawBalance = str_replace(array(".", ","), "", $request->price);
      $rawBalance /= 100000000;
      $balance = number_format($rawBalance * 100000000, 0, '.', '');
    } else {
      $rawBalance = $request->price / 100000000;
      $balance = number_format($rawBalance * 100000000, 0, '.', '');
    }
    $grade->price = $balance;
    $grade->plucky = str_replace(",", ".", $request->plucky);
    $grade->save();

    return redirect()->back();
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param $id
   * @return RedirectResponse
   */
  public function destroy($id)
  {
    LotList::destroy($id);
    return redirect()->back();
  }
}
