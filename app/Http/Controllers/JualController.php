<?php

namespace App\Http\Controllers;

use Mpdf\Mpdf;
use Mpdf\Output\Destination;
use App\Http\Requests\JualhRequest;
use App\Http\Requests\JualdRequest;
use Illuminate\Http\Request;
use Session;
// use Yajra\DataTables\Contracts\DataTables;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use App\Models\Soh;
use App\Models\Sod;
use App\Models\Jualh;
use App\Models\Juald;
use App\Models\Tbbarang;
use App\Models\Tbsales;
use App\Models\Userdtl;
use App\Models\Saplikasi;
use Illuminate\Support\Facades\Config;

Config::set('terbilang.locale', 'id');

// //return type View
// use Illuminate\View\View;

class JualController extends Controller
{
  public function index(Request $request) //: View
  {
    $username = session('username');
    $data = [
      'menu' => 'transaksi',
      'submenu' => 'jual',
      'submenu1' => 'ref_umum',
      'title' => 'Penjualan',
      // 'jualh' => Jualh::all(),
      'userdtlmenu' => Userdtl::join('tbmodule', 'userdtl.cmodule', '=', 'tbmodule.cmodule')->where('userdtl.pakai', '1')->where('username', $username)->orderBy('userdtl.nurut')->get(),
      'userdtl' => Userdtl::where('cmodule', 'Penjualan')->where('username', $username)->first(),
    ];
    $userdtl = Userdtl::where('cmodule', 'Penjualan')->where('username', $username)->first();
    if ($userdtl->pakai == '1') {
      return view('jual.index')->with($data);
    } else {
      return redirect('home');
    }
  }
  public function jualajax(Request $request) //: View
  {
    if ($request->ajax()) {
      $data = Jualh::select('*'); //->orderBy('kode', 'asc');
      return Datatables::of($data)
        ->addIndexColumn()
        ->addColumn('kode1', function ($row) {
          $id = $row['id'];
          $btn = $row['kode']; //'<a href="#"' . 'onclick=detail(' . $id . ')>' .  $row['kode'] . '</a>';
          return $btn;
        })
        ->rawColumns(['kode1'])
        ->make(true);
      return view('jual');
    }
  }

