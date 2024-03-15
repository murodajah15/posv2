<style>
    @page {
        size: 10cm 20cm landscape;
    }
</style>

<?php
$html = '';
?>
@include('home.logo')
<?php
$html .= session('tampillogo');

$nokwitansi = $kasir_keluar->nokwitansi;
$totalf = number_format($kasir_keluar->total, 0, ',', '.');
$terbilang = '# <i>' . ucwords(Terbilang::make($kasir_keluar->total, ' Rupiah #')) . '</i>';
$html .= '<p style="margin-top:5px; margin-bottom:5px" align="center"><u>BUKTI PENGELUARAN UANG</u></p>
		';
$html .=
    '<table border="0" height="5">
			<tr><td width="70" style="font-size:12px;">NO.</td><td width="280" style="font-size:13px";>: ' .
    $kasir_keluar->nokwitansi .
    '</td>
	<tr><td width="70" style="font-size:12px;">Tanggal </td></td><td width="280" style="font-size:13px";>: ' .
    $kasir_keluar->tglkwitansi .
    '</td>
	<tr><td width="70" style="font-size:12px;">Jenis </td></td><td width="280" style="font-size:13px";>: ' .
    $kasir_keluar->nmjnkeluar .
    '</td>
	<tr><td width="70" style="font-size:12px;">Cara Bayar </td></td><td width="280" style="font-size:13px";>: ' .
    $kasir_keluar->carabayar .
    '</td>
	<tr><td width="70" style="font-size:12px;">Total Bayar </td></td><td width="280" style="font-size:13px";>: Rp. ' .
    '<b>' .
    $totalf .
    '</b>' .
    ',-' .
    '</td>
	<tr><td width="70" style="font-size:12px;">Terbilang </td></td><td class="text-capitalize" width="750" style="font-size:13px";>: ' .
    $terbilang .
    '</td>
			</td><td width="180" style="font-size:11px";>' .
    '</td></tr></table>';

$html .= '<table table-layout="fixed"; cellpadding="0"; cellspacing="0"; style=font-size:11px; class="table table-striped table table-bordered;">
		<tr><td width="40px" height="20" style="font-size:10px;font-family: Sans-serif;text-align:center;" color="black">NO. </td>
			<td width="90px" style="font-size:10px;font-family: Sans-serif;" color="black">Dokumen</td>
			<td width="65px" style="font-size:10px;font-family: Sans-serif;" color="black">Tanggal</td>
			<td width="250px" style="font-size:10px;font-family: Sans-serif;" color="black">Supplier/Customer</td>
			<td width="160px" style="font-size:10px;font-family: Sans-serif;" color="black">Keterangan</td>
			<td width="75px" style="font-size:10px;font-family: Sans-serif; text-align:right;" color="black">Jumlah&nbsp;</td></tr>';

$no = 1;
$nuang = 0;
foreach ($kasir_keluard as $row) {
    $uang = number_format($row['uang'], 0, ',', '.');
    $html .=
        '<tr><td width="30px" align="center">' .
        $no .
        '</td>
        <td width="30px" align="left">' .
        '&nbsp;' .
        $row['nodokumen'] .
        '</td>
	    <td width="50px" align="left">' .
        '&nbsp;' .
        $row['tgldokumen'] .
        '</td>
        <td width="250px" align="left">' .
        '&nbsp;' .
        $row['kdsupplier'] .
        '-' .
        $row['nmsupplier'] .
        '</td>
        <td width="165px" align="left">' .
        '&nbsp;' .
        $row['keterangan'] .
        '</td>
		<td width="40px" align="right">' .
        $uang .
        '&nbsp;' .
        '</td>
		</tr>';
    $no++;
    $nuang = $nuang + $row['uang'];
}
$uang = number_format($nuang, 0, ',', '.');
$html .=
    '<tr><tr>
    	<td colspan="5" style="color:black" align="right">&nbsp;Total Bayar&nbsp; </td><td align="right">&nbsp;' .
    $uang .
    '&nbsp;</td></tr>
		</table>';
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
