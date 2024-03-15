<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tbklpcust extends Model
{
  use HasFactory;

  // Fillable
  protected $table = "tbklpcust";
  protected $fillable = ['kode', 'nama', 'user',];
  public $timestamps = false;
}
