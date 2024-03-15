<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tbcustomer extends Model
{
  use HasFactory;

  // Fillable
  protected $table = "tbcustomer";
  // protected $fillable = [
  //   'kode', 'kelompok', 'nama', 'alamat', 'kota', 'kodepos', 'telp1', 'telp2', 'agama', 'tgl_lahir',
  //   'alamat_ktr', 'kota_ktr', 'kodepos_ktr', 'telp1_ktr', 'telp2_ktr', 'npwp', 'alamat_npwp', 'nama_npwp', 'alamat_ktp',
  //   'kota_ktp', 'kodepos_ktp', 'contact_person_rmh', 'mak_piutang', 'kdklpcust', 'nmklpcust', 'tgl_register', 'tempo', 'user',
  // ];

  protected $guarded = ['id'];
  public $timestamps = false;
}
