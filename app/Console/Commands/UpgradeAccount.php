<?php

namespace App\Console\Commands;

use App\Model\Lot;
use App\Model\Queue;
use App\Model\Setting;
use App\Model\WalletAdmin;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use mysql_xdevapi\Exception;

class UpgradeAccount extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'UpgradeAccount';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Command description';

  /**
   * Create a new command instance.
   *
   * @return void
   */
  public function __construct()
  {
    parent::__construct();
  }

  /**
   * Execute the console command.
   *
   * @return void
   */
  public function handle()
  {
    try {
      $data = Queue::where('status', 0)->whereDate('created_at', '<=', Carbon::now())->orderBy('id', 'asc')->get()->first();
      if ($data) {
        $user = User::find($data->user_id);
        if ($data->type == 2) {
          $sendTo = WalletAdmin::find($data->send_to);
        } elseif ($data->type == 0) {
          $sendTo = Setting::find(1);
        } else {
          $sendTo = User::find($data->send_to);
        }

        $responseGetSession = Http::asForm()->post('https://www.999doge.com/api/web.aspx', [
          'a' => 'Login',
          'Key' => '1b4755ced78e4d91bce9128b9a053cad',
          'username' => $user->doge_username,
          'password' => $user->doge_password,
          'Totp' => ''
        ]);

        if ($responseGetSession->successful() && str_contains($responseGetSession->body(), 'InvalidApiKey') == false && str_contains($responseGetSession->body(), 'LoginInvalid') == false) {
          $dataGetSession = $responseGetSession->json();
          if ($data->type == 0) {
            $response = Http::asForm()->post('https://www.999doge.com/api/web.aspx', [
              'a' => 'Withdraw',
              's' => $dataGetSession["SessionCookie"],
              'Amount' => $data->value,
              'Address' => Setting::find(1)->wallet_it,
              'Totp ' => '',
              'Currency' => 'doge',
            ]);
          } else {
            $response = Http::asForm()->post('https://www.999doge.com/api/web.aspx', [
              'a' => 'Withdraw',
              's' => $dataGetSession["SessionCookie"],
              'Amount' => $data->value,
              'Address' => $sendTo->wallet,
              'Totp ' => '',
              'Currency' => 'doge',
            ]);
          }

          Log::info($response->body());

          if ($response->successful() && str_contains($response->body(), 'Pending') == true) {
            $data->status = 1;
            $data->save();

            if ($data->send_to == 0) {
              $lot = new Lot();
              $lot->user_id = 0;
              $lot->from_user = $user->id;
              $lot->debit = 0;
              $lot->credit = $data->value;
              $lot->lot = 0;
              $lot->type = 0;
              $lot->save();
            } else if ($data->send_to == 2) {
              $lot = new Lot();
              $lot->user_id = $sendTo->id;
              $lot->from_user = $user->id;
              $lot->debit = 0;
              $lot->credit = $data->value;
              $lot->lot = 2;
              $lot->type = 2;
              $lot->save();
            } else {
              $lot = new Lot();
              $lot->user_id = $sendTo->id;
              $lot->from_user = $user->id;
              $lot->debit = 0;
              $lot->credit = $data->value;
              $lot->lot = $user->lot;
              $lot->type = 1;
              $lot->save();
            }
          } else if(str_contains($response->body(), 'InsufficientFunds') == true) {
            $data->created_at = Carbon::parse($data->created_at)->addDay()->format('Y-m-d H:i:s');
            $data->save();
          }
        }
      }
    } catch (Exception $e) {
      Log::warning($e->getMessage() . " file : " . $e->getFile() . " line : " . $e->getLine());
    }
  }
}
