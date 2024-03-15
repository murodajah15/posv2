<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Saplikasi;
use App\Models\Userdtl;
use App\Models\Jualh;
use App\Models\Opnameh;
use App\Models\User;

class ReportController extends Controller
{
    public function rfaktur(Request $request) //: View
    {
        $username = session('username');
        $data = [
            'menu' => 'report',
            'submenu' => 'rfaktur',
            'submenu1' => 'ref_umum',
            'title' => 'Laporan Faktur Harian',
            'userdtlmenu' => Userdtl::join('tbmodule', 'userdtl.cmodule', '=', 'tbmodule.cmodule')->where('userdtl.pakai', '1')->where('username', $username)->orderBy('userdtl.nurut')->get(),
            'userdtl' => Userdtl::where('cmodule', 'Laporan Faktur Harian')->where('username', $username)->first(),
        ];
        $userdtl = Userdtl::where('cmodule', 'Laporan Faktur Harian')->where('username', $username)->first();
        if ($userdtl->pakai == '1') {
            return view('report.rfaktur')->with($data);
        } else {
            return redirect('home');
        }
    }
    public function rfaktur_xls(Request $request) //: View
    {
        $username = session('username');
        $data = [
            'menu' => 'report',
            'submenu' => 'rfaktur',
            'submenu1' => 'ref_umum',
            'title' => 'Laporan Faktur Harian',
            'saplikasi' => Saplikasi::where('aktif', 'Y')->first(),
            'jualh' => Jualh::where('proses', 'Y')->where('tgljual', '>=', $request->tanggal1)->where('tgljual', '<=', $request->tanggal2)->get(),
            'recjualh' => Jualh::where('proses', 'Y')->where('tgljual', '>=', $request->tanggal1)->where('tgljual', '<=', $request->tanggal2)->count(),
            'userdtlmenu' => Userdtl::join('tbmodule', 'userdtl.cmodule', '=', 'tbmodule.cmodule')->where('userdtl.pakai', '1')->where('username', $username)->orderBy('userdtl.nurut')->get(),
            'userdtl' => Userdtl::where('cmodule', 'Laporan Faktur Harian')->where('username', $username)->first(),
            'semuaperiode' => isset($request->semuaperiode) ? 'Y' : 'N',
            'tanggal1' => $request->tanggal1,
            'tanggal2' => $request->tanggal2,
        ];
        $userdtl = Userdtl::where('cmodule', 'Laporan Faktur Harian')->where('username', $username)->first();
        if ($userdtl->pakai == '1') {
            return view('report.rfaktur_xls')->with($data);
        } else {
            return redirect('home');
        }
    }
    public function rfaktur_export(Request $request) //: View
    {
        $username = session('username');
        $data = [
            'menu' => 'report',
            'submenu' => 'rfaktur',
            'submenu1' => 'ref_umum',
            'title' => 'Laporan Faktur Harian',
            'saplikasi' => Saplikasi::where('aktif', 'Y')->first(),
            'jualh' => Jualh::where('proses', 'Y')->where('tgljual', '>=', $request->tanggal1)->where('tgljual', '<=', $request->tanggal2)->get(),
            'recjualh' => Jualh::where('proses', 'Y')->where('tgljual', '>=', $request->tanggal1)->where('tgljual', '<=', $request->tanggal2)->count(),
            'userdtlmenu' => Userdtl::join('tbmodule', 'userdtl.cmodule', '=', 'tbmodule.cmodule')->where('userdtl.pakai', '1')->where('username', $username)->orderBy('userdtl.nurut')->get(),
            'userdtl' => Userdtl::where('cmodule', 'Laporan Faktur Harian')->where('username', $username)->first(),
            'semuaperiode' => isset($request->semuaperiode) ? 'Y' : 'N',
            'tanggal1' => $request->tanggal1,
            'tanggal2' => $request->tanggal2,
        ];
        $userdtl = Userdtl::where('cmodule', 'Laporan Faktur Harian')->where('username', $username)->first();
        if ($userdtl->pakai == '1') {
            return view('report.rfaktur_export')->with($data);
        } else {
            return redirect('home');
        }
    }

    public function rso(Request $request) //: View
    {
        $username = session('username');
        $data = [
            'menu' => 'report',
            'submenu' => 'rso',
            'submenu1' => 'ref_umum',
            'title' => 'Laporan Sales Order',
            'userdtlmenu' => Userdtl::join('tbmodule', 'userdtl.cmodule', '=', 'tbmodule.cmodule')->where('userdtl.pakai', '1')->where('username', $username)->orderBy('userdtl.nurut')->get(),
            'userdtl' => Userdtl::where('cmodule', 'Laporan Sales Order')->where('username', $username)->first(),
        ];
        $userdtl = Userdtl::where('cmodule', 'Laporan Sales Order')->where('username', $username)->first();
        if ($userdtl->pakai == '1') {
            return view('report.rso')->with($data);
        } else {
            return redirect('home');
        }
    }
    public function rso_xls(Request $request) //: View
    {
        $username = session('username');
        $data = [
            'menu' => 'report',
            'submenu' => 'rso',
            'submenu1' => 'ref_umum',
            'title' => 'Laporan Sales Order',
            'saplikasi' => Saplikasi::where('aktif', 'Y')->first(),
            // 'jualh' => Jualh::where('proses', 'Y')->where('tgljual', '>=', $request->tanggal1)->where('tgljual', '<=', $request->tanggal2)->get(),
            // 'recjualh' => Jualh::where('proses', 'Y')->where('tgljual', '>=', $request->tanggal1)->where('tgljual', '<=', $request->tanggal2)->count(),
            'userdtlmenu' => Userdtl::join('tbmodule', 'userdtl.cmodule', '=', 'tbmodule.cmodule')->where('userdtl.pakai', '1')->where('username', $username)->orderBy('userdtl.nurut')->get(),
            'userdtl' => Userdtl::where('cmodule', 'Laporan Sales Order')->where('username', $username)->first(),
            'outstanding' => isset($request->outstanding) ? 'Y' : 'N',
            'semuaperiode' => isset($request->semuaperiode) ? 'Y' : 'N',
            'tanggal1' => $request->tanggal1,
            'tanggal2' => $request->tanggal2,
            'semuacustomer' => isset($request->semuacustomer) ? 'Y' : 'N',
            'kdcustomer' => isset($request->kdcustomer) ? $request->kdcustomer : '',
            'nmcustomer' => isset($request->nmcustomer) ? $request->nmcustomer : '',
            'semuasales' => isset($request->semuasales) ? 'Y' : 'N',
            'kdsales' => isset($request->kdsales) ? $request->kdsales : '',
            'nmsales' => isset($request->nmsales) ? $request->nmsales : '',
        ];
        $userdtl = Userdtl::where('cmodule', 'Laporan Sales Order')->where('username', $username)->first();
        if ($userdtl->pakai == '1') {
            return view('report.rso_xls')->with($data);
        } else {
            return redirect('home');
        }
    }
    public function rso_export(Request $request) //: View
    {
        $username = session('username');
        $data = [
            'menu' => 'report',
            'submenu' => 'rso',
            'submenu1' => 'ref_umum',
            'title' => 'Laporan Sales Order',
            'saplikasi' => Saplikasi::where('aktif', 'Y')->first(),
            // 'jualh' => Jualh::where('proses', 'Y')->where('tgljual', '>=', $request->tanggal1)->where('tgljual', '<=', $request->tanggal2)->get(),
            // 'recjualh' => Jualh::where('proses', 'Y')->where('tgljual', '>=', $request->tanggal1)->where('tgljual', '<=', $request->tanggal2)->count(),
            'userdtlmenu' => Userdtl::join('tbmodule', 'userdtl.cmodule', '=', 'tbmodule.cmodule')->where('userdtl.pakai', '1')->where('username', $username)->orderBy('userdtl.nurut')->get(),
            'userdtl' => Userdtl::where('cmodule', 'Laporan Sales Order')->where('username', $username)->first(),
            'outstanding' => $request->outstanding,
            'semuaperiode' => $request->semuaperiode,
            'tanggal1' => $request->tanggal1,
            'tanggal2' => $request->tanggal2,
            'semuacustomer' => $request->semuacustomer,
            'kdcustomer' => $request->kdcustomer,
            'nmcustomer' => $request->nmcustomer,
            'semuasales' => $request->semuasales,
            'kdsales' => $request->kdsales,
            'nmsales' => $request->nmsales,
        ];
        $userdtl = Userdtl::where('cmodule', 'Laporan Sales Order')->where('username', $username)->first();
        if ($userdtl->pakai == '1') {
            return view('report.rso_export')->with($data);
        } else {
            return redirect('home');
        }
    }

