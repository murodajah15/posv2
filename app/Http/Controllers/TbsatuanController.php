<?php

namespace App\Http\Controllers;

use App\Http\Requests\TbsatuanRequest;
use Illuminate\Http\Request;
use Session;
// use Yajra\DataTables\Contracts\DataTables;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Tbsatuan;
use App\Models\Tbbarang;
use App\Models\Userdtl;

// //return type View
// use Illuminate\View\View;

class TbsatuanController extends Controller
{
    public function index(Request $request) //: View
    {
        $username = session('username');
        $data = [
            'menu' => 'file',
            'submenu' => 'tbsatuan',
            'submenu1' => 'ref_umum',
            'title' => 'Tabel Satuan',
            // 'tbsatuan' => Tbsatuan::all(),
            'userdtlmenu' => Userdtl::join('tbmodule', 'userdtl.cmodule', '=', 'tbmodule.cmodule')->where('userdtl.pakai', '1')->where('username', $username)->orderBy('userdtl.nurut')->get(),
            'userdtl' => Userdtl::where('cmodule', 'Tabel Satuan')->where('username', $username)->first(),
        ];
        $userdtl = Userdtl::where('cmodule', 'Tabel Satuan')->where('username', $username)->first();
        if ($userdtl->pakai == '1') {
            return view('tbsatuan.index')->with($data);
        } else {
            return redirect('home');
        }
    }
    public function tbsatuanajax(Request $request) //: View
    {
        if ($request->ajax()) {
            $data = Tbsatuan::select('*'); //->orderBy('kode', 'asc');
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('kode1', function ($row) {
                    $id = $row['id'];
                    $btn = $row['kode']; //'<a href="#"' . 'onclick=detail(' . $id . ')>' .  $row['kode'] . '</a>';
                    return $btn;
                })
                ->rawColumns(['kode1'])
                // ->addIndexColumn()
                // ->addColumn('action', function ($row) {
                //     $btn = '<a href="javascript:void(0)" class="edit btn btn-primary btn-sm">View</a>';
                //     return $btn;
                // })
                // ->rawColumns(['action'])
                ->make(true);
            return view('tbsatuan');
        }
    }

    public function tabel_satuan(Request $request)
    {
        $data = [
            'menu' => 'file',
            'submenu' => 'tbsatuan',
            'submenu1' => 'ref_umum',
            'title' => 'Tabel Satuan',
            'tbsatuan' => Tbsatuan::orderBy('kode', 'asc')->get(),
        ];
        // var_dump($data);
        return view('tbsatuan.tabel_satuan')->with($data);
    }

    public function create(Request $request)
    {
        if ($request->Ajax()) {
            $data = [
                'menu' => 'file',
                'submenu' => 'tbsatuan',
                'submenu1' => 'ref_umum',
                'title' => 'Tambah Data Tabel Satuan',
                // 'tbsatuan' => Tbsatuan::all(),
            ];
            // var_dump($data);
            return response()->json([
                'body' => view('tbsatuan.modaltambah', [
                    'tbsatuan' => new Tbsatuan(), //Tbsatuan::first(),
                    'action' => route('tbsatuan.store'),
                    'vdata' => $data,
                ])->render(),
                'data' => $data,

            ]);
        } else {
            exit('Maaf tidak dapat diproses');
        }
    }

