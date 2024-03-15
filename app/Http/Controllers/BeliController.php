<?php

namespace App\Http\Controllers;

use Mpdf\Mpdf;
use Mpdf\Output\Destination;
use App\Http\Requests\BelihRequest;
use App\Http\Requests\BelidRequest;
use Illuminate\Http\Request;
use Session;
// use Yajra\DataTables\Contracts\DataTables;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use App\Models\Belih;
use App\Models\Belid;
use App\Models\Poh;
use App\Models\Pod;
use App\Models\Tbbarang;
use App\Models\Tbgudang;
use App\Models\Userdtl;
use App\Models\Saplikasi;

// //return type View
// use Illuminate\View\View;

class BeliController extends Controller
{
  public function index(Request $request) //: View
  {
    $username = session('username');
    $data = [
      'menu' => 'transaksi',
      'submenu' => 'beli',
      'submenu1' => 'ref_umum',
      'title' => 'Penerimaan Pembelian',
      // 'Belih' => Belih::all(),
      'userdtlmenu' => Userdtl::join('tbmodule', 'userdtl.cmodule', '=', 'tbmodule.cmodule')->where('userdtl.pakai', '1')->where('username', $username)->orderBy('userdtl.nurut')->get(),
      'userdtl' => Userdtl::where('cmodule', 'Penerimaan Pembelian')->where('username', $username)->first(),
    ];
    $userdtl = Userdtl::where('cmodule', 'Penerimaan Pembelian')->where('username', $username)->first();
    if ($userdtl->pakai == '1') {
      return view('beli.index')->with($data);
    } else {
      return redirect('home');
    }
  }
  public function beliajax(Request $request) //: View
  {
    if ($request->ajax()) {
      $data = Belih::select('*'); //->orderBy('kode', 'asc');
      return Datatables::of($data)
        ->addIndexColumn()
        ->addColumn('kode1', function ($row) {
          $id = $row['id'];
          $btn = $row['kode']; //'<a href="#"' . 'onclick=detail(' . $id . ')>' .  $row['kode'] . '</a>';
          return $btn;
        })
        ->rawColumns(['kode1'])
        ->make(true);
      return view('beli');
    }
  }

