<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keluard extends Model
{
  use HasFactory;

  // Fillable
  protected $table = "keluard";

  // protected $fillable = [
  //   'kdbarang', 'nmbarang', 'kdsatuan', 'qty', 'harga', 'discount', 'subtotal', 'noso', 'Keluar', 'kurang', 'proses', 'user'
  // ];

  protected $guarded = ['id'];
  public $timestamps = false;
}
