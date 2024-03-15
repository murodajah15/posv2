<?php

namespace App\Http\Controllers;

use App\Http\Requests\HisuserRequest;
use Illuminate\Http\Request;
use Session;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Hisuser;
use App\Models\Userdtl;
use Illuminate\Support\Facades\DB;

//return type View
use Illuminate\View\View;

class HisuserController extends Controller
{
  public function index(Request $request) //: View
  {
    // for ($x = 0; $x <= 1000000; $x++) {
    //   //Create History
    //   $tanggal = date('Y-m-d');
    //   $datetime = date('Y-m-d H:i:s');
    //   $dokumen = uniqid();
    //   $form = 'Penjualan';
    //   $status = 'Tambah';
    //   $catatan = isset($request->catatan) ? $request->catatan : '';
    //   $username = session('username');
    //   DB::table('hisuser')->insert(['tanggal' => $tanggal, 'dokumen' => $dokumen, 'form' => $form, 'status' => $status, 'user' => $username, 'catatan' => $catatan, 'datetime' => $datetime]);
    // }

    $username = session('username');
    $data = [
      'menu' => 'utility',
      'submenu' => 'hisuser',
      'submenu1' => 'ref_umum',
      'title' => 'History User',
      // 'hisuser' => Hisuser::all(),
      'userdtlmenu' => Userdtl::join('tbmodule', 'userdtl.cmodule', '=', 'tbmodule.cmodule')->where('username', $username)->orderBy('userdtl.nurut')->get(),
      'userdtl' => Userdtl::where('cmodule', 'History User')->where('username', $username)->first(),
    ];
    // var_dump($data);
    return view('hisuser.index')->with($data);
  }
  public function hisuserajax(Request $request) //: View
  {
    if ($request->ajax()) {
      $data = Hisuser::select('*'); //->orderBy('cmodule', 'asc');
      return Datatables::of($data)
        ->addIndexColumn()
        ->addColumn('cmodule1', function ($row) {
          $id = $row['id'];
          $btn = $row['cmodule']; //'<a href="#"' . 'onclick=detail(' . $id . ')>' .  $row['cmodule'] . '</a>';
          return $btn;
        })
        ->rawColumns(['cmodule1'])
        // ->addIndexColumn()
        // ->addColumn('action', function ($row) {
        //     $btn = '<a href="javascript:void(0)" class="edit btn btn-primary btn-sm">View</a>';
        //     return $btn;
        // })
        // ->rawColumns(['action'])
        ->make(true);
      return view('hisuser');
    }
  }

  // public function show(string $id)
  public function show()
  {

    $id = $_GET['id'];
    // // if ($this->request->isAjax()) {
    $username = session('username');
    $data = [
      'menu' => 'utility',
      'submenu' => 'hisuser',
      'submenu1' => 'ref_umum',
      'title' => 'Detail Setup Aplikasi',
      'hisuser' => Hisuser::findOrFail($id),
      'userdtl' => Userdtl::where('cmodule', 'History User')->where('username', $username)->first(),
    ];
    // return view('hisuser.modaltambah')->with($data);
    return response()->json([
      'body' => view('hisuser.modaltambah', [
        'hisuser' => Hisuser::findOrFail($id),
        'action' => route('hisuser.store'),
        'vdata' => $data,
      ])->render(),
      'data' => $data,
    ]);
    // // } else {
    // //     exit('Maaf tidak dapat diproses');
    // // }
  }

  public function edit(hisuser $hisuser)
  {
    // if ($this->request->isAjax()) {
    $data = [
      'menu' => 'utility',
      'submenu' => 'hisuser',
      'submenu1' => 'ref_umum',
      'title' => 'Edit Data Setup Aplikasi',
    ];
    // var_dump($data);

    // return response()->json([
    //     'data' => $data,
    // ]);
    return response()->json([
      'body' => view('hisuser.modaltambah', [
        'hisuser' => $hisuser,
        'action' => route('hisuser.update', $hisuser->id),
        'vdata' => $data,
      ])->render(),
      'data' => $data,
    ]);
    // } else {
    //     exit('Maaf tidak dapat diproses');
    // }
  }

  public function destroy(hisuser $hisuser)
  {
    $id = $_POST['id'];
    $hisuser = Hisuser::findOrFail($id);
    $hisuser->delete();
    return response()->json([
      'sukses' => 'Data berhasil di hapus',
    ]);
    // return redirect()->back()->with('message', 'Berhasil di hapus');
  }
}
