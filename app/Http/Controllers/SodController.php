<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\SodRequest;
use Illuminate\Support\Facades\DB;
use App\Models\Sod;
use App\Models\Soh;
use App\Models\Saplikasi;

class SodController extends Controller
{
  public function destroy(Sod $sod, Request $request)
  {
    if ($request->Ajax()) {
      $id = $request->id;
      // $soh->delete('id', $id);
      $deleted = Sod::where('id', $id)->delete();
      if ($deleted) {
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
      $noso = $request->noso;
      $username = session('username');
      $data = [
        'menu' => 'transaksi',
        'submenu' => 'so',
        'submenu1' => 'ref_umum',
        'title' => 'Tambah Data Sales Order',
      ];
      // var_dump($data);

      // return response()->json([
      //     'data' => $data,
      // ]);
      return response()->json([
        'body' => view('so.modaleditdetail', [
          'so' => Soh::where('noso', $noso)->first(),
          'sod' => new Sod(),
          'saplikasi' => Saplikasi::where('aktif', 'Y')->first(),
          // 'action' => route('so.update', $soh->id),
          'action' => route('sodstore'),
          'vdata' => $data,
        ])->render(),
        'data' => $data,
      ]);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function store(Request $request, SodRequest $sodrequest, Sod $sod)
  // public function store(Request $request, Soh $soh)
  {
    if ($request->Ajax()) {
      $noso = $request->noso;
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
        $recsod = Sod::where('noso', $request->noso)->where('kdbarang', $request->kdbarang)->first();
        if (isset($recsod->noso)) {
          $msg = [
            'sukses' => 'Data gagal di tambah', //view('tbbarang.tabel_barang')
          ];
        } else {
          $multiprc = isset($request->multiprc) ? 'Y' : 'N';
          $sod->fill([
            'noso' => isset($request->noso) ? $request->noso : '',
            'kdbarang' => isset($request->kdbarang) ? $request->kdbarang : '',
            'nmbarang' => isset($request->nmbarang) ? $request->nmbarang : '',
            'kdsatuan' => isset($request->kdsatuan) ? $request->kdsatuan : '',
            'qty' => isset($request->qty) ? $request->qty : '',
            'harga' => isset($request->harga) ? $request->harga : '',
            'discount' => isset($request->discount) ? $request->discount : '',
            'subtotal' => isset($request->subtotal) ? $request->subtotal : '',
            'total' => isset($request->total) ? $request->total : '',
            'multiprc' => $multiprc,
            'user' => 'Tambah-' . $request->username . ', ' . date('d-m-Y h:i:s'),
          ]);
          $sod->save($validate);
          $soh = Soh::select('*')->where('noso', $noso)->first();
          // dd($soh);
          $biaya_lain = $soh->biaya_lain;
          $materai = $soh->materai;
          $ppn = $soh->ppn;
          $subtotal = Sod::where('noso', $request->noso)->sum('subtotal');
          $total_sementara = $biaya_lain + $subtotal + $materai;
          $total = $total_sementara + ($total_sementara * ($ppn / 100));
          Soh::where('noso', $request->noso)->update([
            'subtotal' => $subtotal, 'biaya_lain' => $biaya_lain, 'materai' => $materai, 'total_sementara' => $total_sementara, 'total_sementara' =>
            $total_sementara, 'total' => $total
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

  public function edit(Sod $sod, Request $request)
  {
    if ($request->Ajax()) {
      // $id = $_GET['id'];
      $id = $request->id;
      $row = Sod::where('id', $id)->first();
      $noso = $row->noso;
      $data = [
        'menu' => 'transaksi',
        'submenu' => 'Sales Order',
        'submenu1' => 'ref_umum',
        'title' => 'Edit Data Sales Order',
      ];
      // var_dump($data);

      // return response()->json([
      //     'data' => $data,
      // ]);
      return response()->json([
        'body' => view('so.modaleditdetail', [
          'so' => Soh::where('noso', $noso)->first(),
          'sod' => Sod::where('id', $id)->first(),
          'saplikasi' => Saplikasi::where('aktif', 'Y')->first(),
          // 'action' => route('so.update', $soh->id),
          'action' => 'sodupdate',
          'vdata' => $data,
        ])->render(),
        'data' => $data,
      ]);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function update(Request $request, Sod $sod)
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

      $sod = sod::find($id);
      if ($validate) {
        // $recsod = Sod::where('noso', $request->nosod)->where('kdbarang', $request->kdbarang)->first();
        // if (isset($recsod->noso)) {
        //   $msg = [
        //     'sukses' => 'Data gagal di tambah', //view('tbbarang.tabel_barang')
        //   ];
        // } else {
        $multiprc = isset($request->multiprc) ? 'Y' : 'N';
        $sod->fill([
          'noso' => isset($request->nosod) ? $request->nosod : '',
          'kdbarang' => isset($request->kdbarang) ? $request->kdbarang : '',
          'nmbarang' => isset($request->nmbarang) ? $request->nmbarang : '',
          'kdsatuan' => isset($request->kdsatuan) ? $request->kdsatuan : '',
          'qty' => isset($request->qty) ? $request->qty : '',
          'harga' => isset($request->harga) ? $request->harga : '',
          'discount' => isset($request->discount) ? $request->discount : '',
          'subtotal' => isset($request->subtotal) ? $request->subtotal : '',
          'total' => isset($request->total) ? $request->total : '',
          'multiprc' => $multiprc,
          'user' => 'Tambah-' . $request->username . ', ' . date('d-m-Y h:i:s'),
        ]);
        $sod->save($validate);
        $soh = Soh::where('noso', $request->nosod)->first();
        $biaya_lain = $soh->biaya_lain;
        $materai = $soh->materai;
        $ppn = $soh->ppn;
        $subtotal = Sod::where('noso', $request->nosod)->sum('subtotal');
        $total_sementara = $biaya_lain + $subtotal + $materai;
        $total = $total_sementara + ($total_sementara * ($ppn / 100));
        Soh::where('noso', $request->nosod)->update([
          'subtotal' => $subtotal, 'biaya_lain' => $biaya_lain, 'materai' => $materai, 'total_sementara' => $total_sementara, 'total_sementara' =>
          $total_sementara, 'total' => $total
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
