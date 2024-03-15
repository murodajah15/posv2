<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\TerimadRequest;
use Illuminate\Support\Facades\DB;
use App\Models\Terimad;
use App\Models\Terimah;
use App\Models\Saplikasi;

class TerimadController extends Controller
{
  public function destroy(Terimad $terimad, Request $request)
  {
    if ($request->Ajax()) {
      $id = $request->id;
      $terimad = Terimad::where('id', $id)->first();
      $noterima = $terimad->noterima;
      $deleted = Terimad::where('id', $id)->delete();
      if ($deleted) {
        $subtotal = Terimad::where('noterima', $noterima)->sum('subtotal');
        $terimah = Terimah::where('noterima', $noterima)->first();
        $biaya_lain = isset($terimah->biaya_lain) ? $terimah->biaya_lain : '0';
        $total = $biaya_lain + $subtotal;
        Terimah::where('noterima', $noterima)->update(['biaya_lain' => $biaya_lain, 'subtotal' => $subtotal, 'total' => $total]);
        return response()->json([
          'sukses' => 'Data berhasil di hapus',
        ]);
      } else {
        return response()->json([
          'sukses' => 'Data gagal di hapus',
        ]);
      }
      // return redirect()->back()->with('message', 'Berhasil di hapus');
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function create(Request $request)
  {
    if ($request->Ajax()) {
      $noterima = $request->noterima;
      $username = session('username');
      $data = [
        'menu' => 'transaksi',
        'submenu' => 'terima',
        'submenu1' => 'ref_umum',
        'title' => 'Tambah Data Penterimaan',
      ];
      // var_dump($data);

      // return response()->json([
      //     'data' => $data,
      // ]);
      return response()->json([
        'body' => view('terima.modaleditdetail', [
          'terima' => Terimah::where('noterima', $noterima)->first(),
          'terimad' => new terimad(),
          'saplikasi' => Saplikasi::where('aktif', 'Y')->first(),
          'action' => route('terimadstore'),
          'vdata' => $data,
        ])->render(),
        'data' => $data,
      ]);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function store(Request $request, terimadRequest $terimadrequest, Terimad $terimad)
  {
    if ($request->Ajax()) {
      $noterima = $request->noterima;
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
        $recterimad = Terimad::where('noterima', $request->noterima)->where('kdbarang', $request->kdbarang)->first();
        if (isset($recterimad->noterima)) {
          $msg = [
            'sukses' => 'Data gagal di tambah', //view('tbbarang.tabel_barang')
          ];
        } else {
          $terimad->fill([
            'tglterima' => isset($request->tglterima) ? $request->tglterima : '',
            'noterima' => isset($request->noterima) ? $request->noterima : '',
            'kdbarang' => isset($request->kdbarang) ? $request->kdbarang : '',
            'nmbarang' => isset($request->nmbarang) ? $request->nmbarang : '',
            'kdsatuan' => isset($request->kdsatuan) ? $request->kdsatuan : '',
            'qty' => isset($request->qty) ? $request->qty : '',
            'harga' => isset($request->harga) ? $request->harga : '',
            'discount' => isset($request->discount) ? $request->discount : '',
            'subtotal' => isset($request->subtotal) ? $request->subtotal : '',
            'total' => isset($request->total) ? $request->total : '',
            'user' => 'Tambah-' . $request->username . ', ' . date('d-m-Y h:i:s'),
          ]);
          $terimad->save($validate);
          $terimah = Terimah::select('*')->where('noterima', $noterima)->first();
          // dd($terimah);
          $biaya_lain = $terimah->biaya_lain;
          $subtotal = Terimad::where('noterima', $request->noterima)->sum('subtotal');
          $total = $biaya_lain + $subtotal;
          Terimah::where('noterima', $request->noterima)->update([
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

  public function edit(Terimad $terimad, Request $request)
  {
    if ($request->Ajax()) {
      // $id = $_GET['id'];
      $id = $request->id;
      $row = Terimad::where('id', $id)->first();
      $noterima = $row->noterima;
      $data = [
        'menu' => 'transaksi',
        'submenu' => 'Penterimaan',
        'submenu1' => 'ref_umum',
        'title' => 'Edit Data Penterimaan',
      ];
      // var_dump($data);

      // return response()->json([
      //     'data' => $data,
      // ]);
      return response()->json([
        'body' => view('terima.modaleditdetail', [
          'terima' => Terimah::where('noterima', $noterima)->first(),
          'terimad' => Terimad::where('id', $id)->first(),
          'saplikasi' => Saplikasi::where('aktif', 'Y')->first(),
          // 'action' => route('so.update', $terimah->id),
          'action' => 'terimadupdate',
          'vdata' => $data,
        ])->render(),
        'data' => $data,
      ]);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function update(Request $request, Terimad $terimad)
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

      $terimad = Terimad::find($id);
      if ($validate) {
        // $recterimad = Terimad::where('noterima', $request->noterimad)->where('kdbarang', $request->kdbarang)->first();
        // if (isset($recterimad->noterima)) {
        //   $msg = [
        //     'sukses' => 'Data gagal di tambah', //view('tbbarang.tabel_barang')
        //   ];
        // } else {
        $terimad->fill([
          'tglterima' => isset($request->tglterima) ? $request->tglterima : '',
          'noterima' => isset($request->noterimad) ? $request->noterimad : '',
          'kdbarang' => isset($request->kdbarang) ? $request->kdbarang : '',
          'nmbarang' => isset($request->nmbarang) ? $request->nmbarang : '',
          'kdsatuan' => isset($request->kdsatuan) ? $request->kdsatuan : '',
          'qty' => isset($request->qty) ? $request->qty : '',
          'harga' => isset($request->harga) ? $request->harga : '',
          'discount' => isset($request->discount) ? $request->discount : '',
          'subtotal' => isset($request->subtotal) ? $request->subtotal : '',
          'total' => isset($request->total) ? $request->total : '',
          'user' => 'Tambah-' . $request->username . ', ' . date('d-m-Y h:i:s'),
        ]);
        $terimad->save($validate);
        $terimah = Terimah::where('noterima', $request->noterimad)->first();
        $biaya_lain = $terimah->biaya_lain;
        $subtotal = Terimad::where('noterima', $request->noterimad)->sum('subtotal');
        $total = $biaya_lain + $subtotal;
        Terimah::where('noterima', $request->noterimad)->update([
          'subtotal' => $subtotal, 'biaya_lain' => $biaya_lain, 'total' => $total
        ]);
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
