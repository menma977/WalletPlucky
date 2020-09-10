<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('users', function (Blueprint $table) {
      $table->id();
      $table->integer('role')->default(2);
      $table->string('username')->unique();
      $table->string('phone')->nullable();
      $table->string('email')->nullable();
      $table->timestamp('email_verified_at')->nullable();
      $table->string('password')->nullable();
      $table->string('password_junk')->nullable();
      $table->string('secondary_password')->nullable();
      $table->string('secondary_password_junk')->nullable();
      $table->string('doge_username')->nullable();
      $table->string('doge_password')->nullable();
      $table->string('wallet')->nullable();
      $table->integer('lot')->default(0);
      $table->boolean('suspend')->default(false);
      $table->boolean('is_password_ready')->default(false);
      $table->boolean('is_secondary_password_ready')->default(false);
      $table->string('date_trade')->nullable();
      $table->rememberToken();
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
    Schema::dropIfExists('users');
  }
}
