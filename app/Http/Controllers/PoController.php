<?php

namespace App\Http\Controllers;

use Mpdf\Mpdf;
use Mpdf\Output\Destination;
use App\Http\Requests\PohRequest;
use Illuminate\Http\Request;
use Session;
// use Yajra\DataTables\Contracts\DataTables;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use App\Models\Poh;
use App\Models\Pod;
use App\Models\Tbsupplier;
// use App\Models\Tbsales;
use App\Models\Tbbarang;
use App\Models\Tbmultiprc;
use App\Models\Userdtl;
use App\Models\Saplikasi;

// //return type View
// use Illuminate\View\View;

class PoController extends Controller
{
  public function index(Request $request) //: View
  {
    $username = session('username');
    $data = [
      'menu' => 'transaksi',
      'submenu' => 'po',
      'submenu1' => 'ref_umum',
      'title' => 'Purchase Order',
      // 'tbbarang' => Tbbarang::all(),
      'userdtlmenu' => Userdtl::join('tbmodule', 'userdtl.cmodule', '=', 'tbmodule.cmodule')->where('userdtl.pakai', '1')->where('username', $username)->orderBy('userdtl.nurut')->get(),
      'userdtl' => Userdtl::where('cmodule', 'Purchase Order')->where('username', $username)->first(),
    ];
    $userdtl = Userdtl::where('cmodule', 'Purchase Order')->where('username', $username)->first();
    if ($userdtl->pakai == '1') {
      return view('po.index')->with($data);
    } else {
      return redirect('home');
    }
  }
  public function poajax(Request $request) //: View
  {
    if ($request->ajax()) {
      // $data = Poh::select('*'); //->orderBy('kode', 'asc');
      $data = Poh::select('id', DB::raw("DATE_FORMAT(poh.tglpo, '%Y-%m-%d') as tglpo"), 'nopo', 'nmsupplier', 'total', 'proses', 'batal'); //->orderBy('kode', 'asc');
      return Datatables::of($data)
        ->addIndexColumn()
        ->addColumn('kode1', function ($row) {
          $id = $row['id'];
          $btn = $row['kode']; //'<a href="#"' . 'onclick=detail(' . $id . ')>' .  $row['kode'] . '</a>';
          return $btn;
        })
        ->rawColumns(['kode1'])
        ->make(true);
      return view('po');
    }
  }

