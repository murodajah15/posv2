<style>
    @page {
        size: 10cm 20cm landscape;
    }
</style>

<?php
$username = session('username');
$noopname = $opnameh->noopname;
$html = '';
$html .= '<p style="margin-top:5px; margin-bottom:5px" align="center"><u>DAFTAR STOCK OPNAME</u></p>
		';
$html .=
    '<table border="0" height="5">
			<tr><td width="30" style="font-size:12px;">NO.</td><td width="280" style="font-size:13px";>: ' .
    $opnameh->noopname .
    '</td>
	<tr><td width="30" style="font-size:12px;">Tanggal </td></td><td width="280" style="font-size:13px";>: ' .
    $opnameh->tglopname .
    '</td>
			</td><td width="180" style="font-size:11px";>' .
    '</td></tr>
		</table>
		<table border="0" table-layout="fixed"; cellpadding="0"; cellspacing="0"; style=font-size:11px; class="table table-striped table table-bordered;">
			<tr>
				<th width="30px" height="20" align="center"><font size="1" color="black">&nbsp;NO.&nbsp;</th>
				<th width="100px" align="left"><font size="1" color="black">&nbsp;KODE BARANG&nbsp;</th>
				<th width="230px" align="left"><font size="1" color="black">&nbsp;NAMA BARANG&nbsp;</th>
				<th width="50px"  align="center"><font size="1" color="black">&nbsp;LOKASI&nbsp;</th>
				<th width="50px"  align="right"><font size="1" color="black">&nbsp;QTY&nbsp;</th>
			</tr>';

$no = 1;
$nsubtotal = 0;
foreach ($opnamed as $row) {
    $qty = number_format($row['qty'], 2, ',', '.');
    $html .=
        '<tr><td width="30px" align="center">' .
        $no .
        '</td>
      <td width="30px" align="left">' .
        '&nbsp;' .
        $row['kdbarang'] .
        '</td>
					<td width="50px" align="left">' .
        '&nbsp;' .
        $row['nmbarang'] .
        '</td>
					<td width="40px" align="center">' .
        $row['lokasi'] .
        '</td>
					<td width="50px" align="right">' .
        $qty .
        '&nbsp;' .
        '</td>
					<td width="70px" align="right">
        </tr>';
    $no++;
}
$html .= '</table><hr>';
$html .= '<font size="1"><left>Cetak oleh : ' . $username . ', ' . date('d-m-Y H:i:s a') . '<br>';

echo $html;
?>