  public function create(Request $request)
  {
    if ($request->Ajax()) {
      $username = session('username');
      $data = [
        'menu' => 'transaksi',
        'submenu' => 'beli',
        'submenu1' => 'ref_umum',
        'title' => 'Tambah Data Pembelian',
      ];
      return response()->json([
        'body' => view('beli.modaltambahmaster', [
          'saplikasi' => Saplikasi::where('aktif', 'Y')->first(),
          'beli' => new Belih(),
          'action' => route('beli.store'),
          'vdata' => $data,
        ])->render(),
        'data' => $data,

      ]);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function store(BelihRequest $request, Belih $belih)
  // public function store(Request $request, Belih $belih)
  {
    if ($request->Ajax()) {
      $sort_num = 0;
      $new_code = $request->nobeli;
      $ketemu = 0;
      $record = 0;
      $rec = Belih::where('nobeli', $new_code)->first();
      if ($rec == null) {
        $aplikasi = Saplikasi::where('aktif', 'Y')->first();
        $sort_num = $aplikasi->nobeli;
        $tahun = $aplikasi->tahun;
        $bulan = $aplikasi->bulan;
        Saplikasi::where('aktif', 'Y')->update(['nobeli' => $sort_num + 1]);
      } else {
        while ($ketemu == $record) { //0=0
          $aplikasi = Saplikasi::where('aktif', 'Y')->first();
          $sort_num = $aplikasi->nobeli;
          $tahun = $aplikasi->tahun;
          $bulan = $aplikasi->bulan;
          Saplikasi::where('aktif', 'Y')->update(['nobeli' => $sort_num + 1]);
          $new_code = 'BE' . $tahun . sprintf('%02s', $bulan) . sprintf("%05s", $sort_num + 1);
          $rec = Belih::where('nobeli', $new_code)->first();
          if ($rec == null) {
            $record = 0;
            Saplikasi::where('aktif', 'Y')->update(['nobeli' => $sort_num + 1]);
            break;
          } else {
            Saplikasi::where('aktif', 'Y')->update(['nobeli' => $sort_num + 1]);
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
        $belih->fill([
          'nobeli' => isset($request->nobeli) ? $new_code : '',
          'tglbeli' => isset($request->tglbeli) ? $request->tglbeli : '',
          'nofaktur' => isset($request->nofaktur) ? $request->nofaktur : '',
          'noinvoice' => isset($request->noinvoice) ? $request->noinvoice : '',
          'tgl_invoice' => isset($request->tgl_invoice) ? $request->tgl_invoice : '',
          'penerima' => isset($request->penerima) ? $request->penerima : '',
          'kdsupplier' => isset($request->kdsupplier) ? $request->kdsupplier : '',
          'nmsupplier' => isset($request->nmsupplier) ? $request->nmsupplier : '',
          'kdgudang' => isset($request->kdgudang) ? $request->kdgudang : '',
          'nmgudang' => isset($request->nmgudang) ? $request->nmgudang : '',
          'jenis_order' => isset($request->jenis_order) ? $request->jenis_order : '',
          'carabayar' => isset($request->carabayar) ? $request->carabayar : '',
          'tglkirim' => isset($request->tglkirim) ? $request->tglkirim : '',
          'tempo' => isset($request->tempo) ? $request->tempo : '',
          'tgl_jt_tempo' => isset($request->tgl_jt_tempo) ? $request->tgl_jt_tempo : '',
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
        $belih->save($validated);
        //Create History
        $tanggal = date('Y-m-d');
        $datetime = date('Y-m-d H:i:s');
        $dokumen = $new_code;
        $form = 'Penerimaan Pembelian';
        $status = 'Tambah';
        $catatan = isset($request->catatan) ? $request->catatan : '';
        $username = session('username');
        DB::table('hisuser')->insert(['tanggal' => $tanggal, 'dokumen' => $dokumen, 'form' => $form, 'status' => $status, 'user' => $username, 'catatan' => $catatan, 'datetime' => $datetime]);
        $msg = [
          'sukses' => 'Data berhasil di tambah',
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
        'submenu' => 'beli',
        'submenu1' => 'ref_umum',
        'title' => 'Detail Pembelian',
        // 'userdtl' => Userdtl::where('cmodule', 'Penerimaan Pembelian')->where('username', $username)->first(),
      ];
      return response()->json([
        'body' => view('beli.modaltambahmaster', [
          'saplikasi' => Saplikasi::where('aktif', 'Y')->first(),
          'beli' => Belih::where('id', $id)->first(),
          'action' => route('tbbarang.store'),
          'vdata' => $data,
        ])->render(),
        'data' => $data,
      ]);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function edit(Belih $belih, Request $request)
  {
    if ($request->Ajax()) {
      $id = $_GET['id'];
      $username = session('username');
      $data = [
        'menu' => 'transaksi',
        'submenu' => 'beli',
        'submenu1' => 'ref_umum',
        'title' => 'Edit Data Pembelian',
      ];
      // var_dump($data);

      // return response()->json([
      //     'data' => $data,
      // ]);
      return response()->json([
        'body' => view('beli.modaltambahmaster', [
          'saplikasi' => Saplikasi::where('aktif', 'Y')->first(),
          'beli' => Belih::where('id', $id)->first(),
          // 'action' => route('beli.update', $belih->id),
          'action' => 'beliupdate',
          'vdata' => $data,
        ])->render(),
        'data' => $data,
      ]);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function update(Request $request, Belih $belih)
  {
    if ($request->Ajax()) {
      $id = $request->id;
      if ($request->nobeli === $request->nobelilama) {
        $validate = $request->validate(
          [
            'nobeli' => 'required',
            'tglbeli' => 'required',
          ],
          [
            'nobeli.required' => 'No. SO harus di isi',
            'tglbeli.required' => 'Tanggal SO harus di isi',
          ],
        );
      } else {
        $validate = $request->validate(
          [
            'nobeli' => 'required|unique:Belih|max:255',
            'tglbeli' => 'required',
          ],
          [
            'nobeli.required' => 'No. SO harus di isi',
            'tglbeli.required' => 'Tanggal SO harus di isi',
          ],
        );
      }
      $belih = Belih::find($id);
      if ($validate) {
        $nobeli = $request->nobeli;
        // $subtotal = DB::table('belid')->where('nobeli', $nobeli)->sum('subtotal');
        $subtotal = Belid::where('nobeli', $nobeli)->sum('subtotal');
        $biaya_lain = isset($request->biaya_lain) ? $request->biaya_lain : '0';
        $materai = isset($request->materai) ? $request->materai : '0';
        $ppn = isset($request->ppn) ? $request->ppn : '0';
        $total_sementara = $biaya_lain + $subtotal + $materai;
        $total = $total_sementara + ($total_sementara * ($ppn / 100));
        $belih->fill([
          'nobeli' => isset($request->nobeli) ? $request->nobeli : '',
          'tglbeli' => isset($request->tglbeli) ? $request->tglbeli : '',
          'nofaktur' => isset($request->nofaktur) ? $request->nofaktur : '',
          'noinvoice' => isset($request->noinvoice) ? $request->noinvoice : '',
          'tgl_invoice' => isset($request->tgl_invoice) ? $request->tgl_invoice : '',
          'penerima' => isset($request->penerima) ? $request->penerima : '',
          'kdsupplier' => isset($request->kdsupplier) ? $request->kdsupplier : '',
          'nmsupplier' => isset($request->nmsupplier) ? $request->nmsupplier : '',
          'kdgudang' => isset($request->kdgudang) ? $request->kdgudang : '',
          'nmgudang' => isset($request->nmgudang) ? $request->nmgudang : '',
          'jenis_order' => isset($request->jenis_order) ? $request->jenis_order : '',
          'carabayar' => isset($request->carabayar) ? $request->carabayar : '',
          'tglkirim' => isset($request->tglkirim) ? $request->tglkirim : '',
          'tempo' => isset($request->tempo) ? $request->tempo : '',
          'tgl_jt_tempo' => isset($request->tgl_jt_tempo) ? $request->tgl_jt_tempo : '',
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
        $belih->save($validate);
        //Create History
        $tanggal = date('Y-m-d');
        $datetime = date('Y-m-d H:i:s');
        $dokumen = $nobeli;
        $form = 'Penerimaan Pembelian';
        $status = 'Update';
        $catatan = isset($request->catatan) ? $request->catatan : '';
        $username = session('username');
        DB::table('hisuser')->insert(['tanggal' => $tanggal, 'dokumen' => $dokumen, 'form' => $form, 'status' => $status, 'user' => $username, 'catatan' => $catatan, 'datetime' => $datetime]);
        $msg = [
          'sukses' => 'Data berhasil di update',
        ];
      } else {
        $msg = [
          'sukses' => 'Data gagal di update',
        ];
      }
      echo json_encode($msg);
      // return redirect()->back()->with('message', 'Berhasil di update');
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function beliproses(Request $request, Belih $beliproses)
  {
    if ($request->Ajax()) {
      $id = $request->id;
      $belih = Belih::where('id', $id)->first();
      $nobeli = $belih->nobeli;

      // di remark permintaan user, 1 pembelian bisa beberapa PO
      // //Cek double detail
      // $belidgroup = Belid::where('nobeli', $belih->nobeli)->groupBy('kdbarang')->get();
      // // dd($belidgroup);
      // foreach ($belidgroup as $row) {
      //   $cekdouble = Belid::where('nobeli', $belih->nobeli)->where('kdbarang', $row->kdbarang)->count();
      //   if ($cekdouble > 1) {
      //     return response()->json([
      //       'sukses' => false,
      //     ]);
      //     exit('Double barang');
      //   }
      // }
      $lanjut = true;
      $tahun = date("Y", strtotime($belih->tglbeli));
      $bulan = substr('0' . date("m", strtotime($belih->tglbeli)), -2);
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
        if ($belih->total == 0) {
          return response()->json([
            'sukses' => false,
          ]);
        } else {
          $beliproses->load('beliDetail');
          $subtotal = $beliproses->belidetail->sum('subtotal');
          $subtotal = Belih::join('belid', 'belih.nobeli', '=', 'belid.nobeli')->where('belid.nobeli', $nobeli)->sum('belid.subtotal');
          // $subtotal = DB::table('belid')->where('nobeli', $nobeli)->sum('subtotal');
          $total_sementara = $beliproses->biaya_lain + $subtotal;
          $total = $total_sementara + ($total_sementara * ($belih->ppn / 100));
          $belih = Belih::find($id);
          $tglbeli = $belih->tglbeli;
          $belih->fill([
            'proses' => 'Y',
            'subtotal' => $subtotal,
            'total_sementara' => $total_sementara,
            'total' => $total,
            'kurangbayar' => $total,
            'sudahbayar' => 0,
            'user' => 'Proses-' . session('username') . ', ' . date('d-m-Y h:i:s'),
          ]);
          $belih->save();
          $nopo = "";
          $belid = Belid::where('nobeli', $nobeli)->get();
          foreach ($belid as $row) {
            $idd = $row->id;
            $qty = $row->qty;
            $harga = $row->harga;
            // DB::table('belid')->where('id', $idd)->update(['proses' => 'Y']);
            Belid::where('id', $idd)->update(['proses' => 'Y', 'tglbeli' => $tglbeli]);
            if ($row->nopo <> "") {
              $nopo = $row->nopo;
              $kdbarang = $row->kdbarang;
              $pod = Pod::where('nopo', $nopo)->where('kdbarang', $kdbarang)->first();
              $terima = $pod->terima + $qty;
              $kurang = $pod->kurang - $qty;
              // DB::table('pod')->where('nopo', $nopo)->where('kdbarang', $kdbarang)->update(['terima' =>  $terima, 'kurang' =>  $kurang]);
              Pod::where('nopo', $nopo)->where('kdbarang', $kdbarang)->update(['terima' =>  $terima, 'kurang' =>  $kurang]);
            }
            //Update stock
            $tbbarang = Tbbarang::where('kode', $row->kdbarang)->first();
            $stockakhir = $tbbarang->stock + $qty;
            // DB::table('tbbarang')->where('kode', $row->kdbarang)->update(['stock' => $stockakhir]);
            Tbbarang::where('kode', $row->kdbarang)->update(['stock' => $stockakhir]);
            // Update HPP
            $hpptbbarang = $tbbarang->hpp;
            if ($hpptbbarang < 0) {
              $hpptbbarang = 0;
            }
            $stocktbbarang = $tbbarang->stock;
            if ($stocktbbarang < 0) {
              $stocktbbarang = 0;
            }

            $jumstock = $stocktbbarang + $qty;
            $harga_beli_lama  = $tbbarang->harga_beli_lama;
            if ($harga_beli_lama = 0) {
              $harga_beli_lama = $harga;
            } else {
              $harga_beli_lama = $tbbarang->harga_beli;
            }
            $totalharga = ($hpptbbarang * $stocktbbarang) + ($harga * $qty);
            // dd($totalharga . '   ' . $jumstock . '   ' . ($totalharga / $jumstock));
            $hpp = $totalharga / ($jumstock > 0 ? $jumstock : 1);
            // dd($hpp);
            // if ($stocktbbarang > 0) {
            //   DB::table('tbbarang')->where('kode', $row->kdbarang)->update([
            //     'hpp_lama' => $hpptbbarang, 'harga_beli_lama' => $harga_beli_lama, 'hpp' => $hpp, 'harga_beli' => $harga
            //   ]);
            // } else {
            //   DB::table('tbbarang')->where('kode', $row->kdbarang)->update([
            //     'hpp_lama' => '0', 'hpp' => $hpp, 'harga_beli' => $harga
            //   ]);
            // }
            // DB::table('belid')->where('id', $idd)->update(['proses' => 'Y', 'hpp' => $hpp]);
            if ($stocktbbarang > 0) {
              Tbbarang::where('kode', $row->kdbarang)->update([
                'hpp_lama' => $hpptbbarang, 'harga_beli_lama' => $harga_beli_lama, 'hpp' => $hpp, 'harga_beli' => $harga
              ]);
            } else {
              Tbbarang::where('kode', $row->kdbarang)->update([
                'hpp_lama' => '0', 'hpp' => $hpp, 'harga_beli' => $harga
              ]);
            }
            Belid::where('id', $idd)->update(['proses' => 'Y', 'hpp' => $hpp]);
          }
          if ($nopo <> "") {
            Poh::where('nopo', $nopo)->update(['terima' => 'Y']);
          }
          //Create History
          $belih = Belih::where('id', $request->id)->first();
          $tanggal = date('Y-m-d');
          $datetime = date('Y-m-d H:i:s');
          $dokumen = $belih->nobeli;
          $form = 'Penerimaan Pembelian';
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
      exit('Maaf tidak dapat diproses');
    }
  }

  public function beliunproses(Request $request, Belih $belih)
  {
    if ($request->Ajax()) {
      $id = $request->id;
      $user = 'Proses-' . session('username') . ', ' . date('d-m-Y h:i:s');
      // DB::table('belih')->where('id', $id)->update(['proses' => 'N', 'user' => $user]);
      Belih::where('id', $id)->update(['proses' => 'N', 'user' => $user]);
      //Create History
      $belih = Belih::where('id', $id)->first();
      $tanggal = date('Y-m-d');
      $datetime = date('Y-m-d H:i:s');
      $dokumen = $belih->nobeli;
      $form = 'Penerimaan Pembelian';
      $status = 'Batal Proses';
      $catatan = isset($request->catatan) ? $request->catatan : '';
      $username = session('username');
      DB::table('hisuser')->insert(['tanggal' => $tanggal, 'dokumen' => $dokumen, 'form' => $form, 'status' => $status, 'user' => $username, 'catatan' => $catatan, 'datetime' => $datetime]);
      //Create History
      $belih = Belih::where('id', $request->id)->first();
      $tanggal = date('Y-m-d');
      $datetime = date('Y-m-d H:i:s');
      $dokumen = $belih->nobeli;
      $form = 'Penerimaan Pembelian';
      $status = 'Batal Proses';
      $catatan = isset($request->catatan) ? $request->catatan : '';
      $username = session('username');
      DB::table('hisuser')->insert(['tanggal' => $tanggal, 'dokumen' => $dokumen, 'form' => $form, 'status' => $status, 'user' => $username, 'catatan' => $catatan, 'datetime' => $datetime]);
      $msg = [
        'sukses' => 'Data berhasil di Cancel',
      ];
      echo json_encode($msg);
      // return redirect()->back()->with('message', 'Berhasil di update');
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function belibatalproses(Belih $belih, Request $request)
  {
    if ($request->Ajax()) {
      $id = $_GET['id'];
      $belih = Belih::where('id', $id)->first();
      if ($belih->sudahbayar > 0) {
        return response()->json('2');
      }
      $bulan = substr('0' . date('m', strtotime($belih->tglbeli)), -2);
      $tahun = date('Y', strtotime($belih->tglbeli));
      $periode = $tahun . $bulan;
      $saplikasi = Saplikasi::where('aktif', 'Y')->first();
      // dd($saplikasi->closing_hpp . '   ' . $periode);
      if ($periode <= $saplikasi->closing_hpp) {
        return response()->json('1');
      }
      $data = [
        'menu' => 'transaksi',
        'submenu' => 'beli',
        'submenu1' => 'ref_umum',
        'title' => 'Batal Proses Pembelian',
      ];
      // var_dump($data);

      // return response()->json([
      //     'data' => $data,
      // ]);
      return response()->json([
        'body' => view('beli.modalbatalproses', [
          'beli' => Belih::where('id', $id)->first(),
          // 'action' => route('so.update', $soh->id),
          'action' => 'belibatalprosesok',
          'vdata' => $data,
        ])->render(),
        'data' => $data,
      ]);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function belibatalprosesok(Request $request, Belih $belih)
  {
    if ($request->Ajax()) {
      $id = $request->id;
      $user = 'Proses-' . session('username') . ', ' . date('d-m-Y h:i:s');
      // DB::table('belih')->where('id', $id)->update(['proses' => 'N', 'user' => $user]);
      Belih::where('id', $id)->update(['proses' => 'N', 'user' => $user]);
      $belih = Belih::find($id);
      $nopo = "";
      $belid = Belid::where('nobeli', $belih->nobeli)->get();
      foreach ($belid as $row) {
        $idd = $row->id;
        $qty = $row->qty;
        if ($row->nopo <> "") {
          $nopo = $row->nopo;
          $kdbarang = $row->kdbarang;
          $pod = Pod::where('nopo', $nopo)->where('kdbarang', $kdbarang)->first();
          $terima = $pod->terima - $qty;
          $kurang = $pod->kurang + $qty;
          // DB::table('pod')->where('nopo', $nopo)->where('kdbarang', $kdbarang)->update(['terima' =>  $terima, 'kurang' =>  $kurang]);
          Pod::where('nopo', $nopo)->where('kdbarang', $kdbarang)->update(['terima' =>  $terima, 'kurang' =>  $kurang]);
        }
        //Update stock
        $tbbarang = Tbbarang::where('kode', $row->kdbarang)->first();
        $stockakhir = $tbbarang->stock - $qty;
        // DB::table('tbbarang')->where('kode', $row->kdbarang)->update(['stock' => $stockakhir]);
        Tbbarang::where('kode', $row->kdbarang)->update(['stock' => $stockakhir]);
        $hpp_lama = $tbbarang->hpp_lama;
        $harga_beli_lama = $tbbarang->harga_beli_lama;
        if ($harga_beli_lama = 0) {
          $harga_beli_lama = $tbbarang->harga_beli;
        }
        if ($tbbarang->stock > 0) {
          $harga_beli_lama = $tbbarang->harga_beli;
        } else {
          $harga_beli_lama = $tbbarang->harga_beli;
        }
        if ($hpp_lama == 0) {
          $hpp_lama = $harga_beli_lama;
        }
        // DB::table('tbbarang')->where('kode', $row->kdbarang)->update([
        //   'hpp_lama' => $hpp_lama, 'harga_beli_lama' => $harga_beli_lama, 'hpp' => $hpp_lama, 'harga_beli' => $harga_beli_lama
        // ]);
        // DB::table('belid')->where('id', $idd)->update(['proses' => 'N']);
        Tbbarang::where('kode', $row->kdbarang)->update([
          'hpp_lama' => $hpp_lama, 'harga_beli_lama' => $harga_beli_lama, 'hpp' => $hpp_lama, 'harga_beli' => $harga_beli_lama
        ]);
        Belid::where('id', $idd)->update(['proses' => 'N']);
      }
      if ($nopo <> "") {
        $terima = Pod::where('nopo', $nopo)->sum('terima');
        if ($terima > 0) {
          Poh::where('nopo', $nopo)->update(['terima' => 'Y']);
        } else {
          Poh::where('nopo', $nopo)->update(['terima' => 'N']);
        }
      }
      //Create History
      $belih = Belih::where('id', $id)->first();
      $nobeli = $belih->nobeli;
      $tanggal = date('Y-m-d');
      $datetime = date('Y-m-d H:i:s');
      $dokumen = $nobeli;
      $form = 'Penerimaan Pembelian';
      $status = 'Batal Proses';
      $catatan = isset($request->catatan) ? $request->catatan : '';
      $username = session('username');
      DB::table('hisuser')->insert(['tanggal' => $tanggal, 'dokumen' => $dokumen, 'form' => $form, 'status' => $status, 'user' => $username, 'catatan' => $catatan, 'datetime' => $datetime]);
      $msg = [
        'sukses' => 'Data berhasil di Cancel',
      ];
      echo json_encode($msg);
      // return redirect()->back()->with('message', 'Berhasil di update');
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function belicancel(Request $request, Belih $belih)
  {
    if ($request->Ajax()) {
      $id = $request->id;
      $user = 'Cancel-' . session('username') . ', ' . date('d-m-Y h:i:s');
      // DB::table('belih')->where('id', $id)->update(['batal' => 'Y', 'user' => $user]);
      Belih::where('id', $id)->update(['batal' => 'Y', 'user' => $user]);
      //Create History
      $belih = Belih::where('id', $request->id)->first();
      $tanggal = date('Y-m-d');
      $datetime = date('Y-m-d H:i:s');
      $dokumen = $belih->nobeli;
      $form = 'Penerimaan Pembelian';
      $status = 'Cancel';
      $catatan = isset($request->catatan) ? $request->catatan : '';
      $username = session('username');
      DB::table('hisuser')->insert(['tanggal' => $tanggal, 'dokumen' => $dokumen, 'form' => $form, 'status' => $status, 'user' => $username, 'catatan' => $catatan, 'datetime' => $datetime]);
      $msg = [
        'sukses' => 'Data berhasil di Cancel',
      ];
      echo json_encode($msg);
      // return redirect()->back()->with('message', 'Berhasil di update');
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function beliambil(Request $request, Belih $belih)
  {
    if ($request->Ajax()) {
      $id = $request->id;
      $user = 'Ambil-' . session('username') . ', ' . date('d-m-Y h:i:s');
      // DB::table('belih')->where('id', $id)->update(['batal' => 'N', 'user' => $user]);
      Belih::where('id', $id)->update(['batal' => 'N', 'user' => $user]);
      //Create History
      $belih = Belih::where('id', $request->id)->first();
      $tanggal = date('Y-m-d');
      $datetime = date('Y-m-d H:i:s');
      $dokumen = $belih->nobeli;
      $form = 'Penerimaan Pembelian';
      $status = 'Ambil';
      $catatan = isset($request->catatan) ? $request->catatan : '';
      $username = session('username');
      DB::table('hisuser')->insert(['tanggal' => $tanggal, 'dokumen' => $dokumen, 'form' => $form, 'status' => $status, 'user' => $username, 'catatan' => $catatan, 'datetime' => $datetime]);
      $msg = [
        'sukses' => 'Data berhasil di Cancel',
      ];
      echo json_encode($msg);
      // return redirect()->back()->with('message', 'Berhasil di update');
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function destroy(Belih $belih, Request $request)
  {
    if ($request->Ajax()) {
      $id = $request->id;
      $belih = Belih::where('id', $id)->first();
      // $deleted = DB::table('belih')->where('id', $id)->delete();
      $deleted = Belih::where('id', $id)->delete();
      if ($deleted) {
        // DB::table('belid')->where('nobeli', $belih->nobeli)->delete();
        Belid::where('nobeli', $belih->nobeli)->delete();
        //Create History
        $tanggal = date('Y-m-d');
        $datetime = date('Y-m-d H:i:s');
        $dokumen = $belih->nobeli;
        $form = 'Penerimaan Pembelian';
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

  public function carigudang(Request $request)
  {
    if ($request->Ajax()) {
      $data = [
        'title' => 'Cari Tabel Gudang',
      ];
      // var_dump($data);
      return response()->json([
        'body' => view('modalcari.modalcarigudang', [
          'tbgudang' => Tbgudang::orderBy('nama', 'asc')->get(),
          'vdata' => $data,
        ])->render(),
        'data' => $data,
      ]);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function replgudang(Request $request)
  {
    if ($request->Ajax()) {
      $kode = $request->kode; //$_GET['kode_barang'];
      $row = Tbgudang::where('kode', $kode)->first();
      if (isset($row)) {
        $data = [
          'kdgudang' => $row['kode'],
          'nmgudang' => $row['nama'],
        ];
      } else {
        $data = [
          'kdgudang' => '',
          'nmgudang' => '',
        ];
      }
      echo json_encode($data);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function caripo(Request $request)
  {
    if ($request->Ajax()) {
      $data = [
        'title' => 'Cari Purchase Order',
      ];
      // var_dump($data);
      return response()->json([
        'body' => view('modalcari.modalcaripo', [
          // 'poh' => Poh::where('proses', 'Y')->orderBy('nopo', 'desc')->get(),
          'poh' => Poh::join('pod', 'pod.nopo', '=', 'poh.nopo')->where('poh.proses', 'Y')->where('pod.kurang', '>', 0)
            ->groupBy('poh.nopo')->orderBy('poh.nopo', 'desc')->get(),
          'vdata' => $data,
        ])->render(),
        'data' => $data,
      ]);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function prosessalinpo(Request $request)
  {
    if ($request->Ajax()) {
      $insertdetail = true;
      $nopo = $request->nopo;
      $nobeli = $request->nobeli;
      $tglbeli = $request->tglbeli;
      $user = 'Salin-' . session('username') . ', ' . date('d-m-Y h:i:s');
      // DB::table('belid')->Where('nobeli', $nobeli)->Where('nopo', $nopo)->delete();
      $delete = Belid::where('nobeli', $nobeli)->where('nopo', $nopo)->delete();
      if ($delete >= 0) {
        $pod = Pod::Where('nopo', $nopo)->Get();
        // dd($pod);
        foreach ($pod as $row) {
          // $insert = DB::table('belid')->Insert([
          //   'nobeli' => $nobeli, 'tglbeli' => $tglbeli, 'kdbarang' => $row->kdbarang, 'nmbarang' => $row->nmbarang,
          //   'kdsatuan' => $row->kdsatuan, 'qty' => $row->qty, 'harga' => $row->harga, 'discount' => $row->discount, 'subtotal' => $row->subtotal, 'user' => $user,
          //   'nopo' => $row->nopo
          // ]);
          $insert = Belid::insert([
            'nobeli' => $nobeli, 'tglbeli' => $tglbeli, 'kdbarang' => $row->kdbarang, 'nmbarang' => $row->nmbarang,
            'kdsatuan' => $row->kdsatuan, 'qty' => $row->qty, 'harga' => $row->harga, 'discount' => $row->discount,
            'subtotal' => $row->subtotal, 'nopo' => $row->nopo, 'user' => $user,
          ]);
          if ($insert <> true) {
            $insertdetail = false;
          }
        }
      }
      // $subtotal = DB::table('belid')->where('nobeli', $nobeli)->sum('subtotal');
      $subtotal = Belid::where('nobeli', $nobeli)->sum('subtotal');
      $belih = Belih::where('nobeli', $nobeli)->first();
      $biaya_lain = isset($belih->biaya_lain) ? $belih->biaya_lain : '0';
      $materai = isset($belih->materai) ? $belih->materai : '0';
      $ppn = isset($belih->ppn) ? $belih->ppn : '0';
      $total_sementara = $biaya_lain + $subtotal + $materai;
      $total = $total_sementara + ($total_sementara * ($ppn / 100));
      // $update = DB::table('belih')->where('nobeli', $nobeli)->update(['biaya_lain' => $biaya_lain, 'subtotal' => $subtotal, 'total_sementara' => $total_sementara, 'ppn' => $ppn, 'total' => $total]);
      $update = Belih::where('nobeli', $nobeli)->update(['biaya_lain' => $biaya_lain, 'subtotal' => $subtotal, 'total_sementara' => $total_sementara, 'ppn' => $ppn, 'total' => $total]);
      if ($update >= 0 and $insertdetail) {
        $msg = [
          'sukses' => true,
        ];
      } else {
        $msg = [
          'sukses' => false,
        ];
      }
      echo json_encode($msg);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function beliinputbelid(Belih $belih, Request $request)
  {
    if ($request->Ajax()) {
      $id = $_GET['id'];
      $username = session('username');
      $belih = Belih::where('id', $id)->first();
      $nobeli = $belih->nobeli;
      $data = [
        'menu' => 'transaksi',
        'submenu' => 'beli',
        'submenu1' => 'ref_umum',
        'title' => 'Detail Data Pembelian',
      ];
      // var_dump($data);

      // return response()->json([
      //     'data' => $data,
      // ]);
      return response()->json([
        'body' => view('beli.modaldetail', [
          'userdtlmenu' => Userdtl::join('tbmodule', 'userdtl.cmodule', '=', 'tbmodule.cmodule')->where('userdtl.pakai', '1')->where('username', $username)->orderBy('userdtl.nurut')->get(),
          'userdtl' => Userdtl::where('cmodule', 'Penerimaan Pembelian')->where('username', $username)->first(),
          'saplikasi' => Saplikasi::where('aktif', 'Y')->first(),
          'beli' => Belih::where('id', $id)->first(),
          'belid' => Belid::where('nobeli', $nobeli)->get(),
          // 'action' => route('beli.update', $belih->id),
          'action' => 'belitambahdetail',
          'vdata' => $data,
        ])->render(),
        'data' => $data,
      ]);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function belidajax(Request $request) //: View
  {
    $nobeli = $request->nobeli;
    if ($request->ajax()) {
      // $data = Belid::where('nobeli', $nobeli); //->orderBy('kode', 'asc');
      $data = Belid::leftjoin('tbsatuan', 'tbsatuan.kode', '=', 'belid.kdsatuan')
        ->select('belid.*', 'tbsatuan.nama as nmsatuan')
        ->where('nobeli', $nobeli); //->orderBy('kode', 'asc');
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

  public function belitambahdetail(Request $request, BelidRequest $Belidrequest, Belid $Belid)
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
        // $recBelid = Belid::where('nobeli', $request->noBelid)->where('kdbarang', $request->kdbarang)->first();
        // if (isset($recBelid->nobeli)) {
        //   $msg = [
        //     'sukses' => 'Data gagal di tambah',
        //   ];
        // } else {
        $Belid->fill([
          'nobeli' => isset($request->noBelid) ? $request->noBelid : '',
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
        $Belid->save($validate);
        $belih = Belih::where('nobeli', $request->noBelid)->first();
        $biaya_lain = $belih->biaya_lain;
        $materai = $belih->materai;
        $ppn = $belih->ppn;
        // $subtotal = DB::table('belid')->where('nobeli', $request->noBelid)->sum('subtotal');
        $subtotal = Belid::where('nobeli', $request->noBelid)->sum('subtotal');
        $total_sementara = $biaya_lain + $subtotal + $materai;
        $total = $total_sementara + ($total_sementara * ($ppn / 100));
        // DB::table('belih')->where('nobeli', $request->noBelid)->update([
        //   'subtotal' => $subtotal, 'biaya_lain' => $biaya_lain, 'materai' => $materai, 'total_sementara' => $total_sementara, 'total_sementara' =>
        //   $total_sementara, 'total' => $total
        // ]);
        Belih::where('nobeli', $request->noBelid)->update([
          'subtotal' => $subtotal, 'biaya_lain' => $biaya_lain, 'materai' => $materai, 'total_sementara' => $total_sementara, 'total_sementara' =>
          $total_sementara, 'total' => $total
        ]);
        $msg = [
          'sukses' => 'Data berhasil di tambah',
        ];
        // }
      } else {
        $msg = [
          'sukses' => 'Data gagal di tambah',
        ];
      }
      echo json_encode($msg);
      // return redirect()->back()->with('message', 'Berhasil di update');
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }


  public function belitotaldetail(Belih $belih, Request $request)
  {
    if ($request->Ajax()) {
      $nobeli = $request->nobeli;
      $belih = Belih::where('nobeli', $nobeli)->first();
      $data = [
        'menu' => 'transaksi',
        'submenu' => 'Beli',
        'submenu1' => 'ref_umum',
        'title' => 'Detail Data Pembelian',
      ];
      // var_dump($data);

      // return response()->json([
      //     'data' => $data,
      // ]);
      return response()->json([
        'body' => view('beli.totaldetail', [
          'subtotalbelid' => Belid::where('nobeli', $nobeli)->sum('subtotal'),
          'qtybelid' => Belid::where('nobeli', $nobeli)->sum('qty'),
          'vdata' => $data,
        ])->render(),
        'data' => $data,
      ]);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function belicetak(Request $request)
  {
    $rowbelih = Belih::join('tbsupplier', 'belih.kdsupplier', '=', 'tbsupplier.kode')->where('belih.id', $request->id)->first();
    $nobeli = $rowbelih->nobeli;
    $rowbelid = Belih::join('belid', 'belid.nobeli', '=', 'belih.nobeli')
      ->leftjoin('tbsatuan', 'tbsatuan.kode', '=', 'belid.kdsatuan')
      ->select('belih.nobeli', 'belid.*', 'tbsatuan.nama as nmsatuan')
      ->where('belih.nobeli', $nobeli)->get();

    $data = [
      'belih' => $rowbelih,
      'belid' => $rowbelid,
    ];
    // return view('beli.cetak', $data);

    $rowd = Belid::where('nobeli', $nobeli)->get();
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
    $belih = Belih::where('id', $request->id)->first();
    $tanggal = date('Y-m-d');
    $datetime = date('Y-m-d H:i:s');
    $dokumen = $belih->nobeli;
    $form = 'Penerimaan Pembelian';
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
    $mpdf->WriteHTML(view('beli.cetak', $data));
    $namafile = $nobeli . ' - ' . date('dmY H:i:s') . '.pdf';
    //return the PDF for download
    // return $mpdf->Output($request->get('name') . $namafile, Destination::DOWNLOAD);
    $mpdf->Output($namafile, 'I');
    exit;
  }
}
