<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Session;

class RegisterController extends Controller
{
    public function register()
    {
        $data = [
            'menu' => '',
            'submenu' => '',
            'submenu1' => '',
            'title' => 'Dashboard',
        ];
        return view('login/register', $data);
    }

    public function actionregister(Request $request)
    {
        $users = User::create([
            'email' => $request->email,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'aktif' => 'N',
        ]);

        Session::flash('message', 'Register Berhasil. Akun Anda sudah Aktif silahkan Login menggunakan username dan password.');
        return redirect('register');
    }
}
