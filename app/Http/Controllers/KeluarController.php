<?php

namespace App\Http\Controllers;

use Mpdf\Mpdf;
use Mpdf\Output\Destination;
use App\Http\Requests\KeluarhRequest;
use App\Http\Requests\KeluardRequest;
use Illuminate\Http\Request;
use Session;
// use Yajra\DataTables\Contracts\DataTables;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use App\Models\Keluarh;
use App\Models\Keluard;
use App\Models\Tbbarang;
use App\Models\Tbjntrans;
use App\Models\Userdtl;
use App\Models\Saplikasi;

// //return type View
// use Illuminate\View\View;

class KeluarController extends Controller
{
  public function index(Request $request) //: View
  {
    $username = session('username');
    $data = [
      'menu' => 'transaksi',
      'submenu' => 'keluar',
      'submenu1' => 'ref_umum',
      'title' => 'Pengeluaran Barang',
      // 'keluarh' => Keluarh::all(),
      'userdtlmenu' => Userdtl::join('tbmodule', 'userdtl.cmodule', '=', 'tbmodule.cmodule')->where('userdtl.pakai', '1')->where('username', $username)->orderBy('userdtl.nurut')->get(),
      'userdtl' => Userdtl::where('cmodule', 'Pengeluaran Barang')->where('username', $username)->first(),
    ];
    $userdtl = Userdtl::where('cmodule', 'Pengeluaran Barang')->where('username', $username)->first();
    if ($userdtl->pakai == '1') {
      return view('keluar.index')->with($data);
    } else {
      return redirect('home');
    }
  }
  public function keluarajax(Request $request) //: View
  {
    if ($request->ajax()) {
      $data = Keluarh::select('*'); //->orderBy('kode', 'asc');
      return Datatables::of($data)
        ->addIndexColumn()
        ->addColumn('kode1', function ($row) {
          $id = $row['id'];
          $btn = $row['kode']; //'<a href="#"' . 'onclick=detail(' . $id . ')>' .  $row['kode'] . '</a>';
          return $btn;
        })
        ->rawColumns(['kode1'])
        ->make(true);
      return view('keluar');
    }
  }

