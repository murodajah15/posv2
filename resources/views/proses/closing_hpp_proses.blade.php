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
    <?php
    $connect = session('connect');
    date_default_timezone_set('Asia/Jakarta');
    $aktif = 'Y';
    ?>

    <?php
    header('Cache-Control: no-cache, must-revalidate');
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
    header('Content-Type: application/xml; charset=utf-8');
    clearstatcache();
    ?>

    <div id="loader"></div>
    <script src="{{ asset('/') }}assets/dist/js/sweet-alert.min.js"></script>

    <style>
        /* Center the loader */
        #loader {
            position: absolute;
            left: 48%;
            top: 50%;
            z-index: 1;
            border: 16px solid #f3f3f3;
            border-radius: 50%;
            border-top: 16px solid blue;
            border-right: 16px solid green;
            border-bottom: 16px solid red;
            width: 60px;
            height: 60px;
            -webkit-animation: spin 2s linear infinite;
            animation: spin 2s linear infinite;
        }

        @-webkit-keyframes spin {
            0% {
                -webkit-transform: rotate(0deg);
            }

            100% {
                -webkit-transform: rotate(360deg);
            }
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Add animation to "page content" */
        .animate-bottom {
            position: relative;
            -webkit-animation-name: animatebottom;
            -webkit-animation-duration: 1s;
            animation-name: animatebottom;
            animation-duration: 1s
        }

        @-webkit-keyframes animatebottom {
            from {
                bottom: -100px;
                opacity: 0
            }

            to {
                bottom: 0px;
                opacity: 1
            }
        }

        @keyframes animatebottom {
            from {
                bottom: -100px;
                opacity: 0
            }

            to {
                bottom: 0;
                opacity: 1
            }
        }

        #myDiv {
            display: none;
        }
    </style>

    <?php
    header('Content-type: text/html; charset=utf-8');
    echo 'Mulai Proses ...<br />';
    $percent = 0;
    // Javascript for updating the progress bar and information
    echo '<script language="javascript">document.getElementById("progressawal").innerHTML = "<div style=\"width:' . $percent . ';background-color:#ddd;\">&nbsp;</div>";document.getElementById("informationawal").innerHTML = "' . $percent . ' row(s) processed (1/2).";</script>';
    // This is for the buffer achieve the minimum size in order to flush data
    //echo str_repeat(' ', 1024 * 64);
    // Send output to browser immediately
    ob_flush();
    flush();
    // Sleep one second so we can see the delay
    sleep(1);
    ?>
    <div id="progressawal" style="width:500px; border:1px solid #ccc;"></div>
    <div id="informationawal"></div>

    <?php
		date_default_timezone_set("Asia/Jakarta");
		$aktif = 'Y';
		$username = $_GET['username'];
		$tahun = $_GET['tahun'];
		$id = $_GET['id'];
		$cari = $id - 1;
		if (substr('0' . $id, -2)=='01'){
			$cari = (intval(substr($tahun,0,4))-1).'12';
		}else{
			$cari = $tahun.(substr('0' . $cari, -2));
		}
		$bulan = substr('0' . $_GET['id'], -2);
		$periode = $tahun . $bulan;
		$tgl_closing = date("Y-m-d H:i:s");
		//echo 'aaaa'.$tgl_closing;
		$tanggal = date('Y-m-d');
		//$array1=explode("-",$tanggal);
		//$tahun=$array1[0];
		//$bulan=$array1[1];			
		//echo $tanggal.'  '.$bulan.'   '.$tahun;
		$periodesblm = $cari;
		echo 'Periode sebelumnya '.(substr('0' . $cari, -2)).', '; //'-'.$bulan.'-'.$tahun;
		$noopname = $_GET['noopname']; //'OP20230700001';
		$de = mysqli_fetch_assoc(mysqli_query($connect, "select * from opnameh where noopname='$noopname'"));
		$tglopname = $de['tglopname'];
		$data = explode('-', $tglopname);
		$tahun_opname = $data[0];
		$bulan_opname = $data[1];
		$periode_opname = $tahun_opname . substr('0' . $bulan_opname, -2); // . substr('0' . $date($tglopname,), -2); //'202307';

		$de = mysqli_fetch_assoc(mysqli_query($connect, "select * from saplikasi where aktif='Y'"));
		$closing_hpp = $de['closing_hpp'];
		if ($periode <= $closing_hpp) {
			echo 'Closing terakhir : ' . $closing_hpp;
		?>
    <script>
        swal({
            title: "Gagal Closing",
            text: "Tidak boleh lebih kecil/sama dengan dari closing terakhir !",
            icon: "error"
            // }).then(function() {
            //     window.history.back(); //then(function(){window.location.href='../../dashboard.php?m=wo';
            // });
        }).then(function() {
            window.location.href = 'closing_hpp';
        });
    </script>
    <?php
			exit();
		}
		mysqli_query($connect, "TRUNCATE TABLE proses_hpp");
		$querytbabrang = mysqli_query($connect, "select kode from tbbarang order by kode");
		$jumrec = mysqli_num_rows($querytbabrang);
		$gagal = 0;
		$i = 1;
		?>
    <div id="progress" style="width:500px; border:1px solid #ccc;"></div>
    <div id="information"></div>
    <?php
    //prosed update ulang tanggal detail table untuk stock barang
    $qry = mysqli_query($connect, "select * from belih where proses='Y' and year(tglbeli)='$tgl_closing' and month(tglbeli)='$tgl_closing'");
    while ($rowbelih = mysqli_fetch_assoc($qry)) {
        $nobeli = $rowbelih['nobeli'];
        $tglbeli = $rowbelih['tglbeli'];
        mysqli_query($connect, "update belid set tglbeli='$tglbeli' where nobeli='$nobeli'");
    }
    $qry = mysqli_query($connect, "select * from jualh where proses='Y' and year(tgljual)='$tgl_closing' and month(tgljual)='$tgl_closing'");
    while ($rowjualh = mysqli_fetch_assoc($qry)) {
        $nojual = $rowjualh['nojual'];
        $tgljual = $rowjualh['tgljual'];
        mysqli_query($connect, "update juald set tgljual='$tgljual' where nojual='$nojual'");
    }
    $qry = mysqli_query($connect, "select * from keluarh where proses='Y' and year(tglkeluar)='$tgl_closing' and month(tglkeluar)='$tgl_closing'");
    while ($rowkeluarh = mysqli_fetch_assoc($qry)) {
        $nokeluar = $rowkeluarh['nokeluar'];
        $tglkeluar = $rowkeluarh['tglkeluar'];
        mysqli_query($connect, "update keluard set tglkeluar='$tglkeluar' where nokeluar='$nokeluar'");
    }
    $qry = mysqli_query($connect, "select * from terimah where proses='Y' and year(tglterima)='$tgl_closing' and month(tglterima)='$tgl_closing'");
    while ($rowterimah = mysqli_fetch_assoc($qry)) {
        $noterima = $rowterimah['noterima'];
        $tglterima = $rowterimah['tglterima'];
        mysqli_query($connect, "update terimad set tglterima='$tglterima' where noterima='$noterima'");
    }
    
    echo 'Proses stock_barang ...<br>';
    while ($databarang = mysqli_fetch_assoc($querytbabrang)) {
        $kdbarang = $databarang['kode'];
        // update ulang harga beli terakhir ke tbbarang
        // $querynobeli = mysqli_query($connect, "select max(nobeli) as nobeli from belid where kdbarang='$kdbarang'");
        // $rownobeli = mysqli_fetch_assoc($querynobeli);
        // $nobeli = $rownobeli['nobeli'];
        // $rowhrgbeli = mysqli_fetch_assoc(mysqli_query($connect, "select harga from belid where nobeli='$nobeli'"));
        // $hrgbeli = $rowhrgbeli['harga'];
        // mysqli_query($connect, "update tbbarang set harga_beli='$hrgbeli' where kode='$kdbarang'");
        //---------------------------------------------
        $queryd = mysqli_query($connect, "select periode from stock_barang where periode='$periode' and kdbarang='$kdbarang'");
        $rec = mysqli_fetch_row($queryd);
        if ($rec > 0) {
            $query = $connect->prepare('update stock_barang set periode=?,kdbarang=?,tgl_closing=?,user_closing=?,tgl_closing=? where kdbarang=? and periode=?');
            $query->bind_param('sssssss', $periode, $kdbarang, $tgl_closing, $username, $tgl_closing, $kdbarang, $periode);
        } else {
            $query = $connect->prepare('insert into stock_barang (periode,kdbarang,tgl_closing,user_closing) values (?,?,?,?)');
            $query->bind_param('ssss', $periode, $kdbarang, $tgl_closing, $username);
        }
        if ($query->execute() and mysqli_affected_rows($connect) > 0) {
            // if ($kdbarang == 'SKF000005') {
            //cari stock awal dari opname jika periode closing = tanggal opname
            // echo $noopname . '  ' . $periode_opname . '  ' . $periode . '<br>';
            if ($periode_opname === $periode) {
                $k = mysqli_fetch_assoc(mysqli_query($connect, "select qty from opnamed where kdbarang='$kdbarang' and noopname='$noopname'"));
                $stock_awal = isset($k['qty']) ? $k['qty'] : 0;
                $query1 = $connect->prepare('update stock_barang set awal=?,akhir=?,tgl_closing=? where kdbarang=? and periode=?');
                $query1->bind_param('sssss', $stock_awal, $stock_awal, $tgl_closing, $kdbarang, $periode);
                if ($query1->execute() and mysqli_affected_rows($connect) > 0) {
                    echo 'Update stock awal ' . $kdbarang . ' = ' . $stock_awal . '<br>';
                }
                // if ($kdbarang == 'SKF000007') {
                // 	mysqli_query($connect, "update stock_barang set awal='$stock_awal',akhir='$stock_awal' where kdbarang='$kdbarang' and periode='$periode'");
                // 	echo 'Update stock awal ' . $kdbarang . ' = ' . $stock_awal . '<br>';
                // }
            }
            // }
        } else {
            $gagal = 1;
            echo 'Insert stock_barang gagal';
            // exit();
        }
        $percent = round(($i / $jumrec) * 100, 0) . '%';
        // Javascript for updating the progress bar and information
        echo '<script language="javascript">document.getElementById("progress").innerHTML="<div style=\"width:' . $percent . ';background-color:#ddd;\">&nbsp;</div>";document.getElementById("information").innerHTML="' . $percent . ' row(s) processed (1/2).";</script>';
        // This is for the buffer achieve the minimum size in order to flush data
        //echo str_repeat(' ', 1024 * 64);
        // Send output to browser immediately
        ob_flush();
        flush();
        // Sleep one second so we can see the delay
        sleep(0);
        $i++;
    }
    ?>

    <div id="progress1" style="width:500px; border:1px solid #ccc;"></div>
    <div id="information1"></div>
    <?php
	echo 'Insert stock_barang selesai<br>';
	echo 'Proses perhitungan stock awal dan akhir bulan ' . $bulan . ' Tahun ' . $tahun . ' ...<br>';
	$i = 1;
	$stock_barang = mysqli_query($connect, "select kdbarang,awal,hpp_akhir from stock_barang where periode='$periode'");
	$qtymasuk = 0;
	$qtykeluar = 0;
	while ($k = mysqli_fetch_assoc($stock_barang)) {
		// if ($k['kdbarang']=='AMAB0009'){
			$kdbarang = $k['kdbarang'];
			$qtyawal = $k['awal'];
			$hrgbeliawal = $k['hpp_akhir'];
			// $queryjum = mysqli_query($connect, "select sum(qty) as qty from belid where kdbarang='$kdbarang' and  month(tglbeli)='$bulan' and year(tglbeli)='$tahun' and proses='Y'");
			// $de = mysqli_fetch_assoc($queryjum);
			// $qtymasuk = $qtymasuk + $de['qty'];
			// $queryjum = mysqli_query($connect, "select sum(qty) as qty from terimad where kdbarang='$kdbarang' and month(tglterima)='$bulan' and year(tglterima)='$tahun' and proses='Y'");
			// $de = mysqli_fetch_assoc($queryjum);
			// $qtymasuk = $qtymasuk + $de['qty'];
			// $queryjum = mysqli_query($connect, "select sum(qty) as qty from juald where kdbarang='$kdbarang' and month(tgljual)='$bulan' and year(tgljual)='$tahun' and proses='Y'");
			// $de = mysqli_fetch_assoc($queryjum);
			// $qtykeluar = $qtykeluar + $de['qty'];
			// $queryjum = mysqli_query($connect, "select sum(qty) as qty from keluard where kdbarang='$kdbarang' and month(tglkeluar)='$bulan' and year(tglkeluar)='$tahun' and proses='Y'");
			// $de = mysqli_fetch_assoc($queryjum);
			// $qtykeluar = $qtykeluar + $de['qty'];
			$queryjum = mysqli_query($connect, "select sum(tbl.qty) as qtymasuk from (select tglbeli as tgldokumen,kdbarang as kode,nobeli as nodokumen,qty,hpp from belid where kdbarang='$kdbarang' and  month(tglbeli)='$bulan' and year(tglbeli)='$tahun' and proses='Y' 
					union select tglterima as tgldokumen,kdbarang as kode,noterima as nodokumen,qty,hpp from terimad where kdbarang='$kdbarang' and month(tglterima)='$bulan' and year(tglterima)='$tahun' and proses='Y') tbl");
			$de = mysqli_fetch_assoc($queryjum);
			$qtymasuk = $de['qtymasuk'];
			$queryjum = mysqli_query($connect, "select sum(tbl.qty) as qtykeluar from (select tgljual as tgldokumen,kdbarang as kode,nojual as nodokumen,qty,hpp from juald where kdbarang='$kdbarang' and month(tgljual)='$bulan' and year(tgljual)='$tahun' and proses='Y' 
					union select tglkeluar as tgldokumen,kdbarang as kode,nokeluar as nodokumen,qty,hpp from keluard where kdbarang='$kdbarang' and month(tglkeluar)='$bulan' and year(tglkeluar)='$tahun' and proses='Y') tbl");
			$de = mysqli_fetch_assoc($queryjum);
			$qtykeluar = $de['qtykeluar'];
			$qtyakhir = $qtyawal + $qtymasuk - $qtykeluar;
			// $query = $connect->prepare("update stock_barang set masuk=?,keluar=?,akhir=? where kdbarang=? and periode=?");
			// $query->bind_param('sssss', $qtymasuk, $qtykeluar, $qtyakhir, $kdbarang, $periode);
			$query = $connect->prepare("update stock_barang set awal=?,masuk=?,keluar=?,akhir=? where kdbarang=? and periode=?");
			$query->bind_param('iiiiss', $qtyawal, $qtymasuk, $qtykeluar, $qtyakhir, $kdbarang, $periode);
			//mysqli_query($connect,"insert into stock_barang (periode,kdbarang) values ('$periode','$kdbarang')");
			if ($query->execute()) {
			} else {
				echo 'Gagal update';
			}
			$querybeli = mysqli_query($connect, "select tglbeli as tgldokumen,kdbarang as kode,nobeli as nodokumen,qty,hpp,harga  from belid where kdbarang='$kdbarang' and  month(tglbeli)='$bulan' and year(tglbeli)='$tahun' and proses='Y' 
					union select tglterima as tgldokumen,kdbarang as kode,noterima as nodokumen,qty,hpp,harga from terimad where kdbarang='$kdbarang' and month(tglterima)='$bulan' and year(tglterima)='$tahun' and proses='Y' 
					union select tgljual as tgldokumen,kdbarang as kode,nojual as nodokumen,qty,hpp,harga from juald where kdbarang='$kdbarang' and month(tgljual)='$bulan' and year(tgljual)='$tahun' and proses='Y' 
					union select tglkeluar as tgldokumen,kdbarang as kode,nokeluar as nodokumen,qty,hpp,harga from keluard where kdbarang='$kdbarang' and month(tglkeluar)='$bulan' and year(tglkeluar)='$tahun' and proses='Y' order by tgldokumen");
			//echo 'aaa'.$record;
			$no = 1;
			while ($db = mysqli_fetch_assoc($querybeli)) {
				mysqli_query($connect, "insert into proses_hpp (kdbarang,nodokumen,tgldokumen,qty,hpp,harga) values ('$db[kode]','$db[nodokumen]','$db[tgldokumen]','$db[qty]','$db[hpp]','$db[harga]')");
			}
			//Ambil Stock sebelumnya dan update ke stock awal bulan proses
			// $periodesebelumnya = $periode - 1;
			// $periodesebelumnya = $periodesblm; //$cari - 1;
			// echo $periode;
			$querystock_barang = mysqli_query($connect, "select akhir,hpp_akhir from stock_barang where periode='$periodesblm' and kdbarang='$kdbarang'");
			$record = mysqli_num_rows($querystock_barang);
			$de = mysqli_fetch_assoc($querystock_barang);
			if (isset($de)) {
				if ($record > 0) {
					$stockblnsebelumnya = $de['akhir'];
					if ($stockblnsebelumnya<=0){
						$hppblnsebelumnya = 0;
					}else{
						$hppblnsebelumnya = $de['hpp_akhir'];
					}
					$stockakhir = $stockblnsebelumnya;
					$stock_berjalan = $stockblnsebelumnya;
					$hpp_berjalan = $hppblnsebelumnya;
					$hpp_akhir = $hppblnsebelumnya;
					$hpp_awal = $hppblnsebelumnya;
				} else {
					$stockblnsebelumnya = 0; //$qtyawal;
					$hppblnsebelumnya =	0; //$hrgbeliawal; //$databarang['hrgbeli'];
					$stockakhir = 0; //$qtyawal;
					$stock_berjalan = 0;
					$hpp_berjalan = 0;
					$hpp_awal = 0;
				}
			} else {
				$stockblnsebelumnya = $qtyawal;
				$hppblnsebelumnya =	$hrgbeliawal; //$databarang['hrgbeli'];
				$stockakhir = $qtyawal;
				$stock_berjalan = 0;
				$hpp_berjalan = 0;
				$hpp_awal = 0;
			}
			// if ($kdbarang == 'SKF000007') {
			// 	echo 'Stock sebelumnya / periodesebelumya' . ' ' . $periodesebelumnya . ' ' . $stockblnsebelumnya;
			// }
			$query_proses_hpp = mysqli_query($connect, "select * from proses_hpp where kdbarang='$kdbarang' order by tgldokumen");
			while ($db = mysqli_fetch_assoc($query_proses_hpp)) {
				$idhpp = $db['id'];
				$nodokumen = $db['nodokumen'];
				$qty = $db['qty'];
				$harga = $db['harga'];
				if (substr($nodokumen, 0, 2) == 'BE' or substr($nodokumen, 0, 2) == 'TB') {
					if (substr($nodokumen, 0, 2) == 'BE') {
						//Hitung HPP
						// echo $kdbarang . ' : ' . $hpp_berjalan . '*' . $stock_berjalan . '+' . $harga . '*' . $qty . '/' . $stock_berjalan . '+' . $qty;
						if ($stock_berjalan + $qty > 0) {
							$hpp_berjalan = (($hpp_berjalan * $stock_berjalan) + ($harga * $qty)) / ($stock_berjalan + $qty);
						} else {
							$hpp_berjalan = $hpp_berjalan;
						}
						// if ($hpp_berjalan>0) {
						// 	echo $kdbarang.' : '.$hpp_berjalan.'*'.$stock_berjalan.'+'.$harga.'*'.$qty.'/'.$stock_berjalan.'+'.$qty;
						// 	$hpp_berjalan = (($hpp_berjalan*$stock_berjalan) + ($harga*$qty)) / ($stock_berjalan+$qty);
						// }else{
						// 	$hpp_berjalan = $harga;
						// }
					}else{
						$hpp_berjalan = $hpp_berjalan;
					}
					$stock_berjalan = $stock_berjalan + $qty;
					$stockakhir = $stockakhir + $qty;
				} else {
					$stock_berjalan = $stock_berjalan - $qty;
					$stockakhir = $stockakhir - $qty;
					//echo 'b'.$stock_berjalan.'c'.$qty;
				}
				mysqli_query($connect, "update proses_hpp set hpp_berjalan='$hpp_berjalan' where id='$idhpp'");
			}
			$query_hpp_berjalan = mysqli_query($connect, "select hpp,hpp_berjalan from proses_hpp where kdbarang='$kdbarang' limit 1");
			$dt = mysqli_fetch_assoc($query_hpp_berjalan);
			if (mysqli_num_rows($query_hpp_berjalan) > 0) {
				$hpp_akhir = $dt['hpp_berjalan'];
			} else {
				$hpp_akhir = $hppblnsebelumnya;
			}
			$query_hpp_berjalan = mysqli_query($connect, "select hpp,hpp_berjalan from proses_hpp order by id desc limit 1");
			$dt = mysqli_fetch_assoc($query_hpp_berjalan);
			if (mysqli_num_rows($query_hpp_berjalan) > 0) {
				$hpp_akhir = $dt['hpp_berjalan'];
			} else {
				$hpp_akhir = $hppblnsebelumnya;
			}
			$nilai_akhir = $stockakhir * $hpp_akhir;
			$query = $connect->prepare("update stock_barang set awal=?,akhir=?,hpp_awal=?,hpp_akhir=?,nilai_akhir=? where kdbarang=? and periode=?");
			$query->bind_param('iiiiiss', $stockblnsebelumnya, $stockakhir, $hpp_awal, $hpp_akhir, $nilai_akhir, $kdbarang, $periode);
			// $query = $connect->prepare("update stock_barang set hpp_awal=?,hpp_akhir=?,nilai_akhir=? where kdbarang=? and periode=?");
			// $query->bind_param('sssss', $hpp_awal, $hpp_akhir, $nilai_akhir, $kdbarang, $periode);
			if ($query->execute()) {
				mysqli_query($connect, "update stock_barang set nilai_awal=awal*hpp_awal where kdbarang='$kdbarang' and periode='$periode'");
			} else {
				echo 'Gagal update';
			}
			// $periode1 = $periode - 1;
			// $de = mysqli_fetch_assoc(mysqli_query($connect, "select * from stock_barang where periode='$periode1' and kdbarang='$kdbarang'"));
			// if ($record == 0) {
			// 	//echo 'masuk';
			// 	$stockblnsebelumnya = $de['akhir'];
			// 	$stockakhir = $stockakhir; //$stockblnsebelumnya;
			// 	$hpp_awal = 0; //$de['hpp_awal'];
			// 	$hpp_akhir = $hpp_berjalan;
			// 	$nilai_akhir = $stockakhir * $hpp_akhir;
			// 	$query = $connect->prepare("update stock_barang set awal=?,akhir=?,hpp_awal=?,hpp_akhir=?,nilai_akhir=? where kdbarang=? and periode=?");
			// 	$query->bind_param('iiiiiss', $stockblnsebelumnya, $stockakhir, $hpp_awal, $hpp_akhir, $nilai_akhir, $kdbarang, $periode);
			// 	if ($query->execute()) {
			// 		mysqli_query($connect, "update stock_barang set nilai_awal=awal*hpp_awal where kdbarang='$kdbarang' and periode='$periode'");
			// 	} else {
			// 		echo 'Gagal update';
			// 	}
			// }

			$percent = round($i / $jumrec * 100, 0) . "%";
			// Javascript for updating the progress bar and information
			echo '<script language="javascript">
			document.getElementById("progress1").innerHTML="<div style=\"width:' . $percent . ';background-color:#ddd;\">&nbsp;</div>";
			document.getElementById("information1").innerHTML="' . $percent . ' row(s) processed (2/2).";
			</script>';
			// This is for the buffer achieve the minimum size in order to flush data
			// 		echo str_repeat(' ', 1024 * 64);
			// Send output to browser immediately
			ob_flush();
			flush();
			// Sleep one second so we can see the delay
			sleep(0);
			$i++;
		// } //test
	}
	echo 'Proses perhitungan stock awal dan akhir selesai<br>';

	$proses_bulan_berikutnya = 0;
	if ($proses_bulan_berikutnya > 0) {
		echo 'Proses akhir ...';
		//Bentuk stock bulan berikutnya pada saat transaksi
		$bulan1 = substr('0' . $_GET['bulan1'], -2);
		$tahun1 = $_GET['tahun1'];
		$periodeberikut = $tahun1 . $bulan1;
		$query_stock_barang = mysqli_query($connect, "select kdbarang,akhir,hpp_akhir,nilai_akhir from stock_barang where periode='$periode' order by kdbarang");
		while ($db = mysqli_fetch_assoc($query_stock_barang)) {
			$kdbarang = $db['kdbarang'];
			$akhir = $db['akhir'];
			$hpp_akhir = $db['hpp_akhir'];
			$nilai_akhir = $db['nilai_akhir'];
			$qry = mysqli_query($connect, "select kdbarang from stock_barang where kdbarang='$kdbarang' and periode='$periodeberikut'");
			$rec = mysqli_num_rows($qry);
			if ($rec == 0) {
				mysqli_query($connect, "insert into stock_barang (periode,kdbarang,awal,hpp_awal,hpp_akhir,nilai_awal) values ('$periodeberikut','$kdbarang','$akhir','$hpp_akhir','$hpp_akhir','$nilai_akhir')");
			}
			mysqli_query($connect, "update stock_barang set awal='$akhir',akhir=awal+masuk-keluar,hpp_awal='$hpp_akhir',hpp_akhir='$hpp_akhir',
		nilai_awal='$nilai_akhir',nilai_akhir=akhir*hpp_akhir where kdbarang='$kdbarang' and periode='$periodeberikut'");
		}
	}

	//Update HPP
	$queryupdate = mysqli_query($connect, "select * from proses_hpp");
	while ($db = mysqli_fetch_assoc($queryupdate)) {
		$nodokumen = $db['nodokumen'];
		$hpp = $db['hpp_berjalan'];
		$kdbarang = $db['kdbarang'];
		$dok = substr($nodokumen, 0, 2);
		switch ($dok) {
			case "BE":
				$query = $connect->prepare("update belid set hpp=? where nobeli=? and kdbarang=?");
				break;
			case "TB":
				$query = $connect->prepare("update terimad set hpp=? where noterima=? and kdbarang=?");
				break;
			case "JL":
				$query = $connect->prepare("update juald set hpp=? where nojual=? and kdbarang=?");
				break;
			default:
				$query = $connect->prepare("update keluard set hpp=? where nokeluar=? and kdbarang=?");
		}
		$query->bind_param('iss', $hpp, $nodokumen, $kdbarang);
		if ($query->execute() <= 0) {
			$gagal = 1;
			exit();
		}
	}

	if ($gagal == 0) { //and mysqli_affected_rows($connect)>0
		echo 'Proses closing HPP selesai<br>';
		$bulan1 = substr('0' . $_GET['id'], -2);
		$tahun1 = $_GET['tahun1'];
		$nbulan1 = $_GET['bulan1'];
		$ntahun1 = $_GET['tahun1'];
		mysqli_query($connect, "update saplikasi set closing_hpp='$periode',bulan='$nbulan1',tahun='$ntahun1'  where aktif='Y'");
		// if($query->execute()) {
		// }else{
		// 	echo 'Gagal update';
		// }
		$username = $_GET['username'];
		$bulan = substr('0' . $_GET['id'], -2);
		$tahun = $_GET['tahun'];
		$periode = $tahun . $bulan;
		$user_closing = $_GET['username'];
		$status = "Y";
		mysqli_query($connect, "insert into close_hpp (periode,tgl_closing,user_closing,status,user) values ('$periode','$tgl_closing','$user_closing','$status','$username')");

		//Create History
		$tanggal = date('Y-m-d');
		$datetime = date('Y-m-d H:i:s');
		$dokumen = $periode;
		$form = 'Closing Bulanan';
		$status = 'Closing';
		$catatan = '';
		$username = $username;
		$history = $connect->prepare("insert into hisuser (tanggal,dokumen,form,status,user,catatan,datetime) values (?,?,?,?,?,?,?)");
		$history->bind_param('sssssss', $tanggal, $dokumen, $form, $status, $username, $catatan, $datetime);
		$history->execute();

	?>
    <script>
        swal({
            title: "Closing HPP Berhasil ",
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
            title: "Closing HPP Gagal ",
            text: "",
            icon: "error"
        }).then(function() {
            window.location.href = 'closing_hpp';
        });
    </script>
    <?php
	}
	?>

    <script>
        // Loading Page
        var myVar;

        function myFunction() {
            myVar = setTimeout(showPage, 500);
        }

        function showPage() {
            document.getElementById("loader").style.display = "block";
            // document.getElementById("myDiv").style.display = "block";
        }
    </script>
</body>
