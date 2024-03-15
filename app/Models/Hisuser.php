<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hisuser extends Model
{
  use HasFactory;

  // Fillable
  protected $table = "hisuser";
  protected $fillable = [
    'datetime', 'tanggal', 'dokumen', 'form', 'status', 'user'
  ];
  public $timestamps = false;
}
