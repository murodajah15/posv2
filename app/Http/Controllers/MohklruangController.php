<?php

namespace App\Http\Controllers;

use Mpdf\Mpdf;
use Mpdf\Output\Destination;
use App\Http\Requests\MohklruanghRequest;
use Illuminate\Http\Request;
use Session;
// use Yajra\DataTables\Contracts\DataTables;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use App\Models\Mohklruangh;
use App\Models\Mohklruangd;
use App\Models\Userdtl;
use App\Models\Saplikasi;
use App\Models\Tbjnkeluar;
use Riskihajar\Terbilang\Facades\Terbilang;
use Illuminate\Support\Facades\Config;

Config::set('terbilang.locale', 'id');

// //return type View
// use Illuminate\View\View;

class MohklruangController extends Controller
{
  public function index() //: View
  {
    $username = session('username');
    $data = [
      'menu' => 'transaksi',
      'submenu' => 'mohklruang',
      'submenu1' => 'ref_umum',
      'title' => 'Permohonan Keluar Uang',
      // 'mohklruangh' => Mohklruangh::all(),
      'userdtlmenu' => Userdtl::join('tbmodule', 'userdtl.cmodule', '=', 'tbmodule.cmodule')->where('userdtl.pakai', '1')->where('username', $username)->orderBy('userdtl.nurut')->get(),
      'userdtl' => Userdtl::where('cmodule', 'Permohonan Keluar Uang')->where('username', $username)->first(),
    ];
    $userdtl = Userdtl::where('cmodule', 'Permohonan Keluar Uang')->where('username', $username)->first();
    if ($userdtl->pakai == '1') {
      return view('mohklruang.index')->with($data);
    } else {
      return redirect('home');
    }
  }
  public function mohklruangajax(Request $request) //: View
  {
    if ($request->ajax()) {
      $data = Mohklruangh::select('*'); //->orderBy('kode', 'asc');
      return Datatables::of($data)
        ->addIndexColumn()
        ->addColumn('kode1', function ($row) {
          $id = $row['id'];
          $btn = $row['kode']; //'<a href="#"' . 'onclick=detail(' . $id . ')>' .  $row['kode'] . '</a>';
          return $btn;
        })
        ->rawColumns(['kode1'])
        ->make(true);
      return view('mohklruang');
    }
  }

