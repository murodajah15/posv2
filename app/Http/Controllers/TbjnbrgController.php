<?php

namespace App\Http\Controllers;

use App\Http\Requests\TbjnbrgRequest;
use Illuminate\Http\Request;
use Session;
// use Yajra\DataTables\Contracts\DataTables;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Tbjnbrg;
use App\Models\Tbbarang;
use App\Models\Userdtl;

// //return type View
// use Illuminate\View\View;

class TbjnbrgController extends Controller
{
    public function index(Request $request) //: View
    {
        $username = session('username');
        $data = [
            'menu' => 'file',
            'submenu' => 'tbjnbrg',
            'submenu1' => 'ref_umum',
            'title' => 'Tabel Jenis Barang',
            // 'tbjnbrg' => Tbjnbrg::all(),
            'userdtlmenu' => Userdtl::join('tbmodule', 'userdtl.cmodule', '=', 'tbmodule.cmodule')->where('userdtl.pakai', '1')->where('username', $username)->orderBy('userdtl.nurut')->get(),
            'userdtl' => Userdtl::where('cmodule', 'Tabel Jenis Barang')->where('username', $username)->first(),
        ];
        $userdtl = Userdtl::where('cmodule', 'Tabel Jenis Barang')->where('username', $username)->first();
        if ($userdtl->pakai == '1') {
            return view('tbjnbrg.index')->with($data);
        } else {
            return redirect('home');
        }
    }
    public function tbjnbrgajax(Request $request) //: View
    {
        if ($request->ajax()) {
            $data = Tbjnbrg::select('*'); //->orderBy('kode', 'asc');
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
            return view('tbjnbrg');
        }
    }

    public function tabel_jnbrg(Request $request)
    {
        $data = [
            'menu' => 'file',
            'submenu' => 'tbjnbrg',
            'submenu1' => 'ref_umum',
            'title' => 'Tabel Jenis Barang',
            'tbjnbrg' => Tbjnbrg::orderBy('kode', 'asc')->get(),
        ];
        // var_dump($data);
        return view('tbjnbrg.tabel_jnbrg')->with($data);
    }

    public function create(Request $request)
    {
        if ($request->Ajax()) {
            $data = [
                'menu' => 'file',
                'submenu' => 'tbjnbrg',
                'submenu1' => 'ref_umum',
                'title' => 'Tambah Data Tabel Jenis Barang',
                // 'tbjnbrg' => Tbjnbrg::all(),
            ];
            // var_dump($data);
            return response()->json([
                'body' => view('tbjnbrg.modaltambah', [
                    'tbjnbrg' => new Tbjnbrg(), //Tbjnbrg::first(),
                    'action' => route('tbjnbrg.store'),
                    'vdata' => $data,
                ])->render(),
                'data' => $data,

            ]);
        } else {
            exit('Maaf tidak dapat diproses');
        }
    }

    public function store(TbjnbrgRequest $request, Tbjnbrg $tbjnbrg)
    {
        if ($request->Ajax()) {
            $validated = $request->validated(
                // [
                //     'kode' => 'required|unique:tbjnbrg,kode',
                //     'nama' => 'required',
                // ],
                // [
                //     'kode.unique' => 'Kode tidak boleh sama',
                //     'kode.required' => 'Kode harus di isi',
                //     'nama.required' => 'Nama harus di isi',
                // ]
            );
            if ($validated) {
                // $tbjnbrg->fill($request->all());
                // $tbjnbrg->aktif = $request->aktif == 'on' ? 'Y' : 'N';
                // $tbjnbrg->user = $request->username . date('d-m-Y');
                $aktif = isset($request->aktif) ? 'Y' : 'N';
                $tbjnbrg->fill([
                    'nama' => $request->nama,
                    'kode' => $request->kode,
                    'aktif' => $aktif,
                    'user' => 'Tambah-' . $request->username . ', ' . date('d-m-Y h:i:s'),
                ]);
                $tbjnbrg->save($validated);
                $msg = [
                    'sukses' => 'Data berhasil di tambah', //view('tbjnbrg.tabel_jnbrg')
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
                'submenu' => 'tbjnbrg',
                'submenu1' => 'ref_umum',
                'title' => 'Detail Tabel Jenis Barang',
                'tbjnbrg' => Tbjnbrg::findOrFail($id),
                'userdtl' => Userdtl::where('cmodule', 'Tabel Jenis Barang')->where('username', $username)->first(),
            ];
            // return view('tbjnbrg.modaldetail')->with($data);
            return response()->json([
                'body' => view('tbjnbrg.modaltambah', [
                    'tbjnbrg' => Tbjnbrg::findOrFail($id),
                    'action' => route('tbjnbrg.store'),
                    'vdata' => $data,
                ])->render(),
                'data' => $data,
            ]);
        } else {
            exit('Maaf tidak dapat diproses');
        }
    }

    public function edit(Tbjnbrg $tbjnbrg, Request $request)
    {
        if ($request->Ajax()) {
            $data = [
                'menu' => 'file',
                'submenu' => 'tbjnbrg',
                'submenu1' => 'ref_umum',
                'title' => 'Edit Data Tabel Jenis Barang',
            ];
            return response()->json([
                'body' => view('tbjnbrg.modaltambah', [
                    'tbjnbrg' => $tbjnbrg,
                    'action' => route('tbjnbrg.update', $tbjnbrg->id),
                    'vdata' => $data,
                ])->render(),
                'data' => $data,
            ]);
        } else {
            exit('Maaf tidak dapat diproses');
        }
    }

    public function update(TbjnbrgRequest $request, Tbjnbrg $tbjnbrg)
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
                    //     'kode' => 'required|unique:tbjnbrg,kode',
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
                $tbjnbrg->fill([
                    'nama' => $request->nama,
                    'kode' => $request->kode,
                    'aktif' => $aktif,
                    'user' => 'Update-' . $request->username . ', ' . date('d-m-Y h:i:s'),
                ]);
                $tbjnbrg->save($validated);
                if ($request->kodelama <> $request->kode) {
                    $cekTbbarang = Tbbarang::where('kdjnbrg', $request->kodelama)->first();
                    if (isset($cekTbbarang)) {
                        Tbbarang::where('kdjnbrg', $request->kodelama)->update(['kdjnbrg' => $request->kode]);
                    }
                }
                $msg = [
                    'sukses' => 'Data berhasil di update', //view('tbjnbrg.tabel_jnbrg')
                ];
            } else {
                $msg = [
                    'sukses' => 'Data gagal di update', //view('tbjnbrg.tabel_jnbrg')
                ];
            }
            echo json_encode($msg);
            // return redirect()->back()->with('message', 'Berhasil di update');
        } else {
            exit('Maaf tidak dapat diproses');
        }
    }

    public function destroy(Tbjnbrg $tbjnbrg, Request $request)
    {
        if ($request->Ajax()) {
            $terpakai = 0;
            $rowTbjnbrg = Tbjnbrg::where('id', $request->id)->first();
            $kode = $rowTbjnbrg->kode;
            $Tbbarang = Tbbarang::where('kdjnbrg', $kode)->first();
            if (isset($Tbbarang)) {
                $terpakai = 1;
            }
            if ($terpakai == 0) {
                $tbjnbrg->delete();
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
