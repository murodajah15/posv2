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

if ($unproses != '1') {
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
    $id = $_GET['id'];
    $cari = $id - 1;
    $bulan = substr('0' . $_GET['bulan'], -2);
    $tahun = $_GET['tahun'];
    if (substr('0' . $id, -2)=='01'){
        $cari = (intval(substr($cari,0,4))-1).'12';
    }else{
        $cari = $tahun.(substr('0' . $cari, -2));
    }
    // if ($bulan=='01'){
    //     $bulan = '12';
    //     $tahun = $tahun-1;
    // }
    // $periodesblm = $tahun . $bulan;
    $periodesblm = $cari; //$tahun . $bulan;
	echo 'Periode sebelumnya '.$cari; //'-'.$bulan.'-'.$tahun;
    $k = mysqli_fetch_assoc(mysqli_query($connect, "select * from stock_barang where periode='$cari'"));
		if (isset($k['periode'])){
			if ($k['periode'] == $cari) {
                $query = $connect->prepare("update saplikasi set closing_hpp=?,bulan=?,tahun=? where aktif=?");
                $query->bind_param('ssss', $cari, $bulan, $tahun, $aktif);
                if ($query->execute()) { //and mysqli_affected_rows($connect)>0
                    // echo "<script>alert('Data berhasil disimpan !');
                    // window.location.href='wo';
                    // </script>";	
                    mysqli_query($connect, "update close_hpp set status='N' where periode='$id'");
                    mysqli_query($connect, "TRUNCATE TABLE proses_hpp");
                    $periode = $tahun.$bulan;
                    mysqli_query($connect, "delete from stock_barang where periode='$periode'");
			?>
    <script>
        swal({
            title: "Unclosing Berhasil ",
            text: "",
            icon: "success"
        }).then(function() {
            window.location.href = 'closing_hpp';
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
            title: "Unclosing Gagal ",
            text: "",
            icon: "error"
        }).then(function() {
            window.location.href = 'closing_hpp';
        });
    </script>
    <?php
					}
			} else {
					$cari = "";
					$query = $connect->prepare("update saplikasi set closing_hpp=? where aktif=?");
					$query->bind_param('ss', $cari, $aktif);
					if ($query->execute()) { //and mysqli_affected_rows($connect)>0
							// echo "<script>alert('Data berhasil disimpan !');
							// window.location.href='wo';
							// </script>";	
							mysqli_query($connect, "update close_hpp set status='N' where periode='$id'");
							$k = mysqli_fetch_assoc(mysqli_query($connect, "select * from close_hpp where status='Y' order by periode desc limit 1"));
							echo $k['periode'];
							if (isset($k)) {
									$periodesblm = $k['periode'];
									$bulan = substr('0' . $_GET['bulan'], -2);
									$tahun = $_GET['tahun'];
							} else {
									$bulan = substr('0' . $_GET['bulan'], -2);
									$tahun = $_GET['tahun'];
									$periodesblm = $tahun . $bulan;
							}
							mysqli_query($connect, "update saplikasi set closing_hpp='$periodesblm',bulan='$bulan',tahun='$tahun' where aktif='Y'");
					?>
    <script>
        swal({
            title: "Unclosing Berhasil ",
            text: "",
            icon: "success"
        }).then(function() {
            window.location.href = 'closing_hpp';
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
            title: "Unclosing Gagal ",
            text: "",
            icon: "error"
        }).then(function() {
            window.location.href = 'closing_hpp';
        });
    </script>
    <?php
					}
			}
		}else{
			?>
    <script>
        swal({
            title: "Unclosing Gagal ",
            text: "",
            icon: "error"
        }).then(function() {
            window.location.href = 'closing_hpp';
        });
    </script>
    <?php
		}
    ?>
</body>
