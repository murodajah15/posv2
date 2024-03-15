<?php

namespace App\Http\Controllers;

use App\Http\Requests\TbjnkeluarRequest;
use Illuminate\Http\Request;
use Session;
// use Yajra\DataTables\Contracts\DataTables;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Tbjnkeluar;
use App\Models\Mohklruangh;
use App\Models\Userdtl;

// //return type View
// use Illuminate\View\View;

class TbjnkeluarController extends Controller
{
    public function index(Request $request) //: View
    {
        $username = session('username');
        $data = [
            'menu' => 'file',
            'submenu' => 'tbjnkeluar',
            'submenu1' => 'ref_umum',
            'title' => 'Tabel Jenis Pengeluaran',
            // 'tbjnkeluar' => Tbjnkeluar::all(),
            'userdtlmenu' => Userdtl::join('tbmodule', 'userdtl.cmodule', '=', 'tbmodule.cmodule')->where('userdtl.pakai', '1')->where('username', $username)->orderBy('userdtl.nurut')->get(),
            'userdtl' => Userdtl::where('cmodule', 'Tabel Jenis Pengeluaran')->where('username', $username)->first(),
        ];
        $userdtl = Userdtl::where('cmodule', 'Tabel Jenis Pengeluaran')->where('username', $username)->first();
        if ($userdtl->pakai == '1') {
            return view('tbjnkeluar.index')->with($data);
        } else {
            return redirect('home');
        }
    }
    public function tbjnkeluarajax(Request $request) //: View
    {
        if ($request->ajax()) {
            $data = Tbjnkeluar::select('*'); //->orderBy('kode', 'asc');
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
            return view('tbjnkeluar');
        }
    }

    public function tabel_jnkeluar(Request $request)
    {
        $data = [
            'menu' => 'file',
            'submenu' => 'tbjnkeluar',
            'submenu1' => 'ref_umum',
            'title' => 'Tabel Jenis Pengeluaran',
            'tbjnkeluar' => Tbjnkeluar::orderBy('kode', 'asc')->get(),
        ];
        // var_dump($data);
        return view('tbjnkeluar.tabel_jnkeluar')->with($data);
    }

    public function create(Request $request)
    {
        if ($request->Ajax()) {
            $data = [
                'menu' => 'file',
                'submenu' => 'tbjnkeluar',
                'submenu1' => 'ref_umum',
                'title' => 'Tambah Data Tabel Jenis Pengeluaran',
                // 'tbjnkeluar' => Tbjnkeluar::all(),
            ];
            // var_dump($data);
            return response()->json([
                'body' => view('tbjnkeluar.modaltambah', [
                    'tbjnkeluar' => new Tbjnkeluar(), //Tbjnkeluar::first(),
                    'action' => route('tbjnkeluar.store'),
                    'vdata' => $data,
                ])->render(),
                'data' => $data,

            ]);
        } else {
            exit('Maaf tidak dapat diproses');
        }
    }

    public function store(TbjnkeluarRequest $request, Tbjnkeluar $tbjnkeluar)
    {
        if ($request->Ajax()) {
            $validated = $request->validated(
                // [
                //     'kode' => 'required|unique:tbjnkeluar,kode',
                //     'nama' => 'required',
                // ],
                // [
                //     'kode.unique' => 'Kode tidak boleh sama',
                //     'kode.required' => 'Kode harus di isi',
                //     'nama.required' => 'Nama harus di isi',
                // ]
            );
            if ($validated) {
                // $tbjnkeluar->fill($request->all());
                // $tbjnkeluar->aktif = $request->aktif == 'on' ? 'Y' : 'N';
                // $tbjnkeluar->user = $request->username . date('d-m-Y');
                // $aktif = isset($request->aktif) ? 'Y' : 'N';
                $tbjnkeluar->fill([
                    'nama' => $request->nama,
                    'kode' => $request->kode,
                    // 'aktif' => $aktif,
                    'user' => 'Tambah-' . $request->username . ', ' . date('d-m-Y h:i:s'),
                ]);
                $tbjnkeluar->save($validated);
                $msg = [
                    'sukses' => 'Data berhasil di tambah', //view('tbjnkeluar.tabel_jnkeluar')
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
                'submenu' => 'tbjnkeluar',
                'submenu1' => 'ref_umum',
                'title' => 'Detail Tabel Jenis Pengeluaran',
                'tbjnkeluar' => Tbjnkeluar::findOrFail($id),
                'userdtl' => Userdtl::where('cmodule', 'Tabel Jenis Pengeluaran')->where('username', $username)->first(),
            ];
            // return view('tbjnkeluar.modaldetail')->with($data);
            return response()->json([
                'body' => view('tbjnkeluar.modaltambah', [
                    'tbjnkeluar' => Tbjnkeluar::findOrFail($id),
                    'action' => route('tbjnkeluar.store'),
                    'vdata' => $data,
                ])->render(),
                'data' => $data,
            ]);
        } else {
            exit('Maaf tidak dapat diproses');
        }
    }

    public function edit(Tbjnkeluar $tbjnkeluar, Request $request)
    {
        if ($request->Ajax()) {
            $data = [
                'menu' => 'file',
                'submenu' => 'tbjnkeluar',
                'submenu1' => 'ref_umum',
                'title' => 'Edit Data Tabel Jenis Pengeluaran',
            ];
            return response()->json([
                'body' => view('tbjnkeluar.modaltambah', [
                    'tbjnkeluar' => $tbjnkeluar,
                    'action' => route('tbjnkeluar.update', $tbjnkeluar->id),
                    'vdata' => $data,
                ])->render(),
                'data' => $data,
            ]);
        } else {
            exit('Maaf tidak dapat diproses');
        }
    }

    public function update(TbjnkeluarRequest $request, Tbjnkeluar $tbjnkeluar)
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
                    //     'kode' => 'required|unique:tbjnkeluar,kode',
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
                // $aktif = $request->aktif == 'on' ? 'Y' : 'N';
                $tbjnkeluar->fill([
                    'nama' => $request->nama,
                    'kode' => $request->kode,
                    // 'aktif' => $aktif,
                    'user' => 'Update-' . $request->username . ', ' . date('d-m-Y h:i:s'),
                ]);
                $tbjnkeluar->save($validated);
                if ($request->kodelama <> $request->kode) {
                    $cekMohklruangh = Mohklruangh::where('kdjnkeluar', $request->kodelama)->first();
                    if (isset($cekMohklruangh)) {
                        Mohklruangh::where('kdjnkeluar', $request->kodelama)->update(['kdjnkeluar' => $request->kode]);
                    }
                }
                $msg = [
                    'sukses' => 'Data berhasil di update', //view('tbjnkeluar.tabel_jnkeluar')
                ];
            } else {
                $msg = [
                    'sukses' => 'Data gagal di update', //view('tbjnkeluar.tabel_jnkeluar')
                ];
            }
            echo json_encode($msg);
            // return redirect()->back()->with('message', 'Berhasil di update');
        } else {
            exit('Maaf tidak dapat diproses');
        }
    }

    public function destroy(Tbjnkeluar $tbjnkeluar, Request $request)
    {
        if ($request->Ajax()) {
            $terpakai = 0;
            $rowTbjnkeluar = Tbjnkeluar::where('id', $request->id)->first();
            $kode = $rowTbjnkeluar->kode;
            $Mohklruangh = Mohklruangh::where('kdjnkeluar', $kode)->first();
            if (isset($Mohklruangh)) {
                $terpakai = 1;
            }
            if ($terpakai == 0) {
                $tbjnkeluar->delete();
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
