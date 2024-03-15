<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tbklpuser extends Model
{
  public $table = "tbklpuser";
  use HasFactory;

  // Fillable

  protected $fillable = ['id',];
}
