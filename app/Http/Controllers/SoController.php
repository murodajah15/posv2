<?php

namespace App\Http\Controllers;

use Mpdf\Mpdf;
use Mpdf\Output\Destination;
use App\Http\Requests\SohRequest;
use App\Http\Requests\SodRequest;
use Illuminate\Http\Request;
use Session;
// use Yajra\DataTables\Contracts\DataTables;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use App\Models\Soh;
use App\Models\Sod;
// use App\Models\Tbsales;
use App\Models\Tbbarang;
use App\Models\Tbmultiprc;
use App\Models\Userdtl;
use App\Models\Saplikasi;

// //return type View
// use Illuminate\View\View;

class SoController extends Controller
{
  public function index(Request $request) //: View
  {
    $username = session('username');
    $data = [
      'menu' => 'transaksi',
      'submenu' => 'so',
      'submenu1' => 'ref_umum',
      'title' => 'Sales Order',
      // 'tbbarang' => Tbbarang::all(),
      'userdtlmenu' => Userdtl::join('tbmodule', 'userdtl.cmodule', '=', 'tbmodule.cmodule')->where('userdtl.pakai', '1')->where('username', $username)->orderBy('userdtl.nurut')->get(),
      'userdtl' => Userdtl::where('cmodule', 'Sales Order')->where('username', $username)->first(),
    ];
    $userdtl = Userdtl::where('cmodule', 'Sales Order')->where('username', $username)->first();
    if ($userdtl->pakai == '1') {
      return view('so.index')->with($data);
    } else {
      return redirect('home');
    }
  }
  public function soajax(Request $request) //: View
  {
    if ($request->ajax()) {
      $data = Soh::select('*'); //->orderBy('kode', 'asc');
      return Datatables::of($data)
        ->addIndexColumn()
        ->addColumn('kode1', function ($row) {
          $id = $row['id'];
          $btn = $row['kode']; //'<a href="#"' . 'onclick=detail(' . $id . ')>' .  $row['kode'] . '</a>';
          return $btn;
        })
        ->rawColumns(['kode1'])
        ->make(true);
      return view('so');
    }
  }

