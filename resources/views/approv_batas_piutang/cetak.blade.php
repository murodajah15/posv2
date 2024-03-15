<style>
    @page {
        size: 10cm 20cm landscape;
    }
</style>

<?php
$noapprov = $approv_batas_piutang->noapprov;
$totalf = number_format($approv_batas_piutang->total, 0, ',', '.');
$terbilang = '# ' . strtoupper(Terbilang::make($approv_batas_piutang->total, ' Rupiah #'));
$html = '';
$html .= '<p style="margin-top:5px; margin-bottom:5px" align="center"><u>NOTA APPROVAL BATAS PIUTANG</u></p>
		';
$html .=
    '<table border="0" height="5">
			<tr><td width="30" style="font-size:12px;">NO.</td><td width="280" style="font-size:13px";>: ' .
    $approv_batas_piutang->noapprov .
    '</td>
			<tr><td width="30" style="font-size:12px;">Tanggal </td></td><td width="280" style="font-size:13px";>: ' .
    $approv_batas_piutang->tglapprov .
    '</td>
			<tr><td width="30" style="font-size:12px;">Penjualan </td></td><td width="280" style="font-size:13px";>: ' .
    $approv_batas_piutang->nojual .
    '</td>
			<tr><td width="30" style="font-size:12px;">Tanggal </td></td><td width="280" style="font-size:13px";>: ' .
    $approv_batas_piutang->tgljual .
    '</td>
			<tr><td width="30" style="font-size:12px;">Customer </td></td><td width="280" style="font-size:13px";>: ' .
    $approv_batas_piutang->nmcustomer .
    '</td>
			<tr><td width="30" style="font-size:12px;">Total </td></td><td width="280" style="font-size:13px";>: Rp. ' .
    $totalf .
    ',-' .
    '</td>' .
    '</td>
			<tr><td width="30" style="font-size:12px;">Terbilang </td></td><td class="text-capitalize" width="580" style="font-size:13px";>: ' .
    $terbilang .
    '</td>
			</td><td width="180" style="font-size:11px";>' .
    '</td></tr></table>';

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
