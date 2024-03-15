<?php

namespace App\Http\Controllers;

use App\Http\Requests\TbsupplierRequest;
use Illuminate\Http\Request;
use Session;
// use Yajra\DataTables\Contracts\DataTables;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Tbsupplier;
use App\Models\Poh;
use App\Models\Belih;
use App\Models\Userdtl;

// //return type View
// use Illuminate\View\View;

class TbsupplierController extends Controller
{
  public function index(Request $request) //: View
  {
    $username = session('username');
    $data = [
      'menu' => 'file',
      'submenu' => 'tbsupplier',
      'submenu1' => 'ref_umum',
      'title' => 'Tabel Supplier',
      // 'tbsupplier' => Tbsupplier::all(),
      'userdtlmenu' => Userdtl::join('tbmodule', 'userdtl.cmodule', '=', 'tbmodule.cmodule')->where('userdtl.pakai', '1')->where('username', $username)->orderBy('userdtl.nurut')->get(),
      'userdtl' => Userdtl::where('cmodule', 'Tabel Supplier')->where('username', $username)->first(),
    ];
    $userdtl = Userdtl::where('cmodule', 'Tabel Supplier')->where('username', $username)->first();
    if ($userdtl->pakai == '1') {
      return view('tbsupplier.index')->with($data);
    } else {
      return redirect('home');
    }
  }
  public function tbsupplierajax(Request $request) //: View
  {
    if ($request->ajax()) {
      $data = Tbsupplier::select('*'); //->orderBy('kode', 'asc');
      return Datatables::of($data)
        ->addIndexColumn()
        ->addColumn('kode1', function ($row) {
          $id = $row['id'];
          $btn = $row['kode']; //'<a href="#"' . 'onclick=detail(' . $id . ')>' .  $row['kode'] . '</a>';
          return $btn;
        })
        ->rawColumns(['kode1'])
        // ->addIndexColumn()
        // ->addColumn('action', function ($row) {
        //     $btn = '<a href="javascript:void(0)" class="edit btn btn-primary btn-sm">View</a>';
        //     return $btn;
        // })
        // ->rawColumns(['action'])
        ->make(true);
      return view('tbsupplier');
    }
  }

  public function tabel_supplier(Request $request)
  {
    $data = [
      'menu' => 'file',
      'submenu' => 'tbsupplier',
      'submenu1' => 'ref_umum',
      'title' => 'Tabel Supplier',
      'tbsupplier' => Tbsupplier::orderBy('kode', 'asc')->get(),
    ];
    // var_dump($data);
    return view('tbsupplier.tabel_supplier')->with($data);
  }