    public function rjual(Request $request) //: View
    {
        $username = session('username');
        $data = [
            'menu' => 'report',
            'submenu' => 'rjual',
            'submenu1' => 'ref_umum',
            'title' => 'Laporan Penjualan',
            'userdtlmenu' => Userdtl::join('tbmodule', 'userdtl.cmodule', '=', 'tbmodule.cmodule')->where('userdtl.pakai', '1')->where('username', $username)->orderBy('userdtl.nurut')->get(),
            'userdtl' => Userdtl::where('cmodule', 'Laporan Penjualan')->where('username', $username)->first(),
        ];
        $userdtl = Userdtl::where('cmodule', 'Laporan Penjualan')->where('username', $username)->first();
        if ($userdtl->pakai == '1') {
            return view('report.rjual')->with($data);
        } else {
            return redirect('home');
        }
    }
    public function rjual_xls(Request $request) //: View
    {
        $username = session('username');
        $data = [
            'menu' => 'report',
            'submenu' => 'rjual',
            'submenu1' => 'ref_umum',
            'title' => 'Laporan Penjualan',
            'saplikasi' => Saplikasi::where('aktif', 'Y')->first(),
            'userdtlmenu' => Userdtl::join('tbmodule', 'userdtl.cmodule', '=', 'tbmodule.cmodule')->where('userdtl.pakai', '1')->where('username', $username)->orderBy('userdtl.nurut')->get(),
            'userdtl' => Userdtl::where('cmodule', 'Laporan Penjualan')->where('username', $username)->first(),
            'tanggal1' => $request->tanggal1,
            'tanggal2' => $request->tanggal2,
            'semuaperiode' => isset($request->semuaperiode) ? 'Y' : 'N',
            'semuabarang' => isset($request->semuabarang) ? 'Y' : 'N',
            'semuacustomer' => isset($request->semuacustomer) ? 'Y' : 'N',
            'semuasales' => isset($request->semuasales) ? 'Y' : 'N',
            'semuaklpcust' => isset($request->semuaklpcust) ? 'Y' : 'N',
            'rincian' => isset($request->rincian) ? 'Y' : 'N',
            'groupingcustomer' => isset($request->groupingcustomer) ? 'Y' : 'N',
            'kdbarang' => isset($request->kdbarang) ? $request->kdbarang : '',
            'nmbarang' => isset($request->nmbarang) ? $request->nmbarang : '',
            'kdcustomer' => isset($request->kdcustomer) ? $request->kdcustomer : '',
            'nmcustomer' => isset($request->nmcustomer) ? $request->nmcustomer : '',
            'kdsales' => isset($request->kdsales) ? $request->kdsales : '',
            'nmsales' => isset($request->nmsales) ? $request->nmsales : '',
            'kdklpcust' => isset($request->kdklpcust) ? $request->kdklpcust : '',
            'nmklpcust' => isset($request->nmklpcust) ? $request->nmklpcust : '',
            'pilihanppn' => isset($request->pilihanppn) ? $request->pilihanppn : '',
        ];
        $userdtl = Userdtl::where('cmodule', 'Laporan Penjualan')->where('username', $username)->first();
        if ($userdtl->pakai == '1') {
            return view('report.rjual_xls')->with($data);
        } else {
            return redirect('home');
        }
    }
    public function rjual_export(Request $request) //: View
    {
        $username = session('username');
        $data = [
            'menu' => 'report',
            'submenu' => 'rjual',
            'submenu1' => 'ref_umum',
            'title' => 'Laporan Penjualan',
            'saplikasi' => Saplikasi::where('aktif', 'Y')->first(),
            'userdtlmenu' => Userdtl::join('tbmodule', 'userdtl.cmodule', '=', 'tbmodule.cmodule')->where('userdtl.pakai', '1')->where('username', $username)->orderBy('userdtl.nurut')->get(),
            'userdtl' => Userdtl::where('cmodule', 'Laporan Penjualan')->where('username', $username)->first(),
            'tanggal1' => $request->tanggal1,
            'tanggal2' => $request->tanggal2,
            'semuaperiode' => $request->semuaperiode,
            'rincian' => $request->rincian,
            'semuabarang' => $request->semuabarang,
            'kdbarang' => $request->kdbarang,
            'nmbarang' => $request->nmbarang,
            'semuacustomer' => $request->semuacustomer,
            'kdcustomer' => $request->kdcustomer,
            'nmcustomer' => $request->nmcustomer,
            'semuasales' => $request->semuasales,
            'kdsales' => $request->kdsales,
            'nmsales' => $request->nmsales,
            'semuaklpcust' => $request->semuaklpcust,
            'kdklpcust' => $request->kdklpcust,
            'nmklpcust' => $request->nmklpcust,
            'groupingcustomer' => $request->groupingcustomer,
            'pilihanppn' => $request->pilihanppn,
        ];
        $userdtl = Userdtl::where('cmodule', 'Laporan Penjualan')->where('username', $username)->first();
        if ($userdtl->pakai == '1') {
            return view('report.rjual_export')->with($data);
        } else {
            return redirect('home');
        }
    }

    public function rrating(Request $request) //: View
    {
        $username = session('username');
        $data = [
            'menu' => 'report',
            'submenu' => 'rrating',
            'submenu1' => 'ref_umum',
            'title' => 'Laporan Rating Penjualan',
            'userdtlmenu' => Userdtl::join('tbmodule', 'userdtl.cmodule', '=', 'tbmodule.cmodule')->where('userdtl.pakai', '1')->where('username', $username)->orderBy('userdtl.nurut')->get(),
            'userdtl' => Userdtl::where('cmodule', 'Laporan Rating Penjualan')->where('username', $username)->first(),
        ];
        $userdtl = Userdtl::where('cmodule', 'Laporan Rating Penjualan')->where('username', $username)->first();
        if ($userdtl->pakai == '1') {
            return view('report.rrating')->with($data);
        } else {
            return redirect('home');
        }
    }

    public function rrating_xls(Request $request) //: View
    {
        $username = session('username');
        $data = [
            'menu' => 'report',
            'submenu' => 'rrating',
            'submenu1' => 'ref_umum',
            'title' => 'Laporan Rating Penjualan',
            'saplikasi' => Saplikasi::where('aktif', 'Y')->first(),
            'userdtlmenu' => Userdtl::join('tbmodule', 'userdtl.cmodule', '=', 'tbmodule.cmodule')->where('userdtl.pakai', '1')->where('username', $username)->orderBy('userdtl.nurut')->get(),
            'userdtl' => Userdtl::where('cmodule', 'Laporan Rating Penjualan')->where('username', $username)->first(),
            'tanggal1' => $request->tanggal1,
            'tanggal2' => $request->tanggal2,
            'semuaperiode' => isset($request->semuaperiode) ? 'Y' : 'N',
            'urutqtydesc' => isset($request->urutqtydesc) ? 'Y' : 'N',
        ];
        $userdtl = Userdtl::where('cmodule', 'Laporan Rating Penjualan')->where('username', $username)->first();
        if ($userdtl->pakai == '1') {
            return view('report.rrating_xls')->with($data);
        } else {
            return redirect('home');
        }
    }

    public function rrating_export(Request $request) //: View
    {
        $username = session('username');
        $data = [
            'menu' => 'report',
            'submenu' => 'rrating',
            'submenu1' => 'ref_umum',
            'title' => 'Laporan Rating Penjualan',
            'saplikasi' => Saplikasi::where('aktif', 'Y')->first(),
            'userdtlmenu' => Userdtl::join('tbmodule', 'userdtl.cmodule', '=', 'tbmodule.cmodule')->where('userdtl.pakai', '1')->where('username', $username)->orderBy('userdtl.nurut')->get(),
            'userdtl' => Userdtl::where('cmodule', 'Laporan Rating Penjualan')->where('username', $username)->first(),
            'tanggal1' => $request->tanggal1,
            'tanggal2' => $request->tanggal2,
            'semuaperiode' => $request->semuaperiode,
            'urutqtydesc' => $request->urutqtydesc,
        ];
        $userdtl = Userdtl::where('cmodule', 'Laporan Rating Penjualan')->where('username', $username)->first();
        if ($userdtl->pakai == '1') {
            return view('report.rrating_export')->with($data);
        } else {
            return redirect('home');
        }
    }

    public function rpo(Request $request) //: View
    {
        $username = session('username');
        $data = [
            'menu' => 'report',
            'submenu' => 'rpo',
            'submenu1' => 'ref_umum',
            'title' => 'Laporan Purchase Order',
            'userdtlmenu' => Userdtl::join('tbmodule', 'userdtl.cmodule', '=', 'tbmodule.cmodule')->where('userdtl.pakai', '1')->where('username', $username)->orderBy('userdtl.nurut')->get(),
            'userdtl' => Userdtl::where('cmodule', 'Laporan Purchase Order')->where('username', $username)->first(),
        ];
        $userdtl = Userdtl::where('cmodule', 'Laporan Purchase Order')->where('username', $username)->first();
        if (isset($userdtl)) {
            if ($userdtl->pakai == '1') {
                return view('report.rpo')->with($data);
            } else {
                return redirect('home');
            }
        } else {
            return redirect('home');
        }
    }

