<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Saplikasi extends Model
{
  use HasFactory;

  // Fillable
  protected $table = "saplikasi";
  // protected $fillable = [
  //   'kd_perusahaan', 'nm_perusahaan', 'alamat', 'telp', 'npwp', 'llogo', 'logo', 'pejabat_1', 'pejabat_2', 'nm_sistem', 'jenis_hpp', 'tgl_closing',
  //   'user_closing', 'tgl_berikutnya', 'closing_hpp', 'direktur', 'finance_mgr', 'norek1', 'norek2', 'tahun', 'bulan', 'noso', 'nojual',
  //   'nopo', 'nobeli', 'noterima', 'nokeluar', 'noopname', 'noapprov', 'nokwtunai', 'nokwtagihan', 'nomohon', 'nokwkeluar', 'nosrtjln',
  //   'dirbackup', 'kunci_harga_jual', 'kunci_stock', 'ppn', 'aktif', 'user'
  // ];
  protected $guarded = ['id'];
  public $timestamps = false;
}
