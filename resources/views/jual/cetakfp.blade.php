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

date_default_timezone_set('Asia/Jakarta');
$id = $_GET['id'];
$no = 1;

$queryh = mysqli_query($connect, "select * from jualh where id='$id' and jualh.proses='Y' order by nojual");
$de = mysqli_fetch_assoc($queryh);
$nojual = $de['nojual'];
$tanggal = date('d-M-Y', strtotime($de['tgljual'])); //$de['tgljual']; //

$kdcustomer = $de['kdcustomer'];
$nmcustomer = $de['nmcustomer'];
$biaya_lain = $de['biaya_lain'];
$keterangan = $de['keterangan'];
$ppn = $de['ppn'];
$rp_ppn = $de['total_sementara'] * ($ppn / 100);
$total = $de['total'];
$customer = $de['kdcustomer'] . '-' . $de['nmcustomer'];
$queryh = mysqli_query($connect, "select * from tbcustomer where kode='$kdcustomer'");
$de = mysqli_fetch_assoc($queryh);
$alamatcust = $de['alamat'] . ' ' . $de['kota'] . ' ' . $de['kodepos'];
$telpcust = $de['telp1'] . ' - ' . $de['telp2'];

$html = '';

// $html = '<style>
// th, td {
//   border: 10px solid black;
//   border-radius: 10px;
// }
// </style>';

?>
@include('home.logo')
<?php
$html .= session('tampillogo');

$html .=
    '<p style="font-family: Sans-serif;font-size:18px;text-align:center;margin-top:1px;margin-bottom:1px;"><u>FAKTUR PENJUALAN</u></p>
  <table border="0">
   <tr><td width="30" style="font-size:12px;font-family: Sans-serif;">NO.</td><td width="280" style="font-size:14px; font-family: Sans-serif;">: ' .
    "$nojual" .
    '</td>
   <td width="380" style="font-size:14px; font-family: Sans-serif;">' .
    "$nmcustomer" .
    '</td>
   <tr><td width="30" style="font-size:12px;font-family: Sans-serif;">Tanggal </td></td><td width="280" style="font-size:13px;font-family: Sans-serif;">: ' .
    "$tanggal" .
    '</td>
   </td><td width="180" style="font-size:12px;font-family: Arial, Helvetica, sans-serif;">' .
    "$alamatcust" .
    '</td>
   <tr><td colspan="2"></td><td style="font-size:12px"font-family: Sans-serif;;>' .
    "$telpcust" .
    '</td></tr></table>';

$html .= '<table border="1" table-layout="fixed"; cellpadding="0"; cellspacing="0"; style="font-size:15px;font-family: Sans-serif;" class="table table-striped table table-bordered;">
   <tr>
   <th width="30px" style="text-align:center;"><font size="1">NO.</th>
   <th width="95px" align="center"><font size="1">KODE BARANG</th>
   <th width="267px" align="center"><font size="1">NAMA BARANG</th>
   <th width="50px" align="center"><font size="1">&nbsp;SATUAN&nbsp;</th>
   <th width="60px" align="center"><font size="1">QTY</th>
   <th width="80px" align="center"><font size="1">HARGA</th>
   <th width="44px" align="center"><font size="1">DISC.</th>
   <th width="90px" align="center"><font size="1">&nbsp;SUBTOTAL&nbsp;</th>
   </tr></table>';

