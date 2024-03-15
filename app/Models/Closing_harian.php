<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Closing_harian extends Model
{
  use HasFactory;

  // Fillable
  protected $table = "closing_harian";

  // protected $fillable = [
  //   'noso', 'tglso', 'nopo_customer', 'tglpo_customer', 'noreferensi', 'kdcustomer', 'nmcustomer', 'kdsales', 'nmsales', 'tglkirim',
  //   'jenis_order', 'biaya_lain', 'ket_biaya_lain', 'subtotal', 'total_sementara', 'ppn', 'materai', 'total', 'tempo', 'tgl_jt_tempo',
  //   'carabayar', 'keterangan', 'proses', 'batal', 'terima', 'user_proses', 'user'
  // ];

  protected $guarded = ['id'];
  public $timestamps = false;
}
