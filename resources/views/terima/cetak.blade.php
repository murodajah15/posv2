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

$noterima = $terimah->noterima;
$ppn = $terimah->ppn;
$biaya_lain = $terimah->biaya_lain;
$total = $terimah->total;
$html .= '<p style="margin-top:5px; margin-bottom:5px" align="center"><u>NOTA PENERIMAAN BARANG</u></p>
		';
$html .=
    '<table border="0" height="5">
			<tr><td width="30" style="font-size:12px;">NO.</td><td width="280" style="font-size:13px";>: ' .
    $terimah->noterima .
    '</td>
	<tr><td width="30" style="font-size:12px;">Tanggal </td></td><td width="280" style="font-size:13px";>: ' .
    $terimah->tglterima .
    '</td>
    <tr><td width="30" style="font-size:12px;">Referensi </td></td><td width="280" style="font-size:13px";>: ' .
    $terimah->noreferensi .
    '</td>
			</td><td width="180" style="font-size:11px";>' .
    '</td></tr>
		</table>
		<table border="0" table-layout="fixed"; cellpadding="0"; cellspacing="0"; style=font-size:11px; class="table table-striped table table-bordered;">
			<tr>
				<th width="30px" height="20" align="center"><font size="1" color="black">&nbsp;NO.&nbsp;</th>
				<th width="100px" align="left"><font size="1" color="black">&nbsp;KODE BARANG&nbsp;</th>
				<th width="230px" align="left"><font size="1" color="black">&nbsp;NAMA BARANG&nbsp;</th>
				<th width="50px"  align="center"><font size="1" color="black">&nbsp;SATUAN&nbsp;</th>
				<th width="50px"  align="right"><font size="1" color="black">&nbsp;QTY&nbsp;</th>
				<th width="60px" align="right"><font size="1" color="black">&nbsp;HARGA&nbsp;</th>
				<th width="50px" align="right"><font size="1" color="black">&nbsp;DISC.(%)&nbsp;</th>
				<th width="80px" align="right"><font size="1" color="black">&nbsp;SUBTOTAL&nbsp;</th>
			</tr>';

$no = 1;
$nsubtotal = 0;
foreach ($terimad as $row) {
    $qty = number_format($row['qty'], 2, ',', '.');
    $harga = number_format($row['harga'], 0, ',', '.');
    $discount = number_format($row['discount'], 2, ',', '.');
    $subtotal = number_format($row['subtotal'], 0, ',', '.');
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
        $row['nama'] .
        '</td>
					<td width="50px" align="right">' .
        $qty .
        '&nbsp;' .
        '</td>
					<td width="70px" align="right">' .
        $harga .
        '&nbsp;' .
        '</td>
					<td width="30px" align="right">' .
        $discount .
        '&nbsp;' .
        '</td>
					<td width="70px" align="right">' .
        $subtotal .
        '&nbsp;' .
        '</td>
				</tr>';
    $no++;
    $nsubtotal = $nsubtotal + $row['subtotal'];
}
$subtotal = number_format($nsubtotal, 0, ',', '.');
$ntotal = number_format($total, 0, ',', '.');
$html .=
    '<tr><tr>
				<td colspan="7" style="color:black" align="right">&nbsp;Subtotal&nbsp; </td><td align="right">&nbsp;' .
    $subtotal .
    '&nbsp;</td></tr>
				<tr><td colspan="7" style="color:black" align="right">&nbsp;Biaya Lain&nbsp; </td><td align="right">&nbsp;' .
    $biaya_lain .
    '&nbsp;</td></tr>
				<tr><td colspan="7" style="color:black" align="right">&nbsp;PPn (%)&nbsp; </td><td align="right">&nbsp;' .
    $ppn .
    '&nbsp;</td></tr>
				<tr><td colspan="7" style="color:black" align="right">&nbsp;Total&nbsp; </td><td align="right">&nbsp;' .
    $ntotal .
    '&nbsp;</td></tr>
		</table>';
$html .= '<br><span style="font-size:12px;">' . ($terimah->keterangan != '' ? 'Keterangan : ' : '') . $terimah->keterangan . '</span>';
echo $html;
?>
{{-- 
"$alamatcust" .
    '</td>
			<tr><td></td><td style="font-size:12px;"></td></td><td style="font-size:11px";>' .
    "$telpcust" . --}}

{{-- @foreach ($so as $row)
    <table>
        <tr>
            {{ $row->kdbarang }}
        </tr>
    </table>
@endforeach --}}
