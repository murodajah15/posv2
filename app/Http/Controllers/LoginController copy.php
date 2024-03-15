<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use App\Models\Saplikasi;

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
      'email' => $request->input('email'),
      'password' => $request->input('password'),
      // 'username' => 'username',
    ];

    if (Auth::Attempt($data)) {
      $session = session();
      $email = Auth::user()->email;
      $user = User::where('email', $email)->first();
      // $data = [
      //   'user' => User::where('email', $email)->first(),
      // ];
      // var_dump($data);
      // echo 'bbb' . $user['username'];
      session(['username' => $user['username']]);
      session(['level' => $user['level']]);
      session(['nama_lengkap' => $user['nama_lengkap']]);
      session(['photo' => $user['photo']]);

      $saplikasi = Saplikasi::where('aktif', 'Y')->first();
      session(['lppn' => $saplikasi['lppn']]);
      session(['ppn' => $saplikasi['ppn']]);


      // echo $email . '   ' . $user['username'] . '   ' . $session->get('username');
      // foreach ($data as $row) {
      //   echo '<br>AA' . $email . '   ' . $row . '    ' . $row['level'];
      // }
      return redirect('home');
    } else {
      // $request->Session::flash('status', 'Task was successful!');
      Session::flash('error', 'User atau Password Salah');
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
