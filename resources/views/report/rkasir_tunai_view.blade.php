@include('home.config')

<?php
$connect = session('connect');
$nm_perusahaan = $saplikasi->nm_perusahaan;
$alamat_perusahaan = $saplikasi->alamat;
$telp_perusahaan = $saplikasi->telp;

date_default_timezone_set('Asia/Jakarta');
$tgl1 = date('Y-m-d', strtotime($tanggal1));
$tgl2 = date('Y-m-d', strtotime($tanggal2));
$no = 1;

$tanggal = $semuaperiode == 'Y' ? 'Semua Periode' : $tanggal1 . ' s/d ' . $tanggal2;
$qry = "select nokwitansi,tglkwitansi,nojual,nmcustomer,piutang,bayar,if(carabayar='Cash',bayar,0) as cash,";
$qry .= "if(carabayar='Transfer',bayar,0) as transfer, if(carabayar='Cek/Giro',bayar,0) as cek_giro,";
$qry .= "if(carabayar='Debit Card',bayar,0) as debit_card, if(carabayar='Credit Card',bayar,0) as credit_card,";
$qry .= "piutang-bayar as sisa_piutang from kasir_tunai where proses='Y'";
$qry .= $semuaperiode == 'Y' ? ' ' : " and (kasir_tunai.tglkwitansi>='$tgl1' and kasir_tunai.tglkwitansi<='$tgl2')";
$qry .= $nmkasir != '' ? ' ' : "and user_input='$nmkasir'";
$qry .= 'order by tglkwitansi';
$queryh = mysqli_query($connect, $qry);

$cek = mysqli_num_rows($queryh);
if (empty($cek)) {
    echo '<script>
        alert(\'Tidak Ada sesuai kriteria\')
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
<th width="90px" rowspan="2"><font size="1" color="black">NO. KWITANSI</th>
<th width="65px" rowspan="2"><font size="1" color="black">TANGGAL</th>
<th width="95px" rowspan="2"><font size="1" color="black">NO. DOKUMEN</th>
<th width="200px" rowspan="2"><font size="1" color="black">CUSTOMER</th>
<th colspan="5">CARA BAYAR</th>
<th width="80px" rowspan="2">TOTAL</th>
<tr><th width="70px">TUNAI</th>
<th width="70px">TRANSFER</th>
<th width="70px">CEK/GIRO</th>
<th width="70px">DEBIT<br>CARD</th>
<th width="70px">CREDIT<br>CARD</th>
</tr>';
$totalcash = 0;
$totaltransfer = 0;
$totalcek_giro = 0;
$totaldebit_card = 0;
$totalcredit_card = 0;
$totalsisa_piutang = 0;
$totalpiutang = 0;
$totalbayar = 0;
while ($row = mysqli_fetch_assoc($queryh)) {
    //$subtotal_ppn = $rowd['subtotal'] - ($rowd['subtotal'] * ($row['ppn']/100));
    $piutang = number_format($row['piutang'], 0, '.', ',');
    $cash = number_format($row['cash'], 0, '.', ',');
    $transfer = number_format($row['transfer'], 0, '.', ',');
    $cek_giro = number_format($row['cek_giro'], 0, '.', ',');
    $debit_card = number_format($row['debit_card'], 0, '.', ',');
    $credit_card = number_format($row['credit_card'], 0, '.', ',');
    $sisa_piutang = number_format($row['sisa_piutang'], 0, '.', ',');
    $bayar = number_format($row['bayar'], 0, '.', ',');
    $tglkwitansi = $row['tglkwitansi'];
    echo '<tr>
<td width="30px"  align="center">' .
        $no .
        '</td>
<td >' .
        $row['nokwitansi'] .
        '</td>
<td >' .
        $tglkwitansi .
        '</td>
<td >' .
        $row['nojual'] .
        '</td>
<td >' .
        $row['nmcustomer'] .
        '</td>
<td  align="right">' .
        $cash .
        '</td>
<td  align="right">' .
        $transfer .
        '</td>
<td  align="right">' .
        $cek_giro .
        '</td>
<td  align="right">' .
        $debit_card .
        '</td>
<td  align="right">' .
        $credit_card .
        '</td>
<td  align="right">' .
        $bayar .
        '</td>
</tr>';
    $no++;
    $totalcash = $totalcash + $row['cash'];
    $totaltransfer = $totaltransfer + $row['transfer'];
    $totalcek_giro = $totalcek_giro + $row['cek_giro'];
    $totaldebit_card = $totaldebit_card + $row['debit_card'];
    $totalcredit_card = $totalcredit_card + $row['credit_card'];
    $totalsisa_piutang = $totalsisa_piutang + $row['sisa_piutang'];
    $totalpiutang = $totalpiutang + $row['piutang'];
    $totalbayar = $totalbayar + $row['bayar'];
}
$totalpiutang = number_format($totalpiutang, 0, '.', ',');
$totalcash = number_format($totalcash, 0, '.', ',');
$totaltransfer = number_format($totaltransfer, 0, '.', ',');
$totalcek_giro = number_format($totalcek_giro, 0, '.', ',');
$totaldebit_card = number_format($totaldebit_card, 0, '.', ',');
$totalcredit_card = number_format($totalcredit_card, 0, '.', ',');
$totalsisa_piutang = number_format($totalsisa_piutang, 0, '.', ',');
$totalbayar = number_format($totalbayar, 0, '.', ',');
echo '<tr><td colspan="5" height="20px" align="left">' .
    'Total' .
    '</td>
<td height="20px" align="right">' .
    $totalcash .
    '</td>
<td height="20px" align="right">' .
    $totaltransfer .
    '</td>
<td height="20px" align="right">' .
    $totalcek_giro .
    '</td>
<td height="20px" align="right">' .
    $totaldebit_card .
    '</td>
<td height="20px" align="right">' .
    $totalcredit_card .
    '</td>
<td height="20px" align="right">' .
    $totalbayar .
    '</td>
</tr>';
echo '</table><font size="1"><left>Tanggal cetak : ' . date('d-m-Y H:i:s a');
echo '<br><br><table border="0">
<tr>
<th width="30px" height="50" valign="top"><font size="1" color="black">KASIR</th>
<tr><td height="20px" width="150" align="center">( ' .
    $nmkasir .
    ')</td>
</tr></table>';
