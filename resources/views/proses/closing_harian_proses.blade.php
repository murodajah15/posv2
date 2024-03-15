@include('home.config')

@include('home.akses')
<?php
$pakai = session('pakai');
$tambah = session('tambah');
$edit = session('edit');
$hapus = session('hapus');
$proses = session('proses');
$unproses = session('unproses');
$cetak = session('cetak');

if ($proses != '1') {
    echo '<script>
        alert(\'Anda Tidak Berhak\')
                window.close()
    </script>';
    exit();
}
?>

<body>
    <script src="{{ asset('/') }}assets/dist/js/sweet-alert.min.js"></script>
    <?php
	$connect = session('connect');
	date_default_timezone_set('Asia/Jakarta');
	$aktif = 'Y';
	//$tgl_closing = $_GET['id'];
	// $username = $_GET['username'];
	$tgl_closing = date('Y-m-d');
	$tgl_berikutnya = $_GET['id'];
	$tgl_berikutnya = date("Y-m-d", strtotime($tgl_berikutnya));
	$qry = mysqli_query($connect, "select * from saplikasi where aktif='Y'");
	$data = mysqli_fetch_assoc($qry);
	$periodeclose = $data['closing_hpp'];
	$periodedata = date("Y", strtotime($tgl_berikutnya)) . date("m", strtotime($tgl_berikutnya));
	if ($periodedata < $periodeclose) {
		echo $periodedata .'<'. $periodeclose;
	?>
    <script>
        swal({
            title: "Bulan dan tahun tanggal berikutnya tidak boleh lebih kecil terakhir closing Bulanan ",
            text: "",
            icon: "error"
        }).then(function() {
            window.location.href = 'closing_harian';
        });
    </script>
    <?php
		exit;
	}
	$query = $connect->prepare("update saplikasi set tgl_closing=?,tgl_berikutnya=?,user_closing=? where aktif=?");
	$query->bind_param('ssss', $tgl_closing, $tgl_berikutnya, $username, $aktif);
	if ($query->execute()) { //and mysqli_affected_rows($connect)>0
		// echo "<script>alert('Data berhasil disimpan !');
		// window.location.href='wo';
		// </script>";
		if ($_GET['resetnomor'] == 'on') {
			$bulan = substr('0' . $_GET['bulan'], -2);
			$tahun = $_GET['tahun'];
			mysqli_query($connect, "update saplikasi set bulan='$bulan',tahun='$tahun',noso='0',nojual='0',nopo='0',nobeli='0',nokeluar='0',noterima='0',noopname='0',noapprov='0',nokwtunai='0',nokwtagihan='0',nomohon='0',nokwkeluar='0' where aktif='Y'");
		}

		//jalankan ini gara2 nilai detail tidak menjumlah ke header
		$tampil = mysqli_query($connect, "select * from belih");
		while ($rowbelih = mysqli_fetch_assoc($tampil)) {
			$nobeli = $rowbelih['nobeli'];
			mysqli_query($connect, "update belid set subtotal=(qty*harga)-((qty*harga)*(discount/100)) where nobeli='$nobeli'");
			$query = mysqli_query($connect, "select sum(subtotal) as nsubtotal from belid where nobeli='$nobeli'");
			$k = mysqli_fetch_assoc($query);
			$subtotal = $k['nsubtotal'];
			mysqli_query($connect, "update belih set subtotal='$subtotal', total_sementara='$subtotal'+biaya_lain, total='$subtotal'+biaya_lain+materai+(('$subtotal'+biaya_lain)*(ppn/100)) where nobeli='$nobeli'");
		}

		$qry = mysqli_query($connect, "select * from belih where proses='Y' and tglbeli='$tgl_closing'");
		while ($rowbelih = mysqli_fetch_assoc($qry)) {
			$nobeli = $rowbelih['nobeli'];
			$tglbeli = $rowbelih['tglbeli'];
			mysqli_query($connect,"update belid set tglbeli='$tglbeli' where nobeli='$nobeli'");
		}
		$qry = mysqli_query($connect, "select * from jualh where proses='Y' and tgljual='$tgl_closing'");
		while ($rowjualh = mysqli_fetch_assoc($qry)) {
			$nojual = $rowjualh['nojual'];
			$tgljual = $rowjualh['tgljual'];
			mysqli_query($connect,"update juald set tgljual='$tgljual' where nojual='$nojual'");
		}
		$qry = mysqli_query($connect, "select * from keluarh where proses='Y' and tglkeluar='$tgl_closing'");
		while ($rowkeluarh = mysqli_fetch_assoc($qry)) {
			$nokeluar = $rowkeluarh['nokeluar'];
			$tglkeluar = $rowkeluarh['tglkeluar'];
			mysqli_query($connect,"update keluard set tglkeluar='$tglkeluar' where nokeluar='$nokeluar'");
		}
		$qry = mysqli_query($connect, "select * from terimah where proses='Y' and tglterima='$tgl_closing'");
		while ($rowterimah = mysqli_fetch_assoc($qry)) {
			$noterima = $rowterimah['noterima'];
			$tglterima = $rowterimah['tglterima'];
			mysqli_query($connect,"update terimad set tglterima='$tglterima' where noterima='$noterima'");
		}

		// $qry = mysqli_query($connect, "select * from belih where proses='Y'");
		// while ($rowbelih = mysqli_fetch_assoc($qry)) {
		// 	$nobeli = $rowbelih['nobeli'];
		// 	$tglbeli = $rowbelih['tglbeli'];
		// 	mysqli_query($connect,"update belid set tglbeli='$tglbeli' where nobeli='$nobeli'");
		// }
		// $qry = mysqli_query($connect, "select * from jualh where proses='Y'");
		// while ($rowjualh = mysqli_fetch_assoc($qry)) {
		// 	$nojual = $rowjualh['nojual'];
		// 	$tgljual = $rowjualh['tgljual'];
		// 	mysqli_query($connect,"update juald set tgljual='$tgljual' where nojual='$nojual'");
		// }
		// $qry = mysqli_query($connect, "select * from keluarh where proses='Y'");
		// while ($rowkeluarh = mysqli_fetch_assoc($qry)) {
		// 	$nokeluar = $rowkeluarh['nokeluar'];
		// 	$tglkeluar = $rowkeluarh['tglkeluar'];
		// 	mysqli_query($connect,"update keluard set tglkeluar='$tglkeluar' where nokeluar='$nokeluar'");
		// }
		// $qry = mysqli_query($connect, "select * from terimah where proses='Y'");
		// while ($rowterimah = mysqli_fetch_assoc($qry)) {
		// 	$noterima = $rowterimah['noterima'];
		// 	$tglterima = $rowterimah['tglterima'];
		// 	mysqli_query($connect,"update terimad set tglterima='$tglterima' where noterima='$noterima'");
		// }

		//Create History
		$tanggal = date('Y-m-d');
		$datetime = date('Y-m-d H:i:s');
		$dokumen = $tgl_closing . ' - ' . $tgl_berikutnya;
		$form = 'Closing Harian';
		$status = 'Closing';
		$catatan = '';
		$username = $username;
		$history = $connect->prepare("insert into hisuser (tanggal,dokumen,form,status,user,catatan,datetime) values (?,?,?,?,?,?,?)");
		$history->bind_param('sssssss', $tanggal, $dokumen, $form, $status, $username, $catatan, $datetime);
		$history->execute();
		$closing_harian = $connect->prepare("insert into closing_harian (tglclosing,tglberikut,created_at,user) values (?,?,?,?)");
		$closing_harian->bind_param('ssss', $tanggal, $tgl_berikutnya, $datetime, $username);
		$closing_harian->execute();

	?>
    <script>
        swal({
            title: "Closing Harian Berhasil ",
            text: "",
            icon: "success"
        }).then(function() {
            window.location.href = 'closing_harian';
        });
    </script>
    <?php
	} else {
		// echo "<script>alert('Gagal simpan data !');
		// window.location.href='wo';
		// </script>";							
	?>
    <script>
        swal({
            title: "Closing harian Gagal ",
            text: "",
            icon: "error"
        }).then(function() {
            window.location.href = 'closing_harian';
        });
    </script>
    <?php
	}
	?>
</body>
