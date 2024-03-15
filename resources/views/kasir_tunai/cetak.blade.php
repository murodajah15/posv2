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

$nokwitansi = $kasir_tunai->nokwitansi;
$bayarf = number_format($kasir_tunai->bayar, 0, ',', '.');
$terbilang = ucwords(Terbilang::make($kasir_tunai->bayar));
// $html .= '<p style="margin-top:5px; margin-bottom:5px" align="center"><u>KWITANSI TUNAI</u></p>
// 		';
// $html .=
//     '<table border="0" height="5">
// 			<tr><td width="30" style="font-size:12px;">NO.</td><td width="280" style="font-size:13px";>: ' .
//     $kasir_tunai->nokwitansi .
//     '</td>
// 			<tr><td width="30" style="font-size:12px;">Tanggal </td></td><td width="280" style="font-size:13px";>: ' .
//     $kasir_tunai->tglkwitansi .
//     '</td>
// 			<tr><td width="30" style="font-size:12px;">Penjualan </td></td><td width="280" style="font-size:13px";>: ' .
//     $kasir_tunai->nojual .
//     '</td>
// 	<tr><td width="30" style="font-size:12px;">Customer </td></td><td width="280" style="font-size:13px";>: ' .
//     $kasir_tunai->nmcustomer .
//     '</td>
// 			<tr><td width="30" style="font-size:12px;">Bayar </td></td><td width="280" style="font-size:13px";>: Rp. ' .
//     $bayarf .
//     ',-' .
//     '</td>' .
//     '</td>
// 			<tr><td width="30" style="font-size:12px;">Terbilang </td></td><td class="text-capitalize" width="580" style="font-size:13px";>: ' .
//     $terbilang .
//     '</td>
// 			</td><td width="180" style="font-size:11px";>' .
//     '</td></tr></table>';

$html .=
    '<br><center>TANDA TERIMA PEMBAYARAN</center>
		<hr style="width:99%;height:-1px;text-align:left;margin-left:0">
		<table border="0" height="5">
			<tr><td width="80" style="font-size:12px;">NO.</td><td width="180" style="font-size:12px";>: ' .
    "$kasir_tunai->nokwitansi" .
    '</td>
			<tr><td width="100" style="font-size:12px;">Tanggal </td></td><td width="350" style="font-size:12px";>: ' .
    "$kasir_tunai->tglkwitansi" .
    '</td>
			<tr><td width="100" style="font-size:12px;">No. Penjualan </td></td><td width="350" style="font-size:12px";>: ' .
    "$kasir_tunai->nojual" .
    '</td>
			<tr><td width="100" style="font-size:12px;">Customer </td></td><td width="380" style="font-size:12px";>: ' .
    "$kasir_tunai->nmcustomer" .
    '</td>
			<tr><td width="100" style="font-size:12px;">Cara Bayar </td></td><td width="350" style="font-size:12px";>: ' .
    "$kasir_tunai->carabayar" .
    '</td>
			<tr><td width="100" style="font-size:12px;">Jenis Kartu </td></td><td width="350" style="font-size:12px";>: ' .
    "$kasir_tunai->nmjnskartu" .
    '</td>
			<tr><td width="100" style="font-size:12px;">No. Rekening </td></td><td width="350" style="font-size:12px";>: ' .
    "$kasir_tunai->norek" .
    '</td>
			<tr><td width="100" style="font-size:12px;">No. Cek/Giro </td></td><td width="350" style="font-size:12px";>: ' .
    "$kasir_tunai->nocekgiro" .
    '</td>
		</table>
		</font>';
$nbayar = number_format($kasir_tunai->bayar, 0, ',', '.');
$html .= '<font size="2">Uang yang diterima : <b>Rp. ' . $nbayar . ',-</b><br>';
$html .= '<font size="2">Terbilang : <i># ' . $terbilang . ' Rupiah #</i></font>';
$html .=
    '<table border="1" table-layout="fixed"; cellpadding="1"; cellspacing="1"; style=font-size:11px; class="table table-striped table table-bordered;">
			<tr>
				<th width="390px" height="20"><font size="1" color="black">KETERANGAN :</th>
				<th width="150px"><font size="1" color="black">PEMBAYAR</th>
				<th width="150px"><font size="1" color="black">KASIR</th>
				<tr><td height="80" align="center">' .
    $kasir_tunai->keterangan .
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
