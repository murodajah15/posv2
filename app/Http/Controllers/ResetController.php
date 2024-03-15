<?php

namespace App\Http\Controllers;

// use App\Http\Requests\TbjnkeluarRequest;
use Illuminate\Http\Request;
use Session;
// use Yajra\DataTables\Contracts\DataTables;
// use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use App\Models\Userdtl;

// //return type View
// use Illuminate\View\View;

class ResetController extends Controller
{
    public function reset(Request $request) //: View
    {
        $username = session('username');
        $data = [
            'menu' => 'utility',
            'submenu' => 'reset',
            'submenu1' => 'ref_umum',
            'title' => 'Reset Database',
            // 'tbjnkeluar' => Tbjnkeluar::all(),
            'userdtlmenu' => Userdtl::join('tbmodule', 'userdtl.cmodule', '=', 'tbmodule.cmodule')->where('userdtl.pakai', '1')->where('username', $username)->orderBy('userdtl.nurut')->get(),
            'userdtl' => Userdtl::where('cmodule', 'Reset Database')->where('username', $username)->first(),
        ];
        // var_dump($data);
        return view('reset.index')->with($data);
    }

    public function reset_proses(Request $request)
    {
        if ($request->Ajax()) {
            if (isset($request->approv)) {
                dd('on');
            } else {
                dd('off');
            }
            // DB::table('tbbarang')->truncate();
            // DB::table('tbgudang')->truncate();
            // DB::table('tbjntrans')->truncate();
            // DB::table('tbjnbrg')->truncate();
            // DB::table('tbsatuan')->truncate();
            // DB::table('tbnegara')->truncate();
            // DB::table('tbmove')->truncate();
            // DB::table('tbdiscount')->truncate();
            // DB::table('tbcustomer')->truncate();
            // DB::table('tbsupplier')->truncate();
            // DB::table('tbmultiprc')->truncate();
            // DB::table('tbsales')->truncate();
            // DB::table('tbjnkeluar')->truncate();
            // DB::table('soh')->truncate();
            // DB::table('sod')->truncate();
            // DB::table('jualh')->truncate();
            // DB::table('juald')->truncate();
            // DB::table('poh')->truncate();
            // DB::table('pod')->truncate();
            // DB::table('belih')->truncate();
            // DB::table('belid')->truncate();
            // DB::table('terimah')->truncate();
            // DB::table('terimad')->truncate();
            // DB::table('keluarh')->truncate();
            // DB::table('keluard')->truncate();
            // DB::table('opnameh')->truncate();
            // DB::table('opnamed')->truncate();
            if (isset($_GET['approv'])) {
                DB::table('approv_batas_piutang')->truncate();
            }
            // DB::table('kasir_tunai')->truncate();
            // DB::table('kasir_tagihan')->truncate();
            // DB::table('kasir_tagihand')->truncate();
            // DB::table('mohklruangh')->truncate();
            // DB::table('mohklruangd')->truncate();
            // DB::table('kasir_keluarh')->truncate();
            // DB::table('kasir_keluard')->truncate();
            $msg = [
                'sukses' => true, //view('tbjntrans.tabel_jntrans')
            ];
            echo json_encode($msg);
            // return redirect()->back()->with('message', 'Berhasil di hapus');
        } else {
            exit('Maaf tidak dapat diproses');
        }
    }

    public function alter_proses(Request $request)
    {
        $n = 0;
        $count = 0;
        $db = 'Tables_in_' . env('DB_DATABASE');
        $tables = DB::select('SHOW TABLES');
        $tablesarr = array();
        foreach ($tables as $tablerow) {
            $n++;
            echo "Table " . ++$count . ": {$tablerow[0]}" . "<br>";
            $table = $tablerow[0];
            $count = 0;
            $sqlfields = "SELECT * FROM information_schema.columns WHERE table_schema = '$db' AND table_name = '$table'"; // Change the table_name your own table name
            $resultfields = DB::select($tables);
            // $rowfields = $resultfields->count();
            while ($row = $resultfields) {
                $nmfield = $row[3];
                $count++;
                if ($row[7] == "int" or $row[7] == "bigint" or $row[7] == "decimal") {
                    $sql = "ALTER TABLE $table ALTER COLUMN $nmfield SET DEFAULT 0";
                }
                if ($row[7] == "varchar" or $row[7] == "char" or $row[7] == "text") {
                    $sql = "ALTER TABLE $table ALTER COLUMN $nmfield SET DEFAULT ''";
                }
                if ($row[7] == "enum") {
                    $sql = "ALTER TABLE $table ALTER COLUMN $nmfield SET DEFAULT 'N'";
                }
                if ($row[7] == "date") {
                    $sql = "ALTER TABLE $table CHANGE COLUMN $nmfield $nmfield DATE NULL DEFAULT NULL"; //'0000-00-00 00:00:00'";
                }
                if ($row[7] == "datetime" or $row[7] == "timestamp") {
                    $sql = "ALTER TABLE $table CHANGE COLUMN $nmfield $nmfield TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP"; //'0000-00-00 00:00:00'";
                }
                // $query = mysqli_query($connect, $sql);
            }
            echo $count . ' fields updated <br>';
        }
    }
}
