<?php

namespace App\Http\Controllers;

use Mpdf\Mpdf;
use Mpdf\Output\Destination;
use App\Http\Requests\Kasir_tunaiRequest;
use Illuminate\Http\Request;
use Session;
// use Yajra\DataTables\Contracts\DataTables;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use App\Models\Kasir_tunai;
use App\Models\Jualh;
use App\Models\Userdtl;
use App\Models\Saplikasi;
use Riskihajar\Terbilang\Facades\Terbilang;
use Illuminate\Support\Facades\Config;

Config::set('terbilang.locale', 'id');

// //return type View
// use Illuminate\View\View;

class Kasir_tunaiController extends Controller
{
  public function index(Request $request) //: View
  {
    $username = session('username');
    $data = [
      'menu' => 'transaksi',
      'submenu' => 'kasir_tunai',
      'submenu1' => 'ref_umum',
      'title' => 'Kasir Penerimaan Tunai',
      // 'kasir_tunai' => Kasir_tunai::all(),
      'userdtlmenu' => Userdtl::join('tbmodule', 'userdtl.cmodule', '=', 'tbmodule.cmodule')->where('userdtl.pakai', '1')->where('username', $username)->orderBy('userdtl.nurut')->get(),
      'userdtl' => Userdtl::where('cmodule', 'Kasir Penerimaan Tunai')->where('username', $username)->first(),
    ];
    $userdtl = Userdtl::where('cmodule', 'Penjualan')->where('username', $username)->first();
    if ($userdtl->pakai == '1') {
      return view('kasir_tunai.index')->with($data);
    } else {
      return redirect('home');
    }
  }
  public function kasir_tunaiajax(Request $request) //: View
  {
    if ($request->ajax()) {
      $data = Kasir_tunai::select('*'); //->orderBy('kode', 'asc');
      return Datatables::of($data)
        ->addIndexColumn()
        ->addColumn('kode1', function ($row) {
          $id = $row['id'];
          $btn = $row['kode']; //'<a href="#"' . 'onclick=detail(' . $id . ')>' .  $row['kode'] . '</a>';
          return $btn;
        })
        ->rawColumns(['kode1'])
        ->make(true);
      return view('kasir_tunai');
    }
  }

