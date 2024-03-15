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

if ($semuaperiode != 'Y') {
    $tanggal = $tanggal1 . ' s/d ' . $tanggal2;
} else {
    $tanggal = 'Semua Periode';
}

?>
@include('report.judulreport')
<?php
echo 'Tanggal : ' . $tanggal . '</font>';

echo '<table table-layout="fixed"; cellpadding="2"; cellspacing="0"; style=font-size:11px; class="table table-striped table table-bordered;">
  <tr>
   <th width="30px" height="20"><font size="1" color="black"><b>NO.</th>
   <th width="120px"><font size="1" color="black"><b>KODE BARANG</th>
   <th width="400px"><font size="1" color="black"><b>NAMA BARANG</th>
   <th width="60px"><font size="1" color="black"><b>QTY</th>
   <th width="100px"><font size="1" color="black"><b>HARGA</th>
   <th width="60px"><font size="1" color="black"><b>STOCK<br>AKHIR</th>
  </tr>';

$no = 1;
$jumqty = 0;
$jumsubtotal = 0;
$jumstock = 0;
$qh = "select jualh.nojual,juald.kdbarang,juald.nmbarang,sum(juald.qty) as qty,sum(juald.subtotal) as subtotal,tbbarang.stock from jualh inner join juald on juald.nojual=jualh.nojual inner join tbbarang on tbbarang.kode=juald.kdbarang where jualh.proses='Y'";
$qh .= $semuaperiode == 'Y' ? ' ' : " and (jualh.tgljual>='$tgl1' and jualh.tgljual<='$tgl2')";
$qh .= ' group by kdbarang order by qty';
$qh .= $urutqtydesc == 'Y' ? ' desc' : ' asc';
$queryh = mysqli_query($connect, $qh);
while ($row = mysqli_fetch_assoc($queryh)) {
    $jumqty = $jumqty + $row['qty'];
    $jumsubtotal = $jumsubtotal + $row['subtotal'];
    $jumstock = $jumstock + $row['stock'];
    $qtyf = number_format($row['qty'], 0, '.', ',');
    $subtotalf = number_format($row['subtotal'], 0, '.', ',');
    $stockf = number_format($row['stock'], 0, '.', ',');
    echo '<tr>
  <td style="text-align:center;">' .
        $no .
        '</td>
  <td>' .
        $row['kdbarang'] .
        '</td>
  <td>' .
        $row['nmbarang'] .
        '</td>
  <td style="text-align:right;">' .
        $qtyf .
        '</td>
  <td style="text-align:right;">' .
        $subtotalf .
        '</td>
  <td style="text-align:right;">' .
        $stockf .
        '</td>
  </tr>';
    $no++;
    // $nojual = $row['nojual'];
    // $queryd = mysqli_query($connect, "select * from juald where nojual='$nojual'");
    // while ($detail = mysqli_fetch_assoc($queryd)) {
    // }
}
$jumqtyf = number_format($jumqty, 0, '.', ',');
$jumsubtotalf = number_format($jumsubtotal, 0, '.', ',');
$jumstockf = number_format($jumstock, 0, '.', ',');
echo '<tr>
  <td></td>
  <td colspan=2 style="text-align:left;"><b> Total </b></td>
  <td style="text-align:right;"><b>' .
    $jumqtyf .
    '</td>
  <td style="text-align:right;"><b>' .
    $jumsubtotalf .
    '</td>
  <td style="text-align:right;"><b>' .
    $jumstockf .
    '</td>';
echo '</table>';

echo '<font size="1"><left>Tanggal cetak : ' . date('d-m-Y H:i:s a') . '<br>';
