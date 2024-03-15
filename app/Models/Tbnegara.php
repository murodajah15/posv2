<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tbnegara extends Model
{
  use HasFactory;

  // Fillable
  protected $table = "tbnegara";
  protected $fillable = ['kode', 'nama', 'aktif', 'user',];
  public $timestamps = false;
}
