@include('home.akses')
<?php
$pakai = session('pakai');
$tambah = session('tambah');
$edit = session('edit');
$hapus = session('hapus');
$proses = session('proses');
$unproses = session('unproses');
$cetak = session('cetak');

use Illuminate\Support\Facades\DB;

date_default_timezone_set('Asia/Jakarta');
// $tgl1 = date('d-m-Y', strtotime($tanggal1));
// $tgl2 = date('d-m-Y', strtotime($tanggal2));

$tgl1 = $tanggal1;
$tgl2 = $tanggal2;

$nm_perusahaan = $saplikasi->nm_perusahaan;
$alamat_perusahaan = $saplikasi->alamat;
$telp_perusahaan = $saplikasi->telp;
$no = 1;

// if ($semuaperiode != 'Y') {
$tanggal = $tanggal1 . ' s/d ' . $tanggal2;
// } else {
//     $tanggal = 'Semua Periode';
// }

if ($recjualh == 0) {
    echo '<script>
        alert(\'Tidak ada data sesuai kriteria\')
                window.close()
    </script>';
}

?>
@include('report.judulreport')
<?php
echo 'Tanggal : ' . $tanggal . '</font>';

echo '<table table-layout="fixed"; cellpadding="2"; cellspacing="0"; style=font-size:11px; class="table table-striped table table-bordered;">
   <tr>
    <th width="30px" height="20" rowspan="2"><font size="1" color="black">NO.</th>
    <th width="100px" rowspan="2"><font size="1" color="black">NO. FAKTUR</th>
    <th width="75px" rowspan="2"><font size="1" color="black">TANGGAL<br>FAKTUR</th>
    <th width="260px" rowspan="2"><font size="1" color="black">NAMA CUSTOMER</th>
    <th width="90px" rowspan="2"><font size="1" color="black">NILAI<br>PIUTANG</th>
    <th colspan="5">PENERIMAAN KASIR TUNAI</th>
    <th width="90px" rowspan="2"><font size="1" color="black">SALDO<br>PIUTANG</th>
    <tr><th width="90px">TUNAI</th>
    <th width="90px">TRANSFER</th>
    <th width="90px">CEK/GIRO</th>
    <th width="90px">DEBIT<br>CARD</th>
    <th width="90px">CREDIT<br>CARD</th>
   </tr>';

$gtotal = 0;
$gcash = 0;
$gtransfer = 0;
$gcek_giro = 0;
$gdebit_card = 0;
$gcredit_card = 0;
$gkurangbayar = 0;
foreach ($jualh as $row) {
    $nojual = $row['nojual'];
    $tgljual = $row['tgljual'];
    // $tgljual = date('d-m-Y', strtotime($tgljual));
    $nmcustomer = $row['nmcustomer'];
    $ntotal = $row['total'];
    $total = number_format($row['total'], 0, '.', ',');
    $kasir_tunai = DB::table('kasir_tunai')
        ->where('proses', 'Y')
        ->where('nojual', $nojual)
        ->where('tglkwitansi', '>=', $tgl1)
        ->where('tglkwitansi', '<=', $tgl2)
        ->get();
    $ncash = 0;
    $ntransfer = 0;
    $ncek_giro = 0;
    $ndebit_card = 0;
    $ncredit_card = 0;
    foreach ($kasir_tunai as $rowd) {
        $tglkwitansi = $rowd->tglkwitansi;
        switch ($rowd->carabayar) {
            case 'Cash':
                $ncash = $ncash + $rowd->bayar;
                break;
            case 'Transfer':
                $ntransfer = $ntransfer + $rowd->bayar;
                break;
            case 'Cek/Giro':
                $ncek_giro = $ncek_giro + $rowd->bayar;
                break;
            case 'Debit Card':
                $ndebit_card = $ndebit_card + $rowd->bayar;
                break;
            case 'Cek/Giro':
                $ncek_giro = $ncek_giro + $rowd->bayar;
                break;
            default:
                $ncredit_card = $ncredit_card + $rowd['credit_card'];
        }
    }
    if (isset($tglkwitansi)) {
        $tglkwitansi = date('d-m-Y', strtotime($tglkwitansi));
    } else {
        $tglkwitansi = '';
    }
    $cash = number_format($ncash, 0, '.', ',');
    $transfer = number_format($ntransfer, 0, '.', ',');
    $cek_giro = number_format($ncek_giro, 0, '.', ',');
    $debit_card = number_format($ndebit_card, 0, '.', ',');
    $credit_card = number_format($ncredit_card, 0, '.', ',');
    $nbayar = $ncash + $ntransfer + $ncek_giro + $ndebit_card + $ncredit_card;
    $nkurangbayar = $ntotal - $nbayar;
    $kurangbayar = number_format($nkurangbayar, 0, '.', ',');
    echo '<tr>
    <td align="center">' .
        $no .
        '</td>
    <td>' .
        $nojual .
        '</td>
    <td style="text-align:center;">' .
        $tgljual .
        '</td>
    <td>' .
        $nmcustomer .
        '</td>
    <td align="right">' .
        $total .
        '</td>
    <td align="right">' .
        $cash .
        '</td>
    <td align="right">' .
        $transfer .
        '</td>
    <td align="right">' .
        $cek_giro .
        '</td>
    <td align="right">' .
        $debit_card .
        '</td>
    <td align="right">' .
        $credit_card .
        '</td>
    <td align="right">' .
        $kurangbayar .
        '</td>
    </tr>';
    $no++;
    $gtotal = $gtotal + $ntotal;
    $gcash = $gcash + $ncash;
    $gtransfer = $gtransfer + $ntransfer;
    $gcek_giro = $gcek_giro + $ncek_giro;
    $gdebit_card = $gdebit_card + $ndebit_card;
    $gcredit_card = $gcredit_card + $ncredit_card;
    $gkurangbayar = $gkurangbayar + $nkurangbayar;
}
$gtotal = number_format($gtotal, 0, '.', ',');
$gcash = number_format($gcash, 0, '.', ',');
$gtransfer = number_format($gtransfer, 0, '.', ',');
$gcek_giro = number_format($gcek_giro, 0, '.', ',');
$gdebit_card = number_format($gdebit_card, 0, '.', ',');
$gcredit_card = number_format($gcredit_card, 0, '.', ',');
$gkurangbayar = number_format($gkurangbayar, 0, '.', ',');
echo '<tr><td colspan="4" height="10px" align="right">' .
    'Grand Total ...' .
    '</td>
    <td align="right">' .
    $gtotal .
    '</td>
    <td align="right">' .
    $gcash .
    '</td>
    <td align="right">' .
    $gtransfer .
    '</td>
    <td align="right">' .
    $gcek_giro .
    '</td>
    <td align="right">' .
    $gdebit_card .
    '</td>
    <td align="right">' .
    $gcredit_card .
    '</td>
    <td align="right">' .
    $gkurangbayar .
    '</td>
    </tr>';

echo '</table><br>PENERIMAAN TAGIHAN';
echo '<table table-layout="fixed"; cellpadding="2"; cellspacing="0"; style=font-size:11px; class="table table-striped table table-bordered;">
    <tr>
    <th width="30px" height="20" rowspan="2"><font size="1" color="black">NO.</th>
    <th width="100px" rowspan="2"><font size="1" color="black">NO. FAKTUR</th>
    <th width="75px" rowspan="2"><font size="1" color="black">TANGGAL<br>FAKTUR</th>
    <th width="75px" rowspan="2"><font size="1" color="black">TANGGAL<br>BAYAR</th>
    <th width="350px" rowspan="2"><font size="1" color="black">NAMA CUSTOMER</th>
    <th width="85px" rowspan="2"><font size="1" color="black">NILAI<br>PIUTANG</th>
    <th colspan="5">CARA BAYAR</th>
    <th width="85px" rowspan="2"><font size="1" color="black">SALDO<br>PIUTANG</th>
    <tr><th width="90px">TUNAI</th>
    <th width="85px">TRANSFER</th>
    <th width="85px">CEK/GIRO</th>
    <th width="85px">DEBIT<br>CARD</th>
    <th width="85px">CREDIT<br>CARD</th>
    </tr>';

$no = 1;
$gtotal = 0;
$gcash = 0;
$gtransfer = 0;
$gcek_giro = 0;
$gdebit_card = 0;
$gcredit_card = 0;
$gkurangbayar = 0;

$querydok = DB::table('kasir_tagihan')
    ->where('kasir_tagihan.proses', 'Y')
    ->where('kasir_tagihan.tglkwitansi', '>=', $tanggal1)
    ->where('kasir_tagihan.tglkwitansi', '<=', $tanggal2)
    ->join('kasir_tagihand', 'kasir_tagihand.nokwitansi', '=', 'kasir_tagihan.nokwitansi')
    ->groupBy('kasir_tagihand.nojual')
    ->select('kasir_tagihan.tglkwitansi', 'kasir_tagihand.nojual')
    ->get();
// dd($querydok);
foreach ($querydok as $rowdok) {
    $nojual = $rowdok->nojual;
    $tglkwitansi = $rowdok->tglkwitansi;

    $queryh = DB::table('kasir_tagihand')
        ->where('kasir_tagihan.proses', 'Y')
        ->where('kasir_tagihand.nojual', $nojual)
        ->join('kasir_tagihan', 'kasir_tagihan.nokwitansi', '=', 'kasir_tagihand.nokwitansi')
        ->join('jualh', 'jualh.nojual', '=', 'kasir_tagihand.nojual')
        ->select('kasir_tagihand.nojual', 'kasir_tagihan.carabayar', 'kasir_tagihand.bayar', 'jualh.tgljual')
        ->get();

    $ncash = 0;
    $ntransfer = 0;
    $ncek_giro = 0;
    $ndebit_card = 0;
    $ncredit_card = 0;

    foreach ($queryh as $rowd) {
        switch ($rowd->carabayar) {
            case 'Cash':
                $ncash = $ncash + $rowd->bayar;
                break;
            case 'Transfer':
                $ntransfer = $ntransfer + $rowd->bayar;
                break;
            case 'Cek/Giro':
                $ncek_giro = $ncek_giro + $rowd->bayar;
                break;
            case 'Debit Card':
                $ndebit_card = $ndebit_card + $rowd->bayar;
                break;
            case 'Cek/Giro':
                $ncek_giro = $ncek_giro + $rowd->bayar;
                break;
            default:
                $ncredit_card = $ncredit_card + $rowd['credit_card'];
        }
    }
    $queryjual = DB::table('jualh')
        ->where('jualh.proses', 'Y')
        ->where('jualh.nojual', $nojual)
        ->first();
    // $tgljual = $queryjual->tgljual;
    // $tgljual = $queryjual->tglkwitansi;
    $tgljual = date('d-m-Y', strtotime($tgljual));
    $tglkwitansi = date('d-m-Y', strtotime($tglkwitansi));
    $nmcustomer = $queryjual->nmcustomer;
    $ntotal = $queryjual->total;
    $total = number_format($ntotal, 0, '.', ',');
    $cash = number_format($ncash, 0, '.', ',');
    $transfer = number_format($ntransfer, 0, '.', ',');
    $cek_giro = number_format($ncek_giro, 0, '.', ',');
    $debit_card = number_format($ndebit_card, 0, '.', ',');
    $credit_card = number_format($ncredit_card, 0, '.', ',');
    $nbayar = $ncash + $ntransfer + $ncek_giro + $ndebit_card + $ncredit_card;
    $nkurangbayar = $ntotal - $nbayar;
    $kurangbayar = number_format($nkurangbayar, 0, '.', ',');
    echo '<tr>
  <td height="10px" align="center">' .
        $no .
        '</td>
  <td>' .
        $nojual .
        '</td>
  <td style="text-align:center;">' .
        $tgljual .
        '</td>
  <td style="text-align:center;">' .
        $tglkwitansi .
        '</td>
  <td>' .
        $nmcustomer .
        '</td>
  <td align="right">' .
        $total .
        '</td>
  <td align="right">' .
        $cash .
        '</td>
  <td align="right">' .
        $transfer .
        '</td>
  <td align="right">' .
        $cek_giro .
        '</td>
  <td align="right">' .
        $debit_card .
        '</td>
  <td align="right">' .
        $credit_card .
        '</td>
  <td align="right">' .
        $kurangbayar .
        '</td>
  </tr>';
    $no++;
    $gtotal = $gtotal + $ntotal;
    $gcash = $gcash + $ncash;
    $gtransfer = $gtransfer + $ntransfer;
    $gcek_giro = $gcek_giro + $ncek_giro;
    $gdebit_card = $gdebit_card + $ndebit_card;
    $gcredit_card = $gcredit_card + $ncredit_card;
    $gkurangbayar = $gkurangbayar + $nkurangbayar;
}
$gtotal = number_format($gtotal, 0, '.', ',');
$gcash = number_format($gcash, 0, '.', ',');
$gtransfer = number_format($gtransfer, 0, '.', ',');
$gcek_giro = number_format($gcek_giro, 0, '.', ',');
$gdebit_card = number_format($gdebit_card, 0, '.', ',');
$gcredit_card = number_format($gcredit_card, 0, '.', ',');
$gkurangbayar = number_format($gkurangbayar, 0, '.', ',');
echo '<tr><td colspan="5" height="10px" align="right">' .
    'Grand Total ...' .
    '</td>
  <td height="10px" align="right">' .
    $gtotal .
    '</td>
  <td height="10px" align="right">' .
    $gcash .
    '</td>
  <td height="10px" align="right">' .
    $gtransfer .
    '</td>
  <td height="10px" align="right">' .
    $gcek_giro .
    '</td>
  <td height="10px" align="right">' .
    $gdebit_card .
    '</td>
  <td height="10px" align="right">' .
    $gcredit_card .
    '</td>
  <td height="10px" align="right">' .
    $gkurangbayar .
    '</td>
  </tr></table>';

echo '<font size="1"><left>Tanggal cetak : ' . date('d-m-Y H:i:s a') . '<br>';
