<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\SaplikasiRequest;
use Illuminate\Http\Request;
use Session;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Saplikasi;
use App\Models\Userdtl;

//return type View
use Illuminate\View\View;

class SaplikasiController extends Controller
{
  public function index(Request $request) //: View
  {
    $username = session('username');
    $data = [
      'menu' => 'utility',
      'submenu' => 'saplikasi',
      'submenu1' => 'ref_umum',
      'title' => 'Setup Aplikasi',
      // 'saplikasi' => Saplikasi::all(),
      'userdtlmenu' => Userdtl::join('tbmodule', 'userdtl.cmodule', '=', 'tbmodule.cmodule')->where('userdtl.pakai', '1')->where('username', $username)->orderBy('userdtl.nurut')->get(),
      'userdtl' => Userdtl::where('cmodule', 'Setup Aplikasi')->where('username', $username)->first(),
    ];
    return view('saplikasi.index')->with($data);
  }
  public function saplikasiajax(Request $request) //: View
  {
    if ($request->ajax()) {
      $data = Saplikasi::select('*'); //->orderBy('cmodule', 'asc');
      return Datatables::of($data)
        ->addIndexColumn()
        ->addColumn('cmodule1', function ($row) {
          $id = $row['id'];
          $btn = $row['cmodule']; //'<a href="#"' . 'onclick=detail(' . $id . ')>' .  $row['cmodule'] . '</a>';
          return $btn;
        })
        ->rawColumns(['cmodule1'])
        // ->addIndexColumn()
        // ->addColumn('action', function ($row) {
        //     $btn = '<a href="javascript:void(0)" class="edit btn btn-primary btn-sm">View</a>';
        //     return $btn;
        // })
        // ->rawColumns(['action'])
        ->make(true);
      return view('saplikasi');
    }
  }

  public function create()
  {
    // if ($this->request->isAjax()) {
    $data = [
      'menu' => 'utility',
      'submenu' => 'saplikasi',
      'submenu1' => 'ref_umum',
      'title' => 'Tambah Data Setup Aplikasi',
      // 'saplikasi' => Saplikasi::all(),
    ];
    // var_dump($data);
    return response()->json([
      'body' => view('saplikasi.modaltambah', [
        'saplikasi' => new saplikasi(), //Saplikasi::first(),
        'action' => route('saplikasi.store'),
        'vdata' => $data,
      ])->render(),
      'data' => $data,

    ]);
    // } else {
    //     exit('Maaf tidak dapat diproses');
    // }
  }

