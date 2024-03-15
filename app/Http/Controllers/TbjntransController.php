<?php

namespace App\Http\Controllers;

use App\Http\Requests\TbjntransRequest;
use Illuminate\Http\Request;
use Session;
// use Yajra\DataTables\Contracts\DataTables;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Tbjntrans;
use App\Models\Userdtl;
use Illuminate\Support\Facades\DB;

// //return type View
// use Illuminate\View\View;

class TbjntransController extends Controller
{
  public function index() //: View
  {
    $username = session('username');
    $data = [
      'menu' => 'file',
      'submenu' => 'tbjntrans',
      'submenu1' => 'ref_umum',
      'title' => 'Tabel Jenis Transaksi',
      // 'tbjntrans' => Tbjntrans::all(),
      'userdtlmenu' => Userdtl::join('tbmodule', 'userdtl.cmodule', '=', 'tbmodule.cmodule')->where('userdtl.pakai', '1')->where('username', $username)->orderBy('userdtl.nurut')->get(),
      'userdtl' => Userdtl::where('cmodule', 'Tabel Jenis Transaksi')->where('username', $username)->first(),
    ];
    $userdtl = Userdtl::where('cmodule', 'Tabel Jenis Transaksi')->where('username', $username)->first();
    if ($userdtl->pakai == '1') {
      return view('tbjntrans.index')->with($data);
    } else {
      return redirect('home');
    }
  }
  public function tbjntransajax(Request $request) //: View
  {
    if ($request->ajax()) {
      $data = Tbjntrans::select('*'); //->orderBy('kode', 'asc');
      return Datatables::of($data)
        ->addIndexColumn()
        ->addColumn('kode1', function ($row) {
          $id = $row['id'];
          $btn = $row['kode']; //'<a href="#"' . 'onclick=detail(' . $id . ')>' .  $row['kode'] . '</a>';
          return $btn;
        })
        ->rawColumns(['kode1'])
        ->make(true);
      return view('tbjntrans');
    }
  }

  public function tabel_jntrans(Request $request)
  {
    $data = [
      'menu' => 'file',
      'submenu' => 'tbjntrans',
      'submenu1' => 'ref_umum',
      'title' => 'Tabel Jenis Transaksi',
      'tbjntrans' => Tbjntrans::orderBy('kode', 'asc')->get(),
    ];
    // var_dump($data);
    return view('tbjntrans.tabel_jntrans')->with($data);
  }

  public function create(Request $request)
  {
    if ($request->Ajax()) {
      $data = [
        'menu' => 'file',
        'submenu' => 'tbjntrans',
        'submenu1' => 'ref_umum',
        'title' => 'Tambah Data Tabel jntrans',
      ];
      return response()->json([
        'body' => view('tbjntrans.modaltambah', [
          'tbjntrans' => new Tbjntrans(),
          'action' => route('tbjntrans.store'),
          'vdata' => $data,
        ])->render(),
        'data' => $data,

      ]);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function store(TbjntransRequest $request, Tbjntrans $tbjntrans)
  {
    if ($request->Ajax()) {
      $validated = $request->validated();
      if ($validated) {
        $tbjntrans->fill([
          'nama' => $request->nama,
          'kode' => $request->kode,
          'keterangan' => isset($request->keterangan) ? $request->keterangan : '',
          'user' => 'Tambah-' . $request->username . ', ' . date('d-m-Y h:i:s'),
        ]);
        $tbjntrans->save($validated);
        $msg = [
          'sukses' => 'Data berhasil di tambah', //view('tbjntrans.tabel_jntrans')
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
        'submenu' => 'tbjntrans',
        'submenu1' => 'ref_umum',
        'title' => 'Detail Tabel jntrans',
        'tbjntrans' => Tbjntrans::findOrFail($id),
        'userdtl' => Userdtl::where('cmodule', 'Tabel jntrans')->where('username', $username)->first(),
      ];
      // return view('tbjntrans.modaldetail')->with($data);
      return response()->json([
        'body' => view('tbjntrans.modaltambah', [
          'tbjntrans' => Tbjntrans::findOrFail($id),
          'action' => route('tbjntrans.store'),
          'vdata' => $data,
        ])->render(),
        'data' => $data,
      ]);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function edit(Tbjntrans $tbjntrans, Request $request, $id)
  {
    if ($request->Ajax()) {
      $tbjntrans = Tbjntrans::findOrFail($id);
      $data = [
        'menu' => 'file',
        'submenu' => 'tbjntrans',
        'submenu1' => 'ref_umum',
        'title' => 'Edit Data Tabel jntrans',
      ];
      return response()->json([
        'body' => view('tbjntrans.modaltambah', [
          'tbjntrans' => $tbjntrans,
          'action' => route('tbjntrans.update', $tbjntrans->id),
          'vdata' => $data,
        ])->render(),
        'data' => $data,
      ]);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function update(Request $request, Tbjntrans $tbjntrans)
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
            'kode' => 'required|unique:tbjntrans,kode',
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
        $tbjntrans->fill([
          'kode' => $request->kode,
          'nama' => $request->nama,
          'keterangan' => isset($request->keterangan) ? $request->keterangan : '',
          'user' => 'Update-' . $request->username . ', ' . date('d-m-Y h:i:s'),
        ]);
        $kode = $request->kode;
        $nama = $request->nama;
        $keterangan = isset($request->keterangan) ? $request->keterangan : '';
        $user = 'Update-' . $request->username . ', ' . date('d-m-Y h:i:s');
        // $tbjntrans = Tbjntrans::find($request->id);
        // $tbjntrans->save();
        DB::table('tbjntrans')->where('id', $request->id)->update(['kode' => $kode, 'nama' => $nama, 'keterangan' => $keterangan, 'user' => $user]);
        $msg = [
          'sukses' => 'Data berhasil di update', //view('tbjntrans.tabel_jntrans')
        ];
      } else {
        $msg = [
          'sukses' => 'Data gagal di update', //view('tbjntrans.tabel_jntrans')
        ];
      }
      echo json_encode($msg);
      // return redirect()->back()->with('message', 'Berhasil di update');
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function destroy(Tbjntrans $tbjntrans, Request $request, $id)
  {
    if ($request->Ajax()) {
      DB::table('tbjntrans')->where('id', $id)->delete();
      // $tbjntrans->delete();
      return response()->json([
        'sukses' => 'Data berhasil di hapus',
      ]);
      // return redirect()->back()->with('message', 'Berhasil di hapus');
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }
}
