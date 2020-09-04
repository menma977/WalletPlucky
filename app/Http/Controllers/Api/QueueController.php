<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Model\Queue;
use Illuminate\Support\Facades\Auth;

class QueueController extends Controller
{
  public function index()
  {
    $queue = Queue::where('user_id', Auth::user()->id)->get();

    $data = [
      'queueList' => $queue
    ];

    return response()->json($data, 200);
  }
}
