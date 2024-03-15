<?php

namespace App\Http\Controllers;

use Mpdf\Mpdf;
use Mpdf\Output\Destination;
use App\Http\Requests\Kasir_tagihanRequest;
use Illuminate\Http\Request;
use Session;
// use Yajra\DataTables\Contracts\DataTables;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use App\Models\Kasir_tagihan;
use App\Models\Kasir_tagihand;
use App\Models\Jualh;
use App\Models\Userdtl;
use App\Models\Saplikasi;
use Riskihajar\Terbilang\Facades\Terbilang;
use Illuminate\Support\Facades\Config;

Config::set('terbilang.locale', 'id');

// //return type View
// use Illuminate\View\View;

class Kasir_tagihanController extends Controller
{
  public function index(Request $request) //: View
  {
    $username = session('username');
    $data = [
      'menu' => 'transaksi',
      'submenu' => 'kasir_tagihan',
      'submenu1' => 'ref_umum',
      'title' => 'Kasir Penerimaan Tagihan',
      // 'kasir_tagihan' => Kasir_tagihan::all(),
      'userdtlmenu' => Userdtl::join('tbmodule', 'userdtl.cmodule', '=', 'tbmodule.cmodule')->where('userdtl.pakai', '1')->where('username', $username)->orderBy('userdtl.nurut')->get(),
      'userdtl' => Userdtl::where('cmodule', 'Kasir Penerimaan Tagihan')->where('username', $username)->first(),
    ];
    $userdtl = Userdtl::where('cmodule', 'Kasir Penerimaan Tagihan')->where('username', $username)->first();
    if ($userdtl->pakai == '1') {
      return view('kasir_tagihan.index')->with($data);
    } else {
      return redirect('home');
    }
  }
  public function kasir_tagihanajax(Request $request) //: View
  {
    if ($request->ajax()) {
      $data = Kasir_tagihan::select('*'); //->orderBy('kode', 'asc');
      return Datatables::of($data)
        ->addIndexColumn()
        ->addColumn('kode1', function ($row) {
          $id = $row['id'];
          $btn = $row['kode']; //'<a href="#"' . 'onclick=detail(' . $id . ')>' .  $row['kode'] . '</a>';
          return $btn;
        })
        ->rawColumns(['kode1'])
        ->make(true);
      return view('kasir_tagihan');
    }
  }

