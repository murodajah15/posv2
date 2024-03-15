<?php

namespace App\Http\Controllers;

use App\Http\Requests\TbbarangRequest;
use Illuminate\Http\Request;
use Session;
// use Yajra\DataTables\Contracts\DataTables;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Tbbarang;
use App\Models\Tbgudang;
use App\Models\Tbjnbrg;
use App\Models\Tbdiscount;
use App\Models\Tbnegara;
use App\Models\Tbmove;
use App\Models\Tbsatuan;
use App\Models\Userdtl;
use App\Models\Pod;
use App\Models\Belid;
use App\Models\Terimad;
use App\Models\Sod;
use App\Models\Juald;
use App\Models\Keluard;

use App\Exports\TbbarangExport;
use Maatwebsite\Excel\Facades\Excel;

// //return type View
// use Illuminate\View\View;

class TbbarangController extends Controller
{
    public function index(Request $request) //: View
    {
        $username = session('username');
        $data = [
            'menu' => 'file',
            'submenu' => 'tbbarang',
            'submenu1' => 'ref_umum',
            'title' => 'Tabel Barang',
            // 'tbbarang' => Tbbarang::all(),
            'userdtlmenu' => Userdtl::join('tbmodule', 'userdtl.cmodule', '=', 'tbmodule.cmodule')->where('userdtl.pakai', '1')->where('username', $username)->orderBy('userdtl.nurut')->get(),
            'userdtl' => Userdtl::where('cmodule', 'Tabel Barang')->where('username', $username)->first(),
        ];
        $userdtl = Userdtl::where('cmodule', 'Tabel Barang')->where('username', $username)->first();
        if ($userdtl->pakai == '1') {
            return view('tbbarang.index')->with($data);
        } else {
            return redirect('home');
        }
    }
    public function tbbarangajax(Request $request) //: View
    {
        if ($request->ajax()) {
            $data = Tbbarang::select('*'); //->orderBy('kode', 'asc');
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('kode1', function ($row) {
                    $id = $row['id'];
                    $btn = $row['kode']; //'<a href="#"' . 'onclick=detail(' . $id . ')>' .  $row['kode'] . '</a>';
                    return $btn;
                })
                ->rawColumns(['kode1'])
                ->make(true);
            return view('tbbarang');
        }
    }

    public function tabel_barang(Request $request)
    {
        $data = [
            'menu' => 'file',
            'submenu' => 'tbbarang',
            'submenu1' => 'ref_umum',
            'title' => 'Tabel Barang',
            'tbbarang' => Tbbarang::orderBy('kode', 'asc')->get(),
        ];
        // var_dump($data);
        return view('tbbarang.tabel_barang')->with($data);
    }

    public function create(Request $request)
    {
        if ($request->Ajax()) {
            $username = session('username');
            $data = [
                'menu' => 'file',
                'submenu' => 'tbbarang',
                'submenu1' => 'ref_umum',
                'title' => 'Tambah Data Tabel barang',
            ];
            return response()->json([
                'body' => view('tbbarang.modaltambahmaster', [
                    'tambahtbnegara' => Userdtl::where('cmodule', 'Tabel Negara')->where('username', $username)->first(),
                    'tambahtbjnbrg' => Userdtl::where('cmodule', 'Tabel Jenis Barang')->where('username', $username)->first(),
                    'tambahtbsatuan' => Userdtl::where('cmodule', 'Tabel Satuan')->where('username', $username)->first(),
                    'tambahtbmove' => Userdtl::where('cmodule', 'Tabel Perputaran Barang')->where('username', $username)->first(),
                    'tambahtbdisc' => Userdtl::where('cmodule', 'Tabel Discount')->where('username', $username)->first(),
                    'tambahtbgudang' => Userdtl::where('cmodule', 'Tabel Gudang')->where('username', $username)->first(),
                    'tbbarang' => new Tbbarang(),
                    'action' => route('tbbarang.store'),
                    'vdata' => $data,
                ])->render(),
                'data' => $data,

            ]);
        } else {
            exit('Maaf tidak dapat diproses');
        }
    }