$queryd = mysqli_query($connect, "select jualh.nojual,jualh.tgljual,jualh.kdcustomer,jualh.nmcustomer,juald.kdbarang,juald.nmbarang,juald.kdsatuan,juald.qty,juald.harga,juald.discount,juald.subtotal,tbsatuan.nama as nmsatuan from jualh inner join juald on jualh.nojual=juald.nojual left join tbsatuan on tbsatuan.kode=juald.kdsatuan where jualh.nojual='$nojual' and jualh.proses='Y' order by nojual");
$nsubtotal = 0;
$jumrecord = mysqli_num_rows($queryd);
$html .= '<table border="0.5" table-layout="fixed"; cellpadding="0"; cellspacing="1"; style="font-size:14px;font-family: Tahoma, Verdana, Segoe, sans-serif;" class="table table-striped table table-bordered;">';
while ($row = mysqli_fetch_assoc($queryd)) {
    $harga = number_format($row['harga'], 0, ',', '.');
    $subtotal = number_format($row['subtotal'], 0, ',', '.');
    $html .=
        '<tr><td width="30px" align="center" height="10" style="font-size:13px;font-family:Sans-serif;">' .
        $no .
        '</td>
     <td width="95px" style="font-size:13px;font-family:Sans-serif;text-align:left;">' .
        '&nbsp;' .
        $row['kdbarang'] .
        '</td>
     <td width="267x" style="font-size:13px;font-family:Sans-serif;text-align:left;">' .
        '&nbsp;' .
        $row['nmbarang'] .
        '</td>
     <td width="50px" style="font-size:13px;font-family:Sans-serif;text-align:center;">' .
        $row['nmsatuan'] .
        '</td>
     <td width="60px" style="font-size:13px;font-family:Sans-serif;text-align:right;">' .
        $row['qty'] .
        '&nbsp;' .
        '</td>
     <td width="80px" style="font-size:13px;font-family:Sans-serif;text-align:right;">' .
        $harga .
        '&nbsp;' .
        '</td>
     <td width="44px" style="font-size:13px;font-family:Sans-serif;text-align:right;">' .
        $row['discount'] .
        '&nbsp;' .
        '</td>
     <td width="90px" style="font-size:13px;font-family:Sans-serif;text-align:right;">' .
        $subtotal .
        '&nbsp;' .
        '</td>
    </tr>';
    $no++;
    $nsubtotal = $nsubtotal + $row['subtotal'];
}
$html .= '</table>';

$subtotal = number_format($nsubtotal, 0, ',', '.');
$biaya_lain = number_format($biaya_lain, 0, ',', '.');
$ntotal = number_format($total, 0, ',', '.');
$rp_ppn = number_format($rp_ppn, 0, ',', '.');

// <tr><td colspan="2"></td><td colspan="2" style="text-align:right;">&nbsp;Biaya Lain-lain&nbsp;</td><td width="50" align="right">' . $biaya_lain . '&nbsp;</td></tr>
// 			<tr><td colspan="2"></td><td colspan="2" style="text-align:right;">&nbsp;PPN&nbsp;</td><td width="50" align="right">' . $rp_ppn . '&nbsp;</td></tr>
// 			<tr><td colspan="2"></td><td colspan="2"  style="text-align:right;">&nbsp;Total&nbsp;</td><td width="80" align="right">' . $ntotal . '&nbsp;</td></tr>
// 			<tr><td colspan="4"><br><br></td></tr>
// $html .= '<table border="1" table-layout="fixed"; cellpadding="0"; cellspacing="1"; style="font-size:14px;font-family: Tahoma, Verdana, Segoe, sans-serif;" class="table table-striped table table-bordered;">';
$html .= '<table border="1" height="15" style="font-size:13px; font-family: Sans-serif;" table-layout="fixed"; cellpadding="0.8"; cellspacing="1"; class="table table-striped table table-bordered;">';
$html .=
    ' <tr>
   <td width="260px" style="font-size:13px;font-family:Sans-serif;text-align:center">Penerima</td>
   <td width="260px" style="font-size:13px;font-family:Sans-serif;text-align:center">Hormat Kami</td>
   <td colspan="3" width="120" style="font-size:13px;font-family:Sans-serif;text-align:right;">&nbsp;Subtotal&nbsp;</td><td width="90" align="right">' .
    $subtotal .
    '&nbsp;</td></tr>
   <tr><td rowspan="4"></td><td td rowspan="4"></td><td colspan="3" style="text-align:right;">&nbsp;Biaya Lain-lain&nbsp;</td><td width="90" align="right">' .
    $biaya_lain .
    '&nbsp;</td></tr>';
