<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\podRequest;
use Illuminate\Support\Facades\DB;
use App\Models\Pod;
use App\Models\Poh;
use App\Models\Saplikasi;

class PodController extends Controller
{
  public function destroy(Pod $pod, Request $request)
  {
    if ($request->Ajax()) {
      $id = $request->id;
      // $poh->delete('id', $id);
      $deleted = Pod::where('id', $id)->delete();
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
      $nopo = $request->nopo;
      $username = session('username');
      $data = [
        'menu' => 'transaksi',
        'submenu' => 'po',
        'submenu1' => 'ref_umum',
        'title' => 'Tambah Data Purchase Order',
      ];
      // var_dump($data);

      // return response()->json([
      //     'data' => $data,
      // ]);
      return response()->json([
        'body' => view('po.modaleditdetail', [
          'po' => Poh::where('nopo', $nopo)->first(),
          'pod' => new pod(),
          'saplikasi' => Saplikasi::where('aktif', 'Y')->first(),
          // 'action' => route('po.update', $poh->id),
          'action' => route('podstore'),
          'vdata' => $data,
        ])->render(),
        'data' => $data,
      ]);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function store(Request $request, pod $pod)
  // public function store(Request $request, Soh $poh)
  {
    if ($request->Ajax()) {
      $nopo = $request->nopo;
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
        $recpod = pod::where('nopo', $request->nopo)->where('kdbarang', $request->kdbarang)->first();
        if (isset($recpod->nopo)) {
          $msg = [
            'sukses' => 'Data gagal di tambah', //view('tbbarang.tabel_barang')
          ];
        } else {
          $pod->fill([
            'nopo' => isset($request->nopo) ? $request->nopo : '',
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
          $pod->save($validate);
          $poh = Poh::select('*')->where('nopo', $nopo)->first();
          $biaya_lain = $poh->biaya_lain;
          $materai = $poh->materai;
          $ppn = $poh->ppn;
          $subtotal = Pod::where('nopo', $request->nopo)->sum('subtotal');
          $total_sementara = $biaya_lain + $subtotal + $materai;
          $total = $total_sementara + ($total_sementara * ($ppn / 100));
          Poh::where('nopo', $request->nopo)->update([
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

  public function edit(Pod $pod, Request $request)
  {
    if ($request->Ajax()) {
      // $id = $_GET['id'];
      $id = $request->id;
      $row = pod::where('id', $id)->first();
      $nopo = $row->nopo;
      $data = [
        'menu' => 'transaksi',
        'submenu' => 'Purchase Order',
        'submenu1' => 'ref_umum',
        'title' => 'Edit Data Purchase Order',
      ];
      // var_dump($data);

      // return response()->json([
      //     'data' => $data,
      // ]);
      return response()->json([
        'body' => view('po.modaleditdetail', [
          'po' => Poh::where('nopo', $nopo)->first(),
          'pod' => pod::where('id', $id)->first(),
          'saplikasi' => Saplikasi::where('aktif', 'Y')->first(),
          // 'action' => route('po.update', $poh->id),
          'action' => 'podupdate',
          'vdata' => $data,
        ])->render(),
        'data' => $data,
      ]);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function update(Request $request, pod $pod)
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

      $pod = pod::find($id);
      if ($validate) {
        // $recpod = pod::where('nopo', $request->nopod)->where('kdbarang', $request->kdbarang)->first();
        // if (isset($recpod->nopo)) {
        //   $msg = [
        //     'sukses' => 'Data gagal di tambah', //view('tbbarang.tabel_barang')
        //   ];
        // } else {
        $pod->fill([
          'nopo' => isset($request->nopod) ? $request->nopod : '',
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
        $pod->save($validate);
        $poh = Poh::where('nopo', $request->nopod)->first();
        $biaya_lain = $poh->biaya_lain;
        $materai = $poh->materai;
        $ppn = $poh->ppn;
        $subtotal = Pod::where('nopo', $request->nopod)->sum('subtotal');
        $total_sementara = $biaya_lain + $subtotal + $materai;
        $total = $total_sementara + ($total_sementara * ($ppn / 100));
        Poh::where('nopo', $request->nopod)->update([
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