    public function rpo_xls(Request $request) //: View
    {
        $username = session('username');
        $data = [
            'menu' => 'report',
            'submenu' => 'rpo',
            'submenu1' => 'ref_umum',
            'title' => 'Laporan Purchase Order',
            'saplikasi' => Saplikasi::where('aktif', 'Y')->first(),
            'userdtlmenu' => Userdtl::join('tbmodule', 'userdtl.cmodule', '=', 'tbmodule.cmodule')->where('userdtl.pakai', '1')->where('username', $username)->orderBy('userdtl.nurut')->get(),
            'userdtl' => Userdtl::where('cmodule', 'Laporan Purchase Order')->where('username', $username)->first(),
            'tanggal1' => $request->tanggal1,
            'tanggal2' => $request->tanggal2,
            'semuaperiode' => isset($request->semuaperiode) ? 'Y' : 'N',
            'outstanding' => isset($request->outstanding) ? 'Y' : 'N',
            'rincian' => isset($request->rincian) ? 'Y' : 'N',
            'semuabarang' => isset($request->semuabarang) ? 'Y' : 'N',
            'kdbarang' => isset($request->kdbarang) ? $request->kdbarang : '',
            'nmbarang' => isset($request->nmbarang) ? $request->nmbarang : '',
            'semuasupplier' => isset($request->semuasupplier) ? 'Y' : 'N',
            'kdsupplier' => isset($request->kdsupplier) ? $request->kdsupplier : '',
            'nmsupplier' => isset($request->nmsupplier) ? $request->nmsupplier : '',
            'groupingsupplier' => isset($request->groupingsupplier) ? 'Y' : 'N',
        ];
        $userdtl = Userdtl::where('cmodule', 'Laporan Purchase Order')->where('username', $username)->first();
        if (isset($userdtl)) {
            if ($userdtl->pakai == '1') {
                return view('report.rpo_xls')->with($data);
            } else {
                return redirect('home');
            }
        } else {
            return redirect('home');
        }
    }

    public function rpo_export(Request $request) //: View
    {
        $username = session('username');
        $data = [
            'menu' => 'report',
            'submenu' => 'rpo',
            'submenu1' => 'ref_umum',
            'title' => 'Laporan Purchase Order',
            'saplikasi' => Saplikasi::where('aktif', 'Y')->first(),
            'userdtlmenu' => Userdtl::join('tbmodule', 'userdtl.cmodule', '=', 'tbmodule.cmodule')->where('userdtl.pakai', '1')->where('username', $username)->orderBy('userdtl.nurut')->get(),
            'userdtl' => Userdtl::where('cmodule', 'Laporan Purchase Order')->where('username', $username)->first(),
            'tanggal1' => $request->tanggal1,
            'tanggal2' => $request->tanggal2,
            'semuaperiode' => $request->semuaperiode,
            'outstanding' => $request->outstanding,
            'rincian' => $request->rincian,
            'semuabarang' => $request->semuabarang,
            'kdbarang' => $request->kdbarang,
            'nmbarang' => $request->nmbarang,
            'semuasupplier' => $request->semuasupplier,
            'kdsupplier' => $request->kdsupplier,
            'nmsupplier' => $request->nmsupplier,
            'groupingsupplier' => $request->groupingsupplier,
        ];
        $userdtl = Userdtl::where('cmodule', 'Laporan Purchase Order')->where('username', $username)->first();
        if (isset($userdtl)) {
            if ($userdtl->pakai == '1') {
                return view('report.rpo_export')->with($data);
            } else {
                return redirect('home');
            }
        } else {
            return redirect('home');
        }
    }

    public function rbeli(Request $request) //: View
    {
        $username = session('username');
        $data = [
            'menu' => 'report',
            'submenu' => 'rbeli',
            'submenu1' => 'ref_umum',
            'title' => 'Laporan Penerimaan Pembelian',
            'userdtlmenu' => Userdtl::join('tbmodule', 'userdtl.cmodule', '=', 'tbmodule.cmodule')->where('userdtl.pakai', '1')->where('username', $username)->orderBy('userdtl.nurut')->get(),
            'userdtl' => Userdtl::where('cmodule', 'Laporan Penerimaan Pembelian')->where('username', $username)->first(),
        ];
        $userdtl = Userdtl::where('cmodule', 'Laporan Penerimaan Pembelian')->where('username', $username)->first();
        if (isset($userdtl)) {
            if ($userdtl->pakai == '1') {
                return view('report.rbeli')->with($data);
            } else {
                return redirect('home');
            }
        } else {
            return redirect('home');
        }
    }
    public function rbeli_view(Request $request) //: View
    {
        $username = session('username');
        $data = [
            'menu' => 'report',
            'submenu' => 'rbeli',
            'submenu1' => 'ref_umum',
            'title' => 'Laporan Penerimaan Pembelian',
            'saplikasi' => Saplikasi::where('aktif', 'Y')->first(),
            'userdtlmenu' => Userdtl::join('tbmodule', 'userdtl.cmodule', '=', 'tbmodule.cmodule')->where('userdtl.pakai', '1')->where('username', $username)->orderBy('userdtl.nurut')->get(),
            'userdtl' => Userdtl::where('cmodule', 'Laporan Penerimaan Pembelian')->where('username', $username)->first(),
            'tanggal1' => $request->tanggal1,
            'tanggal2' => $request->tanggal2,
            'semuaperiode' => isset($request->semuaperiode) ? 'Y' : 'N',
            'rincian' => isset($request->rincian) ? 'Y' : 'N',
            'semuabarang' => isset($request->semuabarang) ? 'Y' : 'N',
            'kdbarang' => isset($request->kdbarang) ? $request->kdbarang : '',
            'nmbarang' => isset($request->nmbarang) ? $request->nmbarang : '',
            'semuasupplier' => isset($request->semuasupplier) ? 'Y' : 'N',
            'kdsupplier' => isset($request->kdsupplier) ? $request->kdsupplier : '',
            'nmsupplier' => isset($request->nmsupplier) ? $request->nmsupplier : '',
            'groupingsupplier' => isset($request->groupingsupplier) ? 'Y' : 'N',
        ];
        $userdtl = Userdtl::where('cmodule', 'Laporan Penerimaan Pembelian')->where('username', $username)->first();
        if (isset($userdtl)) {
            if ($userdtl->pakai == '1') {
                return view('report.rbeli_view')->with($data);
            } else {
                return redirect('home');
            }
        } else {
            return redirect('home');
        }
    }
    public function rbeli_xls(Request $request) //: View
    {
        $username = session('username');
        $data = [
            'menu' => 'report',
            'submenu' => 'rbeli',
            'submenu1' => 'ref_umum',
            'title' => 'Laporan Penerimaan Pembelian',
            'saplikasi' => Saplikasi::where('aktif', 'Y')->first(),
            'userdtlmenu' => Userdtl::join('tbmodule', 'userdtl.cmodule', '=', 'tbmodule.cmodule')->where('userdtl.pakai', '1')->where('username', $username)->orderBy('userdtl.nurut')->get(),
            'userdtl' => Userdtl::where('cmodule', 'Laporan Penerimaan Pembelian')->where('username', $username)->first(),
            'tanggal1' => $request->tanggal1,
            'tanggal2' => $request->tanggal2,
            'semuaperiode' => isset($request->semuaperiode) ? 'Y' : 'N',
            'rincian' => isset($request->rincian) ? 'Y' : 'N',
            'semuabarang' => isset($request->semuabarang) ? 'Y' : 'N',
            'kdbarang' => isset($request->kdbarang) ? $request->kdbarang : '',
            'nmbarang' => isset($request->nmbarang) ? $request->nmbarang : '',
            'semuasupplier' => isset($request->semuasupplier) ? 'Y' : 'N',
            'kdsupplier' => isset($request->kdsupplier) ? $request->kdsupplier : '',
            'nmsupplier' => isset($request->nmsupplier) ? $request->nmsupplier : '',
            'groupingsupplier' => isset($request->groupingsupplier) ? 'Y' : 'N',
        ];
        $userdtl = Userdtl::where('cmodule', 'Laporan Penerimaan Pembelian')->where('username', $username)->first();
        if (isset($userdtl)) {
            if ($userdtl->pakai == '1') {
                return view('report.rbeli_xls')->with($data);
            } else {
                return redirect('home');
            }
        } else {
            return redirect('home');
        }
    }
    public function rbeli_export(Request $request) //: View
    {
        $username = session('username');
        $data = [
            'menu' => 'report',
            'submenu' => 'rbeli',
            'submenu1' => 'ref_umum',
            'title' => 'Laporan Penerimaan Pembelian',
            'saplikasi' => Saplikasi::where('aktif', 'Y')->first(),
            'userdtlmenu' => Userdtl::join('tbmodule', 'userdtl.cmodule', '=', 'tbmodule.cmodule')->where('userdtl.pakai', '1')->where('username', $username)->orderBy('userdtl.nurut')->get(),
            'userdtl' => Userdtl::where('cmodule', 'Laporan Penerimaan Pembelian')->where('username', $username)->first(),
            'tanggal1' => $request->tanggal1,
            'tanggal2' => $request->tanggal2,
            'semuaperiode' => $request->semuaperiode,
            'rincian' => $request->rincian,
            'semuabarang' => $request->semuabarang,
            'kdbarang' => $request->kdbarang,
            'nmbarang' => $request->nmbarang,
            'semuasupplier' => $request->semuasupplier,
            'kdsupplier' => $request->kdsupplier,
            'nmsupplier' => $request->nmsupplier,
            'groupingsupplier' => $request->groupingsupplier,
        ];
        $userdtl = Userdtl::where('cmodule', 'Laporan Penerimaan Pembelian')->where('username', $username)->first();
        if (isset($userdtl)) {
            if ($userdtl->pakai == '1') {
                return view('report.rbeli_export')->with($data);
            } else {
                return redirect('home');
            }
        } else {
            return redirect('home');
        }
    }

