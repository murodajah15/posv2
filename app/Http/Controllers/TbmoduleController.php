<?php

namespace App\Http\Controllers;

use App\Http\Requests\TbModuleRequest;
use Illuminate\Http\Request;
use Session;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Tbmodule;
use App\Models\Userdtl;

//return type View
use Illuminate\View\View;

class TbmoduleController extends Controller
{
  public function index(Request $request) //: View
  {
    $username = session('username');
    $data = [
      'menu' => 'utility',
      'submenu' => 'tbmodule',
      'submenu1' => '',
      'title' => 'Tabel Module',
      // 'tbmodule' => Tbmodule::all(),
      'userdtlmenu' => Userdtl::where('userdtl.pakai', '1')->where('username', $username)->orderBy('userdtl.nurut')->get(), //Userdtl::join('tbmodule', 'userdtl.cmodule', '=', 'tbmodule.cmodule')->where('userdtl.pakai', '1')->where('username', $username)->orderBy('userdtl.nurut')->get(),
      'userdtl' => Userdtl::where('cmodule', 'Tabel Module')->where('username', $username)->first(),
    ];
    // var_dump($data);
    return view('tbmodule.index')->with($data);
  }
  public function tbmoduleajax(request $request) //: View
  {
    if ($request->ajax()) {
      $data = Tbmodule::select('*'); //->orderBy('cmodule', 'asc');
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
      return view('tbmodule');
    }
  }

  public function tabel_module(Request $request)
  {
    $data = [
      'menu' => 'utility',
      'submenu' => 'tbmodule',
      'submenu1' => 'ref_umum',
      'title' => 'Tabel Module',
      'tbmodule' => Tbmodule::orderBy('cmodule', 'asc')->get(),
    ];
    // var_dump($data);
    return view('tbmodule.tabel_module')->with($data);
  }

  public function create()
  {
    // if ($this->request->isAjax()) {
    $data = [
      'menu' => 'utility',
      'submenu' => 'tbmodule',
      'submenu1' => 'ref_umum',
      'title' => 'Tambah Data Tabel Module',
      // 'tbmodule' => Tbmodule::all(),
    ];
    // var_dump($data);
    return response()->json([
      'body' => view('tbmodule.modaltambah', [
        'tbmodule' => new tbmodule(), //Tbmodule::first(),
        'action' => route('tbmodule.store'),
        'vdata' => $data,
      ])->render(),
      'data' => $data,

    ]);
    // } else {
    //     exit('Maaf tidak dapat diproses');
    // }
  }

  public function store(tbmoduleRequest $request)
  {
    $tbmodule = new tbmodule();
    $validated = $request->validate(
      [
        'cmodule' => 'required|unique:tbmodule,cmodule',
        'nurut' => 'required',
      ],
      [
        'cmodule.unique' => 'cmodule tidak boleh sama',
        'cmodule.required' => 'cmodule harus di isi',
        'nurut.required' => 'No. Urut harus di isi',
      ]
    );
    if ($validated) {
      $tbmodule = new tbmodule();
      // $tbmodule->fill($request->all());
      // $tbmodule->aktif = $request->aktif == 'on' ? 'Y' : 'N';
      // $tbmodule->user = $request->username . date('d-m-Y');
      $aktif = isset($request->aktif) ? 'Y' : 'N';
      $cmainmenu = isset($request->cmainmenu) ? 'Y' : 'N';
      $tbmodule->fill([
        'nurut' => isset($request->nurut) ? $request->nurut : '0',
        'cmodule' => isset($request->cmodule) ? $request->cmodule : '',
        'cmenu' => isset($request->cmenu) ? $request->cmenu : '',
        'cparent' => isset($request->cparent) ? $request->cparent : '',
        'nlevel' => isset($request->nlevel) ?  $request->nlevel : '0',
        'cmainmenu' => $cmainmenu,
        'aktif' => $aktif,
        'user' => 'Tambah-' . $request->username . ', ' . date('d-m-Y h:i:s'),
      ]);
      $tbmodule->save($validated);
      $msg = [
        'sukses' => 'Data berhasil di tambah', //view('tbmodule.tabel_module')
      ];
    }
    echo json_encode($msg);

    // return redirect()->back()->with('message', 'Berhasil di simpan');
  }

