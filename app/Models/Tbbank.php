<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tbbank extends Model
{
  use HasFactory;

  // Fillable
  protected $table = "tbbank";
  protected $fillable = ['kode', 'nama', 'aktif', 'user',];
  public $timestamps = false;
}
