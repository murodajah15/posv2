<?php

namespace App\Http\Controllers;

use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;
use App\Http\Requests\Kasir_keluardRequest;
use Illuminate\Support\Facades\DB;
use App\Models\Mohklruangd;
use App\Models\Kasir_keluard;
use App\Models\Kasir_keluar;
use App\Models\Saplikasi;
use App\Models\Userdtl;

class Kasir_keluardController extends Controller
{
  public function kasir_keluarinputkasir_keluard(kasir_keluar $kasir_keluar, Request $request)
  {
    if ($request->Ajax()) {
      $id = $_GET['id'];
      $username = session('username');
      $kasir_keluar = Kasir_keluar::where('id', $id)->first();
      $nokwitansi = $kasir_keluar->nokwitansi;
      $data = [
        'menu' => 'transaksi',
        'submenu' => 'kasir_keluar',
        'submenu1' => 'ref_umum',
        'title' => 'Detail Data Kasir Pengeluaran Uang',
      ];
      // var_dump($data);

      // return response()->json([
      //     'data' => $data,
      // ]);
      return response()->json([
        'body' => view('kasir_keluar.modaldetail', [
          'userdtlmenu' => Userdtl::join('tbmodule', 'userdtl.cmodule', '=', 'tbmodule.cmodule')->where('userdtl.pakai', '1')->where('username', $username)->orderBy('userdtl.nurut')->get(),
          'userdtl' => Userdtl::where('cmodule', 'Kasir Pengeluaran Uang')->where('username', $username)->first(),
          'saplikasi' => Saplikasi::where('aktif', 'Y')->first(),
          'kasir_keluar' => Kasir_keluar::where('id', $id)->first(),
          'kasir_keluard' => kasir_keluard::where('nokwitansi', $nokwitansi)->get(),
          // 'action' => route('kasir_keluar.update', $kasir_keluar->id),
          'action' => 'kasir_keluartambahdetail',
          'vdata' => $data,
        ])->render(),
        'data' => $data,
      ]);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function kasir_keluardajax(Request $request) //: View
  {
    $nokwitansi = $request->nokwitansi;
    if ($request->ajax()) {
      $data = Kasir_keluard::where('nokwitansi', $nokwitansi); //->orderBy('kode', 'asc');
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

  public function kasir_keluartambahdetail(Request $request, kasir_keluardRequest $kasir_keluardrequest, kasir_keluard $kasir_keluard)
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
        // $reckasir_keluard = kasir_keluard::where('nokwitansi', $request->nokwitansid)->where('kdbarang', $request->kdbarang)->first();
        // if (isset($reckasir_keluard->nokwitansi)) {
        //   $msg = [
        //     'sukses' => 'Data gagal di tambah',
        //   ];
        // } else {
        $kasir_keluard->fill([
          'nokwitansi' => isset($request->nokwitansid) ? $request->nokwitansid : '',
          'kdsupplier' => isset($request->kdsupplier) ? $request->kdsupplier : '',
          'nmsupplier' => isset($request->nmsupplier) ? $request->nmsupplier : '',
          'uang' => isset($request->bayar) ? $request->bayar : '',
          'user' => 'Tambah-' . $request->username . ', ' . date('d-m-Y h:i:s'),
        ]);
        $kasir_keluard->save($validate);
        $subtotal = Kasir_keluard::where('nokwitansi', $request->nokwitansid)->sum('uang');
        $kasir_keluarh = Kasir_keluar::where('nokwitansi', $request->nokwitansid);
        $materai = $kasir_keluarh->materai;
        $total = $subtotal + $materai;
        Kasir_Keluar::where('nokwitansi', $request->nokwitansid)->update([
          'subtotal' => $subtotal,
          'total' => $total,
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


  public function kasir_keluartotaldetail(kasir_keluar $kasir_keluar, Request $request)
  {
    if ($request->Ajax()) {
      $nokwitansi = $request->nokwitansi;
      $kasir_keluar = Kasir_keluar::where('nokwitansi', $nokwitansi)->first();
      $data = [
        'menu' => 'transaksi',
        'submenu' => 'kasir_keluar',
        'submenu1' => 'ref_umum',
        'title' => 'Detail Data Pengeluaran Uang',
      ];
      // var_dump($data);

      // return response()->json([
      //     'data' => $data,
      // ]);
      return response()->json([
        'body' => view('kasir_keluar.totaldetail', [
          'subtotaluangd' => kasir_keluard::where('nokwitansi', $nokwitansi)->sum('uang'),
          'vdata' => $data,
        ])->render(),
        'data' => $data,
      ]);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function destroy(kasir_keluard $kasir_keluard, Request $request)
  {
    if ($request->Ajax()) {
      $id = $request->id;
      $kasir_keluard = Kasir_keluard::where('id', $id)->first();
      $nokwitansi = $kasir_keluard->nokwitansi;
      $deleted = Kasir_keluard::where('id', $id)->delete();
      if ($deleted) {
        $subtotal = Kasir_keluard::where('nokwitansi', $nokwitansi)->sum('uang');
        $kasir_keluarh = Kasir_keluar::where('nokwitansi', $nokwitansi)->first();
        $materai = $kasir_keluarh->materai;
        $total = $subtotal + $materai;
        Kasir_Keluar::where('nokwitansi', $nokwitansi)->update(['subtotal' => $subtotal, 'total' => $total]);
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
        'submenu' => 'kasir_keluar',
        'submenu1' => 'ref_umum',
        'title' => 'Tambah Data Pengeluaran Uang',
      ];
      // var_dump($data);

      // return response()->json([
      //     'data' => $data,
      // ]);
      return response()->json([
        'body' => view('kasir_keluar.modaleditdetail', [
          'kasir_keluar' => Kasir_keluar::where('nokwitansi', $nokwitansi)->first(),
          'kasir_keluard' => new kasir_keluard(),
          'saplikasi' => Saplikasi::where('aktif', 'Y')->first(),
          'action' => route('kasir_keluardstore'),
          'vdata' => $data,
        ])->render(),
        'data' => $data,
      ]);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  // public function store(Request $request, kasir_keluardRequest $kasir_keluardrequest, kasir_keluard $kasir_keluard)
  public function store(Request $request, kasir_keluard $kasir_keluard)
  {
    if ($request->Ajax()) {
      if (isset($request->permohonan)) {
        $nomohon = $request->nomohon;
        //Delete record yang ada dulu
        Kasir_keluard::where('nomohon', $nomohon)->delete();
        $mohklruangd = Mohklruangd::where('nomohon', $nomohon)->get();
        foreach ($mohklruangd as $row) {
          Kasir_keluard::insert([
            'nokwitansi' => $request->nokwitansi, 'nodokumen' => $row->nodokumen, 'tgldokumen' => $row->tgldokumen,
            'kdsupplier' => $row->kdsupplier, 'nmsupplier' => $row->nmsupplier,
            'uang' => $row->uang, 'nomohon' => $row->nomohon,
          ]);
        }
        $msg = [
          'sukses' => 'Data berhasil di tambah', //view('tbbarang.tabel_barang')
        ];
      } else {
        $validate = $request->validate(
          [
            'nodokumen' => 'required',
          ],
          [
            'nodokumen.required' => 'Dokumen harus di isi',
          ],
        );
        if ($validate) {
          $reckasir_keluard = Kasir_keluard::where('nokwitansi', $request->nokwitansi)->where('nodokumen', $request->nodokumen)->first();
          if (isset($reckasir_keluard->nokwitansi)) {
            $msg = [
              'sukses' => 'Data gagal di tambah', //view('tbbarang.tabel_barang')
            ];
          } else {
            $kasir_keluard->fill([
              'nokwitansi' => isset($request->nokwitansi) ? $request->nokwitansi : '',
              'nodokumen' => isset($request->nodokumen) ? $request->nodokumen : '',
              'tgldokumen' => isset($request->tgldokumen) ? $request->tgldokumen : '',
              'kdsupplier' => isset($request->kdsupplier) ? $request->kdsupplier : '',
              'nmsupplier' => isset($request->nmsupplier) ? $request->nmsupplier : '',
              'uang' => isset($request->uang) ? $request->uang : '',
              'user' => 'Tambah-' . $request->username . ', ' . date('d-m-Y h:i:s'),
            ]);
            $kasir_keluard->save($validate);
            $subtotal = Kasir_keluard::where('nokwitansi', $request->nokwitansi)->sum('uang');
            $kasir_keluarh = Kasir_keluar::where('nokwitansi', $request->nokwitansi)->first();
            $materai = $kasir_keluarh->materai;
            $total = $subtotal + $materai;
            Kasir_Keluar::where('nokwitansi', $request->nokwitansi)->update(['subtotal' => $subtotal, 'total' => $total]);
            $msg = [
              'sukses' => 'Data berhasil di tambah', //view('tbbarang.tabel_barang')
            ];
          }
        } else {
          $msg = [
            'sukses' => 'Data gagal di tambah', //view('tbbarang.tabel_barang')
          ];
        }
      }
      echo json_encode($msg);
      // return redirect()->back()->with('message', 'Berhasil di update');
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function edit(kasir_keluard $kasir_keluard, Request $request)
  {
    if ($request->Ajax()) {
      // $id = $_GET['id'];
      $id = $request->id;
      $row = Kasir_keluard::where('id', $id)->first();
      $nokwitansi = $row->nokwitansi;
      $data = [
        'menu' => 'transaksi',
        'submenu' => 'kasir_keluara',
        'submenu1' => 'ref_umum',
        'title' => 'Edit Data Pengeluaran Uang',
      ];
      // var_dump($data);

      // return response()->json([
      //     'data' => $data,
      // ]);
      return response()->json([
        'body' => view('kasir_keluar.modaleditdetail', [
          'kasir_keluar' => Kasir_keluar::where('nokwitansi', $nokwitansi)->first(),
          'kasir_keluard' => Kasir_keluard::where('id', $id)->first(),
          'saplikasi' => Saplikasi::where('aktif', 'Y')->first(),
          // 'action' => route('so.update', $kasir_keluar->id),
          'action' => 'kasir_keluardupdate',
          'vdata' => $data,
        ])->render(),
        'data' => $data,
      ]);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function update(Request $request, kasir_keluard $kasir_keluard)
  {
    if ($request->Ajax()) {
      $id = $request->id;
      $validate = $request->validate(
        [
          'nodokumen' => 'required',
        ],
        [
          'nodokumen.required' => 'Dokumen harus di isi',
        ],
      );

      $kasir_keluard = Kasir_keluard::find($id);
      if ($validate) {
        // $reckasir_keluard = Kasir_keluard::where('nokwitansi', $request->nokwitansid)->where('kdbarang', $request->kdbarang)->first();
        // if (isset($reckasir_keluard->nokwitansi)) {
        //   $msg = [
        //     'sukses' => 'Data gagal di tambah', //view('tbbarang.tabel_barang')
        //   ];
        // } else {
        $kasir_keluard->fill([
          'nokwitansi' => isset($request->nokwitansi) ? $request->nokwitansi : '',
          'nodokumen' => isset($request->nodokumen) ? $request->nodokumen : '',
          'tgldokumen' => isset($request->tgldokumen) ? $request->tgldokumen : '',
          'kdsupplier' => isset($request->kdsupplier) ? $request->kdsupplier : '',
          'nmsupplier' => isset($request->nmsupplier) ? $request->nmsupplier : '',
          'uang' => isset($request->uang) ? $request->uang : '',
          'user' => 'Update-' . $request->username . ', ' . date('d-m-Y h:i:s'),
        ]);
        $kasir_keluard->save($validate);
        $subtotal = Kasir_keluard::where('nokwitansi', $request->nokwitansi)->sum('uang');
        $kasir_keluarh = Kasir_keluar::where('nokwitansi', $request->nokwitansi)->first();
        $materai = $kasir_keluarh->materai;
        $total = $subtotal + $materai;
        Kasir_Keluar::where('nokwitansi', $request->nokwitansi)->update(['subtotal' => $subtotal, 'total' => $total]);
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