    public function store(TbbarangRequest $request, Tbbarang $tbbarang)
    {
        if ($request->Ajax()) {
            $validated = $request->validated();
            if ($validated) {
                $tbbarang->fill([
                    'kode' => isset($request->kode) ? $request->kode : '',
                    'nama' => isset($request->nama) ? $request->nama : '',
                    'kdgudang' => isset($request->kdgudang) ? $request->kdgudang : '',
                    'lokasi' => isset($request->lokasi) ? $request->lokasi : '',
                    'merek' => isset($request->merek) ? $request->merek : '',
                    'kdjnbrg' => isset($request->kdjnbrg) ? $request->kdjnbrg : '',
                    'kdsatuan' => isset($request->kdsatuan) ? $request->kdsatuan : '',
                    'nmsatuan' => isset($request->nmsatuan) ? $request->nmsatuan : '',
                    'kdnegara' => isset($request->kdnegara) ? $request->kdnegara : '',
                    'kdmove' => isset($request->kdmove) ? $request->kdmove : '',
                    'kddiscount' => isset($request->kddiscount) ? $request->kddiscount : '',
                    'harga_beli' => isset($request->harga_beli) ? $request->harga_beli : '',
                    'harga_jual' => isset($request->harga_jual) ? $request->harga_jual : '',
                    'stock_min' => isset($request->stock_min) ? $request->stock_min : '',
                    'stock_mak' => isset($request->stock_mak) ? $request->stock_mak : '',
                    'nobatch' => isset($request->nobatch) ? $request->nobatch : '',
                    'tglexpired' => isset($request->tglexpired) ? $request->tglexpired : '',
                    'user' => 'Tambah-' . $request->username . ', ' . date('d-m-Y h:i:s'),
                ]);
                $tbbarang->save($validated);
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
    public function show(Tbbarang $tbbarang, Request $request)
    {
        $id = $_GET['id'];
        $username = session('username');
        if ($request->Ajax()) {
            $data = [
                'menu' => 'file',
                'submenu' => 'tbbarang',
                'submenu1' => 'ref_umum',
                'title' => 'Detail Tabel barang',
                // 'tbbarang' => Tbbarang::findOrFail($id),
                // 'userdtl' => Userdtl::where('cmodule', 'Tabel Barang')->where('username', $username)->first(),
            ];
            // return view('tbbarang.modaldetail')->with($data);
            return response()->json([
                'body' => view('tbbarang.modaltambahmaster', [
                    'tambahtbnegara' => Userdtl::where('cmodule', 'Tabel Negara')->where('username', $username)->first(),
                    'tambahtbjnbrg' => Userdtl::where('cmodule', 'Tabel Jenis Barang')->where('username', $username)->first(),
                    'tambahtbsatuan' => Userdtl::where('cmodule', 'Tabel Satuan')->where('username', $username)->first(),
                    'tambahtbmove' => Userdtl::where('cmodule', 'Tabel Perputaran Barang')->where('username', $username)->first(),
                    'tambahtbdisc' => Userdtl::where('cmodule', 'Tabel Discount')->where('username', $username)->first(),
                    'tambahtbgudang' => Userdtl::where('cmodule', 'Tabel Gudang')->where('username', $username)->first(),
                    'tbnegara' => Tbnegara::where('kode', $tbbarang->kdnegara)->first(),
                    'tbbarang' => Tbbarang::findOrFail($id),
                    'action' => route('tbbarang.store'),
                    'vdata' => $data,
                ])->render(),
                'data' => $data,
            ]);
        } else {
            exit('Maaf tidak dapat diproses');
        }
    }

    public function edit(Tbbarang $tbbarang, Request $request)
    {
        if ($request->Ajax()) {
            $username = session('username');
            $data = [
                'menu' => 'file',
                'submenu' => 'tbbarang',
                'submenu1' => 'ref_umum',
                'title' => 'Edit Data Tabel barang',
            ];
            // var_dump($data);

            // return response()->json([
            //     'data' => $data,
            // ]);
            return response()->json([
                'body' => view('tbbarang.modaltambahmaster', [
                    'tambahtbnegara' => Userdtl::where('cmodule', 'Tabel Negara')->where('username', $username)->first(),
                    'tambahtbjnbrg' => Userdtl::where('cmodule', 'Tabel Jenis Barang')->where('username', $username)->first(),
                    'tambahtbsatuan' => Userdtl::where('cmodule', 'Tabel Satuan')->where('username', $username)->first(),
                    'tambahtbmove' => Userdtl::where('cmodule', 'Tabel Perputaran Barang')->where('username', $username)->first(),
                    'tambahtbdisc' => Userdtl::where('cmodule', 'Tabel Discount')->where('username', $username)->first(),
                    'tambahtbgudang' => Userdtl::where('cmodule', 'Tabel Gudang')->where('username', $username)->first(),
                    'tbnegara' => Tbnegara::where('kode', $tbbarang->kdnegara)->first(),
                    'tbbarang' => $tbbarang,
                    'action' => route('tbbarang.update', $tbbarang->id),
                    'vdata' => $data,
                ])->render(),
                'data' => $data,
            ]);
        } else {
            exit('Maaf tidak dapat diproses');
        }
    }

    public function update(TbbarangRequest $request, Tbbarang $tbbarang)
    {
        if ($request->Ajax()) {
            if ($request->kode === $request->kodelama) {
                $validated = $request->validated();
            } else {
                $validated = $request->validated();
            }
            if ($validated) {
                $tbbarang->fill([
                    'kode' => isset($request->kode) ? $request->kode : '',
                    'nama' => isset($request->nama) ? $request->nama : '',
                    'kdgudang' => isset($request->kdgudang) ? $request->kdgudang : '',
                    'lokasi' => isset($request->lokasi) ? $request->lokasi : '',
                    'merek' => isset($request->merek) ? $request->merek : '',
                    'kdjnbrg' => isset($request->kdjnbrg) ? $request->kdjnbrg : '',
                    'kdsatuan' => isset($request->kdsatuan) ? $request->kdsatuan : '',
                    'nmsatuan' => isset($request->nmsatuan) ? $request->nmsatuan : '',
                    'kdnegara' => isset($request->kdnegara) ? $request->kdnegara : '',
                    'kdmove' => isset($request->kdmove) ? $request->kdmove : '',
                    'kddiscount' => isset($request->kddiscount) ? $request->kddiscount : '',
                    'harga_beli' => isset($request->harga_beli) ? $request->harga_beli : '',
                    'harga_jual' => isset($request->harga_jual) ? $request->harga_jual : '',
                    'stock_min' => isset($request->stock_min) ? $request->stock_min : '',
                    'stock_mak' => isset($request->stock_mak) ? $request->stock_mak : '',
                    'nobatch' => isset($request->nobatch) ? $request->nobatch : '',
                    'tglexpired' => isset($request->tglexpired) ? $request->tglexpired : '',
                    'user' => 'Update-' . $request->username . ', ' . date('d-m-Y h:i:s'),
                ]);
                $tbbarang->save($validated);
                if ($request->kodelama <> $request->kode) {
                    $cekPod = Pod::where('kdbarang', $request->kodelama)->first();
                    if (isset($cekPod)) {
                        Pod::where('kdbarang', $request->kodelama)->update(['kdbarang' => $request->kode]);
                    }
                    $cekBelid = Belid::where('kdbarang', $request->kodelama)->first();
                    if (isset($cekBelid)) {
                        Belid::where('kdbarang', $request->kodelama)->update(['kdbarang' => $request->kode]);
                    }
                    $cekTerimad = Terimad::where('kdbarang', $request->kodelama)->first();
                    if (isset($cekTerimad)) {
                        Terimad::where('kdbarang', $request->kodelama)->update(['kdbarang' => $request->kode]);
                    }
                    $cekSod = Sod::where('kdbarang', $request->kodelama)->first();
                    if (isset($cekSod)) {
                        Sod::where('kdbarang', $request->kodelama)->update(['kdbarang' => $request->kode]);
                    }
                    $cekjuald = Juald::where('kdbarang', $request->kodelama)->first();
                    if (isset($cekjuald)) {
                        Juald::where('kdbarang', $request->kodelama)->update(['kdbarang' => $request->kode]);
                    }
                    $cekjuald = Keluard::where('kdbarang', $request->kodelama)->first();
                    if (isset($cekjuald)) {
                        Keluard::where('kdbarang', $request->kodelama)->update(['kdbarang' => $request->kode]);
                    }
                }
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

    public function destroy(Tbbarang $tbbarang, Request $request)
    {
        if ($request->Ajax()) {
            $terpakai = 0;
            $rowtbbarang = Tbbarang::where('id', $request->id)->first();
            $kdbarang = $rowtbbarang->kode;
            if ($terpakai == 0) {
                $Pod = Pod::where('kdbarang', $kdbarang)->first();
                if (isset($Pod->kdbarang)) {
                    $terpakai = 1;
                }
            }
            if ($terpakai == 0) {
                $Belid = Belid::where('kdbarang', $kdbarang)->first();
                if (isset($Belid->kdbarang)) {
                    $terpakai = 1;
                }
            }
            if ($terpakai == 0) {
                $Terimad = Terimad::where('kdbarang', $kdbarang)->first();
                if (isset($Terimad->kdbarang)) {
                    $terpakai = 1;
                }
            }
            if ($terpakai == 0) {
                $sod = Sod::where('kdbarang', $kdbarang)->first();
                if (isset($sod->kdbarang)) {
                    $terpakai = 1;
                }
            }
            if ($terpakai == 0) {
                $juald = Juald::where('kdbarang', $kdbarang)->first();
                if (isset($juald->kdbarang)) {
                    $terpakai = 1;
                }
            }
            if ($terpakai == 0) {
                $Keluard = Keluard::where('kdbarang', $kdbarang)->first();
                if (isset($Keluard->kdbarang)) {
                    $terpakai = 1;
                }
            }
            if ($terpakai == 0) {
                $tbbarang->delete();
                return response()->json([
                    'sukses' => true,
                ]);
            } else {
                return response()->json([
                    'sukses' => false,
                ]);
            }
            // return redirect()->back()->with('message', 'Berhasil di hapus');
        } else {
            exit('Maaf tidak dapat diproses');
        }
    }

    public function ambildatatbgudang(Request $request, Tbgudang $tbgudang)
    {
        if ($request->Ajax()) {
            $kdgudang = $request->kdgudang;
            $datatbgudang = $tbgudang->orderBy('nama')->get();
            $isidata = "<option value='' selected>[Pilih Gudang]</option>";
            foreach ($datatbgudang as $row) {
                if ($row['kode'] == $kdgudang) {
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

    public function tambahtbgudang(Request $request)
    {
        if ($request->Ajax()) {
            $data = [
                'menu' => 'file',
                'submenu' => 'tbbarang',
                'title' => 'Tambah Data Tabel Gudang',
                // 'tbgudang' => $this->tbgudangModel->getid()
            ];
            // dd($data);
            $msg = [
                'data' => view('tbbarang/tambahtbgudang', $data),
            ];
            echo json_encode($msg);
        } else {
            exit('Maaf tidak dapat diproses');
        }
    }

    public function ambildatatbsatuan(Request $request, Tbsatuan $tbsatuan)
    {
        if ($request->Ajax()) {
            $kdsatuan = $request->kdsatuan;
            $datatbsatuan = $tbsatuan->orderBy('nama')->get();
            $isidata = "<option value='' selected>[Pilih Satuan]</option>";
            foreach ($datatbsatuan as $row) {
                if ($row['kode'] == $kdsatuan) {
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

    public function tambahtbsatuan(Request $request)
    {
        if ($request->Ajax()) {
            $data = [
                'menu' => 'file',
                'submenu' => 'tbbarang',
                'title' => 'Tambah Data Tabel Satuan',
                // 'tbsatuan' => $this->tbsatuanModel->getid()
            ];
            // dd($data);
            $msg = [
                'data' => view('tbbarang/tambahtbsatuan', $data),
            ];
            echo json_encode($msg);
        } else {
            exit('Maaf tidak dapat diproses');
        }
    }

    public function ambildatatbmove(Request $request, Tbmove $tbmove)
    {
        if ($request->Ajax()) {
            $kdmove = $request->kdmove;
            $datatbmove = $tbmove->orderBy('nama')->get();
            $isidata = "<option value='' selected>[Pilih Perputaran Barang]</option>";
            foreach ($datatbmove as $row) {
                if ($row['kode'] == $kdmove) {
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

    public function tambahtbmove(Request $request)
    {
        if ($request->Ajax()) {
            $data = [
                'menu' => 'file',
                'submenu' => 'tbbarang',
                'title' => 'Tambah Data Tabel Move',
                // 'tbmove' => $this->tbmoveModel->getid()
            ];
            // dd($data);
            $msg = [
                'data' => view('tbbarang/tambahtbmove', $data),
            ];
            echo json_encode($msg);
        } else {
            exit('Maaf tidak dapat diproses');
        }
    }

    public function ambildatatbjnbrg(Request $request, Tbjnbrg $tbjnbrg)
    {
        if ($request->Ajax()) {
            $kdjnbrg = $request->kdjnbrg;
            $datatbjnbrg = $tbjnbrg->orderBy('nama')->get();
            $isidata = "<option value='' selected>[Pilih Jenis Barang]</option>";
            foreach ($datatbjnbrg as $row) {
                if ($row['kode'] == $kdjnbrg) {
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

    public function tambahtbjnbrg(Request $request)
    {
        if ($request->Ajax()) {
            $data = [
                'menu' => 'file',
                'submenu' => 'tbbarang',
                'title' => 'Tambah Data Tabel Jenis Barang',
                // 'tbjnbrg' => $this->tbjnbrgModel->getid()
            ];
            // dd($data);
            $msg = [
                'data' => view('tbbarang/tambahtbjnbrg', $data),
            ];
            echo json_encode($msg);
        } else {
            exit('Maaf tidak dapat diproses');
        }
    }

    public function ambildatatbdiscount(Request $request, Tbdiscount $tbdiscount)
    {
        if ($request->Ajax()) {
            $kddiscount = $request->kddiscount;
            $datatbdiscount = $tbdiscount->orderBy('nama')->get();
            $isidata = "<option value='' selected>[Pilih Kode Discount]</option>";
            foreach ($datatbdiscount as $row) {
                if ($row['kode'] == $kddiscount) {
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

    public function tambahtbdiscount(Request $request)
    {
        if ($request->Ajax()) {
            $data = [
                'menu' => 'file',
                'submenu' => 'tbbarang',
                'title' => 'Tambah Data Tabel Jenis Barang',
                // 'tbdiscount' => $this->tbdiscountModel->getid()
            ];
            // dd($data);
            $msg = [
                'data' => view('tbbarang/tambahtbdiscount', $data),
            ];
            echo json_encode($msg);
        } else {
            exit('Maaf tidak dapat diproses');
        }
    }

    public function carinegara(Request $request)
    {
        if ($request->Ajax()) {
            $data = [
                // 'menu' => 'file',
                // 'submenu' => 'tbcustomer',
                // 'submenu1' => 'ref_umum',
                'title' => 'Cari Negara',
            ];
            // var_dump($data);
            return response()->json([
                'body' => view('tbbarang.modalcari', [
                    'tbnegara' => Tbnegara::all(),
                    'vdata' => $data,
                ])->render(),
                'data' => $data,
            ]);
        } else {
            exit('Maaf tidak dapat diproses');
        }
    }
    public function replnegara(Request $request)
    {
        if ($request->Ajax()) {
            $kode = $_GET['kode'];
            $row = Tbnegara::where('kode', $kode)->first();
            if (isset($row)) {
                $data = [
                    'kdnegara' => $row['kode'],
                    'nmnegara' => $row['nama'],
                ];
            } else {
                $data = [
                    'kdnegara' => '',
                    'nmnegara' => '',
                ];
            }
            echo json_encode($data);
        } else {
            exit('Maaf tidak dapat diproses');
        }
    }

    public function download(Request $request)
    {
        $data = [
            'tbbarang' => Tbbarang::leftjoin('tbmove', 'tbmove.kode', '=', 'tbbarang.kdmove')
                ->leftjoin('tbsatuan', 'tbsatuan.kode', '=', 'tbbarang.kdsatuan')
                ->leftjoin('tbjnbrg', 'tbjnbrg.kode', '=', 'tbbarang.kdjnbrg')
                ->leftjoin('tbnegara', 'tbnegara.kode', '=', 'tbbarang.kdnegara')
                ->leftjoin('tbdiscount', 'tbdiscount.kode', '=', 'tbbarang.kddiscount')
                ->select(
                    'tbbarang.*',
                    'tbmove.nama as nmmove',
                    'tbsatuan.nama as nmsatuan',
                    'tbjnbrg.nama as nmjnbrg',
                    'tbjnbrg.nama as nmjnbrg',
                    'tbdiscount.nama as nmdisc'
                )
                ->orderBy('tbbarang.kode', 'asc')->get(),
        ];
        return view('tbbarang.export')->with($data);
        $msg = [
            'sukses' => true
        ];
        echo json_encode($msg);
    }
}