  public function create(Request $request)
  {
    if ($request->Ajax()) {
      $username = session('username');
      $data = [
        'menu' => 'transaksi',
        'submenu' => 'jual',
        'submenu1' => 'ref_umum',
        'title' => 'Tambah Data Penjualan',
      ];
      return response()->json([
        'body' => view('jual.modaltambahmaster', [
          'tambahtbnegara' => Userdtl::where('cmodule', 'Tabel Negara')->where('username', $username)->first(),
          'tambahtbjnbrg' => Userdtl::where('cmodule', 'Tabel Jenis Barang')->where('username', $username)->first(),
          'tambahtbsatuan' => Userdtl::where('cmodule', 'Tabel Satuan')->where('username', $username)->first(),
          'tambahtbmove' => Userdtl::where('cmodule', 'Tabel Perputaran Barang')->where('username', $username)->first(),
          'tambahtbdisc' => Userdtl::where('cmodule', 'Tabel Discount')->where('username', $username)->first(),
          'saplikasi' => Saplikasi::where('aktif', 'Y')->first(),
          'tbsales' => Tbsales::orderBy('nama')->get(),
          'jual' => new Jualh(),
          'action' => route('jual.store'),
          'vdata' => $data,
        ])->render(),
        'data' => $data,

      ]);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function store(JualhRequest $request, Jualh $Jualh)
  // public function store(Request $request, Jualh $Jualh)
  {
    if ($request->Ajax()) {
      $sort_num = 0;
      $new_code = $request->nojual;
      $ketemu = 0;
      $record = 0;
      $rec = Jualh::where('nojual', $new_code)->first();
      if ($rec == null) {
        $aplikasi = Saplikasi::where('aktif', 'Y')->first();
        $sort_num = $aplikasi->nojual;
        $tahun = $aplikasi->tahun;
        $bulan = $aplikasi->bulan;
        Saplikasi::where('aktif', 'Y')->update(['nojual' => $sort_num + 1]);
      } else {
        while ($ketemu == $record) { //0=0
          $aplikasi = Saplikasi::where('aktif', 'Y')->first();
          $sort_num = $aplikasi->nojual;
          $tahun = $aplikasi->tahun;
          $bulan = $aplikasi->bulan;
          Saplikasi::where('aktif', 'Y')->update(['nojual' => $sort_num + 1]);
          $new_code = 'JL' . $tahun . sprintf('%02s', $bulan) . sprintf("%05s", $sort_num + 1);
          $rec = Jualh::where('nojual', $new_code)->first();
          if ($rec == null) {
            $record = 0;
            Saplikasi::where('aktif', 'Y')->update(['nojual' => $sort_num + 1]);
            break;
          } else {
            Saplikasi::where('aktif', 'Y')->update(['nojual' => $sort_num + 1]);
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
        $Jualh->fill([
          'nojual' => isset($request->nojual) ? $new_code : '',
          'tgljual' => isset($request->tgljual) ? $request->tgljual : '',
          'nofaktur' => isset($request->nofaktur) ? $request->nofaktur : '',
          'noinvoice' => isset($request->noinvoice) ? $request->noinvoice : '',
          'tgl_invoice' => isset($request->tgl_invoice) ? $request->tgl_invoice : '',
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
          'lppn' => isset($request->lppn) ? 'Y' : 'N',
          'ppn' => $ppn,
          'materai' => $materai,
          'total' => $total,
          'keterangan' => isset($request->keterangan) ? $request->keterangan : '',
          'user' => 'Tambah-' . $request->username . ', ' . date('d-m-Y h:i:s'),
        ]);
        $Jualh->save($validated);
        //Create History
        $tanggal = date('Y-m-d');
        $datetime = date('Y-m-d H:i:s');
        $dokumen = $new_code;
        $form = 'Penjualan';
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
        'submenu' => 'jual',
        'submenu1' => 'ref_umum',
        'title' => 'Detail Penjualan',
        // 'userdtl' => Userdtl::where('cmodule', 'Penjualan')->where('username', $username)->first(),
      ];
      return response()->json([
        'body' => view('jual.modaltambahmaster', [
          'saplikasi' => Saplikasi::where('aktif', 'Y')->first(),
          'tbsales' => Tbsales::get(),
          'jual' => Jualh::where('id', $id)->first(),
          'action' => route('tbbarang.store'),
          'vdata' => $data,
        ])->render(),
        'data' => $data,
      ]);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function edit(Jualh $Jualh, Request $request)
  {
    if ($request->Ajax()) {
      $id = $_GET['id'];
      $username = session('username');
      $data = [
        'menu' => 'transaksi',
        'submenu' => 'jual',
        'submenu1' => 'ref_umum',
        'title' => 'Edit Data Penjualan',
      ];
      // var_dump($data);

      // return response()->json([
      //     'data' => $data,
      // ]);
      return response()->json([
        'body' => view('jual.modaltambahmaster', [
          'saplikasi' => Saplikasi::where('aktif', 'Y')->first(),
          'tbsales' => Tbsales::get(),
          'jual' => Jualh::where('id', $id)->first(),
          // 'action' => route('jual.update', $Jualh->id),
          'action' => 'jualupdate',
          'vdata' => $data,
        ])->render(),
        'data' => $data,
      ]);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function update(Request $request, Jualh $Jualh)
  {
    if ($request->Ajax()) {
      $id = $request->id;
      if ($request->nojual === $request->nojuallama) {
        $validate = $request->validate(
          [
            'nojual' => 'required',
            'tgljual' => 'required',
            'carabayar' => 'required',
          ],
          [
            'nojual.required' => 'No. SO harus di isi',
            'tgljual.required' => 'Tanggal SO harus di isi',
            'carabayar.required' => 'Cara Bayar harus di isi',
          ],
        );
      } else {
        $validate = $request->validate(
          [
            'nojual' => 'required|unique:Jualh|max:255',
            'tgljual' => 'required',
            'carabayar' => 'required',
          ],
          [
            'nojual.required' => 'No. SO harus di isi',
            'tgljual.required' => 'Tanggal SO harus di isi',
            'carabayar.required' => 'Cara Bayar harus di isi',
          ],
        );
      }
      $Jualh = Jualh::find($id);
      if ($validate) {
        $nojual = $request->nojual;
        // $subtotal = DB::table('juald')->where('nojual', $nojual)->sum('subtotal');
        $subtotal = Juald::where('nojual', $nojual)->sum('subtotal');
        $biaya_lain = isset($request->biaya_lain) ? $request->biaya_lain : '0';
        $materai = isset($request->materai) ? $request->materai : '0';
        $ppn = isset($request->ppn) ? $request->ppn : '0';
        $total_sementara = $biaya_lain + $subtotal + $materai;
        $total = $total_sementara + ($total_sementara * ($ppn / 100));
        $Jualh->fill([
          'nojual' => isset($request->nojual) ? $request->nojual : '',
          'tgljual' => isset($request->tgljual) ? $request->tgljual : '',
          'nofaktur' => isset($request->nofaktur) ? $request->nofaktur : '',
          'noinvoice' => isset($request->noinvoice) ? $request->noinvoice : '',
          'tgl_invoice' => isset($request->tgl_invoice) ? $request->tgl_invoice : '',
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
          'lppn' => isset($request->lppn) ? 'Y' : 'N',
          'ppn' => $ppn,
          'materai' => $materai,
          'total' => $total,
          'keterangan' => isset($request->keterangan) ? $request->keterangan : '',
          'user' => 'Update-' . $request->username . ', ' . date('d-m-Y h:i:s'),
        ]);
        $Jualh->save($validate);
        //Create History
        $tanggal = date('Y-m-d');
        $datetime = date('Y-m-d H:i:s');
        $dokumen = $nojual;
        $form = 'Penjualan';
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

  public function jualproses(Request $request, Jualh $jualproses)
  {
    if ($request->Ajax()) {
      $lanjut = true;
      $id = $request->id;
      $jualh = Jualh::where('id', $id)->first();
      $tahun = date("Y", strtotime($jualh->tgljual));
      $bulan = substr('0' . date("m", strtotime($jualh->tgljual)), -2);
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
        if ($saplikasi->kunci_stock == 'Y') {
          $juald = Juald::where('nojual', $jualh->nojual)->get();
          foreach ($juald as $row) {
            //check stock tidak boleh minus
            $tbbarang = Tbbarang::where('kode', $row->kdbarang)->first();
            if ($tbbarang->stock - $row->qty < 0) {
              $lanjut = false;
            }
          }
        }

        if ($lanjut == true) {
          $id = $request->id;
          $jualh = Jualh::where('id', $id)->first();
          $nojual = $jualh->nojual;
          $tgljual = $jualh->tgljual;
          $jualproses->load('jualDetail');
          $subtotal = $jualproses->jualdetail->sum('subtotal');
          $total_sementara = $jualproses->biaya_lain + $subtotal;
          // $jualproses->proses = 'Y';
          // $jualproses->subtotal = $subtotal;
          // $jualproses->total_sementara = $total_sementara;
          // $jualproses->total = $total_sementara + ($total_sementara * ($jualproses->ppn / 100));
          // $jualproses->user = 'Proses-' . session('username') . ', ' . date('d-m-Y h:i:s');
          // $jualproses->save();
          $jualh = Jualh::find($id);
          $sudahbayar = $jualh->sudahbayar;
          $jualh->fill([
            'proses' => 'Y',
            'subtotal' => $subtotal,
            'total_sementara' => $total_sementara,
            'total' => $total_sementara + ($total_sementara * ($jualh->ppn / 100)),
            'kurangbayar' => $total_sementara + ($total_sementara * ($jualh->ppn / 100)) - $sudahbayar,
            'user' => 'Proses-' . session('username') . ', ' . date('d-m-Y h:i:s'),
          ]);
          $jualh->save();
          $juald = Juald::where('nojual', $nojual)->get();
          foreach ($juald as $row) {
            $idd = $row->id;
            $qty = $row->qty;
            //Update stock
            $tbbarang = Tbbarang::where('kode', $row->kdbarang)->first();
            $stockakhir = $tbbarang->stock - $qty;
            // DB::table('tbbarang')->where('kode', $row->kdbarang)->update(['stock' => $stockakhir]);
            Tbbarang::where('kode', $row->kdbarang)->update(['stock' => $stockakhir]);
            // DB::table('juald')->where('id', $idd)->update(['proses' => 'Y', 'kurang' => $qty, 'hpp' => $tbbarang->hpp]);
            Juald::where('id', $idd)->update(['proses' => 'Y', 'kurang' => $qty, 'hpp' => $tbbarang->hpp, 'tgljual' => $tgljual]);
            if ($row->noso <> "") {
              $noso = $row->noso;
              $kdbarang = $row->kdbarang;
              $sod = Sod::where('noso', $noso)->where('kdbarang', $kdbarang)->first();
              $terima = $sod->terima + $qty;
              $kurang = $sod->kurang - $qty;
              // DB::table('sod')->where('noso', $noso)->where('kdbarang', $kdbarang)->update(['terima' =>  $terima, 'kurang' =>  $kurang]);
              Sod::where('noso', $noso)->where('kdbarang', $kdbarang)->update(['terima' =>  $terima, 'kurang' =>  $kurang]);
            }
          }
          //Create History
          $jualh = Jualh::where('id', $request->id)->first();
          $tanggal = date('Y-m-d');
          $datetime = date('Y-m-d H:i:s');
          $dokumen = $jualh->nojual;
          $form = 'Penjualan';
          $status = 'Proses';
          $catatan = isset($request->catatan) ? $request->catatan : '';
          $username = session('username');
          DB::table('hisuser')->insert(['tanggal' => $tanggal, 'dokumen' => $dokumen, 'form' => $form, 'status' => $status, 'user' => $username, 'catatan' => $catatan, 'datetime' => $datetime]);
          return response()->json([
            'sukses' => 'Data berhasil di proses', //view('tbbarang.tabel_barang')
          ]);
          // return redirect()->back()->with('message', 'Berhasil di update');        
        } else {
          return response()->json([
            'sukses' => false,
          ]);
        }
      }
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function jualunproses(Request $request, Jualh $Jualh)
  {
    if ($request->Ajax()) {
      $id = $request->id;
      $user = 'Proses-' . session('username') . ', ' . date('d-m-Y h:i:s');
      // DB::table('jualh')->where('id', $id)->update(['proses' => 'N', 'user' => $user]);
      Jualh::where('id', $id)->update(['proses' => 'N', 'user' => $user]);
      //Create History
      $jualh = Jualh::where('id', $id)->first();
      $tanggal = date('Y-m-d');
      $datetime = date('Y-m-d H:i:s');
      $dokumen = $jualh->nojual;
      $form = 'Penjualan';
      $status = 'Batal Proses';
      $catatan = isset($request->catatan) ? $request->catatan : '';
      $username = session('username');
      DB::table('hisuser')->insert(['tanggal' => $tanggal, 'dokumen' => $dokumen, 'form' => $form, 'status' => $status, 'user' => $username, 'catatan' => $catatan, 'datetime' => $datetime]);
      //Create History
      $jualh = Jualh::where('id', $request->id)->first();
      $tanggal = date('Y-m-d');
      $datetime = date('Y-m-d H:i:s');
      $dokumen = $jualh->nojual;
      $form = 'Penjualan';
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

  public function jualkurir(jualh $jualh, Request $request)
  {
    if ($request->Ajax()) {
      $id = $request->id;
      $data = [
        'menu' => 'transaksi',
        'submenu' => 'jual',
        'submenu1' => 'ref_umum',
        'title' => 'Penjualan',
      ];
      return response()->json([
        'body' => view('jual.modalkurir', [
          'jual' => Jualh::where('id', $id)->first(),
          // 'action' => route('so.update', $soh->id),
          'action' => 'jualkurirsimpan',
          'vdata' => $data,
        ])->render(),
        'data' => $data,
      ]);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function jualkurirsimpan(Request $request, jualh $jualh)
  {
    if ($request->Ajax()) {
      $id = $request->id;
      Jualh::where('id', $id)->update(['kdkurir' => $request->kdkurir, 'nmkurir' => $request->nmkurir]);
      //Create History
      $jualh = Jualh::where('id', $id)->first();
      $nojual = $jualh->nojual;
      $tanggal = date('Y-m-d');
      $datetime = date('Y-m-d H:i:s');
      $dokumen = $nojual;
      $form = 'Penjualan';
      $status = 'Input Kurir';
      $catatan = isset($request->nmkurir) ? $request->nmkurir : '';
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

  public function jualcancel(Request $request, Jualh $Jualh)
  {
    if ($request->Ajax()) {
      $id = $request->id;
      $user = 'Cancel-' . session('username') . ', ' . date('d-m-Y h:i:s');
      // DB::table('jualh')->where('id', $id)->update(['batal' => 'Y', 'user' => $user]);
      Jualh::where('id', $id)->update(['batal' => 'Y', 'user' => $user]);
      //Create History
      $jualh = Jualh::where('id', $request->id)->first();
      $tanggal = date('Y-m-d');
      $datetime = date('Y-m-d H:i:s');
      $dokumen = $jualh->nojual;
      $form = 'Penjualan';
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

  public function jualambil(Request $request, Jualh $Jualh)
  {
    if ($request->Ajax()) {
      $id = $request->id;
      $user = 'Ambil-' . session('username') . ', ' . date('d-m-Y h:i:s');
      // DB::table('jualh')->where('id', $id)->update(['batal' => 'N', 'user' => $user]);
      Jualh::where('id', $id)->update(['batal' => 'N', 'user' => $user]);
      //Create History
      $jualh = Jualh::where('id', $request->id)->first();
      $tanggal = date('Y-m-d');
      $datetime = date('Y-m-d H:i:s');
      $dokumen = $jualh->nojual;
      $form = 'Penjualan';
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

  public function destroy(Jualh $Jualh, Request $request)
  {
    if ($request->Ajax()) {
      $id = $request->id;
      $jualh = Jualh::where('id', $request->id)->first();
      // $deleted = DB::table('jualh')->where('id', $id)->delete();
      $deleted = Jualh::where('id', $id)->delete();
      if ($deleted) {
        // DB::table('juald')->where('nojual', $jualh->nojual)->delete();
        Juald::where('nojual', $jualh->nojual)->delete();
        //Create History
        $tanggal = date('Y-m-d');
        $datetime = date('Y-m-d H:i:s');
        $dokumen = $jualh->nojual;
        $form = 'Penjualan';
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

  public function cariso(Request $request)
  {
    if ($request->Ajax()) {
      $data = [
        'title' => 'Cari Sales Order',
      ];
      // var_dump($data);
      return response()->json([
        'body' => view('jual.modalcariso', [
          // 'soh' => Soh::where('proses', 'Y')->orderBy('noso', 'desc')->get(),
          'soh' => Soh::join('sod', 'sod.noso', '=', 'soh.noso')->where('soh.proses', 'Y')->where('sod.kurang', '>', 0)
            ->groupBy('soh.noso')->orderBy('soh.noso', 'desc')->get(),
          'vdata' => $data,
        ])->render(),
        'data' => $data,
      ]);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function prosessalinso(Request $request)
  {
    if ($request->Ajax()) {
      $insertdetail = false;
      $noso = $request->noso;
      $nojual = $request->nojual;
      $tgljual = $request->tgljual;
      $user = 'Salin-' . session('username') . ', ' . date('d-m-Y h:i:s');
      // DB::table('juald')->where('nojual', $nojual)->where('noso', $noso)->delete();
      $delete = Juald::where('nojual', $nojual)->where('noso', $noso)->delete();
      if ($delete >= 0) {
        $sod = Sod::where('noso', $noso)->get();
        foreach ($sod as $row) {
          // $insert = DB::table('juald')->insert([
          //   'nojual' => $nojual, 'tgljual' => $tgljual, 'kdbarang' => $row->kdbarang, 'nmbarang' => $row->nmbarang,
          //   'kdsatuan' => $row->kdsatuan, 'qty' => $row->qty, 'harga' => $row->harga, 'discount' => $row->discount, 'subtotal' => $row->subtotal,
          //   'noso' => $noso, 'user' => $user
          // ]);
          $insert = Juald::insert([
            'nojual' => $nojual, 'tgljual' => $tgljual, 'kdbarang' => $row->kdbarang, 'nmbarang' => $row->nmbarang,
            'kdsatuan' => $row->kdsatuan, 'qty' => $row->qty, 'harga' => $row->harga, 'discount' => $row->discount, 'subtotal' => $row->subtotal,
            'noso' => $noso, 'user' => $user
          ]);
          if ($insert <> true) {
            $insertdetail = false;
          }
        }
      }
      // $subtotal = DB::table('juald')->where('nojual', $nojual)->sum('subtotal');
      $subtotal = Juald::where('nojual', $nojual)->sum('subtotal');
      $jualh = Jualh::where('nojual', $nojual)->first();
      $biaya_lain = isset($jualh->biaya_lain) ? $jualh->biaya_lain : '0';
      $materai = isset($jualh->materai) ? $jualh->materai : '0';
      $ppn = isset($jualh->ppn) ? $jualh->ppn : '0';
      $total_sementara = $biaya_lain + $subtotal + $materai;
      $total = $total_sementara + ($total_sementara * ($ppn / 100));
      // $update = DB::table('jualh')->where('nojual', $nojual)->update(['biaya_lain' => $biaya_lain, 'subtotal' => $subtotal, 'total_sementara' => $total_sementara, 'ppn' => $ppn, 'total' => $total]);
      $update = Jualh::where('nojual', $nojual)->update(['biaya_lain' => $biaya_lain, 'subtotal' => $subtotal, 'total_sementara' => $total_sementara, 'ppn' => $ppn, 'total' => $total]);
      if ($update >= 0 and $insertdetail) {
        $msg = [
          'sukses' => true,
        ];
      } else {
        $msg = [
          'sukses' => false,
        ];
      }
      $msg = [
        'sukses' => true,
      ];
      echo json_encode($msg);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function jualinputjuald(Jualh $Jualh, Request $request)
  {
    if ($request->Ajax()) {
      $id = $_GET['id'];
      $username = session('username');
      $Jualh = Jualh::where('id', $id)->first();
      $nojual = $Jualh->nojual;
      $data = [
        'menu' => 'transaksi',
        'submenu' => 'jual',
        'submenu1' => 'ref_umum',
        'title' => 'Detail Data Penjualan',
      ];
      // var_dump($data);

      // return response()->json([
      //     'data' => $data,
      // ]);
      return response()->json([
        'body' => view('jual.modaldetail', [
          'userdtlmenu' => Userdtl::join('tbmodule', 'userdtl.cmodule', '=', 'tbmodule.cmodule')->where('userdtl.pakai', '1')->where('username', $username)->orderBy('userdtl.nurut')->get(),
          'userdtl' => Userdtl::where('cmodule', 'Penjualan')->where('username', $username)->first(),
          'saplikasi' => Saplikasi::where('aktif', 'Y')->first(),
          'tbsales' => Tbsales::get(),
          'jual' => Jualh::where('id', $id)->first(),
          'juald' => juald::where('nojual', $nojual)->get(),
          // 'action' => route('jual.update', $Jualh->id),
          'action' => 'jualtambahdetail',
          'vdata' => $data,
        ])->render(),
        'data' => $data,
      ]);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function jualdajax(Request $request) //: View
  {
    $nojual = $request->nojual;
    if ($request->ajax()) {
      // $data = juald::where('nojual', $nojual); //->orderBy('kode', 'asc');
      $data = Juald::leftjoin('tbsatuan', 'tbsatuan.kode', '=', 'juald.kdsatuan')
        ->select('juald.*', 'tbsatuan.nama as nmsatuan')
        ->where('nojual', $nojual); //->orderBy('kode', 'asc');
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

  public function jualtambahdetail(Request $request, JualdRequest $jualdrequest, Juald $juald)
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
        // $recjuald = juald::where('nojual', $request->nojuald)->where('kdbarang', $request->kdbarang)->first();
        // if (isset($recjuald->nojual)) {
        //   $msg = [
        //     'sukses' => 'Data gagal di tambah',
        //   ];
        // } else {
        $juald->fill([
          'nojual' => isset($request->nojuald) ? $request->nojuald : '',
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
        $juald->save($validate);
        $Jualh = Jualh::where('nojual', $request->nojuald)->first();
        $biaya_lain = $Jualh->biaya_lain;
        $materai = $Jualh->materai;
        $ppn = $Jualh->ppn;
        // $subtotal = DB::table('juald')->where('nojual', $request->nojuald)->sum('subtotal');
        $subtotal = Juald::where('nojual', $request->nojuald)->sum('subtotal');
        $total_sementara = $biaya_lain + $subtotal + $materai;
        $total = $total_sementara + ($total_sementara * ($ppn / 100));
        // DB::table('jualh')->where('nojual', $request->nojuald)->update([
        //   'subtotal' => $subtotal, 'biaya_lain' => $biaya_lain, 'materai' => $materai, 'total_sementara' => $total_sementara, 'total_sementara' =>
        //   $total_sementara, 'total' => $total
        // ]);
        Jualh::where('nojual', $request->nojuald)->update([
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


  public function jualtotaldetail(Jualh $Jualh, Request $request)
  {
    if ($request->Ajax()) {
      $nojual = $request->nojual;
      $Jualh = Jualh::where('nojual', $nojual)->first();
      $data = [
        'menu' => 'transaksi',
        'submenu' => 'Penjualan',
        'submenu1' => 'ref_umum',
        'title' => 'Detail Data Penjualan',
      ];
      // var_dump($data);

      // return response()->json([
      //     'data' => $data,
      // ]);
      return response()->json([
        'body' => view('jual.totaldetail', [
          'subtotaljuald' => juald::where('nojual', $nojual)->sum('subtotal'),
          'qtyjuald' => juald::where('nojual', $nojual)->sum('qty'),
          'vdata' => $data,
        ])->render(),
        'data' => $data,
      ]);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function jualcetakfp(Request $request)
  {
    $row = Jualh::where('id', $request->id)->first();
    $nojual = $row->nojual;
    $data = [
      'saplikasi' => Saplikasi::where('aktif', 'Y')->first(),
      'jualh' => $row,
      'juald' => Jualh::join('juald', 'juald.nojual', '=', 'jualh.nojual')
        ->join('tbcustomer', 'tbcustomer.kode', '=', 'jualh.kdcustomer')
        ->join('tbsatuan', 'tbsatuan.kode', '=', 'juald.kdsatuan')
        ->select('jualh.*', 'juald.*', 'tbsatuan.nama as nmsatuan')
        ->where('jualh.nojual', $nojual)->get(),
    ];
    // return view('jual.cetak', $data);

    $rowd = juald::where('nojual', $nojual)->get();
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
    $jualh = Jualh::where('id', $request->id)->first();
    $tanggal = date('Y-m-d');
    $datetime = date('Y-m-d H:i:s');
    $dokumen = $jualh->nojual;
    $form = 'Penjualan';
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
    $mpdf->WriteHTML(view('jual.cetakfp', $data));
    $namafile = $nojual . ' - ' . date('dmY H:i:s') . '.pdf';
    //return the PDF for download
    // return $mpdf->Output($request->get('name') . $namafile, Destination::DOWNLOAD);
    // $mpdf->Output($namafile, 'I');
    $mpdf->Output($namafile, 'I');
    exit;
  }

  public function jualcetaksj(Request $request)
  {
    $row = Jualh::where('id', $request->id)->first();
    $nojual = $row->nojual;
    $data = [
      'saplikasi' => Saplikasi::where('aktif', 'Y')->first(),
      'jualh' => $row,
      'juald' => Jualh::join('juald', 'juald.nojual', '=', 'jualh.nojual')
        ->join('tbcustomer', 'tbcustomer.kode', '=', 'jualh.kdcustomer')
        ->join('tbsatuan', 'tbsatuan.kode', '=', 'juald.kdsatuan')
        ->select('jualh.*', 'juald.*', 'tbsatuan.nama as nmsatuan')
        ->where('jualh.nojual', $nojual)->get(),
    ];
    // return view('jual.cetak', $data);

    $rowd = juald::where('nojual', $nojual)->get();
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
    $jualh = Jualh::where('id', $request->id)->first();
    $tanggal = date('Y-m-d');
    $datetime = date('Y-m-d H:i:s');
    $dokumen = $jualh->nojual;
    $form = 'Penjualan';
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
    $mpdf->WriteHTML(view('jual.cetaksj', $data));
    $namafile = $nojual . ' - ' . date('dmY H:i:s') . '.pdf';
    //return the PDF for download
    // return $mpdf->Output($request->get('name') . $namafile, Destination::DOWNLOAD);
    $mpdf->Output($namafile, 'I');
    exit;
  }

  public function jualbatalproses(jualh $jualh, Request $request)
  {
    if ($request->Ajax()) {
      $id = $_GET['id'];
      $jualh = Jualh::where('id', $id)->first();
      if ($jualh->sudahbayar > 0) {
        return response()->json('2');
      }
      $bulan = substr('0' . date('m', strtotime($jualh->tgljual)), -2);
      $tahun = date('Y', strtotime($jualh->tgljual));
      $periode = $tahun . $bulan;
      $saplikasi = Saplikasi::where('aktif', 'Y')->first();
      // dd($saplikasi->closing_hpp . '   ' . $periode);
      if ($periode <= $saplikasi->closing_hpp) {
        return response()->json('1');
      }
      $data = [
        'menu' => 'transaksi',
        'submenu' => 'jual',
        'submenu1' => 'ref_umum',
        'title' => 'Batal Proses Penjualan',
      ];
      // var_dump($data);

      // return response()->json([
      //   'data' => $data,
      // ]);

      return response()->json([
        'body' => view('jual.modalbatalproses', [
          'jual' => Jualh::where('id', $id)->first(),
          // 'action' => route('so.update', $soh->id),
          'action' => 'jualbatalprosesok',
          'vdata' => $data,
        ])->render(),
        'data' => $data,
      ]);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function jualbatalprosesok(Request $request, jualh $jualh)
  {
    if ($request->Ajax()) {
      $id = $request->id;
      $user = 'Proses-' . session('username') . ', ' . date('d-m-Y h:i:s');
      // DB::table('jualh')->where('id', $id)->update(['proses' => 'N', 'user' => $user]);
      Jualh::where('id', $id)->update(['proses' => 'N', 'user' => $user]);
      $jualh = Jualh::where('id', $id)->first();
      //Update stock
      $juald = Juald::where('nojual', $jualh->nojual)->get();
      foreach ($juald as $row) {
        $idd = $row->id;
        $qty = $row->qty;
        //Update stock
        $tbbarang = Tbbarang::where('kode', $row->kdbarang)->first();
        $stockakhir = $tbbarang->stock + $qty;
        // DB::table('tbbarang')->where('kode', $row->kdbarang)->update(['stock' => $stockakhir]);
        Tbbarang::where('kode', $row->kdbarang)->update(['stock' => $stockakhir]);
        // DB::table('juald')->where('id', $idd)->update(['proses' => 'N', 'kurang' => $qty, 'hpp' => $tbbarang->hpp]);
        Juald::where('id', $idd)->update(['proses' => 'N', 'kurang' => $qty, 'hpp' => $tbbarang->hpp]);
      }
      //Create History
      $jualh = Jualh::where('id', $id)->first();
      $nojual = $jualh->nojual;
      $tanggal = date('Y-m-d');
      $datetime = date('Y-m-d H:i:s');
      $dokumen = $nojual;
      $form = 'Penjualan';
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
}
