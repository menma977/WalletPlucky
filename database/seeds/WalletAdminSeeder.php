<?php

use App\Model\WalletAdmin;
use Illuminate\Database\Seeder;

class WalletAdminSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $data = new WalletAdmin();
    $data->wallet = "DHRDzBmt5NJtq1nkGz7rdEWVETUDWmQkKm";
    $data->save();
  }
}
