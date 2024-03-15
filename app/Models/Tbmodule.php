<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tbmodule extends Model
{
  use HasFactory;

  // Fillable
  protected $table = "tbmodule";
  protected $fillable = ['cmodule', 'cmenu', 'nlevel', 'cmainmenu', 'nurut', 'clain', 'cparent', 'aktif'];
  public $timestamps = false;
}