    public function store(TbsatuanRequest $request, Tbsatuan $tbsatuan)
    {
        if ($request->Ajax()) {
            $validated = $request->validated(
                // [
                //     'kode' => 'required|unique:tbsatuan,kode',
                //     'nama' => 'required',
                // ],
                // [
                //     'kode.unique' => 'Kode tidak boleh sama',
                //     'kode.required' => 'Kode harus di isi',
                //     'nama.required' => 'Nama harus di isi',
                // ]
            );
            if ($validated) {
                // $tbsatuan->fill($request->all());
                // $tbsatuan->aktif = $request->aktif == 'on' ? 'Y' : 'N';
                // $tbsatuan->user = $request->username . date('d-m-Y');
                $aktif = isset($request->aktif) ? 'Y' : 'N';
                $tbsatuan->fill([
                    'nama' => $request->nama,
                    'kode' => $request->kode,
                    'aktif' => $aktif,
                    'user' => 'Tambah-' . $request->username . ', ' . date('d-m-Y h:i:s'),
                ]);
                $tbsatuan->save($validated);
                $msg = [
                    'sukses' => 'Data berhasil di tambah', //view('tbsatuan.tabel_satuan')
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
        if ($request->Ajax()) {
            $id = $_GET['id'];
            $username = session('username');
            $data = [
                'menu' => 'file',
                'submenu' => 'tbsatuan',
                'submenu1' => 'ref_umum',
                'title' => 'Detail Tabel Satuan',
                'tbsatuan' => Tbsatuan::findOrFail($id),
                'userdtl' => Userdtl::where('cmodule', 'Tabel Satuan')->where('username', $username)->first(),
            ];
            // return view('tbsatuan.modaldetail')->with($data);
            return response()->json([
                'body' => view('tbsatuan.modaltambah', [
                    'tbsatuan' => Tbsatuan::findOrFail($id),
                    'action' => route('tbsatuan.store'),
                    'vdata' => $data,
                ])->render(),
                'data' => $data,
            ]);
        } else {
            exit('Maaf tidak dapat diproses');
        }
    }

    public function edit(Tbsatuan $tbsatuan, Request $request)
    {
        if ($request->Ajax()) {
            $data = [
                'menu' => 'file',
                'submenu' => 'tbsatuan',
                'submenu1' => 'ref_umum',
                'title' => 'Edit Data Tabel Satuan',
            ];
            return response()->json([
                'body' => view('tbsatuan.modaltambah', [
                    'tbsatuan' => $tbsatuan,
                    'action' => route('tbsatuan.update', $tbsatuan->id),
                    'vdata' => $data,
                ])->render(),
                'data' => $data,
            ]);
        } else {
            exit('Maaf tidak dapat diproses');
        }
    }

    public function update(TbsatuanRequest $request, Tbsatuan $tbsatuan)
    {
        if ($request->Ajax()) {
            if ($request->kode === $request->kodelama) {
                $validated = $request->validated(
                    // [
                    //     'kode' => 'required',
                    //     'nama' => 'required',
                    // ],
                    // [
                    //     'kode.required' => 'Kode harus di isi',
                    //     'nama.required' => 'Nama harus di isi',
                    // ]
                );
            } else {
                // var_dump($request->kode . '!=' . $request->kodelama);
                $validated = $request->validated(
                    // [
                    //     'kode' => 'required|unique:tbsatuan,kode',
                    //     'nama' => 'required',
                    // ],
                    // [
                    //     'kode.unique' => 'Kode tidak boleh sama',
                    //     'kode.required' => 'Kode harus di isi',
                    //     'nama.required' => 'Nama harus di isi',
                    // ]
                );
            }
            if ($validated) {
                $aktif = $request->aktif == 'on' ? 'Y' : 'N';
                $tbsatuan->fill([
                    'nama' => $request->nama,
                    'kode' => $request->kode,
                    'aktif' => $aktif,
                    'user' => 'Update-' . $request->username . ', ' . date('d-m-Y h:i:s'),
                ]);
                $tbsatuan->save($validated);
                if ($request->kodelama <> $request->kode) {
                    $cekTbbarang = Tbbarang::where('kdsatuan', $request->kodelama)->first();
                    if (isset($cekTbbarang)) {
                        Tbbarang::where('kdsatuan', $request->kodelama)->update(['kdsatuan' => $request->kode]);
                    }
                }
                $msg = [
                    'sukses' => 'Data berhasil di update', //view('tbsatuan.tabel_satuan')
                ];
            } else {
                $msg = [
                    'sukses' => 'Data gagal di update', //view('tbsatuan.tabel_satuan')
                ];
            }
            echo json_encode($msg);
            // return redirect()->back()->with('message', 'Berhasil di update');
        } else {
            exit('Maaf tidak dapat diproses');
        }
    }

    public function destroy(Tbsatuan $tbsatuan, Request $request)
    {
        if ($request->Ajax()) {
            $terpakai = 0;
            $rowTbsatuan = Tbsatuan::where('id', $request->id)->first();
            $kode = $rowTbsatuan->kode;
            $Tbbarang = Tbbarang::where('kdsatuan', $kode)->first();
            if (isset($Tbbarang)) {
                $terpakai = 1;
            }
            if ($terpakai == 0) {
                $tbsatuan->delete();
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
