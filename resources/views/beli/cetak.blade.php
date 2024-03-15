@include('home.config')

{{-- <style>
    table,
    th,
    td {
        border: 1px solid black;
        border-collapse: collapse;
    }
</style> --}}

<?php
$connect = session('connect');
$nm_perusahaan = session('nm_perusahaan');
$alamat_perusahaan = session('alamat');
$telp_perusahaan = session('telp');
$no = 1;

$html = '';
// $html = '<style>
// 		td { border: 0px solid grey; margin: 5px; height: 20px;}
//         th { border: 0px solid grey; font-weight:normal; height: 30px;}
//         body { font-family: comic sans ms;}
// 	</style>';
?>
@include('home.logo')
<?php
$html .= session('tampillogo');

$nobeli = $belih->nobeli;
$ppn = $belih->ppn;
$biaya_lain = $belih->biaya_lain;
$total = $belih->total;
$tanggal = date('d-M-Y', strtotime($belih->tglbeli));
$kdsupplier = $belih->kdsupplier;
$nmsupplier = $belih->nmsupplier;
$biaya_lain = number_format($belih->biaya_lain, 0, ',', '.');
$ppn = $belih->ppn;
$total = $belih->total;
$supplier = $belih->kdsupplier . '-' . $belih->nmsupplier;
$alamatcust = $belih->alamat . ' ' . $belih->kota . ' ' . $belih->kodepos;
$telpcust = $belih->telp1 . ' - ' . $belih->telp2;

$html .= '<p style="margin-top:5px; margin-bottom:5px" align="center"><u>NOTA PENERIMAAN PEMBELIAN</u></p>
		<hr style="width:99%;text-align:left;margin-left:0">';
$html .=
    '<table border="0" height="5">
			<tr><td width="30" style="font-size:12px;">NO.</td><td width="280" style="font-size:13px";>: ' .
    "$nobeli" .
    '</td>
			<td width="180" style="font-size:12px";>' .
    "$nmsupplier" .
    '</td>
			<tr><td width="30" style="font-size:12px;">Tanggal </td></td><td width="280" style="font-size:13px";>: ' .
    "$tanggal" .
    '</td>
			</td><td width="180" style="font-size:10px";>' .
    "$alamatcust" .
    '</td>
			<tr><td></td><td style="font-size:12px;"></td></td><td style="font-size:11px";>' .
    "$telpcust" .
    '</td></tr>
			<hr size=2></font>
		</table>
		<table table border="0.50" table-layout="fixed"; cellpadding="1"; cellspacing="0"; style=font-size:11px; class="table table-striped table table-bordered;">
			<tr>
				<th width="30px" align="left" height="20"><font size="3" color="black">NO.</th>
				<th width="100px" align="left"><font size="3" color="black">KD. BARANG</th>
				<th width="320px" align="left"><font size="3" color="black">NAMA BARANG</th>
				<th width="40px" align="left"><font size="3" color="black">SATUAN</th>
				<th width="50px" align="right"><font size="3" color="black">QTY' .
    '&nbsp;' .
    '</th>
				<th width="70px" align="right"><font size="3" color="black">HARGA' .
    '&nbsp;' .
    '</th>
				<th width="40px" align="right"><font size="3" color="black">DISC.' .
    '&nbsp;' .
    '</th>
				<th width="80px" align="right"><font size="3" color="black">SUBTOTAL' .
    '&nbsp;' .
    '</th>
			</tr>';

$no = 1;
$nsubtotal = 0;
// dd($belid);
foreach ($belid as $row) {
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
        $row['nmsatuan'] .
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
				<td colspan="7" style="color:black" align="right">&nbsp;Subtotal&nbsp;  </td><td align="right">&nbsp;' .
    $subtotal .
    '&nbsp;</td></tr>
				<tr><td colspan="7" style="color:black" align="right">&nbsp;Biaya Lain&nbsp;  </td><td align="right">&nbsp;' .
    $biaya_lain .
    '&nbsp;</td></tr>
				<tr><td colspan="7" style="color:black" align="right">&nbsp;PPn (%)&nbsp;  </td><td align="right">&nbsp;' .
    $ppn .
    '&nbsp;</td></tr>
				<tr><td colspan="7" style="color:black" align="right">&nbsp;Total&nbsp;  </td><td align="right">&nbsp;<b>' .
    $ntotal .
    '&nbsp;</b></td></tr>
				</table>';

$html .= '<font size="1">Jakarta,  ' . date('d-M-Y', strtotime(date('d-m-Y'))) . ',';

$html .= '<table border="0">
			<tr>
			<th></th>
			<tr><td></td>
			<tr><td></td>
			<tr><td></td>
			<tr><td></td>
			<tr><td></td>
			<tr><td></td>
			<tr><td></td>
			<tr><td></td>
			<tr><td></td>
			<tr><td></td>
			<tr><td></td>
			<tr><td></td>
			<tr><td><font size="1" color="black" align="center">(Kabag. Pembelian)</td></tr></table>';
$html .= '<font size="1" align="right" width="300">Tanggal cetak : ' . date('d-m-Y H:i:s a') . '</font><br>';

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
