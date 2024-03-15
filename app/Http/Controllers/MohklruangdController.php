<?php

namespace App\Http\Controllers;

use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;
use App\Http\Requests\MohklruangdRequest;
use Illuminate\Support\Facades\DB;
use App\Models\Mohklruangd;
use App\Models\Mohklruangh;
use App\Models\Saplikasi;
use App\Models\Userdtl;

class MohklruangdController extends Controller
{
  public function mohklruanginputmohklruangd(Mohklruangh $mohklruangh, Request $request)
  {
    if ($request->Ajax()) {
      $id = $_GET['id'];
      $username = session('username');
      $mohklruang = Mohklruangh::where('id', $id)->first();
      $nomohon = $mohklruang->nomohon;
      $data = [
        'menu' => 'transaksi',
        'submenu' => 'mohklruang',
        'submenu1' => 'ref_umum',
        'title' => 'Detail Data Permohonan Keluar Uang',
      ];
      // var_dump($data);

      // return response()->json([
      //     'data' => $data,
      // ]);
      return response()->json([
        'body' => view('mohklruang.modaldetail', [
          'userdtlmenu' => Userdtl::join('tbmodule', 'userdtl.cmodule', '=', 'tbmodule.cmodule')->where('userdtl.pakai', '1')->where('username', $username)->orderBy('userdtl.nurut')->get(),
          'userdtl' => Userdtl::where('cmodule', 'Permohonan Keluar Uang')->where('username', $username)->first(),
          'saplikasi' => Saplikasi::where('aktif', 'Y')->first(),
          'mohklruang' => Mohklruangh::where('id', $id)->first(),
          'mohklruangd' => mohklruangd::where('nomohon', $nomohon)->get(),
          // 'action' => route('mohklruang.update', $mohklruang->id),
          'action' => 'mohklruangtambahdetail',
          'vdata' => $data,
        ])->render(),
        'data' => $data,
      ]);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function mohklruangdajax(Request $request) //: View
  {
    $nomohon = $request->nomohon;
    if ($request->ajax()) {
      $data = mohklruangd::where('nomohon', $nomohon); //->orderBy('kode', 'asc');
      return Datatables::of($data)
        ->addIndexColumn()
        ->addColumn('kode1', function ($row) {
          $id = $row['id'];
          $btn = $row['kode']; //'<a href="#"' . 'onclick=detail(' . $id . ')>' .  $row['kode'] . '</a>';
          return $btn;
        })
        ->rawColumns(['kode1'])
        ->make(true);
      // return view('tbl-detail-so');
    }
  }

  public function mohklruangtambahdetail(Request $request, mohklruangdRequest $mohklruangdrequest, mohklruangd $mohklruangd)
  {
    if ($request->Ajax()) {
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
        // $recmohklruangd = mohklruangd::where('nomohon', $request->nomohond)->where('kdbarang', $request->kdbarang)->first();
        // if (isset($recmohklruangd->nomohon)) {
        //   $msg = [
        //     'sukses' => 'Data gagal di tambah',
        //   ];
        // } else {
        $mohklruangd->fill([
          'nomohon' => isset($request->nomohond) ? $request->nomohond : '',
          'kdbarang' => isset($request->kdbarang) ? $request->kdbarang : '',
          'nmbarang' => isset($request->nmbarang) ? $request->nmbarang : '',
          'kdsatuan' => isset($request->kdsatuan) ? $request->kdsatuan : '',
          'qty' => isset($request->qty) ? $request->qty : '',
          'harga' => isset($request->harga) ? $request->harga : '',
          'discount' => isset($request->discount) ? $request->discount : '',
          'uang' => isset($request->uang) ? $request->uang : '',
          'total' => isset($request->total) ? $request->total : '',
          'user' => 'Tambah-' . $request->username . ', ' . date('d-m-Y h:i:s'),
        ]);
        $mohklruangd->save($validate);
        $subtotal = Mohklruangd::where('nomohon', $request->nomohond)->sum('uang');
        Mohklruangh::where('nomohon', $request->nomohond)->update([
          'subtotal' => $subtotal
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


  public function mohklruangtotaldetail(Mohklruangh $mohklruangh, Request $request)
  {
    if ($request->Ajax()) {
      $nomohon = $request->nomohon;
      $mohklruangh = Mohklruangh::where('nomohon', $nomohon)->first();
      $data = [
        'menu' => 'transaksi',
        'submenu' => 'mohklruang',
        'submenu1' => 'ref_umum',
        'title' => 'Detail Data Keluar',
      ];
      // var_dump($data);

      // return response()->json([
      //     'data' => $data,
      // ]);
      return response()->json([
        'body' => view('mohklruang.totaldetail', [
          'subtotalbayard' => mohklruangd::where('nomohon', $nomohon)->sum('bayar'),
          'vdata' => $data,
        ])->render(),
        'data' => $data,
      ]);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function destroy(mohklruangd $mohklruangd, Request $request)
  {
    if ($request->Ajax()) {
      $id = $request->id;
      $mohklruangd = mohklruangd::where('id', $id)->first();
      $nomohon = $mohklruangd->nomohon;
      $deleted = Mohklruangd::where('id', $id)->delete();
      if ($deleted) {
        $uang = Mohklruangd::where('nomohon', $nomohon)->sum('uang');
        Mohklruangh::where('nomohon', $nomohon)->update(['subtotal' => $uang]);
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
      $nomohon = $request->nomohon;
      $username = session('username');
      $data = [
        'menu' => 'transaksi',
        'submenu' => 'mohklruang',
        'submenu1' => 'ref_umum',
        'title' => 'Tambah Data Permohonan Keluar Uang',
      ];
      // var_dump($data);

      // return response()->json([
      //     'data' => $data,
      // ]);
      return response()->json([
        'body' => view('mohklruang.modaleditdetail', [
          'mohklruang' => Mohklruangh::where('nomohon', $nomohon)->first(),
          'mohklruangd' => new mohklruangd(),
          'saplikasi' => Saplikasi::where('aktif', 'Y')->first(),
          'action' => route('mohklruangdstore'),
          'vdata' => $data,
        ])->render(),
        'data' => $data,
      ]);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function store(Request $request, mohklruangdRequest $mohklruangdrequest, mohklruangd $mohklruangd)
  {
    if ($request->Ajax()) {
      $validate = $request->validate(
        [
          'nodokumen' => 'required',
        ],
        [
          'nodokumen.required' => 'Dokumen Penjualan harus di isi',
        ],
      );
      if ($validate) {
        $recmohklruangd = mohklruangd::where('nomohon', $request->nomohon)->where('nomohon', $request->nomohon)->first();
        if (isset($recmohklruangd->nomohon)) {
          $msg = [
            'sukses' => 'Data gagal di tambah', //view('tbbarang.tabel_barang')
          ];
        } else {
          $mohklruangd->fill([
            'nodokumen' => isset($request->nodokumen) ? $request->nodokumen : '',
            'tgldokumen' => isset($request->tgldokumen) ? $request->tgldokumen : '',
            'nomohon' => isset($request->nomohon) ? $request->nomohon : '',
            'kdsupplier' => isset($request->kdsupplier) ? $request->kdsupplier : '',
            'nmsupplier' => isset($request->nmsupplier) ? $request->nmsupplier : '',
            'uang' => isset($request->uang) ? $request->uang : '',
            'keterangan' => isset($request->keterangan) ? $request->keterangan : '',
            'user' => 'Tambah-' . $request->username . ', ' . date('d-m-Y h:i:s'),
          ]);
          $mohklruangd->save($validate);
          $uang = Mohklruangd::where('nomohon', $request->nomohon)->sum('uang');
          $mohklruangh = Mohklruangh::where('nomohon', $request->nomohon)->first();
          $total = $uang + $mohklruangh->materai;
          $kurang = $total - $mohklruangh->bayar;
          Mohklruangh::where('nomohon', $request->nomohon)->update([
            'subtotal' => $uang,
            'total' => $total,
            'kurang' => $kurang,
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

  public function edit(mohklruangd $mohklruangd, Request $request)
  {
    if ($request->Ajax()) {
      // $id = $_GET['id'];
      $id = $request->id;
      $row = mohklruangd::where('id', $id)->first();
      $nomohon = $row->nomohon;
      $data = [
        'menu' => 'transaksi',
        'submenu' => 'Permohonan Keluar Uang',
        'submenu1' => 'ref_umum',
        'title' => 'Edit Data Permohonan Keluar Uang',
      ];
      // var_dump($data);

      // return response()->json([
      //     'data' => $data,
      // ]);
      return response()->json([
        'body' => view('mohklruang.modaleditdetail', [
          'mohklruang' => Mohklruangh::where('nomohon', $nomohon)->first(),
          'mohklruangd' => mohklruangd::where('id', $id)->first(),
          'saplikasi' => Saplikasi::where('aktif', 'Y')->first(),
          // 'action' => route('so.update', $mohklruang->id),
          'action' => 'mohklruangdupdate',
          'vdata' => $data,
        ])->render(),
        'data' => $data,
      ]);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function update(Request $request, mohklruangd $mohklruangd)
  {
    if ($request->Ajax()) {
      $id = $request->id;
      $validate = $request->validate(
        [
          'nomohon' => 'required',
        ],
        [
          'nomohon.required' => 'Dokumen Penjualan harus di isi',
        ],
      );

      $mohklruangd = mohklruangd::find($id);
      if ($validate) {
        // $recmohklruangd = mohklruangd::where('nomohon', $request->nomohond)->where('kdbarang', $request->kdbarang)->first();
        // if (isset($recmohklruangd->nomohon)) {
        //   $msg = [
        //     'sukses' => 'Data gagal di tambah', //view('tbbarang.tabel_barang')
        //   ];
        // } else {
        $mohklruangd->fill([
          'nodokumen' => isset($request->nodokumen) ? $request->nodokumen : '',
          'tgldokumen' => isset($request->tgldokumen) ? $request->tgldokumen : '',
          'nomohon' => isset($request->nomohon) ? $request->nomohon : '',
          'kdsupplier' => isset($request->kdsupplier) ? $request->kdsupplier : '',
          'nmsupplier' => isset($request->nmsupplier) ? $request->nmsupplier : '',
          'uang' => isset($request->uang) ? $request->uang : '',
          'keterangan' => isset($request->keterangan) ? $request->keterangan : '',
          'user' => 'Tambah-' . $request->username . ', ' . date('d-m-Y h:i:s'),
        ]);
        $mohklruangd->save($validate);
        $uang = Mohklruangd::where('nomohon', $request->nomohon)->sum('uang');
        $mohklruangh = Mohklruangh::where('nomohon', $request->nomohon)->first();
        $total = $uang + $mohklruangh->materai;
        $kurang = $total - $mohklruangh->bayar;
        Mohklruangh::where('nomohon', $request->nomohon)->update([
          'subtotal' => $uang,
          'total' => $total,
          'kurang' => $kurang,
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
