<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Tbbank;
use App\Models\Tbjnskartu;
use App\Models\Tbcustomer;
use App\Models\Tbsales;
use App\Models\Tbbarang;
use App\Models\Tbmultiprc;
use App\Models\Tbsupplier;
use App\Models\Tbklpcust;
use App\Models\Poh;
use App\Models\Jualh;
use App\Models\Belih;
use App\Models\Mohklruangh;
use App\Models\Kasir_tagihand;
use App\Models\Kasir_tunai;
use App\Models\Kasir_keluard;

// //return type View
// use Illuminate\View\View;

class CariController extends Controller
{
  public function cariklpcust(Request $request)
  {
    if ($request->Ajax()) {
      $data = [
        // 'menu' => 'transaksi',
        // 'submenu' => 'tbcustomer',
        // 'submenu1' => 'ref_umum',
        'title' => 'Cari Data Kelompok Customer',
      ];
      // var_dump($data);
      return response()->json([
        'body' => view('modalcari.modalcariklpcust', [
          'tbklpcust' => Tbklpcust::all(),
          'vdata' => $data,
        ])->render(),
        'data' => $data,
      ]);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function replklpcust(Request $request)
  {
    if ($request->Ajax()) {
      $kode = $request->kode; //$_GET['kode_bank'];
      $row = Tbklpcust::where('kode', $kode)->first();
      if (isset($row)) {
        $data = [
          'kdklpcust' => $row['kode'],
          'nmklpcust' => $row['nama'],
        ];
      } else {
        $data = [
          'kdklpcust' => '',
          'nmklpcust' => '',
        ];
      }
      echo json_encode($data);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function caritbbank(Request $request)
  {
    if ($request->Ajax()) {
      $data = [
        // 'menu' => 'transaksi',
        // 'submenu' => 'tbcustomer',
        // 'submenu1' => 'ref_umum',
        'title' => 'Cari Data bank',
      ];
      // var_dump($data);
      return response()->json([
        'body' => view('modalcari.modalcaribank', [
          'tbbank' => Tbbank::all(),
          'vdata' => $data,
        ])->render(),
        'data' => $data,
      ]);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function repltbbank(Request $request)
  {
    if ($request->Ajax()) {
      $kode = $request->kode; //$_GET['kode_bank'];
      $row = Tbbank::where('kode', $kode)->first();
      if (isset($row)) {
        $data = [
          'kdbank' => $row['kode'],
          'nmbank' => $row['nama'],
        ];
      } else {
        $data = [
          'kdbank' => '',
          'nmbank' => '',
        ];
      }
      echo json_encode($data);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function caritbjnskartu(Request $request)
  {
    if ($request->Ajax()) {
      $data = [
        // 'menu' => 'transaksi',
        // 'submenu' => 'tbcustomer',
        // 'submenu1' => 'ref_umum',
        'title' => 'Cari Data jnskartu',
      ];
      // var_dump($data);
      return response()->json([
        'body' => view('modalcari.modalcarijnskartu', [
          'tbjnskartu' => Tbjnskartu::all(),
          'vdata' => $data,
        ])->render(),
        'data' => $data,
      ]);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function repltbjnskartu(Request $request)
  {
    if ($request->Ajax()) {
      $kode = $request->kode; //$_GET['kode_jnskartu'];
      $row = Tbjnskartu::where('kode', $kode)->first();
      if (isset($row)) {
        $data = [
          'kdjnskartu' => $row['kode'],
          'nmjnskartu' => $row['nama'],
        ];
      } else {
        $data = [
          'kdjnskartu' => '',
          'nmjnskartu' => '',
        ];
      }
      echo json_encode($data);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function caritbbarang(Request $request)
  {
    if ($request->Ajax()) {
      $data = [
        // 'menu' => 'transaksi',
        // 'submenu' => 'tbcustomer',
        // 'submenu1' => 'ref_umum',
        'title' => 'Cari Data Barang',
      ];
      // var_dump($data);
      return response()->json([
        'body' => view('modalcari.modalcaritbbarang', [
          'tbbarang' => Tbbarang::all(),
          'vdata' => $data,
        ])->render(),
        'data' => $data,
      ]);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function repltbbarang(Request $request)
  {
    if ($request->Ajax()) {
      $kode = $request->kode_barang; //$_GET['kode_barang'];
      $row = Tbbarang::where('kode', $kode)->first();
      if (isset($row)) {
        $data = [
          'kdbarang' => $row['kode'],
          'nmbarang' => $row['nama'],
          'kdsatuan' => $row['kdsatuan'],
          'harga_jual' => $row['harga_jual'],
          'harga_beli' => $row['harga_beli'],
        ];
      } else {
        $data = [
          'kdbarang' => '',
          'nmbarang' => '',
          'kdsatuan' => '',
          'harga_jual' => 0,
          'harga_beli' => 0,
        ];
      }
      echo json_encode($data);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function caritbbarangbeli(Request $request)
  {
    if ($request->Ajax()) {
      $data = [
        // 'menu' => 'transaksi',
        // 'submenu' => 'tbcustomer',
        // 'submenu1' => 'ref_umum',
        'title' => 'Cari Data Barang',
      ];
      // var_dump($data);
      return response()->json([
        'body' => view('modalcari.modalcaritbbarangbeli', [
          'tbbarang' => Tbbarang::all(),
          'vdata' => $data,
        ])->render(),
        'data' => $data,
      ]);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function caritbmultiprc(Request $request)
  {
    if ($request->Ajax()) {
      $kdcustomer = $request->kode_customer;
      // $kdcustomer = $_GET['kode_customer'];
      // dd($kdcustomer);
      $data = [
        // 'menu' => 'transaksi',
        // 'submenu' => 'tbcustomer',
        // 'submenu1' => 'ref_umum',
        'title' => 'Cari Data Multi Price',
      ];
      // var_dump($data);
      return response()->json([
        'body' => view('modalcari.modalcaritbmultiprc', [
          // 'tbmultiprc' => Tbmultiprc::all(),
          'tbmultiprc' => Tbmultiprc::join('tbbarang', 'tbmultiprc.kdbarang', '=', 'tbbarang.kode')->where('tbmultiprc.kdcustomer', $kdcustomer)->get(),
          'vdata' => $data,
        ])->render(),
        'data' => $data,
      ]);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function repltbmultiprc(Request $request)
  {
    if ($request->Ajax()) {
      // $kode = $request->kode_barang; //$_GET['kode_multiprc'];
      // $row = DB::table('select tbmultiprc.kode,tbmultiprc.nama,tbmultiprc.kdsatuan,tbsatuan.nama as nmsatuan')->join('tbbarang', 'tbmultiprc.kdbarang', '=', 'tbbarang.kode')->where('tbmultiprc.kdbarang', $kode)->first();
      $row = Tbmultiprc::join('tbbarang', 'tbmultiprc.kdbarang', '=', 'tbbarang.kode')->where('tbmultiprc.kdcustomer', $request->kode_customer)->where('kdbarang', $request->kode_barang)->first();
      if (isset($row)) {
        $data = [
          'kdbarang' => $row['kdbarang'],
          'nmbarang' => $row['nmbarang'],
          'kdsatuan' => $row['kdsatuan'],
          'harga_jual' => $row['harga'],
        ];
      } else {
        $data = [
          'kdbarang' => '',
          'nmbarang' => '',
          'kdsatuan' => '',
          'harga_jual' => 0,
        ];
      }
      echo json_encode($data);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function caricustomer(Request $request)
  {
    if ($request->Ajax()) {
      $data = [
        // 'menu' => 'transaksi',
        // 'submenu' => 'customer',
        // 'submenu1' => 'ref_umum',
        'title' => 'Cari Data Customer',
      ];
      // var_dump($data);
      return response()->json([
        'body' => view('modalcari.modalcaricustomer', [
          'tbcustomer' => Tbcustomer::all(),
          'vdata' => $data,
        ])->render(),
        'data' => $data,
      ]);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }
  public function replcustomer(Request $request)
  {
    if ($request->Ajax()) {
      $kode = $_GET['kode'];
      $row = Tbcustomer::where('kode', $kode)->first();
      if (isset($row)) {
        $data = [
          'kdcustomer' => $row['kode'],
          'nmcustomer' => $row['nama'],
        ];
      } else {
        $data = [
          'kdcustomer' => '',
          'nmcustomer' => '',
        ];
      }
      echo json_encode($data);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function carisales(Request $request)
  {
    if ($request->Ajax()) {
      $data = [
        // 'menu' => 'transaksi',
        // 'submenu' => 'sales',
        // 'submenu1' => 'ref_umum',
        'title' => 'Cari Data Sales',
      ];
      // var_dump($data);
      return response()->json([
        'body' => view('modalcari.modalcarisales', [
          'tbsales' => Tbsales::all(),
          'vdata' => $data,
        ])->render(),
        'data' => $data,
      ]);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }
  public function replsales(Request $request)
  {
    if ($request->Ajax()) {
      $kode = $_GET['kode'];
      $row = Tbsales::where('kode', $kode)->first();
      if (isset($row)) {
        $data = [
          'kdsales' => $row['kode'],
          'nmsales' => $row['nama'],
        ];
      } else {
        $data = [
          'kdsales' => '',
          'nmsales' => '',
        ];
      }
      echo json_encode($data);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function carikurir(Request $request)
  {
    if ($request->Ajax()) {
      $data = [
        // 'menu' => 'transaksi',
        // 'submenu' => 'kurir',
        // 'submenu1' => 'ref_umum',
        'title' => 'Cari Data kurir',
      ];
      // var_dump($data);
      return response()->json([
        'body' => view('modalcari.modalcarikurir', [
          'tbkurir' => Tbsales::all(),
          'vdata' => $data,
        ])->render(),
        'data' => $data,
      ]);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }
  public function replkurir(Request $request)
  {
    if ($request->Ajax()) {
      $kode = $_GET['kode'];
      $row = Tbsales::where('kode', $kode)->first();
      if (isset($row)) {
        $data = [
          'kdkurir' => $row['kode'],
          'nmkurir' => $row['nama'],
        ];
      } else {
        $data = [
          'kdkurir' => '',
          'nmkurir' => '',
        ];
      }
      echo json_encode($data);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function carisupplierdetail(Request $request)
  {
    if ($request->Ajax()) {
      $data = [
        // 'menu' => 'transaksi',
        // 'submenu' => 'supplier',
        // 'submenu1' => 'ref_umum',
        'title' => 'Cari Data Supplier',
      ];
      // var_dump($data);
      return response()->json([
        'body' => view('modalcari.modalcarisupplierdetail', [
          'tbsupplier' => Tbsupplier::all(),
          'vdata' => $data,
        ])->render(),
        'data' => $data,
      ]);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function carisupplier(Request $request)
  {
    if ($request->Ajax()) {
      $data = [
        // 'menu' => 'transaksi',
        // 'submenu' => 'supplier',
        // 'submenu1' => 'ref_umum',
        'title' => 'Cari Data Supplier',
      ];
      // var_dump($data);
      return response()->json([
        'body' => view('modalcari.modalcarisupplier', [
          'tbsupplier' => Tbsupplier::all(),
          'vdata' => $data,
        ])->render(),
        'data' => $data,
      ]);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }
  public function replsupplier(Request $request)
  {
    if ($request->Ajax()) {
      $kode = $_GET['kode'];
      $row = Tbsupplier::where('kode', $kode)->first();
      if (isset($row)) {
        $data = [
          'kdsupplier' => $row['kode'],
          'nmsupplier' => $row['nama'],
        ];
      } else {
        $data = [
          'kdsupplier' => '',
          'nmsupplier' => '',
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
        'title' => 'Cari Penpoan',
      ];
      // var_dump($data);
      return response()->json([
        'body' => view('modalcari.modalcaripo', [
          'poh' => poh::where('proses', 'Y')->orderBy('nopo', 'desc')->get(),
          'vdata' => $data,
        ])->render(),
        'data' => $data,
      ]);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }
  public function replpo(Request $request)
  {
    if ($request->Ajax()) {
      $nopo = $request->nopo; //$_GET['kode_barang'];
      $row = poh::where('nopo', $nopo)->first();
      if (isset($row)) {
        $data = [
          'nopo' => $row['nopo'],
          'tglpo' => $row['tglpo'],
          'nmsupplier' => $row['nmsupplier'],
        ];
      } else {
        $data = [
          'nopo' => '',
          'tglpo' => '',
          'nmsupplier' => '',
        ];
      }
      echo json_encode($data);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function carijual(Request $request)
  {
    if ($request->Ajax()) {
      $data = [
        'title' => 'Cari Penjualan',
      ];
      // var_dump($data);
      return response()->json([
        'body' => view('modalcari.modalcarijual', [
          'jualh' => Jualh::where('proses', 'Y')->orderBy('nojual', 'desc')->get(),
          'vdata' => $data,
        ])->render(),
        'data' => $data,
      ]);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }
  public function repljual(Request $request)
  {
    if ($request->Ajax()) {
      $nojual = $request->kode; //$_GET['kode_barang'];
      $row = Jualh::where('nojual', $nojual)->first();
      if (isset($row)) {
        $data = [
          'nojual' => $row['nojual'],
          'tgljual' => $row['tgljual'],
          'total' => $row['total'],
          'nmcustomer' => $row['nmcustomer'],
        ];
      } else {
        $data = [
          'nojual' => '',
          'tgljual' => '',
          'total' => '0',
          'nmcustomer' => '',
        ];
      }
      echo json_encode($data);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function carijualpiutang(Request $request)
  {
    if ($request->Ajax()) {
      $data = [
        'title' => 'Cari Penjualan',
      ];
      // var_dump($data);
      return response()->json([
        'body' => view('modalcari.modalcarijual', [
          'jualh' => Jualh::where('proses', 'Y')->where('kurangbayar', '>', 0)->orderBy('nojual', 'desc')->get(),
          'vdata' => $data,
        ])->render(),
        'data' => $data,
      ]);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }
  public function repljualpiutang(Request $request)
  {
    if ($request->Ajax()) {
      $nojual = $request->kode; //$_GET['kode_barang'];
      $row = Jualh::where('nojual', $nojual)->where('kurangbayar', '>', 0)->first();
      if (isset($row)) {
        $data = [
          'nojual' => $row['nojual'],
          'tgljual' => $row['tgljual'],
          'piutang' => $row['kurangbayar'],
          'uang' => $row['kurangbayar'],
          'bayar' => $row['kurangbayar'],
          'kdcustomer' => $row['kdcustomer'],
          'nmcustomer' => $row['nmcustomer'],
        ];
      } else {
        $data = [
          'nojual' => '',
          'tgljual' => '',
          'piutang' => '0',
          'uang' => '0',
          'bayar' => '0',
          'kdcustomer' => '',
          'nmcustomer' => '',
        ];
      }
      echo json_encode($data);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function carimohklruang(Request $request)
  {
    if ($request->Ajax()) {
      $data = [
        'title' => 'Cari Permohonan Keluar Uang',
      ];
      return response()->json([
        'body' => view('modalcari.modalcarimohklruang', [
          'mohklruangh' => Mohklruangh::where('proses', 'Y')->where('kurang', '>', 0)->orderBy('nomohon', 'desc')->get(),
          'vdata' => $data,
        ])->render(),
        'data' => $data,
      ]);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }
  public function replmohklruang(Request $request)
  {
    if ($request->Ajax()) {
      $nomohon = $request->kode; //$_GET['kode_barang'];
      $row = Mohklruangh::where('nomohon', $nomohon)->where('kurang', '>', 0)->first();
      if (isset($row)) {
        $data = [
          'nomohon' => $row['nomohon'],
        ];
      } else {
        $data = [
          'nomohon' => '',
        ];
      }
      echo json_encode($data);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function caribeli(Request $request)
  {
    if ($request->Ajax()) {
      $data = [
        'title' => 'Cari Penerimaan Pembelian',
      ];
      return response()->json([
        'body' => view('modalcari.modalcaribeli', [
          'belih' => Belih::where('proses', 'Y')->where('kurangbayar', '>', 0)->orderBy('nobeli', 'desc')->get(),
          'vdata' => $data,
        ])->render(),
        'data' => $data,
      ]);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }
  public function replbeli(Request $request)
  {
    if ($request->Ajax()) {
      $nobeli = $request->kode; //$_GET['kode_barang'];
      $row = Belih::where('nobeli', $nobeli)->where('kurangbayar', '>', 0)->first();
      if (isset($row)) {
        $data = [
          'nodokumen' => $row['nobeli'],
          'tgldokumen' => $row['tglbeli'],
          'uang' => $row['kurangbayar'],
          'kdsupplier' => $row['kdsupplier'],
          'nmsupplier' => $row['nmsupplier'],
        ];
      } else {
        $data = [
          'nodokumen' => '',
          'tgldokumen' => '',
          'uang' => '0',
          'kdsupplier' => '',
          'nmsupplier' => '',
        ];
      }
      echo json_encode($data);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function tampilpembayaran(Request $request)
  {
    if ($request->Ajax()) {
      $cari = $_GET['cari'];
      $data = [
        'title' => 'Tampil Pembayaran ' . $cari,
      ];
      return response()->json([
        'body' => view('modalcari.modaltampilpembayaran', [
          // 'kasir_tagihand' => Kasir_tagihand::where('nojual', $cari)->orderBy('nokwitansi', 'desc')->get(),
          'kasir_tagihand' => DB::table('kasir_tagihand')->join('kasir_tagihan', 'kasir_tagihand.nokwitansi', '=', 'kasir_tagihan.nokwitansi')
            ->select('kasir_tagihand.*', 'kasir_tagihan.tglkwitansi')->where('kasir_tagihand.nojual', $cari)->get(),
          'kasir_tunai' => Kasir_tunai::where('proses', 'Y')->where('nojual', $cari)->orderBy('nokwitansi', 'desc')->get(),
          'vdata' => $data,
        ])->render(),
        'data' => $data,
      ]);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }

  public function tampilpembayaranhutang(Request $request)
  {
    if ($request->Ajax()) {
      $cari = $_GET['cari'];
      $data = [
        'title' => 'Tampil Pembayaran Hutang ' . $cari,
      ];
      return response()->json([
        'body' => view('modalcari.modaltampilpembayaranhutang', [
          'kasir_keluard' => Kasir_keluard::join('kasir_keluarh', 'kasir_keluard.nokwitansi', '=', 'kasir_keluarh.nokwitansi')
            ->select('kasir_keluard.*', 'kasir_keluarh.tglkwitansi', 'kasir_keluarh.carabayar')
            ->where('kasir_keluard.nodokumen', $cari)->where('kasir_keluarh.proses', 'Y')->orderBy('nokwitansi', 'desc')->get(),
          'vdata' => $data,
        ])->render(),
        'data' => $data,
      ]);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }
}