  public function create(Request $request)
  {
    if ($request->Ajax()) {
      $username = session('username');
      $data = [
        'menu' => 'transaksi',
        'submenu' => 'po',
        'submenu1' => 'ref_umum',
        'title' => 'Tambah Data Purchase Order',
      ];
      return response()->json([
        'body' => view('po.modaltambahmaster', [
          'saplikasi' => Saplikasi::where('aktif', 'Y')->first(),
          // 'tbsales' => Tbsales::orderBy('nama')->get(),
          'po' => new Poh(),
          'action' => route('po.store'),
          'vdata' => $data,
        ])->render(),
        'data' => $data,

      ]);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function store(PohRequest $request, Poh $poh)
  // public function store(Request $request, Poh $poh)
  {
    if ($request->Ajax()) {
      $sort_num = 0;
      $new_code = $request->nopo;
      $ketemu = 0;
      $record = 0;
      $rec = Poh::where('nopo', $new_code)->first();
      if ($rec == null) {
        $aplikasi = Saplikasi::where('aktif', 'Y')->first();
        $sort_num = $aplikasi->nopo;
        $tahun = $aplikasi->tahun;
        $bulan = $aplikasi->bulan;
        Saplikasi::where('aktif', 'Y')->update(['nopo' => $sort_num + 1]);
      } else {
        while ($ketemu == $record) { //0=0
          $aplikasi = Saplikasi::where('aktif', 'Y')->first();
          $sort_num = $aplikasi->nopo;
          $tahun = $aplikasi->tahun;
          $bulan = $aplikasi->bulan;
          Saplikasi::where('aktif', 'Y')->update(['nopo' => $sort_num + 1]);
          $new_code = 'po' . $tahun . sprintf('%02s', $bulan) . sprintf("%05s", $sort_num + 1);
          $rec = Poh::where('nopo', $new_code)->first();
          if ($rec == null) {
            $record = 0;
            Saplikasi::where('aktif', 'Y')->update(['nopo' => $sort_num + 1]);
            break;
          } else {
            Saplikasi::where('aktif', 'Y')->update(['nopo' => $sort_num + 1]);
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
        $poh->fill([
          'nopo' => isset($request->nopo) ? $new_code : '',
          'tglpo' => isset($request->tglpo) ? $request->tglpo : '',
          'noreferensi' => isset($request->noreferensi) ? $request->noreferensi : '',
          'kdsupplier' => isset($request->kdsupplier) ? $request->kdsupplier : '',
          'nmsupplier' => isset($request->nmsupplier) ? $request->nmsupplier : '',
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
        $poh->save($validated);
        //Create History
        $tanggal = date('Y-m-d');
        $datetime = date('Y-m-d H:i:s');
        $dokumen = $new_code;
        $form = 'Purchase Order';
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
        'submenu' => 'po',
        'submenu1' => 'ref_umum',
        'title' => 'Detail Purchase Order',
        // 'userdtl' => Userdtl::where('cmodule', 'Purchase Order')->where('username', $username)->first(),
      ];
      return response()->json([
        'body' => view('po.modaltambahmaster', [
          'saplikasi' => Saplikasi::where('aktif', 'Y')->first(),
          // 'tbsales' => Tbsales::get(),
          'po' => Poh::where('id', $id)->first(),
          'action' => route('tbbarang.store'),
          'vdata' => $data,
        ])->render(),
        'data' => $data,
      ]);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function edit(Poh $poh, Request $request)
  {
    if ($request->Ajax()) {
      $id = $_GET['id'];
      $username = session('username');
      $data = [
        'menu' => 'transaksi',
        'submenu' => 'po',
        'submenu1' => 'ref_umum',
        'title' => 'Edit Data Purchase Order',
      ];
      // var_dump($data);

      // return response()->json([
      //     'data' => $data,
      // ]);
      return response()->json([
        'body' => view('po.modaltambahmaster', [
          'saplikasi' => Saplikasi::where('aktif', 'Y')->first(),
          // 'tbsales' => Tbsales::get(),
          'po' => Poh::where('id', $id)->first(),
          // 'action' => route('po.update', $poh->id),
          'action' => 'poupdate',
          'vdata' => $data,
        ])->render(),
        'data' => $data,
      ]);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function update(Request $request, Poh $poh)
  {
    if ($request->Ajax()) {
      $id = $request->id;
      if ($request->nopo === $request->nopolama) {
        $validate = $request->validate(
          [
            'nopo' => 'required',
            'tglpo' => 'required',
            'kdsupplier' => 'required',
          ],
          [
            'nopo.required' => 'No. SO harus di isi',
            'tglpo.required' => 'Tanggal SO harus di isi',
            'kdsupplier.required' => 'Supplier harus di isi',
          ],
        );
      } else {
        $validate = $request->validate(
          [
            'nopo' => 'required|unique:Poh|max:255',
            'tglpo' => 'required',
            'kdsupplier' => 'required',
          ],
          [
            'nopo.required' => 'No. SO harus di isi',
            'tglpo.required' => 'Tanggal SO harus di isi',
            'kdsupplier.required' => 'Supplier harus di isi',
          ],
        );
      }
      $poh = Poh::find($id);
      if ($validate) {
        $nopo = $request->nopo;
        $subtotal = DB::table('pod')->where('nopo', $nopo)->sum('subtotal');
        $biaya_lain = isset($request->biaya_lain) ? $request->biaya_lain : '0';
        $materai = isset($request->materai) ? $request->materai : '0';
        $ppn = isset($request->ppn) ? $request->ppn : '0';
        $total_sementara = $biaya_lain + $subtotal + $materai;
        $total = $total_sementara + ($total_sementara * ($ppn / 100));
        $poh->fill([
          'nopo' => isset($request->nopo) ? $request->nopo : '',
          'tglpo' => isset($request->tglpo) ? $request->tglpo : '',
          'noreferensi' => isset($request->noreferensi) ? $request->noreferensi : '',
          'kdsupplier' => isset($request->kdsupplier) ? $request->kdsupplier : '',
          'nmsupplier' => isset($request->nmsupplier) ? $request->nmsupplier : '',
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
        $poh->save($validate);
        //Create History
        $tanggal = date('Y-m-d');
        $datetime = date('Y-m-d H:i:s');
        $dokumen = $nopo;
        $form = 'Purchase Order';
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

  public function poproses(Request $request, Poh $poproses)
  {
    if ($request->Ajax()) {
      $id = $request->id;
      $poh = Poh::where('id', $id)->first();
      $nopo = $poh->nopo;
      // $poproses->load('poDetail');
      // $subtotal = $poproses->soDetail->sum('subtotal');
      $subtotal = Pod::where('nopo', $nopo)->sum('subtotal');
      $total_sementara = $poproses->biaya_lain + $subtotal;
      // $poproses->proses = 'Y';
      // $poproses->subtotal = $subtotal;
      // $poproses->total_sementara = $total_sementara;
      // $poproses->total = $total_sementara + ($total_sementara * ($poproses->ppn / 100));
      // $poproses->user = 'Proses-' . session('username') . ', ' . date('d-m-Y h:i:s');
      // $poproses->save();
      $poh = Poh::find($id);
      $poh->fill([
        'proses' => 'Y',
        'subtotal' => $subtotal,
        'total_sementara' => $total_sementara,
        'total' => $total_sementara + ($total_sementara * ($poh->ppn / 100)),
        'user' => 'Proses-' . session('username') . ', ' . date('d-m-Y h:i:s'),
      ]);
      $poh->save();
      $pod = Pod::where('nopo', $nopo)->get();
      foreach ($pod as $row) {
        $idd = $row->id;
        $qty = $row->qty;
        DB::table('pod')->where('id', $idd)->update(['proses' => 'Y', 'kurang' => $qty]);
      }
      //Create History
      $tanggal = date('Y-m-d');
      $datetime = date('Y-m-d H:i:s');
      $dokumen = $nopo;
      $form = 'Purchase Order';
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

  public function pobatalproses(Poh $poh, Request $request)
  {
    if ($request->Ajax()) {
      $id = $_GET['id'];
      $poh = Poh::where('id', $id)->first();
      $pod = Pod::where('nopo', $poh->nopo)->where('terima', '>', '0')->first();
      if (isset($pod->nopo)) {
        $msg = [
          'sukses' => 'Data gagal di cancel', //view('tbbarang.tabel_barang')
        ];
        echo json_encode($msg);
      } else {
        $data = [
          'menu' => 'transaksi',
          'submenu' => 'po',
          'submenu1' => 'ref_umum',
          'title' => 'Batal Proses Purchase Order',
        ];
        // var_dump($data);

        // return response()->json([
        //     'data' => $data,
        // ]);
        return response()->json([
          'body' => view('po.modalbatalproses', [
            'po' => Poh::where('id', $id)->first(),
            // 'action' => route('po.update', $poh->id),
            'action' => 'pobatalprosesok',
            'vdata' => $data,
          ])->render(),
          'data' => $data,
        ]);
      }
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function pobatalprosesok(Request $request, Poh $poh)
  {
    if ($request->Ajax()) {
      $id = $request->id;
      $user = 'Proses-' . session('username') . ', ' . date('d-m-Y h:i:s');
      DB::table('poh')->where('id', $id)->update(['proses' => 'N', 'user' => $user]);
      //Create History
      $poh = Poh::where('id', $id)->first();
      $nopo = $poh->nopo;
      $tanggal = date('Y-m-d');
      $datetime = date('Y-m-d H:i:s');
      $dokumen = $nopo;
      $form = 'Purchase Order';
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

  public function pocancel(Request $request, Poh $poh)
  {
    if ($request->Ajax()) {
      $id = $_POST['id'];
      $user = 'Cancel-' . session('username') . ', ' . date('d-m-Y h:i:s');
      DB::table('poh')->where('id', $id)->update(['batal' => 'Y', 'user' => $user]);
      //Create History
      $poh = Poh::where('id', $id)->first();
      $nopo = $poh->nopo;
      $tanggal = date('Y-m-d');
      $datetime = date('Y-m-d H:i:s');
      $dokumen = $nopo;
      $form = 'Purchase Order';
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

  public function poambil(Request $request, Poh $poh)
  {
    if ($request->Ajax()) {
      $id = $_POST['id'];
      $user = 'Ambil-' . session('username') . ', ' . date('d-m-Y h:i:s');
      DB::table('poh')->where('id', $id)->update(['batal' => 'N', 'user' => $user]);
      //Create History
      $row = Poh::where('id', $request->id)->first();
      $nopo = $row->nopo;
      $tanggal = date('Y-m-d');
      $datetime = date('Y-m-d H:i:s');
      $dokumen = $nopo;
      $form = 'Purchase Order';
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

  public function destroy(Poh $poh, Request $request)
  {
    if ($request->Ajax()) {
      $id = $request->id;
      $row = Poh::where('id', $request->id)->first();
      $deleted = DB::table('poh')->where('id', $id)->delete();
      if ($deleted) {
        DB::table('pod')->where('nopo', $row->nopo)->delete();
        //Create History
        $nopo = $row->nopo;
        $tanggal = date('Y-m-d');
        $datetime = date('Y-m-d H:i:s');
        $dokumen = $nopo;
        $form = 'Purchase Order';
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

  public function pocaritbbarang(Request $request)
  {
    if ($request->Ajax()) {
      $data = [
        // 'menu' => 'transaksi',
        // 'submenu' => 'tbsupplier',
        // 'submenu1' => 'ref_umum',
        'title' => 'Cari tabel barang',
      ];
      // var_dump($data);
      return response()->json([
        'body' => view('so.modalcaritbbarang', [
          'tbbarang' => Tbbarang::all(),
          'vdata' => $data,
        ])->render(),
        'data' => $data,
      ]);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function porepltbbarang(Request $request)
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

  public function pocaritbmultiprc(Request $request)
  {
    if ($request->Ajax()) {
      $kdsupplier = $request->kode_supplier;
      // $kdsupplier = $_GET['kode_supplier'];
      // dd($kdsupplier);
      $data = [
        // 'menu' => 'transaksi',
        // 'submenu' => 'tbsupplier',
        // 'submenu1' => 'ref_umum',
        'title' => 'Cari Tabel Multi Price',
      ];
      // var_dump($data);
      return response()->json([
        'body' => view('so.modalcaritbmultiprc', [
          // 'tbmultiprc' => Tbmultiprc::all(),
          'tbmultiprc' => Tbmultiprc::join('tbbarang', 'tbmultiprc.kdbarang', '=', 'tbbarang.kode')->where('tbmultiprc.kdsupplier', $kdsupplier)->get(),
          'vdata' => $data,
        ])->render(),
        'data' => $data,
      ]);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function porepltbmultiprc(Request $request)
  {
    if ($request->Ajax()) {
      // $kode = $request->kode_barang; //$_GET['kode_multiprc'];
      // $row = DB::table('select tbmultiprc.kode,tbmultiprc.nama,tbmultiprc.kdsatuan,tbsatuan.nama as nmsatuan')->join('tbbarang', 'tbmultiprc.kdbarang', '=', 'tbbarang.kode')->where('tbmultiprc.kdbarang', $kode)->first();
      $row = Tbmultiprc::join('tbbarang', 'tbmultiprc.kdbarang', '=', 'tbbarang.kode')->where('tbmultiprc.kdsupplier', $request->kode_supplier)->where('kdbarang', $request->kode_barang)->first();
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

  public function poinputpod(Poh $poh, Request $request)
  {
    if ($request->Ajax()) {
      $id = $_GET['id'];
      $username = session('username');
      $poh = Poh::where('id', $id)->first();
      $nopo = $poh->nopo;
      $data = [
        'menu' => 'transaksi',
        'submenu' => 'po',
        'submenu1' => 'ref_umum',
        'title' => 'Detail Data Purchase Order',
      ];
      // var_dump($data);

      // return response()->json([
      //     'data' => $data,
      // ]);
      return response()->json([
        'body' => view('po.modaldetail', [
          'userdtlmenu' => Userdtl::join('tbmodule', 'userdtl.cmodule', '=', 'tbmodule.cmodule')->where('userdtl.pakai', '1')->where('username', $username)->orderBy('userdtl.nurut')->get(),
          'userdtl' => Userdtl::where('cmodule', 'Purchase Order')->where('username', $username)->first(),
          'saplikasi' => Saplikasi::where('aktif', 'Y')->first(),
          // 'tbsales' => Tbsales::get(),
          'po' => Poh::where('id', $id)->first(),
          'pod' => Pod::where('nopo', $nopo)->get(),
          // 'action' => route('po.update', $poh->id),
          'action' => 'potambahdetail',
          'vdata' => $data,
        ])->render(),
        'data' => $data,
      ]);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function podajax(Request $request) //: View
  {
    $nopo = $request->nopo;
    if ($request->ajax()) {
      // $data = Pod::where('nopo', $nopo); //->orderBy('kode', 'asc');
      $data = Pod::leftjoin('tbsatuan', 'tbsatuan.kode', '=', 'pod.kdsatuan')
        ->select('pod.*', 'tbsatuan.nama as nmsatuan')
        ->where('nopo', $nopo); //->orderBy('kode', 'asc');
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

  public function potambahdetail(Request $request, Pod $pod)
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
        // $recsod = Pod::where('nopo', $request->nopod)->where('kdbarang', $request->kdbarang)->first();
        // if (isset($recsod->nopo)) {
        //   $msg = [
        //     'sukses' => 'Data gagal di tambah',
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
        $subtotal = DB::table('pod')->where('nopo', $request->nopod)->sum('subtotal');
        $total_sementara = $biaya_lain + $subtotal + $materai;
        $total = $total_sementara + ($total_sementara * ($ppn / 100));
        DB::table('poh')->where('nopo', $request->nopod)->update([
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

  public function pototaldetail(Poh $poh, Request $request)
  {
    if ($request->Ajax()) {
      $nopo = $request->nopo;
      $poh = Poh::where('nopo', $nopo)->first();
      $data = [
        'menu' => 'transaksi',
        'submenu' => 'Purchase Order',
        'submenu1' => 'ref_umum',
        'title' => 'Detail Data Purchase Order',
      ];
      // var_dump($data);

      // return response()->json([
      //     'data' => $data,
      // ]);
      return response()->json([
        'body' => view('po.totaldetail', [
          'subtotalpod' => Pod::where('nopo', $nopo)->sum('subtotal'),
          'qtypod' => Pod::where('nopo', $nopo)->sum('qty'),
          'vdata' => $data,
        ])->render(),
        'data' => $data,
      ]);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function pocetak(Request $request)
  {
    //Create History
    $rowpoh = Poh::join('tbsupplier', 'poh.kdsupplier', '=', 'tbsupplier.kode')->where('poh.id', $request->id)->first();
    $nopo = $rowpoh->nopo;
    $rowpod = Poh::join('pod', 'pod.nopo', '=', 'poh.nopo')->where('poh.nopo', $nopo)->get();
    $data = [
      'poh' => $rowpoh,
      'pod' => $rowpod,
    ];
    // return view('po.cetak', $data);

    $rowd = Pod::where('nopo', $nopo)->get();
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
      //harus pilih custom kertas 1/2 kwarto supaya tidak landscape
      // $mpdf = new Mpdf([
      //   'format' => [150, 210], //gagal jadi ke landscape
      //   // 'format' => 'Letter-P',
      //   'orientation' => 'L',
      //   'margin_left' => 10,
      //   'margin_right' => 10,
      //   'margin_top' => 8,
      //   'margin_bottom' => 5,
      //   'margin_header' => 5,
      //   'margin_footer' => 5,
      // ]);
      //dirubah ke A4 permintaan Feby tgl 09-01-2024
      $mpdf = new Mpdf([
        'format' => 'A4',
        'margin_left' => 10,
        'margin_right' => 10,
        'margin_top' => 8,
        'margin_bottom' => 5,
        'margin_header' => 5,
        'margin_footer' => 5,
      ]);
    }

    //Create History
    $poh = Poh::where('id', $request->id)->first();
    $tanggal = date('Y-m-d');
    $datetime = date('Y-m-d H:i:s');
    $dokumen = $poh->nopo;
    $form = 'Purchase Order';
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
    $mpdf->WriteHTML(view('po.cetak', $data));
    $namafile = $nopo . ' - ' . date('dmY H:i:s') . '.pdf';
    //return the PDF for download
    // return $mpdf->Output($request->get('name') . $namafile, Destination::DOWNLOAD);
    $mpdf->Output($namafile, 'I');
    exit;
  }
}
