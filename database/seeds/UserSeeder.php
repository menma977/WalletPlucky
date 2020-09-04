<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $data = new User();
    $data->role = 1;
    $data->username = "plucky";
    $data->email = "admin@dogearn.com";
    $data->phone = "6200000000000";
    $data->password = Hash::make("arif999999");
    $data->password_junk = "arif999999";
    $data->secondary_password = Hash::make("1234");
    $data->secondary_password_junk = "1234";
    $data->wallet = "DHRDzBmt5NJtq1nkGz7rdEWVETUDWmQkKm";
    $data->doge_username = "arn2";
    $data->doge_password = "arif999999";
    $data->save();
  }
}
