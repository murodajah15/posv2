@include('home.config')

<style>
    @page {
        size: 10cm 20cm landscape;
    }
</style>

<?php
$connect = session('connect');
$nm_perusahaan = session('nm_perusahaan');
$alamat_perusahaan = session('alamat');
$telp_perusahaan = session('telp');

$html = '';

?>
@include('home.logo')
<?php
$html .= session('tampillogo');

$nokwitansi = $kasir_tagihan->nokwitansi;
$bayarf = number_format($kasir_tagihan->total, 0, ',', '.');
$terbilang = ucwords(Terbilang::make($kasir_tagihan->total));

$html .=
    '<br><center>TANDA TERIMA PENERIMAAN TAGIHAN</center>
		<hr style="width:99%;height:-1px;text-align:left;margin-left:0">
		<table border="0" height="5">
			<tr><td width="80" style="font-size:12px;">NO.</td><td width="180" style="font-size:12px";>: ' .
    "$kasir_tagihan->nokwitansi" .
    '</td>
			<tr><td width="100" style="font-size:12px;">Tanggal </td></td><td width="350" style="font-size:12px";>: ' .
    "$kasir_tagihan->tglkwitansi" .
    '</td>
			<tr><td width="100" style="font-size:12px;">No. Penjualan </td></td><td width="350" style="font-size:12px";>: ' .
    "$kasir_tagihan->nojual" .
    '</td>
			<tr><td width="100" style="font-size:12px;">Customer </td></td><td width="380" style="font-size:12px";>: ' .
    "$kasir_tagihan->nmcustomer" .
    '</td>
			<tr><td width="100" style="font-size:12px;">Cara Bayar </td></td><td width="350" style="font-size:12px";>: ' .
    "$kasir_tagihan->carabayar" .
    '</td>
			<tr><td width="100" style="font-size:12px;">Jenis Kartu </td></td><td width="350" style="font-size:12px";>: ' .
    "$kasir_tagihan->nmjnskartu" .
    '</td>
			<tr><td width="100" style="font-size:12px;">No. Rekening </td></td><td width="350" style="font-size:12px";>: ' .
    "$kasir_tagihan->norek" .
    '</td>
			<tr><td width="100" style="font-size:12px;">No. Cek/Giro </td></td><td width="350" style="font-size:12px";>: ' .
    "$kasir_tagihan->nocekgiro" .
    '</td>
		</table>
		</font>';
$nbayar = number_format($kasir_tagihan->total, 0, ',', '.');
$html .= '<font size="2">Uang yang diterima : <b>Rp. ' . $nbayar . ',-</b><br>';
$html .= '<font size="2">Terbilang : <i># ' . $terbilang . ' Rupiah #</i></font>';

$queryd = mysqli_query($connect, "select kasir_tagihand.*,jualh.tgljual from kasir_tagihand inner join jualh on jualh.nojual=kasir_tagihand.nojual where kasir_tagihand.nokwitansi='$nokwitansi'");
$jumrecord = mysqli_num_rows($queryd);
$no = 1;
$html .= '	<table table-layout="fixed"; cellpadding="0"; cellspacing="0"; style=font-size:11px; class="table table-striped table table-bordered;">
			<tr>
			<th width="30px" height="15" align="center"><font size="1" color="black">NO.</th>
			<th width="90px" height="20" align="left"><font size="1" color="black">NO. PENJUALAN</th>
			<th width="60px" height="20" align="center"><font size="1" color="black">TANGGAL</th>
			<th width="70px" align="right"><font size="1" color="black">BAYAR&nbsp;</th></tr>';
while ($k = mysqli_fetch_assoc($queryd)) {
    $bayar = number_format($k['bayar'], 0, ',', '.');
    $tgl = date('d-m-Y', strtotime($k['tgljual']));
    $html .=
        '<tr><td align="center">' .
        $no .
        '</td>
				<td align="left">&nbsp;' .
        $k['nojual'] .
        '</td>
				<td align="center">&nbsp;' .
        $tgl .
        '</td>
				<td align="right">' .
        $bayar .
        '&nbsp;</td>
			</tr>';
    $no++;
}
$html .= '</table><br>';

$html .=
    '<table border="1" table-layout="fixed"; cellpadding="1"; cellspacing="1"; style=font-size:11px; class="table table-striped table table-bordered;">
			<tr>
				<th width="390px" height="20"><font size="1" color="black">KETERANGAN :</th>
				<th width="150px"><font size="1" color="black">PEMBAYAR</th>
				<th width="150px"><font size="1" color="black">KASIR</th>
				<tr><td height="80" align="center">' .
    $kasir_tagihan->keterangan .
    '</td><td></td><td></td>
				<tr><td height="20"></td><td></td><td align="center">' .
    session('username') .
    '</td>
			</tr></table>';
$html .= '<font size="1" align="right" width="300">Tanggal cetak : ' . date('d-m-Y H:i:s a') . '</font>';

echo $html;
?>

<script>
    function terbilang($nilai) {
        if ($nilai < 0) {
            $hasil = "minus ".trim(penyebut($nilai));
        } else {
            $hasil = trim(penyebut($nilai));
        }
        return $hasil;
    }

    function penyebut($nilai) {
        $nilai = abs($nilai);
        $huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh",
            "sebelas");
        $temp = "";
        if ($nilai < 12) {
            $temp = " ".$huruf[$nilai];
        } else if ($nilai < 20) {
            $temp = penyebut($nilai - 10).
            " belas";
        } else if ($nilai < 100) {
            $temp = penyebut($nilai / 10).
            " puluh".penyebut($nilai % 10);
        } else if ($nilai < 200) {
            $temp = " seratus".penyebut($nilai - 100);
        } else if ($nilai < 1000) {
            $temp = penyebut($nilai / 100).
            " ratus".penyebut($nilai % 100);
        } else if ($nilai < 2000) {
            $temp = " seribu".penyebut($nilai - 1000);
        } else if ($nilai < 1000000) {
            $temp = penyebut($nilai / 1000).
            " ribu".penyebut($nilai % 1000);
        } else if ($nilai < 1000000000) {
            $temp = penyebut($nilai / 1000000).
            " juta".penyebut($nilai % 1000000);
        } else if ($nilai < 1000000000000) {
            $temp = penyebut($nilai / 1000000000).
            " milyar".penyebut(fmod($nilai, 1000000000));
        } else if ($nilai < 1000000000000000) {
            $temp = penyebut($nilai / 1000000000000).
            " trilyun".penyebut(fmod($nilai, 1000000000000));
        }
        return $temp;
    }
</script>
