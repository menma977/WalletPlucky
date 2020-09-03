<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Lot
 * @package App\Model
 *
 * @property integer user_id
 * @property integer from_user
 * @property string debit
 * @property string credit
 * @property integer lot
 * @property integer type
 */
class Lot extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'user_id',
    'from_user',
    'debit',
    'credit',
    'lot',
    'type',
  ];
}
