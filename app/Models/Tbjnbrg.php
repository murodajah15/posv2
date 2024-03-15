<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tbjnbrg extends Model
{
  use HasFactory;

  // Fillable
  protected $table = "tbjnbrg";
  protected $fillable = ['kode', 'nama', 'user',];
  public $timestamps = false;
}
