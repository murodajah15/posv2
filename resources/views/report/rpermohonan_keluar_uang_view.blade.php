@include('home.config')

<?php
$connect = session('connect');
$nm_perusahaan = $saplikasi->nm_perusahaan;
$alamat_perusahaan = $saplikasi->alamat;
$telp_perusahaan = $saplikasi->telp;
$no = 1;

date_default_timezone_set('Asia/Jakarta');
$tgl1 = date('Y-m-d', strtotime($tanggal1));
$tgl2 = date('Y-m-d', strtotime($tanggal2));
$no = 1;

$tanggal = $semuaperiode == 'Y' ? 'Semua Periode' : $tanggal1 . ' s/d ' . $tanggal2;
if ($outstanding == 'Y') {
    $tanggal = 'Outstanding s/d ' . $tanggal2;
    $qry = "select * from mohklruangh where proses='Y' and terima='N'";
    $qry .= $semuaperiode == 'Y' ? ' ' : "and (tglmohon>='$tgl1' and tglmohon<='$tgl2')";
    $qry .= 'order by tglmohon';
} else {
    $qry = "select * from mohklruangh where proses='Y'";
    $qry .= $semuaperiode == 'Y' ? ' ' : "and (tglmohon>='$tgl1' and tglmohon<='$tgl2')";
}

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
    <th width="30px" height="20"><font size="1" color="black">NO.</th>
    <th width="100px"><font size="1" color="black">NO. DOKUMEN</th>
    <th width="70px"><font size="1" color="black">TANGGAL</th>
    <th width="250px"><font size="1" color="black">SUPPLIER</th>
    <th width="80px"><font size="1" color="black">TOTAL</th>
   </tr>';
$grandtotal = 0;
while ($row = mysqli_fetch_assoc($queryh)) {
    echo '<tr>
   <td colspan="5" width="573px" height="35px" align="left">' .
        'No. Permohonan : ' .
        $row['nomohon'] .
        ', Tanggal : ' .
        $row['tglmohon'] .
        ', Jenis : ' .
        $row['nmjnkeluar'] .
        ', Cara Bayar : ' .
        $row['carabayar'] .
        '</td>';
    $nomohon = $row['nomohon'];
    if ($semuaperiode == 'Y') {
        $tanggal = 'Semua Periode';
        $queryd = mysqli_query($connect, "select mohklruangh.nomohon,mohklruangh.tglmohon,mohklruangh.nmjnkeluar,mohklruangd.nodokumen,mohklruangd.tgldokumen,mohklruangd.uang,mohklruangd.kdsupplier,mohklruangd.nmsupplier from mohklruangh inner join mohklruangd on mohklruangh.nomohon=mohklruangd.nomohon where mohklruangh.proses='Y' and mohklruangh.nomohon='$nomohon'");
    } else {
        $tanggal = $tanggal1 . ' s/d ' . $tanggal2;
        $queryd = mysqli_query($connect, "select mohklruangh.nomohon,mohklruangh.tglmohon,mohklruangh.nmjnkeluar,mohklruangh.nmcustomer,mohklruangd.nodokumen,mohklruangd.tgldokumen,mohklruangd.uang,mohklruangd.kdsupplier,mohklruangd.nmsupplier from mohklruangh inner join mohklruangd on mohklruangh.nomohon=mohklruangd.nomohon where mohklruangh.proses='Y' and (mohklruangh.tglmohon>='$tgl1' and mohklruangh.tglmohon<='$tgl2') and mohklruangd.nomohon='$nomohon'");
    }
    $jumsubtotal = 0;
    while ($rowd = mysqli_fetch_assoc($queryd)) {
        //$subtotal_ppn = $rowd['subtotal'] - ($rowd['subtotal'] * ($row['ppn']/100));
        $uang = number_format($rowd['uang'], 0, '.', ',');
        $supplier = $rowd['kdsupplier'] . '-' . $rowd['nmsupplier'];
        $tgldokumen = $rowd['tgldokumen'];
        echo '<tr>
     <td width="30px"  align="center">' .
            $no .
            '</td>
     <td width="100px" >' .
            $rowd['nodokumen'] .
            '</td>
     <td width="50px"  align="center">' .
            $tgldokumen .
            '</td>
     <td width="250px" >' .
            $supplier .
            '</td>
     <td width="80px"  align="right">' .
            $uang .
            '</td>
    </tr>';
        $no++;
        $jumsubtotal = $jumsubtotal + $rowd['uang'];
        $grandtotal = $grandtotal + $rowd['uang'];
    }
    $total = number_format($jumsubtotal, 0, '.', ',');
    echo '<tr><td colspan="4" height="20px" align="left">' .
        'Total' .
        '</td>
   <td height="20px" align="right">' .
        $total .
        '</td>
  </tr>';
}
$grandtotal = number_format($grandtotal, 0, '.', ',');
echo '<tr><td colspan="4" height="20px" align="left">' .
    'Grand Total' .
    '</td>
  <td height="20px" align="right">' .
    $grandtotal .
    '</td>
  </tr></table>';

echo '</table><font size="1"><left>Tanggal cetak : ' . date('d-m-Y H:i:s a');