    public function rterima(Request $request) //: View
    {
        $username = session('username');
        $data = [
            'menu' => 'report',
            'submenu' => 'rterima',
            'submenu1' => 'ref_umum',
            'title' => 'Laporan Penerimaan Barang',
            'userdtlmenu' => Userdtl::join('tbmodule', 'userdtl.cmodule', '=', 'tbmodule.cmodule')->where('userdtl.pakai', '1')->where('username', $username)->orderBy('userdtl.nurut')->get(),
            'userdtl' => Userdtl::where('cmodule', 'Laporan Penerimaan Barang')->where('username', $username)->first(),
        ];
        $userdtl = Userdtl::where('cmodule', 'Laporan Penerimaan Barang')->where('username', $username)->first();
        if (isset($userdtl)) {
            if ($userdtl->pakai == '1') {
                return view('report.rterima')->with($data);
            } else {
                return redirect('home');
            }
        } else {
            return redirect('home');
        }
    }

    public function rterima_xls(Request $request) //: View
    {
        $username = session('username');
        $data = [
            'menu' => 'report',
            'submenu' => 'rterima',
            'submenu1' => 'ref_umum',
            'title' => 'Laporan Penerimaan Barang',
            'saplikasi' => Saplikasi::where('aktif', 'Y')->first(),
            'userdtlmenu' => Userdtl::join('tbmodule', 'userdtl.cmodule', '=', 'tbmodule.cmodule')->where('userdtl.pakai', '1')->where('username', $username)->orderBy('userdtl.nurut')->get(),
            'userdtl' => Userdtl::where('cmodule', 'Laporan Penerimaan Barang')->where('username', $username)->first(),
            'semuaperiode' => isset($request->semuaperiode) ? 'Y' : 'N',
            'tanggal1' => $request->tanggal1,
            'tanggal2' => $request->tanggal2,
        ];
        $userdtl = Userdtl::where('cmodule', 'Laporan Penerimaan Barang')->where('username', $username)->first();
        if (isset($userdtl)) {
            if ($userdtl->pakai == '1') {
                return view('report.rterima_xls')->with($data);
            } else {
                return redirect('home');
            }
        } else {
            return redirect('home');
        }
    }

    public function rterima_export(Request $request) //: View
    {
        $username = session('username');
        $data = [
            'menu' => 'report',
            'submenu' => 'rterima',
            'submenu1' => 'ref_umum',
            'title' => 'Laporan Penerimaan Barang',
            'saplikasi' => Saplikasi::where('aktif', 'Y')->first(),
            'userdtlmenu' => Userdtl::join('tbmodule', 'userdtl.cmodule', '=', 'tbmodule.cmodule')->where('userdtl.pakai', '1')->where('username', $username)->orderBy('userdtl.nurut')->get(),
            'userdtl' => Userdtl::where('cmodule', 'Laporan Penerimaan Barang')->where('username', $username)->first(),
            'semuaperiode' => $request->semuaperiode,
            'tanggal1' => $request->tanggal1,
            'tanggal2' => $request->tanggal2,
        ];
        $userdtl = Userdtl::where('cmodule', 'Laporan Penerimaan Barang')->where('username', $username)->first();
        if (isset($userdtl)) {
            if ($userdtl->pakai == '1') {
                return view('report.rterima_export')->with($data);
            } else {
                return redirect('home');
            }
        } else {
            return redirect('home');
        }
    }

    public function rkeluar(Request $request) //: View
    {
        $username = session('username');
        $data = [
            'menu' => 'report',
            'submenu' => 'rkeluar',
            'submenu1' => 'ref_umum',
            'title' => 'Laporan Pengeluaran Barang',
            'userdtlmenu' => Userdtl::join('tbmodule', 'userdtl.cmodule', '=', 'tbmodule.cmodule')->where('userdtl.pakai', '1')->where('username', $username)->orderBy('userdtl.nurut')->get(),
            'userdtl' => Userdtl::where('cmodule', 'Laporan Pengeluaran Barang')->where('username', $username)->first(),
        ];
        $userdtl = Userdtl::where('cmodule', 'Laporan Pengeluaran Barang')->where('username', $username)->first();
        if (isset($userdtl)) {
            if ($userdtl->pakai == '1') {
                return view('report.rkeluar')->with($data);
            } else {
                return redirect('home');
            }
        } else {
            return redirect('home');
        }
    }

    public function rkeluar_xls(Request $request) //: View
    {
        $username = session('username');
        $data = [
            'menu' => 'report',
            'submenu' => 'rkeluar',
            'submenu1' => 'ref_umum',
            'title' => 'Laporan Pengeluaran Barang',
            'saplikasi' => Saplikasi::where('aktif', 'Y')->first(),
            'userdtlmenu' => Userdtl::join('tbmodule', 'userdtl.cmodule', '=', 'tbmodule.cmodule')->where('userdtl.pakai', '1')->where('username', $username)->orderBy('userdtl.nurut')->get(),
            'userdtl' => Userdtl::where('cmodule', 'Laporan Pengeluaran Barang')->where('username', $username)->first(),
            'semuaperiode' => isset($request->semuaperiode) ? 'Y' : 'N',
            'tanggal1' => $request->tanggal1,
            'tanggal2' => $request->tanggal2,
        ];
        $userdtl = Userdtl::where('cmodule', 'Laporan Pengeluaran Barang')->where('username', $username)->first();
        if (isset($userdtl)) {
            if ($userdtl->pakai == '1') {
                return view('report.rkeluar_xls')->with($data);
            } else {
                return redirect('home');
            }
        } else {
            return redirect('home');
        }
    }

    public function rkeluar_export(Request $request) //: View
    {
        $username = session('username');
        $data = [
            'menu' => 'report',
            'submenu' => 'rkeluar',
            'submenu1' => 'ref_umum',
            'title' => 'Laporan Pengeluaran Barang',
            'saplikasi' => Saplikasi::where('aktif', 'Y')->first(),
            'userdtlmenu' => Userdtl::join('tbmodule', 'userdtl.cmodule', '=', 'tbmodule.cmodule')->where('userdtl.pakai', '1')->where('username', $username)->orderBy('userdtl.nurut')->get(),
            'userdtl' => Userdtl::where('cmodule', 'Laporan Pengeluaran Barang')->where('username', $username)->first(),
            'semuaperiode' => $request->semuaperiode,
            'tanggal1' => $request->tanggal1,
            'tanggal2' => $request->tanggal2,
        ];
        $userdtl = Userdtl::where('cmodule', 'Laporan Pengeluaran Barang')->where('username', $username)->first();
        if (isset($userdtl)) {
            if ($userdtl->pakai == '1') {
                return view('report.rkeluar_export')->with($data);
            } else {
                return redirect('home');
            }
        } else {
            return redirect('home');
        }
    }

    public function rstock_opname(Request $request) //: View
    {
        $username = session('username');
        $data = [
            'menu' => 'report',
            'submenu' => 'rstock_opname',
            'submenu1' => 'ref_umum',
            'title' => 'Laporan Stock Opname',
            'userdtlmenu' => Userdtl::join('tbmodule', 'userdtl.cmodule', '=', 'tbmodule.cmodule')->where('userdtl.pakai', '1')->where('username', $username)->orderBy('userdtl.nurut')->get(),
            'userdtl' => Userdtl::where('cmodule', 'Laporan Stock Opname')->where('username', $username)->first(),
            'opnameh' => Opnameh::where('proses', 'Y')->get(),
        ];
        $userdtl = Userdtl::where('cmodule', 'Laporan Stock Opname')->where('username', $username)->first();
        if (isset($userdtl)) {
            if ($userdtl->pakai == '1') {
                return view('report.rstock_opname')->with($data);
            } else {
                return redirect('home');
            }
        } else {
            return redirect('home');
        }
    }