  public function store(SaplikasiRequest $request)
  {
    $saplikasi = new saplikasi();
    $validated = $request->validate(
      [
        'kd_perusahaan' => 'required|unique:saplikasi,kd_perusahaan',
        'nm_perusahaan' => 'required',
      ],
      [
        'kd_perusahaan.unique' => 'Kode Perusahaan tidak boleh sama',
        'kd_perusahaan.required' => 'Kode Perusahaan harus di isi',
        'nm_perusahaan.required' => 'Nama Perusahaan harus di isi',
      ]
    );
    if ($validated) {
      $saplikasi = new saplikasi();
      $aktif = $request->aktif == 'on' ? 'Y' : 'N';
      $kunci_harga_jual = $request->kunci_harga_jual == 'on' ? 'Y' : 'N';
      $kunci_stock = $request->kunci_stock == 'on' ? 'Y' : 'N';
      $llogo = $request->llogo == 'on' ? 'Y' : 'N';
      if ($request->hasFile('logo')) {
        $path = $request->file('logo')->store('uploads');
        $filelogo = $request->file('logo')->store('');
      } else {
        $path = '';
      }
      $saplikasi->fill([
        'kd_perusahaan' => $request->kd_perusahaan,
        'nm_perusahaan' => $request->nm_perusahaan,
        'telp' => is_null($request->telp) ? '' :  $request->telp,
        'npwp' => is_null($request->npwp) ? '' :  $request->npwp,
        'alamat' => is_null($request->alamat) ? '' :  $request->alamat,
        'llogo' => $llogo,
        'logo' => $filelogo,
        'pejabat_1' => is_null($request->pejabat_1) ? '' :  $request->pejabat_1,
        'pejabat_2' => is_null($request->pejabat_2) ? '' :  $request->pejabat_2,
        'nm_sistem' => is_null($request->nm_sistem) ? '' :  $request->nm_sistem,
        'jenis_hpp' => is_null($request->jenis_hpp) ? '' :  $request->jenis_hpp,
        'direktur' => is_null($request->direktur) ? '' :  $request->direktur,
        'finance_mgr' => is_null($request->finance_mgr) ? '' :  $request->finance_mgr,
        'norek1' => is_null($request->norek1) ? '' :  $request->norek1,
        'norek2' => is_null($request->norek2) ? '' :  $request->norek2,
        'tahun' => is_null($request->tahun) ? '' :  $request->tahun,
        'bulan' => is_null($request->bulan) ? '' :  $request->bulan,
        'tgl_berikutnya' => is_null($request->tgl_berikutnya) ? '' :  $request->tgl_berikutnya,
        'noso' => is_null($request->noso) ? '' :  $request->noso,
        'nojual' => is_null($request->nojual) ? '' :  $request->nojual,
        'nopo' => is_null($request->nopo) ? '' :  $request->nopo,
        'nobeli' => is_null($request->nobeli) ? '' :  $request->nobeli,
        'noterima' => is_null($request->noterima) ? '' :  $request->noterima,
        'nokeluar' => is_null($request->nokeluar) ? '' :  $request->nokeluar,
        'noopname' => is_null($request->noopname) ? '' :  $request->noopname,
        'noapprov' => is_null($request->noapprov) ? '' :  $request->noapprov,
        'nokwtunai' => is_null($request->nokwtunai) ? '' :  $request->nokwtunai,
        'nokwtagihan' => is_null($request->nokwtagihan) ? '' :  $request->nokwtagihan,
        'nomohon' => is_null($request->nomohon) ? '' :  $request->nomohon,
        'nokwkeluar' => is_null($request->nokwkeluar) ? '' :  $request->nokwkeluar,
        'nosrtjln' => is_null($request->nosrtjln) ? '' :  $request->nosrtjln,
        'dirbackup' => is_null($request->dirbackup) ? '' :  $request->dirbackup,
        'kunci_harga_jual' => $kunci_harga_jual,
        'kunci_stock' => $kunci_stock,
        'ppn' => is_null($request->ppn) ? '' :  $request->ppn,
        'closing_hpp' => is_null($request->closing_hpp) ? '' :  $request->closing_hpp,
        'aktif' => $aktif,
        'user' => 'Tambah-' . $request->username . ', ' . date('d-m-Y h:i:s'),
      ]);
      $saplikasi->save($validated);
      $msg = [
        'sukses' => 'Data berhasil di tambah', //view('saplikasi.tabel_module')
      ];
    }
    echo json_encode($msg);

    // return redirect()->back()->with('message', 'Berhasil di simpan');
  }

  // public function show(string $id)
  public function show()
  {

    $id = $_GET['id'];
    // // if ($this->request->isAjax()) {
    $username = session('username');
    $data = [
      'menu' => 'utility',
      'submenu' => 'saplikasi',
      'submenu1' => 'ref_umum',
      'title' => 'Detail Setup Aplikasi',
      'saplikasi' => Saplikasi::findOrFail($id),
      'userdtl' => Userdtl::where('cmodule', 'Setup Aplikasi')->where('username', $username)->first(),
    ];
    // return view('saplikasi.modaltambah')->with($data);
    return response()->json([
      'body' => view('saplikasi.modaltambah', [
        'saplikasi' => Saplikasi::findOrFail($id),
        'action' => route('saplikasi.store'),
        'vdata' => $data,
      ])->render(),
      'data' => $data,
    ]);
    // // } else {
    // //     exit('Maaf tidak dapat diproses');
    // // }
  }

  public function edit(saplikasi $saplikasi)
  {
    // if ($this->request->isAjax()) {
    $data = [
      'menu' => 'utility',
      'submenu' => 'saplikasi',
      'submenu1' => 'ref_umum',
      'title' => 'Edit Data Setup Aplikasi',
    ];
    // var_dump($data);

    // return response()->json([
    //     'data' => $data,
    // ]);
    return response()->json([
      'body' => view('saplikasi.modaltambah', [
        'saplikasi' => $saplikasi,
        'action' => route('saplikasi.update', $saplikasi->id),
        'vdata' => $data,
      ])->render(),
      'data' => $data,
    ]);
    // } else {
    //     exit('Maaf tidak dapat diproses');
    // }
  }

