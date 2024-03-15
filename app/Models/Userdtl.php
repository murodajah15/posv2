<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Userdtl extends Model
{
    public $table = "userdtl";
    use HasFactory;

    // Fillable

    protected $fillable = [
        'iduser', 'idmodule', 'username', 'cmodule', 'clain', 'cmenu', 'cparent', 'cmainmenu', 'nlevel', 'nurut', 'pakai', 'tambah', 'edit', 'hapus', 'cetak', 'proses', 'unproses',
    ];
    public $timestamps = false;


    public function delete_by_username($username)
    {
        // dd($username);
        $this->where('username', $username);
        $query = $this->delete();
        return $query;
    }

    public function updateuserdtl($data, $cmodule, $username)
    {
        // dd($username);
        session()->setFlashdata('pesan', 'Data berhasil diubah');
        $update_query = $this->db->table($this->table)->update($data, array('cmodule' => $cmodule, 'username' => $username));
        // $update_query = $this->update($data, array('cmodule' => $cmodule, 'username' => $username));
        return $update_query;
    }
}
