<?php

namespace App\Http\Controllers;

use App\Http\Requests\TbcustomerRequest;
use Illuminate\Http\Request;
use Session;
// use Yajra\DataTables\Contracts\DataTables;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Tbcustomer;
use App\Models\Tbagama;
use App\Models\Soh;
use App\Models\Jualh;
use App\Models\Kasir_tunai;
use App\Models\Kasir_tagihand;
use App\Models\Userdtl;

// //return type View
// use Illuminate\View\View;

class TbcustomerController extends Controller
{
    public function index(Request $request) //: View
    {
        $username = session('username');
        $data = [
            'menu' => 'file',
            'submenu' => 'tbcustomer',
            'submenu1' => 'ref_umum',
            'title' => 'Tabel Customer',
            // 'tbcustomer' => Tbcustomer::all(),
            'userdtlmenu' => Userdtl::join('tbmodule', 'userdtl.cmodule', '=', 'tbmodule.cmodule')->where('userdtl.pakai', '1')->where('username', $username)->orderBy('userdtl.nurut')->get(),
            'userdtl' => Userdtl::where('cmodule', 'Tabel Customer')->where('username', $username)->first(),
        ];
        $userdtl = Userdtl::where('cmodule', 'Tabel Customer')->where('username', $username)->first();
        if ($userdtl->pakai == '1') {
            return view('tbcustomer.index')->with($data);
        } else {
            return redirect('home');
        }
    }
    public function tbcustomerajax(Request $request) //: View
    {
        if ($request->ajax()) {
            $data = Tbcustomer::select('*'); //->orderBy('kode', 'asc');
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
            return view('tbcustomer');
        }
    }

    public function tabel_customer(Request $request)
    {
        $data = [
            'menu' => 'file',
            'submenu' => 'tbcustomer',
            'submenu1' => 'ref_umum',
            'title' => 'Tabel Customer',
            'tbcustomer' => Tbcustomer::orderBy('kode', 'asc')->get(),
        ];
        // var_dump($data);
        return view('tbcustomer.tabel_customer')->with($data);
    }

    public function create(Request $request)
    {
        if ($request->Ajax()) {
            $data = [
                'menu' => 'file',
                'submenu' => 'tbcustomer',
                'submenu1' => 'ref_umum',
                'title' => 'Tambah Data Tabel Customer',
            ];
            // var_dump($data);
            return response()->json([
                'body' => view('tbcustomer.modaltambah', [
                    'tbcustomer' => new Tbcustomer(), //Tbcustomer::first(),
                    'tbagama' => Tbagama::all(),
                    'action' => route('tbcustomer.store'),
                    'vdata' => $data,
                ])->render(),
                'data' => $data,

            ]);
        } else {
            exit('Maaf tidak dapat diproses');
        }
    }

