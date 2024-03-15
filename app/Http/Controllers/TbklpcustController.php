<?php

namespace App\Http\Controllers;

use App\Http\Requests\TbklpcustRequest;
use Illuminate\Http\Request;
use Session;
// use Yajra\DataTables\Contracts\DataTables;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Tbklpcust;
use App\Models\Tbcustomer;
use App\Models\Userdtl;

// //return type View
// use Illuminate\View\View;

class TbklpcustController extends Controller
{
    public function index(Request $request) //: View
    {
        $username = session('username');
        $data = [
            'menu' => 'file',
            'submenu' => 'tbklpcust',
            'submenu1' => 'ref_umum',
            'title' => 'Tabel Kelompok Customer',
            // 'tbklpcust' => Tbklpcust::all(),
            'userdtlmenu' => Userdtl::join('tbmodule', 'userdtl.cmodule', '=', 'tbmodule.cmodule')->where('userdtl.pakai', '1')->where('username', $username)->orderBy('userdtl.nurut')->get(),
            'userdtl' => Userdtl::where('cmodule', 'Tabel Kelompok Customer')->where('username', $username)->first(),
        ];
        $userdtl = Userdtl::where('cmodule', 'Tabel Kelompok Customer')->where('username', $username)->first();
        if ($userdtl->pakai == '1') {
            return view('tbklpcust.index')->with($data);
        } else {
            return redirect('home');
        }
    }
    public function tbklpcustajax(Request $request) //: View
    {
        if ($request->ajax()) {
            $data = Tbklpcust::select('*'); //->orderBy('kode', 'asc');
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
            return view('tbklpcust');
        }
    }

    public function tabel_klpcust(Request $request)
    {
        $data = [
            'menu' => 'file',
            'submenu' => 'tbklpcust',
            'submenu1' => 'ref_umum',
            'title' => 'Tabel Kelompok Customer',
            'tbklpcust' => Tbklpcust::orderBy('kode', 'asc')->get(),
        ];
        // var_dump($data);
        return view('tbklpcust.tabel_klpcust')->with($data);
    }

    public function create(Request $request)
    {
        if ($request->Ajax()) {
            $data = [
                'menu' => 'file',
                'submenu' => 'tbklpcust',
                'submenu1' => 'ref_umum',
                'title' => 'Tambah Data Tabel klpcust',
                // 'tbklpcust' => Tbklpcust::all(),
            ];
            // var_dump($data);
            return response()->json([
                'body' => view('tbklpcust.modaltambah', [
                    'tbklpcust' => new Tbklpcust(), //Tbklpcust::first(),
                    'action' => route('tbklpcust.store'),
                    'vdata' => $data,
                ])->render(),
                'data' => $data,

            ]);
        } else {
            exit('Maaf tidak dapat diproses');
        }
    }

    public function store(TbklpcustRequest $request, Tbklpcust $tbklpcust)
    {
        if ($request->Ajax()) {
            $validated = $request->validated(
                // [
                //     'kode' => 'required|unique:tbklpcust,kode',
                //     'nama' => 'required',
                // ],
                // [
                //     'kode.unique' => 'Kode tidak boleh sama',
                //     'kode.required' => 'Kode harus di isi',
                //     'nama.required' => 'Nama harus di isi',
                // ]
            );
            if ($validated) {
                // $tbklpcust->fill($request->all());
                // $tbklpcust->aktif = $request->aktif == 'on' ? 'Y' : 'N';
                // $tbklpcust->user = $request->username . date('d-m-Y');
                $aktif = isset($request->aktif) ? 'Y' : 'N';
                $tbklpcust->fill([
                    'nama' => $request->nama,
                    'kode' => $request->kode,
                    'aktif' => $aktif,
                    'user' => 'Tambah-' . $request->username . ', ' . date('d-m-Y h:i:s'),
                ]);
                $tbklpcust->save($validated);
                $msg = [
                    'sukses' => 'Data berhasil di tambah', //view('tbklpcust.tabel_klpcust')
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
                'submenu' => 'tbklpcust',
                'submenu1' => 'ref_umum',
                'title' => 'Detail Tabel klpcust',
                'tbklpcust' => Tbklpcust::findOrFail($id),
                'userdtl' => Userdtl::where('cmodule', 'Tabel Kelompok Customer')->where('username', $username)->first(),
            ];
            // return view('tbklpcust.modaldetail')->with($data);
            return response()->json([
                'body' => view('tbklpcust.modaltambah', [
                    'tbklpcust' => Tbklpcust::findOrFail($id),
                    'action' => route('tbklpcust.store'),
                    'vdata' => $data,
                ])->render(),
                'data' => $data,
            ]);
        } else {
            exit('Maaf tidak dapat diproses');
        }
    }

    public function edit(Tbklpcust $tbklpcust, Request $request)
    {
        if ($request->Ajax()) {
            $data = [
                'menu' => 'file',
                'submenu' => 'tbklpcust',
                'submenu1' => 'ref_umum',
                'title' => 'Edit Data Tabel klpcust',
            ];
            return response()->json([
                'body' => view('tbklpcust.modaltambah', [
                    'tbklpcust' => $tbklpcust,
                    'action' => route('tbklpcust.update', $tbklpcust->id),
                    'vdata' => $data,
                ])->render(),
                'data' => $data,
            ]);
        } else {
            exit('Maaf tidak dapat diproses');
        }
    }

    public function update(TbklpcustRequest $request, Tbklpcust $tbklpcust)
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
                    //     'kode' => 'required|unique:tbklpcust,kode',
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
                $tbklpcust->fill([
                    'nama' => $request->nama,
                    'kode' => $request->kode,
                    'aktif' => $aktif,
                    'user' => 'Update-' . $request->username . ', ' . date('d-m-Y h:i:s'),
                ]);
                $tbklpcust->save($validated);
                if ($request->kodelama <> $request->kode) {
                    $cekTbcustomer = Tbcustomer::where('kdklpcust', $request->kodelama)->first();
                    if (isset($cekTbcustomer)) {
                        Tbcustomer::where('kdklpcust', $request->kodelama)->update(['kdklpcust' => $request->kode]);
                    }
                }
                $msg = [
                    'sukses' => 'Data berhasil di update', //view('tbklpcust.tabel_klpcust')
                ];
            } else {
                $msg = [
                    'sukses' => 'Data gagal di update', //view('tbklpcust.tabel_klpcust')
                ];
            }
            echo json_encode($msg);
            // return redirect()->back()->with('message', 'Berhasil di update');
        } else {
            exit('Maaf tidak dapat diproses');
        }
    }

    public function destroy(Tbklpcust $tbklpcust, Request $request)
    {
        if ($request->Ajax()) {
            $terpakai = 0;
            $rowTbklpcust = Tbklpcust::where('id', $request->id)->first();
            $kode = $rowTbklpcust->kode;
            $Tbcustomer = Tbcustomer::where('kdklpcust', $kode)->first();
            if (isset($Tbcustomer)) {
                $terpakai = 1;
            }
            if ($terpakai == 0) {
                $tbklpcust->delete();
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
