<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\DataTables\UserDataTable;
use App\Http\Requests\TbUserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Session;
use Yajra\DataTables\Facades\DataTables;
use App\Models\User;
use App\Models\Userdtl;
use App\Models\Tbmodule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $username = session('username');
        $data = [
            'menu' => 'utility',
            'submenu' => 'user',
            'submenu1' => 'ref_umum',
            'title' => 'Tabel User',
            'userdtlmenu' => Userdtl::where('userdtl.pakai', '1')->where('username', $username)->orderBy('userdtl.nurut')->get(), //Userdtl::join('tbmodule', 'userdtl.cmodule', '=', 'tbmodule.cmodule')->where('userdtl.pakai', '1')->where('username', $username)->orderBy('userdtl.nurut')->get(),
            // 'userdtl' => Userdtl::where('username', $username)->orderBy('nurut')->get(),
            'userdtl' => Userdtl::where('cmodule', 'Tabel User')->where('username', $username)->first(),
        ];
        return view('user.index')->with($data);
    }

    public function index1(UserDataTable $userDataTable)
    {
        $username = session('username');
        $data = [
            'menu' => 'utility',
            'submenu' => 'user',
            'submenu1' => 'ref_umum',
            'title' => 'Tabel User',
            'userdtl' => Userdtl::where('cmodule', 'Tabel User')->where('username', $username)->first(),
        ];
        return $userDataTable->with(['submenu1' => 'ref_umum'])->render('user.index', $data);
        // var_dump($data);
        // return view('user.index')->with($data);
    }
    public function userajax(Request $request) //: View
    {
        if ($request->ajax()) {
            $data = User::All(); //->orderBy('kode', 'asc');
            // $data = User::select('username,email,nama_lengkap,level,aktif'); //->orderBy('kode', 'asc');
            return Datatables::of($data)
                ->addIndexColumn()
                // ->addIndexColumn()
                // ->addColumn('action', function ($row) {
                //     $btn = '<a href="javascript:void(0)" class="edit btn btn-primary btn-sm">View</a>';
                //     return $btn;
                // })
                // ->rawColumns(['action'])
                ->make(true);
            return view('user');
        }
    }

    public function create()
    {
        // if ($this->request->isAjax()) {
        $data = [
            'menu' => 'utility',
            'submenu' => 'user',
            'submenu1' => 'ref_umum',
            'title' => 'Tambah Data Tabel User',
            'action' => route('user.store'),
            'user' => new User(),
            // 'user' => User::all(),
        ];
        // var_dump($data);
        return response()->json([
            'body' => view('user.modaltambah', [
                'user' => new User(), //User::first(),
                'action' => route('user.store'),
                'vdata' => $data,
            ])->render(),
            'data' => $data,
        ]);

        // return view('user.create')->with($data); //dengan form

        // } else {
        //     exit('Maaf tidak dapat diproses');
        // }
    }

    public function store(TbUserRequest $request)
    {
        $user = new User();
        // $validated = $request->validate();
        $validated = $request->validate(
            [
                'username' => 'required|unique:users,username',
                'nama_lengkap' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:3|max:250',
                'confirm_password' => 'required|same:password|min:3',
                'photo' => 'mimes:jpg,png,jpeg|image|max:2040',
            ],
            [
                'username.unique' => 'Nama User tidak boleh sama',
                'username.required' => 'Nama User harus di isi',
                'email.required' => 'Email harus di isi',
                'email.email' => 'Harus sesuai format email',
                'email.unique' => 'Email User tidak boleh sama',
                'nama_lengkap.required' => 'Nama Lengkap User harus di isi',
            ]
        );

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('uploads');
        } else {
            $path = '';
        }
        // ddd($path);
        if ($validated) {
            $user = new User();
            // $user->fill($request->all());
            // $user->aktif = $request->aktif == 'on' ? 'Y' : 'N';
            // $user->user = $request->username . date('d-m-Y');
            $aktif = isset($request->aktif) ? 'Y' : 'N';
            $user->fill([
                'username' => $request->username,
                'email' => $request->email,
                'nama_lengkap' => $request->nama_lengkap,
                'telp' => is_null($request->telp) ? '' :  $request->telp,
                // 'password' => password_hash($request->password, PASSWORD_DEFAULT),
                'password' => Hash::make($request->password),
                'level' => $request->level,
                'photo' => $path,
                'aktif' => $aktif,
                'user' => 'Tambah-' . $request->username . ', ' . date('d-m-Y h:i:s'),
            ]);
            $user->save($validated);
            $msg = [
                'sukses' => 'Data berhasil di tambah', //view('user.tabel_bank')
            ];
        }
        echo json_encode($msg);

        // return redirect()->back()->with('message', 'Berhasil di simpan');
    }

    // public function show(string $id)
    public function show()
    {
        $id = $_GET['id'];
        $username = session('username');
        // // if ($this->request->isAjax()) {
        $data = [
            'menu' => 'utility',
            'submenu' => 'user',
            'submenu1' => 'ref_umum',
            'title' => 'Detail Tabel User',
            'user' => User::findOrFail($id),
            'userdtl' => Userdtl::where('cmodule', 'Tabel User')->where('username', $username)->first(),
        ];
        // return view('user.modaldetail')->with($data);
        return response()->json([
            'body' => view('user.modaltambah', [
                'user' => User::findOrFail($id),
                'action' => route('user.store'),
                'vdata' => $data,
            ])->render(),
            'data' => $data,
        ]);
        // // } else {
        // //     exit('Maaf tidak dapat diproses');
        // // }
    }

    public function edit(user $user)
    {
        // if ($this->request->isAjax()) {
        $data = [
            'menu' => 'utility',
            'submenu' => 'user',
            'submenu1' => 'ref_umum',
            'title' => 'Edit Data Tabel User',
        ];
        // var_dump($data);

        // return response()->json([
        //     'data' => $data,
        // ]);
        return response()->json([
            'body' => view('user.modaltambah', [
                'user' => $user,
                'action' => route('user.update', $user->id),
                'vdata' => $data,
            ])->render(),
            'data' => $data,
        ]);
        // } else {
        //     exit('Maaf tidak dapat diproses');
        // }
    }

    public function update(Request $request, user $user)
    {

        if ($request->username === $request->usernamelama) {
            if ($request->email === $request->emaillama) {
                if ($request->resetpassword == 'on') {
                    $validated = $request->validate(
                        [
                            'username' => 'required',
                            'nama_lengkap' => 'required',
                            'email' => 'required|email',
                            'confirm_password' => 'required|same:password|min:3'
                        ],
                        [
                            'username.required' => 'Username harus di isi',
                            'email.required' => 'Email harus di isi',
                            'email.email' => 'Harus sesuai format email',
                            'nama_lengkap.required' => 'Nama Lengkap User harus di isi',
                        ]
                    );
                } else {
                    $validated = $request->validate(
                        [
                            'username' => 'required',
                            'nama_lengkap' => 'required',
                            'email' => 'required|email',
                        ],
                        [
                            'username.required' => 'Username harus di isi',
                            'email.required' => 'Email harus di isi',
                            'email.email' => 'Harus sesuai format email',
                            'nama_lengkap.required' => 'Nama Lengkap User harus di isi',
                        ]
                    );
                }
            } else {
                if ($request->resetpassword == 'on') {
                    $validated = $request->validate(
                        [
                            'username' => 'required',
                            'nama_lengkap' => 'required',
                            'email' => 'required|email|unique:users,email',
                            'confirm_password' => 'required|same:password|min:3'
                        ],
                        [
                            'username.required' => 'Username harus di isi',
                            'email.required' => 'Email harus di isi',
                            'email.email' => 'Harus sesuai format email',
                            'email.unique' => 'Email sudah pernah terdaftar',
                            'nama_lengkap.required' => 'Nama Lengkap User harus di isi',
                        ]
                    );
                } else {
                    $validated = $request->validate(
                        [
                            'username' => 'required',
                            'nama_lengkap' => 'required',
                            'email' => 'required|email|unique:users,email',
                        ],
                        [
                            'username.required' => 'Username harus di isi',
                            'email.required' => 'Email harus di isi',
                            'email.email' => 'Harus sesuai format email',
                            'email.unique' => 'Email sudah pernah terdaftar',
                            'nama_lengkap.required' => 'Nama Lengkap User harus di isi',
                        ]
                    );
                }
            }
        } else {
            if ($request->email === $request->emaillama) {
                $validated = $request->validate(
                    [
                        'username' => 'required|unique:users,username',
                        'email' => 'required|email',
                        'nama_lengkap' => 'required',
                    ],
                    [
                        'username.unique' => 'Username tidak boleh sama',
                        'username.required' => 'Username harus di isi',
                        'email.required' => 'Email harus di isi',
                        'email.email' => 'Harus sesuai format email',
                        'nama_lengkap.required' => 'Nama Lengkap User harus di isi',
                    ]
                );
            } else {
                $validated = $request->validate(
                    [
                        'username' => 'required|unique:users,username',
                        'nama_lengkap' => 'required',
                        'email' => 'required|email|qunique:users,email',
                        'confirm_password' => 'required|same:password|min:3'
                    ],
                    [
                        'username.unique' => 'Username tidak boleh sama',
                        'username.required' => 'Username harus di isi',
                        'email.required' => 'Email harus di isi',
                        'email.email' => 'Harus sesuai format email',
                        'email.unique' => 'Email sudah pernah terdaftar',
                        'nama_lengkap.required' => 'Nama Lengkap User harus di isi',
                    ]
                );
            }
        }
        if ($validated) {
            $aktif = $request->aktif == 'on' ? 'Y' : 'N';
            if ($request->resetpassword == 'on') {
                if ($request->hasFile('photo')) {
                    $path = $request->file('photo')->store('uploads');
                    $user->fill([
                        'username' => $request->username,
                        'email' => $request->email,
                        'nama_lengkap' => $request->nama_lengkap,
                        'telp' => is_null($request->telp) ? '' :  $request->telp,
                        // 'password' => password_hash($request->password, PASSWORD_DEFAULT),
                        'password' => Hash::make($request->password),
                        'level' => $request->level,
                        'photo' => $path,
                        'aktif' => $aktif,
                        'user' => 'Update-' . $request->username . ', ' . date('d-m-Y h:i:s'),
                    ]);
                    $filelama = $request->photolama;
                    if (($filelama != null || $filelama != '') and $filelama <> $path) {
                        // var_dump($filelama . ' ---- ' . $path);
                        File::delete($filelama);
                        Storage::delete($filelama);
                    }
                } else {
                    $user->fill([
                        'username' => $request->username,
                        'email' => $request->email,
                        'nama_lengkap' => $request->nama_lengkap,
                        'telp' => is_null($request->telp) ? '' :  $request->telp,
                        // 'password' => password_hash($request->password, PASSWORD_DEFAULT),
                        'password' => Hash::make($request->password),
                        'level' => $request->level,
                        'aktif' => $aktif,
                        'user' => 'Update-' . $request->username . ', ' . date('d-m-Y h:i:s'),
                    ]);
                }
            } else {
                // ddd($request->file('photo')->store('uploads'));
                if ($request->hasFile('photo')) {
                    $path = $request->file('photo')->store('uploads');
                    $user->fill([
                        'username' => $request->username,
                        'email' => $request->email,
                        'nama_lengkap' => $request->nama_lengkap,
                        'telp' => is_null($request->telp) ? '' :  $request->telp,
                        'level' => $request->level,
                        'photo' => $path,
                        'aktif' => $aktif,
                        'user' => 'Update-' . $request->username . ', ' . date('d-m-Y h:i:s'),
                    ]);
                    $filelama = $request->photolama;
                    if (($filelama != null || $filelama != '') and $filelama <> $path) {
                        // var_dump($filelama . ' ---- ' . $path);
                        File::delete($filelama);
                        Storage::delete($filelama);
                    }
                } else {
                    $user->fill([
                        'username' => $request->username,
                        'email' => $request->email,
                        'nama_lengkap' => $request->nama_lengkap,
                        'telp' => is_null($request->telp) ? '' :  $request->telp,
                        'level' => $request->level,
                        'aktif' => $aktif,
                        'user' => 'Update-' . $request->username . ', ' . date('d-m-Y h:i:s'),
                    ]);
                }
            }
            $user->save($validated);
            $msg = [
                'sukses' => 'Data berhasil di update', //view('user.tabel_bank')
            ];
        } else {
            $msg = [
                'sukses' => 'Data gagal di update', //view('user.tabel_bank')
            ];
        }
        echo json_encode($msg);
        // return redirect()->back()->with('message', 'Berhasil di update');
    }

    public function user_akses()
    {
        $id = $_GET['id'];
        // // if ($this->request->isAjax()) {
        $user = User::findOrFail($id);
        $username = $user->username;
        $data = [
            'menu' => 'utility',
            'submenu' => 'user',
            'submenu1' => 'ref_umum',
            'title' => 'Akses User',
            'user' => $user,
            // 'userdtl' => Userdtl::where('cmodule', 'Tabel User')->where('username', 'MUROD')->first(),
        ];
        // return view('user.modaldetail')->with($data);
        return response()->json([
            'body' => view('user.modalakses', [
                'user' => $user,
                // 'tbmodule' => Tbmodule::All(),
                'tbmodule' => Tbmodule::where('aktif', 'Y')->orderBy('nurut')->get(),
                'userdtl' => Userdtl::where('username', $username)->orderBy('nurut')->get(),
                'action' => route('simpanakses'),
                // 'action' => route('user.update', $user->id),
                'vdata' => $data,
            ])->render(),
            'data' => $data,
        ]);
        // // } else {
        // //     exit('Maaf tidak dapat diproses');
        // // }
    }

    public function simpanakses(Request $request)
    {
        //hapus record sesuai iduser
        $username = $request->username;
        Userdtl::where('username', $username)->delete();
        //insert ke userdtl sesuai module yang dipilih
        $cmodule = $request->cmodule;
        $cmenu = $request->cmenu;
        $cmainmenu = $request->cmainmenu;
        $cparent = $request->cparent;
        $nlevel = $request->nlevel;
        $nurut = $request->nurut;
        $username = $request->username;
        $pakai = $request->pakai;
        $tambah = $request->tambah;
        $edit = $request->edit;
        $hapus = $request->hapus;
        $proses = $request->proses;
        $unproses = $request->unproses;
        $cetak = $request->cetak;
        $jmldata = count($cmodule);
        isset($pakai) ? $jmlpakai = count($pakai) : $jmlpakai = 0;
        isset($tambah) ? $jmltambah = count($tambah) : $jmltambah = 0;
        isset($edit) ? $jmledit = count($edit) : $jmledit = 0;
        isset($hapus) ? $jmlhapus = count($hapus) : $jmlhapus = 0;
        isset($proses) ? $jmlproses = count($proses) : $jmlproses = 0;
        isset($unproses) ? $jmlunproses = count($unproses) : $jmlunproses = 0;
        isset($cetak) ? $jmlcetak = count($cetak) : $jmlcetak = 0;
        //insert batch
        // var_dump('aaaa' . $jmldata);
        $userdtl_data = array();
        for ($i = 0; $i < $jmldata; $i++) {
            $userdtl_data[] = [
                'username' => $username,
                'idmodule' => $nurut[$i],
                'nurut' => $nurut[$i],
                'cmodule' => $cmodule[$i],
                'cmenu' => $cmenu[$i],
                'cmainmenu' => $cmainmenu[$i],
                'cparent' => $cparent[$i],
                'nlevel' => $nlevel[$i],
                // 'pakai' => isset($request->pakai[$i]) ? '1' : '0',
                // 'tambah' => isset($request->tambah[$i]) ? '1' : '0',
                // 'edit' => isset($request->edit[$i]) ? '1' : '0',
                // 'hapus' => isset($request->hapus[$i]) ? '1' : '0',
                // 'proses' => isset($request->proses[$i]) ? '1' : '0',
                // 'unproses' => isset($request->unproses[$i]) ? '1' : '0',
                // 'cetak' => isset($request->cetak[$i]) ? '1' : '0',
            ];
        }
        // var_dump($userdtl_data);
        Userdtl::insert($userdtl_data);
        for ($i = 0; $i < $jmlpakai; $i++) {
            $cmodule = $pakai[$i];
            Userdtl::where('cmodule', $cmodule)->where('username', $username)->update(['pakai' => 1]);
        }
        for ($i = 0; $i < $jmltambah; $i++) {
            $cmodule = $tambah[$i];
            Userdtl::where('cmodule', $cmodule)->where('username', $username)->update(['tambah' => 1]);
        }
        for ($i = 0; $i < $jmledit; $i++) {
            $cmodule = $edit[$i];
            Userdtl::where('cmodule', $cmodule)->where('username', $username)->update(['edit' => 1]);
        }
        for ($i = 0; $i < $jmlhapus; $i++) {
            $cmodule = $hapus[$i];
            Userdtl::where('cmodule', $cmodule)->where('username', $username)->update(['hapus' => 1]);
        }
        for ($i = 0; $i < $jmlproses; $i++) {
            $cmodule = $proses[$i];
            Userdtl::where('cmodule', $cmodule)->where('username', $username)->update(['proses' => 1]);
        }
        for ($i = 0; $i < $jmlunproses; $i++) {
            $cmodule = $unproses[$i];
            Userdtl::where('cmodule', $cmodule)->where('username', $username)->update(['unproses' => 1]);
        }
        for ($i = 0; $i < $jmlcetak; $i++) {
            $cmodule = $cetak[$i];
            Userdtl::where('cmodule', $cmodule)->where('username', $username)->update(['cetak' => 1]);
        }
        $msg = [
            'sukses' => 'Data berhasil di update', //view('user.tabel_bank')
        ];
        echo json_encode($msg);
    }

    public function destroy(user $user)
    {
        $id = $_POST['id'];
        $user = User::findOrFail($id);
        $username = $user->username;
        $pathphoto = $user->photo;
        Userdtl::where('username', $username)->delete();
        $user->delete();
        if ($pathphoto != null || $pathphoto != '') {
            Storage::delete($pathphoto);
        }
        return response()->json([
            'sukses' => 'Data berhasil di hapus',
        ]);
        // return redirect()->back()->with('message', 'Berhasil di hapus');
    }

    public function updateprofile()
    {
        $username = session('username');
        $data = [
            'menu' => 'utility',
            'submenu' => 'updateprofile',
            'submenu1' => 'ref_umum',
            'title' => 'Update Profile',
            'user' => User::where('username', $username)->first(),
            'userdtlmenu' => Userdtl::join('tbmodule', 'userdtl.cmodule', '=', 'tbmodule.cmodule')->where('username', $username)->orderBy('userdtl.nurut')->get(),
        ];
        // var_dump($data);
        return view('user.updateprofile')->with($data);
    }

    public function updateprofile_aksi1111(Request $request, user $user)
    {
        $validated = $request->validate(
            [
                'nama_lengkap' => 'required',
            ],
            [
                'nama_lengkap.required' => 'Nama Lengkap User harus di isi',
            ]
        );
        if ($validated) {
            $id = $request->id;
            $user = User::find($id);
            if ($request->hasFile('photo')) {
                $path = $request->file('photo')->store('uploads');
                $user->fill([
                    'nama_lengkap' => $request->nama_lengkap,
                    'telp' => is_null($request->telp) ? '' :  $request->telp,
                    'photo' => $path,
                    'user' => 'Update-' . $request->username . ', ' . date('d-m-Y h:i:s'),
                ]);
                $filelama = $request->photolama;
                if (($filelama != null || $filelama != '') and $filelama <> $path) {
                    // var_dump($filelama . ' ---- ' . $path);
                    File::delete($filelama);
                    Storage::delete($filelama);
                }
            } else {
                $user->fill([
                    'nama_lengkap' => $request->nama_lengkap,
                    'telp' => is_null($request->telp) ? '' :  $request->telp,
                    'user' => 'Update-' . $request->username . ', ' . date('d-m-Y h:i:s'),
                ]);
            }
            $user->save($validated);
            $msg = [
                'sukses' => 'Data berhasil di update', //view('user.tabel_bank')
            ];
        }
    }
}
