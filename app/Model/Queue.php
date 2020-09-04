<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Queue
 * @package App\Model
 *
 * @property integer user_id
 * @property integer send_to
 * @property integer type
 * @property string value
 * @property boolean status
 */
class Queue extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'user_id',
    'send_to',
    'type',
    'value',
    'status'
  ];
}
