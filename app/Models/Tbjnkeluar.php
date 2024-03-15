<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tbjnkeluar extends Model
{
  use HasFactory;

  // Fillable
  protected $table = "tbjnkeluar";
  protected $fillable = ['kode', 'nama', 'user',];
  public $timestamps = false;
}
