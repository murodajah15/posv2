<?php

namespace App\Http\Controllers;

use App\Http\Requests\TbcustomerRequest;
use App\Http\Requests\TbmultiprcRequest;
use Illuminate\Http\Request;
use Session;
// use Yajra\DataTables\Contracts\DataTables;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Tbcustomer;
use App\Models\Tbagama;
use App\Models\Tbmultiprc;
use App\Models\Tbbarang;
use App\Models\Userdtl;
use Illuminate\Support\Facades\DB;

// //return type View
// use Illuminate\View\View;

class TbmultiprcController extends Controller
{
    public function index(Request $request) //: View
    {
        $username = session('username');
        $data = [
            'menu' => 'file',
            'submenu' => 'tbmultiprc',
            'submenu1' => 'ref_umum',
            'title' => 'Tabel Multi Price',
            // 'tbcustomer' => Tbcustomer::all(),
            'userdtlmenu' => Userdtl::join('tbmodule', 'userdtl.cmodule', '=', 'tbmodule.cmodule')->where('userdtl.pakai', '1')->where('username', $username)->orderBy('userdtl.nurut')->get(),
            'userdtl' => Userdtl::where('cmodule', 'Tabel Multi Price')->where('username', $username)->first(),
        ];
        $userdtl = Userdtl::where('cmodule', 'Tabel Multi Price')->where('username', $username)->first();
        if ($userdtl->pakai == '1') {
            return view('tbmultiprc.index')->with($data);
        } else {
            return redirect('home');
        }
    }
    public function tbmultiprcajax(Request $request) //: View
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
            return view('tbmultiprc');
        }
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
                    // 'tbagama' => Tbagama::all(),
                    'action' => route('tbcustomer.store'),
                    'vdata' => $data,
                ])->render(),
                'data' => $data,

            ]);
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
                'userdtl' => Userdtl::where('cmodule', 'Tabel Multi Price')->where('username', $username)->first(),
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

    public function inputmultiprc(Request $request)
    {
        if ($request->Ajax()) {
            $id = $_GET['id'];
            $username = session('username');
            $tbcustomer = Tbcustomer::where('id', $id)->first();
            $kdcustomer = $tbcustomer['kode'];
            // $tbmultiprc = Tbmultiprc::where('kdcustomer', $kdcustomer)->get();
            $data = [
                'menu' => 'file',
                'submenu' => 'tbmultiprc',
                'submenu1' => 'ref_umum',
                'title' => 'Tabel Multi Price',
                'tbcustomer' => Tbcustomer::findOrFail($id),
                'userdtlmenu' => Userdtl::join('tbmodule', 'userdtl.cmodule', '=', 'tbmodule.cmodule')->where('userdtl.pakai', '1')->where('username', $username)->orderBy('userdtl.nurut')->get(),
                'userdtl' => Userdtl::where('cmodule', 'Tabel Multi Price')->where('username', $username)->first(),
                // 'tbmultiprc' => $tbmultiprc,
            ];
            // // return view('tbcustomer.modaldetail')->with($data);
            return response()->json([
                'body' => view('tbmultiprc.modalmultiprc', [
                    'tbcustomer' => Tbcustomer::findOrFail($id),
                    'tbmultiprc' => Tbmultiprc::where('kdcustomer', $kdcustomer)->get(),
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
                    // 'tbagama' => Tbagama::all(),
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
                $aktif = $request->aktif == 'on' ? 'Y' : 'N';
                $tbcustomer->fill([
                    'nama' => isset($request->nama) ? $request->nama : '',
                    'kode' => isset($request->kode) ? $request->kode : '',
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

    public function multiprc_table(Request $request)
    {
        $kdcustomer = $_GET['kdcustomer'];
        $data = [
            'kdcustomer' => $kdcustomer,
            'tbmultiprc' => Tbmultiprc::where('kdcustomer', $kdcustomer)->get(),
        ];
        // dd($data);
        // echo view('tbmultiprc.multiprc_table', $data);
        return view('tbmultiprc.multiprc_table', $data);
    }

    public function multiprc_simpan(TbmultiprcRequest $request, Tbmultiprc $tbmultiprc)
    {
        if ($request->Ajax()) {
            $validated = $request->validate(
                [
                    'kdbarang' => 'required',
                ],
                [
                    'kdbarang.required' => 'Kode barang harus di isi',
                ]
            );
            // dd($validated);
            if ($validated) {
                $kdcustomer = isset($request->kdcustomer) ? $request->kdcustomer : '';
                $kdbarang = isset($request->kdbarang) ? $request->kdbarang : '';
                $cari = Tbmultiprc::where('kdcustomer', $kdcustomer)->where('kdbarang', $kdbarang)->first();
                if (isset($cari)) {
                    $msg = [
                        'sukses' => 'Data gagal di tambah', //view('tbmultiprc.tabel_multiprc')
                    ];
                } else {
                    $tbmultiprc->fill([
                        'kdbarang' => isset($request->kdbarang) ? htmlspecialchars_decode($request->kdbarang) : '',
                        'nmbarang' => isset($request->nmbarang) ? htmlspecialchars_decode($request->nmbarang) : '',
                        'harga' => isset($request->harga) ? $request->harga : '',
                        'discount' => isset($request->discount) ? $request->discount : '',
                        'kdcustomer' => isset($request->kdcustomer) ? htmlspecialchars_decode(
                            $request->kdcustomer
                        ) : '',
                        'user' => 'Tambah-' . $request->username . ', ' . date('d-m-Y h:i:s'),
                    ]);
                    $tbmultiprc->save($validated);
                    $msg = [
                        'sukses' => 'Data berhasil di tambah', //view('tbmultiprc.tabel_multiprc')
                    ];
                }
            } else {
                $msg = [
                    'sukses' => 'Data gagal di tambah', //view('tbmultiprc.tabel_multiprc')
                ];
            }
            echo json_encode($msg);
            // return redirect()->back()->with('message', 'Berhasil di simpan');
        } else {
            exit('Maaf tidak dapat diproses');
        }
    }

    public function caritbcustomer(Request $request)
    {
        if ($request->Ajax()) {
            $data = [
                // 'menu' => 'file',
                // 'submenu' => 'tbcustomer',
                // 'submenu1' => 'ref_umum',
                'title' => 'Cari Data customer',
            ];
            // var_dump($data);
            return response()->json([
                'body' => view('tbmultiprc.modalcaritbcustomer', [
                    'tbcustomer' => Tbcustomer::all(),
                    'vdata' => $data,
                ])->render(),
                'data' => $data,
            ]);
        } else {
            exit('Maaf tidak dapat diproses');
        }
    }
    public function repltbcustomer(Request $request)
    {
        if ($request->Ajax()) {
            $kode = $_GET['kode'];
            $row = Tbcustomer::where('kode', $kode)->first();
            if (isset($row)) {
                $data = [
                    'kdcustomer' => $row['kode'],
                    'nmcustomer' => $row['nama'],
                ];
            } else {
                $data = [
                    'kdcustomer' => '',
                    'nmcustomer' => '',
                ];
            }
            echo json_encode($data);
        } else {
            exit('Maaf tidak dapat diproses');
        }
    }
    public function caritbbarang(Request $request)
    {
        if ($request->Ajax()) {
            $data = [
                // 'menu' => 'file',
                // 'submenu' => 'tbcustomer',
                // 'submenu1' => 'ref_umum',
                'title' => 'Cari Data Barang',
            ];
            // var_dump($data);
            return response()->json([
                'body' => view('tbmultiprc.modalcari', [
                    'tbbarang' => Tbbarang::all(),
                    'vdata' => $data,
                ])->render(),
                'data' => $data,
            ]);
        } else {
            exit('Maaf tidak dapat diproses');
        }
    }
    public function repltbbarang(Request $request)
    {
        if ($request->Ajax()) {
            $kode = $_GET['kode'];
            $row = Tbbarang::where('kode', $kode)->first();
            if (isset($row)) {
                $data = [
                    'kdbarang' => $row['kode'],
                    'nmbarang' => $row['nama'],
                    'harga_jual' => $row['harga_jual'],
                ];
            } else {
                $data = [
                    'kdbarang' => '',
                    'nmbarang' => '',
                    'harga_jual' => '0',
                ];
            }
            echo json_encode($data);
        } else {
            exit('Maaf tidak dapat diproses');
        }
    }

    public function multiprc_salin_tbbarang_customer(Request $request)
    {
        if ($request->Ajax()) {
            $username = session('username');
            $kdcustomer = $_GET['kdcustomer'];
            $kdcustomersalin = $_GET['kdcustomersalin'];
            DB::table('tbmultiprc')->where('kdcustomer', $kdcustomer)->delete();
            $tbmultiprccust = Tbmultiprc::where('kdcustomer', $kdcustomersalin)->get();
            $userdtl_data = array();
            // if ($tbmultiprccust) {
            //     foreach ($tbmultiprccust as $row) {
            //         $kdbarang = $row->kdbarang;
            //         $nmbarang = $row->nmbarang;
            //         $harga = $row->harga;
            //         $discount = $row->discount;
            //         $user = 'Salin-' . $username . ', ' . date('d-m-Y h:i:s');
            //         $cek = Tbmultiprc::where('kdcustomer', $kdcustomer)->where('kdbarang', $kdbarang)->first();
            //         if (isset($cek)) {
            //         } else {
            //             // DB::table('tbmultiprc')->insert([
            //             //     'kdcustomer' => $kdcustomer,
            //             //     'kdbarang' => $kdbarang,
            //             //     'nmbarang' => $nmbarang,
            //             //     'harga' => $harga,
            //             //     'discount' => $discount,
            //             //     'user' => $user,
            //             // ]);
            //             $userdtl_data[] = [
            //                 'kdcustomer' => $kdcustomer,
            //                 'kdbarang' => $row->kode,
            //                 'nmbarang' => $row->nama,
            //                 'harga' => $row->harga_jual,
            //                 'discount' => 0,
            //                 'user' => 'Salin-' . $username . ', ' . date('d-m-Y h:i:s'),
            //             ];
            //         }
            //     }
            //     Tbmultiprc::insert($userdtl_data);
            // }
            if ($tbmultiprccust) {
                foreach ($tbmultiprccust as $row) {
                    $userdtl_data[] = [
                        'kdcustomer' => $kdcustomer,
                        'kdbarang' => $row->kdbarang,
                        'nmbarang' => $row->nmbarang,
                        'harga' => $row->harga,
                        'discount' => $row->discount,
                        'user' => 'Salin-' . $username . ', ' . date('d-m-Y h:i:s'),
                    ];
                }
                // dd($userdtl_data);
                Tbmultiprc::insert($userdtl_data);
            }
            return response()->json([
                'sukses' => 'Data berhasil di salin',
            ]);
        } else {
            exit('Maaf tidak dapat diproses');
        }
    }

    public function multiprc_salin_tbbarang(Request $request)
    {
        if ($request->Ajax()) {
            $username = session('username');
            $kdcustomer = $_GET['kdcustomer'];
            DB::table('tbmultiprc')->where('kdcustomer', $kdcustomer)->delete();
            $tbbarang = Tbbarang::all();
            // foreach ($tbbarang as $row) {
            //     $kdbarang = $row->kode;
            //     $nmbarang = $row->nama;
            //     $harga = $row->harga_jual;
            //     $discount = 0;
            //     $user = 'Salin-' . $username . ', ' . date('d-m-Y h:i:s');
            //     DB::table('tbmultiprc')->insert([
            //         'kdcustomer' => $kdcustomer,
            //         'kdbarang' => $kdbarang,
            //         'nmbarang' => $nmbarang,
            //         'harga' => $harga,
            //         'discount' => $discount,
            //         'user' => $user,
            //     ]);
            // }
            $userdtl_data = array();
            foreach ($tbbarang as $row) {
                $userdtl_data[] = [
                    'kdcustomer' => $kdcustomer,
                    'kdbarang' => $row->kode,
                    'nmbarang' => $row->nama,
                    'harga' => $row->harga_jual,
                    'discount' => 0,
                    'user' => 'Salin-' . $username . ', ' . date('d-m-Y h:i:s'),
                ];
            }
            // var_dump($userdtl_data);
            Tbmultiprc::insert($userdtl_data);
            return response()->json([
                'sukses' => 'Data berhasil di salin',
            ]);
        } else {
            exit('Maaf tidak dapat diproses');
        }
    }

    public function multiprc_edit(Tbmultiprc $tbmultiprc, Request $request)
    {
        if ($request->Ajax()) {
            $id = $_GET['id'];
            // $kdcustomer = $_GET['kdcustomer'];
            $qry = Tbmultiprc::find($id);
            $kdcustomer = $qry->kdcustomer;
            // dd($kdcustomer);
            $tbcust = Tbcustomer::where('kode', $kdcustomer)->first();
            $nmcustomer = $tbcust->nama;
            $data = [
                'menu' => 'file',
                'submenu' => 'multiprc',
                'submenu1' => 'ref_umum',
                'title' => 'Edit Data Multi Price',
                'nmcustomer' => $nmcustomer,
                'kdcustomer' => $kdcustomer,
            ];
            return response()->json([
                'body' => view('tbmultiprc.modaleditdetail', [
                    'tbmultiprc' => Tbmultiprc::findOrFail($id),
                    'action' => route('multiprc_update', $tbmultiprc->id),
                    'vdata' => $data,
                ])->render(),
                'data' => $data,
            ]);
        } else {
            exit('Maaf tidak dapat diproses');
        }
    }

    public function multiprc_update(TbmultiprcRequest $request, Tbmultiprc $tbmultiprc)
    {
        if ($request->Ajax()) {
            $validated = $request->validated();
            if ($validated) {
                $tbmultiprc->fill([
                    'kdcustomer' => isset($request->kdcustomer) ? $request->kdcustomer : '',
                    'kdbarang' => isset($request->kdbarang) ? $request->kdbarang : '',
                    'namabarang' => isset($request->namabarang) ? $request->namabarang : '',
                    'harga' => isset($request->harga) ? $request->harga : '',
                    'discount' => isset($request->discount) ? $request->discount : '',
                    'user' => 'Update-' . $request->username . ', ' . date('d-m-Y h:i:s'),
                ]);
                // Tbmultiprc::find($_POST['id']);
                // $tbmultiprc->save($validated);
                DB::table('tbmultiprc')->where('id', $request->id)->update([
                    'kdbarang' => $request->kdbarang, 'nmbarang' => $request->nmbarang,
                    'harga' => $request->harga, 'discount' => $request->discount, 'user' => 'Update-' . $request->username . ', ' . date('d-m-Y h:i:s'),
                ]);
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

    public function destroy(Tbmultiprc $tbmultiprc, Request $request)
    {
        if ($request->Ajax()) {
            $tbmultiprc->delete();
            return response()->json([
                'sukses' => true,
            ]);
            // return redirect()->back()->with('message', 'Berhasil di hapus');
        } else {
            exit('Maaf tidak dapat diproses');
        }
    }
}
