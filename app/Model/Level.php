<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Level
 * @package App\Model
 *
 * @property double percent
 */
class Level extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'percent',
  ];
}
