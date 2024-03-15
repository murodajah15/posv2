@include('home.config')

<?php
$connect = session('connect');
$nm_perusahaan = $saplikasi->nm_perusahaan;
$alamat_perusahaan = $saplikasi->alamat;
$telp_perusahaan = $saplikasi->telp;
$no = 1;

date_default_timezone_set('Asia/Jakarta');
$bulan = $bulan;
$tahun = $tahun;
$periode = $tahun . substr('0' . $bulan, -2);
$tanggal = 'Bulan : ' . $bulan . ', Tahun : ' . $tahun;

if ($semuabarang != 'Y') {
    //Rekapitulasi perbarang
    $nmlaporan = 'LAPORAN HPP PERBARANG ' . $kdbarang . ' (' . $nmbarang . ')';
    $text = "select stock_barang.*,tbbarang.nama as nmbarang from stock_barang inner join tbbarang on stock_barang.kdbarang=tbbarang.kode where kdbarang='$kdbarang' and periode='$periode' order by kode";
} else {
    //Rekapitulasi semua barang
    $nmlaporan = 'LAPORAN HPP BARANG (SEMUA BARANG)';
    $text = "select stock_barang.*,tbbarang.nama as nmbarang from stock_barang inner join tbbarang on stock_barang.kdbarang=tbbarang.kode where periode='$periode' order by kdbarang";
}
$queryh = mysqli_query($connect, $text);

?>
@include('report.judulreport')
<?php
echo 'Tanggal : ' . $tanggal . '</font>';

echo '<table table-layout="fixed"; cellpadding="2"; cellspacing="0"; style=font-size:11px; class="table table-striped table table-bordered;">
  <tr>
  <th width="40px" height="20"><font size="1" color="black">NO.</th>
  <th width="150px" ><font size="1" color="black">KODE BARANG</th>
  <th width="450px"><font size="1" color="black">NAMA BARANG</th>
  <th width="60px"><font size="1" color="black">STOCK<br>AWAL</th>
  <th width="60px"><font size="1" color="black">MASUK</th>
  <th width="60px"><font size="1" color="black">KELUAR</th>
  <th width="60px"><font size="1" color="black">STOCK<br>AKHIR</th>
  <th width="120px"><font size="1" color="black">NILAI AWAL</th>
  <th width="120px"><font size="1" color="black">NILAI AKHIR</th>';
$jumnilai_awal = 0;
$jumnilai_akhir = 0;
$jumnilai_awalf = 0;
$jumnilai_akhirf = 0;
while ($row = mysqli_fetch_assoc($queryh)) {
    $kdbarang = $row['kdbarang'];
    $nmbarang = $row['nmbarang'];
    $barang = $row['kdbarang'] . ' (' . $row['nmbarang'] . ')';
    $stockawalf = number_format($row['awal'], 2, '.', ',');
    $stockmasukf = number_format($row['masuk'], 2, '.', ',');
    $stockkeluarf = number_format($row['keluar'], 2, '.', ',');
    $stockakhirf = number_format($row['akhir'], 2, '.', ',');
    $nilai_awalf = number_format($row['nilai_awal'], 2, '.', ',');
    $nilai_akhirf = number_format($row['nilai_akhir'], 2, '.', ',');
    echo '<tr>
    <td width="30px" height="20px" align="center">' .
        $no .
        '</td>
    <td width="80px" height="20px">' .
        $kdbarang .
        '</td>
    <td width="80px" height="20px">' .
        $nmbarang .
        '</td>
    <td height="20px" align="right">' .
        $stockawalf .
        '</td>
    <td height="20px" align="right">' .
        $stockmasukf .
        '</td>
    <td height="20px" align="right">' .
        $stockkeluarf .
        '</td>
    <td height="20px" align="right">' .
        $stockakhirf .
        '</td>
    <td height="20px" align="right">' .
        $nilai_awalf .
        '</td>
    <td height="20px" align="right">' .
        $nilai_akhirf .
        '</td>
    </tr>';

    $no++;
    $jumnilai_awal = $jumnilai_awal + $row['nilai_awal'];
    $jumnilai_akhir = $jumnilai_akhir + $row['nilai_akhir'];
    $jumnilai_awalf = number_format($jumnilai_awal, 2, '.', ',');
    $jumnilai_akhirf = number_format($jumnilai_akhir, 2, '.', ',');
}
echo '<tr><td colspan="7" height="20px" align="right">' .
    'Total' .
    '</td>
  <td height="20px" align="right">' .
    $jumnilai_awalf .
    '</td>
  <td height="20px" align="right">' .
    $jumnilai_akhirf .
    '</td>
  </tr>';

echo '</table><font size="1"><left>Tanggal cetak : ' . date('d-m-Y H:i:s a');
'';
