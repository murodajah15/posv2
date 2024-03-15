<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Session;
use App\Models\Userdtl;
use App\Models\User;

class PassyouController extends Controller
{
  public function index() //: View
  {
    $username = session('username');
    $data = [
      'menu' => 'utility',
      'submenu' => 'passyou',
      'submenu1' => 'ref_umum',
      'title' => 'Rubah Password',
      'user' => User::where('username', $username)->first(),
      'userdtlmenu' => Userdtl::join('tbmodule', 'userdtl.cmodule', '=', 'tbmodule.cmodule')->where('userdtl.pakai', '1')->where('username', $username)->orderBy('userdtl.nurut')->get(),
    ];
    // var_dump($data);
    return view('user.passyou')->with($data);
  }

  public function show()
  {
    return redirect('home');
  }

  public function update(Request $request, User $user)
  {
    // var_dump($request->kode . '!=' . $request->kodelama);
    $validated = $request->validate(
      [
        'password' => 'required|min:3|max:250',
        'passwordbaru' => 'required|min:3|max:250',
        'confirm_password' => 'required|same:passwordbaru|min:3',
      ],
      [
        'password.required' => 'Password Lama harus di isi',
        'passwordbaru.required' => 'Password harus di isi',
      ]
    );

    if ($validated) {
      $data = [
        'email' => $request->email,
        'password' => $request->password,
      ];
      if (Auth::Attempt($data)) {
        $id = $request->id;
        $user = User::find($id);
        $user->fill([
          'password' => Hash::make($request->passwordbaru),
        ]);
        $user->save($validated);
        $msg = [
          'sukses' => 'Data berhasil di update', //view('tbbank.tabel_bank')
        ];
      } else {
        $msg = [
          'sukses' => 'Password lama salah !', //view('tbbank.tabel_bank')
        ];
      }
    } else {
      $msg = [
        'sukses' => 'Data gagal di update !', //view('tbbank.tabel_bank')
      ];
    }
    echo json_encode($msg);
    // return redirect()->back()->with('message', 'Berhasil di update');
  }
}