if ($ppn > 0) {
    $html .= '<tr><td colspan="3" style="text-align:right;">&nbsp;PPN&nbsp;' . $ppn . ' %' . '</td><td width="90" align="right">' . $rp_ppn . '&nbsp;</td></tr>';
} else {
    $html .= '<tr><td colspan="3" style="text-align:right;">&nbsp;PPN&nbsp;</td><td width="90" align="right">' . $rp_ppn . '&nbsp;</td></tr>';
}
$html .= '<tr><td colspan="3" style="text-align:right;">&nbsp;Total&nbsp;</td><td width="90" align="right">' . $ntotal . '&nbsp;</td></tr>';
$html .= '<tr><td style="border: 0px solid white; text-align:right;">&nbsp;&nbsp;</td><td style="border: 0px solid white;"></td></tr>';
$html .= '<tr><td style="font-size:11px" align="center" width="180">Nama Terang & Cap Perusahaan</td><td style="font-size:11px" align="center" width="170">' . $nm_perusahaan . '&nbsp;</td></tr>';
$html .= '</table>';
// $terbilang = ucwords(terbilang($total));
// $terbilang = '# ' . strtoupper(Terbilang::make($total, ' Rupiah #'));
$terbilang = ucwords(Terbilang::make($total, ''));
$html .= '<i><span style="color: black; font-size: 15px; font-family: Sans-serif;">Terbilang : # ' . $terbilang . ' Rupiah #</span></i>';
$html .= '<br><span style="color: black; font-size: 12px; font-family: Sans-serif;">' . session('norek1') . ', ' . session('norek2') . '</span>';
$html .=
    '<br><span style="color: black; font-size: 12px; font-family: Sans-serif;">' .
    'Komplain/penukaran barang dalam waktu 14 hari setelah barang
diterima dengan syarat sebelum expired dan masih utuh. Resiko barang beralih pada customer saat diterima customer.' .
    '</span>';
if ($keterangan != '') {
    $html .= '<br><span style="color: black; font-size: 12px; font-family: Sans-serif;">' . 'Keterangan : ' . $keterangan . '</span>';
}
echo $html;

// $filename = "FP_" . $nojual . ".pdf";
// try {
// 	require_once("../../vendor/autoload.php");

// 	//$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'utf-8', [190, 236]]);
// 	//  $mpdf = new mPDF('',    // mode - default ''
// 	// '',    // format - A4, for example, default ''
// 	// 0,     // font size - default 0
// 	// '',    // default font family
// 	// 15,    // margin_left
// 	// 15,    // margin right
// 	// 16,     // margin top
// 	// 16,    // margin bottom
// 	// 9,     // margin header
// 	// 9,     // margin footer
// 	// 'L');  // L - landscape, P - portrait

// 	if ($jumrecord > 10) {
// 		$mpdf = new \Mpdf\Mpdf([
// 			'format' => 'Letter',
// 			'margin_left' => 10,
// 			'margin_right' => 10,
// 			'margin_top' => 8,
// 			'margin_bottom' => 5,
// 			'margin_header' => 5,
// 			'margin_footer' => 5,
// 		]);
// 	} else {
// 		//$mpdf = new \Mpdf\Mpdf(['format' => [190, 126], wide=190, height=126
// 		$mpdf = new \Mpdf\Mpdf([
// 			// 'format' => [205, 126],
// 			// 'format' => [95, 126], //gagal jadi ke landscape
// 			'format' => [150, 210], //gagal jadi ke landscape
// 			// 'format' => 'Letter-P',
// 			'orientation' => 'L',
// 			'margin_left' => 10,
// 			'margin_right' => 10,
// 			'margin_top' => 8,
// 			'margin_bottom' => 5,
// 			'margin_header' => 5,
// 			'margin_footer' => 5,

// 		]);
// 	}
// 	// echo $html;
// 	// $tmpdir = sys_get_temp_dir();   # ambil direktori temporary untuk simpan file.
// 	// $file =  tempnam($tmpdir, 'ctk');  # nama file temporary yang akan dicetak
// 	// $handle = fopen($file, 'w');
// 	// fwrite($handle, $html);
// 	// fclose($handle);
// 	// // copy($file, "//localhost/EPSON LX-310 ESCP");  # Lakukan cetak
// 	// copy($file, "//192.168.30.3/EPSON L120 Series");  # Lakukan cetak
// 	// unlink($file);
// 	// // 	'mode' => 'c',
// 	// $mpdf->AddPage('P');
// 	$mpdf->SetDisplayMode(50);
// 	$mpdf->showImageErrors = true;
// 	$mpdf->mirrorMargins = 1;
// 	$mpdf->SetTitle('Generate PDF file using PHP and MPDF');
// 	$mpdf->WriteHTML($html);
// 	$mpdf->Output($filename, 'I');
// } catch (\Mpdf\MpdfException $e) {
// 	echo $e->getMessage();
// }

?>
