<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Model\Setting;

class SettingController extends Controller
{
  public function index()
  {
    $setting = Setting::find(1);

    $data = [
      'setting' => $setting
    ];

    return response()->json($data, 200);
  }
}
