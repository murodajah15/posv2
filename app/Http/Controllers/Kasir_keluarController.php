<?php

namespace App\Http\Controllers;

use Mpdf\Mpdf;
use Mpdf\Output\Destination;
use App\Http\Requests\Kasir_keluarRequest;
use Illuminate\Http\Request;
use Session;
// use Yajra\DataTables\Contracts\DataTables;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use App\Models\Kasir_keluar;
use App\Models\Kasir_keluard;
use App\Models\Mohklruangh;
use App\Models\Mohklruangd;
use App\Models\Belih;
use App\Models\Userdtl;
use App\Models\Saplikasi;
use Riskihajar\Terbilang\Facades\Terbilang;
use Illuminate\Support\Facades\Config;

Config::set('terbilang.locale', 'id');

// //return type View
// use Illuminate\View\View;

class Kasir_keluarController extends Controller
{
  public function index(Request $request) //: View
  {
    $username = session('username');
    $data = [
      'menu' => 'transaksi',
      'submenu' => 'kasir_keluar',
      'submenu1' => 'ref_umum',
      'title' => 'Kasir Pengeluaran Uang',
      // 'kasir_keluar' => Kasir_keluar::all(),
      'userdtlmenu' => Userdtl::join('tbmodule', 'userdtl.cmodule', '=', 'tbmodule.cmodule')->where('userdtl.pakai', '1')->where('username', $username)->orderBy('userdtl.nurut')->get(),
      'userdtl' => Userdtl::where('cmodule', 'Kasir Pengeluaran Uang')->where('username', $username)->first(),
    ];
    $userdtl = Userdtl::where('cmodule', 'Kasir Pengeluaran Uang')->where('username', $username)->first();
    if ($userdtl->pakai == '1') {
      return view('kasir_keluar.index')->with($data);
    } else {
      return redirect('home');
    }
  }
  public function kasir_keluarajax(Request $request) //: View
  {
    if ($request->ajax()) {
      $data = Kasir_keluar::select('*'); //->orderBy('kode', 'asc');
      return Datatables::of($data)
        ->addIndexColumn()
        ->addColumn('kode1', function ($row) {
          $id = $row['id'];
          $btn = $row['kode']; //'<a href="#"' . 'onclick=detail(' . $id . ')>' .  $row['kode'] . '</a>';
          return $btn;
        })
        ->rawColumns(['kode1'])
        ->make(true);
      return view('kasir_keluar');
    }
  }

