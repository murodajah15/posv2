<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tbgudang extends Model
{
  use HasFactory;

  // Fillable
  protected $table = "tbgudang";
  protected $fillable = ['kode', 'nama', 'user',];
  public $timestamps = false;
}
