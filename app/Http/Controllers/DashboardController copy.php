<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
//return type View
use Illuminate\View\View;

use App\Models\Userdtl;
use App\Models\Poh;
use App\Models\Belih;
use App\Models\Tbcustomer;
use App\Models\Jualh;
use App\Models\Kasir_tunai;
use App\Models\Kasir_tagihan;
use App\Models\Tbbarang;

class DashboardController extends Controller
{
  public function index1(): View
  {
    $data = [
      'menu' => '',
      'submenu' => '',
      'submenu1' => '',
      'title' => 'Dashboard',
    ];
    // dd($data);
    return view('/dashboard.index', $data);
  }

  public function index(Request $request) //: View
  {
    $username = session('username');
    $bulan = date('m');
    $tahun = isset($request->tahun_grafik) ? $request->tahun_grafik : date('Y'); //date('Y');
    // var_dump(session('tahun_grafik'));
    $categories = [];
    $categoriestahunan = [];
    $totaljualtahunan = [];
    $totalbelitahunan = [];
    $totaljual = [];
    $totalbeli = [];
    $totalpiutang = [];
    $datajualtahunan = Jualh::selectRaw('year(tgljual) year, monthname(tgljual) monthname,month(tgljual) monthn, count(*) data')
      ->where('proses', 'Y') //->whereMonth('tgljual', '>', 8)
      ->whereYear('tgljual', $tahun)
      ->groupBy('year', 'monthname')
      ->orderBy('monthn', 'asc')
      ->get();
    foreach ($datajualtahunan as $dj) {
      $totaljualtahunan[] = (Jualh::where('proses', 'Y')->whereMonth('tgljual', $dj->monthn)->whereYear('tgljual', $tahun)->sum('total')) / 1000000;
    }
    $datajual = Jualh::selectRaw('year(tgljual) year, monthname(tgljual) monthname,month(tgljual) monthn, count(*) data')
      ->where('proses', 'Y') //->whereMonth('tgljual', '>', 8)
      ->groupBy('year', 'monthname')
      ->orderBy('monthn', 'asc')
      ->get();
    foreach ($datajual as $dj) {
      $totaljual[] = (Jualh::where('proses', 'Y')->whereMonth('tgljual', $dj->monthn)->sum('total')) / 1000000;
    }
    $databelitahunan = Belih::selectRaw('year(tglbeli) year, monthname(tglbeli) monthname,month(tglbeli) monthn, count(*) data')
      ->where('proses', 'Y') //->whereMonth('tglbeli', '>', 8)
      ->whereYear('tglbeli', $tahun)
      ->groupBy('year', 'monthname')
      ->orderBy('monthn', 'asc')
      ->get();
    foreach ($databelitahunan as $dj) {
      $categoriestahunan[] = $dj->monthname;
      $totalbelitahunan[] = (Belih::where('proses', 'Y')->whereMonth('tglbeli', $dj->monthn)->whereYear('tglbeli', $tahun)->sum('total')) / 1000000;
    }
    $databeli = Belih::selectRaw('year(tglbeli) year, monthname(tglbeli) monthname,month(tglbeli) monthn, count(*) data')
      ->where('proses', 'Y') //->whereMonth('tglbeli', '>', 8)
      ->whereYear('tglbeli', $tahun)
      ->groupBy('year', 'monthname')
      ->orderBy('monthn', 'asc')
      ->get();
    foreach ($databeli as $dj) {
      $categories[] = $dj->monthname;
      $totalbeli[] = (Belih::where('proses', 'Y')->whereMonth('tglbeli', $dj->monthn)->whereYear('tglbeli', $tahun)->sum('total')) / 1000000;
    }
    //piutang
    foreach ($datajualtahunan as $dj) {
      $jual = Jualh::where('proses', 'Y')->whereMonth('tgljual', '<=', $dj->monthn)
        // ->whereYear('tgljual', '=', $dj->year)
        ->whereYear('tgljual', $tahun)
        ->sum('total');
      $tunai = Kasir_tunai::where('proses', 'Y')->whereMonth('tglkwitansi', '<=', $dj->monthn)
        // ->whereYear('tglkwitansi', '=', $dj->year)
        ->whereYear('tglkwitansi', $tahun)
        ->sum('bayar');
      $tagihan = Kasir_tagihan::where('proses', 'Y')->whereMonth('tglkwitansi', '<=', $dj->monthn)
        // ->whereYear('tglkwitansi', '=', $dj->year)
        ->whereYear('tglkwitansi', $tahun)
        ->sum('total');
      $totalpiutang[] = ($jual - ($tunai + $tagihan)) / 1000000;
    }
    $pakaijual = Userdtl::where('cmodule', 'Penjualan')->where('username', $username)->first();
    $pakaipo = Userdtl::where('cmodule', 'Purchase Order')->where('username', $username)->first();
    $pakaibeli = Userdtl::where('cmodule', 'Penerimaan Pembelian')->where('username', $username)->first();
    $pakaicustomer = Userdtl::where('cmodule', 'Tabel Customer')->where('username', $username)->first();
    $data = [
      'menu' => '',
      'submenu' => '',
      'submenu1' => '',
      'title' => 'Home',
      'userdtl' => Userdtl::join('tbmodule', 'userdtl.cmodule', '=', 'tbmodule.cmodule')->where('userdtl.pakai', '1')->where('username', $username)->orderBy('userdtl.nurut')->get(),
      'userdtlmenu' => Userdtl::join('tbmodule', 'userdtl.cmodule', '=', 'tbmodule.cmodule')->where('userdtl.pakai', '1')->where('username', $username)->orderBy('userdtl.nurut')->get(),
      'jumlah_jual' => Jualh::where('proses', 'Y')->sum('total'),
      'jumlah_beli' => Belih::where('proses', 'Y')->sum('total'),
      'jumlah_piutang' => Jualh::where('proses', 'Y')->sum('kurangbayar'),
      'jumlah_hutang' => Belih::where('proses', 'Y')->sum('kurangbayar'),
      'jumlah_jual_bln' => Jualh::where('proses', 'Y')->whereMonth('tgljual', $bulan)->whereYear('tgljual', $tahun)->sum('total'),
      'jumlah_beli_bln' => Belih::where('proses', 'Y')->whereMonth('tglbeli', $bulan)->whereYear('tglbeli', $tahun)->sum('total'),
      'jumlah_customer' => Tbcustomer::count(),
      'jumlah_outstandingpo' => Poh::where('terima', 'N')->count(),
      'tbbarang' => Tbbarang::select('*')->orderBy('kode')->get(),
      'categories' => $categories,
      'categoriestahunan' => $categoriestahunan,
      'totaljual' => $totaljual,
      'totalbeli' => $totalbeli,
      'totalpiutang' => $totalpiutang,
      'totaljualtahunan' => $totaljualtahunan,
      'totalbelitahunan' => $totalbelitahunan,
      'tahun_grafik' => $tahun,
      'pakaibeli' => $pakaibeli->pakai,
      'pakaipo' => $pakaipo->pakai,
      'pakaijual' => $pakaijual->pakai,
      'pakaicustomer' => $pakaicustomer->pakai,
    ];
    // var_dump($data);
    return view('dashboard.index', $data);
  }