  public function create(Request $request)
  {
    if ($request->Ajax()) {
      $username = session('username');
      $data = [
        'menu' => 'transaksi',
        'submenu' => 'kasir_tagihan',
        'submenu1' => 'ref_umum',
        'title' => 'Tambah Data Kasir Tagihan',
      ];
      return response()->json([
        'body' => view('kasir_tagihan.modaltambahmaster', [
          'saplikasi' => Saplikasi::where('aktif', 'Y')->first(),
          'kasir_tagihan' => new kasir_tagihan(),
          'action' => route('kasir_tagihan.store'),
          'vdata' => $data,
        ])->render(),
        'data' => $data,

      ]);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function store(kasir_tagihanRequest $request, kasir_tagihan $kasir_tagihan)
  // public function store(Request $request, kasir_tagihan $kasir_tagihan)
  {
    if ($request->Ajax()) {
      $sort_num = 0;
      $new_code = $request->nokwitansi;
      $ketemu = 0;
      $record = 0;
      $rec = Kasir_tagihan::where('nokwitansi', $new_code)->first();
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
          $new_code = 'KWT' . $tahun . sprintf('%02s', $bulan) . sprintf("%04s", $sort_num + 1);
          $rec = Kasir_tagihan::where('nokwitansi', $new_code)->first();
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
        $kasir_tagihan->fill([
          'nokwitansi' => isset($request->nokwitansi) ? $new_code : '',
          'tglkwitansi' => isset($request->tglkwitansi) ? $request->tglkwitansi : '',
          'kdcustomer' => isset($request->kdcustomer) ? $request->kdcustomer : '',
          'nmcustomer' => isset($request->nmcustomer) ? $request->nmcustomer : '',
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
          'keterangan' => isset($request->keterangan) ? $request->keterangan : '',
          'user' => 'Tambah-' . $request->username . ', ' . date('d-m-Y h:i:s'),
        ]);
        $kasir_tagihan->save($validated);
        //Create History
        $tanggal = date('Y-m-d');
        $datetime = date('Y-m-d H:i:s');
        $dokumen = $new_code;
        $form = 'Kasir Penerimaan Tagihan';
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
        'submenu' => 'kasir_tagihan',
        'submenu1' => 'ref_umum',
        'title' => 'Detail Kasir Tagihan',
        // 'userdtl' => Userdtl::where('cmodule', 'Kasir Penerimaan Tagihan')->where('username', $username)->first(),
      ];
      return response()->json([
        'body' => view('kasir_tagihan.modaltambahmaster', [
          'saplikasi' => Saplikasi::where('aktif', 'Y')->first(),
          'kasir_tagihan' => Kasir_tagihan::where('id', $id)->first(),
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
        'submenu' => 'kasir_tagihan',
        'submenu1' => 'ref_umum',
        'title' => 'Edit Data Kasir Tagihan',
      ];
      // var_dump($data);

      // return response()->json([
      //     'data' => $data,
      // ]);
      return response()->json([
        'body' => view('kasir_tagihan.modaltambahmaster', [
          'saplikasi' => Saplikasi::where('aktif', 'Y')->first(),
          'kasir_tagihan' => Kasir_tagihan::where('id', $id)->first(),
          // 'action' => route('kasir_tagihan.update', $kasir_tagihan->id),
          'action' => 'kasir_tagihanupdate',
          'vdata' => $data,
        ])->render(),
        'data' => $data,
      ]);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function update(Request $request, kasir_tagihan $kasir_tagihan)
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
            'nokwitansi' => 'required|unique:kasir_tagihan|max:255',
            'tglkwitansi' => 'required',
          ],
          [
            'nokwitansi.required' => 'No. harus di isi',
            'tglkwitansi.required' => 'Tanggal harus di isi',
          ],
        );
      }
      $kasir_tagihan = Kasir_tagihan::find($id);
      if ($validate) {
        $nokwitansi = $request->nokwitansi;
        $kasir_tagihan->fill([
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
          'keterangan' => isset($request->keterangan) ? $request->keterangan : '',
          'user' => 'Update-' . $request->username . ', ' . date('d-m-Y h:i:s'),
        ]);
        $kasir_tagihan->save($validate);
        //Create History
        $tanggal = date('Y-m-d');
        $datetime = date('Y-m-d H:i:s');
        $dokumen = $nokwitansi;
        $form = 'Kasir Penerimaan Tagihan';
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

  public function kasir_tagihanproses(Request $request, kasir_tagihan $kasir_tagihanproses)
  {
    if ($request->Ajax()) {
      $id = $request->id;
      $kasir_tagihan = Kasir_tagihan::find($id);
      $tglkwitansi = $kasir_tagihan->tglkwitansi;
      //Cek tanggal bayar tidak boleh lebih kecil dari tanggal jual
      $lanjut = true;
      $kasir_tagihand = Kasir_tagihand::where('nokwitansi', $kasir_tagihan->nokwitansi)->get();
      foreach ($kasir_tagihand as $row) {
        $jualh = Jualh::where('nojual', $row->nojual)->first();
        if ($tglkwitansi < $jualh->tgljual) {
          $lanjut = false;
        }
      }

      if ($lanjut == true) {
        $kasir_tagihan->fill([
          'proses' => 'Y',
          'user_proses' => 'Proses-' . session('username') . ', ' . date('d-m-Y h:i:s'),
        ]);
        $kasir_tagihan->save();
        //Update Jualh
        $kasir_tagihand = Kasir_tagihand::where('nokwitansi', $kasir_tagihan->nokwitansi)->get();
        foreach ($kasir_tagihand as $row) {
          $jualh = Jualh::where('nojual', $row->nojual)->first();
          $sudahbayar = $jualh->sudahbayar + $row->bayar;
          $kurangbayar = $jualh->kurangbayar - $row->bayar;
          DB::table('jualh')->where('nojual', $row->nojual)->update(['sudahbayar' => $sudahbayar, 'kurangbayar' => $kurangbayar]);
        }
        //Create History
        $kasir_tagihan = Kasir_tagihan::where('id', $request->id)->first();
        $tanggal = date('Y-m-d');
        $datetime = date('Y-m-d H:i:s');
        $dokumen = $kasir_tagihan->nokwitansi;
        $form = 'Kasir Penerimaan Tagihan';
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

  public function kasir_tagihanbatalproses(kasir_tagihan $kasir_tagihan, Request $request)
  {
    if ($request->Ajax()) {
      $id = $_GET['id'];
      $data = [
        'menu' => 'transaksi',
        'submenu' => 'kasir_tagihan',
        'submenu1' => 'ref_umum',
        'title' => 'Batal Proses Kasir Tagihan',
      ];
      // var_dump($data);

      // return response()->json([
      //     'data' => $data,
      // ]);
      return response()->json([
        'body' => view('kasir_tagihan.modalbatalproses', [
          'kasir_tagihan' => Kasir_tagihan::where('id', $id)->first(),
          // 'action' => route('so.update', $soh->id),
          'action' => 'kasir_tagihanbatalprosesok',
          'vdata' => $data,
        ])->render(),
        'data' => $data,
      ]);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function kasir_tagihanbatalprosesok(Request $request, kasir_tagihan $kasir_tagihan)
  {
    if ($request->Ajax()) {
      $id = $request->id;
      $kasir_tagihan = Kasir_tagihan::find($id);
      $user_proses = 'Proses-' . session('username') . ', ' . date('d-m-Y h:i:s');
      DB::table('kasir_tagihan')->where('id', $id)->update(['proses' => 'N', 'user_proses' => $user_proses]);
      //Update Jualh
      $kasir_tagihand = Kasir_tagihand::where('nokwitansi', $kasir_tagihan->nokwitansi)->get();
      foreach ($kasir_tagihand as $row) {
        $jualh = Jualh::where('nojual', $row->nojual)->first();
        $sudahbayar = $jualh->sudahbayar - $row->bayar;
        $kurangbayar = $jualh->kurangbayar + $row->bayar;
        DB::table('jualh')->where('nojual', $row->nojual)->update(['sudahbayar' => $sudahbayar, 'kurangbayar' => $kurangbayar]);
      }
      //Create History
      $kasir_tagihan = Kasir_tagihan::where('id', $id)->first();
      $nokwitansi = $kasir_tagihan->nokwitansi;
      $tanggal = date('Y-m-d');
      $datetime = date('Y-m-d H:i:s');
      $dokumen = $nokwitansi;
      $form = 'Kasir Penerimaan Tagihan';
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

  public function kasir_tagihancancel(Request $request, kasir_tagihan $kasir_tagihan)
  {
    if ($request->Ajax()) {
      $id = $request->id;
      $user_proses = 'Cancel-' . session('username') . ', ' . date('d-m-Y h:i:s');
      DB::table('kasir_tagihan')->where('id', $id)->update(['batal' => 'Y', 'user_proses' => $user_proses]);
      //Create History
      $kasir_tagihan = Kasir_tagihan::where('id', $request->id)->first();
      $tanggal = date('Y-m-d');
      $datetime = date('Y-m-d H:i:s');
      $dokumen = $kasir_tagihan->nokwitansi;
      $form = 'Kasir Penerimaan Tagihan';
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

  public function kasir_tagihanambil(Request $request, kasir_tagihan $kasir_tagihan)
  {
    if ($request->Ajax()) {
      $id = $request->id;
      $user_proses = 'Ambil-' . session('username') . ', ' . date('d-m-Y h:i:s');
      DB::table('kasir_tagihan')->where('id', $id)->update(['batal' => 'N', 'user_proses' => $user_proses]);
      //Create History
      $kasir_tagihan = Kasir_tagihan::where('id', $request->id)->first();
      $tanggal = date('Y-m-d');
      $datetime = date('Y-m-d H:i:s');
      $dokumen = $kasir_tagihan->nokwitansi;
      $form = 'Kasir Penerimaan Tagihan';
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

  public function destroy(kasir_tagihan $kasir_tagihan, Request $request)
  {
    if ($request->Ajax()) {
      $id = $request->id;
      $kasir_tagihan = Kasir_tagihan::where('id', $id)->first();
      $deleted = DB::table('kasir_tagihan')->where('id', $id)->delete();
      if ($deleted) {
        DB::table('kasir_tagihand')->where('nokwitansi', $kasir_tagihan->nokwitansi)->delete();
        //Create History
        $tanggal = date('Y-m-d');
        $datetime = date('Y-m-d H:i:s');
        $dokumen = $kasir_tagihan->nokwitansi;
        $form = 'Kasir Penerimaan Tagihan';
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


  public function kasir_tagihancetak(Request $request)
  {
    $row = Kasir_tagihan::where('id', $request->id)->first();
    $nokwitansi = $row->nokwitansi;
    $rowd = Kasir_tagihand::where('nokwitansi', $nokwitansi)->get();
    // dd($rowd);
    $data = [
      'kasir_tagihan' => $row,
      'kasir_tagihand' => $rowd,
    ];
    // return view('kasir_tagihan.cetak', $data);

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
    $kasir_tagihan = Kasir_tagihan::where('id', $request->id)->first();
    $tanggal = date('Y-m-d');
    $datetime = date('Y-m-d H:i:s');
    $dokumen = $kasir_tagihan->nokwitansi;
    $form = 'Kasir Penerimaan Tagihan';
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
    $mpdf->WriteHTML(view('kasir_tagihan.cetak', $data));
    $namafile = $nokwitansi . ' - ' . date('dmY H:i:s') . '.pdf';
    //return the PDF for download
    // return $mpdf->Output($request->get('name') . $namafile, Destination::DOWNLOAD);
    $mpdf->Output($namafile, 'I');
    exit;
  }
}
