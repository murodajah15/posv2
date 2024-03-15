@include('home.config')

<?php
$connect = session('connect');
$nm_perusahaan = session('nm_perusahaan');
$alamat_perusahaan = session('alamat');
$telp_perusahaan = session('telp');

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
    '<p style="font-family: Tahoma, Verdana, Segoe, sans-serif;font-size:18px;text-align:left;margin-top:1px;margin-bottom:1px;"><u>SURAT JALAN</u></p>
	<table border="0">
		<tr><td width="30" style="font-size:12px;font-family: Tahoma, Verdana, Segoe, sans-serif;">NO.</td><td width="280" style="font-size:14px";>: ' .
    "$nojual" .
    '</td>
		<td width="380" style="font-size:14px;font-family: Tahoma, Verdana, Segoe, sans-serif;">' .
    "$nmcustomer" .
    '</td>
		<tr><td width="30" style="font-size:12px;font-family: Tahoma, Verdana, Segoe, sans-serif;">Tanggal </td></td><td width="280" style="font-size:13px";>: ' .
    "$tanggal" .
    '</td>
		</td><td width="180" style="font-size:12px;font-family: Tahoma, Verdana, Segoe, sans-serif;">' .
    "$alamatcust" .
    '</td>
		<tr><td colspan="2"></td><td style="font-size:13px;font-family: Tahoma, Verdana, Segoe, sans-serif;">' .
    "$telpcust" .
    '</td></tr></table>';

$html .= '<table border="1" table-layout="fixed"; cellpadding="0"; cellspacing="0"; style="font-size:13px;font-family: Tahoma, Verdana, Segoe, sans-serif;" class="table table-striped table table-bordered;">
		<tr>
			<td width="30px" style="text-align:center;">NO.</td>
			<td width="100px" style="text-align:center;">&nbsp;KODE&nbsp;<br>BARANG</td>
			<td width="510px" style="text-align:left;">&nbsp;NAMA BARANG</td>
			<td width="60px" style="text-align:center;">SATUAN</td>
			<td width="70px" style="text-align:center;">QTY&nbsp;</td>
		</tr></table>';

$no = 1;
$nsubtotal = 0;
$html .= '<table border="0.5" table-layout="fixed"; cellpadding="0"; cellspacing="1"; style="font-size:14px;font-family: Tahoma, Verdana, Segoe, sans-serif;" class="table table-striped table table-bordered;">';
$queryd = mysqli_query($connect, "select jualh.nojual,jualh.tgljual,jualh.kdcustomer,jualh.nmcustomer,juald.kdbarang,juald.nmbarang,juald.kdsatuan,juald.qty,juald.harga,juald.discount,juald.subtotal,tbsatuan.nama as nmsatuan from jualh inner join juald on jualh.nojual=juald.nojual left join tbsatuan on tbsatuan.kode=juald.kdsatuan where jualh.nojual='$nojual' and jualh.proses='Y' order by nojual");
while ($row = mysqli_fetch_assoc($queryd)) {
    // foreach ($juald as $row) {
    $harga = number_format($row['harga'], 0, ',', '.');
    $subtotal = number_format($row['subtotal'], 0, ',', '.');
    $html .=
        '<tr><td width="30px" align="center" height="12">' .
        $no .
        '</td>
					<td width="100px" style="text-align:left";>' .
        '&nbsp;' .
        $row['kdbarang'] .
        '</td>
					<td width="510px" style="text-align:left";>' .
        '&nbsp;' .
        $row['nmbarang'] .
        '</td>
					<td width="60px" align="center">' .
        $row['nmsatuan'] .
        '</td>
					<td width="70px" align="right">' .
        $row['qty'] .
        '&nbsp;' .
        '</td>
				</tr>';
    $no++;
    $nsubtotal = $nsubtotal + $row['subtotal'];
}
$html .= '</table>';

$subtotal = number_format($nsubtotal, 0, ',', '.');
$ntotal = number_format($total, 0, ',', '.');

$html .=
    '<table border="1" height="15" style="font-size:13px;font-family: Tahoma, Verdana, Segoe, sans-serif;" table-layout="fixed"; cellpadding="0"; cellspacing="0"; class="table table-striped table table-bordered;">	
			<tr>
				<td width="150px" align="center"><color="black">Penerima</td>
				<td width="210px" align="center"><color="black">Hormat Kami</td>
				<td width="250px" align="center"><color="black">Barang-barang tsb diatas telah diterima /diperiksa dengan baik dan ukuran cukup</td>
				<td width="130px" align="center"><color="black">Pengirim</td>				
			</tr><tr>
			<td></td><td colspan="1"></td><td align="center"><br>KAMI HANYA MELAYANI<br>KOMPLAIN/PENUKARAN BARANG<br>DALAM WAKTU 7 (TUJUH HARI),<br>SETELAH BARANG DITERIMA</td><td></td>
			<tr><td align="center" width="180">Nama Terang & Cap Perusahaan</td><td width="180" align="center">' .
    $nm_perusahaan .
    '</td><td></td><td></td></tr>';
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