  public function update(SaplikasiRequest $request, saplikasi $saplikasi)
  {
    $validated = $request->validated();
    if ($validated) {
      $aktif = $request->aktif == 'on' ? 'Y' : 'N';
      $kunci_harga_jual = $request->kunci_harga_jual == 'on' ? 'Y' : 'N';
      $kunci_stock = $request->kunci_stock == 'on' ? 'Y' : 'N';
      $llogo = $request->llogo == 'on' ? 'Y' : 'N';
      if ($request->hasFile('logo')) {
        $path = $request->file('logo')->store('uploads');
        $filelogo = $request->file('logo')->store('');
        $saplikasi->fill([
          'kd_perusahaan' => $request->kd_perusahaan,
          'nm_perusahaan' => $request->nm_perusahaan,
          'telp' => is_null($request->telp) ? '' :  $request->telp,
          'npwp' => is_null($request->npwp) ? '' :  $request->npwp,
          'alamat' => is_null($request->alamat) ? '' :  $request->alamat,
          'llogo' => $llogo,
          'logo' => $filelogo,
          'pejabat_1' => is_null($request->pejabat_1) ? '' :  $request->pejabat_1,
          'pejabat_2' => is_null($request->pejabat_2) ? '' :  $request->pejabat_2,
          'nm_sistem' => is_null($request->nm_sistem) ? '' :  $request->nm_sistem,
          'jenis_hpp' => is_null($request->jenis_hpp) ? '' :  $request->jenis_hpp,
          'direktur' => is_null($request->direktur) ? '' :  $request->direktur,
          'finance_mgr' => is_null($request->finance_mgr) ? '' :  $request->finance_mgr,
          'norek1' => is_null($request->norek1) ? '' :  $request->norek1,
          'norek2' => is_null($request->norek2) ? '' :  $request->norek2,
          'tahun' => is_null($request->tahun) ? '' :  $request->tahun,
          'bulan' => is_null($request->bulan) ? '' :  $request->bulan,
          'tgl_berikutnya' => is_null($request->tgl_berikutnya) ? '' :  $request->tgl_berikutnya,
          'noso' => is_null($request->noso) ? '' :  $request->noso,
          'nojual' => is_null($request->nojual) ? '' :  $request->nojual,
          'nopo' => is_null($request->nopo) ? '' :  $request->nopo,
          'nobeli' => is_null($request->nobeli) ? '' :  $request->nobeli,
          'noterima' => is_null($request->noterima) ? '' :  $request->noterima,
          'nokeluar' => is_null($request->nokeluar) ? '' :  $request->nokeluar,
          'noopname' => is_null($request->noopname) ? '' :  $request->noopname,
          'noapprov' => is_null($request->noapprov) ? '' :  $request->noapprov,
          'nokwtunai' => is_null($request->nokwtunai) ? '' :  $request->nokwtunai,
          'nokwtagihan' => is_null($request->nokwtagihan) ? '' :  $request->nokwtagihan,
          'nomohon' => is_null($request->nomohon) ? '' :  $request->nomohon,
          'nokwkeluar' => is_null($request->nokwkeluar) ? '' :  $request->nokwkeluar,
          'nosrtjln' => is_null($request->nosrtjln) ? '' :  $request->nosrtjln,
          'dirbackup' => is_null($request->dirbackup) ? '' :  $request->dirbackup,
          'kunci_harga_jual' => $kunci_harga_jual,
          'kunci_stock' => $kunci_stock,
          'ppn' => is_null($request->ppn) ? '' :  $request->ppn,
          'closing_hpp' => is_null($request->closing_hpp) ? '' :  $request->closing_hpp,
          'aktif' => $aktif,
          'user' => 'Update-' . $request->username . ', ' . date('d-m-Y h:i:s'),
        ]);
        $filelama = 'uploads/' . $request->logolama;
        if (($filelama != null || $filelama != '') and $filelama <> $path) {
          // if (($filelama != null || $filelama != '') and $filelama <> $filelogo) {
          File::delete($filelogo);
          Storage::delete($filelogo);
          Storage::delete($filelama);
          // var_dump($filelama . ' ---- ' . $filelogo);
        }
        // $img = $request->file('logo');
        // $path = public_path('\img\uploads');
        // var_dump($path);
        // $img->move($path, $filelogo);
      } else {
        $saplikasi->fill([
          'kd_perusahaan' => $request->kd_perusahaan,
          'nm_perusahaan' => $request->nm_perusahaan,
          'telp' => is_null($request->telp) ? '' :  $request->telp,
          'npwp' => is_null($request->npwp) ? '' :  $request->npwp,
          'alamat' => is_null($request->alamat) ? '' :  $request->alamat,
          'llogo' => $llogo,
          'pejabat_1' => is_null($request->pejabat_1) ? '' :  $request->pejabat_1,
          'pejabat_2' => is_null($request->pejabat_2) ? '' :  $request->pejabat_2,
          'nm_sistem' => is_null($request->nm_sistem) ? '' :  $request->nm_sistem,
          'jenis_hpp' => is_null($request->jenis_hpp) ? '' :  $request->jenis_hpp,
          'direktur' => is_null($request->direktur) ? '' :  $request->direktur,
          'finance_mgr' => is_null($request->finance_mgr) ? '' :  $request->finance_mgr,
          'norek1' => is_null($request->norek1) ? '' :  $request->norek1,
          'norek2' => is_null($request->norek2) ? '' :  $request->norek2,
          'tahun' => is_null($request->tahun) ? '' :  $request->tahun,
          'bulan' => is_null($request->bulan) ? '' :  $request->bulan,
          'tgl_berikutnya' => is_null($request->tgl_berikutnya) ? '' :  $request->tgl_berikutnya,
          'noso' => is_null($request->noso) ? '' :  $request->noso,
          'nojual' => is_null($request->nojual) ? '' :  $request->nojual,
          'nopo' => is_null($request->nopo) ? '' :  $request->nopo,
          'nobeli' => is_null($request->nobeli) ? '' :  $request->nobeli,
          'noterima' => is_null($request->noterima) ? '' :  $request->noterima,
          'nokeluar' => is_null($request->nokeluar) ? '' :  $request->nokeluar,
          'noopname' => is_null($request->noopname) ? '' :  $request->noopname,
          'noapprov' => is_null($request->noapprov) ? '' :  $request->noapprov,
          'nokwtunai' => is_null($request->nokwtunai) ? '' :  $request->nokwtunai,
          'nokwtagihan' => is_null($request->nokwtagihan) ? '' :  $request->nokwtagihan,
          'nomohon' => is_null($request->nomohon) ? '' :  $request->nomohon,
          'nokwkeluar' => is_null($request->nokwkeluar) ? '' :  $request->nokwkeluar,
          'nosrtjln' => is_null($request->nosrtjln) ? '' :  $request->nosrtjln,
          'dirbackup' => is_null($request->dirbackup) ? '' :  $request->dirbackup,
          'kunci_harga_jual' => $kunci_harga_jual,
          'kunci_stock' => $kunci_stock,
          'ppn' => is_null($request->ppn) ? '' :  $request->ppn,
          'closing_hpp' => is_null($request->closing_hpp) ? '' :  $request->closing_hpp,
          'aktif' => $aktif,
          'user' => 'Update-' . $request->username . ', ' . date('d-m-Y h:i:s'),
        ]);
      }
      $saplikasi->save($validated);
      $saplikasi = Saplikasi::where('aktif', 'Y')->first();
      session(['nm_perusahaan1' => $saplikasi['nm_perusahaan1']]);
      session(['nm_perusahaan' => $saplikasi['nm_perusahaan']]);
      session(['alamat_perusahaan' => $saplikasi['alamat']]);
      session(['telp_perusahaan' => $saplikasi['telp']]);
      session(['lppn' => $saplikasi['lppn']]);
      session(['ppn' => $saplikasi['ppn']]);
      session(['norek1' => $saplikasi['norek1']]);
      session(['norek2' => $saplikasi['norek2']]);
      session(['llogo' => $saplikasi['llogo']]);
      session(['logo' => $saplikasi['logo']]);
      $msg = [
        'sukses' => 'Data berhasil di update', //view('saplikasi.tabel_module')
      ];
    } else {
      $msg = [
        'sukses' => 'Data gagal di update', //view('saplikasi.tabel_module')
      ];
    }
    echo json_encode($msg);
    // return redirect()->back()->with('message', 'Berhasil di update');
  }

  public function destroy(saplikasi $saplikasi)
  {
    $id = $_POST['id'];
    $aplikasi = Saplikasi::findOrFail($id);
    $pathlogo = $aplikasi->logo;
    if ($pathlogo != null || $pathlogo != '') {
      Storage::delete($pathlogo);
    }
    $saplikasi->delete();
    return response()->json([
      'sukses' => 'Data berhasil di hapus',
    ]);
    // return redirect()->back()->with('message', 'Berhasil di hapus');
  }
}
