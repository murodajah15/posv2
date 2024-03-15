<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tbmultiprc extends Model
{
  use HasFactory;

  // Fillable
  protected $table = "tbmultiprc";
  protected $fillable = [
    'kdbarang', 'nmbarang', 'harga', 'discount', 'kdcustomer', 'user',
  ];
  public $timestamps = false;
}
