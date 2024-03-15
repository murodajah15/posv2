<?php

namespace App\Http\Controllers;

use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;
use App\Http\Requests\Kasir_tagihandRequest;
use Illuminate\Support\Facades\DB;
use App\Models\Kasir_tagihand;
use App\Models\Kasir_tagihan;
use App\Models\Saplikasi;
use App\Models\Userdtl;

class Kasir_tagihandController extends Controller
{
  public function kasir_tagihaninputkasir_tagihand(kasir_tagihan $kasir_tagihan, Request $request)
  {
    if ($request->Ajax()) {
      $id = $_GET['id'];
      $username = session('username');
      $kasir_tagihan = Kasir_tagihan::where('id', $id)->first();
      $nokwitansi = $kasir_tagihan->nokwitansi;
      $data = [
        'menu' => 'transaksi',
        'submenu' => 'kasir_tagihan',
        'submenu1' => 'ref_umum',
        'title' => 'Detail Data Kasir Penerimaan Tagihan',
      ];
      // var_dump($data);

      // return response()->json([
      //     'data' => $data,
      // ]);
      return response()->json([
        'body' => view('kasir_tagihan.modaldetail', [
          'userdtlmenu' => Userdtl::join('tbmodule', 'userdtl.cmodule', '=', 'tbmodule.cmodule')->where('userdtl.pakai', '1')->where('username', $username)->orderBy('userdtl.nurut')->get(),
          'userdtl' => Userdtl::where('cmodule', 'Kasir Penerimaan Tagihan')->where('username', $username)->first(),
          'saplikasi' => Saplikasi::where('aktif', 'Y')->first(),
          'kasir_tagihan' => Kasir_tagihan::where('id', $id)->first(),
          'kasir_tagihand' => kasir_tagihand::where('nokwitansi', $nokwitansi)->get(),
          // 'action' => route('kasir_tagihan.update', $kasir_tagihan->id),
          'action' => 'kasir_tagihantambahdetail',
          'vdata' => $data,
        ])->render(),
        'data' => $data,
      ]);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function kasir_tagihandajax(Request $request) //: View
  {
    $nokwitansi = $request->nokwitansi;
    if ($request->ajax()) {
      $data = Kasir_tagihand::where('nokwitansi', $nokwitansi); //->orderBy('kode', 'asc');
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

  public function kasir_tagihantambahdetail(Request $request, kasir_tagihandRequest $kasir_tagihandrequest, kasir_tagihand $kasir_tagihand)
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
        // $reckasir_tagihand = kasir_tagihand::where('nokwitansi', $request->nokwitansid)->where('kdbarang', $request->kdbarang)->first();
        // if (isset($reckasir_tagihand->nokwitansi)) {
        //   $msg = [
        //     'sukses' => 'Data gagal di tambah',
        //   ];
        // } else {
        $kasir_tagihand->fill([
          'nokwitansi' => isset($request->nokwitansid) ? $request->nokwitansid : '',
          'kdbarang' => isset($request->kdbarang) ? $request->kdbarang : '',
          'nmbarang' => isset($request->nmbarang) ? $request->nmbarang : '',
          'kdsatuan' => isset($request->kdsatuan) ? $request->kdsatuan : '',
          'qty' => isset($request->qty) ? $request->qty : '',
          'harga' => isset($request->harga) ? $request->harga : '',
          'discount' => isset($request->discount) ? $request->discount : '',
          'bayar' => isset($request->bayar) ? $request->bayar : '',
          'total' => isset($request->total) ? $request->total : '',
          'user' => 'Tambah-' . $request->username . ', ' . date('d-m-Y h:i:s'),
        ]);
        $kasir_tagihand->save($validate);
        $bayar = Kasir_tagihand::where('nokwitansi', $request->nokwitansid)->sum('bayar');
        Kasir_tagihan::where('nokwitansi', $request->nokwitansid)->update([
          'bayar' => $bayar
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


  public function kasir_tagihantotaldetail(kasir_tagihan $kasir_tagihan, Request $request)
  {
    if ($request->Ajax()) {
      $nokwitansi = $request->nokwitansi;
      $kasir_tagihan = Kasir_tagihan::where('nokwitansi', $nokwitansi)->first();
      $data = [
        'menu' => 'transaksi',
        'submenu' => 'kasir_tagihan',
        'submenu1' => 'ref_umum',
        'title' => 'Detail Data Pengeluaran',
      ];
      // var_dump($data);

      // return response()->json([
      //     'data' => $data,
      // ]);
      return response()->json([
        'body' => view('kasir_tagihan.totaldetail', [
          'subtotalpiutangd' => kasir_tagihand::where('nokwitansi', $nokwitansi)->sum('piutang'),
          'subtotalbayard' => kasir_tagihand::where('nokwitansi', $nokwitansi)->sum('bayar'),
          'vdata' => $data,
        ])->render(),
        'data' => $data,
      ]);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function destroy(kasir_tagihand $kasir_tagihand, Request $request)
  {
    if ($request->Ajax()) {
      $id = $request->id;
      $kasir_tagihand = Kasir_tagihand::where('id', $id)->first();
      $nokwitansi = $kasir_tagihand->nokwitansi;
      $deleted = Kasir_tagihand::where('id', $id)->delete();
      if ($deleted) {
        $bayar = Kasir_tagihand::where('nokwitansi', $nokwitansi)->sum('bayar');
        Kasir_tagihan::where('nokwitansi', $nokwitansi)->update(['total' => $bayar]);
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
      $nokwitansi = $request->nokwitansi;
      $username = session('username');
      $data = [
        'menu' => 'transaksi',
        'submenu' => 'kasir_tagihan',
        'submenu1' => 'ref_umum',
        'title' => 'Tambah Data Penerimaan tagihan',
      ];
      // var_dump($data);

      // return response()->json([
      //     'data' => $data,
      // ]);
      return response()->json([
        'body' => view('kasir_tagihan.modaleditdetail', [
          'kasir_tagihan' => Kasir_tagihan::where('nokwitansi', $nokwitansi)->first(),
          'kasir_tagihand' => new kasir_tagihand(),
          'saplikasi' => Saplikasi::where('aktif', 'Y')->first(),
          'action' => route('kasir_tagihandstore'),
          'vdata' => $data,
        ])->render(),
        'data' => $data,
      ]);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function store(Request $request, kasir_tagihandRequest $kasir_tagihandrequest, kasir_tagihand $kasir_tagihand)
  {
    if ($request->Ajax()) {
      $validate = $request->validate(
        [
          'nojual' => 'required',
        ],
        [
          'nojual.required' => 'Dokumen Penjualan harus di isi',
        ],
      );
      if ($validate) {
        $reckasir_tagihand = Kasir_tagihand::where('nokwitansi', $request->nokwitansi)->where('nojual', $request->nojual)->first();
        if (isset($reckasir_tagihand->nokwitansi)) {
          $msg = [
            'sukses' => 'Data gagal di tambah', //view('tbbarang.tabel_barang')
          ];
        } else {
          $kasir_tagihand->fill([
            'nokwitansi' => isset($request->nokwitansi) ? $request->nokwitansi : '',
            'nojual' => isset($request->nojual) ? $request->nojual : '',
            'kdcustomer' => isset($request->kdcustomer) ? $request->kdcustomer : '',
            'nmcustomer' => isset($request->nmcustomer) ? $request->nmcustomer : '',
            'piutang' => isset($request->piutang) ? $request->piutang : '',
            'bayar' => isset($request->bayar) ? $request->bayar : '',
            'user' => 'Tambah-' . $request->username . ', ' . date('d-m-Y h:i:s'),
          ]);
          $kasir_tagihand->save($validate);
          $bayar = Kasir_tagihand::where('nokwitansi', $request->nokwitansi)->sum('bayar');
          Kasir_tagihan::where('nokwitansi', $request->nokwitansi)->update([
            'total' => $bayar,
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

  public function edit(kasir_tagihand $kasir_tagihand, Request $request)
  {
    if ($request->Ajax()) {
      // $id = $_GET['id'];
      $id = $request->id;
      $row = Kasir_tagihand::where('id', $id)->first();
      $nokwitansi = $row->nokwitansi;
      $data = [
        'menu' => 'transaksi',
        'submenu' => 'Penerimaan tagihan',
        'submenu1' => 'ref_umum',
        'title' => 'Edit Data Penerimaan tagihan',
      ];
      // var_dump($data);

      // return response()->json([
      //     'data' => $data,
      // ]);
      return response()->json([
        'body' => view('kasir_tagihan.modaleditdetail', [
          'kasir_tagihan' => Kasir_tagihan::where('nokwitansi', $nokwitansi)->first(),
          'kasir_tagihand' => Kasir_tagihand::where('id', $id)->first(),
          'saplikasi' => Saplikasi::where('aktif', 'Y')->first(),
          // 'action' => route('so.update', $kasir_tagihan->id),
          'action' => 'kasir_tagihandupdate',
          'vdata' => $data,
        ])->render(),
        'data' => $data,
      ]);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function update(Request $request, kasir_tagihand $kasir_tagihand)
  {
    if ($request->Ajax()) {
      $id = $request->id;
      $validate = $request->validate(
        [
          'nojual' => 'required',
        ],
        [
          'nojual.required' => 'Dokumen Penjualan harus di isi',
        ],
      );

      $kasir_tagihand = Kasir_tagihand::find($id);
      if ($validate) {
        // $reckasir_tagihand = Kasir_tagihand::where('nokwitansi', $request->nokwitansid)->where('kdbarang', $request->kdbarang)->first();
        // if (isset($reckasir_tagihand->nokwitansi)) {
        //   $msg = [
        //     'sukses' => 'Data gagal di tambah', //view('tbbarang.tabel_barang')
        //   ];
        // } else {
        $kasir_tagihand->fill([
          'nokwitansi' => isset($request->nokwitansi) ? $request->nokwitansi : '',
          'nojual' => isset($request->nojual) ? $request->nojual : '',
          'kdcustomer' => isset($request->kdcustomer) ? $request->kdcustomer : '',
          'nmcustomer' => isset($request->nmcustomer) ? $request->nmcustomer : '',
          'piutang' => isset($request->piutang) ? $request->piutang : '',
          'bayar' => isset($request->bayar) ? $request->bayar : '',
          'user' => 'Tambah-' . $request->username . ', ' . date('d-m-Y h:i:s'),
        ]);
        $kasir_tagihand->save($validate);
        $bayar = Kasir_tagihand::where('nokwitansi', $request->nokwitansi)->sum('bayar');
        // dd($bayar . $request->nokwitansi);
        Kasir_tagihan::where('nokwitansi', $request->nokwitansi)->update([
          'total' => $bayar
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
