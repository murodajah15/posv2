<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Session;
use App\Models\Userdtl;
use App\Models\User;

class UpdateprofileController extends Controller
{
  public function index() //: View
  {
    $username = session('username');
    $data = [
      'menu' => 'utility',
      'submenu' => 'updateprofile',
      'submenu1' => 'ref_umum',
      'title' => 'Update Profile',
      'user' => User::where('username', $username)->first(),
      'userdtlmenu' => Userdtl::join('tbmodule', 'userdtl.cmodule', '=', 'tbmodule.cmodule')->where('userdtl.pakai', '1')->where('username', $username)->orderBy('userdtl.nurut')->get(),
    ];
    // var_dump($data);
    return view('user.updateprofile')->with($data);
  }

  public function show()
  {
    return redirect('home');
  }

  public function update(Request $request, User $user)
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
      echo json_encode($msg);
    }
  }
}
