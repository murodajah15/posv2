<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\BelidRequest;
use Illuminate\Support\Facades\DB;
use App\Models\Belid;
use App\Models\Belih;
use App\Models\Saplikasi;

class BelidController extends Controller
{
  public function destroy(Belid $belid, Request $request)
  {
    if ($request->Ajax()) {
      $id = $request->id;
      $belid = Belid::where('id', $id)->first();
      $nobeli = $belid->nobeli;
      // $deleted = DB::table('belid')->where('id', $id)->delete();
      $deleted = Belid::where('id', $id)->delete();
      if ($deleted) {
        $subtotal = Belid::where('nobeli', $nobeli)->sum('subtotal');
        $belih = Belih::where('nobeli', $nobeli)->first();
        $biaya_lain = isset($belih->biaya_lain) ? $belih->biaya_lain : '0';
        $materai = isset($belih->materai) ? $belih->materai : '0';
        $ppn = isset($belih->ppn) ? $belih->ppn : '0';
        $total_sementara = $biaya_lain + $subtotal + $materai;
        $total = $total_sementara + ($total_sementara * ($ppn / 100));
        Belih::where('nobeli', $nobeli)->update(['biaya_lain' => $biaya_lain, 'subtotal' => $subtotal, 'total_sementara' => $total_sementara, 'ppn' => $ppn, 'total' => $total]);
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
      $nobeli = $request->nobeli;
      $username = session('username');
      $data = [
        'menu' => 'transaksi',
        'submenu' => 'beli',
        'submenu1' => 'ref_umum',
        'title' => 'Tambah Data Pembelian',
      ];
      // var_dump($data);

      // return response()->json([
      //     'data' => $data,
      // ]);
      return response()->json([
        'body' => view('beli.modaleditdetail', [
          'beli' => Belih::where('nobeli', $nobeli)->first(),
          'belid' => new belid(),
          'saplikasi' => Saplikasi::where('aktif', 'Y')->first(),
          'action' => route('belidstore'),
          'vdata' => $data,
        ])->render(),
        'data' => $data,
      ]);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function store(Request $request, belidRequest $belidrequest, belid $belid)
  {
    if ($request->Ajax()) {
      $nobeli = $request->nobeli;
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
        $recbelid = Belid::where('nobeli', $request->nobeli)->where('kdbarang', $request->kdbarang)->first();
        if (isset($recbelid->nobeli)) {
          $msg = [
            'sukses' => 'Data gagal di tambah', //view('tbbarang.tabel_barang')
          ];
        } else {
          $belid->fill([
            'tglbeli' => isset($request->tglbeli) ? $request->tglbeli : '',
            'nobeli' => isset($request->nobeli) ? $request->nobeli : '',
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
          $belid->save($validate);
          $belih = Belih::select('*')->where('nobeli', $nobeli)->first();
          // dd($belih);
          $biaya_lain = $belih->biaya_lain;
          $materai = $belih->materai;
          $ppn = $belih->ppn;
          $subtotal = Belid::where('nobeli', $request->nobeli)->sum('subtotal');
          $total_sementara = $biaya_lain + $subtotal + $materai;
          $total = $total_sementara + ($total_sementara * ($ppn / 100));
          // DB::table('belih')->where('nobeli', $request->nobeli)->update([
          //   'subtotal' => $subtotal, 'biaya_lain' => $biaya_lain, 'materai' => $materai, 'total_sementara' => $total_sementara, 'total_sementara' =>
          //   $total_sementara, 'total' => $total
          // ]);
          Belih::where('nobeli', $request->nobeli)->update([
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

  public function edit(Belid $belid, Request $request)
  {
    if ($request->Ajax()) {
      // $id = $_GET['id'];
      $id = $request->id;
      $row = Belid::where('id', $id)->first();
      $nobeli = $row->nobeli;
      $data = [
        'menu' => 'transaksi',
        'submenu' => 'Pembelian',
        'submenu1' => 'ref_umum',
        'title' => 'Edit Data Pembelian',
      ];
      // var_dump($data);

      // return response()->json([
      //     'data' => $data,
      // ]);
      return response()->json([
        'body' => view('beli.modaleditdetail', [
          'beli' => Belih::where('nobeli', $nobeli)->first(),
          'belid' => Belid::where('id', $id)->first(),
          'saplikasi' => Saplikasi::where('aktif', 'Y')->first(),
          // 'action' => route('so.update', $belih->id),
          'action' => 'belidupdate',
          'vdata' => $data,
        ])->render(),
        'data' => $data,
      ]);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function update(Request $request, belid $belid)
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

      $belid = Belid::find($id);
      if ($validate) {
        // $recbelid = Belid::where('nobeli', $request->nobelid)->where('kdbarang', $request->kdbarang)->first();
        // if (isset($recbelid->nobeli)) {
        //   $msg = [
        //     'sukses' => 'Data gagal di tambah', //view('tbbarang.tabel_barang')
        //   ];
        // } else {
        $belid->fill([
          'tglbeli' => isset($request->tglbeli) ? $request->tglbeli : '',
          'nobeli' => isset($request->nobelid) ? $request->nobelid : '',
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
        $belid->save($validate);
        $belih = Belih::where('nobeli', $request->nobelid)->first();
        $biaya_lain = $belih->biaya_lain;
        $materai = $belih->materai;
        $ppn = $belih->ppn;
        $subtotal = Belid::where('nobeli', $request->nobelid)->sum('subtotal');
        $total_sementara = $biaya_lain + $subtotal + $materai;
        $total = $total_sementara + ($total_sementara * ($ppn / 100));
        // DB::table('belih')->where('nobeli', $request->nobelid)->update([
        //   'subtotal' => $subtotal, 'biaya_lain' => $biaya_lain, 'materai' => $materai, 'total_sementara' => $total_sementara, 'total_sementara' =>
        //   $total_sementara, 'total' => $total
        // ]);
        Belih::where('nobeli', $request->nobelid)->update([
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
