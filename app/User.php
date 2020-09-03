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
 * @property string password
 * @property string password_junk
 * @property string secondary_password
 * @property string wallet
 * @property string doge_username
 * @property string doge_password
 * @property integer lot
 * @property integer suspend
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
    'password',
    'password_junk',
    'secondary_password',
    'wallet',
    'doge_username',
    'doge_password',
    'lot',
    'suspend',
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