  // public function show(string $id)
  public function show()
  {
    $id = $_GET['id'];
    // // if ($this->request->isAjax()) {
    $username = session('username');
    $data = [
      'menu' => 'utility',
      'submenu' => 'tbmodule',
      'submenu1' => 'ref_umum',
      'title' => 'Detail Tabel Module',
      'tbmodule' => Tbmodule::findOrFail($id),
      'userdtl' => Userdtl::where('cmodule', 'Tabel Module')->where('username', $username)->first(),
    ];
    // return view('tbmodule.modaltambah')->with($data);
    return response()->json([
      'body' => view('tbmodule.modaltambah', [
        'tbmodule' => Tbmodule::findOrFail($id),
        'action' => route('tbmodule.store'),
        'vdata' => $data,
      ])->render(),
      'data' => $data,
    ]);
    // // } else {
    // //     exit('Maaf tidak dapat diproses');
    // // }
  }

  public function edit(tbmodule $tbmodule)
  {
    // if ($this->request->isAjax()) {
    $data = [
      'menu' => 'utility',
      'submenu' => 'tbmodule',
      'submenu1' => 'ref_umum',
      'title' => 'Edit Data Tabel Module',
    ];
    // var_dump($data);

    // return response()->json([
    //     'data' => $data,
    // ]);
    return response()->json([
      'body' => view('tbmodule.modaltambah', [
        'tbmodule' => $tbmodule,
        'action' => route('tbmodule.update', $tbmodule->id),
        'vdata' => $data,
      ])->render(),
      'data' => $data,
    ]);
    // } else {
    //     exit('Maaf tidak dapat diproses');
    // }
  }

  public function update(Request $request, tbmodule $tbmodule)
  {
    // dd($request->cmodule . ' /  ' . $request->cmodulelama);
    if ($request->cmodule === $request->cmodulelama) {
      // var_dump($request->cmodule . '!======' . $request->cmodulelama);
      $validated = $request->validate(
        [
          'cmodule' => 'required',
        ],
        [
          'cmodule.required' => 'cmodule harus di isi',
        ]
      );
    } else {
      // var_dump($request->cmodule . '!=' . $request->cmodulelama);
      $validated = $request->validate(
        [
          'cmodule' => 'required|unique:tbmodule,cmodule',
        ],
        [
          'cmodule.unique' => 'cmodule tidak boleh sama',
          'cmodule.required' => 'cmodule harus di isi',
        ]
      );
    }
    if ($validated) {
      // dd($request->cparent . $request->cmodule);
      $aktif = $request->aktif == 'on' ? 'Y' : 'N';
      $cmainmenu = isset($request->cmainmenu) ? 'Y' : 'N';
      $tbmodule->fill([
        'nurut' => isset($request->nurut) ? $request->nurut : '0',
        'cmodule' => isset($request->cmodule) ? $request->cmodule : '',
        'cmenu' => isset($request->cmenu) ? $request->cmenu : '',
        'cparent' => isset($request->cparent) ? $request->cparent : '',
        'nlevel' => isset($request->nlevel) ?  $request->nlevel : '0',
        'cmainmenu' => $cmainmenu,
        'aktif' => $aktif,
        'user' => 'Update-' . $request->username . ', ' . date('d-m-Y h:i:s'),
      ]);
      $tbmodule->save($validated);
      $msg = [
        'sukses' => 'Data berhasil di update', //view('tbmodule.tabel_module')
      ];
    } else {
      $msg = [
        'sukses' => 'Data gagal di update', //view('tbmodule.tabel_module')
      ];
    }
    echo json_encode($msg);
    // return redirect()->back()->with('message', 'Berhasil di update');
  }

  public function destroy(tbmodule $tbmodule)
  {
    $tbmodule->delete();
    return response()->json([
      'sukses' => 'Data berhasil di hapus',
    ]);
    // return redirect()->back()->with('message', 'Berhasil di hapus');
  }

  public function tbmodule_urutkan(request $request)
  {
    if ($request->ajax()) {
      $results = Tbmodule::orderBy('nurut')->get();
      $i = 1;
      foreach ($results as $row) {
        $nurut = $i;
        $simpandata = [
          'nurut' => $nurut
        ];
        $id = $row['id'];
        Tbmodule::where('id', $id)->update($simpandata);
        $i++;
      }
      $msg = [
        'sukses' => 'Data berhasil di update', //view('tbmodule.tabel_module')
      ];
      echo json_encode($msg);
    } else {
      exit('Maaf tidak dapat diproses');
    }
  }
}
