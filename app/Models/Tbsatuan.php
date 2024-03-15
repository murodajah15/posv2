<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tbsatuan extends Model
{
  use HasFactory;

  // Fillable
  protected $table = "tbsatuan";
  protected $fillable = ['kode', 'nama', 'user',];
  public $timestamps = false;
}
