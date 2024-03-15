<?php

namespace App\Http\Controllers;

use Mpdf\Mpdf;
use Mpdf\Output\Destination;
use App\Http\Requests\Approv_batas_piutangRequest;
use Illuminate\Http\Request;
use Session;
// use Yajra\DataTables\Contracts\DataTables;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use App\Models\Approv_batas_piutang;
use App\Models\Jualh;
use App\Models\Userdtl;
use App\Models\Saplikasi;
use Riskihajar\Terbilang\Facades\Terbilang;
use Illuminate\Support\Facades\Config;

Config::set('terbilang.locale', 'id');

// //return type View
// use Illuminate\View\View;

class Approv_batas_piutangController extends Controller
{
  public function index(Request $request) //: View
  {
    $username = session('username');
    $data = [
      'menu' => 'transaksi',
      'submenu' => 'approv_batas_piutang',
      'submenu1' => 'ref_umum',
      'title' => 'Approval Batas Piutang',
      // 'approv_batas_piutang' => Approv_batas_piutang::all(),
      'userdtlmenu' => Userdtl::join('tbmodule', 'userdtl.cmodule', '=', 'tbmodule.cmodule')->where('userdtl.pakai', '1')->where('username', $username)->orderBy('userdtl.nurut')->get(),
      'userdtl' => Userdtl::where('cmodule', 'Approval Batas Piutang')->where('username', $username)->first(),
    ];
    // var_dump($data);
    return view('approv_batas_piutang.index')->with($data);
  }
  public function approv_batas_piutangajax(Request $request) //: View
  {
    if ($request->ajax()) {
      $data = Approv_batas_piutang::select('*'); //->orderBy('kode', 'asc');
      return Datatables::of($data)
        ->addIndexColumn()
        ->addColumn('kode1', function ($row) {
          $id = $row['id'];
          $btn = $row['kode']; //'<a href="#"' . 'onclick=detail(' . $id . ')>' .  $row['kode'] . '</a>';
          return $btn;
        })
        ->rawColumns(['kode1'])
        ->make(true);
      return view('approv_batas_piutang');
    }
  }

