<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLotsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('lots', function (Blueprint $table) {
      $table->id();
      $table->integer('user_id');
      $table->integer('from_user');
      $table->string('debit')->default(0);
      $table->string('credit')->default(0);
      $table->integer('lot');
      $table->integer('type');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('lots');
  }
}