    public function store(TbcustomerRequest $request, Tbcustomer $tbcustomer)
    {
        if ($request->Ajax()) {
            $validated = $request->validated(
                // [
                //     'kode' => 'required|unique:tbcustomer,kode',
                //     'nama' => 'required',
                // ],
                // [
                //     'kode.unique' => 'Kode tidak boleh sama',
                //     'kode.required' => 'Kode harus di isi',
                //     'nama.required' => 'Nama harus di isi',
                // ]
            );
            if ($validated) {
                // $aktif = isset($request->aktif) ? 'Y' : 'N';
                $tbcustomer->fill([
                    'nama' => isset($request->nama) ? $request->nama : '',
                    'kode' => isset($request->kode) ? $request->kode : '',
                    'kelompok' => isset($request->kelompok) ? $request->kelompok : '',
                    'alamat' => isset($request->alamat) ? $request->alamat : '',
                    'kota' => isset($request->kota) ? $request->kota : '',
                    'kodepos' => isset($request->kodepos) ? $request->kodepos : '',
                    'telp1' => isset($request->telp1) ? $request->telp1 : '',
                    'telp2' => isset($request->telp2) ? $request->telp2 : '',
                    'agama' => isset($request->agama) ? $request->agama : '',
                    'tgl_lahir' => isset($request->tgl_lahir) ? $request->tgl_lahir : '',
                    'alamat_ktr' => isset($request->alamat_ktr) ? $request->alamat_ktr : '',
                    'kota_ktr' => isset($request->kota_ktr) ? $request->kota_ktr : '',
                    'kodepos_ktr' => isset($request->kodepos_ktr) ? $request->kodepos_ktr : '',
                    'telp1_ktr' => isset($request->telp1_ktr) ? $request->telp1_ktr : '',
                    'telp2_ktr' => isset($request->telp2_ktr) ? $request->telp2_ktr : '',
                    'npwp' => isset($request->npwp) ? $request->npwp : '',
                    'alamat_npwp' => isset($request->alamat_npwp) ? $request->alamat_npwp : '',
                    'nama_npwp' => isset($request->nama_npwp) ? $request->nama_npwp : '',
                    'alamat_ktp' => isset($request->alamat_ktp) ? $request->alamat_ktp : '',
                    'kota_ktp' => isset($request->kota_ktp) ? $request->kota_ktp : '',
                    'kodepos_ktp' => isset($request->kodepos_ktp) ? $request->kodepos_ktp : '',
                    'contact_person_rmh' => isset($request->contact_person_rmh) ? $request->contact_person_rmh : '',
                    'mak_piutang' => isset($request->mak_piutang) ? $request->mak_piutang : '',
                    'kdklpcust' => isset($request->kdklpcust) ? $request->kdklpcust : '',
                    'nmklpcust' => isset($request->nmklpcust) ? $request->nmklpcust : '',
                    'tgl_register' => isset($request->tgl_register) ? $request->tgl_register : '',
                    'tempo' => isset($request->tempo) ? $request->tempo : '',
                    'user' => 'Tambah-' . $request->username . ', ' . date('d-m-Y h:i:s'),
                ]);
                $tbcustomer->save($validated);
                $msg = [
                    'sukses' => 'Data berhasil di tambah', //view('tbcustomer.tabel_customer')
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
                'submenu' => 'tbcustomer',
                'submenu1' => 'ref_umum',
                'title' => 'Detail Tabel customer',
                'tbcustomer' => Tbcustomer::findOrFail($id),
                'userdtl' => Userdtl::where('cmodule', 'Tabel Customer')->where('username', $username)->first(),
            ];
            // return view('tbcustomer.modaldetail')->with($data);
            return response()->json([
                'body' => view('tbcustomer.modaltambah', [
                    'tbcustomer' => Tbcustomer::findOrFail($id),
                    'tbagama' => Tbagama::all(),
                    'action' => route('tbcustomer.store'),
                    'vdata' => $data,
                ])->render(),
                'data' => $data,
            ]);
        } else {
            exit('Maaf tidak dapat diproses');
        }
    }

    public function edit(Tbcustomer $tbcustomer, Request $request)
    {
        if ($request->Ajax()) {
            $data = [
                'menu' => 'file',
                'submenu' => 'tbcustomer',
                'submenu1' => 'ref_umum',
                'title' => 'Edit Data Tabel Customer',
            ];
            return response()->json([
                'body' => view('tbcustomer.modaltambah', [
                    'tbcustomer' => $tbcustomer,
                    'tbagama' => Tbagama::all(),
                    'action' => route('tbcustomer.update', $tbcustomer->id),
                    'vdata' => $data,
                ])->render(),
                'data' => $data,
            ]);
        } else {
            exit('Maaf tidak dapat diproses');
        }
    }

    public function update(TbcustomerRequest $request, Tbcustomer $tbcustomer)
    {
        if ($request->Ajax()) {
            $validated = $request->validated();
            if ($validated) {
                // dd('aaa' . isset($request->kelompok) ? $request->kelompok : '');
                // $aktif = $request->aktif == 'on' ? 'Y' : 'N';
                $tbcustomer->fill([
                    'nama' => isset($request->nama) ? $request->nama : '',
                    'kode' => isset($request->kode) ? $request->kode : '',
                    'kelompok' => isset($request->kelompok) ? $request->kelompok : '',
                    'alamat' => isset($request->alamat) ? $request->alamat : '',
                    'kota' => isset($request->kota) ? $request->kota : '',
                    'kodepos' => isset($request->kodepos) ? $request->kodepos : '',
                    'telp1' => isset($request->telp1) ? $request->telp1 : '',
                    'telp2' => isset($request->telp2) ? $request->telp2 : '',
                    'agama' => isset($request->agama) ? $request->agama : '',
                    'tgl_lahir' => isset($request->tgl_lahir) ? $request->tgl_lahir : '',
                    'alamat_ktr' => isset($request->alamat_ktr) ? $request->alamat_ktr : '',
                    'kota_ktr' => isset($request->kota_ktr) ? $request->kota_ktr : '',
                    'kodepos_ktr' => isset($request->kodepos_ktr) ? $request->kodepos_ktr : '',
                    'telp1_ktr' => isset($request->telp1_ktr) ? $request->telp1_ktr : '',
                    'telp2_ktr' => isset($request->telp2_ktr) ? $request->telp2_ktr : '',
                    'npwp' => isset($request->npwp) ? $request->npwp : '',
                    'alamat_npwp' => isset($request->alamat_npwp) ? $request->alamat_npwp : '',
                    'nama_npwp' => isset($request->nama_npwp) ? $request->nama_npwp : '',
                    'alamat_ktp' => isset($request->alamat_ktp) ? $request->alamat_ktp : '',
                    'kota_ktp' => isset($request->kota_ktp) ? $request->kota_ktp : '',
                    'kodepos_ktp' => isset($request->kodepos_ktp) ? $request->kodepos_ktp : '',
                    'contact_person_rmh' => isset($request->contact_person_rmh) ? $request->contact_person_rmh : '',
                    'mak_piutang' => isset($request->mak_piutang) ? $request->mak_piutang : '',
                    'kdklpcust' => isset($request->kdklpcust) ? $request->kdklpcust : '',
                    'nmklpcust' => isset($request->nmklpcust) ? $request->nmklpcust : '',
                    'tgl_register' => isset($request->tgl_register) ? $request->tgl_register : '',
                    'tempo' => isset($request->tempo) ? $request->tempo : '',
                    'user' => 'Update-' . $request->username . ', ' . date('d-m-Y h:i:s'),
                ]);
                $tbcustomer->save($validated);
                if ($request->kodelama <> $request->kode) {
                    $cekSoh = Soh::where('kdcustomer', $request->kodelama)->first();
                    if (isset($cekSoh)) {
                        Soh::where('kdcustomer', $request->kodelama)->update(['kdcustomer' => $request->kode]);
                    }
                    $cekJualh = Jualh::where('kdcustomer', $request->kodelama)->first();
                    if (isset($cekJualh)) {
                        Jualh::where('kdcustomer', $request->kodelama)->update(['kdcustomer' => $request->kode]);
                    }
                    $cekKasir_tunai = Kasir_tunai::where('kdcustomer', $request->kodelama)->first();
                    if (isset($cekKasir_tunai)) {
                        Kasir_tunai::where('kdcustomer', $request->kodelama)->update(['kdcustomer' => $request->kode]);
                    }
                    $cekKasir_tagihand = Kasir_tagihand::where('kdcustomer', $request->kodelama)->first();
                    if (isset($cekKasir_tagihand)) {
                        Kasir_tagihand::where('kdcustomer', $request->kodelama)->update(['kdcustomer' => $request->kode]);
                    }
                }
                $msg = [
                    'sukses' => 'Data berhasil di update', //view('tbcustomer.tabel_customer')
                ];
            } else {
                $msg = [
                    'sukses' => 'Data gagal di update', //view('tbcustomer.tabel_customer')
                ];
            }
            echo json_encode($msg);
            // return redirect()->back()->with('message', 'Berhasil di update');
        } else {
            exit('Maaf tidak dapat diproses');
        }
    }

    // public function cariklpcust(Request $request)
    // {
    //     if ($request->Ajax()) {
    //         $data = [
    //             // 'menu' => 'file',
    //             // 'submenu' => 'tbcustomer',
    //             // 'submenu1' => 'ref_umum',
    //             'title' => 'Cari Kelompok Customer',
    //         ];
    //         // var_dump($data);
    //         return response()->json([
    //             'body' => view('modalcari.modalcariklpcust', [
    //                 'tbklpcust' => Tbklpcust::all(),
    //                 'vdata' => $data,
    //             ])->render(),
    //             'data' => $data,
    //         ]);
    //     } else {
    //         exit('Maaf tidak dapat diproses');
    //     }
    // }
    // public function replklpcust(Request $request)
    // {
    //     if ($request->Ajax()) {
    //         $kode = $_GET['kode'];
    //         $row = Tbklpcust::where('kode', $kode)->first();
    //         if (isset($row)) {
    //             $data = [
    //                 'kdklpcust' => $row['kode'],
    //                 'nmklpcust' => $row['nama'],
    //             ];
    //         } else {
    //             $data = [
    //                 'kdklpcust' => '',
    //                 'nmklpcust' => '',
    //             ];
    //         }
    //         echo json_encode($data);
    //     } else {
    //         exit('Maaf tidak dapat diproses');
    //     }
    // }

    public function destroy(Tbcustomer $tbcustomer, Request $request)
    {
        if ($request->Ajax()) {
            $terpakai = 0;
            $rowTbcustomer = Tbcustomer::where('id', $request->id)->first();
            $kode = $rowTbcustomer->kode;
            $Soh = Soh::where('kdcustomer', $kode)->first();
            if (isset($Soh)) {
                $terpakai = 1;
            }
            if ($terpakai == 0) {
                $Jualh = Jualh::where('kdcustomer', $kode)->first();
                if (isset($Jualh)) {
                    $terpakai = 1;
                }
            }
            if ($terpakai == 0) {
                $Kasir_tunai = Kasir_tunai::where('kdcustomer', $kode)->first();
                if (isset($Kasir_tunai)) {
                    $terpakai = 1;
                }
            }
            if ($terpakai == 0) {
                $Kasir_tagihand = Kasir_tagihand::where('kdcustomer', $kode)->first();
                if (isset($Kasir_tagihand)) {
                    $terpakai = 1;
                }
            }
            if ($terpakai == 0) {
                $tbcustomer->delete();
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