  public function create(Request $request)
  {
    if ($request->Ajax()) {
      $username = session('username');
      $data = [
        'menu' => 'transaksi',
        'submenu' => 'keluar',
        'submenu1' => 'ref_umum',
        'title' => 'Tambah Data Pengeluaran',
      ];
      return response()->json([
        'body' => view('keluar.modaltambahmaster', [
          'saplikasi' => Saplikasi::where('aktif', 'Y')->first(),
          'keluar' => new keluarh(),
          'action' => route('keluar.store'),
          'vdata' => $data,
        ])->render(),
        'data' => $data,

      ]);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function store(KeluarhRequest $request, keluarh $keluarh)
  // public function store(Request $request, keluarh $keluarh)
  {
    if ($request->Ajax()) {
      $sort_num = 0;
      $new_code = $request->nokeluar;
      $ketemu = 0;
      $record = 0;
      $rec = Keluarh::where('nokeluar', $new_code)->first();
      if ($rec == null) {
        $aplikasi = Saplikasi::where('aktif', 'Y')->first();
        $sort_num = $aplikasi->nokeluar;
        $tahun = $aplikasi->tahun;
        $bulan = $aplikasi->bulan;
        Saplikasi::where('aktif', 'Y')->update(['nokeluar' => $sort_num + 1]);
      } else {
        while ($ketemu == $record) { //0=0
          $aplikasi = Saplikasi::where('aktif', 'Y')->first();
          $sort_num = $aplikasi->nokeluar;
          $tahun = $aplikasi->tahun;
          $bulan = $aplikasi->bulan;
          Saplikasi::where('aktif', 'Y')->update(['nokeluar' => $sort_num + 1]);
          $new_code = 'PB' . $tahun . sprintf('%02s', $bulan) . sprintf("%05s", $sort_num + 1);
          $rec = Keluarh::where('nokeluar', $new_code)->first();
          if ($rec == null) {
            $record = 0;
            Saplikasi::where('aktif', 'Y')->update(['nokeluar' => $sort_num + 1]);
            break;
          } else {
            Saplikasi::where('aktif', 'Y')->update(['nokeluar' => $sort_num + 1]);
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
        $keluarh->fill([
          'nokeluar' => isset($request->nokeluar) ? $new_code : '',
          'tglkeluar' => isset($request->tglkeluar) ? $request->tglkeluar : '',
          'nofaktur' => isset($request->nofaktur) ? $request->nofaktur : '',
          'noinvoice' => isset($request->noinvoice) ? $request->noinvoice : '',
          'noreferensi' => isset($request->noreferensi) ? $request->noreferensi : '',
          'tgl_invoice' => isset($request->tgl_invoice) ? $request->tgl_invoice : '',
          'penerima' => isset($request->penerima) ? $request->penerima : '',
          'kdjntrans' => isset($request->kdjntrans) ? $request->kdjntrans : '',
          'kdgudang' => isset($request->kdgudang) ? $request->kdgudang : '',
          'jenis_order' => isset($request->jenis_order) ? $request->jenis_order : '',
          'ket_biaya_lain' => isset($request->ket_biaya_lain) ? $request->ket_biaya_lain : '',
          'biaya_lain' => $biaya_lain,
          'subtotal' => $subtotal,
          'total_sementara' => $total_sementara,
          'lppn' => isset($request->lppn) ? 'Y' : 'N',
          'ppn' => $ppn,
          'materai' => $materai,
          'total' => $total,
          'keterangan' => isset($request->keterangan) ? $request->keterangan : '',
          'user' => 'Tambah-' . $request->username . ', ' . date('d-m-Y h:i:s'),
        ]);
        $keluarh->save($validated);
        //Create History
        $tanggal = date('Y-m-d');
        $datetime = date('Y-m-d H:i:s');
        $dokumen = $new_code;
        $form = 'Pengeluaran Barang';
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
        'submenu' => 'keluar',
        'submenu1' => 'ref_umum',
        'title' => 'Detail Pengeluaran',
        // 'userdtl' => Userdtl::where('cmodule', 'Pengeluaran Barang')->where('username', $username)->first(),
      ];
      return response()->json([
        'body' => view('keluar.modaltambahmaster', [
          'saplikasi' => Saplikasi::where('aktif', 'Y')->first(),
          'keluar' => Keluarh::where('id', $id)->first(),
          'action' => route('tbbarang.store'),
          'vdata' => $data,
        ])->render(),
        'data' => $data,
      ]);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function edit(keluarh $keluarh, Request $request)
  {
    if ($request->Ajax()) {
      $id = $_GET['id'];
      $username = session('username');
      $data = [
        'menu' => 'transaksi',
        'submenu' => 'keluar',
        'submenu1' => 'ref_umum',
        'title' => 'Edit Data Pengeluaran',
      ];
      // var_dump($data);

      // return response()->json([
      //     'data' => $data,
      // ]);
      return response()->json([
        'body' => view('keluar.modaltambahmaster', [
          'saplikasi' => Saplikasi::where('aktif', 'Y')->first(),
          'keluar' => Keluarh::where('id', $id)->first(),
          // 'action' => route('keluar.update', $keluarh->id),
          'action' => 'keluarupdate',
          'vdata' => $data,
        ])->render(),
        'data' => $data,
      ]);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function update(Request $request, keluarh $keluarh)
  {
    if ($request->Ajax()) {
      $id = $request->id;
      if ($request->nokeluar === $request->nokeluarlama) {
        $validate = $request->validate(
          [
            'nokeluar' => 'required',
            'tglkeluar' => 'required',
          ],
          [
            'nokeluar.required' => 'No. SO harus di isi',
            'tglkeluar.required' => 'Tanggal SO harus di isi',
          ],
        );
      } else {
        $validate = $request->validate(
          [
            'nokeluar' => 'required|unique:keluarh|max:255',
            'tglkeluar' => 'required',
          ],
          [
            'nokeluar.required' => 'No. SO harus di isi',
            'tglkeluar.required' => 'Tanggal SO harus di isi',
          ],
        );
      }
      $keluarh = Keluarh::find($id);
      if ($validate) {
        $nokeluar = $request->nokeluar;
        // $subtotal = DB::table('keluard')->where('nokeluar', $nokeluar)->sum('subtotal');
        $subtotal = Keluard::where('nokeluar', $nokeluar)->sum('subtotal');
        $biaya_lain = isset($request->biaya_lain) ? $request->biaya_lain : '0';
        $materai = isset($request->materai) ? $request->materai : '0';
        $ppn = isset($request->ppn) ? $request->ppn : '0';
        $total_sementara = $biaya_lain + $subtotal + $materai;
        $total = $total_sementara + ($total_sementara * ($ppn / 100));
        $keluarh->fill([
          'nokeluar' => isset($request->nokeluar) ? $request->nokeluar : '',
          'tglkeluar' => isset($request->tglkeluar) ? $request->tglkeluar : '',
          'nofaktur' => isset($request->nofaktur) ? $request->nofaktur : '',
          'noinvoice' => isset($request->noinvoice) ? $request->noinvoice : '',
          'noreferensi' => isset($request->noreferensi) ? $request->noreferensi : '',
          'tgl_invoice' => isset($request->tgl_invoice) ? $request->tgl_invoice : '',
          'penerima' => isset($request->penerima) ? $request->penerima : '',
          'kdjntrans' => isset($request->kdjntrans) ? $request->kdjntrans : '',
          'kdgudang' => isset($request->kdgudang) ? $request->kdgudang : '',
          'jenis_order' => isset($request->jenis_order) ? $request->jenis_order : '',
          'ket_biaya_lain' => isset($request->ket_biaya_lain) ? $request->ket_biaya_lain : '',
          'biaya_lain' => $biaya_lain,
          'subtotal' => $subtotal,
          'total_sementara' => $total_sementara,
          'lppn' => isset($request->lppn) ? 'Y' : 'N',
          'ppn' => $ppn,
          'materai' => $materai,
          'total' => $total,
          'keterangan' => isset($request->keterangan) ? $request->keterangan : '',
          'user' => 'Update-' . $request->username . ', ' . date('d-m-Y h:i:s'),
        ]);
        $keluarh->save($validate);
        //Create History
        $tanggal = date('Y-m-d');
        $datetime = date('Y-m-d H:i:s');
        $dokumen = $nokeluar;
        $form = 'Pengeluaran Barang';
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

  public function keluarproses(Request $request, keluarh $keluarproses)
  {
    if ($request->Ajax()) {
      $lanjut = true;

      $id = $request->id;
      $keluarh = Keluarh::where('id', $id)->first();

      $tahun = date("Y", strtotime($keluarh->tglkeluar));
      $bulan = substr('0' . date("m", strtotime($keluarh->tglkeluar)), -2);
      $periode = $tahun . $bulan;
      $saplikasi = Saplikasi::where('aktif', 'Y')->first();
      if ($periode <= $saplikasi->closing_hpp) {
        // return response()->json('1');
        return response()->json([
          'sukses' => false, //view('tbbarang.tabel_barang')
        ]);
        $lanjut = false;
      }
      if ($lanjut == true) {

        $keluard = Keluard::where('nokeluar', $keluarh->nokeluar)->get();
        $lanjut = true;
        $aplikasi = Saplikasi::where('aktif', 'Y')->first();
        if ($aplikasi->kunci_stock == 'Y') {
          foreach ($keluard as $row) {
            //check stock tidak boleh minus
            $tbbarang = Tbbarang::where('kode', $row->kdbarang)->first();
            if ($tbbarang->stock - $row->qty < 0) {
              $lanjut = false;
            }
          }
        }

        if ($lanjut == true) {
          $keluarh = Keluarh::where('id', $id)->first();
          $nokeluar = $keluarh->nokeluar;
          $tglkeluar = $keluarh->tglkeluar;
          if ($keluarh->total == 0) {
            return response()->json([
              'sukses' => false, //view('tbbarang.tabel_barang')
            ]);
          } else {
            $keluarproses->load('keluardetail');
            $subtotal = $keluarproses->keluardetail->sum('subtotal');
            $subtotal = Keluarh::join('keluard', 'keluarh.nokeluar', '=', 'keluard.nokeluar')->where('keluard.nokeluar', $nokeluar)->sum('keluard.subtotal');
            // $subtotal = DB::table('keluard')->where('nokeluar', $nokeluar)->sum('subtotal');
            $total_sementara = $keluarproses->biaya_lain + $subtotal;
            $total = $total_sementara + ($total_sementara * ($keluarh->ppn / 100));
            $keluarh = Keluarh::find($id);
            $keluarh->fill([
              'proses' => 'Y',
              'subtotal' => $subtotal,
              'total_sementara' => $total_sementara,
              'total' => $total,
              'kurangbayar' => $total,
              'sudahbayar' => 0,
              'user' => 'Proses-' . session('username') . ', ' . date('d-m-Y h:i:s'),
            ]);
            $keluarh->save();
            $keluard = Keluard::where('nokeluar', $nokeluar)->get();
            foreach ($keluard as $row) {
              $idd = $row->id;
              $qty = $row->qty;
              //Update stock
              $tbbarang = Tbbarang::where('kode', $row->kdbarang)->first();
              $stockakhir = $tbbarang->stock - $qty;
              // DB::table('tbbarang')->where('kode', $row->kdbarang)->update(['stock' => $stockakhir]);
              // DB::table('keluard')->where('id', $idd)->update(['proses' => 'Y', 'hpp' => $tbbarang->hpp]);
              Tbbarang::where('kode', $row->kdbarang)->update(['stock' => $stockakhir]);
              Keluard::where('id', $idd)->update(['proses' => 'Y', 'hpp' => $tbbarang->hpp, 'tglkeluar' => $tglkeluar]);
            }
            //Create History
            $keluarh = Keluarh::where('id', $request->id)->first();
            $tanggal = date('Y-m-d');
            $datetime = date('Y-m-d H:i:s');
            $dokumen = $keluarh->nokeluar;
            $form = 'Pengeluaran Barang';
            $status = 'Proses';
            $catatan = isset($request->catatan) ? $request->catatan : '';
            $username = session('username');
            DB::table('hisuser')->insert(['tanggal' => $tanggal, 'dokumen' => $dokumen, 'form' => $form, 'status' => $status, 'user' => $username, 'catatan' => $catatan, 'datetime' => $datetime]);
            return response()->json([
              'sukses' => true,
            ]);
            // return redirect()->back()->with('message', 'Berhasil di update');
          }
        }
      } else {
        return response()->json([
          'sukses' => false,
        ]);
      }
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function keluarunproses(Request $request, keluarh $keluarh)
  {
    if ($request->Ajax()) {
      $id = $request->id;
      $user = 'Proses-' . session('username') . ', ' . date('d-m-Y h:i:s');
      // DB::table('keluarh')->where('id', $id)->update(['proses' => 'N', 'user' => $user]);
      Keluarh::where('id', $id)->update(['proses' => 'N', 'user' => $user]);
      //Create History
      $keluarh = Keluarh::where('id', $id)->first();
      $tanggal = date('Y-m-d');
      $datetime = date('Y-m-d H:i:s');
      $dokumen = $keluarh->nokeluar;
      $form = 'Pengeluaran Barang';
      $status = 'Batal Proses';
      $catatan = isset($request->catatan) ? $request->catatan : '';
      $username = session('username');
      DB::table('hisuser')->insert(['tanggal' => $tanggal, 'dokumen' => $dokumen, 'form' => $form, 'status' => $status, 'user' => $username, 'catatan' => $catatan, 'datetime' => $datetime]);
      //Create History
      $keluarh = Keluarh::where('id', $request->id)->first();
      $tanggal = date('Y-m-d');
      $datetime = date('Y-m-d H:i:s');
      $dokumen = $keluarh->nokeluar;
      $form = 'Pengeluaran Barang';
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

  public function keluarbatalproses(keluarh $keluarh, Request $request)
  {
    if ($request->Ajax()) {
      $id = $_GET['id'];
      $keluarh = Keluarh::where('id', $id)->first();
      $bulan = substr('0' . date('m', strtotime($keluarh->tglkeluar)), -2);
      $tahun = date('Y', strtotime($keluarh->tglkeluar));
      $periode = $tahun . $bulan;
      $saplikasi = Saplikasi::where('aktif', 'Y')->first();
      if ($periode <= $saplikasi->closing_hpp) {
        return response()->json('1');
      }
      $data = [
        'menu' => 'transaksi',
        'submenu' => 'keluar',
        'submenu1' => 'ref_umum',
        'title' => 'Batal Proses Pengeluaran',
      ];
      // var_dump($data);

      // return response()->json([
      //     'data' => $data,
      // ]);
      return response()->json([
        'body' => view('keluar.modalbatalproses', [
          'keluar' => Keluarh::where('id', $id)->first(),
          // 'action' => route('so.update', $soh->id),
          'action' => 'keluarbatalprosesok',
          'vdata' => $data,
        ])->render(),
        'data' => $data,
      ]);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function keluarbatalprosesok(Request $request, keluarh $keluarh)
  {
    if ($request->Ajax()) {
      $id = $request->id;
      $user = 'Proses-' . session('username') . ', ' . date('d-m-Y h:i:s');
      // DB::table('keluarh')->where('id', $id)->update(['proses' => 'N', 'user' => $user]);
      Keluarh::where('id', $id)->update(['proses' => 'N', 'user' => $user]);
      $keluarh = Keluarh::find($id);
      $keluard = Keluard::where('nokeluar', $keluarh->nokeluar)->get();
      foreach ($keluard as $row) {
        $idd = $row->id;
        $qty = $row->qty;
        //Update stock
        $tbbarang = Tbbarang::where('kode', $row->kdbarang)->first();
        $stockakhir = $tbbarang->stock + $qty;
        // DB::table('tbbarang')->where('kode', $row->kdbarang)->update(['stock' => $stockakhir]);
        // DB::table('keluard')->where('id', $idd)->update(['proses' => 'N', 'hpp' => $tbbarang->hpp]);
        Tbbarang::where('kode', $row->kdbarang)->update(['stock' => $stockakhir]);
        Keluard::where('id', $idd)->update(['proses' => 'N', 'hpp' => $tbbarang->hpp]);
      }
      //Create History
      $keluarh = Keluarh::where('id', $id)->first();
      $nokeluar = $keluarh->nokeluar;
      $tanggal = date('Y-m-d');
      $datetime = date('Y-m-d H:i:s');
      $dokumen = $nokeluar;
      $form = 'Pengeluaran Barang';
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

  public function keluarcancel(Request $request, keluarh $keluarh)
  {
    if ($request->Ajax()) {
      $id = $request->id;
      $user = 'Cancel-' . session('username') . ', ' . date('d-m-Y h:i:s');
      // DB::table('keluarh')->where('id', $id)->update(['batal' => 'Y', 'user' => $user]);
      Keluarh::where('id', $id)->update(['batal' => 'Y', 'user' => $user]);
      //Create History
      $keluarh = Keluarh::where('id', $request->id)->first();
      $tanggal = date('Y-m-d');
      $datetime = date('Y-m-d H:i:s');
      $dokumen = $keluarh->nokeluar;
      $form = 'Pengeluaran Barang';
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

  public function keluarambil(Request $request, keluarh $keluarh)
  {
    if ($request->Ajax()) {
      $id = $request->id;
      $user = 'Ambil-' . session('username') . ', ' . date('d-m-Y h:i:s');
      // DB::table('keluarh')->where('id', $id)->update(['batal' => 'N', 'user' => $user]);
      Keluarh::where('id', $id)->update(['batal' => 'N', 'user' => $user]);
      //Create History
      $keluarh = Keluarh::where('id', $request->id)->first();
      $tanggal = date('Y-m-d');
      $datetime = date('Y-m-d H:i:s');
      $dokumen = $keluarh->nokeluar;
      $form = 'Pengeluaran Barang';
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

  public function destroy(keluarh $keluarh, Request $request)
  {
    if ($request->Ajax()) {
      $id = $request->id;
      $keluarh = Keluarh::where('id', $id)->first();
      // $deleted = DB::table('keluarh')->where('id', $id)->delete();
      $deleted = Keluarh::where('id', $id)->delete();
      if ($deleted) {
        DB::table('keluard')->where('nokeluar', $keluarh->nokeluar)->delete();
        //Create History
        $tanggal = date('Y-m-d');
        $datetime = date('Y-m-d H:i:s');
        $dokumen = $keluarh->nokeluar;
        $form = 'Pengeluaran Barang';
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


  public function ambildatatbjntransk(Request $request, Tbjntrans $tbjntrans)
  {
    if ($request->Ajax()) {
      $kdjntrans = $request->kdjntrans;
      $datatbjntrans = $tbjntrans->where('keterangan', 'OUT')->orderBy('nama')->get();
      $isidata = "<option value='' selected>[Pilih Jenis Transaksi]</option>";
      foreach ($datatbjntrans as $row) {
        if ($row['kode'] == $kdjntrans) {
          $isidata .= '<option value="' . $row['kode'] . '" selected>' . $row['nama'] .  ' </option>';
        } else {
          $isidata .= '<option value="' . $row['kode'] . '">' . $row['nama'] . '</option>';
        }
      }
      $msg = [
        'data' => $isidata
      ];
      echo json_encode($msg);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function keluarinputkeluard(keluarh $keluarh, Request $request)
  {
    if ($request->Ajax()) {
      $id = $_GET['id'];
      $username = session('username');
      $keluarh = Keluarh::where('id', $id)->first();
      $nokeluar = $keluarh->nokeluar;
      $data = [
        'menu' => 'transaksi',
        'submenu' => 'keluar',
        'submenu1' => 'ref_umum',
        'title' => 'Detail Data Pengeluaran',
      ];
      // var_dump($data);

      // return response()->json([
      //     'data' => $data,
      // ]);
      return response()->json([
        'body' => view('keluar.modaldetail', [
          'userdtlmenu' => Userdtl::join('tbmodule', 'userdtl.cmodule', '=', 'tbmodule.cmodule')->where('userdtl.pakai', '1')->where('username', $username)->orderBy('userdtl.nurut')->get(),
          'userdtl' => Userdtl::where('cmodule', 'Pengeluaran Barang')->where('username', $username)->first(),
          'saplikasi' => Saplikasi::where('aktif', 'Y')->first(),
          'keluar' => Keluarh::where('id', $id)->first(),
          'keluard' => Keluard::where('nokeluar', $nokeluar)->get(),
          // 'action' => route('keluar.update', $keluarh->id),
          'action' => 'keluartambahdetail',
          'vdata' => $data,
        ])->render(),
        'data' => $data,
      ]);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function keluardajax(Request $request) //: View
  {
    $nokeluar = $request->nokeluar;
    if ($request->ajax()) {
      // $data = Keluard::where('nokeluar', $nokeluar); //->orderBy('kode', 'asc');
      $data = Keluard::leftjoin('tbsatuan', 'tbsatuan.kode', '=', 'keluard.kdsatuan')
        ->select('keluard.*', 'tbsatuan.nama as nmsatuan')
        ->where('nokeluar', $nokeluar); //->orderBy('kode', 'asc');    
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

  public function keluartambahdetail(Request $request, keluardRequest $keluardrequest, keluard $keluard)
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
        // $reckeluard = Keluard::where('nokeluar', $request->nokeluard)->where('kdbarang', $request->kdbarang)->first();
        // if (isset($reckeluard->nokeluar)) {
        //   $msg = [
        //     'sukses' => 'Data gagal di tambah',
        //   ];
        // } else {
        $keluard->fill([
          'nokeluar' => isset($request->nokeluard) ? $request->nokeluard : '',
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
        $keluard->save($validate);
        $keluarh = Keluarh::where('nokeluar', $request->nokeluard)->first();
        $biaya_lain = $keluarh->biaya_lain;
        $materai = $keluarh->materai;
        $ppn = $keluarh->ppn;
        // $subtotal = DB::table('keluard')->where('nokeluar', $request->nokeluard)->sum('subtotal');
        $subtotal = Keluard::where('nokeluar', $request->nokeluard)->sum('subtotal');
        $total_sementara = $biaya_lain + $subtotal + $materai;
        $total = $total_sementara + ($total_sementara * ($ppn / 100));
        // DB::table('keluarh')->where('nokeluar', $request->nokeluard)->update([
        //   'subtotal' => $subtotal, 'biaya_lain' => $biaya_lain, 'materai' => $materai, 'total_sementara' => $total_sementara, 'total_sementara' =>
        //   $total_sementara, 'total' => $total
        // ]);
        Keluarh::where('nokeluar', $request->nokeluard)->update([
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


  public function keluartotaldetail(keluarh $keluarh, Request $request)
  {
    if ($request->Ajax()) {
      $nokeluar = $request->nokeluar;
      $data = [
        'menu' => 'transaksi',
        'submenu' => 'keluar',
        'submenu1' => 'ref_umum',
        'title' => 'Detail Data Pengeluaran',
      ];
      // var_dump($data);

      // return response()->json([
      //     'data' => $data,
      // ]);
      return response()->json([
        'body' => view('keluar.totaldetail', [
          'subtotalkeluard' => Keluard::where('nokeluar', $nokeluar)->sum('subtotal'),
          'qtykeluard' => Keluard::where('nokeluar', $nokeluar)->sum('qty'),
          'vdata' => $data,
        ])->render(),
        'data' => $data,
      ]);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function keluarcetak(Request $request)
  {
    $row = Keluarh::where('id', $request->id)->first();
    $nokeluar = $row->nokeluar;
    $data = [
      'keluarh' => $row,
      'keluard' => Keluarh::join('keluard', 'keluard.nokeluar', '=', 'keluarh.nokeluar')
        ->join('tbsatuan', 'tbsatuan.kode', '=', 'keluard.kdsatuan')
        ->select('keluarh.*', 'keluard.*', 'tbsatuan.nama as nmsatuan')
        ->where('keluarh.nokeluar', $nokeluar)->get(),
    ];
    // return view('keluar.cetak', $data);

    $rowd = Keluard::where('nokeluar', $nokeluar)->get();
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
    $keluarh = Keluarh::where('id', $request->id)->first();
    $tanggal = date('Y-m-d');
    $datetime = date('Y-m-d H:i:s');
    $dokumen = $keluarh->nokeluar;
    $form = 'Pengeluaran Barang';
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
    $mpdf->WriteHTML(view('keluar.cetak', $data));
    $namafile = $nokeluar . ' - ' . date('dmY H:i:s') . '.pdf';
    //return the PDF for download
    // return $mpdf->Output($request->get('name') . $namafile, Destination::DOWNLOAD);
    $mpdf->Output($namafile, 'I');
    exit;
  }
}