    public function rstock_opname_xls(Request $request) //: View
    {
        $username = session('username');
        $data = [
            'menu' => 'report',
            'submenu' => 'rstock_opname',
            'submenu1' => 'ref_umum',
            'title' => 'Laporan Stock Opname',
            'saplikasi' => Saplikasi::where('aktif', 'Y')->first(),
            'userdtlmenu' => Userdtl::join('tbmodule', 'userdtl.cmodule', '=', 'tbmodule.cmodule')->where('userdtl.pakai', '1')->where('username', $username)->orderBy('userdtl.nurut')->get(),
            'userdtl' => Userdtl::where('cmodule', 'Laporan Stock Opname')->where('username', $username)->first(),
            'noopname' => $request->noopname,
        ];
        $userdtl = Userdtl::where('cmodule', 'Laporan Stock Opname')->where('username', $username)->first();
        if (isset($userdtl)) {
            if ($userdtl->pakai == '1') {
                return view('report.rstock_opname_xls')->with($data);
            } else {
                return redirect('home');
            }
        } else {
            return redirect('home');
        }
    }

    public function rstock_opname_export(Request $request) //: View
    {
        $username = session('username');
        $data = [
            'menu' => 'report',
            'submenu' => 'rstock_opname',
            'submenu1' => 'ref_umum',
            'title' => 'Laporan Stock Opname',
            'saplikasi' => Saplikasi::where('aktif', 'Y')->first(),
            'userdtlmenu' => Userdtl::join('tbmodule', 'userdtl.cmodule', '=', 'tbmodule.cmodule')->where('userdtl.pakai', '1')->where('username', $username)->orderBy('userdtl.nurut')->get(),
            'userdtl' => Userdtl::where('cmodule', 'Laporan Stock Opname')->where('username', $username)->first(),
            'noopname' => $request->noopname,
        ];
        $userdtl = Userdtl::where('cmodule', 'Laporan Stock Opname')->where('username', $username)->first();
        if (isset($userdtl)) {
            if ($userdtl->pakai == '1') {
                return view('report.rstock_opname_export')->with($data);
            } else {
                return redirect('home');
            }
        } else {
            return redirect('home');
        }
    }

    public function rstock(Request $request) //: View
    {
        $username = session('username');
        $data = [
            'menu' => 'report',
            'submenu' => 'rstock',
            'submenu1' => 'ref_umum',
            'title' => 'Laporan Stock Barang',
            'userdtlmenu' => Userdtl::join('tbmodule', 'userdtl.cmodule', '=', 'tbmodule.cmodule')->where('userdtl.pakai', '1')->where('username', $username)->orderBy('userdtl.nurut')->get(),
            'userdtl' => Userdtl::where('cmodule', 'Laporan Stock Barang')->where('username', $username)->first(),
            'opnameh' => Opnameh::where('proses', 'Y')->orderBy('noopname', 'desc')->get(),
        ];
        // var_dump($data);
        return view('report.rstock')->with($data);
    }

    public function rstock_xls(Request $request) //: View
    {
        $username = session('username');
        $data = [
            'menu' => 'report',
            'submenu' => 'rstock',
            'submenu1' => 'ref_umum',
            'title' => 'Laporan Stock Barang',
            'saplikasi' => Saplikasi::where('aktif', 'Y')->first(),
            'userdtlmenu' => Userdtl::join('tbmodule', 'userdtl.cmodule', '=', 'tbmodule.cmodule')->where('userdtl.pakai', '1')->where('username', $username)->orderBy('userdtl.nurut')->get(),
            'userdtl' => Userdtl::where('cmodule', 'Laporan Stock Barang')->where('username', $username)->first(),
            'noopname' => $request->noopname,
            'rekapitulasi' => isset($request->rekapitulasi) ? 'Y' : 'N',
            'semuaperiode' => isset($request->semuaperiode) ? 'Y' : 'N',
            'semuabarang' => isset($request->semuabarang) ? 'Y' : 'N',
            'kdbarang' => isset($request->kdbarang) ? $request->kdbarang : '',
            'nmbarang' => isset($request->nmbarang) ? $request->nmbarang : '',
            'tanggal1' => $request->tanggal1,
            'tanggal2' => $request->tanggal2,
            'tglopname' => $request->tglopname,
        ];
        $userdtl = Userdtl::where('cmodule', 'Laporan Stock Barang')->where('username', $username)->first();
        if (isset($userdtl)) {
            if ($userdtl->pakai == '1') {
                return view('report.rstock_xls')->with($data);
            } else {
                return redirect('home');
            }
        } else {
            return redirect('home');
        }
    }

    public function rstock_export(Request $request) //: View
    {
        $username = session('username');
        $data = [
            'menu' => 'report',
            'submenu' => 'rstock',
            'submenu1' => 'ref_umum',
            'title' => 'Laporan Stock Barang',
            'saplikasi' => Saplikasi::where('aktif', 'Y')->first(),
            'userdtlmenu' => Userdtl::join('tbmodule', 'userdtl.cmodule', '=', 'tbmodule.cmodule')->where('userdtl.pakai', '1')->where('username', $username)->orderBy('userdtl.nurut')->get(),
            'userdtl' => Userdtl::where('cmodule', 'Laporan Stock Barang')->where('username', $username)->first(),
            'noopname' => $request->noopname,
            'rekapitulasi' => $request->rekapitulasi,
            'semuaperiode' => $request->semuaperiode,
            'semuabarang' => $request->semuabarang,
            'kdbarang' => $request->kdbarang,
            'nmbarang' => $request->nmbarang,
            'tanggal1' => $request->tanggal1,
            'tanggal2' => $request->tanggal2,
            'tglopname' => $request->tglopname,
        ];
        $userdtl = Userdtl::where('cmodule', 'Laporan Stock Barang')->where('username', $username)->first();
        if (isset($userdtl)) {
            if ($userdtl->pakai == '1') {
                return view('report.rstock_export')->with($data);
            } else {
                return redirect('home');
            }
        } else {
            return redirect('home');
        }
    }

    public function rhpp(Request $request) //: View
    {
        $username = session('username');
        $data = [
            'menu' => 'report',
            'submenu' => 'rhpp',
            'submenu1' => 'ref_umum',
            'title' => 'Laporan HPP Barang',
            'userdtlmenu' => Userdtl::join('tbmodule', 'userdtl.cmodule', '=', 'tbmodule.cmodule')->where('userdtl.pakai', '1')->where('username', $username)->orderBy('userdtl.nurut')->get(),
            'userdtl' => Userdtl::where('cmodule', 'Laporan HPP Barang')->where('username', $username)->first(),
            'opnameh' => Opnameh::where('proses', 'Y')->orderBy('noopname', 'desc')->get(),
        ];
        $userdtl = Userdtl::where('cmodule', 'Laporan HPP Barang')->where('username', $username)->first();
        if (isset($userdtl)) {
            if ($userdtl->pakai == '1') {
                return view('report.rhpp')->with($data);
            } else {
                return redirect('home');
            }
        } else {
            return redirect('home');
        }
    }
    public function rhpp_xls(Request $request) //: View
    {
        $username = session('username');
        $data = [
            'menu' => 'report',
            'submenu' => 'rhpp',
            'submenu1' => 'ref_umum',
            'title' => 'Laporan HPP Barang',
            'saplikasi' => Saplikasi::where('aktif', 'Y')->first(),
            'userdtlmenu' => Userdtl::join('tbmodule', 'userdtl.cmodule', '=', 'tbmodule.cmodule')->where('userdtl.pakai', '1')->where('username', $username)->orderBy('userdtl.nurut')->get(),
            'userdtl' => Userdtl::where('cmodule', 'Laporan HPP Barang')->where('username', $username)->first(),
            'semuabarang' => isset($request->semuabarang) ? 'Y' : 'N',
            'kdbarang' => isset($request->kdbarang) ? $request->kdbarang : '',
            'nmbarang' => isset($request->nmbarang) ? $request->nmbarang : '',
            'bulan' => $request->bulan,
            'tahun' => $request->tahun,
        ];
        $userdtl = Userdtl::where('cmodule', 'Laporan HPP Barang')->where('username', $username)->first();
        if (isset($userdtl)) {
            if ($userdtl->pakai == '1') {
                return view('report.rhpp_xls')->with($data);
            } else {
                return redirect('home');
            }
        } else {
            return redirect('home');
        }
    }
    public function rhpp_export(Request $request) //: View
    {
        $username = session('username');
        $data = [
            'menu' => 'report',
            'submenu' => 'rhpp',
            'submenu1' => 'ref_umum',
            'title' => 'Laporan Hpp Barang',
            'saplikasi' => Saplikasi::where('aktif', 'Y')->first(),
            'userdtlmenu' => Userdtl::join('tbmodule', 'userdtl.cmodule', '=', 'tbmodule.cmodule')->where('userdtl.pakai', '1')->where('username', $username)->orderBy('userdtl.nurut')->get(),
            'userdtl' => Userdtl::where('cmodule', 'Laporan Hpp Barang')->where('username', $username)->first(),
            'semuabarang' => $request->semuabarang,
            'kdbarang' => $request->kdbarang,
            'nmbarang' => $request->nmbarang,
            'bulan' => $request->bulan,
            'tahun' => $request->tahun,
        ];
        $userdtl = Userdtl::where('cmodule', 'Laporan HPP Barang')->where('username', $username)->first();
        if (isset($userdtl)) {
            if ($userdtl->pakai == '1') {
                return view('report.rhpp_export')->with($data);
            } else {
                return redirect('home');
            }
        } else {
            return redirect('home');
        }
    }