  public function create(Request $request)
  {
    if ($request->Ajax()) {
      $username = session('username');
      $data = [
        'menu' => 'transaksi',
        'submenu' => 'approv_batas_piutang',
        'submenu1' => 'ref_umum',
        'title' => 'Tambah Data Approval Batas Piutang',
      ];
      return response()->json([
        'body' => view('approv_batas_piutang.modaltambahmaster', [
          'saplikasi' => Saplikasi::where('aktif', 'Y')->first(),
          'approv_batas_piutang' => new approv_batas_piutang(),
          'action' => route('approv_batas_piutang.store'),
          'vdata' => $data,
        ])->render(),
        'data' => $data,

      ]);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function store(Approv_batas_piutangRequest $request, approv_batas_piutang $approv_batas_piutang)
  // public function store(Request $request, approv_batas_piutang $approv_batas_piutang)
  {
    if ($request->Ajax()) {
      $sort_num = 0;
      $new_code = $request->noapprov;
      $ketemu = 0;
      $record = 0;
      $rec = Approv_batas_piutang::where('noapprov', $new_code)->first();
      if ($rec == null) {
        $aplikasi = Saplikasi::where('aktif', 'Y')->first();
        $sort_num = $aplikasi->noapprov;
        $tahun = $aplikasi->tahun;
        $bulan = $aplikasi->bulan;
        DB::table('saplikasi')->where('aktif', 'Y')->update(['noapprov' => $sort_num + 1]);
      } else {
        while ($ketemu == $record) { //0=0
          $aplikasi = Saplikasi::where('aktif', 'Y')->first();
          $sort_num = $aplikasi->noapprov;
          $tahun = $aplikasi->tahun;
          $bulan = $aplikasi->bulan;
          DB::table('saplikasi')->where('aktif', 'Y')->update(['noapprov' => $sort_num + 1]);
          $new_code = 'AP' . $tahun . $bulan . sprintf("%05s", $sort_num + 1);
          $rec = Approv_batas_piutang::where('noapprov', $new_code)->first();
          if ($rec == null) {
            $record = 0;
            DB::table('saplikasi')->where('aktif', 'Y')->update(['noapprov' => $sort_num + 1]);
            break;
          } else {
            DB::table('saplikasi')->where('aktif', 'Y')->update(['noapprov' => $sort_num + 1]);
          }
        }
      }
      $validated = $request->validated();
      if ($validated) {
        $approv_batas_piutang->fill([
          'noapprov' => isset($request->noapprov) ? $new_code : '',
          'tglapprov' => isset($request->tglapprov) ? $request->tglapprov : '',
          'nojual' => isset($request->nojual) ? $request->nojual : '',
          'tgljual' => isset($request->tgljual) ? $request->tgljual : '',
          'nmcustomer' => isset($request->nmcustomer) ? $request->nmcustomer : '',
          'total' => isset($request->total) ? $request->total : '',
          'keterangan' => isset($request->keterangan) ? $request->keterangan : '',
          'user' => 'Tambah-' . $request->username . ', ' . date('d-m-Y h:i:s'),
        ]);
        $approv_batas_piutang->save($validated);
        //Create History
        $tanggal = date('Y-m-d');
        $datetime = date('Y-m-d H:i:s');
        $dokumen = $new_code;
        $form = 'Approval Batas Piutang';
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
        'submenu' => 'approv_batas_piutang',
        'submenu1' => 'ref_umum',
        'title' => 'Detail Approval Batas Piutang',
        // 'userdtl' => Userdtl::where('cmodule', 'Approval Batas Piutang')->where('username', $username)->first(),
      ];
      return response()->json([
        'body' => view('approv_batas_piutang.modaltambahmaster', [
          'saplikasi' => Saplikasi::where('aktif', 'Y')->first(),
          'approv_batas_piutang' => Approv_batas_piutang::where('id', $id)->first(),
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
        'submenu' => 'approv_batas_piutang',
        'submenu1' => 'ref_umum',
        'title' => 'Edit Data Approval Batas Piutang',
      ];
      // var_dump($data);

      // return response()->json([
      //     'data' => $data,
      // ]);
      return response()->json([
        'body' => view('approv_batas_piutang.modaltambahmaster', [
          'saplikasi' => Saplikasi::where('aktif', 'Y')->first(),
          'approv_batas_piutang' => Approv_batas_piutang::where('id', $id)->first(),
          // 'action' => route('approv_batas_piutang.update', $approv_batas_piutang->id),
          'action' => 'approv_batas_piutangupdate',
          'vdata' => $data,
        ])->render(),
        'data' => $data,
      ]);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function update(Request $request, approv_batas_piutang $approv_batas_piutang)
  {
    if ($request->Ajax()) {
      $id = $request->id;
      if ($request->noapprov === $request->noapprovlama) {
        $validate = $request->validate(
          [
            'noapprov' => 'required',
            'tglapprov' => 'required',
          ],
          [
            'noapprov.required' => 'No. harus di isi',
            'tglapprov.required' => 'Tanggal harus di isi',
          ],
        );
      } else {
        $validate = $request->validate(
          [
            'noapprov' => 'required|unique:approv_batas_piutang|max:255',
            'tglapprov' => 'required',
          ],
          [
            'noapprov.required' => 'No. harus di isi',
            'tglapprov.required' => 'Tanggal harus di isi',
          ],
        );
      }
      $approv_batas_piutang = Approv_batas_piutang::find($id);
      if ($validate) {
        $noapprov = $request->noapprov;
        $approv_batas_piutang->fill([
          'noapprov' => isset($request->noapprov) ? $request->noapprov : '',
          'tglapprov' => isset($request->tglapprov) ? $request->tglapprov : '',
          'nojual' => isset($request->nojual) ? $request->nojual : '',
          'tgljual' => isset($request->tgljual) ? $request->tgljual : '',
          'nmcustomer' => isset($request->nmcustomer) ? $request->nmcustomer : '',
          'total' => isset($request->total) ? $request->total : '',
          'keterangan' => isset($request->keterangan) ? $request->keterangan : '',
          'user' => 'Update-' . $request->username . ', ' . date('d-m-Y h:i:s'),
        ]);
        $approv_batas_piutang->save($validate);
        //Create History
        $tanggal = date('Y-m-d');
        $datetime = date('Y-m-d H:i:s');
        $dokumen = $noapprov;
        $form = 'Approval Batas Piutang';
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

  public function approv_batas_piutangproses(Request $request, approv_batas_piutang $approv_batas_piutangproses)
  {
    if ($request->Ajax()) {
      $id = $request->id;
      $approv_batas_piutang = Approv_batas_piutang::find($id);
      $approv_batas_piutang->fill([
        'proses' => 'Y',
        'user_proses' => 'Proses-' . session('username') . ', ' . date('d-m-Y h:i:s'),
      ]);
      $approv_batas_piutang->save();
      //Create History
      $approv_batas_piutang = Approv_batas_piutang::where('id', $request->id)->first();
      $tanggal = date('Y-m-d');
      $datetime = date('Y-m-d H:i:s');
      $dokumen = $approv_batas_piutang->noapprov;
      $form = 'Approval Batas Piutang';
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

  public function approv_batas_piutangbatalproses(approv_batas_piutang $approv_batas_piutang, Request $request)
  {
    if ($request->Ajax()) {
      $id = $_GET['id'];
      $data = [
        'menu' => 'transaksi',
        'submenu' => 'approv_batas_piutang',
        'submenu1' => 'ref_umum',
        'title' => 'Batal Proses Approval Batas Piutang',
      ];
      // var_dump($data);

      // return response()->json([
      //     'data' => $data,
      // ]);
      return response()->json([
        'body' => view('approv_batas_piutang.modalbatalproses', [
          'approv_batas_piutang' => Approv_batas_piutang::where('id', $id)->first(),
          // 'action' => route('so.update', $soh->id),
          'action' => 'approv_batas_piutangbatalprosesok',
          'vdata' => $data,
        ])->render(),
        'data' => $data,
      ]);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function approv_batas_piutangbatalprosesok(Request $request, approv_batas_piutang $approv_batas_piutang)
  {
    if ($request->Ajax()) {
      $id = $request->id;
      $user_proses = 'Proses-' . session('username') . ', ' . date('d-m-Y h:i:s');
      DB::table('approv_batas_piutang')->where('id', $id)->update(['proses' => 'N', 'user_proses' => $user_proses]);
      //Create History
      $approv_batas_piutang = Approv_batas_piutang::where('id', $id)->first();
      $noapprov = $approv_batas_piutang->noapprov;
      $tanggal = date('Y-m-d');
      $datetime = date('Y-m-d H:i:s');
      $dokumen = $noapprov;
      $form = 'Approval Batas Piutang';
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

  public function approv_batas_piutangcancel(Request $request, approv_batas_piutang $approv_batas_piutang)
  {
    if ($request->Ajax()) {
      $id = $request->id;
      $user_proses = 'Cancel-' . session('username') . ', ' . date('d-m-Y h:i:s');
      DB::table('approv_batas_piutang')->where('id', $id)->update(['batal' => 'Y', 'user_proses' => $user_proses]);
      //Create History
      $approv_batas_piutang = Approv_batas_piutang::where('id', $request->id)->first();
      $tanggal = date('Y-m-d');
      $datetime = date('Y-m-d H:i:s');
      $dokumen = $approv_batas_piutang->noapprov;
      $form = 'Approval Batas Piutang';
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

  public function approv_batas_piutangambil(Request $request, approv_batas_piutang $approv_batas_piutang)
  {
    if ($request->Ajax()) {
      $id = $request->id;
      $user_proses = 'Ambil-' . session('username') . ', ' . date('d-m-Y h:i:s');
      DB::table('approv_batas_piutang')->where('id', $id)->update(['batal' => 'N', 'user_proses' => $user_proses]);
      //Create History
      $approv_batas_piutang = Approv_batas_piutang::where('id', $request->id)->first();
      $tanggal = date('Y-m-d');
      $datetime = date('Y-m-d H:i:s');
      $dokumen = $approv_batas_piutang->noapprov;
      $form = 'Approval Batas Piutang';
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

  public function destroy(approv_batas_piutang $approv_batas_piutang, Request $request)
  {
    if ($request->Ajax()) {
      $id = $request->id;
      $approv_batas_piutang = Approv_batas_piutang::where('id', $id)->first();
      $deleted = DB::table('approv_batas_piutang')->where('id', $id)->delete();
      if ($deleted) {
        //Create History
        $tanggal = date('Y-m-d');
        $datetime = date('Y-m-d H:i:s');
        $dokumen = $approv_batas_piutang->noapprov;
        $form = 'Approval Batas Piutang';
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


  public function approv_batas_piutangcetak(Request $request)
  {
    $row = Approv_batas_piutang::where('id', $request->id)->first();
    $noapprov = $row->noapprov;
    $data = [
      'approv_batas_piutang' => $row,
    ];
    // return view('approv_batas_piutang.cetak', $data);

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
    $approv_batas_piutang = Approv_batas_piutang::where('id', $request->id)->first();
    $tanggal = date('Y-m-d');
    $datetime = date('Y-m-d H:i:s');
    $dokumen = $approv_batas_piutang->noapprov;
    $form = 'Approval Batas Piutang';
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
    $mpdf->WriteHTML(view('approv_batas_piutang.cetak', $data));
    $namafile = $noapprov . ' - ' . date('dmY H:i:s') . '.pdf';
    //return the PDF for download
    // return $mpdf->Output($request->get('name') . $namafile, Destination::DOWNLOAD);
    $mpdf->Output($namafile, 'I');
  }
}