  public function create(Request $request)
  {
    if ($request->Ajax()) {
      $username = session('username');
      $data = [
        'menu' => 'transaksi',
        'submenu' => 'so',
        'submenu1' => 'ref_umum',
        'title' => 'Tambah Data Sales Order',
      ];
      return response()->json([
        'body' => view('so.modaltambahmaster', [
          'tambahtbnegara' => Userdtl::where('cmodule', 'Tabel Negara')->where('username', $username)->first(),
          'tambahtbjnbrg' => Userdtl::where('cmodule', 'Tabel Jenis Barang')->where('username', $username)->first(),
          'tambahtbsatuan' => Userdtl::where('cmodule', 'Tabel Satuan')->where('username', $username)->first(),
          'tambahtbmove' => Userdtl::where('cmodule', 'Tabel Perputaran Barang')->where('username', $username)->first(),
          'tambahtbdisc' => Userdtl::where('cmodule', 'Tabel Discount')->where('username', $username)->first(),
          'saplikasi' => Saplikasi::where('aktif', 'Y')->first(),
          // 'tbsales' => Tbsales::orderBy('nama')->get(),
          'so' => new Soh(),
          'action' => route('so.store'),
          'vdata' => $data,
        ])->render(),
        'data' => $data,

      ]);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function store(SohRequest $request, Soh $soh)
  // public function store(Request $request, Soh $soh)
  {
    if ($request->Ajax()) {
      $sort_num = 0;
      $new_code = $request->noso;
      $ketemu = 0;
      $record = 0;
      $rec = Soh::where('noso', $new_code)->first();
      if ($rec == null) {
        $aplikasi = Saplikasi::where('aktif', 'Y')->first();
        $sort_num = $aplikasi->noso;
        $tahun = $aplikasi->tahun;
        $bulan = $aplikasi->bulan;
        Saplikasi::where('aktif', 'Y')->update(['noso' => $sort_num + 1]);
      } else {
        while ($ketemu == $record) { //0=0
          $aplikasi = Saplikasi::where('aktif', 'Y')->first();
          $sort_num = $aplikasi->noso;
          $tahun = $aplikasi->tahun;
          $bulan = $aplikasi->bulan;
          Saplikasi::where('aktif', 'Y')->update(['noso' => $sort_num + 1]);
          $new_code = 'SO' . $tahun . sprintf('%02s', $bulan) . sprintf("%05s", $sort_num + 1);
          $rec = Soh::where('noso', $new_code)->first();
          if ($rec == null) {
            $record = 0;
            Saplikasi::where('aktif', 'Y')->update(['noso' => $sort_num + 1]);
            break;
          } else {
            Saplikasi::where('aktif', 'Y')->update(['noso' => $sort_num + 1]);
          }
        }
      }
      $validated = $request->validated();
      if ($validated) {
        $subtotal = 0;
        $biaya_lain = isset($request->biaya_lain) ? $request->biaya_lain : '0';
        $materai = isset($request->materai) ? $request->materai : '0';
        $ppn = isset($request->ppn) ? $request->ppn : '0';
        $total_sementara = $biaya_lain + $subtotal + $materai;
        $total = $total_sementara + ($total_sementara * ($ppn / 100));
        $soh->fill([
          'noso' => isset($request->noso) ? $new_code : '',
          'tglso' => isset($request->tglso) ? $request->tglso : '',
          'noreferensi' => isset($request->noreferensi) ? $request->noreferensi : '',
          'nopo_customer' => isset($request->nopo_customer) ? $request->nopo_customer : '',
          'tglpo_customer' => isset($request->tglpo_customer) ? $request->tglpo_customer : '',
          'kdcustomer' => isset($request->kdcustomer) ? $request->kdcustomer : '',
          'nmcustomer' => isset($request->nmcustomer) ? $request->nmcustomer : '',
          'kdsales' => isset($request->kdsales) ? $request->kdsales : '',
          'nmsales' => isset($request->nmsales) ? $request->nmsales : '',
          'tglkirim' => isset($request->tglkirim) ? $request->tglkirim : '',
          'jenis_order' => isset($request->jenis_order) ? $request->jenis_order : '',
          'carabayar' => isset($request->carabayar) ? $request->carabayar : '',
          'tempo' => isset($request->tempo) ? $request->tempo : '',
          'tgl_jt_tempo' => isset($request->tgl_jt_tempo) ? $request->tgl_jt_tempo : '',
          'ket_biaya_lain' => isset($request->ket_biaya_lain) ? $request->ket_biaya_lain : '',
          'biaya_lain' => $biaya_lain,
          'subtotal' => $subtotal,
          'total_sementara' => $total_sementara,
          'ppn' => $ppn,
          'materai' => $materai,
          'total' => $total,
          'keterangan' => isset($request->keterangan) ? $request->keterangan : '',
          'user' => 'Tambah-' . $request->username . ', ' . date('d-m-Y h:i:s'),
        ]);
        $soh->save($validated);
        //Create History
        $tanggal = date('Y-m-d');
        $datetime = date('Y-m-d H:i:s');
        $dokumen = $new_code;
        $form = 'Sales Order';
        $status = 'Tambah';
        $catatan = isset($request->catatan) ? $request->catatan : '';
        $username = session('username');
        DB::table('hisuser')->insert(['tanggal' => $tanggal, 'dokumen' => $dokumen, 'form' => $form, 'status' => $status, 'user' => $username, 'catatan' => $catatan, 'datetime' => $datetime]);
        $msg = [
          'sukses' => 'Data berhasil di tambah', //view('tbbarang.tabel_barang')
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
        'menu' => 'transaksi',
        'submenu' => 'so',
        'submenu1' => 'ref_umum',
        'title' => 'Detail Sales Order',
        // 'userdtl' => Userdtl::where('cmodule', 'Sales Order')->where('username', $username)->first(),
      ];
      return response()->json([
        'body' => view('so.modaltambahmaster', [
          'saplikasi' => Saplikasi::where('aktif', 'Y')->first(),
          // 'tbsales' => Tbsales::get(),
          'so' => Soh::where('id', $id)->first(),
          'action' => route('tbbarang.store'),
          'vdata' => $data,
        ])->render(),
        'data' => $data,
      ]);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function edit(Soh $soh, Request $request)
  {
    if ($request->Ajax()) {
      $id = $_GET['id'];
      $username = session('username');
      $data = [
        'menu' => 'transaksi',
        'submenu' => 'so',
        'submenu1' => 'ref_umum',
        'title' => 'Edit Data Sales Order',
      ];
      // var_dump($data);

      // return response()->json([
      //     'data' => $data,
      // ]);
      return response()->json([
        'body' => view('so.modaltambahmaster', [
          'saplikasi' => Saplikasi::where('aktif', 'Y')->first(),
          // 'tbsales' => Tbsales::get(),
          'so' => Soh::where('id', $id)->first(),
          // 'action' => route('so.update', $soh->id),
          'action' => 'soupdate',
          'vdata' => $data,
        ])->render(),
        'data' => $data,
      ]);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function update(Request $request, Soh $soh)
  {
    if ($request->Ajax()) {
      $id = $request->id;
      if ($request->noso === $request->nosolama) {
        $validate = $request->validate(
          [
            'noso' => 'required',
            'tglso' => 'required',
          ],
          [
            'noso.required' => 'No. SO harus di isi',
            'tglso.required' => 'Tanggal SO harus di isi',
          ],
        );
      } else {
        $validate = $request->validate(
          [
            'noso' => 'required|unique:soh|max:255',
            'tglso' => 'required',
          ],
          [
            'noso.required' => 'No. SO harus di isi',
            'tglso.required' => 'Tanggal SO harus di isi',
          ],
        );
      }
      $soh = soh::find($id);
      if ($validate) {
        $noso = $request->noso;
        $subtotal = DB::table('sod')->where('noso', $noso)->sum('subtotal');
        $biaya_lain = isset($request->biaya_lain) ? $request->biaya_lain : '0';
        $materai = isset($request->materai) ? $request->materai : '0';
        $ppn = isset($request->ppn) ? $request->ppn : '0';
        $total_sementara = $biaya_lain + $subtotal + $materai;
        $total = $total_sementara + ($total_sementara * ($ppn / 100));
        $soh->fill([
          'noso' => isset($request->noso) ? $request->noso : '',
          'tglso' => isset($request->tglso) ? $request->tglso : '',
          'noreferensi' => isset($request->noreferensi) ? $request->noreferensi : '',
          'nopo_customer' => isset($request->nopo_customer) ? $request->nopo_customer : '',
          'tglpo_customer' => isset($request->tglpo_customer) ? $request->tglpo_customer : '',
          'kdcustomer' => isset($request->kdcustomer) ? $request->kdcustomer : '',
          'nmcustomer' => isset($request->nmcustomer) ? $request->nmcustomer : '',
          'kdsales' => isset($request->kdsales) ? $request->kdsales : '',
          'nmsales' => isset($request->nmsales) ? $request->nmsales : '',
          'tglkirim' => isset($request->tglkirim) ? $request->tglkirim : '',
          'jenis_order' => isset($request->jenis_order) ? $request->jenis_order : '',
          'carabayar' => isset($request->carabayar) ? $request->carabayar : '',
          'tempo' => isset($request->tempo) ? $request->tempo : '',
          'tgl_jt_tempo' => isset($request->tgl_jt_tempo) ? $request->tgl_jt_tempo : '',
          'ket_biaya_lain' => isset($request->ket_biaya_lain) ? $request->ket_biaya_lain : '',
          'biaya_lain' => $biaya_lain,
          'subtotal' => $subtotal,
          'total_sementara' => $total_sementara,
          'ppn' => $ppn,
          'materai' => $materai,
          'total' => $total,
          'keterangan' => isset($request->keterangan) ? $request->keterangan : '',
          'user' => 'Update-' . $request->username . ', ' . date('d-m-Y h:i:s'),
        ]);
        $soh->save($validate);
        //Create History
        $tanggal = date('Y-m-d');
        $datetime = date('Y-m-d H:i:s');
        $dokumen = $noso;
        $form = 'Sales Order';
        $status = 'Update';
        $catatan = isset($request->catatan) ? $request->catatan : '';
        $username = session('username');
        DB::table('hisuser')->insert(['tanggal' => $tanggal, 'dokumen' => $dokumen, 'form' => $form, 'status' => $status, 'user' => $username, 'catatan' => $catatan, 'datetime' => $datetime]);
        $msg = [
          'sukses' => 'Data berhasil di update', //view('tbbarang.tabel_barang')
        ];
      } else {
        $msg = [
          'sukses' => 'Data gagal di update', //view('tbbarang.tabel_barang')
        ];
      }
      echo json_encode($msg);
      // return redirect()->back()->with('message', 'Berhasil di update');
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function soproses(Request $request, Soh $soproses)
  {
    if ($request->Ajax()) {
      $id = $request->id;
      $soh = Soh::where('id', $id)->first();
      $noso = $soh->noso;
      $soproses->load('soDetail');
      $subtotal = $soproses->soDetail->sum('subtotal');
      $total_sementara = $soproses->biaya_lain + $subtotal;
      // $soproses->proses = 'Y';
      // $soproses->subtotal = $subtotal;
      // $soproses->total_sementara = $total_sementara;
      // $soproses->total = $total_sementara + ($total_sementara * ($soproses->ppn / 100));
      // $soproses->user = 'Proses-' . session('username') . ', ' . date('d-m-Y h:i:s');
      // $soproses->save();
      $soh = Soh::find($id);
      $soh->fill([
        'proses' => 'Y',
        'subtotal' => $subtotal,
        'total_sementara' => $total_sementara,
        'total' => $total_sementara + ($total_sementara * ($soh->ppn / 100)),
        'user' => 'Proses-' . session('username') . ', ' . date('d-m-Y h:i:s'),
      ]);
      $soh->save();
      $sod = Sod::where('noso', $noso)->get();
      foreach ($sod as $row) {
        $idd = $row->id;
        $qty = $row->qty;
        DB::table('sod')->where('id', $idd)->update(['proses' => 'Y', 'kurang' => $qty]);
      }
      //Create History
      $tanggal = date('Y-m-d');
      $datetime = date('Y-m-d H:i:s');
      $dokumen = $noso;
      $form = 'Sales Order';
      $status = 'Proses';
      $catatan = isset($request->catatan) ? $request->catatan : '';
      $username = session('username');
      DB::table('hisuser')->insert(['tanggal' => $tanggal, 'dokumen' => $dokumen, 'form' => $form, 'status' => $status, 'user' => $username, 'catatan' => $catatan, 'datetime' => $datetime]);
      return response()->json([
        'sukses' => 'Data berhasil di Cancel', //view('tbbarang.tabel_barang')
      ]);
      // return redirect()->back()->with('message', 'Berhasil di update');
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function sobatalproses(Soh $soh, Request $request)
  {
    if ($request->Ajax()) {
      $id = $_GET['id'];
      $soh = Soh::where('id', $id)->first();
      $sod = Sod::where('noso', $soh->noso)->where('terima', '>', '0')->first();
      if (isset($sod->noso)) {
        $msg = [
          'sukses' => 'Data gagal di cancel', //view('tbbarang.tabel_barang')
        ];
        echo json_encode($msg);
      } else {
        $data = [
          'menu' => 'transaksi',
          'submenu' => 'so',
          'submenu1' => 'ref_umum',
          'title' => 'Batal Proses Sales Order',
        ];
        // var_dump($data);

        // return response()->json([
        //     'data' => $data,
        // ]);
        return response()->json([
          'body' => view('so.modalbatalproses', [
            'so' => Soh::where('id', $id)->first(),
            // 'action' => route('so.update', $soh->id),
            'action' => 'sobatalprosesok',
            'vdata' => $data,
          ])->render(),
          'data' => $data,
        ]);
      }
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function sobatalprosesok(Request $request, Soh $soh)
  {
    if ($request->Ajax()) {
      $id = $request->id;
      $user = 'Proses-' . session('username') . ', ' . date('d-m-Y h:i:s');
      DB::table('soh')->where('id', $id)->update(['proses' => 'N', 'user' => $user]);
      //Create History
      $soh = Soh::where('id', $id)->first();
      $noso = $soh->noso;
      $tanggal = date('Y-m-d');
      $datetime = date('Y-m-d H:i:s');
      $dokumen = $noso;
      $form = 'Sales Order';
      $status = 'Batal Proses';
      $catatan = isset($request->catatan) ? $request->catatan : '';
      $username = session('username');
      DB::table('hisuser')->insert(['tanggal' => $tanggal, 'dokumen' => $dokumen, 'form' => $form, 'status' => $status, 'user' => $username, 'catatan' => $catatan, 'datetime' => $datetime]);
      $msg = [
        'sukses' => 'Data berhasil di Cancel', //view('tbbarang.tabel_barang')
      ];
      echo json_encode($msg);
      // return redirect()->back()->with('message', 'Berhasil di update');
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function socancel(Request $request, Soh $soh)
  {
    if ($request->Ajax()) {
      $id = $_POST['id'];
      $user = 'Cancel-' . session('username') . ', ' . date('d-m-Y h:i:s');
      DB::table('soh')->where('id', $id)->update(['batal' => 'Y', 'user' => $user]);
      //Create History
      $soh = Soh::where('id', $id)->first();
      $noso = $soh->noso;
      $tanggal = date('Y-m-d');
      $datetime = date('Y-m-d H:i:s');
      $dokumen = $noso;
      $form = 'Sales Order';
      $status = 'Cancel';
      $catatan = isset($request->catatan) ? $request->catatan : '';
      $username = session('username');
      DB::table('hisuser')->insert(['tanggal' => $tanggal, 'dokumen' => $dokumen, 'form' => $form, 'status' => $status, 'user' => $username, 'catatan' => $catatan, 'datetime' => $datetime]);
      $msg = [
        'sukses' => 'Data berhasil di Cancel', //view('tbbarang.tabel_barang')
      ];
      echo json_encode($msg);
      // return redirect()->back()->with('message', 'Berhasil di update');
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function soambil(Request $request, Soh $soh)
  {
    if ($request->Ajax()) {
      $id = $_POST['id'];
      $user = 'Ambil-' . session('username') . ', ' . date('d-m-Y h:i:s');
      DB::table('soh')->where('id', $id)->update(['batal' => 'N', 'user' => $user]);
      //Create History
      $row = Soh::where('id', $request->id)->first();
      $noso = $row->noso;
      $tanggal = date('Y-m-d');
      $datetime = date('Y-m-d H:i:s');
      $dokumen = $noso;
      $form = 'Sales Order';
      $status = 'Ambil';
      $catatan = isset($request->catatan) ? $request->catatan : '';
      $username = session('username');
      DB::table('hisuser')->insert(['tanggal' => $tanggal, 'dokumen' => $dokumen, 'form' => $form, 'status' => $status, 'user' => $username, 'catatan' => $catatan, 'datetime' => $datetime]);
      $msg = [
        'sukses' => 'Data berhasil di Cancel', //view('tbbarang.tabel_barang')
      ];
      echo json_encode($msg);
      // return redirect()->back()->with('message', 'Berhasil di update');
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function destroy(Soh $soh, Request $request)
  {
    if ($request->Ajax()) {
      $id = $request->id;
      $soh = Soh::where('id', $request->id)->first();
      $deleted = DB::table('soh')->where('id', $id)->delete();
      if ($deleted) {
        DB::table('sod')->where('noso', $soh->noso)->delete();
        //Create History
        $noso = $soh->noso;
        $tanggal = date('Y-m-d');
        $datetime = date('Y-m-d H:i:s');
        $dokumen = $noso;
        $form = 'Sales Order';
        $status = 'Hapus';
        $catatan = isset($request->catatan) ? $request->catatan : '';
        $username = session('username');
        DB::table('hisuser')->insert(['tanggal' => $tanggal, 'dokumen' => $dokumen, 'form' => $form, 'status' => $status, 'user' => $username, 'catatan' => $catatan, 'datetime' => $datetime]);
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

  public function socaritbbarang(Request $request)
  {
    if ($request->Ajax()) {
      $data = [
        // 'menu' => 'transaksi',
        // 'submenu' => 'tbcustomer',
        // 'submenu1' => 'ref_umum',
        'title' => 'Cari Tabel barang',
      ];
      // var_dump($data);
      return response()->json([
        'body' => view('modalcari.modalcaritbbarang', [
          'tbbarang' => Tbbarang::all(),
          'vdata' => $data,
        ])->render(),
        'data' => $data,
      ]);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function sorepltbbarang(Request $request)
  {
    if ($request->Ajax()) {
      $kode = $request->kode_barang; //$_GET['kode_barang'];
      $row = Tbbarang::where('kode', $kode)->first();
      if (isset($row)) {
        $data = [
          'kdbarang' => $row['kode'],
          'nmbarang' => $row['nama'],
          'kdsatuan' => $row['kdsatuan'],
          'harga_jual' => $row['harga_jual'],
        ];
      } else {
        $data = [
          'kdbarang' => '',
          'nmbarang' => '',
          'kdsatuan' => '',
          'harga_jual' => 0,
        ];
      }
      echo json_encode($data);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function socaritbmultiprc(Request $request)
  {
    if ($request->Ajax()) {
      $kdcustomer = $request->kode_customer;
      // $kdcustomer = $_GET['kode_customer'];
      // dd($kdcustomer);
      $data = [
        // 'menu' => 'transaksi',
        // 'submenu' => 'tbcustomer',
        // 'submenu1' => 'ref_umum',
        'title' => 'Cari Tabel Multi Price',
      ];
      // var_dump($data);
      return response()->json([
        'body' => view('modalcari.modalcaritbmultiprc', [
          // 'tbmultiprc' => Tbmultiprc::all(),
          'tbmultiprc' => Tbmultiprc::join('tbbarang', 'tbmultiprc.kdbarang', '=', 'tbbarang.kode')->where('tbmultiprc.kdcustomer', $kdcustomer)->get(),
          'vdata' => $data,
        ])->render(),
        'data' => $data,
      ]);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function sorepltbmultiprc(Request $request)
  {
    if ($request->Ajax()) {
      // $kode = $request->kode_barang; //$_GET['kode_multiprc'];
      // $row = DB::table('select tbmultiprc.kode,tbmultiprc.nama,tbmultiprc.kdsatuan,tbsatuan.nama as nmsatuan')->join('tbbarang', 'tbmultiprc.kdbarang', '=', 'tbbarang.kode')->where('tbmultiprc.kdbarang', $kode)->first();
      $row = Tbmultiprc::join('tbbarang', 'tbmultiprc.kdbarang', '=', 'tbbarang.kode')->where('tbmultiprc.kdcustomer', $request->kode_customer)->where('kdbarang', $request->kode_barang)->first();
      if (isset($row)) {
        $data = [
          'kdbarang' => $row['kdbarang'],
          'nmbarang' => $row['nmbarang'],
          'kdsatuan' => $row['kdsatuan'],
          'harga_jual' => $row['harga'],
        ];
      } else {
        $data = [
          'kdbarang' => '',
          'nmbarang' => '',
          'kdsatuan' => '',
          'harga_jual' => 0,
        ];
      }
      echo json_encode($data);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function soinputsod(Soh $soh, Request $request)
  {
    if ($request->Ajax()) {
      $id = $_GET['id'];
      $username = session('username');
      $soh = Soh::where('id', $id)->first();
      $noso = $soh->noso;
      $data = [
        'menu' => 'transaksi',
        'submenu' => 'so',
        'submenu1' => 'ref_umum',
        'title' => 'Detail Data Sales Order',
      ];
      // var_dump($data);

      // return response()->json([
      //     'data' => $data,
      // ]);
      return response()->json([
        'body' => view('so.modaldetail', [
          'userdtlmenu' => Userdtl::join('tbmodule', 'userdtl.cmodule', '=', 'tbmodule.cmodule')->where('userdtl.pakai', '1')->where('username', $username)->orderBy('userdtl.nurut')->get(),
          'userdtl' => Userdtl::where('cmodule', 'Sales Order')->where('username', $username)->first(),
          'saplikasi' => Saplikasi::where('aktif', 'Y')->first(),
          // 'tbsales' => Tbsales::get(),
          'so' => Soh::where('id', $id)->first(),
          'sod' => Sod::where('noso', $noso)->get(),
          // 'action' => route('so.update', $soh->id),
          'action' => 'sotambahdetail',
          'vdata' => $data,
        ])->render(),
        'data' => $data,
      ]);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function sodajax(Request $request) //: View
  {
    $noso = $request->noso;
    if ($request->ajax()) {
      // $data = Sod::where('noso', $noso); //->orderBy('kode', 'asc');
      $data = Sod::leftjoin('tbsatuan', 'tbsatuan.kode', '=', 'sod.kdsatuan')
        ->select('sod.*', 'tbsatuan.nama as nmsatuan')
        ->where('noso', $noso); //->orderBy('kode', 'asc');      
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

  public function sotambahdetail(Request $request, SodRequest $sodrequest, Sod $sod)
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
        // $recsod = Sod::where('noso', $request->nosod)->where('kdbarang', $request->kdbarang)->first();
        // if (isset($recsod->noso)) {
        //   $msg = [
        //     'sukses' => 'Data gagal di tambah',
        //   ];
        // } else {
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
          'user' => 'Tambah-' . $request->username . ', ' . date('d-m-Y h:i:s'),
        ]);
        $sod->save($validate);
        $soh = Soh::where('noso', $request->nosod)->first();
        $biaya_lain = $soh->biaya_lain;
        $materai = $soh->materai;
        $ppn = $soh->ppn;
        $subtotal = DB::table('sod')->where('noso', $request->nosod)->sum('subtotal');
        $total_sementara = $biaya_lain + $subtotal + $materai;
        $total = $total_sementara + ($total_sementara * ($ppn / 100));
        DB::table('soh')->where('noso', $request->nosod)->update([
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

  public function sototaldetail(Soh $soh, Request $request)
  {
    if ($request->Ajax()) {
      $noso = $request->noso;
      $soh = Soh::where('noso', $noso)->first();
      $data = [
        'menu' => 'transaksi',
        'submenu' => 'Sales Order',
        'submenu1' => 'ref_umum',
        'title' => 'Detail Data Sales Order',
      ];
      // var_dump($data);

      // return response()->json([
      //     'data' => $data,
      // ]);
      return response()->json([
        'body' => view('so.totaldetail', [
          'subtotalsod' => Sod::where('noso', $noso)->sum('subtotal'),
          'qtysod' => Sod::where('noso', $noso)->sum('qty'),
          'vdata' => $data,
        ])->render(),
        'data' => $data,
      ]);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function socetak(Request $request)
  {
    //Create History
    $rowsoh = soh::join('tbcustomer', 'soh.kdcustomer', '=', 'tbcustomer.kode')->where('soh.id', $request->id)->first();
    $noso = $rowsoh->noso;
    $rowsod = Soh::join('sod', 'sod.noso', '=', 'soh.noso')
      ->join('tbsatuan', 'tbsatuan.kode', '=', 'sod.kdsatuan')
      ->select('soh.*', 'sod.*', 'tbsatuan.nama as nmsatuan')
      ->where('soh.noso', $noso)->get();
    $data = [
      'soh' => $rowsoh,
      'sod' => $rowsod,
    ];
    // return view('so.cetak', $data);

    $rowd = Sod::where('noso', $noso)->get();
    $rowd = $rowd->count();

    if ($rowd > 10) {
      //create PDF
      $mpdf = new Mpdf([
        'format' => 'Letter',
        'margin_left' => 10,
        'margin_right' => 10,
        'margin_top' => 8,
        'margin_bottom' => 5,
        'margin_header' => 5,
        'margin_footer' => 5,
      ]);
    } else {
      //create PDF
      $mpdf = new Mpdf([
        'format' => [150, 210], //gagal jadi ke landscape
        // 'format' => 'Letter-P',
        'orientation' => 'L',
        'margin_left' => 10,
        'margin_right' => 10,
        'margin_top' => 8,
        'margin_bottom' => 5,
        'margin_header' => 5,
        'margin_footer' => 5,
      ]);
    }

    //Create History
    $soh = Soh::where('id', $request->id)->first();
    $tanggal = date('Y-m-d');
    $datetime = date('Y-m-d H:i:s');
    $dokumen = $soh->noso;
    $form = 'Sales Order';
    $status = 'Cetak';
    $catatan = isset($request->catatan) ? $request->catatan : '';
    $username = session('username');
    DB::table('hisuser')->insert(['tanggal' => $tanggal, 'dokumen' => $dokumen, 'form' => $form, 'status' => $status, 'user' => $username, 'catatan' => $catatan, 'datetime' => $datetime]);

    $header = trim($request->get('header', ''));
    $footer = trim($request->get('footer', ''));

    if (strlen($header)) {
      $mpdf->SetHTMLHeader($header);
    }

    if (strlen($footer)) {
      $mpdf->SetHTMLFooter($footer);
    }

    if ($request->get('show_toc')) {
      $mpdf->h2toc = array(
        'H1' => 0,
        'H2' => 1,
        'H3' => 2,
        'H4' => 3,
        'H5' => 4,
        'H6' => 5
      );
      $mpdf->TOCpagebreak();
    }

    //write content
    // $mpdf->WriteHTML($request->get('content'));
    $mpdf->WriteHTML(view('so.cetak', $data));
    $namafile = $noso . ' - ' . date('dmY H:i:s') . '.pdf';
    //return the PDF for download
    // return $mpdf->Output($request->get('name') . $namafile, Destination::DOWNLOAD);
    $mpdf->Output($namafile, 'I');
  }

  public function socetakpl(Request $request)
  {
    //Create History
    $rowsoh = soh::join('tbcustomer', 'soh.kdcustomer', '=', 'tbcustomer.kode')->where('soh.id', $request->id)->first();
    $noso = $rowsoh->noso;
    $rowsod = Soh::join('sod', 'sod.noso', '=', 'soh.noso')
      ->join('tbsatuan', 'tbsatuan.kode', '=', 'sod.kdsatuan')
      ->select('soh.*', 'sod.*', 'tbsatuan.nama as nmsatuan')
      ->where('soh.noso', $noso)->get();
    $data = [
      'soh' => $rowsoh,
      'sod' => $rowsod,
    ];
    // return view('so.cetak', $data);

    $rowd = Sod::where('noso', $noso)->get();
    $rowd = $rowd->count();

    if ($rowd > 10) {
      //create PDF
      $mpdf = new Mpdf([
        'format' => 'Letter',
        'margin_left' => 10,
        'margin_right' => 10,
        'margin_top' => 8,
        'margin_bottom' => 5,
        'margin_header' => 5,
        'margin_footer' => 5,
      ]);
    } else {
      //create PDF
      $mpdf = new Mpdf([
        // 'format' => [150, 210], //gagal jadi ke landscape
        'format' => [60, 60], //gagal jadi ke landscape
        // 'format' => 'Letter-P',
        'orientation' => 'P',
        'margin_left' => 4,
        'margin_right' => 4,
        'margin_top' => 2,
        'margin_bottom' => 2,
        'margin_header' => 2,
        'margin_footer' => 2,
      ]);
    }

    //Create History
    $soh = Soh::where('id', $request->id)->first();
    $tanggal = date('Y-m-d');
    $datetime = date('Y-m-d H:i:s');
    $dokumen = $soh->noso;
    $form = 'Sales Order';
    $status = 'Cetak';
    $catatan = isset($request->catatan) ? $request->catatan : '';
    $username = session('username');
    DB::table('hisuser')->insert(['tanggal' => $tanggal, 'dokumen' => $dokumen, 'form' => $form, 'status' => $status, 'user' => $username, 'catatan' => $catatan, 'datetime' => $datetime]);

    $header = trim($request->get('header', ''));
    $footer = trim($request->get('footer', ''));

    if (strlen($header)) {
      $mpdf->SetHTMLHeader($header);
    }

    if (strlen($footer)) {
      $mpdf->SetHTMLFooter($footer);
    }

    if ($request->get('show_toc')) {
      $mpdf->h2toc = array(
        'H1' => 0,
        'H2' => 1,
        'H3' => 2,
        'H4' => 3,
        'H5' => 4,
        'H6' => 5
      );
      $mpdf->TOCpagebreak();
    }

    //write content
    // $mpdf->WriteHTML($request->get('content'));
    $mpdf->WriteHTML(view('so.cetakpl', $data));
    $namafile = $noso . ' - ' . date('dmY H:i:s') . '.pdf';
    //return the PDF for download
    // return $mpdf->Output($request->get('name') . $namafile, Destination::DOWNLOAD);
    $mpdf->Output($namafile, 'I');
    exit;
  }
}
