<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * Class LogBalance
 * @package App\Model
 *
 * @property integer user_id
 * @property string wallet
 * @property string balance
 */
class LogBalance extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'user_id',
    'wallet',
    'balance',
  ];
}
