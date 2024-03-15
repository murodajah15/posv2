<?php

namespace App\Http\Controllers;

use App\Http\Requests\TbsalesRequest;
use Illuminate\Http\Request;
use Session;
// use Yajra\DataTables\Contracts\DataTables;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Tbsales;
use App\Models\Soh;
use App\Models\Jualh;
use App\Models\Userdtl;
use Illuminate\Support\Facades\DB;

// //return type View
// use Illuminate\View\View;

class TbsalesController extends Controller
{
  public function index() //: View
  {
    $username = session('username');
    $data = [
      'menu' => 'file',
      'submenu' => 'tbsales',
      'submenu1' => 'ref_umum',
      'title' => 'Tabel Sales',
      // 'tbsales' => Tbsales::all(),
      'userdtlmenu' => Userdtl::join('tbmodule', 'userdtl.cmodule', '=', 'tbmodule.cmodule')->where('userdtl.pakai', '1')->where('username', $username)->orderBy('userdtl.nurut')->get(),
      'userdtl' => Userdtl::where('cmodule', 'Tabel Sales')->where('username', $username)->first(),
    ];
    $userdtl = Userdtl::where('cmodule', 'Tabel Sales')->where('username', $username)->first();
    if ($userdtl->pakai == '1') {
      return view('tbsales.index')->with($data);
    } else {
      return redirect('home');
    }
  }
  public function tbsalesajax(Request $request) //: View
  {
    if ($request->ajax()) {
      $data = Tbsales::select('*'); //->orderBy('kode', 'asc');
      return Datatables::of($data)
        ->addIndexColumn()
        ->addColumn('kode1', function ($row) {
          $id = $row['id'];
          $btn = $row['kode']; //'<a href="#"' . 'onclick=detail(' . $id . ')>' .  $row['kode'] . '</a>';
          return $btn;
        })
        ->rawColumns(['kode1'])
        ->make(true);
      return view('tbsales');
    }
  }

  public function tabel_sales(Request $request)
  {
    $data = [
      'menu' => 'file',
      'submenu' => 'tbsales',
      'submenu1' => 'ref_umum',
      'title' => 'Tabel Sales',
      'tbsales' => Tbsales::orderBy('kode', 'asc')->get(),
    ];
    // var_dump($data);
    return view('tbsales.tabel_sales')->with($data);
  }

