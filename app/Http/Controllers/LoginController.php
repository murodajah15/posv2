<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Saplikasi;
use App\Models\Hisuser;
use Illuminate\Support\Facades\Hash;

// use Session; 

//return type View
use Illuminate\View\View;

class LoginController extends Controller
{
  /**
   * index
   *
   * @return View
   */
  public function index(): View
  {
    //get posts
    // $posts = Post::latest()->paginate(5);

    //render view with posts
    return view('/login.index');
  }

  public function login()
  {
    if (Auth::check()) {
      return redirect('home');
    } else {
      return view('login');
    }
  }

  public function actionlogin(Request $request)
  {
    $data = [
      'username' => $request->input('username'),
      'password' => $request->input('password'),
    ];

    //cek passord MD5 dan rubah password ke Hash
    $mypassword = md5($request->input('password'));
    $cek = User::where('username', $request->input('username'))->where('password', $mypassword)->first();
    if (isset($cek)) {
      if (strlen($cek->password) < 50) {
        // dd($cek->password . '  ' . strlen($cek->password));
        $passwordbaru = Hash::make($request->input('password'));
        // DB::table('users')->where('username', $request->input('username'))->where('password', $mypassword)
        //   ->update(['password' => $passwordbaru]);
        User::where('username', $request->input('username'))->where('password', $mypassword)
          ->update(['password' => $passwordbaru]);
      }
    }

    if (Auth::Attempt($data)) {
      $session = session();
      // $email = Auth::user()->email;
      // $user = User::where('email', $email)->first();
      $username = Auth::user()->username;
      $user = User::where('username', $username)->first();
      // $data = [
      //   'user' => User::where('email', $email)->first(),
      // ];
      // var_dump($data);
      // echo 'bbb' . $user['username'];
      session(['email' => $user['email']]);
      session(['username' => $user['username']]);
      session(['level' => $user['level']]);
      session(['nama_lengkap' => $user['nama_lengkap']]);
      session(['photo' => $user['photo']]);
      $datetime = date('Y-m-d, H:i:s');
      User::where('username', $username)->update(['last_login' => $datetime]);

      //Create History
      $tanggal = date('Y-m-d');
      $datetime = date('Y-m-d H:i:s');
      $dokumen = $username;
      $form = 'Login';
      $status = 'Login';
      $catatan = isset($request->catatan) ? $request->catatan : '';
      $username = session('username');
      $hisuser = ['tanggal' => $tanggal, 'dokumen' => $dokumen, 'form' => $form, 'status' => $status, 'user' => $username, 'catatan' => $catatan, 'datetime' => $datetime];
      Hisuser::create($hisuser);
      // $hisuser = new Hisuser();
      // $hisuser->tanggal = $tanggal;
      // $hisuser->datetime = $datetime;
      // $hisuser->dokumen = $dokumen;
      // $hisuser->form = $form;
      // $hisuser->status = $status;
      // $hisuser->catatan = $catatan;
      // $hisuser->username = $username;
      // $hisuser->save();


      $saplikasi = Saplikasi::where('aktif', 'Y')->first();
      session(['nm_perusahaan1' => $saplikasi['nm_perusahaan1']]);
      session(['nm_perusahaan' => $saplikasi['nm_perusahaan']]);
      session(['alamat_perusahaan' => $saplikasi['alamat']]);
      session(['telp_perusahaan' => $saplikasi['telp']]);
      session(['lppn' => $saplikasi['lppn']]);
      session(['ppn' => $saplikasi['ppn']]);
      session(['norek1' => $saplikasi['norek1']]);
      session(['norek2' => $saplikasi['norek2']]);
      session(['llogo' => $saplikasi['llogo']]);
      session(['logo' => $saplikasi['logo']]);

      // echo $email . '   ' . $user['username'] . '   ' . $session->get('username');
      // foreach ($data as $row) {
      //   echo '<br>AA' . $email . '   ' . $row . '    ' . $row['level'];
      // }
      return redirect('home');
    } else {
      // $request->Session::flash('status', 'Task was successful!');
      Session::flash('error', 'Username atau Password Salah');
      return redirect('/');
    }
  }

  public function actionlogout()
  {
    Session::flush();
    Auth::logout();
    return redirect('/');
  }
}
