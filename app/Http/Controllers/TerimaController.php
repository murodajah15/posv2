<?php

namespace App\Http\Controllers;

use Mpdf\Mpdf;
use Mpdf\Output\Destination;
use App\Http\Requests\TerimahRequest;
use App\Http\Requests\TerimadRequest;
use Illuminate\Http\Request;
use Session;
// use Yajra\DataTables\Contracts\DataTables;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use App\Models\Terimah;
use App\Models\Terimad;
use App\Models\Tbbarang;
use App\Models\Tbjntrans;
use App\Models\Userdtl;
use App\Models\Saplikasi;

// //return type View
// use Illuminate\View\View;

class TerimaController extends Controller
{
  public function index(Request $request) //: View
  {
    $username = session('username');
    $data = [
      'menu' => 'transaksi',
      'submenu' => 'terima',
      'submenu1' => 'ref_umum',
      'title' => 'Penerimaan Barang',
      // 'Terimah' => Terimah::all(),
      'userdtlmenu' => Userdtl::join('tbmodule', 'userdtl.cmodule', '=', 'tbmodule.cmodule')->where('userdtl.pakai', '1')->where('username', $username)->orderBy('userdtl.nurut')->get(),
      'userdtl' => Userdtl::where('cmodule', 'Penerimaan Barang')->where('username', $username)->first(),
    ];
    $userdtl = Userdtl::where('cmodule', 'Penerimaan Barang')->where('username', $username)->first();
    if ($userdtl->pakai == '1') {
      return view('terima.index')->with($data);
    } else {
      return redirect('home');
    }
  }
  public function terimaajax(Request $request) //: View
  {
    if ($request->ajax()) {
      $data = Terimah::select('*'); //->orderBy('kode', 'asc');
      return Datatables::of($data)
        ->addIndexColumn()
        ->addColumn('kode1', function ($row) {
          $id = $row['id'];
          $btn = $row['kode']; //'<a href="#"' . 'onclick=detail(' . $id . ')>' .  $row['kode'] . '</a>';
          return $btn;
        })
        ->rawColumns(['kode1'])
        ->make(true);
      return view('terima');
    }
  }