    public function rkasir_tunai(Request $request) //: View
    {
        $username = session('username');
        $data = [
            'menu' => 'report',
            'submenu' => 'rkasir_tunai',
            'submenu1' => 'ref_umum',
            'title' => 'Laporan Kasir Tunai',
            'userdtlmenu' => Userdtl::join('tbmodule', 'userdtl.cmodule', '=', 'tbmodule.cmodule')->where('userdtl.pakai', '1')->where('username', $username)->orderBy('userdtl.nurut')->get(),
            'userdtl' => Userdtl::where('cmodule', 'Laporan Kasir Tunai')->where('username', $username)->first(),
            'user' => User::orderBy('username', 'asc')->get(),
        ];
        $userdtl = Userdtl::where('cmodule', 'Laporan Kasir Tunai')->where('username', $username)->first();
        if (isset($userdtl)) {
            if ($userdtl->pakai == '1') {
                return view('report.rkasir_tunai')->with($data);
            } else {
                return redirect('home');
            }
        } else {
            return redirect('home');
        }
    }
    public function rkasir_tunai_xls(Request $request) //: View
    {
        $username = session('username');
        $data = [
            'menu' => 'report',
            'submenu' => 'rkasir_tunai',
            'submenu1' => 'ref_umum',
            'title' => 'Laporan Kasir Tunai',
            'saplikasi' => Saplikasi::where('aktif', 'Y')->first(),
            'userdtlmenu' => Userdtl::join('tbmodule', 'userdtl.cmodule', '=', 'tbmodule.cmodule')->where('userdtl.pakai', '1')->where('username', $username)->orderBy('userdtl.nurut')->get(),
            'userdtl' => Userdtl::where('cmodule', 'Laporan Kasir Tunai')->where('username', $username)->first(),
            'semuaperiode' => isset($request->semuaperiode) ? 'Y' : 'N',
            'tanggal1' => $request->tanggal1,
            'tanggal2' => $request->tanggal2,
            'nmkasir' => $request->nmkasir,
        ];
        $userdtl = Userdtl::where('cmodule', 'Laporan Kasir Tunai')->where('username', $username)->first();
        if (isset($userdtl)) {
            if ($userdtl->pakai == '1') {
                return view('report.rkasir_tunai_xls')->with($data);
            } else {
                return redirect('home');
            }
        } else {
            return redirect('home');
        }
    }
    public function rkasir_tunai_export(Request $request) //: View
    {
        $username = session('username');
        $data = [
            'menu' => 'report',
            'submenu' => 'rkasir_tunai',
            'submenu1' => 'ref_umum',
            'title' => 'Laporan Kasir Tunai',
            'saplikasi' => Saplikasi::where('aktif', 'Y')->first(),
            'userdtlmenu' => Userdtl::join('tbmodule', 'userdtl.cmodule', '=', 'tbmodule.cmodule')->where('userdtl.pakai', '1')->where('username', $username)->orderBy('userdtl.nurut')->get(),
            'userdtl' => Userdtl::where('cmodule', 'Laporan Kasir Tunai')->where('username', $username)->first(),
            'semuaperiode' => $request->semuaperiode,
            'tanggal1' => $request->tanggal1,
            'tanggal2' => $request->tanggal2,
            'nmkasir' => $request->nmkasir,
        ];
        $userdtl = Userdtl::where('cmodule', 'Laporan Kasir Tunai')->where('username', $username)->first();
        if (isset($userdtl)) {
            if ($userdtl->pakai == '1') {
                return view('report.rkasir_tunai_export')->with($data);
            } else {
                return redirect('home');
            }
        } else {
            return redirect('home');
        }
    }

    public function rkasir_tagihan(Request $request) //: View
    {
        $username = session('username');
        $data = [
            'menu' => 'report',
            'submenu' => 'rkasir_tagihan',
            'submenu1' => 'ref_umum',
            'title' => 'Laporan Kasir Tagihan',
            'userdtlmenu' => Userdtl::join('tbmodule', 'userdtl.cmodule', '=', 'tbmodule.cmodule')->where('userdtl.pakai', '1')->where('username', $username)->orderBy('userdtl.nurut')->get(),
            'userdtl' => Userdtl::where('cmodule', 'Laporan Kasir Tagihan')->where('username', $username)->first(),
            'user' => User::orderBy('username', 'asc')->get(),
        ];
        $userdtl = Userdtl::where('cmodule', 'Laporan Kasir Tagihan')->where('username', $username)->first();
        if (isset($userdtl)) {
            if ($userdtl->pakai == '1') {
                return view('report.rkasir_tagihan')->with($data);
            } else {
                return redirect('home');
            }
        } else {
            return redirect('home');
        }
    }
    public function rkasir_tagihan_xls(Request $request) //: View
    {
        $username = session('username');
        $data = [
            'menu' => 'report',
            'submenu' => 'rkasir_tagihan',
            'submenu1' => 'ref_umum',
            'title' => 'Laporan Kasir Tagihan',
            'saplikasi' => Saplikasi::where('aktif', 'Y')->first(),
            'userdtlmenu' => Userdtl::join('tbmodule', 'userdtl.cmodule', '=', 'tbmodule.cmodule')->where('userdtl.pakai', '1')->where('username', $username)->orderBy('userdtl.nurut')->get(),
            'userdtl' => Userdtl::where('cmodule', 'Laporan Kasir Tagihan')->where('username', $username)->first(),
            'semuaperiode' => isset($request->semuaperiode) ? 'Y' : 'N',
            'tanggal1' => $request->tanggal1,
            'tanggal2' => $request->tanggal2,
            'groupingcustomer' => isset($request->groupingcustomer) ? 'Y' : 'N',
            'groupingcarabayar' => isset($request->groupingcarabayar) ? 'Y' : 'N',
            'semuacustomer' => isset($request->semuacustomer) ? 'Y' : 'N',
            'kdcustomer' => isset($request->kdcustomer) ? $request->kdcustomer : '',
            'nmcustomer' => isset($request->nmcustomer) ? $request->nmcustomer : '',
            'nmkasir' => $request->nmkasir,
            'carabayar' => $request->carabayar,
        ];
        $userdtl = Userdtl::where('cmodule', 'Laporan Kasir Tagihan')->where('username', $username)->first();
        if (isset($userdtl)) {
            if ($userdtl->pakai == '1') {
                return view('report.rkasir_tagihan_xls')->with($data);
            } else {
                return redirect('home');
            }
        } else {
            return redirect('home');
        }
    }
    public function rkasir_tagihan_export(Request $request) //: View
    {
        $username = session('username');
        $data = [
            'menu' => 'report',
            'submenu' => 'rkasir_tagihan',
            'submenu1' => 'ref_umum',
            'title' => 'Laporan Kasir Tagihan',
            'saplikasi' => Saplikasi::where('aktif', 'Y')->first(),
            'userdtlmenu' => Userdtl::join('tbmodule', 'userdtl.cmodule', '=', 'tbmodule.cmodule')->where('userdtl.pakai', '1')->where('username', $username)->orderBy('userdtl.nurut')->get(),
            'userdtl' => Userdtl::where('cmodule', 'Laporan Kasir Tagihan')->where('username', $username)->first(),
            'semuaperiode' => $request->semuaperiode,
            'tanggal1' => $request->tanggal1,
            'tanggal2' => $request->tanggal2,
            'groupingcustomer' => $request->groupingcustomer,
            'groupingcarabayar' => $request->groupingcarabayar,
            'semuacustomer' => $request->semuacustomer,
            'kdcustomer' => $request->kdcustomer,
            'nmcustomer' => $request->nmcustomer,
            'nmkasir' => $request->nmkasir,
            'carabayar' => $request->carabayar,
        ];
        $userdtl = Userdtl::where('cmodule', 'Laporan Kasir Tagihan')->where('username', $username)->first();
        if (isset($userdtl)) {
            if ($userdtl->pakai == '1') {
                return view('report.rkasir_tagihan_export')->with($data);
            } else {
                return redirect('home');
            }
        } else {
            return redirect('home');
        }
    }

