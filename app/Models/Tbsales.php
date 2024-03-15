<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tbsales extends Model
{
  use HasFactory;

  // Fillable
  protected $table = "tbsales";
  protected $fillable = [
    'kode', 'nama', 'alamat', 'kota', 'kdpos', 'telp1', 'telp2', 'keterangan', 'user',
  ];
  public $timestamps = false;
}