  public function create(Request $request)
  {
    if ($request->Ajax()) {
      $username = session('username');
      $data = [
        'menu' => 'transaksi',
        'submenu' => 'terima',
        'submenu1' => 'ref_umum',
        'title' => 'Tambah Data Penerimaan',
      ];
      return response()->json([
        'body' => view('terima.modaltambahmaster', [
          'saplikasi' => Saplikasi::where('aktif', 'Y')->first(),
          'terima' => new Terimah(),
          'action' => route('terima.store'),
          'vdata' => $data,
        ])->render(),
        'data' => $data,

      ]);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function store(TerimahRequest $request, Terimah $terimah)
  // public function store(Request $request, Terimah $terimah)
  {
    if ($request->Ajax()) {
      $sort_num = 0;
      $new_code = $request->noterima;
      $ketemu = 0;
      $record = 0;
      $rec = Terimah::where('noterima', $new_code)->first();
      if ($rec == null) {
        $aplikasi = Saplikasi::where('aktif', 'Y')->first();
        $sort_num = $aplikasi->noterima;
        $tahun = $aplikasi->tahun;
        $bulan = $aplikasi->bulan;
        Saplikasi::where('aktif', 'Y')->update(['noterima' => $sort_num + 1]);
      } else {
        while ($ketemu == $record) { //0=0
          $aplikasi = Saplikasi::where('aktif', 'Y')->first();
          $sort_num = $aplikasi->noterima;
          $tahun = $aplikasi->tahun;
          $bulan = $aplikasi->bulan;
          Saplikasi::where('aktif', 'Y')->update(['noterima' => $sort_num + 1]);
          $new_code = 'TB' . $tahun . sprintf('%02s', $bulan) . sprintf("%05s", $sort_num + 1);
          $rec = Terimah::where('noterima', $new_code)->first();
          if ($rec == null) {
            $record = 0;
            Saplikasi::where('aktif', 'Y')->update(['noterima' => $sort_num + 1]);
            break;
          } else {
            Saplikasi::where('aktif', 'Y')->update(['noterima' => $sort_num + 1]);
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
        $terimah->fill([
          'noterima' => isset($request->noterima) ? $new_code : '',
          'tglterima' => isset($request->tglterima) ? $request->tglterima : '',
          'nofaktur' => isset($request->nofaktur) ? $request->nofaktur : '',
          'noreferensi' => isset($request->noreferensi) ? $request->noreferensi : '',
          'noinvoice' => isset($request->noinvoice) ? $request->noinvoice : '',
          'tgl_invoice' => isset($request->tgl_invoice) ? $request->tgl_invoice : '',
          'penerima' => isset($request->penerima) ? $request->penerima : '',
          'kdgudang' => isset($request->kdgudang) ? $request->kdgudang : '',
          'kdjntrans' => isset($request->kdjntrans) ? $request->kdjntrans : '',
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
        $terimah->save($validated);
        //Create History
        $tanggal = date('Y-m-d');
        $datetime = date('Y-m-d H:i:s');
        $dokumen = $new_code;
        $form = 'Penerimaan Barang';
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
        'submenu' => 'terima',
        'submenu1' => 'ref_umum',
        'title' => 'Detail Penerimaan',
        // 'userdtl' => Userdtl::where('cmodule', 'Penerimaan Barang')->where('username', $username)->first(),
      ];
      return response()->json([
        'body' => view('terima.modaltambahmaster', [
          'saplikasi' => Saplikasi::where('aktif', 'Y')->first(),
          'terima' => Terimah::where('id', $id)->first(),
          'action' => route('tbbarang.store'),
          'vdata' => $data,
        ])->render(),
        'data' => $data,
      ]);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function edit(Terimah $terimah, Request $request)
  {
    if ($request->Ajax()) {
      $id = $_GET['id'];
      $username = session('username');
      $data = [
        'menu' => 'transaksi',
        'submenu' => 'terima',
        'submenu1' => 'ref_umum',
        'title' => 'Edit Data Penerimaan',
      ];
      // var_dump($data);

      // return response()->json([
      //     'data' => $data,
      // ]);
      return response()->json([
        'body' => view('terima.modaltambahmaster', [
          'saplikasi' => Saplikasi::where('aktif', 'Y')->first(),
          'terima' => Terimah::where('id', $id)->first(),
          // 'action' => route('terima.update', $terimah->id),
          'action' => 'terimaupdate',
          'vdata' => $data,
        ])->render(),
        'data' => $data,
      ]);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function update(Request $request, Terimah $terimah)
  {
    if ($request->Ajax()) {
      $id = $request->id;
      if ($request->noterima === $request->noterimalama) {
        $validate = $request->validate(
          [
            'noterima' => 'required',
            'tglterima' => 'required',
          ],
          [
            'noterima.required' => 'No. SO harus di isi',
            'tglterima.required' => 'Tanggal SO harus di isi',
          ],
        );
      } else {
        $validate = $request->validate(
          [
            'noterima' => 'required|unique:Terimah|max:255',
            'tglterima' => 'required',
          ],
          [
            'noterima.required' => 'No. SO harus di isi',
            'tglterima.required' => 'Tanggal SO harus di isi',
          ],
        );
      }
      $terimah = Terimah::find($id);
      if ($validate) {
        $noterima = $request->noterima;
        // $subtotal = DB::table('terimad')->where('noterima', $noterima)->sum('subtotal');
        $subtotal = Terimad::where('noterima', $noterima)->sum('subtotal');
        $biaya_lain = isset($request->biaya_lain) ? $request->biaya_lain : '0';
        $materai = isset($request->materai) ? $request->materai : '0';
        $ppn = isset($request->ppn) ? $request->ppn : '0';
        $total_sementara = $biaya_lain + $subtotal + $materai;
        $total = $total_sementara + ($total_sementara * ($ppn / 100));
        $terimah->fill([
          'noterima' => isset($request->noterima) ? $request->noterima : '',
          'tglterima' => isset($request->tglterima) ? $request->tglterima : '',
          'nofaktur' => isset($request->nofaktur) ? $request->nofaktur : '',
          'noreferensi' => isset($request->noreferensi) ? $request->noreferensi : '',
          'noinvoice' => isset($request->noinvoice) ? $request->noinvoice : '',
          'tgl_invoice' => isset($request->tgl_invoice) ? $request->tgl_invoice : '',
          'penerima' => isset($request->penerima) ? $request->penerima : '',
          'kdgudang' => isset($request->kdgudang) ? $request->kdgudang : '',
          'kdjntrans' => isset($request->kdjntrans) ? $request->kdjntrans : '',
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
        $terimah->save($validate);
        //Create History
        $tanggal = date('Y-m-d');
        $datetime = date('Y-m-d H:i:s');
        $dokumen = $noterima;
        $form = 'Penerimaan Barang';
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

  public function terimaproses(Request $request, Terimah $terimaproses)
  {
    if ($request->Ajax()) {
      $lanjut = true;

      $id = $request->id;
      $terimah = Terimah::where('id', $id)->first();

      $tahun = date("Y", strtotime($terimah->tglterima));
      $bulan = substr('0' . date("m", strtotime($terimah->tglterima)), -2);
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
        $noterima = $terimah->noterima;
        $tglterima = $terimah->tglterima;
        if ($terimah->total == 0) {
          return response()->json([
            'sukses' => false, //view('tbbarang.tabel_barang')
          ]);
        } else {
          $terimaproses->load('terimadetail');
          $subtotal = $terimaproses->Terimadetail->sum('subtotal');
          $subtotal = Terimah::join('terimad', 'terimah.noterima', '=', 'terimad.noterima')->where('terimad.noterima', $noterima)->sum('terimad.subtotal');
          // $subtotal = DB::table('terimad')->where('noterima', $noterima)->sum('subtotal');
          $total_sementara = $terimaproses->biaya_lain + $subtotal;
          $total = $total_sementara + ($total_sementara * ($terimah->ppn / 100));
          $terimah = Terimah::find($id);
          $terimah->fill([
            'proses' => 'Y',
            'subtotal' => $subtotal,
            'total_sementara' => $total_sementara,
            'total' => $total,
            'kurangbayar' => $total,
            'sudahbayar' => 0,
            'user' => 'Proses-' . session('username') . ', ' . date('d-m-Y h:i:s'),
          ]);
          $terimah->save();
          $terimad = Terimad::where('noterima', $noterima)->get();
          foreach ($terimad as $row) {
            $idd = $row->id;
            $qty = $row->qty;
            //Update stock
            $tbbarang = Tbbarang::where('kode', $row->kdbarang)->first();
            $stockakhir = $tbbarang->stock + $qty;
            // DB::table('tbbarang')->where('kode', $row->kdbarang)->update(['stock' => $stockakhir]);
            // DB::table('terimad')->where('id', $idd)->update(['proses' => 'Y', 'hpp' => $tbbarang->hpp]);
            Tbbarang::where('kode', $row->kdbarang)->update(['stock' => $stockakhir]);
            Terimad::where('id', $idd)->update(['proses' => 'Y', 'hpp' => $tbbarang->hpp]);
          }
          //Create History
          $terimah = Terimah::where('id', $request->id)->first();
          $tanggal = date('Y-m-d');
          $datetime = date('Y-m-d H:i:s');
          $dokumen = $terimah->noterima;
          $form = 'Penerimaan Barang';
          $status = 'Proses';
          $catatan = isset($request->catatan) ? $request->catatan : '';
          $username = session('username');
          DB::table('hisuser')->insert(['tanggal' => $tanggal, 'dokumen' => $dokumen, 'form' => $form, 'status' => $status, 'user' => $username, 'catatan' => $catatan, 'datetime' => $datetime]);
          return response()->json([
            'sukses' => true, //view('tbbarang.tabel_barang')
          ]);
          // return redirect()->back()->with('message', 'Berhasil di update');
        }
      }
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function terimaunproses(Request $request, Terimah $terimah)
  {
    if ($request->Ajax()) {
      $id = $request->id;
      $user = 'Proses-' . session('username') . ', ' . date('d-m-Y h:i:s');
      // DB::table('Terimah')->where('id', $id)->update(['proses' => 'N', 'user' => $user]);
      Terimah::where('id', $id)->update(['proses' => 'N', 'user' => $user]);
      //Create History
      $terimah = Terimah::where('id', $id)->first();
      $tanggal = date('Y-m-d');
      $datetime = date('Y-m-d H:i:s');
      $dokumen = $terimah->noterima;
      $form = 'Penerimaan Barang';
      $status = 'Batal Proses';
      $catatan = isset($request->catatan) ? $request->catatan : '';
      $username = session('username');
      DB::table('hisuser')->insert(['tanggal' => $tanggal, 'dokumen' => $dokumen, 'form' => $form, 'status' => $status, 'user' => $username, 'catatan' => $catatan, 'datetime' => $datetime]);
      //Create History
      $terimah = Terimah::where('id', $request->id)->first();
      $tanggal = date('Y-m-d');
      $datetime = date('Y-m-d H:i:s');
      $dokumen = $terimah->noterima;
      $form = 'Penerimaan Barang';
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

  public function terimabatalproses(Terimah $terimah, Request $request)
  {
    if ($request->Ajax()) {
      $id = $_GET['id'];
      $terimah = Terimah::where('id', $id)->first();
      $bulan = substr('0' . date('m', strtotime($terimah->tglterima)), -2);
      $tahun = date('Y', strtotime($terimah->tglterima));
      $periode = $tahun . $bulan;
      $saplikasi = Saplikasi::where('aktif', 'Y')->first();
      if ($periode <= $saplikasi->closing_hpp) {
        return response()->json('1');
      }

      $data = [
        'menu' => 'transaksi',
        'submenu' => 'terima',
        'submenu1' => 'ref_umum',
        'title' => 'Batal Proses Penerimaan',
      ];
      // var_dump($data);

      // return response()->json([
      //     'data' => $data,
      // ]);
      return response()->json([
        'body' => view('terima.modalbatalproses', [
          'terima' => Terimah::where('id', $id)->first(),
          // 'action' => route('so.update', $soh->id),
          'action' => 'terimabatalprosesok',
          'vdata' => $data,
        ])->render(),
        'data' => $data,
      ]);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function terimabatalprosesok(Request $request, Terimah $terimah)
  {
    if ($request->Ajax()) {
      $id = $request->id;
      $user = 'Proses-' . session('username') . ', ' . date('d-m-Y h:i:s');
      // DB::table('terimah')->where('id', $id)->update(['proses' => 'N', 'user' => $user]);
      Terimah::where('id', $id)->update(['proses' => 'N', 'user' => $user]);
      $terimah = Terimah::find($id);
      $terimad = Terimad::where('noterima', $terimah->noterima)->get();
      foreach ($terimad as $row) {
        $idd = $row->id;
        $qty = $row->qty;
        //Update stock
        $tbbarang = Tbbarang::where('kode', $row->kdbarang)->first();
        $stockakhir = $tbbarang->stock - $qty;
        // DB::table('tbbarang')->where('kode', $row->kdbarang)->update(['stock' => $stockakhir]);
        // DB::table('terimad')->where('id', $idd)->update(['proses' => 'N', 'hpp' => $tbbarang->hpp]);
        Tbbarang::where('kode', $row->kdbarang)->update(['stock' => $stockakhir]);
        Terimad::where('id', $idd)->update(['proses' => 'N', 'hpp' => $tbbarang->hpp]);
      }
      //Create History
      $terimah = Terimah::where('id', $id)->first();
      $noterima = $terimah->noterima;
      $tanggal = date('Y-m-d');
      $datetime = date('Y-m-d H:i:s');
      $dokumen = $noterima;
      $form = 'Penerimaan Barang';
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

  public function terimacancel(Request $request, Terimah $terimah)
  {
    if ($request->Ajax()) {
      $id = $request->id;
      $user = 'Cancel-' . session('username') . ', ' . date('d-m-Y h:i:s');
      // DB::table('terimah')->where('id', $id)->update(['batal' => 'Y', 'user' => $user]);
      Terimah::where('id', $id)->update(['batal' => 'Y', 'user' => $user]);
      //Create History
      $terimah = Terimah::where('id', $request->id)->first();
      $tanggal = date('Y-m-d');
      $datetime = date('Y-m-d H:i:s');
      $dokumen = $terimah->noterima;
      $form = 'Penerimaan Barang';
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

  public function terimaambil(Request $request, Terimah $terimah)
  {
    if ($request->Ajax()) {
      $id = $request->id;
      $user = 'Ambil-' . session('username') . ', ' . date('d-m-Y h:i:s');
      // DB::table('terimah')->where('id', $id)->update(['batal' => 'N', 'user' => $user]);
      Terimah::where('id', $id)->update(['batal' => 'N', 'user' => $user]);
      //Create History
      $terimah = Terimah::where('id', $request->id)->first();
      $tanggal = date('Y-m-d');
      $datetime = date('Y-m-d H:i:s');
      $dokumen = $terimah->noterima;
      $form = 'Penerimaan Barang';
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

  public function destroy(Terimah $terimah, Request $request)
  {
    if ($request->Ajax()) {
      $id = $request->id;
      $terimah = Terimah::where('id', $id)->first();
      // $deleted = DB::table('Terimah')->where('id', $id)->delete();
      $deleted = Terimah::where('id', $id)->delete();
      if ($deleted) {
        // DB::table('terimad')->where('noterima', $terimah->noterima)->delete();
        Terimad::where('noterima', $terimah->noterima)->delete();
        //Create History
        $tanggal = date('Y-m-d');
        $datetime = date('Y-m-d H:i:s');
        $dokumen = $terimah->noterima;
        $form = 'Penerimaan Barang';
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


  public function ambildatatbjntranst(Request $request, Tbjntrans $tbjntrans)
  {
    if ($request->Ajax()) {
      $kdjntrans = $request->kdjntrans;
      $datatbjntrans = $tbjntrans->where('keterangan', 'IN')->orderBy('nama')->get();
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

  public function terimainputterimad(Terimah $terimah, Request $request)
  {
    if ($request->Ajax()) {
      $id = $_GET['id'];
      $username = session('username');
      $terimah = Terimah::where('id', $id)->first();
      $noterima = $terimah->noterima;
      $data = [
        'menu' => 'transaksi',
        'submenu' => 'terima',
        'submenu1' => 'ref_umum',
        'title' => 'Detail Data Penerimaan',
      ];
      // var_dump($data);

      // return response()->json([
      //     'data' => $data,
      // ]);
      return response()->json([
        'body' => view('terima.modaldetail', [
          'userdtlmenu' => Userdtl::join('tbmodule', 'userdtl.cmodule', '=', 'tbmodule.cmodule')->where('userdtl.pakai', '1')->where('username', $username)->orderBy('userdtl.nurut')->get(),
          'userdtl' => Userdtl::where('cmodule', 'Penerimaan Barang')->where('username', $username)->first(),
          'saplikasi' => Saplikasi::where('aktif', 'Y')->first(),
          'terima' => Terimah::where('id', $id)->first(),
          'terimad' => Terimad::where('noterima', $noterima)->get(),
          // 'action' => route('terima.update', $terimah->id),
          'action' => 'terimatambahdetail',
          'vdata' => $data,
        ])->render(),
        'data' => $data,
      ]);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function terimadajax(Request $request) //: View
  {
    $noterima = $request->noterima;
    if ($request->ajax()) {
      // $data = Terimad::where('noterima', $noterima); //->orderBy('kode', 'asc');
      $data = Terimad::leftjoin('tbsatuan', 'tbsatuan.kode', '=', 'terimad.kdsatuan')
        ->select('terimad.*', 'tbsatuan.nama as nmsatuan')
        ->where('noterima', $noterima); //->orderBy('kode', 'asc');      
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

  public function terimatambahdetail(Request $request, TerimadRequest $Terimadrequest, Terimad $Terimad)
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
        // $recTerimad = Terimad::where('noterima', $request->noTerimad)->where('kdbarang', $request->kdbarang)->first();
        // if (isset($recTerimad->noterima)) {
        //   $msg = [
        //     'sukses' => 'Data gagal di tambah',
        //   ];
        // } else {
        $Terimad->fill([
          'noterima' => isset($request->noTerimad) ? $request->noTerimad : '',
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
        $Terimad->save($validate);
        $terimah = Terimah::where('noterima', $request->noTerimad)->first();
        $biaya_lain = $terimah->biaya_lain;
        $materai = $terimah->materai;
        $ppn = $terimah->ppn;
        // $subtotal = DB::table('terimad')->where('noterima', $request->noTerimad)->sum('subtotal');
        $subtotal = Terimad::where('noterima', $request->noTerimad)->sum('subtotal');
        $total_sementara = $biaya_lain + $subtotal + $materai;
        $total = $total_sementara + ($total_sementara * ($ppn / 100));
        // DB::table('Terimah')->where('noterima', $request->noTerimad)->update([
        //   'subtotal' => $subtotal, 'biaya_lain' => $biaya_lain, 'materai' => $materai, 'total_sementara' => $total_sementara, 'total_sementara' =>
        //   $total_sementara, 'total' => $total
        // ]);
        Terimah::where('noterima', $request->noTerimad)->update([
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


  public function terimatotaldetail(Terimah $terimah, Request $request)
  {
    if ($request->Ajax()) {
      $noterima = $request->noterima;
      $terimah = Terimah::where('noterima', $noterima)->first();
      $data = [
        'menu' => 'transaksi',
        'submenu' => 'terima',
        'submenu1' => 'ref_umum',
        'title' => 'Detail Data Penerimaan',
      ];
      // var_dump($data);

      // return response()->json([
      //     'data' => $data,
      // ]);
      return response()->json([
        'body' => view('terima.totaldetail', [
          'subtotalterimad' => Terimad::where('noterima', $noterima)->sum('subtotal'),
          'qtyterimad' => Terimad::where('noterima', $noterima)->sum('qty'),
          'vdata' => $data,
        ])->render(),
        'data' => $data,
      ]);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function terimacetak(Request $request)
  {
    $row = Terimah::where('id', $request->id)->first();
    $noterima = $row->noterima;
    $data = [
      'terimah' => $row,
      'terimad' => Terimah::join('terimad', 'terimad.noterima', '=', 'terimah.noterima')
        ->join('tbsatuan', 'tbsatuan.kode', '=', 'terimad.kdsatuan')
        ->select('terimah.*', 'terimad.*', 'tbsatuan.nama as nmsatuan')
        ->where('terimah.noterima', $noterima)->get(),
    ];
    // return view('terima.cetak', $data);

    $rowd = Terimad::where('noterima', $noterima)->get();
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
    $terimah = Terimah::where('id', $request->id)->first();
    $tanggal = date('Y-m-d');
    $datetime = date('Y-m-d H:i:s');
    $dokumen = $terimah->noterima;
    $form = 'Penerimaan Barang';
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
    $mpdf->WriteHTML(view('terima.cetak', $data));
    $namafile = $noterima . ' - ' . date('dmY H:i:s') . '.pdf';
    //return the PDF for download
    // return $mpdf->Output($request->get('name') . $namafile, Destination::DOWNLOAD);
    $mpdf->Output($namafile, 'I');
    exit;
  }
}
