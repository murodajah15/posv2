<?php
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

$html = '<style>
        table {border-collapse: collapse;}
        body { font-family: comic sans ms;}
    </style>';

if (isset($ppn) and $ppn <= 0) {
    if (session('nm_perusahaan1') != '') {
        $nm_perusahaan = $nm_perusahaan1;
    }
}

$llogo = session('llogo');
$logo = session('logo');
$html .= '<table border="0"><tr>';
if ($llogo == 'Y' and $logo != '') {
    $filename = asset('storage/uploads/' . Session::get('logo', 'default'));
    if (env('APP_URL') == 'http://localhost') {
        $filename = public_path('storage/uploads/' . Session::get('logo', 'default'));
        if (file_exists($filename)) {
            $html .= '<td><img src=' . $filename . ' width="60"></td>';
        }
    } else {
        $filename = Session::get('logo', 'default');
        if (File::exists('../../pos/storage/app/public/uploads/' . $filename)) {
            $html .= '<td><img src=' . '../../pos/storage/app/public/uploads/' . $filename . ' width="60"></td>';
        }
    }
}

// if (Storage::exists($filename)) {
//     dd('file esxists');
// } else {
//     dd('no file found');
// }

$html .= '<td style="font-size:18px">' . session('nm_perusahaan') . '<br><span style="font-size:12px;">' . session('alamat_perusahaan') . '<br>' . session('telp_perusahaan') . '</font></td></tr></table>';

session(['tampillogo' => $html]);
