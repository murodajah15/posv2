<?php

namespace App\Http\Controllers;

use Illuminate\Console\Command;
use Carbon\Carbon;

use Illuminate\Http\Request;
use App\Models\Hisuser;
use App\Models\Userdtl;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;


//return type View
use Illuminate\View\View;

class BackupController extends Controller
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'database:backup';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Command description';
  /**
   * Create a new command instance.
   *
   * @return void
   */

  public function index(Request $request) //: View
  {
    $username = session('username');
    $data = [
      'menu' => 'proses',
      'submenu' => 'hisuser',
      'submenu1' => 'ref_umum',
      'title' => 'Backup Database',
      // 'hisuser' => Hisuser::all(),
      'userdtlmenu' => Userdtl::join('tbmodule', 'userdtl.cmodule', '=', 'tbmodule.cmodule')->where('username', $username)->orderBy('userdtl.nurut')->get(),
      'userdtl' => Userdtl::where('cmodule', 'History User')->where('username', $username)->first(),
    ];
    // var_dump($data);
    return view('backup.index')->with($data);
  }

  // public function show(string $id)
  public function backup_proses(Request $request)
  {
    // $filename = "backup-" . Carbon::now()->format('Y-m-d') . ".sql";
    // $command = "mysqldump --user=" . env('DB_USERNAME') . " --password=" . env('DB_PASSWORD') . " --host=" . env('DB_HOST') . " " . env('DB_DATABASE') . "  | gzip > " . storage_path() . "/app/backup/" . $filename;
    // $returnVar = NULL;
    // $output  = NULL;
    // exec($command, $output, $returnVar);

    $return = '';
    $db = 'Tables_in_' . env('DB_DATABASE');
    $tables = DB::select('SHOW TABLES');
    $tablesarr = array();
    foreach ($tables as $table) {
      $tablesarr[] = $table->$db;
    }
    // var_dump($tablesarr);

    // $row2 = DB::select('SHOW CREATE TABLE ' . 'users');
    // // dd($row2);
    // $return .= "\n\n" . $row2[0]->{'Create Table'} . ";\n\n";


    // $table = 'users';
    // $result = DB::table('users')->first();
    // dd($result);

    // $result = count(Schema::getColumnListing('users'));
    // dd($result);

    // $result = DB::table('users')->first();
    // // dd($result);
    // $num_fields = mysqli_num_fields($result);
    // dd($num_fields);

    // $return .= 'INSERT INTO ' . 'users' . ' VALUES(';
    // $isi = DB::table('users')->get();
    // $num_fields = count(Schema::getColumnListing('users'));
    // $field = DB::select('SHOW COLUMNS FROM ' . 'users');
    // for ($j = 0; $j < $num_fields; $j++) {
    //   $return .= ($field[$j]->{'Field'});
    //   if ($j < ($num_fields - 1)) {
    //     $return .= ',';
    //   }
    // }
    // $return .= ");\n";

    //cycle through
    $n = 0;
    foreach ($tablesarr as $table) {
      $n++;
      if ($n > 0) {
        // $result = DB::table($table)->first();
        $return .= 'DROP TABLE ' . $table . ';';
        $row2 = DB::select('SHOW CREATE TABLE ' . $table);
        $return .= "\n\n" . $row2[0]->{'Create Table'} . ";\n\n";

        // insert into users (username,nama_lengkap) values ('1','1'),('2','2')

        $num_fields = count(Schema::getColumnListing($table));
        $field = DB::select('SHOW COLUMNS FROM ' . $table);
        $isi = DB::table($table)->get();
        $reccount = DB::table($table)->count();
        if ($reccount > 0) {
          $return .= 'INSERT INTO ' . $table . ' VALUES(';
        }
        // $i = 0;
        // for ($i = 0; $i < $num_fields; $i++) {
        foreach ($isi as $row) {
          $j = 0;
          for ($j = 0; $j < $num_fields; $j++) {
            $nmfield = ($field[$j]->{'Field'});
            $return .= "'" . $row->$nmfield . "'";
            if ($j < ($num_fields - 1)) {
              $return .= ',';
            }
          }
          $return .= ");\n";
        }
      }
      // if ($n == 2) {
      //   $return .= "\n\n\n";
      //   echo "<pre>" . var_dump($return) . "</pre>";
      //   die();
      //   exit();
      // }
    }

    // echo "<pre>" . var_dump($return) . "</pre>";

    $database = env('DB_DATABASE');
    $today = date('d-m-Y H-i-s');
    $namafile = $database . '-' . $today . '.sql';

    Storage::put('backup/' . $namafile, $return);



    // return response()->download(storage_path('app/public/backup/' . $namafile));
    return Response::download(storage_path('app/public/backup/' . $namafile))->deleteFileAfterSend(true);

    // $namafile = 'pos-05-11-2023 06-50-40.sql';
    // return Response::download($namafile);

    // if (Storage::disk('public')->exists("backup/" . $namafile))
    // $path = Storage::disk('public')->path("backup/" . $namafile);
    // $content = file_get_contents($path);
    // return response($content)->withHeaders(['Content-type' => mime_content_type($path)]);

    // $file_path = public_path() . 'backup/' . $namafile;
    // $headers = [
    //   'Content-Type' => 'application/sql',
    // ];
    // return Response::download('backup/' . $namafile);

    // $file_path = public_path('backup/' . $namafile);
    // return response()->download($file_path)->deleteFileAfterSend(true);

    // Storage::delete('backup/' . $namafile); //ok

    // $handle = fopen($namafile, 'w+');
    // fwrite($handle, $return);
    // fclose($handle);

    // header('Content-Description: File Transfer');
    // header('Content-Type: application/octet-stream');
    // header('Content-Disposition: attachment; filename=' . basename($namafile));
    // header('Content-Transfer-Encoding: binary');
    // header('Expires: 0');
    // header('Cache-Control: private');
    // header('Pragma: private');
    // // header('Content-Length: ' . filesize($namafile));

    // ob_clean();
    // flush();
    // readfile($namafile);
    // unlink($namafile);

    // $msg = [
    //   'sukses' => true, //view('user.tabel_bank')
    // ];
    // echo json_encode($msg);
  }
}
