<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * Class LotList
 * @package App\Model
 *
 * @property string plucky
 * @property string percent
 */
class LotList extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'plucky',
    'percent',
  ];
}