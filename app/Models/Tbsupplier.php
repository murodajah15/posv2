<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tbsupplier extends Model
{
  use HasFactory;

  // Fillable
  protected $table = "tbsupplier";
  // protected $fillable = [
  //   'kode', 'nama', 'alamat', 'kota', 'kodepos', 'telp1', 'telp2', 'contact_person', 'npwp', 'user',
  // ];
  // public $timestamps = true;
  protected $guarded = ['id'];
  public $timestamps = false;
}
