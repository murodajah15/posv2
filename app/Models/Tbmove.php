<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tbmove extends Model
{
  use HasFactory;

  // Fillable
  protected $table = "tbmove";
  protected $fillable = ['kode', 'nama', 'user',];
  public $timestamps = false;
}