  public function create(Request $request)
  {
    if ($request->Ajax()) {
      $username = session('username');
      $data = [
        'menu' => 'transaksi',
        'submenu' => 'kasir_tunai',
        'submenu1' => 'ref_umum',
        'title' => 'Tambah Data Kasir Tunai',
      ];
      return response()->json([
        'body' => view('kasir_tunai.modaltambahmaster', [
          'saplikasi' => Saplikasi::where('aktif', 'Y')->first(),
          'kasir_tunai' => new kasir_tunai(),
          'action' => route('kasir_tunai.store'),
          'vdata' => $data,
        ])->render(),
        'data' => $data,

      ]);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function store(Kasir_tunaiRequest $request, kasir_tunai $kasir_tunai)
  // public function store(Request $request, kasir_tunai $kasir_tunai)
  {
    if ($request->Ajax()) {
      $sort_num = 0;
      $new_code = $request->nokwitansi;
      $ketemu = 0;
      $record = 0;
      $rec = Kasir_tunai::where('nokwitansi', $new_code)->first();
      if ($rec == null) {
        $aplikasi = Saplikasi::where('aktif', 'Y')->first();
        $sort_num = $aplikasi->nokwitansi;
        $tahun = $aplikasi->tahun;
        $bulan = $aplikasi->bulan;
        DB::table('saplikasi')->where('aktif', 'Y')->update(['nokwtunai' => $sort_num + 1]);
      } else {
        while ($ketemu == $record) { //0=0
          $aplikasi = Saplikasi::where('aktif', 'Y')->first();
          $sort_num = $aplikasi->nokwtunai;
          $tahun = $aplikasi->tahun;
          $bulan = $aplikasi->bulan;
          DB::table('saplikasi')->where('aktif', 'Y')->update(['nokwtunai' => $sort_num + 1]);
          $new_code = 'KW' . $tahun . sprintf('%02s', $bulan) . sprintf("%05s", $sort_num + 1);
          $rec = Kasir_tunai::where('nokwitansi', $new_code)->first();
          if ($rec == null) {
            $record = 0;
            DB::table('saplikasi')->where('aktif', 'Y')->update(['nokwtunai' => $sort_num + 1]);
            break;
          } else {
            DB::table('saplikasi')->where('aktif', 'Y')->update(['nokwtunai' => $sort_num + 1]);
          }
        }
      }
      $validated = $request->validated();
      if ($validated) {
        $kasir_tunai->fill([
          'nokwitansi' => isset($request->nokwitansi) ? $new_code : '',
          'tglkwitansi' => isset($request->tglkwitansi) ? $request->tglkwitansi : '',
          'kdcustomer' => isset($request->kdcustomer) ? $request->kdcustomer : '',
          'nmcustomer' => isset($request->nmcustomer) ? $request->nmcustomer : '',
          'nojual' => isset($request->nojual) ? $request->nojual : '',
          'tgljual' => isset($request->tgljual) ? $request->tgljual : '',
          'jnskwitansi' => isset($request->jnskwitansi) ? $request->jnskwitansi : '',
          'carabayar' => isset($request->carabayar) ? $request->carabayar : '',
          'kdbank' => isset($request->kdbank) ? $request->kdbank : '',
          'nmbank' => isset($request->nmbank) ? $request->nmbank : '',
          'kdjnskartu' => isset($request->kdjnskartu) ? $request->kdjnskartu : '',
          'nmjnskartu' => isset($request->nmjnskartu) ? $request->nmjnskartu : '',
          'norek' => isset($request->norek) ? $request->norek : '',
          'nocekgiro' => isset($request->nocekgiro) ? $request->nocekgiro : '',
          'tglterimacekgiro' => isset($request->tglterimacekgiro) ? $request->tglterimacekgiro : '',
          'tgljtempocekgiro' => isset($request->tgljtempocekgiro) ? $request->tgljtempocekgiro : '',
          'piutang' => isset($request->piutang) ? $request->piutang : '',
          'bayar' => isset($request->bayar) ? $request->bayar : '',
          'uang' => isset($request->uang) ? $request->uang : '',
          'kembali' => isset($request->kembali) ? $request->kembali : '',
          'materai' => isset($request->materai) ? $request->materai : '',
          'keterangan' => isset($request->keterangan) ? $request->keterangan : '',
          'user' => 'Tambah-' . $request->username . ', ' . date('d-m-Y h:i:s'),
        ]);
        $kasir_tunai->save($validated);
        //Create History
        $tanggal = date('Y-m-d');
        $datetime = date('Y-m-d H:i:s');
        $dokumen = $new_code;
        $form = 'Kasir Penerimaan Tunai';
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
        'submenu' => 'kasir_tunai',
        'submenu1' => 'ref_umum',
        'title' => 'Detail Kasir Tunai',
        // 'userdtl' => Userdtl::where('cmodule', 'Kasir Penerimaan Tunai')->where('username', $username)->first(),
      ];
      return response()->json([
        'body' => view('kasir_tunai.modaltambahmaster', [
          'saplikasi' => Saplikasi::where('aktif', 'Y')->first(),
          'kasir_tunai' => Kasir_tunai::where('id', $id)->first(),
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
        'submenu' => 'kasir_tunai',
        'submenu1' => 'ref_umum',
        'title' => 'Edit Data Kasir Tunai',
      ];
      // var_dump($data);

      // return response()->json([
      //     'data' => $data,
      // ]);
      return response()->json([
        'body' => view('kasir_tunai.modaltambahmaster', [
          'saplikasi' => Saplikasi::where('aktif', 'Y')->first(),
          'kasir_tunai' => Kasir_tunai::where('id', $id)->first(),
          // 'action' => route('kasir_tunai.update', $kasir_tunai->id),
          'action' => 'kasir_tunaiupdate',
          'vdata' => $data,
        ])->render(),
        'data' => $data,
      ]);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function update(Request $request, kasir_tunai $kasir_tunai)
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
            'nokwitansi' => 'required|unique:kasir_tunai|max:255',
            'tglkwitansi' => 'required',
          ],
          [
            'nokwitansi.required' => 'No. harus di isi',
            'tglkwitansi.required' => 'Tanggal harus di isi',
          ],
        );
      }
      $kasir_tunai = Kasir_tunai::find($id);
      if ($validate) {
        $nokwitansi = $request->nokwitansi;
        $kasir_tunai->fill([
          'nokwitansi' => isset($request->nokwitansi) ? $request->nokwitansi : '',
          'tglkwitansi' => isset($request->tglkwitansi) ? $request->tglkwitansi : '',
          'kdcustomer' => isset($request->kdcustomer) ? $request->kdcustomer : '',
          'nmcustomer' => isset($request->nmcustomer) ? $request->nmcustomer : '',
          'nojual' => isset($request->nojual) ? $request->nojual : '',
          'tgljual' => isset($request->tgljual) ? $request->tgljual : '',
          'jnskwitansi' => isset($request->jnskwitansi) ? $request->jnskwitansi : '',
          'carabayar' => isset($request->carabayar) ? $request->carabayar : '',
          'kdbank' => isset($request->kdbank) ? $request->kdbank : '',
          'nmbank' => isset($request->nmbank) ? $request->nmbank : '',
          'kdjnskartu' => isset($request->kdjnskartu) ? $request->kdjnskartu : '',
          'nmjnskartu' => isset($request->nmjnskartu) ? $request->nmjnskartu : '',
          'norek' => isset($request->norek) ? $request->norek : '',
          'nocekgiro' => isset($request->nocekgiro) ? $request->nocekgiro : '',
          'tglterimacekgiro' => isset($request->tglterimacekgiro) ? $request->tglterimacekgiro : '',
          'tgljttempocekgiro' => isset($request->tgljttempocekgiro) ? $request->tgljttempocekgiro : '',
          'piutang' => isset($request->piutang) ? $request->piutang : '',
          'bayar' => isset($request->bayar) ? $request->bayar : '',
          'uang' => isset($request->uang) ? $request->uang : '',
          'kembali' => isset($request->kembali) ? $request->kembali : '',
          'materai' => isset($request->materai) ? $request->materai : '',
          'keterangan' => isset($request->keterangan) ? $request->keterangan : '',
          'user' => 'Update-' . $request->username . ', ' . date('d-m-Y h:i:s'),
        ]);
        $kasir_tunai->save($validate);
        //Create History
        $tanggal = date('Y-m-d');
        $datetime = date('Y-m-d H:i:s');
        $dokumen = $nokwitansi;
        $form = 'Kasir Penerimaan Tunai';
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

  public function kasir_tunaiproses(Request $request, kasir_tunai $kasir_tunaiproses)
  {
    if ($request->Ajax()) {
      $id = $request->id;
      $kasir_tunai = Kasir_tunai::find($id);
      $tglkwitansi = $kasir_tunai->tglkwitansi;
      $lanjut = true;
      //Cek tanggal bayar harus sama dengan tanggal jual
      $jualh = Jualh::where('nojual', $request->nojual)->first();
      if ($tglkwitansi <> $jualh->tgljual) {
        $lanjut = false;
      }

      if ($lanjut == true) {
        $kasir_tunai->fill([
          'proses' => 'Y',
          'user_proses' => 'Proses-' . session('username') . ', ' . date('d-m-Y h:i:s'),
        ]);
        $kasir_tunai->save();
        //Update Jualh
        $jualh = Jualh::where('nojual', $kasir_tunai->nojual)->first();
        $sudahbayar = $jualh->sudahbayar + $kasir_tunai->bayar;
        $kurangbayar = $jualh->kurangbayar - $kasir_tunai->bayar;
        DB::table('jualh')->where('nojual', $kasir_tunai->nojual)->update(['sudahbayar' => $sudahbayar, 'kurangbayar' => $kurangbayar]);
        //Create History
        $kasir_tunai = Kasir_tunai::where('id', $request->id)->first();
        $tanggal = date('Y-m-d');
        $datetime = date('Y-m-d H:i:s');
        $dokumen = $kasir_tunai->nokwitansi;
        $form = 'Kasir Penerimaan Tunai';
        $status = 'Proses';
        $catatan = isset($request->catatan) ? $request->catatan : '';
        $username = session('username');
        DB::table('hisuser')->insert(['tanggal' => $tanggal, 'dokumen' => $dokumen, 'form' => $form, 'status' => $status, 'user' => $username, 'catatan' => $catatan, 'datetime' => $datetime]);
        return response()->json([
          'sukses' => true, //view('tbbarang.tabel_barang')
        ]);
        // return redirect()->back()->with('message', 'Berhasil di update');
      } else {
        return response()->json([
          'sukses' => false, //view('tbbarang.tabel_barang')
        ]);
      }
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function kasir_tunaibatalproses(kasir_tunai $kasir_tunai, Request $request)
  {
    if ($request->Ajax()) {
      $id = $_GET['id'];
      $data = [
        'menu' => 'transaksi',
        'submenu' => 'kasir_tunai',
        'submenu1' => 'ref_umum',
        'title' => 'Batal Proses Kasir Tunai',
      ];
      // var_dump($data);

      // return response()->json([
      //     'data' => $data,
      // ]);
      return response()->json([
        'body' => view('kasir_tunai.modalbatalproses', [
          'kasir_tunai' => Kasir_tunai::where('id', $id)->first(),
          // 'action' => route('so.update', $soh->id),
          'action' => 'kasir_tunaibatalprosesok',
          'vdata' => $data,
        ])->render(),
        'data' => $data,
      ]);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function kasir_tunaibatalprosesok(Request $request, kasir_tunai $kasir_tunai)
  {
    if ($request->Ajax()) {
      $id = $request->id;
      $kasir_tunai = Kasir_tunai::find($id);
      $user_proses = 'Proses-' . session('username') . ', ' . date('d-m-Y h:i:s');
      DB::table('kasir_tunai')->where('id', $id)->update(['proses' => 'N', 'user_proses' => $user_proses]);
      //Update Jualh
      $jualh = Jualh::where('nojual', $kasir_tunai->nojual)->first();
      $sudahbayar = $jualh->sudahbayar - $kasir_tunai->bayar;
      $kurangbayar = $jualh->kurangbayar + $kasir_tunai->bayar;
      DB::table('jualh')->where('nojual', $kasir_tunai->nojual)->update(['sudahbayar' => $sudahbayar, 'kurangbayar' => $kurangbayar]);
      //Create History
      $kasir_tunai = Kasir_tunai::where('id', $id)->first();
      $nokwitansi = $kasir_tunai->nokwitansi;
      $tanggal = date('Y-m-d');
      $datetime = date('Y-m-d H:i:s');
      $dokumen = $nokwitansi;
      $form = 'Kasir Penerimaan Tunai';
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

  public function kasir_tunaicancel(Request $request, kasir_tunai $kasir_tunai)
  {
    if ($request->Ajax()) {
      $id = $request->id;
      $user_proses = 'Cancel-' . session('username') . ', ' . date('d-m-Y h:i:s');
      DB::table('kasir_tunai')->where('id', $id)->update(['batal' => 'Y', 'user_proses' => $user_proses]);
      //Create History
      $kasir_tunai = Kasir_tunai::where('id', $request->id)->first();
      $tanggal = date('Y-m-d');
      $datetime = date('Y-m-d H:i:s');
      $dokumen = $kasir_tunai->nokwitansi;
      $form = 'Kasir Penerimaan Tunai';
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

  public function kasir_tunaiambil(Request $request, kasir_tunai $kasir_tunai)
  {
    if ($request->Ajax()) {
      $id = $request->id;
      $user_proses = 'Ambil-' . session('username') . ', ' . date('d-m-Y h:i:s');
      DB::table('kasir_tunai')->where('id', $id)->update(['batal' => 'N', 'user_proses' => $user_proses]);
      //Create History
      $kasir_tunai = Kasir_tunai::where('id', $request->id)->first();
      $tanggal = date('Y-m-d');
      $datetime = date('Y-m-d H:i:s');
      $dokumen = $kasir_tunai->nokwitansi;
      $form = 'Kasir Penerimaan Tunai';
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

  public function destroy(kasir_tunai $kasir_tunai, Request $request)
  {
    if ($request->Ajax()) {
      $id = $request->id;
      $kasir_tunai = Kasir_tunai::where('id', $id)->first();
      $deleted = DB::table('kasir_tunai')->where('id', $id)->delete();
      if ($deleted) {
        //Create History
        $tanggal = date('Y-m-d');
        $datetime = date('Y-m-d H:i:s');
        $dokumen = $kasir_tunai->nokwitansi;
        $form = 'Kasir Penerimaan Tunai';
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


  public function kasir_tunaicetak(Request $request)
  {
    $row = Kasir_tunai::where('id', $request->id)->first();
    $nokwitansi = $row->nokwitansi;
    $data = [
      'kasir_tunai' => $row,
    ];
    // return view('kasir_tunai.cetak', $data);

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
    $kasir_tunai = Kasir_tunai::where('id', $request->id)->first();
    $tanggal = date('Y-m-d');
    $datetime = date('Y-m-d H:i:s');
    $dokumen = $kasir_tunai->nokwitansi;
    $form = 'Kasir Penerimaan Tunai';
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
    $mpdf->WriteHTML(view('kasir_tunai.cetak', $data));
    $namafile = $nokwitansi . ' - ' . date('dmY H:i:s') . '.pdf';
    //return the PDF for download
    // return $mpdf->Output($request->get('name') . $namafile, Destination::DOWNLOAD);
    $mpdf->Output($namafile, 'I');
    exit;
  }
}