  public function create(Request $request)
  {
    if ($request->Ajax()) {
      $username = session('username');
      $data = [
        'menu' => 'transaksi',
        'submenu' => 'kasir_keluar',
        'submenu1' => 'ref_umum',
        'title' => 'Tambah Data Kasir Pengeluaran Uang',
      ];
      return response()->json([
        'body' => view('kasir_keluar.modaltambahmaster', [
          'saplikasi' => Saplikasi::where('aktif', 'Y')->first(),
          'kasir_keluar' => new kasir_keluar(),
          'action' => route('kasir_keluar.store'),
          'vdata' => $data,
        ])->render(),
        'data' => $data,

      ]);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function store(kasir_keluarRequest $request, kasir_keluar $kasir_keluar)
  // public function store(Request $request, kasir_keluar $kasir_keluar)
  {
    if ($request->Ajax()) {
      $sort_num = 0;
      $new_code = $request->nokwitansi;
      $ketemu = 0;
      $record = 0;
      $rec = Kasir_keluar::where('nokwitansi', $new_code)->first();
      if ($rec == null) {
        $aplikasi = Saplikasi::where('aktif', 'Y')->first();
        $sort_num = $aplikasi->nokwitansi;
        $tahun = $aplikasi->tahun;
        $bulan = $aplikasi->bulan;
        DB::table('saplikasi')->where('aktif', 'Y')->update(['nokwkeluar' => $sort_num + 1]);
      } else {
        while ($ketemu == $record) { //0=0
          $aplikasi = Saplikasi::where('aktif', 'Y')->first();
          $sort_num = $aplikasi->nokwkeluar;
          $tahun = $aplikasi->tahun;
          $bulan = $aplikasi->bulan;
          DB::table('saplikasi')->where('aktif', 'Y')->update(['nokwkeluar' => $sort_num + 1]);
          $new_code = 'KK' . $tahun . sprintf('%02s', $bulan) . sprintf("%05s", $sort_num + 1);
          $rec = Kasir_keluar::where('nokwitansi', $new_code)->first();
          if ($rec == null) {
            $record = 0;
            DB::table('saplikasi')->where('aktif', 'Y')->update(['nokwkeluar' => $sort_num + 1]);
            break;
          } else {
            DB::table('saplikasi')->where('aktif', 'Y')->update(['nokwkeluar' => $sort_num + 1]);
          }
        }
      }
      $validated = $request->validated();
      if ($validated) {
        $tbjnkeluar = DB::table('tbjnkeluar')->where('kode', $request->kdjnkeluar)->first();
        $kasir_keluar->fill([
          'nokwitansi' => isset($request->nokwitansi) ? $new_code : '',
          'tglkwitansi' => isset($request->tglkwitansi) ? $request->tglkwitansi : '',
          'kdjnkeluar' => isset($request->kdjnkeluar) ? $request->kdjnkeluar : '',
          'nmjnkeluar' => isset($tbjnkeluar) ? $tbjnkeluar->nama : '',
          'carabayar' => isset($request->carabayar) ? $request->carabayar : '',
          'kdbank' => isset($request->kdbank) ? $request->kdbank : '',
          'nmbank' => isset($request->nmbank) ? $request->nmbank : '',
          'kdjnskartu' => isset($request->kdjnskartu) ? $request->kdjnskartu : '',
          'nmjnskartu' => isset($request->nmjnskartu) ? $request->nmjnskartu : '',
          'norek' => isset($request->norek) ? $request->norek : '',
          'nocekgiro' => isset($request->nocekgiro) ? $request->nocekgiro : '',
          'tglterimacekgiro' => isset($request->tglterimacekgiro) ? $request->tglterimacekgiro : '',
          'tgljtempocekgiro' => isset($request->tgljtempocekgiro) ? $request->tgljtempocekgiro : '',
          'keterangan' => isset($request->keterangan) ? $request->keterangan : '',
          'user' => 'Tambah-' . $request->username . ', ' . date('d-m-Y h:i:s'),
        ]);
        $kasir_keluar->save($validated);
        //Create History
        $tanggal = date('Y-m-d');
        $datetime = date('Y-m-d H:i:s');
        $dokumen = $new_code;
        $form = 'Kasir Pengeluaran Uang';
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
        'submenu' => 'kasir_keluar',
        'submenu1' => 'ref_umum',
        'title' => 'Detail Kasir keluar',
        // 'userdtl' => Userdtl::where('cmodule', 'Kasir Pengeluaran Uang')->where('username', $username)->first(),
      ];
      return response()->json([
        'body' => view('kasir_keluar.modaltambahmaster', [
          'saplikasi' => Saplikasi::where('aktif', 'Y')->first(),
          'kasir_keluar' => Kasir_keluar::where('id', $id)->first(),
          'action' => route('tbbarang.store'),
          'vdata' => $data,
        ])->render(),
        'data' => $data,
      ]);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function edit(Request $request)
  {
    if ($request->Ajax()) {
      $id = $request->id;
      $data = [
        'menu' => 'transaksi',
        'submenu' => 'kasir_keluar',
        'submenu1' => 'ref_umum',
        'title' => 'Edit Data Kasir Pengeluaran Uang',
      ];
      // var_dump($data);

      // return response()->json([
      //     'data' => $data,
      // ]);
      return response()->json([
        'body' => view('kasir_keluar.modaltambahmaster', [
          'saplikasi' => Saplikasi::where('aktif', 'Y')->first(),
          'kasir_keluar' => Kasir_keluar::where('id', $id)->first(),
          // 'action' => route('kasir_keluar.update', $kasir_keluar->id),
          'action' => 'kasir_keluarupdate',
          'vdata' => $data,
        ])->render(),
        'data' => $data,
      ]);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function update(Request $request, kasir_keluar $kasir_keluar)
  {
    if ($request->Ajax()) {
      $id = $request->id;
      if ($request->nokwitansi === $request->nokwitansilama) {
        $validate = $request->validate(
          [
            'nokwitansi' => 'required',
            'tglkwitansi' => 'required',
          ],
          [
            'nokwitansi.required' => 'No. harus di isi',
            'tglkwitansi.required' => 'Tanggal harus di isi',
          ],
        );
      } else {
        $validate = $request->validate(
          [
            'nokwitansi' => 'required|unique:kasir_keluar|max:255',
            'tglkwitansi' => 'required',
          ],
          [
            'nokwitansi.required' => 'No. harus di isi',
            'tglkwitansi.required' => 'Tanggal harus di isi',
          ],
        );
      }
      $kasir_keluar = Kasir_keluar::find($id);
      if ($validate) {
        $nokwitansi = $request->nokwitansi;
        $tbjnkeluar = DB::table('tbjnkeluar')->where('kode', $request->kdjnkeluar)->first();
        $kasir_keluar->fill([
          'nokwitansi' => isset($request->nokwitansi) ? $request->nokwitansi : '',
          'tglkwitansi' => isset($request->tglkwitansi) ? $request->tglkwitansi : '',
          'kdjnkeluar' => isset($request->kdjnkeluar) ? $request->kdjnkeluar : '',
          'nmjnkeluar' => isset($tbjnkeluar) ? $tbjnkeluar->nama : '',
          'carabayar' => isset($request->carabayar) ? $request->carabayar : '',
          'kdbank' => isset($request->kdbank) ? $request->kdbank : '',
          'nmbank' => isset($request->nmbank) ? $request->nmbank : '',
          'kdjnskartu' => isset($request->kdjnskartu) ? $request->kdjnskartu : '',
          'nmjnskartu' => isset($request->nmjnskartu) ? $request->nmjnskartu : '',
          'norek' => isset($request->norek) ? $request->norek : '',
          'nocekgiro' => isset($request->nocekgiro) ? $request->nocekgiro : '',
          'tglterimacekgiro' => isset($request->tglterimacekgiro) ? $request->tglterimacekgiro : '',
          'tgljttempocekgiro' => isset($request->tgljttempocekgiro) ? $request->tgljttempocekgiro : '',
          'keterangan' => isset($request->keterangan) ? $request->keterangan : '',
          'user' => 'Update-' . $request->username . ', ' . date('d-m-Y h:i:s'),
        ]);
        $kasir_keluar->save($validate);
        //Create History
        $tanggal = date('Y-m-d');
        $datetime = date('Y-m-d H:i:s');
        $dokumen = $nokwitansi;
        $form = 'Kasir Pengeluaran Uang';
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

  public function kasir_keluarproses(Request $request, kasir_keluar $kasir_keluarproses)
  {
    if ($request->Ajax()) {
      $id = $request->id;
      $kasir_keluar = Kasir_keluar::find($id);
      $kasir_keluar->fill([
        'proses' => 'Y',
        'user_proses' => 'Proses-' . session('username') . ', ' . date('d-m-Y h:i:s'),
      ]);
      $kasir_keluar->save();
      $kasir_keluard = Kasir_keluard::where('nokwitansi', $kasir_keluar->nokwitansi)->get();
      foreach ($kasir_keluard as $row) {
        if ($row->nomohon <> "") {
          //Update Mohklruangh
          $mohklruangd = Mohklruangd::where('nomohon', $row->nomohon)->where('nodokumen', $row->nodokumen)->first();
          $bayar = $mohklruangd->bayar + $row->uang;
          $kurang = $mohklruangd->kurang - $row->uang;
          DB::table('mohklruangd')->where('nomohon', $row->nomohon)->where('nodokumen', $row->nodokumen)->update(['bayar' => $bayar, 'kurang' => $kurang]);
          $mohklruangh = Mohklruangh::where('nomohon', $row->nomohon)->first();
          if (isset($mohklruangh)) {
            $bayar = $mohklruangh->bayar + $row->uang;
            $kurang = $mohklruangh->total - $bayar;
            // $kurang = $mohklruangh->kurang - $row->uang;
            DB::table('mohklruangh')->where('nomohon', $row->nomohon)->update(['bayar' => $bayar, 'kurang' => $kurang]);
          }
        } else {
          //Update Pembelian
          $belih = Belih::where('nobeli', $row->nodokumen)->first();
          if (isset($belih)) {
            $bayar = $belih->sudahbayar + $row->uang;
            $kurang = $belih->total - $bayar;
            // $kurang = $belih->kurangkurang - $row->uang;
            DB::table('belih')->where('nobeli', $row->nodokumen)->update(['sudahbayar' => $bayar, 'kurangbayar' => $kurang]);
          }
        }
      }
      //Create History
      $kasir_keluar = Kasir_keluar::where('id', $request->id)->first();
      $tanggal = date('Y-m-d');
      $datetime = date('Y-m-d H:i:s');
      $dokumen = $kasir_keluar->nokwitansi;
      $form = 'Kasir Pengeluaran Uang';
      $status = 'Proses';
      $catatan = isset($request->catatan) ? $request->catatan : '';
      $username = session('username');
      DB::table('hisuser')->insert(['tanggal' => $tanggal, 'dokumen' => $dokumen, 'form' => $form, 'status' => $status, 'user' => $username, 'catatan' => $catatan, 'datetime' => $datetime]);
      return response()->json([
        'sukses' => true, //view('tbbarang.tabel_barang')
      ]);
      // return redirect()->back()->with('message', 'Berhasil di update');
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function kasir_keluarbatalproses(kasir_keluar $kasir_keluar, Request $request)
  {
    if ($request->Ajax()) {
      $id = $_GET['id'];
      $data = [
        'menu' => 'transaksi',
        'submenu' => 'kasir_keluar',
        'submenu1' => 'ref_umum',
        'title' => 'Batal Proses Kasir keluar',
      ];
      // var_dump($data);

      // return response()->json([
      //     'data' => $data,
      // ]);
      return response()->json([
        'body' => view('kasir_keluar.modalbatalproses', [
          'kasir_keluar' => Kasir_keluar::where('id', $id)->first(),
          // 'action' => route('so.update', $soh->id),
          'action' => 'kasir_keluarbatalprosesok',
          'vdata' => $data,
        ])->render(),
        'data' => $data,
      ]);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function kasir_keluarbatalprosesok(Request $request, kasir_keluar $kasir_keluar)
  {
    if ($request->Ajax()) {
      $id = $request->id;
      $kasir_keluar = Kasir_keluar::find($id);
      $user_proses = 'Proses-' . session('username') . ', ' . date('d-m-Y h:i:s');
      DB::table('kasir_keluarh')->where('id', $id)->update(['proses' => 'N', 'user_proses' => $user_proses]);
      $kasir_keluard = Kasir_keluard::where('nokwitansi', $kasir_keluar->nokwitansi)->get();
      foreach ($kasir_keluard as $row) {
        if ($row->nomohon <> "") {
          //Update Mohklruangh
          $mohklruangd = Mohklruangd::where('nomohon', $row->nomohon)->where('nodokumen', $row->nodokumen)->first();
          $bayar = $mohklruangd->bayar - $row->uang;
          $kurang = $mohklruangd->kurang + $row->uang;
          DB::table('mohklruangd')->where('nomohon', $row->nomohon)->where('nodokumen', $row->nodokumen)->update(['bayar' => $bayar, 'kurang' => $kurang]);
          $mohklruangh = Mohklruangh::where('nomohon', $row->nomohon)->first();
          if (isset($mohklruangh)) {
            $bayar = $mohklruangh->bayar - $row->uang;
            $kurang = $mohklruangh->total + $bayar;
            // $kurang = $mohklruangh->kurang + $row->uang;

            DB::table('mohklruangh')->where('nomohon', $row->nomohon)->update(['bayar' => $bayar, 'kurang' => $kurang]);
          }
        } else {
          //Update Pembelian
          $belih = Belih::where('nobeli', $row->nodokumen)->first();
          if (isset($belih)) {
            $bayar = $belih->sudahbayar - $row->uang;
            $kurang = $belih->total + $bayar;
            // $kurang = $belih->kurangkurang + $row->uang;
            DB::table('belih')->where('nobeli', $row->nodokumen)->update(['sudahbayar' => $bayar, 'kurangbayar' => $kurang]);
          }
        }
      }
      //Create History
      $kasir_keluar = Kasir_keluar::where('id', $id)->first();
      $nokwitansi = $kasir_keluar->nokwitansi;
      $tanggal = date('Y-m-d');
      $datetime = date('Y-m-d H:i:s');
      $dokumen = $nokwitansi;
      $form = 'Kasir Pengeluaran Uang';
      $status = 'Batal Proses';
      $catatan = isset($request->catatan) ? $request->catatan : '';
      $username = session('username');
      DB::table('hisuser')->insert(['tanggal' => $tanggal, 'dokumen' => $dokumen, 'form' => $form, 'status' => $status, 'user' => $username, 'catatan' => $catatan, 'datetime' => $datetime]);
      $msg = [
        'sukses' => 'Data berhasil di Batal Proses', //view('tbbarang.tabel_barang')
      ];
      echo json_encode($msg);
      // return redirect()->back()->with('message', 'Berhasil di update');
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function kasir_keluarcancel(Request $request, kasir_keluar $kasir_keluar)
  {
    if ($request->Ajax()) {
      $id = $request->id;
      $user_proses = 'Cancel-' . session('username') . ', ' . date('d-m-Y h:i:s');
      DB::table('kasir_keluarh')->where('id', $id)->update(['batal' => 'Y', 'user_proses' => $user_proses]);
      //Create History
      $kasir_keluar = Kasir_keluar::where('id', $request->id)->first();
      $tanggal = date('Y-m-d');
      $datetime = date('Y-m-d H:i:s');
      $dokumen = $kasir_keluar->nokwitansi;
      $form = 'Kasir Pengeluaran Uang';
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

  public function kasir_keluarambil(Request $request, kasir_keluar $kasir_keluar)
  {
    if ($request->Ajax()) {
      $id = $request->id;
      $user_proses = 'Ambil-' . session('username') . ', ' . date('d-m-Y h:i:s');
      DB::table('kasir_keluarh')->where('id', $id)->update(['batal' => 'N', 'user_proses' => $user_proses]);
      //Create History
      $kasir_keluar = Kasir_keluar::where('id', $request->id)->first();
      $tanggal = date('Y-m-d');
      $datetime = date('Y-m-d H:i:s');
      $dokumen = $kasir_keluar->nokwitansi;
      $form = 'Kasir Pengeluaran Uang';
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

  public function destroy(kasir_keluar $kasir_keluar, Request $request)
  {
    if ($request->Ajax()) {
      $id = $request->id;
      $kasir_keluar = Kasir_keluar::where('id', $id)->first();
      $deleted = DB::table('kasir_keluarh')->where('id', $id)->delete();
      if ($deleted) {
        DB::table('kasir_keluard')->where('nokwitansi', $kasir_keluar->nokwitansi)->delete();
        //Create History
        $tanggal = date('Y-m-d');
        $datetime = date('Y-m-d H:i:s');
        $dokumen = $kasir_keluar->nokwitansi;
        $form = 'Kasir Pengeluaran Uang';
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


  public function kasir_keluarcetak(Request $request)
  {
    $row = Kasir_keluar::where('id', $request->id)->first();
    $nokwitansi = $row->nokwitansi;
    $rowd = Kasir_keluard::where('nokwitansi', $nokwitansi)->get();
    // dd($rowd);
    $data = [
      'kasir_keluar' => $row,
      'kasir_keluard' => $rowd,
    ];
    // return view('kasir_keluar.cetak', $data);

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
    //Create History
    $kasir_keluar = Kasir_keluar::where('id', $request->id)->first();
    $tanggal = date('Y-m-d');
    $datetime = date('Y-m-d H:i:s');
    $dokumen = $kasir_keluar->nokwitansi;
    $form = 'Kasir Pengeluaran Uang';
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
    $mpdf->WriteHTML(view('kasir_keluar.cetak', $data));
    $namafile = $nokwitansi . ' - ' . date('dmY H:i:s') . '.pdf';
    //return the PDF for download
    // return $mpdf->Output($request->get('name') . $namafile, Destination::DOWNLOAD);
    $mpdf->Output($namafile, 'I');
    exit;
  }
}
