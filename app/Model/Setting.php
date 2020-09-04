<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Setting
 * @package App\Model
 *
 * @property integer version
 * @property integer maintenance
 * @property double fee
 * @property integer target_lot
 * @property string wallet_it
 * @property string key_doge
 * @property string dollar
 */
class Setting extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'version',
    'maintenance',
    'fee',
    'target_lot',
    'wallet_it',
    'key_doge',
  ];
}