  public function create(Request $request)
  {
    if ($request->Ajax()) {
      $username = session('username');
      $data = [
        'menu' => 'transaksi',
        'submenu' => 'mohklruang',
        'submenu1' => 'ref_umum',
        'title' => 'Tambah Data Permohonan Keluang Uang',
      ];
      return response()->json([
        'body' => view('mohklruang.modaltambahmaster', [
          'saplikasi' => Saplikasi::where('aktif', 'Y')->first(),
          'mohklruangh' => new mohklruangh(),
          'action' => route('mohklruang.store'),
          'vdata' => $data,
        ])->render(),
        'data' => $data,

      ]);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function store(MohklruanghRequest $request, Mohklruangh $mohklruangh)
  // public function store(Request $request, mohklruangh $mohklruangh)
  {
    if ($request->Ajax()) {
      $sort_num = 0;
      $new_code = $request->nomohon;
      $ketemu = 0;
      $record = 0;
      $rec = Mohklruangh::where('nomohon', $new_code)->first();
      if ($rec == null) {
        $aplikasi = Saplikasi::where('aktif', 'Y')->first();
        $sort_num = $aplikasi->nomohon;
        $tahun = $aplikasi->tahun;
        $bulan = $aplikasi->bulan;
        Saplikasi::where('aktif', 'Y')->update(['nomohon' => $sort_num + 1]);
      } else {
        while ($ketemu == $record) { //0=0
          $aplikasi = Saplikasi::where('aktif', 'Y')->first();
          $sort_num = $aplikasi->nomohon;
          $tahun = $aplikasi->tahun;
          $bulan = $aplikasi->bulan;
          Saplikasi::where('aktif', 'Y')->update(['nomohon' => $sort_num + 1]);
          $new_code = 'PK' . $tahun . sprintf('%02s', $bulan) . sprintf("%04s", $sort_num + 1);
          $rec = Mohklruangh::where('nomohon', $new_code)->first();
          if ($rec == null) {
            $record = 0;
            Saplikasi::where('aktif', 'Y')->update(['nomohon' => $sort_num + 1]);
            break;
          } else {
            Saplikasi::where('aktif', 'Y')->update(['nokwtunai' => $sort_num + 1]);
          }
        }
      }
      $validated = $request->validated();
      $tbjnkeluar = DB::table('tbjnkeluar')->where('kode', $request->kdjnkeluar)->first();
      if ($validated) {
        $mohklruangh->fill([
          'nomohon' => isset($request->nomohon) ? $new_code : '',
          'tglmohon' => isset($request->tglmohon) ? $request->tglmohon : '',
          'kdjnkeluar' => isset($request->kdjnkeluar) ? $request->kdjnkeluar : '',
          'nmjnkeluar' => isset($tbjnkeluar) ? $tbjnkeluar->nama : '',
          'carabayar' => isset($request->carabayar) ? $request->carabayar : '',
          'kdsupplier' => isset($request->kdsupplier) ? $request->kdsupplier : '',
          'nmsupplier' => isset($request->nmsupplier) ? $request->nmsupplier : '',
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
        $mohklruangh->save($validated);
        //Create History
        $tanggal = date('Y-m-d');
        $datetime = date('Y-m-d H:i:s');
        $dokumen = $new_code;
        $form = 'Permohonan Keluar Uang';
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
        'submenu' => 'mohklruang',
        'submenu1' => 'ref_umum',
        'title' => 'Detail Permohonan Keluang Uang',
        // 'userdtl' => Userdtl::where('cmodule', 'Permohonan Keluar Uang')->where('username', $username)->first(),
      ];
      return response()->json([
        'body' => view('mohklruang.modaltambahmaster', [
          'saplikasi' => Saplikasi::where('aktif', 'Y')->first(),
          'mohklruangh' => Mohklruangh::where('id', $id)->first(),
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
        'submenu' => 'mohklruang',
        'submenu1' => 'ref_umum',
        'title' => 'Edit Data Permohonan Keluang Uang',
      ];
      // var_dump($data);

      // return response()->json([
      //     'data' => $data,
      // ]);
      return response()->json([
        'body' => view('mohklruang.modaltambahmaster', [
          'saplikasi' => Saplikasi::where('aktif', 'Y')->first(),
          'mohklruangh' => Mohklruangh::where('id', $id)->first(),
          // 'action' => route('mohklruang.update', $id),
          'action' => 'mohklruangupdate',
          'vdata' => $data,
        ])->render(),
        'data' => $data,
      ]);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function update(Request $request, Mohklruangh $mohklruangh)
  {
    if ($request->Ajax()) {
      $id = $request->id;
      if ($request->nomohon === $request->nomohonlama) {
        $validate = $request->validate(
          [
            'nomohon' => 'required',
            'tglmohon' => 'required',
          ],
          [
            'nomohon.required' => 'No. harus di isi',
            'tglmohon.required' => 'Tanggal harus di isi',
          ],
        );
      } else {
        $validate = $request->validate(
          [
            'nomohon' => 'required|unique:mohklruang|max:255',
            'tglmohon' => 'required',
          ],
          [
            'nomohon.required' => 'No. harus di isi',
            'tglmohon.required' => 'Tanggal harus di isi',
          ],
        );
      }
      $mohklruang = Mohklruangh::find($id);
      if ($validate) {
        $nomohon = $request->nomohon;
        $tbjnkeluar = DB::table('tbjnkeluar')->where('kode', $request->kdjnkeluar)->first();
        $mohklruang->fill([
          'nomohon' => isset($request->nomohon) ? $request->nomohon : '',
          'tglmohon' => isset($request->tglmohon) ? $request->tglmohon : '',
          'kdjnkeluar' => isset($request->kdjnkeluar) ? $request->kdjnkeluar : '',
          'nmjnkeluar' => isset($tbjnkeluar) ? $tbjnkeluar->nama : '',
          'kdsupplier' => isset($request->kdsupplier) ? $request->kdsupplier : '',
          'nmsupplier' => isset($request->nmsupplier) ? $request->nmsupplier : '',
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
          'user' => 'Update-' . $request->username . ', ' . date('d-m-Y h:i:s'),
        ]);
        $mohklruang->save($validate);
        //Create History
        $tanggal = date('Y-m-d');
        $datetime = date('Y-m-d H:i:s');
        $dokumen = $nomohon;
        $form = 'Permohonan Keluar Uang';
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

  public function mohklruangproses(Request $request, Mohklruangh $mohklruangh)
  {
    if ($request->Ajax()) {
      $id = $request->id;
      $mohklruang = Mohklruangh::find($id);
      $uang = DB::table('mohklruangd')->where('nomohon', $mohklruang->nomohon)->sum('uang');
      $total = $uang + $mohklruang->materai;
      $kurang = $total - $mohklruang->bayar;
      $mohklruang->fill([
        'proses' => 'Y',
        'subtotal' => $uang,
        'total' => $total,
        'kurang' => $kurang,
        'user_proses' => 'Proses-' . session('username') . ', ' . date('d-m-Y h:i:s'),
      ]);
      $mohklruang->save();
      //Create History
      $mohklruang = Mohklruangh::where('id', $request->id)->first();
      $tanggal = date('Y-m-d');
      $datetime = date('Y-m-d H:i:s');
      $dokumen = $mohklruang->nomohon;
      $form = 'Permohonan Keluar Uang';
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

  public function mohklruangbatalproses(Mohklruangh $mohklruangh, Request $request)
  {
    if ($request->Ajax()) {
      $id = $_GET['id'];
      $data = [
        'menu' => 'transaksi',
        'submenu' => 'mohklruang',
        'submenu1' => 'ref_umum',
        'title' => 'Batal Proses Permohonan Keluang Uang',
      ];
      // var_dump($data);

      // return response()->json([
      //     'data' => $data,
      // ]);
      return response()->json([
        'body' => view('mohklruang.modalbatalproses', [
          'mohklruangh' => Mohklruangh::where('id', $id)->first(),
          // 'action' => route('so.update', $soh->id),
          'action' => 'mohklruangbatalprosesok',
          'vdata' => $data,
        ])->render(),
        'data' => $data,
      ]);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function mohklruangbatalprosesok(Request $request, Mohklruangh $mohklruangh)
  {
    if ($request->Ajax()) {
      $id = $request->id;
      $mohklruang = Mohklruangh::find($id);
      $uang = DB::table('mohklruangd')->where('nomohon', $mohklruang->nomohon)->sum('uang');
      $total = $uang + $mohklruang->materai;
      $kurang = $total - $mohklruang->bayar;
      $mohklruang->fill([
        'proses' => 'N',
        'subtotal' => $uang,
        'total' => $total,
        'kurang' => $kurang,
        'user_proses' => 'Batal Proses-' . session('username') . ', ' . date('d-m-Y h:i:s'),
      ]);
      $mohklruang->save();
      //Create History
      $mohklruang = Mohklruangh::where('id', $id)->first();
      $nomohon = $mohklruang->nomohon;
      $tanggal = date('Y-m-d');
      $datetime = date('Y-m-d H:i:s');
      $dokumen = $nomohon;
      $form = 'Permohonan Keluar Uang';
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

  public function mohklruangcancel(Request $request, Mohklruangh $mohklruangh)
  {
    if ($request->Ajax()) {
      $id = $request->id;
      $user_proses = 'Cancel-' . session('username') . ', ' . date('d-m-Y h:i:s');
      DB::table('mohklruangh')->where('id', $id)->update(['batal' => 'Y', 'user_proses' => $user_proses]);
      //Create History
      $mohklruang = Mohklruangh::where('id', $request->id)->first();
      $tanggal = date('Y-m-d');
      $datetime = date('Y-m-d H:i:s');
      $dokumen = $mohklruang->nomohon;
      $form = 'Permohonan Keluar Uang';
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

  public function mohklruangambil(Request $request, Mohklruangh $mohklruangh)
  {
    if ($request->Ajax()) {
      $id = $request->id;
      $user_proses = 'Ambil-' . session('username') . ', ' . date('d-m-Y h:i:s');
      DB::table('mohklruangh')->where('id', $id)->update(['batal' => 'N', 'user_proses' => $user_proses]);
      //Create History
      $mohklruang = Mohklruangh::where('id', $request->id)->first();
      $tanggal = date('Y-m-d');
      $datetime = date('Y-m-d H:i:s');
      $dokumen = $mohklruang->nomohon;
      $form = 'Permohonan Keluar Uang';
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

  public function destroy(Mohklruangh $mohklruangh, Request $request)
  {
    if ($request->Ajax()) {
      $id = $request->id;
      $mohklruang = Mohklruangh::where('id', $id)->first();
      $deleted = DB::table('mohklruangh')->where('id', $id)->delete();
      if ($deleted) {
        DB::table('mohklruangd')->where('nomohon', $mohklruang->nomohon)->delete();
        //Create History
        $tanggal = date('Y-m-d');
        $datetime = date('Y-m-d H:i:s');
        $dokumen = $mohklruang->nomohon;
        $form = 'Permohonan Keluar Uang';
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

  public function ambildatatbjnkeluar(Request $request, Tbjnkeluar $tbjnkeluar)
  {
    if ($request->Ajax()) {
      $kdjnkeluar = $request->kdjnkeluar;
      $datatbjnkeluar = $tbjnkeluar->orderBy('nama')->get();
      $isidata = "<option value='' selected>[Pilih Jenis Pengeluaran]</option>";
      foreach ($datatbjnkeluar as $row) {
        if ($row['kode'] == $kdjnkeluar) {
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

  public function tambahtbjnkeluar(Request $request)
  {
    if ($request->Ajax()) {
      $data = [
        'menu' => 'file',
        'submenu' => 'tbbarang',
        'title' => 'Tambah Data Tabel jnkeluar',
        // 'tbjnkeluar' => $this->tbjnkeluarModel->getid()
      ];
      // dd($data);
      $msg = [
        'data' => view('tbbarang/tambahtbjnkeluar', $data),
      ];
      echo json_encode($msg);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }


  public function mohklruangcetak(Request $request)
  {
    $row = Mohklruangh::where('id', $request->id)->first();
    $nomohon = $row->nomohon;
    $rowd = Mohklruangd::where('nomohon', $nomohon)->get();
    // dd($rowd);
    $data = [
      'mohklruangh' => $row,
      'mohklruangd' => $rowd,
    ];
    // return view('mohklruang.cetak', $data);

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
    $mohklruang = Mohklruangh::where('id', $request->id)->first();
    $tanggal = date('Y-m-d');
    $datetime = date('Y-m-d H:i:s');
    $dokumen = $mohklruang->nomohon;
    $form = 'Permohonan Keluar Uang';
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
    $mpdf->WriteHTML(view('mohklruang.cetak', $data));
    $namafile = $nomohon . ' - ' . date('dmY H:i:s') . '.pdf';
    //return the PDF for download
    // return $mpdf->Output($request->get('name') . $namafile, Destination::DOWNLOAD);
    $mpdf->Output($namafile, 'I');
    exit;
  }
}