    public function rpermohonan_keluar_uang(Request $request) //: View
    {
        $username = session('username');
        $data = [
            'menu' => 'report',
            'submenu' => 'rpermohonan_keluar_uang',
            'submenu1' => 'ref_umum',
            'title' => 'Laporan Permohonan Keluar Uang',
            'userdtlmenu' => Userdtl::join('tbmodule', 'userdtl.cmodule', '=', 'tbmodule.cmodule')->where('userdtl.pakai', '1')->where('username', $username)->orderBy('userdtl.nurut')->get(),
            'userdtl' => Userdtl::where('cmodule', 'Laporan Permohonan Keluar Uang')->where('username', $username)->first(),
        ];
        $userdtl = Userdtl::where('cmodule', 'Laporan Permohonan Keluar Uang')->where('username', $username)->first();
        if (isset($userdtl)) {
            if ($userdtl->pakai == '1') {
                return view('report.rpermohonan_keluar_uang')->with($data);
            } else {
                return redirect('home');
            }
        } else {
            return redirect('home');
        }
    }
    public function rpermohonan_keluar_uang_xls(Request $request) //: View
    {
        $username = session('username');
        $data = [
            'menu' => 'report',
            'submenu' => 'rpermohonan_keluar_uang',
            'submenu1' => 'ref_umum',
            'title' => 'Laporan Permohonan Keluar Uang',
            'saplikasi' => Saplikasi::where('aktif', 'Y')->first(),
            // 'jualh' => Jualh::where('proses', 'Y')->where('tgljual', '>=', $request->tanggal1)->where('tgljual', '<=', $request->tanggal2)->get(),
            // 'recjualh' => Jualh::where('proses', 'Y')->where('tgljual', '>=', $request->tanggal1)->where('tgljual', '<=', $request->tanggal2)->count(),
            'userdtlmenu' => Userdtl::join('tbmodule', 'userdtl.cmodule', '=', 'tbmodule.cmodule')->where('userdtl.pakai', '1')->where('username', $username)->orderBy('userdtl.nurut')->get(),
            'userdtl' => Userdtl::where('cmodule', 'Laporan Permohonan Keluar Uang')->where('username', $username)->first(),
            'outstanding' => isset($request->outstanding) ? 'Y' : 'N',
            'semuaperiode' => isset($request->semuaperiode) ? 'Y' : 'N',
            'tanggal1' => $request->tanggal1,
            'tanggal2' => $request->tanggal2,
        ];
        $userdtl = Userdtl::where('cmodule', 'Laporan Permohonan Keluar Uang')->where('username', $username)->first();
        if (isset($userdtl)) {
            if ($userdtl->pakai == '1') {
                return view('report.rpermohonan_keluar_uang_xls')->with($data);
            } else {
                return redirect('home');
            }
        } else {
            return redirect('home');
        }
    }
    public function rpermohonan_keluar_uang_export(Request $request) //: View
    {
        $username = session('username');
        $data = [
            'menu' => 'report',
            'submenu' => 'rpermohonan_keluar_uang',
            'submenu1' => 'ref_umum',
            'title' => 'Laporan Permohonan Keluar Uang',
            'saplikasi' => Saplikasi::where('aktif', 'Y')->first(),
            // 'jualh' => Jualh::where('proses', 'Y')->where('tgljual', '>=', $request->tanggal1)->where('tgljual', '<=', $request->tanggal2)->get(),
            // 'recjualh' => Jualh::where('proses', 'Y')->where('tgljual', '>=', $request->tanggal1)->where('tgljual', '<=', $request->tanggal2)->count(),
            'userdtlmenu' => Userdtl::join('tbmodule', 'userdtl.cmodule', '=', 'tbmodule.cmodule')->where('userdtl.pakai', '1')->where('username', $username)->orderBy('userdtl.nurut')->get(),
            'userdtl' => Userdtl::where('cmodule', 'Laporan Permohonan Keluar Uang')->where('username', $username)->first(),
            'outstanding' => $request->outstanding,
            'semuaperiode' => $request->semuaperiode,
            'tanggal1' => $request->tanggal1,
            'tanggal2' => $request->tanggal2,
        ];
        $userdtl = Userdtl::where('cmodule', 'Laporan Permohonan Keluar Uang')->where('username', $username)->first();
        if (isset($userdtl)) {
            if ($userdtl->pakai == '1') {
                return view('report.rpermohonan_keluar_uang_export')->with($data);
            } else {
                return redirect('home');
            }
        } else {
            return redirect('home');
        }
    }

    public function rkasir_keluar(Request $request) //: View
    {
        $username = session('username');
        $data = [
            'menu' => 'report',
            'submenu' => 'rkasir_keluar',
            'submenu1' => 'ref_umum',
            'title' => 'Laporan Kasir Pengeluaran Uang',
            'userdtlmenu' => Userdtl::join('tbmodule', 'userdtl.cmodule', '=', 'tbmodule.cmodule')->where('userdtl.pakai', '1')->where('username', $username)->orderBy('userdtl.nurut')->get(),
            'userdtl' => Userdtl::where('cmodule', 'Laporan Kasir Pengeluaran Uang')->where('username', $username)->first(),
            'user' => User::orderBy('username', 'asc')->get(),
        ];
        $userdtl = Userdtl::where('cmodule', 'Laporan Kasir Pengeluaran Uang')->where('username', $username)->first();
        if (isset($userdtl)) {
            if ($userdtl->pakai == '1') {
                return view('report.rkasir_keluar')->with($data);
            } else {
                return redirect('home');
            }
        } else {
            return redirect('home');
        }
    }
    public function rkasir_keluar_xls(Request $request) //: View
    {
        $username = session('username');
        $data = [
            'menu' => 'report',
            'submenu' => 'rkasir_keluar',
            'submenu1' => 'ref_umum',
            'title' => 'Laporan Kasir Pengeluaran Uang',
            'saplikasi' => Saplikasi::where('aktif', 'Y')->first(),
            'userdtlmenu' => Userdtl::join('tbmodule', 'userdtl.cmodule', '=', 'tbmodule.cmodule')->where('userdtl.pakai', '1')->where('username', $username)->orderBy('userdtl.nurut')->get(),
            'userdtl' => Userdtl::where('cmodule', 'Laporan Kasir Pengeluaran Uang')->where('username', $username)->first(),
            'semuaperiode' => isset($request->semuaperiode) ? 'Y' : 'N',
            'tanggal1' => $request->tanggal1,
            'tanggal2' => $request->tanggal2,
            'groupingsupplier' => isset($request->groupingsupplier) ? 'Y' : 'N',
            'semuasupplier' => isset($request->semuasupplier) ? 'Y' : 'N',
            'kdsupplier' => isset($request->kdsupplier) ? $request->kdsupplier : '',
            'nmsupplier' => isset($request->nmsupplier) ? $request->nmsupplier : '',
            'nmkasir' => $request->nmkasir,
        ];
        $userdtl = Userdtl::where('cmodule', 'Laporan Kasir Pengeluaran Uang')->where('username', $username)->first();
        if (isset($userdtl)) {
            if ($userdtl->pakai == '1') {
                return view('report.rkasir_keluar_xls')->with($data);
            } else {
                return redirect('home');
            }
        } else {
            return redirect('home');
        }
    }
    public function rkasir_keluar_export(Request $request) //: View
    {
        $username = session('username');
        $data = [
            'menu' => 'report',
            'submenu' => 'rkasir_keluar',
            'submenu1' => 'ref_umum',
            'title' => 'Laporan Kasir Pengeluaran Uang',
            'saplikasi' => Saplikasi::where('aktif', 'Y')->first(),
            'userdtlmenu' => Userdtl::join('tbmodule', 'userdtl.cmodule', '=', 'tbmodule.cmodule')->where('userdtl.pakai', '1')->where('username', $username)->orderBy('userdtl.nurut')->get(),
            'userdtl' => Userdtl::where('cmodule', 'Laporan Kasir Pengeluaran Uang')->where('username', $username)->first(),
            'semuaperiode' => $request->semuaperiode,
            'tanggal1' => $request->tanggal1,
            'tanggal2' => $request->tanggal2,
            'groupingsupplier' => $request->groupingsupplier,
            'semuasupplier' => $request->semuasupplier,
            'kdsupplier' => $request->kdsupplier,
            'nmsupplier' => $request->nmsupplier,
            'nmkasir' => $request->nmkasir,
        ];
        $userdtl = Userdtl::where('cmodule', 'Laporan Kasir Pengeluaran Uang')->where('username', $username)->first();
        if (isset($userdtl)) {
            if ($userdtl->pakai == '1') {
                return view('report.rkasir_keluar_export')->with($data);
            } else {
                return redirect('home');
            }
        } else {
            return redirect('home');
        }
    }