  public function create(Request $request)
  {
    if ($request->Ajax()) {
      $data = [
        'menu' => 'file',
        'submenu' => 'tbsupplier',
        'submenu1' => 'ref_umum',
        'title' => 'Tambah Data Tabel Supplier',
      ];
      // var_dump($data);
      return response()->json([
        'body' => view('tbsupplier.modaltambah', [
          'tbsupplier' => new Tbsupplier(), //Tbsupplier::first(),
          'action' => route('tbsupplier.store'),
          'vdata' => $data,
        ])->render(),
        'data' => $data,

      ]);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function store(TbsupplierRequest $request, Tbsupplier $tbsupplier)
  {
    if ($request->Ajax()) {
      $validated = $request->validated(
        // [
        //     'kode' => 'required|unique:tbsupplier,kode',
        //     'nama' => 'required',
        // ],
        // [
        //     'kode.unique' => 'Kode tidak boleh sama',
        //     'kode.required' => 'Kode harus di isi',
        //     'nama.required' => 'Nama harus di isi',
        // ]
      );
      if ($validated) {
        // $aktif = isset($request->aktif) ? 'Y' : 'N';
        $tbsupplier->fill([
          'nama' => isset($request->nama) ? $request->nama : '',
          'kode' => isset($request->kode) ? $request->kode : '',
          'alamat' => isset($request->alamat) ? $request->alamat : '',
          'kota' => isset($request->kota) ? $request->kota : '',
          'kodepos' => isset($request->kodepos) ? $request->kodepos : '',
          'telp1' => isset($request->telp1) ? $request->telp1 : '',
          'telp2' => isset($request->telp2) ? $request->telp2 : '',
          'npwp' => isset($request->npwp) ? $request->npwp : '',
          'contact_person' => isset($request->contact_person) ? $request->contact_person : '',
          'user' => 'Tambah-' . $request->username . ', ' . date('d-m-Y h:i:s'),
        ]);
        $tbsupplier->save($validated);
        $msg = [
          'sukses' => 'Data berhasil di tambah', //view('tbsupplier.tabel_supplier')
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
    if ($request->Ajax()) {
      $id = $_GET['id'];
      $username = session('username');
      $data = [
        'menu' => 'file',
        'submenu' => 'tbsupplier',
        'submenu1' => 'ref_umum',
        'title' => 'Detail Tabel Supplier',
        'tbsupplier' => Tbsupplier::findOrFail($id),
        'userdtl' => Userdtl::where('cmodule', 'Tabel Supplier')->where('username', $username)->first(),
      ];
      // return view('tbsupplier.modaldetail')->with($data);
      return response()->json([
        'body' => view('tbsupplier.modaltambah', [
          'tbsupplier' => Tbsupplier::findOrFail($id),
          'action' => route('tbsupplier.store'),
          'vdata' => $data,
        ])->render(),
        'data' => $data,
      ]);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function edit(Tbsupplier $tbsupplier, Request $request)
  {
    if ($request->Ajax()) {
      $data = [
        'menu' => 'file',
        'submenu' => 'tbsupplier',
        'submenu1' => 'ref_umum',
        'title' => 'Edit Data Tabel Supplier',
      ];
      return response()->json([
        'body' => view('tbsupplier.modaltambah', [
          'tbsupplier' => $tbsupplier,
          'action' => route('tbsupplier.update', $tbsupplier->id),
          'vdata' => $data,
        ])->render(),
        'data' => $data,
      ]);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function update(TbsupplierRequest $request, Tbsupplier $tbsupplier)
  {
    if ($request->Ajax()) {
      $validated = $request->validated();
      if ($validated) {
        $aktif = $request->aktif == 'on' ? 'Y' : 'N';
        $tbsupplier->fill([
          'nama' => isset($request->nama) ? $request->nama : '',
          'kode' => isset($request->kode) ? $request->kode : '',
          'alamat' => isset($request->alamat) ? $request->alamat : '',
          'kota' => isset($request->kota) ? $request->kota : '',
          'kodepos' => isset($request->kodepos) ? $request->kodepos : '',
          'telp1' => isset($request->telp1) ? $request->telp1 : '',
          'telp2' => isset($request->telp2) ? $request->telp2 : '',
          'npwp' => isset($request->npwp) ? $request->npwp : '',
          'contact_person' => isset($request->contact_person) ? $request->contact_person : '',
          'user' => 'Update-' . $request->username . ', ' . date('d-m-Y h:i:s'),
        ]);
        $tbsupplier->save($validated);
        if ($request->kodelama <> $request->kode) {
          $cekPoh = Poh::where('kdsupplier', $request->kodelama)->first();
          if (isset($cekPoh)) {
            Poh::where('kdsupplier', $request->kodelama)->update(['kdsupplier' => $request->kode]);
          }
          $cekBelih = Belih::where('kdsupplier', $request->kodelama)->first();
          if (isset($cekBelih)) {
            Belih::where('kdsupplier', $request->kodelama)->update(['kdsupplier' => $request->kode]);
          }
        }
        $msg = [
          'sukses' => 'Data berhasil di update', //view('tbsupplier.tabel_supplier')
        ];
      } else {
        $msg = [
          'sukses' => 'Data gagal di update', //view('tbsupplier.tabel_supplier')
        ];
      }
      echo json_encode($msg);
      // return redirect()->back()->with('message', 'Berhasil di update');
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function destroy(Tbsupplier $tbsupplier, Request $request)
  {
    if ($request->Ajax()) {
      $terpakai = 0;
      $rowTbsupplier = Tbsupplier::where('id', $request->id)->first();
      $kode = $rowTbsupplier->kode;
      $Poh = Poh::where('kdsupplier', $kode)->first();
      if (isset($Poh)) {
        $terpakai = 1;
      }
      if ($terpakai == 0) {
        $Belih = Belih::where('kdsupplier', $kode)->first();
        if (isset($Belih)) {
          $terpakai = 1;
        }
      }
      if ($terpakai == 0) {
        $tbsupplier->delete();
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