  public function create(Request $request)
  {
    if ($request->Ajax()) {
      $data = [
        'menu' => 'file',
        'submenu' => 'tbsales',
        'submenu1' => 'ref_umum',
        'title' => 'Tambah Data Tabel sales',
      ];
      return response()->json([
        'body' => view('tbsales.modaltambah', [
          'tbsales' => new Tbsales(),
          'action' => route('tbsales.store'),
          'vdata' => $data,
        ])->render(),
        'data' => $data,

      ]);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function store(TbsalesRequest $request, Tbsales $tbsales)
  {
    if ($request->Ajax()) {
      $validated = $request->validated();
      if ($validated) {
        $tbsales->fill([
          'nama' => isset($request->nama) ? $request->nama : '',
          'kode' => isset($request->kode) ? $request->kode : '',
          'alamat' => isset($request->alamat) ? $request->alamat : '',
          'kota' => isset($request->kota) ? $request->kota : '',
          'kodepos' => isset($request->kodepos) ? $request->kodepos : '',
          'telp1' => isset($request->telp1) ? $request->telp1 : '',
          'telp2' => isset($request->telp2) ? $request->telp2 : '',
          'keterangan' => isset($request->keterangan) ? $request->keterangan : '',
          'user' => 'Tambah-' . $request->username . ', ' . date('d-m-Y h:i:s'),
        ]);
        $tbsales->save($validated);
        $msg = [
          'sukses' => 'Data berhasil di tambah', //view('tbsales.tabel_sales')
        ];
      }
      echo json_encode($msg);
      // return redirect()->back()->with('message', 'Berhasil di simpan');
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  // public function show(string $id)
  public function show(Request $request)
  {
    $id = $_GET['id'];
    $username = session('username');
    if ($request->Ajax()) {
      $data = [
        'menu' => 'file',
        'submenu' => 'tbsales',
        'submenu1' => 'ref_umum',
        'title' => 'Detail Tabel sales',
        'tbsales' => Tbsales::findOrFail($id),
        'userdtl' => Userdtl::where('cmodule', 'Tabel sales')->where('username', $username)->first(),
      ];
      // return view('tbsales.modaldetail')->with($data);
      return response()->json([
        'body' => view('tbsales.modaltambah', [
          'tbsales' => Tbsales::findOrFail($id),
          'action' => route('tbsales.store'),
          'vdata' => $data,
        ])->render(),
        'data' => $data,
      ]);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function edit(Tbsales $tbsales, Request $request, $id)
  {
    if ($request->Ajax()) {
      $tbsales = Tbsales::findOrFail($id);
      $data = [
        'menu' => 'file',
        'submenu' => 'tbsales',
        'submenu1' => 'ref_umum',
        'title' => 'Edit Data Tabel sales',
      ];
      return response()->json([
        'body' => view('tbsales.modaltambah', [
          'tbsales' => $tbsales,
          'action' => route('tbsales.update', $tbsales->id),
          'vdata' => $data,
        ])->render(),
        'data' => $data,
      ]);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function update(Request $request, Tbsales $tbsales)
  {
    // dd($request->kode, $request->id);
    if ($request->Ajax()) {
      if ($request->kode === $request->kodelama) {
        $validated = $request->validate(
          [
            'kode' => 'required',
            'nama' => 'required',
          ],
          [
            'kode.required' => 'Kode harus di isi',
            'nama.required' => 'Nama harus di isi',
          ]
        );
      } else {
        // var_dump($request->kode . '!=' . $request->kodelama);
        $validated = $request->validate(
          [
            'kode' => 'required|unique:tbsales,kode',
            'nama' => 'required',
          ],
          [
            'kode.unique' => 'Kode tidak boleh sama',
            'kode.required' => 'Kode harus di isi',
            'nama.required' => 'Nama harus di isi',
          ]
        );
      }
      if ($validated) {
        $tbsales->fill([
          'kode' => isset($request->kode) ? $request->kode : '',
          'alamat' => isset($request->alamat) ? $request->alamat : '',
          'kota' => isset($request->kota) ? $request->kota : '',
          'kodepos' => isset($request->kodepos) ? $request->kodepos : '',
          'telp1' => isset($request->telp1) ? $request->telp1 : '',
          'telp2' => isset($request->telp2) ? $request->telp2 : '',
          'keterangan' => isset($request->keterangan) ? $request->keterangan : '',
          'user' => 'Update-' . $request->username . ', ' . date('d-m-Y h:i:s'),
        ]);
        $kode = isset($request->kode) ? $request->kode : '';
        $nama = isset($request->nama) ? $request->nama : '';
        $initial = isset($request->initial) ? $request->initial : '';
        $alamat = isset($request->alamat) ? $request->alamat : '';
        $kota = isset($request->kota) ? $request->kota : '';
        $kdpos = isset($request->kdpos) ? $request->kdpos : '';
        $telp1 = isset($request->telp1) ? $request->telp1 : '';
        $telp2 = isset($request->telp2) ? $request->telp2 : '';
        $keterangan = isset($request->keterangan) ? $request->keterangan : '';
        $user = 'Update-' . $request->username . ', ' . date('d-m-Y h:i:s');
        // var_dump('id : ' . $request->id . ' asasa  ' . $initial);
        // $tbsales = Tbsales::find($request->id);
        // $tbsales->save();
        DB::table('tbsales')->where('id', $request->id)->update([
          'kode' => $kode, 'initial' => $initial, 'nama' => $nama, 'alamat' => $alamat, 'kota' => $kota,
          'kdpos' => $kdpos, 'telp1' => $telp1, 'telp2' => $telp2, 'keterangan' => $keterangan, 'user' => $user
        ]);
        if ($request->kodelama <> $request->kode) {
          $cekSoh = Soh::where('kdsales', $request->kodelama)->first();
          if (isset($cekSoh)) {
            Soh::where('kdsales', $request->kodelama)->update(['kdsales' => $request->kode]);
          }
          $cekJualh = Jualh::where('kdsales', $request->kodelama)->first();
          if (isset($cekJualh)) {
            Jualh::where('kdsales', $request->kodelama)->update(['kdsales' => $request->kode]);
          }
        }
        $msg = [
          'sukses' => 'Data berhasil di update', //view('tbsales.tabel_sales')
        ];
      } else {
        $msg = [
          'sukses' => 'Data gagal di update', //view('tbsales.tabel_sales')
        ];
      }
      echo json_encode($msg);
      // return redirect()->back()->with('message', 'Berhasil di update');
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function destroy(Tbsales $tbsales, Request $request, $id)
  {
    if ($request->Ajax()) {
      $terpakai = 0;
      $rowTbsales = Tbsales::where('id', $request->id)->first();
      $kode = $rowTbsales->kode;
      $Soh = Soh::where('kdsales', $kode)->first();
      if (isset($Soh)) {
        $terpakai = 1;
      }
      if ($terpakai == 0) {
        $Jualh = Jualh::where('kdsales', $kode)->first();
        if (isset($Jualh)) {
          $terpakai = 1;
        }
      }
      if ($terpakai == 0) {
        $tbsales->delete();
        return response()->json([
          'sukses' => 'Data berhasil di hapus',
        ]);
      } else {
        return response()->json([
          'sukses' => false,
        ]);
      }
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }
}
