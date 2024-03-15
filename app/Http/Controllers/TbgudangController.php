<?php

namespace App\Http\Controllers;

use App\Http\Requests\TbgudangRequest;
use Illuminate\Http\Request;
use Session;
// use Yajra\DataTables\Contracts\DataTables;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Tbgudang;
use App\Models\Tbbarang;
use App\Models\Userdtl;

// //return type View
// use Illuminate\View\View;

class TbgudangController extends Controller
{
    public function index(Request $request) //: View
    {
        $username = session('username');
        $data = [
            'menu' => 'file',
            'submenu' => 'tbgudang',
            'submenu1' => 'ref_umum',
            'title' => 'Tabel Gudang',
            // 'tbGudang' => TbGudang::all(),
            'userdtlmenu' => Userdtl::join('tbmodule', 'userdtl.cmodule', '=', 'tbmodule.cmodule')->where('userdtl.pakai', '1')->where('username', $username)->orderBy('userdtl.nurut')->get(),
            'userdtl' => Userdtl::where('cmodule', 'Tabel Gudang')->where('username', $username)->first(),
        ];
        $userdtl = Userdtl::where('cmodule', 'Tabel Gudang')->where('username', $username)->first();
        if ($userdtl->pakai == '1') {
            return view('tbgudang.index')->with($data);
        } else {
            return redirect('home');
        }
    }
    public function tbgudangajax(Request $request) //: View
    {
        if ($request->ajax()) {
            $data = Tbgudang::select('*'); //->orderBy('kode', 'asc');
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('kode1', function ($row) {
                    $id = $row['id'];
                    $btn = $row['kode']; //'<a href="#"' . 'onclick=detail(' . $id . ')>' .  $row['kode'] . '</a>';
                    return $btn;
                })
                ->rawColumns(['kode1'])
                ->make(true);
            return view('tbgudang');
        }
    }

    public function tabel_gudang(Request $request)
    {
        $data = [
            'menu' => 'file',
            'submenu' => 'tbgudang',
            'submenu1' => 'ref_umum',
            'title' => 'Tabel Gudang',
            'tbgudang' => Tbgudang::orderBy('kode', 'asc')->get(),
        ];
        // var_dump($data);
        return view('tbgudang.tabel_gudang')->with($data);
    }

    public function create(Request $request)
    {
        if ($request->Ajax()) {
            $data = [
                'menu' => 'file',
                'submenu' => 'tbgudang',
                'submenu1' => 'ref_umum',
                'title' => 'Tambah Data Tabel gudang',
            ];
            return response()->json([
                'body' => view('tbgudang.modaltambah', [
                    'tbgudang' => new Tbgudang(),
                    'action' => route('tbgudang.store'),
                    'vdata' => $data,
                ])->render(),
                'data' => $data,

            ]);
        } else {
            exit('Maaf tidak dapat diproses');
        }
    }

    public function store(TbgudangRequest $request, Tbgudang $tbgudang)
    {
        if ($request->Ajax()) {
            $validated = $request->validated();
            if ($validated) {
                $tbgudang->fill([
                    'nama' => $request->nama,
                    'kode' => $request->kode,
                    'user' => 'Tambah-' . $request->username . ', ' . date('d-m-Y h:i:s'),
                ]);
                $tbgudang->save($validated);
                $msg = [
                    'sukses' => 'Data berhasil di tambah', //view('tbgudang.tabel_gudang')
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
                'menu' => 'file',
                'submenu' => 'tbgudang',
                'submenu1' => 'ref_umum',
                'title' => 'Detail Tabel gudang',
                'tbgudang' => Tbgudang::findOrFail($id),
                'userdtl' => Userdtl::where('cmodule', 'Tabel gudang')->where('username', $username)->first(),
            ];
            // return view('tbgudang.modaldetail')->with($data);
            return response()->json([
                'body' => view('tbgudang.modaltambah', [
                    'tbgudang' => Tbgudang::findOrFail($id),
                    'action' => route('tbgudang.store'),
                    'vdata' => $data,
                ])->render(),
                'data' => $data,
            ]);
        } else {
            exit('Maaf tidak dapat diproses');
        }
    }

    public function edit(Tbgudang $tbgudang, Request $request)
    {
        if ($request->Ajax()) {
            $data = [
                'menu' => 'file',
                'submenu' => 'tbgudang',
                'submenu1' => 'ref_umum',
                'title' => 'Edit Data Tabel gudang',
            ];
            // var_dump($data);

            // return response()->json([
            //     'data' => $data,
            // ]);
            return response()->json([
                'body' => view('tbgudang.modaltambah', [
                    'tbgudang' => $tbgudang,
                    'action' => route('tbgudang.update', $tbgudang->id),
                    'vdata' => $data,
                ])->render(),
                'data' => $data,
            ]);
        } else {
            exit('Maaf tidak dapat diproses');
        }
    }

    public function update(TbgudangRequest $request, Tbgudang $tbgudang)
    {
        if ($request->Ajax()) {
            if ($request->kode === $request->kodelama) {
                $validated = $request->validated();
            } else {
                $validated = $request->validated();
            }
            if ($validated) {
                $tbgudang->fill([
                    'nama' => $request->nama,
                    'kode' => $request->kode,
                    'user' => 'Update-' . $request->username . ', ' . date('d-m-Y h:i:s'),
                ]);
                $tbgudang->save($validated);
                if ($request->kodelama <> $request->kode) {
                    $cekTbbarang = Tbbarang::where('kdgudang', $request->kodelama)->first();
                    if (isset($cekTbbarang)) {
                        Tbbarang::where('kdgudang', $request->kodelama)->update(['kdgudang' => $request->kode]);
                    }
                }
                $msg = [
                    'sukses' => 'Data berhasil di update', //view('tbgudang.tabel_gudang')
                ];
            } else {
                $msg = [
                    'sukses' => 'Data gagal di update', //view('tbgudang.tabel_gudang')
                ];
            }
            echo json_encode($msg);
            // return redirect()->back()->with('message', 'Berhasil di update');
        } else {
            exit('Maaf tidak dapat diproses');
        }
    }

    public function destroy(Tbgudang $tbgudang, Request $request)
    {
        if ($request->Ajax()) {
            $terpakai = 0;
            $rowTbgudang = Tbgudang::where('id', $request->id)->first();
            $kode = $rowTbgudang->kode;
            $Tbbarang = Tbbarang::where('kdgudang', $kode)->first();
            if (isset($Tbbarang)) {
                $terpakai = 1;
            }
            if ($terpakai == 0) {
                $tbgudang->delete();
                return response()->json([
                    'sukses' => 'Data berhasil di hapus',
                ]);
            } else {
                return response()->json([
                    'sukses' => false,
                ]);
            }
        } else {
            exit('Maaf tidak dapat diproses');
        }
    }
}
