<?php

use App\Model\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $data = new Setting();
    $data->version = 1;
    $data->fee = 1.0;
    $data->target_lot = 1;
    $data->wallet_it = "DJuuiaAWWoCC2DhU5pfht6omqPFsyH2j3i";
    $data->key_doge = "1b4755ced78e4d91bce9128b9a053cad";
    $data->save();
  }
}
