<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\OpnamedRequest;
use Illuminate\Support\Facades\DB;
use App\Models\Opnamed;
use App\Models\Opnameh;
use App\Models\Saplikasi;

class OpnamedController extends Controller
{
  public function destroy(Opnamed $opnamed, Request $request)
  {
    if ($request->Ajax()) {
      $id = $request->id;
      Opnamed::where('id', $id)->delete();
      return response()->json([
        'sukses' => 'Data gagal di hapus',
      ]);
      // return redirect()->back()->with('message', 'Berhasil di hapus');
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function create(Request $request)
  {
    if ($request->Ajax()) {
      $noopname = $request->noopname;
      $data = [
        'menu' => 'transaksi',
        'submenu' => 'opname',
        'submenu1' => 'ref_umum',
        'title' => 'Tambah Data Opname',
      ];
      // var_dump($data);

      // return response()->json([
      //     'data' => $data,
      // ]);
      return response()->json([
        'body' => view('opname.modaleditdetail', [
          'opname' => Opnameh::where('noopname', $noopname)->first(),
          'opnamed' => new opnamed(),
          'saplikasi' => Saplikasi::where('aktif', 'Y')->first(),
          'action' => route('opnamedstore'),
          'vdata' => $data,
        ])->render(),
        'data' => $data,
      ]);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function store(Request $request, opnamedRequest $opnamedrequest, opnamed $opnamed)
  {
    if ($request->Ajax()) {
      $noopname = $request->noopname;
      $id = $request->id;
      $validate = $request->validate(
        [
          'kdbarang' => 'required',
        ],
        [
          'kdbarang.required' => 'Kode barang harus di isi',
        ],
      );
      if ($validate) {
        $recopnamed = Opnamed::where('noopname', $request->noopname)->where('kdbarang', $request->kdbarang)->first();
        if (isset($recopnamed->noopname)) {
          $msg = [
            'sukses' => 'Data gagal di tambah', //view('tbbarang.tabel_barang')
          ];
        } else {
          $opnamed->fill([
            'noopname' => isset($request->noopname) ? $request->noopname : '',
            'kdbarang' => isset($request->kdbarang) ? $request->kdbarang : '',
            'nmbarang' => isset($request->nmbarang) ? $request->nmbarang : '',
            'lokasi' => isset($request->lokasi) ? $request->lokasi : '',
            'qty' => isset($request->qty) ? $request->qty : '',
            'user' => 'Tambah-' . $request->username . ', ' . date('d-m-Y h:i:s'),
          ]);
          $opnamed->save($validate);
          $opnameh = Opnameh::select('*')->where('noopname', $noopname)->first();
          // dd($opnameh);
          $biaya_lain = $opnameh->biaya_lain;
          $subtotal = Opnamed::where('noopname', $request->noopname)->sum('subtotal');
          $total = $biaya_lain + $subtotal;
          Opnameh::where('noopname', $request->noopname)->update([
            'subtotal' => $subtotal, 'biaya_lain' => $biaya_lain, 'total' => $total
          ]);
          $msg = [
            'sukses' => 'Data berhasil di tambah', //view('tbbarang.tabel_barang')
          ];
        }
      } else {
        $msg = [
          'sukses' => 'Data gagal di tambah', //view('tbbarang.tabel_barang')
        ];
      }
      echo json_encode($msg);
      // return redirect()->back()->with('message', 'Berhasil di update');
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function edit(Opnamed $opnamed, Request $request)
  {
    if ($request->Ajax()) {
      // $id = $_GET['id'];
      $id = $request->id;
      $row = Opnamed::where('id', $id)->first();
      $noopname = $row->noopname;
      $data = [
        'menu' => 'transaksi',
        'submenu' => 'opname',
        'submenu1' => 'ref_umum',
        'title' => 'Edit Data Opname',
      ];
      // var_dump($data);

      // return response()->json([
      //     'data' => $data,
      // ]);
      return response()->json([
        'body' => view('opname.modaleditdetail', [
          'opname' => Opnameh::where('noopname', $noopname)->first(),
          'opnamed' => Opnamed::where('id', $id)->first(),
          'saplikasi' => Saplikasi::where('aktif', 'Y')->first(),
          // 'action' => route('so.update', $opnameh->id),
          'action' => 'opnamedupdate',
          'vdata' => $data,
        ])->render(),
        'data' => $data,
      ]);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function update(Request $request, opnamed $opnamed)
  {
    if ($request->Ajax()) {
      $id = $request->id;

      $validate = $request->validate(
        [
          'kdbarang' => 'required',
        ],
        [
          'kdbarang.required' => 'Barang harus di isi',
        ],
      );

      $opnamed = Opnamed::find($id);
      if ($validate) {
        // $recopnamed = Opnamed::where('noopname', $request->noopnamed)->where('kdbarang', $request->kdbarang)->first();
        // if (isset($recopnamed->noopname)) {
        //   $msg = [
        //     'sukses' => 'Data gagal di tambah', //view('tbbarang.tabel_barang')
        //   ];
        // } else {
        $opnamed->fill([
          'noopname' => isset($request->noopnamed) ? $request->noopnamed : '',
          'kdbarang' => isset($request->kdbarang) ? $request->kdbarang : '',
          'nmbarang' => isset($request->nmbarang) ? $request->nmbarang : '',
          'lokasi' => isset($request->lokasi) ? $request->lokasi : '',
          'qty' => isset($request->qty) ? $request->qty : '',
          'user' => 'Tambah-' . $request->username . ', ' . date('d-m-Y h:i:s'),
        ]);
        $opnamed->save($validate);
        $msg = [
          'sukses' => 'Data berhasil di tambah', //view('tbbarang.tabel_barang')
        ];
        // }
      } else {
        $msg = [
          'sukses' => 'Data gagal di tambah', //view('tbbarang.tabel_barang')
        ];
      }
      echo json_encode($msg);
      // return redirect()->back()->with('message', 'Berhasil di update');
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }
}