  public function grafik(Request $request)
  {
    if ($request->Ajax()) {
      $username = session('username');
      $bulan = date('m');
      $tahun = isset($request->tahun_grafik) ? $request->tahun_grafik : date('Y'); //date('Y');
      $categories = [];
      $categoriestahunan = [];
      $totaljualtahunan = [];
      $totalbelitahunan = [];
      $totaljual = [];
      $totalbeli = [];
      $totalpiutang = [];
      $datajualtahunan = Jualh::selectRaw('year(tgljual) year, monthname(tgljual) monthname,month(tgljual) monthn, count(*) data')
        ->where('proses', 'Y') //->whereMonth('tgljual', '>', 8)
        ->whereYear('tgljual', $tahun)
        ->groupBy('year', 'monthname')
        ->orderBy('monthn', 'asc')
        ->get();
      foreach ($datajualtahunan as $dj) {
        $totaljualtahunan[] = (Jualh::where('proses', 'Y')->whereMonth('tgljual', $dj->monthn)->whereYear('tgljual', $tahun)->sum('total')) / 1000000;
      }
      $datajual = Jualh::selectRaw('year(tgljual) year, monthname(tgljual) monthname,month(tgljual) monthn, count(*) data')
        ->where('proses', 'Y') //->whereMonth('tgljual', '>', 8)
        ->groupBy('year', 'monthname')
        ->orderBy('monthn', 'asc')
        ->get();
      foreach ($datajual as $dj) {
        $totaljual[] = (Jualh::where('proses', 'Y')->whereMonth('tgljual', $dj->monthn)->sum('total')) / 1000000;
      }
      $databelitahunan = Belih::selectRaw('year(tglbeli) year, monthname(tglbeli) monthname,month(tglbeli) monthn, count(*) data')
        ->where('proses', 'Y') //->whereMonth('tglbeli', '>', 8)
        ->whereYear('tglbeli', $tahun)
        ->groupBy('year', 'monthname')
        ->orderBy('monthn', 'asc')
        ->get();
      foreach ($databelitahunan as $dj) {
        $categoriestahunan[] = $dj->monthname;
        $totalbelitahunan[] = (Belih::where('proses', 'Y')->whereMonth('tglbeli', $dj->monthn)->whereYear('tglbeli', $tahun)->sum('total')) / 1000000;
      }
      $databeli = Belih::selectRaw('year(tglbeli) year, monthname(tglbeli) monthname,month(tglbeli) monthn, count(*) data')
        ->where('proses', 'Y') //->whereMonth('tglbeli', '>', 8)
        ->whereYear('tglbeli', $tahun)
        ->groupBy('year', 'monthname')
        ->orderBy('monthn', 'asc')
        ->get();
      foreach ($databeli as $dj) {
        $categories[] = $dj->monthname;
        $totalbeli[] = (Belih::where('proses', 'Y')->whereMonth('tglbeli', $dj->monthn)->whereYear('tglbeli', $tahun)->sum('total')) / 1000000;
      }
      //piutang
      foreach ($datajualtahunan as $dj) {
        $jual = Jualh::where('proses', 'Y')->whereMonth('tgljual', '<=', $dj->monthn)
          // ->whereYear('tgljual', '=', $dj->year)
          ->whereYear('tgljual', $tahun)
          ->sum('total');
        $tunai = Kasir_tunai::where('proses', 'Y')->whereMonth('tglkwitansi', '<=', $dj->monthn)
          // ->whereYear('tglkwitansi', '=', $dj->year)
          ->whereYear('tglkwitansi', $tahun)
          ->sum('bayar');
        $tagihan = Kasir_tagihan::where('proses', 'Y')->whereMonth('tglkwitansi', '<=', $dj->monthn)
          // ->whereYear('tglkwitansi', '=', $dj->year)
          ->whereYear('tglkwitansi', $tahun)
          ->sum('total');
        $totalpiutang[] = ($jual - ($tunai + $tagihan)) / 1000000;
      }
      $data = [
        'menu' => '',
        'submenu' => '',
        'submenu1' => '',
        'title' => 'Home',
        'userdtlmenu' => Userdtl::join('tbmodule', 'userdtl.cmodule', '=', 'tbmodule.cmodule')->where('userdtl.pakai', '1')->where('username', $username)->orderBy('userdtl.nurut')->get(),
        'jumlah_jual' => Jualh::where('proses', 'Y')->sum('total'),
        'jumlah_beli' => Belih::where('proses', 'Y')->sum('total'),
        'jumlah_piutang' => Jualh::where('proses', 'Y')->where('kurangbayar', '>', 0)->sum('total'),
        'jumlah_hutang' => Belih::where('proses', 'Y')->where('kurangbayar', '>', 0)->sum('total'),
        'jumlah_jual_bln' => Jualh::where('proses', 'Y')->whereMonth('tgljual', $bulan)->whereYear('tgljual', $tahun)->sum('total'),
        'jumlah_beli_bln' => Belih::where('proses', 'Y')->whereMonth('tglbeli', $bulan)->whereYear('tglbeli', $tahun)->sum('total'),
        'jumlah_customer' => Tbcustomer::count(),
        'jumlah_outstandingpo' => Poh::where('terima', 'N')->count(),
        'tbbarang' => Tbbarang::select('*')->orderBy('kode')->get(),
        'categories' => $categories,
        'categoriestahunan' => $categoriestahunan,
        'totaljual' => $totaljual,
        'totalbeli' => $totalbeli,
        'totalpiutang' => $totalpiutang,
        'totaljualtahunan' => $totaljualtahunan,
        'totalbelitahunan' => $totalbelitahunan,
        'tahun_grafik' => $tahun,
      ];
      return response()->json([
        'data' => $data,
      ]);
      // $msg = [
      //   'data' => view('dashboard/grafik', $data),
      // ];
      // echo json_encode($msg);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }
}
