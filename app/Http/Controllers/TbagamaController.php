<?php

namespace App\Http\Controllers;

use App\Http\Requests\TbagamaRequest;
use Illuminate\Http\Request;
use Session;
// use Yajra\DataTables\Contracts\DataTables;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Tbagama;
use App\Models\Userdtl;

// //return type View
// use Illuminate\View\View;

class TbagamaController extends Controller
{
    public function index(Request $request) //: View
    {
        $username = session('username');
        $data = [
            'menu' => 'file',
            'submenu' => 'tbagama',
            'submenu1' => 'ref_umum',
            'title' => 'Tabel agama',
            // 'tbagama' => Tbagama::all(),
            'userdtlmenu' => Userdtl::join('tbmodule', 'userdtl.cmodule', '=', 'tbmodule.cmodule')->where('userdtl.pakai', '1')->where('username', $username)->orderBy('userdtl.nurut')->get(),
            'userdtl' => Userdtl::where('cmodule', 'Tabel Agama')->where('username', $username)->first(),
        ];
        $userdtl = Userdtl::where('cmodule', 'Tabel Agama')->where('username', $username)->first();
        if ($userdtl->pakai == '1') {
            return view('tbagama.index')->with($data);
        } else {
            return redirect('home');
        }
    }
    public function tbagamaajax(Request $request) //: View
    {
        if ($request->ajax()) {
            $data = Tbagama::select('*'); //->orderBy('kode', 'asc');
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
            return view('tbagama');
        }
    }

    public function tabel_agama(Request $request)
    {
        $data = [
            'menu' => 'file',
            'submenu' => 'tbagama',
            'submenu1' => 'ref_umum',
            'title' => 'Tabel agama',
            'tbagama' => Tbagama::orderBy('kode', 'asc')->get(),
        ];
        // var_dump($data);
        return view('tbagama.tabel_agama')->with($data);
    }

    public function create(Request $request)
    {
        if ($request->Ajax()) {
            $data = [
                'menu' => 'file',
                'submenu' => 'tbagama',
                'submenu1' => 'ref_umum',
                'title' => 'Tambah Data Tabel agama',
                'tbagama' => Tbagama::all(),
            ];
            // var_dump($data);
            return response()->json([
                'body' => view('tbagama.modaltambah', [
                    'tbagama' => new Tbagama(), //Tbagama::first(),
                    'action' => route('tbagama.store'),
                    'vdata' => $data,
                ])->render(),
                'data' => $data,

            ]);
        } else {
            exit('Maaf tidak dapat diproses');
        }
    }

    public function store(TbagamaRequest $request, Tbagama $tbagama)
    {
        if ($request->Ajax()) {
            $validated = $request->validated(
                // [
                //     'kode' => 'required|unique:tbagama,kode',
                //     'nama' => 'required',
                // ],
                // [
                //     'kode.unique' => 'Kode tidak boleh sama',
                //     'kode.required' => 'Kode harus di isi',
                //     'nama.required' => 'Nama harus di isi',
                // ]
            );
            if ($validated) {
                // $tbagama->fill($request->all());
                // $tbagama->aktif = $request->aktif == 'on' ? 'Y' : 'N';
                // $tbagama->user = $request->username . date('d-m-Y');
                $aktif = isset($request->aktif) ? 'Y' : 'N';
                $tbagama->fill([
                    'nama' => $request->nama,
                    'kode' => $request->kode,
                    'aktif' => $aktif,
                    'user' => 'Tambah-' . $request->username . ', ' . date('d-m-Y h:i:s'),
                ]);
                $tbagama->save($validated);
                $msg = [
                    'sukses' => 'Data berhasil di tambah', //view('tbagama.tabel_agama')
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
                'submenu' => 'tbagama',
                'submenu1' => 'ref_umum',
                'title' => 'Detail Tabel agama',
                'tbagama' => Tbagama::findOrFail($id),
                'userdtl' => Userdtl::where('cmodule', 'Tabel agama')->where('username', $username)->first(),
            ];
            // return view('tbagama.modaldetail')->with($data);
            return response()->json([
                'body' => view('tbagama.modaltambah', [
                    'tbagama' => Tbagama::findOrFail($id),
                    'action' => route('tbagama.store'),
                    'vdata' => $data,
                ])->render(),
                'data' => $data,
            ]);
        } else {
            exit('Maaf tidak dapat diproses');
        }
    }

    public function edit(Tbagama $tbagama, Request $request)
    {
        if ($request->Ajax()) {
            $data = [
                'menu' => 'file',
                'submenu' => 'tbagama',
                'submenu1' => 'ref_umum',
                'title' => 'Edit Data Tabel agama',
            ];
            return response()->json([
                'body' => view('tbagama.modaltambah', [
                    'tbagama' => $tbagama,
                    'action' => route('tbagama.update', $tbagama->id),
                    'vdata' => $data,
                ])->render(),
                'data' => $data,
            ]);
        } else {
            exit('Maaf tidak dapat diproses');
        }
    }

    public function update(TbagamaRequest $request, Tbagama $tbagama)
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
                    //     'kode' => 'required|unique:tbagama,kode',
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
                $tbagama->fill([
                    'nama' => $request->nama,
                    'kode' => $request->kode,
                    'aktif' => $aktif,
                    'user' => 'Update-' . $request->username . ', ' . date('d-m-Y h:i:s'),
                ]);
                $tbagama->save($validated);
                $msg = [
                    'sukses' => 'Data berhasil di update', //view('tbagama.tabel_agama')
                ];
            } else {
                $msg = [
                    'sukses' => 'Data gagal di update', //view('tbagama.tabel_agama')
                ];
            }
            echo json_encode($msg);
            // return redirect()->back()->with('message', 'Berhasil di update');
        } else {
            exit('Maaf tidak dapat diproses');
        }
    }

    public function destroy(Tbagama $tbagama, Request $request)
    {
        if ($request->Ajax()) {
            $tbagama->delete();
            return response()->json([
                'sukses' => 'Data berhasil di hapus',
            ]);
            // return redirect()->back()->with('message', 'Berhasil di hapus');
        } else {
            exit('Maaf tidak dapat diproses');
        }
    }
}
