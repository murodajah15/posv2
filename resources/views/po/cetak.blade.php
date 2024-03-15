@include('home.config')

<?php
$connect = session('connect');
$nm_perusahaan = session('nm_perusahaan');
$alamat_perusahaan = session('alamat');
$telp_perusahaan = session('telp');
$no = 1;
?>

{{-- <style>
    @page {
        size: 10cm 20cm landscape;
    }
</style> --}}

<?php
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

$nopo = $poh->nopo;
$tanggal = date('-d-m-Y', strtotime($poh->tglpo)); // $de['tglpo'];
$kdsupplier = $poh->kdsupplier;
$nmsupplier = $poh->nmsupplier;
$biaya_lain = number_format($poh->biaya_lain, 0, ',', '.');
$ppn = $poh->ppn;
$total = $poh->total;
$supplier = $poh->kdsupplier . '-' . $poh->nmsupplier;
$keterangan = $poh->keterangan;
$alamatcust = $poh->alamat . ' ' . $poh->kota . ' ' . $poh->kodepos;
$telpcust = $poh->telp1 . ' - ' . $poh->telp2;

$html .= '<p style="margin-top:5px; margin-bottom:5px" align="center"><u>PURCHASE ORDER</u></p>';
// <hr style="width:99%;text-align:left;margin-left:0">';

$html .=
    '<table border="0" height="5">
			<tr><td width="50" style="font-size:16px;">Kepada Yth :</td><td width="150" style="font-size:13px";>.' .
    '' .
    '</td>
			<td width="180" style="font-size:18px";>' .
    'Pemesan :' .
    '</td><td width="150" style="font-size:13px";>' .
    '' .
    '</td>
			<tr><td width="350" style="font-size:18px";>' .
    "$nmsupplier" .
    '</td><td width="10"></td><td width="250" style="font-size:18px";>' .
    "$nm_perusahaan" .
    '</td>
			<tr><td width="350" style="font-size:16px";>' .
    "$alamatcust" .
    '</td><td width="10"></td><td width="550" style="font-size:16px";>' .
    "$alamat_perusahaan" .
    '</td>
			<tr><td width="250" style="font-size:16px";>' .
    "$telpcust" .
    '</td><td width="10"></td><td width="600" style="font-size:16px";>' .
    "$telp_perusahaan" .
    '</td>
			<tr><td width="250" style="font-size:12px";>' .
    '' .
    '</td><td width="10"></td><td width="250" style="font-size:18px";>No. PO : ' .
    "$nopo" .
    '</td>
			<tr><td width="250" style="font-size:12px";>' .
    '' .
    '</td><td width="10"></td><td width="250" style="font-size:16px";>Tanggal: ' .
    "$tanggal" .
    '</td>
		</table>
		<table border="1" table-layout="fixed"; cellpadding="0"; cellspacing="0"; style=font-size:11px; class="table table-striped table table-bordered;">
			<tr>
				<th width="50px" height="20"  align="center"><font size="2" color="black">NO.</th>
				<th width="150px" align="left"><font size="2" color="black">&nbsp;KODE BARANG</th>
				<th width="400px" align="left"><font size="2" color="black">&nbsp;NAMA BARANG</th>
				<th width="90px"  align="right"><font size="2" color="black">&nbsp;QTY&nbsp;</th>
			</tr>
			</table>';
$html .= '<table border="0" table-layout="fixed"; cellpadding="0"; cellspacing="0"; style=font-size:11px; class="table table-striped table table-bordered;">';
$no = 1;
$nsubtotal = 0;
foreach ($pod as $row) {
    $harga = number_format($row['harga'], 0, ',', '.');
    $subtotal = number_format($row['subtotal'], 0, ',', '.');
    $html .=
        '<tr><td width="50"  align="center">' .
        $no .
        '</td>
					<td width="150" align="left">' .
        '&nbsp;' .
        $row['kdbarang'] .
        '</td>
					<td width="410" align="left">' .
        '&nbsp;' .
        $row['nmbarang'] .
        '</td>
					<td width="80" align="right">' .
        $row['qty'] .
        '&nbsp;' .
        '</td>
					</tr>';
    $no++;
    $nsubtotal = $nsubtotal + $row['subtotal'];
}
$subtotal = number_format($nsubtotal, 0, ',', '.');
$ntotal = number_format($total, 0, ',', '.');
$html .=
    '</table></hr><font size="1"><left>-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
<br>Catatan : <br>' .
    $keterangan .
    '<br>';
// $terbilang = ucwords(terbilang($total));
$terbilang = '# ' . strtoupper(Terbilang::make($total, ' Rupiah #'));
// $html .= '<font size="1">Jakarta,  ' . tgl_indo(date('Y-m-d')) . ',<br>';

$html .= '<table border="1" height="15" style="font-size:13px;font-family: Tahoma, Verdana, Segoe, sans-serif;" table-layout="fixed"; cellpadding="0"; cellspacing="0"; class="table table-striped table table-bordered;">	
			<tr><td width="150px" align="center"><color="black">Yang mengajukan</td>
			<td width="210px" align="center"><color="black">Yang menyetujui</td>
			<tr><td colspan="1"></td><td align="center"><br><br><br><br><br><br></td>
			<tr><td align="left" width="180">&nbsp;</td><td width="180" align="left">&nbsp;</td>';

$html .= '</table>';

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