    public function rpiutang(Request $request) //: View
    {
        $username = session('username');
        $data = [
            'menu' => 'report',
            'submenu' => 'rpiutang',
            'submenu1' => 'ref_umum',
            'title' => 'Laporan Piutang',
            'userdtlmenu' => Userdtl::join('tbmodule', 'userdtl.cmodule', '=', 'tbmodule.cmodule')->where('userdtl.pakai', '1')->where('username', $username)->orderBy('userdtl.nurut')->get(),
            'userdtl' => Userdtl::where('cmodule', 'Laporan Piutang')->where('username', $username)->first(),
            'user' => User::orderBy('username', 'asc')->get(),
        ];
        $userdtl = Userdtl::where('cmodule', 'Laporan Piutang')->where('username', $username)->first();
        if (isset($userdtl)) {
            if ($userdtl->pakai == '1') {
                return view('report.rpiutang')->with($data);
            } else {
                return redirect('home');
            }
        } else {
            return redirect('home');
        }
    }
    public function rpiutang_xls(Request $request) //: View
    {
        $username = session('username');
        $data = [
            'menu' => 'report',
            'submenu' => 'rpiutang',
            'submenu1' => 'ref_umum',
            'title' => 'Laporan Piutang',
            'saplikasi' => Saplikasi::where('aktif', 'Y')->first(),
            'userdtlmenu' => Userdtl::join('tbmodule', 'userdtl.cmodule', '=', 'tbmodule.cmodule')->where('userdtl.pakai', '1')->where('username', $username)->orderBy('userdtl.nurut')->get(),
            'userdtl' => Userdtl::where('cmodule', 'Laporan Piutang')->where('username', $username)->first(),
            'semuaperiode' => isset($request->semuaperiode) ? 'Y' : 'N',
            'tanggal1' => $request->tanggal1,
            'tanggal2' => $request->tanggal2,
            'groupingcustomer' => isset($request->groupingcustomer) ? 'Y' : 'N',
            'bulanan' => isset($request->bulanan) ? 'Y' : 'N',
            'belumlunas' => isset($request->belumlunas) ? 'Y' : 'N',
            'semuacustomer' => isset($request->semuacustomer) ? 'Y' : 'N',
            'kdcustomer' => isset($request->kdcustomer) ? $request->kdcustomer : '',
            'nmcustomer' => isset($request->nmcustomer) ? $request->nmcustomer : '',
        ];
        $userdtl = Userdtl::where('cmodule', 'Laporan Piutang')->where('username', $username)->first();
        if (isset($userdtl)) {
            if ($userdtl->pakai == '1') {
                return view('report.rpiutang_xls')->with($data);
            } else {
                return redirect('home');
            }
        } else {
            return redirect('home');
        }
    }
    public function rpiutang_export(Request $request) //: View
    {
        $username = session('username');
        $data = [
            'menu' => 'report',
            'submenu' => 'rpiutang',
            'submenu1' => 'ref_umum',
            'title' => 'Laporan Piutang',
            'saplikasi' => Saplikasi::where('aktif', 'Y')->first(),
            'userdtlmenu' => Userdtl::join('tbmodule', 'userdtl.cmodule', '=', 'tbmodule.cmodule')->where('userdtl.pakai', '1')->where('username', $username)->orderBy('userdtl.nurut')->get(),
            'userdtl' => Userdtl::where('cmodule', 'Laporan Piutang')->where('username', $username)->first(),
            'semuaperiode' => $request->semuaperiode,
            'tanggal1' => $request->tanggal1,
            'tanggal2' => $request->tanggal2,
            'groupingcustomer' => $request->groupingcustomer,
            'bulanan' => $request->bulanan,
            'belumlunas' => $request->belumlunas,
            'semuacustomer' => $request->semuacustomer,
            'kdcustomer' => $request->kdcustomer,
            'nmcustomer' => $request->nmcustomer,
        ];
        $userdtl = Userdtl::where('cmodule', 'Laporan Piutang')->where('username', $username)->first();
        if (isset($userdtl)) {
            if ($userdtl->pakai == '1') {
                return view('report.rpiutang_export')->with($data);
            } else {
                return redirect('home');
            }
        } else {
            return redirect('home');
        }
    }

    public function rhutang(Request $request) //: View
    {
        $username = session('username');
        $data = [
            'menu' => 'report',
            'submenu' => 'rhutang',
            'submenu1' => 'ref_umum',
            'title' => 'Laporan Hutang',
            'userdtlmenu' => Userdtl::join('tbmodule', 'userdtl.cmodule', '=', 'tbmodule.cmodule')->where('userdtl.pakai', '1')->where('username', $username)->orderBy('userdtl.nurut')->get(),
            'userdtl' => Userdtl::where('cmodule', 'Laporan Hutang')->where('username', $username)->first(),
            'user' => User::orderBy('username', 'asc')->get(),
        ];
        $userdtl = Userdtl::where('cmodule', 'Laporan Hutang')->where('username', $username)->first();
        if (isset($userdtl)) {
            if ($userdtl->pakai == '1') {
                return view('report.rhutang')->with($data);
            } else {
                return redirect('home');
            }
        } else {
            return redirect('home');
        }
    }
    public function rhutang_xls(Request $request) //: View
    {
        $username = session('username');
        $data = [
            'menu' => 'report',
            'submenu' => 'rhutang',
            'submenu1' => 'ref_umum',
            'title' => 'Laporan Hutang',
            'saplikasi' => Saplikasi::where('aktif', 'Y')->first(),
            'userdtlmenu' => Userdtl::join('tbmodule', 'userdtl.cmodule', '=', 'tbmodule.cmodule')->where('userdtl.pakai', '1')->where('username', $username)->orderBy('userdtl.nurut')->get(),
            'userdtl' => Userdtl::where('cmodule', 'Laporan Hutang')->where('username', $username)->first(),
            'semuaperiode' => isset($request->semuaperiode) ? 'Y' : 'N',
            'tanggal1' => $request->tanggal1,
            'tanggal2' => $request->tanggal2,
            'groupingsupplier' => isset($request->groupingsupplier) ? 'Y' : 'N',
            'bulanan' => isset($request->bulanan) ? 'Y' : 'N',
            'belumlunas' => isset($request->belumlunas) ? 'Y' : 'N',
            'semuasupplier' => isset($request->semuasupplier) ? 'Y' : 'N',
            'kdsupplier' => isset($request->kdsupplier) ? $request->kdsupplier : '',
            'nmsupplier' => isset($request->nmsupplier) ? $request->nmsupplier : '',
        ];
        $userdtl = Userdtl::where('cmodule', 'Laporan Hutang')->where('username', $username)->first();
        if (isset($userdtl)) {
            if ($userdtl->pakai == '1') {
                return view('report.rhutang_xls')->with($data);
            } else {
                return redirect('home');
            }
        } else {
            return redirect('home');
        }
    }
    public function rhutang_export(Request $request) //: View
    {
        $username = session('username');
        $data = [
            'menu' => 'report',
            'submenu' => 'rhutang',
            'submenu1' => 'ref_umum',
            'title' => 'Laporan Hutang',
            'saplikasi' => Saplikasi::where('aktif', 'Y')->first(),
            'userdtlmenu' => Userdtl::join('tbmodule', 'userdtl.cmodule', '=', 'tbmodule.cmodule')->where('userdtl.pakai', '1')->where('username', $username)->orderBy('userdtl.nurut')->get(),
            'userdtl' => Userdtl::where('cmodule', 'Laporan Hutang')->where('username', $username)->first(),
            'semuaperiode' => $request->semuaperiode,
            'tanggal1' => $request->tanggal1,
            'tanggal2' => $request->tanggal2,
            'groupingsupplier' => $request->groupingsupplier,
            'bulanan' => $request->bulanan,
            'belumlunas' => $request->belumlunas,
            'semuasupplier' => $request->semuasupplier,
            'kdsupplier' => $request->kdsupplier,
            'nmsupplier' => $request->nmsupplier,
        ];
        $userdtl = Userdtl::where('cmodule', 'Laporan Hutang')->where('username', $username)->first();
        if (isset($userdtl)) {
            if ($userdtl->pakai == '1') {
                return view('report.rhutang_export')->with($data);
            } else {
                return redirect('home');
            }
        } else {
            return redirect('home');
        }
    }
}
