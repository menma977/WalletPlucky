<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

/**
 * Class User
 * @package App
 *
 * @property integer role
 * @property string username
 * @property string email
 * @property string phone
 * @property string password
 * @property string password_junk
 * @property string secondary_password
 * @property string secondary_password_junk
 * @property string wallet
 * @property string doge_username
 * @property string doge_password
 * @property integer lot
 * @property boolean suspend
 * @property boolean is_password_ready
 * @property boolean is_secondary_password_ready
 * @property string date_trade
 */
class User extends Authenticatable
{
  use Notifiable, HasApiTokens;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'role',
    'username',
    'email',
    'phone',
    'password',
    'password_junk',
    'secondary_password',
    'secondary_password_junk',
    'wallet',
    'doge_username',
    'doge_password',
    'lot',
    'suspend',
    'is_password_ready',
    'is_secondary_password_ready',
    'date_trade'
  ];

  /**
   * The attributes that should be hidden for arrays.
   *
   * @var array
   */
  protected $hidden = [
    'password',
    'password_junk',
    'secondary_password',
    'secondary_password_junk',
    'doge_username',
    'doge_password',
    'remember_token',
  ];

  /**
   * The attributes that should be cast to native types.
   *
   * @var array
   */
  protected $casts = [
    'email_verified_at' => 'datetime',
  ];
}
