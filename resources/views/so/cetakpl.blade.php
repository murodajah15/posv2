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
@include('home.logoroll')
<?php
$html .= session('tampillogo');

$noso = $soh->noso;
$ppn = $soh->ppn;
$biaya_lain = $soh->biaya_lain;
$total = $soh->total;
$alamatcust = $soh->alamat . ' ' . $soh->kota . ' ' . $soh->kodepos;
$telpcust = $soh->telp1 . ' - ' . $soh->telp2;
// $html .= '<p style="margin-top:2px; margin-bottom:2px" align="center" style="font-size:7px;"><u>PICKING LIST</u></p>
$html .= '<u style="font-size:8px;">PICKING LIST</u>
		';
$html .=
    '<table border="0" height="5">
			<tr><td width="30" style="font-size:20px;">NO.</td><td width="280" style="font-size:20px";>: ' .
    $soh->noso .
    '</td>
			<td width="250" style="font-size:20px";>' .
    $soh->nmcustomer .
    '</td>
			<tr><td width="30" style="font-size:20px;">Tanggal </td></td><td width="280" style="font-size:20px";>: ' .
    $soh->tglso .
    '</td>
   </td><td width="180" style="font-size:14px;font-family: Arial, Helvetica, sans-serif;">' .
    "$alamatcust" .
    '</td>
			</td><td width="180" style="font-size:11px";>' .
    '</td></tr>
		</table>
		<table border="0" table-layout="fixed"; cellpadding="0"; cellspacing="0"; style=font-size:15px; class="table table-striped table table-bordered;">
			<tr>
				<th width="30px" height="20" align="center"><font size="1" color="black">&nbsp;NO.&nbsp;</th>
				<th width="100px" align="left"><font size="1" color="black">&nbsp;KODE BARANG&nbsp;</th>
				<th width="210px" align="left"><font size="1" color="black">&nbsp;NAMA BARANG&nbsp;</th>
				<th width="50px"  align="center"><font size="1" color="black">&nbsp;SATUAN&nbsp;</th>
				<th width="60px"  align="right"><font size="1" color="black">&nbsp;QTY&nbsp;</th>
			</tr>';

$no = 1;
$nsubtotal = 0;
$nqty = 0;
foreach ($sod as $row) {
    $qty = number_format($row['qty'], 2, ',', '.');
    $harga = number_format($row['harga'], 0, ',', '.');
    $discount = number_format($row['discount'], 2, ',', '.');
    $subtotal = number_format($row['subtotal'], 0, ',', '.');
    $html .=
        '<tr><td width="30px" align="center" style="font-size:14px;">' .
        $no .
        '</td>
      <td width="30px" align="left style="font-size:14px;"">' .
        '&nbsp;' .
        $row['kdbarang'] .
        '</td>
					<td width="50px" align="left" style="font-size:14px;">' .
        '&nbsp;' .
        $row['nmbarang'] .
        '</td>
					<td width="40px" align="center" style="font-size:14px;">' .
        $row['nmsatuan'] .
        '</td>
					<td width="50px" align="right" style="font-size:14px;">' .
        $qty .
        '&nbsp;' .
        '</td>
		</tr>';
    $no++;
    $nsubtotal = $nsubtotal + $row['subtotal'];
    $nqty = $nqty + $row['qty'];
}
$subtotal = number_format($nsubtotal, 0, ',', '.');
$ntotal = number_format($total, 0, ',', '.');
$fqty = number_format($nqty, 2, ',', '.');
$html .=
    '<tr><tr>
				<td colspan="4" style="color:black" align="right">&nbsp;Total&nbsp; </td><td style="font-size:14px;" align="right">&nbsp;' .
    $fqty .
    '&nbsp;</td></tr>
		</table>';

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
