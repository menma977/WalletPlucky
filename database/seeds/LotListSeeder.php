<?php

use App\Model\LotList;
use Illuminate\Database\Seeder;

class LotListSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $data = new LotList();
    $data->price = 500000000000;
    $data->plucky = 500000000000;
    $data->save();

    $data = new LotList();
    $data->price = 1000000000000;
    $data->plucky = 1000000000000;
    $data->save();

    $data = new LotList();
    $data->price = 2000000000000;
    $data->plucky = 2000000000000;
    $data->save();

    $data = new LotList();
    $data->price = 4000000000000;
    $data->plucky = 4000000000000;
    $data->save();

    $data = new LotList();
    $data->price = 8000000000000;
    $data->plucky = 8000000000000;
    $data->save();
  }
}
