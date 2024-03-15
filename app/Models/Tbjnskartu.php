<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tbjnskartu extends Model
{
  use HasFactory;

  // Fillable
  protected $table = "tbjnskartu";
  protected $fillable = ['kode', 'nama', 'aktif', 'user',];
  public $timestamps = false;
}
