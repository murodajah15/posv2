<?php

namespace App\Http\Controllers;

use Mpdf\Mpdf;
use Mpdf\Output\Destination;
use App\Http\Requests\OpnamehRequest;
use App\Http\Requests\OpnamedRequest;
use Illuminate\Http\Request;
use Session;
// use Yajra\DataTables\Contracts\DataTables;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use App\Models\Opnameh;
use App\Models\Opnamed;
use App\Models\Tbbarang;
use App\Models\Userdtl;
use App\Models\Saplikasi;

// //return type View
// use Illuminate\View\View;

class OpnameController extends Controller
{
  public function index(Request $request) //: View
  {
    $username = session('username');
    $data = [
      'menu' => 'transaksi',
      'submenu' => 'opname',
      'submenu1' => 'ref_umum',
      'title' => 'Stock Opname',
      // 'opnameh' => Opnameh::all(),
      'userdtlmenu' => Userdtl::join('tbmodule', 'userdtl.cmodule', '=', 'tbmodule.cmodule')->where('userdtl.pakai', '1')->where('username', $username)->orderBy('userdtl.nurut')->get(),
      'userdtl' => Userdtl::where('cmodule', 'Stock Opname')->where('username', $username)->first(),
    ];
    $userdtl = Userdtl::where('cmodule', 'Stock Opname')->where('username', $username)->first();
    if ($userdtl->pakai == '1') {
      return view('opname.index')->with($data);
    } else {
      return redirect('home');
    }
  }
  public function opnameajax(Request $request) //: View
  {
    if ($request->ajax()) {
      $data = Opnameh::select('*'); //->orderBy('kode', 'asc');
      return Datatables::of($data)
        ->addIndexColumn()
        ->addColumn('kode1', function ($row) {
          $id = $row['id'];
          $btn = $row['kode']; //'<a href="#"' . 'onclick=detail(' . $id . ')>' .  $row['kode'] . '</a>';
          return $btn;
        })
        ->rawColumns(['kode1'])
        ->make(true);
      return view('opname');
    }
  }

