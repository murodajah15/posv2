<?php

namespace App\Http\Controllers;

use App\Http\Requests\TbnegaraRequest;
use Illuminate\Http\Request;
use Session;
// use Yajra\DataTables\Contracts\DataTables;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Tbnegara;
use App\Models\Tbbarang;
use App\Models\Userdtl;

// //return type View
// use Illuminate\View\View;

class TbnegaraController extends Controller
{
    public function index(Request $request) //: View
    {
        $username = session('username');
        $data = [
            'menu' => 'file',
            'submenu' => 'tbnegara',
            'submenu1' => 'ref_umum',
            'title' => 'Tabel Negara',
            // 'tbnegara' => Tbnegara::all(),
            'userdtlmenu' => Userdtl::join('tbmodule', 'userdtl.cmodule', '=', 'tbmodule.cmodule')->where('userdtl.pakai', '1')->where('username', $username)->orderBy('userdtl.nurut')->get(),
            'userdtl' => Userdtl::where('cmodule', 'Tabel Negara')->where('username', $username)->first(),
        ];
        $userdtl = Userdtl::where('cmodule', 'Tabel Negara')->where('username', $username)->first();
        if ($userdtl->pakai == '1') {
            return view('tbnegara.index')->with($data);
        } else {
            return redirect('home');
        }
    }
    public function tbnegaraajax(Request $request) //: View
    {
        if ($request->ajax()) {
            $data = Tbnegara::select('*'); //->orderBy('kode', 'asc');
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
            return view('tbnegara');
        }
    }

    public function tabel_negara(Request $request)
    {
        $data = [
            'menu' => 'file',
            'submenu' => 'tbnegara',
            'submenu1' => 'ref_umum',
            'title' => 'Tabel Negara',
            'tbnegara' => Tbnegara::orderBy('kode', 'asc')->get(),
        ];
        // var_dump($data);
        return view('tbnegara.tabel_negara')->with($data);
    }

    public function create(Request $request)
    {
        if ($request->Ajax()) {
            $data = [
                'menu' => 'file',
                'submenu' => 'tbnegara',
                'submenu1' => 'ref_umum',
                'title' => 'Tambah Data Tabel negara',
                // 'tbnegara' => Tbnegara::all(),
            ];
            // var_dump($data);
            return response()->json([
                'body' => view('tbnegara.modaltambah', [
                    'tbnegara' => new Tbnegara(), //Tbnegara::first(),
                    'action' => route('tbnegara.store'),
                    'vdata' => $data,
                ])->render(),
                'data' => $data,

            ]);
        } else {
            exit('Maaf tidak dapat diproses');
        }
    }

    public function store(TbnegaraRequest $request, Tbnegara $tbnegara)
    {
        if ($request->Ajax()) {
            $validated = $request->validated(
                // [
                //     'kode' => 'required|unique:tbnegara,kode',
                //     'nama' => 'required',
                // ],
                // [
                //     'kode.unique' => 'Kode tidak boleh sama',
                //     'kode.required' => 'Kode harus di isi',
                //     'nama.required' => 'Nama harus di isi',
                // ]
            );
            if ($validated) {
                // $tbnegara->fill($request->all());
                // $tbnegara->aktif = $request->aktif == 'on' ? 'Y' : 'N';
                // $tbnegara->user = $request->username . date('d-m-Y');
                $aktif = isset($request->aktif) ? 'Y' : 'N';
                $tbnegara->fill([
                    'nama' => $request->nama,
                    'kode' => $request->kode,
                    'aktif' => $aktif,
                    'user' => 'Tambah-' . $request->username . ', ' . date('d-m-Y h:i:s'),
                ]);
                $tbnegara->save($validated);
                $msg = [
                    'sukses' => 'Data berhasil di tambah', //view('tbnegara.tabel_negara')
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
                'submenu' => 'tbnegara',
                'submenu1' => 'ref_umum',
                'title' => 'Detail Tabel negara',
                'tbnegara' => Tbnegara::findOrFail($id),
                'userdtl' => Userdtl::where('cmodule', 'Tabel Negara')->where('username', $username)->first(),
            ];
            // return view('tbnegara.modaldetail')->with($data);
            return response()->json([
                'body' => view('tbnegara.modaltambah', [
                    'tbnegara' => Tbnegara::findOrFail($id),
                    'action' => route('tbnegara.store'),
                    'vdata' => $data,
                ])->render(),
                'data' => $data,
            ]);
        } else {
            exit('Maaf tidak dapat diproses');
        }
    }

    public function edit(Tbnegara $tbnegara, Request $request)
    {
        if ($request->Ajax()) {
            $data = [
                'menu' => 'file',
                'submenu' => 'tbnegara',
                'submenu1' => 'ref_umum',
                'title' => 'Edit Data Tabel negara',
            ];
            return response()->json([
                'body' => view('tbnegara.modaltambah', [
                    'tbnegara' => $tbnegara,
                    'action' => route('tbnegara.update', $tbnegara->id),
                    'vdata' => $data,
                ])->render(),
                'data' => $data,
            ]);
        } else {
            exit('Maaf tidak dapat diproses');
        }
    }

    public function update(TbnegaraRequest $request, Tbnegara $tbnegara)
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
                    //     'kode' => 'required|unique:tbnegara,kode',
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
                $tbnegara->fill([
                    'nama' => $request->nama,
                    'kode' => $request->kode,
                    'aktif' => $aktif,
                    'user' => 'Update-' . $request->username . ', ' . date('d-m-Y h:i:s'),
                ]);
                $tbnegara->save($validated);
                if ($request->kodelama <> $request->kode) {
                    $cekTbbarang = Tbbarang::where('kdnegara', $request->kodelama)->first();
                    if (isset($cekTbbarang)) {
                        Tbbarang::where('kdnegara', $request->kodelama)->update(['kdnegara' => $request->kode]);
                    }
                }
                $msg = [
                    'sukses' => 'Data berhasil di update', //view('tbnegara.tabel_negara')
                ];
            } else {
                $msg = [
                    'sukses' => 'Data gagal di update', //view('tbnegara.tabel_negara')
                ];
            }
            echo json_encode($msg);
            // return redirect()->back()->with('message', 'Berhasil di update');
        } else {
            exit('Maaf tidak dapat diproses');
        }
    }

    public function destroy(Tbnegara $tbnegara, Request $request)
    {
        if ($request->Ajax()) {
            $terpakai = 0;
            $rowTbnegara = Tbnegara::where('id', $request->id)->first();
            $kode = $rowTbnegara->kode;
            $Tbbarang = Tbbarang::where('kdnegara', $kode)->first();
            if (isset($Tbbarang)) {
                $terpakai = 1;
            }
            if ($terpakai == 0) {
                $tbnegara->delete();
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
