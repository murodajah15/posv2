<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tbdiscount extends Model
{
  use HasFactory;

  // Fillable
  protected $table = "tbdiscount";
  protected $fillable = ['kode', 'nama', 'user',];
  public $timestamps = false;
}
