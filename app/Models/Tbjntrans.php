<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tbjntrans extends Model
{
  use HasFactory;

  // Fillable
  protected $table = "tbjntrans";
  protected $fillable = ['kode', 'nama', 'keterangan', 'user',];
  public $timestamps = false;
}
