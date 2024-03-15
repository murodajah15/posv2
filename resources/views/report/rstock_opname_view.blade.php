@include('home.config')

<?php
$connect = session('connect');
$nm_perusahaan = $saplikasi->nm_perusahaan;
$alamat_perusahaan = $saplikasi->alamat;
$telp_perusahaan = $saplikasi->telp;
$no = 1;
?>
@include('report.judulreport')
<?php
$noopname = $noopname;
if ($noopname == '') {
    $queryh = mysqli_query($connect, 'select * from opnameh');
} else {
    $queryh = mysqli_query($connect, "select * from opnameh where noopname='$noopname'");
}

echo '<table table-layout="fixed"; cellpadding="2"; cellspacing="0"; style=font-size:11px; class="table table-striped table table-bordered;">
<tr>
<th width="30px" height="20"><font size="1" color="black">NO.</th>
<th width="90px"><font size="1" color="black">KODE BARANG</th>
<th width="300px"><font size="1" color="black">NAMA BARANG</th>
<th width="95px"><font size="1" color="black">LOKASI</th>
<th width="80px"><font size="1" color="black">QTY</th>
</tr>';
while ($row = mysqli_fetch_assoc($queryh)) {
    $jumqty = 0;
    $noopname = $row['noopname'];
    $tglopname = date('d-m-Y', strtotime($row['tglopname']));
    $pelaksana = strip_tags($row['pelaksana']);
    $keterangan = strip_tags($row['keterangan']);
    echo '<tr><td width="30px" height="20px" align="left" colspan="5">' . $noopname . ', ' . $tglopname . ', ' . $pelaksana . ', ' . $keterangan . '</td></tr>';
    $queryd = mysqli_query($connect, "select * from opnamed where noopname='$noopname'");
    while ($rowd = mysqli_fetch_assoc($queryd)) {
        $qty = number_format($rowd['qty'], 0, '.', ',');
        $jumqty = $jumqty + $rowd['qty'];
        echo '<tr>
<td width="30px"  align="center">' .
            $no .
            '</td>
<td >' .
            $rowd['kdbarang'] .
            '</td>
<td >' .
            $rowd['nmbarang'] .
            '</td>
<td >' .
            $rowd['lokasi'] .
            '</td>
<td  align="right">' .
            $qty .
            '</td>
</tr>';
        $no++;
    }
    $jumqty = number_format($jumqty, 0, '.', ',');
    echo '<tr>
<td width="30px" height="20px" colspan="4">' .
        'Total' .
        '</td>
<td width="30px" height="20px" align="right">' .
        $jumqty .
        '</td></tr></table>';
}

echo '<font size="1"><left>Tanggal cetak : ' . date('d-m-Y H:i:s a') . '<br>';
