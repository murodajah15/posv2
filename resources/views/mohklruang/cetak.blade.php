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

$nomohon = $mohklruangh->nomohon;
$totalf = number_format($mohklruangh->total, 0, ',', '.');
$terbilang = '# ' . strtoupper(Terbilang::make($mohklruangh->total, ' Rupiah #'));
$html .= '<p style="margin-top:5px; margin-bottom:5px" align="center"><u>PERMOHONAN PENGELUARAN UANG</u></p>
		';
$html .=
    '<table border="0" height="5">
			<tr><td width="70" style="font-size:12px;">NO.</td><td width="280" style="font-size:13px";>: ' .
    $mohklruangh->nomohon .
    '</td>
	<tr><td width="70" style="font-size:12px;">Tanggal </td></td><td width="280" style="font-size:13px";>: ' .
    $mohklruangh->tglmohon .
    '<tr><td width="70" style="font-size:12px;">Total Bayar </td></td><td width="280" style="font-size:13px";>: Rp. ' .
    $totalf .
    ',-' .
    '</td>' .
    '</td>
	<tr><td width="70" style="font-size:12px;">Terbilang </td></td><td class="text-capitalize" width="580" style="font-size:13px";>: ' .
    $terbilang .
    '</td>
			</td><td width="180" style="font-size:11px";>' .
    '</td></tr></table>';

$html .= '<table border="0" table-layout="fixed"; cellpadding="0"; cellspacing="0"; style=font-size:11px; class="table table-striped table table-bordered;">
			<tr>
				<th width="30px" height="20" align="center"><font size="1" color="black">&nbsp;NO.&nbsp;</th>
				<th width="100px" align="left"><font size="1" color="black">&nbsp;DOKUMEN&nbsp;</th>
				<th width="230px" align="left"><font size="1" color="black">&nbsp;SUPPLIER&nbsp;</th>
				<th width="80px" align="right"><font size="1" color="black">&nbsp;BAYAR&nbsp;</th>
			</tr>';

$no = 1;
$nuang = 0;
foreach ($mohklruangd as $row) {
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
        $row['nmsupplier'] .
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
    	<td colspan="3" style="color:black" align="right">&nbsp;Total Bayar&nbsp; </td><td align="right">&nbsp;' .
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
