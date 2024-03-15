<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tbagama extends Model
{
  use HasFactory;

  // Fillable
  protected $table = "tbagama";
  protected $fillable = ['nama', 'user',];
  public $timestamps = false;
}