  public function create(Request $request)
  {
    if ($request->Ajax()) {
      $username = session('username');
      $data = [
        'menu' => 'transaksi',
        'submenu' => 'opname',
        'submenu1' => 'ref_umum',
        'title' => 'Tambah Data Stock Opname',
      ];
      return response()->json([
        'body' => view('opname.modaltambahmaster', [
          'saplikasi' => Saplikasi::where('aktif', 'Y')->first(),
          'opname' => new opnameh(),
          'action' => route('opname.store'),
          'vdata' => $data,
        ])->render(),
        'data' => $data,

      ]);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function store(opnamehRequest $request, opnameh $opnameh)
  // public function store(Request $request, opnameh $opnameh)
  {
    if ($request->Ajax()) {
      $sort_num = 0;
      $new_code = $request->noopname;
      $ketemu = 0;
      $record = 0;
      $rec = Opnameh::where('noopname', $new_code)->first();
      if ($rec == null) {
        $aplikasi = Saplikasi::where('aktif', 'Y')->first();
        $sort_num = $aplikasi->noopname;
        $tahun = $aplikasi->tahun;
        $bulan = $aplikasi->bulan;
        Saplikasi::where('aktif', 'Y')->update(['noopname' => $sort_num + 1]);
      } else {
        while ($ketemu == $record) { //0=0
          $aplikasi = Saplikasi::where('aktif', 'Y')->first();
          $sort_num = $aplikasi->noopname;
          $tahun = $aplikasi->tahun;
          $bulan = $aplikasi->bulan;
          Saplikasi::where('aktif', 'Y')->update(['noopname' => $sort_num + 1]);
          $new_code = 'OP' . $tahun . sprintf('%02s', $bulan) . sprintf("%05s", $sort_num + 1);
          $rec = Opnameh::where('noopname', $new_code)->first();
          if ($rec == null) {
            $record = 0;
            Saplikasi::where('aktif', 'Y')->update(['noopname' => $sort_num + 1]);
            break;
          } else {
            Saplikasi::where('aktif', 'Y')->update(['noopname' => $sort_num + 1]);
          }
        }
      }
      $validated = $request->validated();
      if ($validated) {
        $opnameh->fill([
          'noopname' => isset($request->noopname) ? $new_code : '',
          'tglopname' => isset($request->tglopname) ? $request->tglopname : '',
          'pelaksana' => isset($request->pelaksana) ? $request->pelaksana : '',
          'keterangan' => isset($request->keterangan) ? $request->keterangan : '',
          'user' => 'Tambah-' . $request->username . ', ' . date('d-m-Y h:i:s'),
        ]);
        $opnameh->save($validated);
        //Create History
        $tanggal = date('Y-m-d');
        $datetime = date('Y-m-d H:i:s');
        $dokumen = $new_code;
        $form = 'Stock Opname';
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
        'submenu' => 'opname',
        'submenu1' => 'ref_umum',
        'title' => 'Detail Stock Opname',
        // 'userdtl' => Userdtl::where('cmodule', 'Stock Opname')->where('username', $username)->first(),
      ];
      return response()->json([
        'body' => view('opname.modaltambahmaster', [
          'saplikasi' => Saplikasi::where('aktif', 'Y')->first(),
          'opname' => Opnameh::where('id', $id)->first(),
          'action' => route('tbbarang.store'),
          'vdata' => $data,
        ])->render(),
        'data' => $data,
      ]);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function edit(opnameh $opnameh, Request $request)
  {
    if ($request->Ajax()) {
      $id = $_GET['id'];
      $username = session('username');
      $data = [
        'menu' => 'transaksi',
        'submenu' => 'opname',
        'submenu1' => 'ref_umum',
        'title' => 'Edit Data Stock Opname',
      ];
      // var_dump($data);

      // return response()->json([
      //     'data' => $data,
      // ]);
      return response()->json([
        'body' => view('opname.modaltambahmaster', [
          'saplikasi' => Saplikasi::where('aktif', 'Y')->first(),
          'opname' => Opnameh::where('id', $id)->first(),
          // 'action' => route('opname.update', $opnameh->id),
          'action' => 'opnameupdate',
          'vdata' => $data,
        ])->render(),
        'data' => $data,
      ]);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function update(Request $request, opnameh $opnameh)
  {
    if ($request->Ajax()) {
      $id = $request->id;
      if ($request->noopname === $request->noopnamelama) {
        $validate = $request->validate(
          [
            'noopname' => 'required',
            'tglopname' => 'required',
          ],
          [
            'noopname.required' => 'No. SO harus di isi',
            'tglopname.required' => 'Tanggal SO harus di isi',
          ],
        );
      } else {
        $validate = $request->validate(
          [
            'noopname' => 'required|unique:opnameh|max:255',
            'tglopname' => 'required',
          ],
          [
            'noopname.required' => 'No. SO harus di isi',
            'tglopname.required' => 'Tanggal SO harus di isi',
          ],
        );
      }
      $opnameh = Opnameh::find($id);
      if ($validate) {
        $noopname = $request->noopname;
        $opnameh->fill([
          'tglopname' => isset($request->tglopname) ? $request->tglopname : '',
          'pelaksana' => isset($request->pelaksana) ? $request->pelaksana : '',
          'keterangan' => isset($request->keterangan) ? $request->keterangan : '',
          'user' => 'Tambah-' . $request->username . ', ' . date('d-m-Y h:i:s'),
        ]);
        $opnameh->save($validate);
        //Create History
        $tanggal = date('Y-m-d');
        $datetime = date('Y-m-d H:i:s');
        $dokumen = $noopname;
        $form = 'Stock Opname';
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

  public function opnamesalinbarang(Request $request)
  {
    if ($request->Ajax()) {
      $id = $request->id;
      $opnameh = Opnameh::where('id', $id)->first();
      Opnamed::where('noopname', $opnameh->noopname)->delete();
      $username = 'Salin-' . session('username') . ', ' . date('d-m-Y h:i:s');
      $tbbarang = Tbbarang::select('*')->get();
      foreach ($tbbarang as $row) {
        $kdbarang = $row->kode;
        $nmbarang = $row->nama;
        //Insert Opnamed
        DB::table('opnamed')->insert(['noopname' => $opnameh->noopname, 'kdbarang' => $kdbarang, 'nmbarang' => $nmbarang, 'user' => $username]);
      }
      //Create History
      $opnameh = Opnameh::where('id', $request->id)->first();
      $tanggal = date('Y-m-d');
      $datetime = date('Y-m-d H:i:s');
      $dokumen = $opnameh->noopname;
      $form = 'Stock Opname';
      $status = 'Salin Barang';
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

  public function opnameproses(Request $request, opnameh $opnameproses)
  {
    if ($request->Ajax()) {
      $id = $request->id;
      $opnameh = Opnameh::where('id', $id)->first();
      $noopname = $opnameh->noopname;
      $opnameproses->load('opnamedetail');
      $opnameh = Opnameh::find($id);
      $opnameh->fill([
        'proses' => 'Y',
        'user' => 'Proses-' . session('username') . ', ' . date('d-m-Y h:i:s'),
      ]);
      $opnameh->save();
      //Update stock ke tabel barang
      $opnamed = Opnamed::where('noopname', $noopname)->get();
      foreach ($opnamed as $row) {
        $kdbarang = $row->kdbarang;
        $tbbarang = Tbbarang::where('kode', $kdbarang)->first();
        if (isset($tbbarang)) {
          $stock_sblm = $tbbarang->stock;
          DB::table('tbbarang')->where('kode', $kdbarang)->update(['stock' => $row->qty, 'stock_sblm' => $stock_sblm]);
        }
      }
      //Create History
      $opnameh = Opnameh::where('id', $request->id)->first();
      $tanggal = date('Y-m-d');
      $datetime = date('Y-m-d H:i:s');
      $dokumen = $opnameh->noopname;
      $form = 'Stock Opname';
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

  public function opnameunproses(Request $request, opnameh $opnameh)
  {
    if ($request->Ajax()) {
      $id = $request->id;
      $user = 'Proses-' . session('username') . ', ' . date('d-m-Y h:i:s');
      DB::table('opnameh')->where('id', $id)->update(['proses' => 'N', 'user' => $user]);
      //Create History
      $opnameh = Opnameh::where('id', $request->id)->first();
      $tanggal = date('Y-m-d');
      $datetime = date('Y-m-d H:i:s');
      $dokumen = $opnameh->noopname;
      $form = 'Stock Opname';
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

  public function opnamebatalproses(opnameh $opnameh, Request $request)
  {
    if ($request->Ajax()) {
      $id = $_GET['id'];
      $data = [
        'menu' => 'transaksi',
        'submenu' => 'opname',
        'submenu1' => 'ref_umum',
        'title' => 'Batal Proses Stock Opname',
      ];
      // var_dump($data);

      // return response()->json([
      //     'data' => $data,
      // ]);
      return response()->json([
        'body' => view('opname.modalbatalproses', [
          'opname' => Opnameh::where('id', $id)->first(),
          // 'action' => route('so.update', $soh->id),
          'action' => 'opnamebatalprosesok',
          'vdata' => $data,
        ])->render(),
        'data' => $data,
      ]);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function opnamebatalprosesok(Request $request, opnameh $opnameh)
  {
    if ($request->Ajax()) {
      $id = $request->id;
      $user = 'Proses-' . session('username') . ', ' . date('d-m-Y h:i:s');
      DB::table('opnameh')->where('id', $id)->update(['proses' => 'N', 'user' => $user]);
      $opnameh = Opnameh::where('id', $id)->first();
      //Update stock ke tabel barang
      $opnamed = Opnamed::where('noopname', $opnameh->noopname)->get();
      foreach ($opnamed as $row) {
        $tbbarang = Tbbarang::where('kode', $row->kdbarang)->first();
        if (isset($tbbarang)) {
          $stock_sblm = $tbbarang->stock_sblm;
          DB::table('tbbarang')->where('kode', $row->kdbarang)->update(['stock' => $stock_sblm]);
        }
      }
      //Create History
      $opnameh = Opnameh::where('id', $id)->first();
      $noopname = $opnameh->noopname;
      $tanggal = date('Y-m-d');
      $datetime = date('Y-m-d H:i:s');
      $dokumen = $noopname;
      $form = 'Stock Opname';
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

  public function opnamecancel(Request $request, opnameh $opnameh)
  {
    if ($request->Ajax()) {
      $id = $request->id;
      $user = 'Cancel-' . session('username') . ', ' . date('d-m-Y h:i:s');
      DB::table('opnameh')->where('id', $id)->update(['batal' => 'Y', 'user' => $user]);
      //Create History
      $opnameh = Opnameh::where('id', $request->id)->first();
      $tanggal = date('Y-m-d');
      $datetime = date('Y-m-d H:i:s');
      $dokumen = $opnameh->noopname;
      $form = 'Stock Opname';
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

  public function opnameambil(Request $request, opnameh $opnameh)
  {
    if ($request->Ajax()) {
      $id = $request->id;
      $user = 'Ambil-' . session('username') . ', ' . date('d-m-Y h:i:s');
      DB::table('opnameh')->where('id', $id)->update(['batal' => 'N', 'user' => $user]);
      //Create History
      $opnameh = Opnameh::where('id', $request->id)->first();
      $tanggal = date('Y-m-d');
      $datetime = date('Y-m-d H:i:s');
      $dokumen = $opnameh->noopname;
      $form = 'Stock Opname';
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

  public function destroy(opnameh $opnameh, Request $request)
  {
    if ($request->Ajax()) {
      $id = $request->id;
      $opnameh = Opnameh::where('id', $id)->first();
      $deleted = DB::table('opnameh')->where('id', $id)->delete();
      if ($deleted) {
        DB::table('opnamed')->where('noopname', $opnameh->noopname)->delete();
        //Create History
        $tanggal = date('Y-m-d');
        $datetime = date('Y-m-d H:i:s');
        $dokumen = $opnameh->noopname;
        $form = 'Stock Opname';
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

  public function opnameinputopnamed(opnameh $opnameh, Request $request)
  {
    if ($request->Ajax()) {
      $id = $_GET['id'];
      $username = session('username');
      $opnameh = Opnameh::where('id', $id)->first();
      $noopname = $opnameh->noopname;
      $data = [
        'menu' => 'transaksi',
        'submenu' => 'opname',
        'submenu1' => 'ref_umum',
        'title' => 'Detail Data Stock Opname',
      ];
      // var_dump($data);

      // return response()->json([
      //     'data' => $data,
      // ]);
      return response()->json([
        'body' => view('opname.modaldetail', [
          'userdtlmenu' => Userdtl::join('tbmodule', 'userdtl.cmodule', '=', 'tbmodule.cmodule')->where('userdtl.pakai', '1')->where('username', $username)->orderBy('userdtl.nurut')->get(),
          'userdtl' => Userdtl::where('cmodule', 'Stock Opname')->where('username', $username)->first(),
          'saplikasi' => Saplikasi::where('aktif', 'Y')->first(),
          'opname' => Opnameh::where('id', $id)->first(),
          'opnamed' => Opnamed::where('noopname', $noopname)->get(),
          // 'action' => route('opname.update', $opnameh->id),
          'action' => 'opnametambahdetail',
          'vdata' => $data,
        ])->render(),
        'data' => $data,
      ]);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function opnamedajax(Request $request) //: View
  {
    $noopname = $request->noopname;
    if ($request->ajax()) {
      $data = Opnamed::where('noopname', $noopname); //->orderBy('kode', 'asc');
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

  public function opnametambahdetail(Request $request, opnamedRequest $opnamedrequest, opnamed $opnamed)
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
        // $recopnamed = Opnamed::where('noopname', $request->noopnamed)->where('kdbarang', $request->kdbarang)->first();
        // if (isset($recopnamed->noopname)) {
        //   $msg = [
        //     'sukses' => 'Data gagal di tambah',
        //   ];
        // } else {
        $opnamed->fill([
          'noopname' => isset($request->noopnamed) ? $request->noopnamed : '',
          'kdbarang' => isset($request->kdbarang) ? $request->kdbarang : '',
          'nmbarang' => isset($request->nmbarang) ? $request->nmbarang : '',
          'lokasi' => isset($request->kdsatuan) ? $request->kdsatuan : '',
          'qty' => isset($request->qty) ? $request->qty : '',
          'user' => 'Tambah-' . $request->username . ', ' . date('d-m-Y h:i:s'),
        ]);
        $opnamed->save($validate);
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


  public function opnametotaldetail(opnameh $opnameh, Request $request)
  {
    if ($request->Ajax()) {
      $noopname = $request->noopname;
      $opnameh = Opnameh::where('noopname', $noopname)->first();
      $data = [
        'menu' => 'transaksi',
        'submenu' => 'opname',
        'submenu1' => 'ref_umum',
        'title' => 'Detail Data Stock Opname',
      ];
      // var_dump($data);

      // return response()->json([
      //     'data' => $data,
      // ]);
      return response()->json([
        'body' => view('opname.totaldetail', [
          'qtyopnamed' => Opnamed::where('noopname', $noopname)->sum('qty'),
          'vdata' => $data,
        ])->render(),
        'data' => $data,
      ]);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function opnamecetak(Request $request)
  {
    $row = Opnameh::where('id', $request->id)->first();
    $noopname = $row->noopname;
    $data = [
      'opnameh' => $row,
      'opnamed' => Opnameh::join('opnamed', 'opnamed.noopname', '=', 'opnameh.noopname')
        ->where('opnameh.noopname', $noopname)->get(),
    ];
    // return view('opname.cetak', $data);

    $rowd = Opnamed::where('noopname', $noopname)->get();
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
    $opnameh = Opnameh::where('id', $request->id)->first();
    $tanggal = date('Y-m-d');
    $datetime = date('Y-m-d H:i:s');
    $dokumen = $opnameh->noopname;
    $form = 'Stock Opname';
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
    $mpdf->WriteHTML(view('opname.cetak', $data));
    $namafile = $noopname . ' - ' . date('dmY H:i:s') . '.pdf';
    //return the PDF for download
    // return $mpdf->Output($request->get('name') . $namafile, Destination::DOWNLOAD);
    $mpdf->Output($namafile, 'I');
    exit;
  }
}
