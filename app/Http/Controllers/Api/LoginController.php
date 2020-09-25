<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Model\Setting;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
  /**
   * @param Request $request
   * @return JsonResponse
   * @throws ValidationException
   */
  public function index(Request $request)
  {
    $this->validate($request, [
      'username' => 'required|string|exists:users,username',
      'password' => 'required|string',
    ]);
    try {
      if (Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
        foreach (Auth::user()->tokens as $key => $value) {
          $value->delete();
        }
        $user = Auth::user();
        if ($user) {
          if ($user->suspend) {
            $data = [
              'message' => 'Your account has been suspended.',
            ];
            return response()->json($data, 500);
          } else if (!$user->is_password_ready) {
            $data = [
              'message' => 'Your Account is not ready.',
            ];
            return response()->json($data, 500);
          } else if (!$user->is_secondary_password_ready) {
            $data = [
              'message' => 'Your Account is not ready.',
            ];
            return response()->json($data, 500);
          } else if (Setting::find(1)->maintenance) {
            $data = [
              'message' => 'Under Maintenance.',
            ];
            return response()->json($data, 500);
          } else {
            $user->token = $user->createToken('Android')->accessToken;
            return response()->json([
              'token' => $user->token,
              'wallet' => $user->wallet,
              'phone' => $user->phone,
              'username' => $user->username,
              'password' => $user->password_junk,
              'usernameDoge' => $user->doge_username,
              'passwordDoge' => $user->doge_password
            ], 200);
          }
        } else {
          $data = [
            'message' => 'Invalid username or password.',
          ];
          return response()->json($data, 500);
        }
      } else {
        $data = [
          'message' => 'Invalid username or password.',
        ];
        return response()->json($data, 500);
      }
    } catch (Exception $exception) {
      Log::error($exception->getMessage() . " - " . $exception->getFile() . " - " . $exception->getLine());
    }
    $data = [
      'message' => 'There was an error processing the data.',
    ];
    return response()->json($data, 500);
  }

  /**
   * @return JsonResponse
   */
  public function show()
  {
    $data = [
      'version' => Setting::find(1)->version,
    ];

    return response()->json($data, 200);
  }
}
