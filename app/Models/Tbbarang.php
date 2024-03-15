<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tbbarang extends Model
{
  use HasFactory;

  // Fillable
  protected $table = "tbbarang";

  // protected $fillable = [
  //   'kode', 'nama', 'lokasi', 'merek', 'kdjnbrg', 'kdsatuan', 'nmsatuan', 'kdnegara', 'kdmove', 'harga_beli', 'harga_jual',
  //   'kddiscount', 'nobatch', 'stock_min', 'stock_mak', 'tglexpired', 'user',
  // ];
  protected $guarded = ['id'];
  public $timestamps = true;
}
