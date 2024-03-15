@include('home.config')

<?php
$connect = session('connect');
$nm_perusahaan = $saplikasi->nm_perusahaan;
$alamat_perusahaan = $saplikasi->alamat;
$telp_perusahaan = $saplikasi->telp;
$no = 1;

date_default_timezone_set('Asia/Jakarta');
$noopname = $noopname;
$row = mysqli_fetch_assoc(mysqli_query($connect, "select * from opnameh where noopname='$noopname'"));
$tglopname = $row['tglopname'];
$semuaperiode = $semuaperiode;
$tanggalind1 = date('d-m-Y', strtotime($tanggal1));
$tanggalind2 = date('d-m-Y', strtotime($tanggal2));
$tgl1 = date('Y-m-d', strtotime($tanggal1));
$tgl2 = date('Y-m-d', strtotime($tanggal2));
$tanggal1 = $tanggal1;
$tanggal2 = $tanggal2;

$rekapitulasi = $rekapitulasi; //rincian/rekap
// $pilihan = $_POST['pilihan']; //perbarang/all
$kdbarang = $kdbarang;
$nmbarang = $nmbarang;
// echo $bentuk,$pilihan;

if ($semuaperiode != 'Y') {
    $tanggal = $tanggal1 . ' s/d ' . $tanggal2;
} else {
    $tanggal = 'Semua Periode ';
}

?>
@include('report.judulreport')
<?php
echo 'Tanggal : ' . $tanggal . '</font>';

$barangkosong = isset($_POST['barangkosong']) ? 'barangkosong' : '';

echo '<style>
    td { border: 0.5px solid grey; margin: 5px;}
    th { border: 0.5px solid grey; font-weight:normal;}
    body { font-family: comic sans ms;}
   </style>';

if ($semuabarang != 'Y') {
    echo 'Barang : ' . $kdbarang . ' - ' . $nmbarang;
} else {
    $kdbarang = '';
}

echo '</font>
  <font size="1" face="comic sans ms"><br>';

if ($semuaperiode == 'Y') {
    $tanggal = 'Semua Periode';
} else {
    $tanggal = $tanggalind1 . ' s/d ' . $tanggalind2;
}
if ($rekapitulasi == 'Y') {
    if ($semuabarang != 'Y') {
        //Rekapitulasi perbarang
        $nmlaporan = 'LAPORAN REKAPITULASI STOCK BARANG ' . $kdbarang . ' (' . $nmbarang . ')';
        $text = "select kode,nama,hpp from tbbarang where kode='$kdbarang' order by kode";
    } else {
        //Rekapitulasi semua barang
        $nmlaporan = 'LAPORAN REKAPITULASI STOCK BARANG (SEMUA BARANG)';
        $text = 'select kode,nama,hpp from tbbarang order by kode';
    }
    $queryh = mysqli_query($connect, $text);
} else {
    if ($semuabarang != 'Y') {
        $nmlaporan = 'LAPORAN STOCK BARANG (PERBARANG)';
        $queryh = mysqli_query($connect, "select * from tbbarang where kode='$kdbarang' order by kode");
    } else {
        $nmlaporan = 'LAPORAN STOCK BARANG (SEMUA BARANG)';
        $queryh = mysqli_query($connect, 'select * from tbbarang order by kode');
    }
}

if ($rekapitulasi == 'Y') {
    echo '<table table-layout="fixed"; cellpadding="2"; cellspacing="0"; style=font-size:11px; class="table table-striped table table-bordered;">
  <tr>
  <th width="40px" height="20"><font size="1" color="black">NO.</th>
  <th width="150px" ><font size="1" color="black">KODE BARANG</th>
  <th width="450px"><font size="1" color="black">NAMA BARANG</th>
  <th width="60px"><font size="1" color="black">STOCK<br>AWAL</th>
  <th width="60px"><font size="1" color="black">MASUK</th>
  <th width="60px"><font size="1" color="black">KELUAR</th>
  <th width="60px"><font size="1" color="black">STOCK<br>AKHIR</th>';
    // <th width="70px"><font size="1" color="black">HPP</th>
    // 		<th width="80px"><font size="1" color="black">NILAI<br>STOCK</th>
    $jumawal = 0;
    $jummasuk = 0;
    $jumkeluar = 0;
    $jumakhir = 0;
    $jumnilai = 0;
    $no = 0;
    while ($row = mysqli_fetch_assoc($queryh)) {
        $kode = $row['kode'];
        $nama = $row['nama'];
        $stock = mysqli_fetch_assoc(mysqli_query($connect, "select qty from opnamed where kdbarang='$kode' and noopname='$noopname'"));
        if (isset($stock['qty'])) {
            $awal = $stock['qty'];
        } else {
            $awal = 0;
        }
        $awal1 = number_format($awal, 2, '.', ',');
        if ($semuaperiode == 'Y') {
            $querybeli = "select sum(qty) as qtybeli from belid where kdbarang='$kode' and tglbeli>'$tglopname' and proses='Y'";
            $rowbeli = mysqli_fetch_assoc(mysqli_query($connect, $querybeli));
            $queryterima = "select sum(qty) as qtyterima from terimad where kdbarang='$kode' and tglterima>'$tglopname' and proses='Y'";
            $rowterima = mysqli_fetch_assoc(mysqli_query($connect, $queryterima));
            $masuk = intval($rowbeli['qtybeli']) + intval($rowterima['qtyterima']);
            $masuk1 = number_format($masuk, 2, '.', ',');
            $queryjual = "select sum(qty) as qtyjual from juald where kdbarang='$kode' and tgljual>'$tglopname' and proses='Y'";
            $rowjual = mysqli_fetch_assoc(mysqli_query($connect, $queryjual));
            $querykeluar = "select sum(qty) as qtykeluar from keluard where kdbarang='$kode' and tglkeluar>'$tglopname' and proses='Y'";
            $rowkeluar = mysqli_fetch_assoc(mysqli_query($connect, $querykeluar));
        } else {
            $querybeli = "select sum(qty) as qtybeli from belid where kdbarang='$kode' and (tglbeli>'$tglopname' and tglbeli<='$tgl2') and proses='Y'";
            $rowbeli = mysqli_fetch_assoc(mysqli_query($connect, $querybeli));
            $queryterima = "select sum(qty) as qtyterima from terimad where kdbarang='$kode' and (tglterima>'$tglopname' and tglterima<='$tgl2') and proses='Y'";
            $rowterima = mysqli_fetch_assoc(mysqli_query($connect, $queryterima));
            $masuk = intval($rowbeli['qtybeli']) + intval($rowterima['qtyterima']);
            $masuk1 = number_format($masuk, 2, '.', ',');
            $queryjual = "select sum(qty) as qtyjual from juald where kdbarang='$kode' and (tgljual>'$tglopname' and tgljual<='$tgl2') and proses='Y'";
            $rowjual = mysqli_fetch_assoc(mysqli_query($connect, $queryjual));
            $querykeluar = "select sum(qty) as qtykeluar from keluard where kdbarang='$kode' and (tglkeluar>'$tglopname' and tglkeluar<='$tgl2') and proses='Y'";
            $rowkeluar = mysqli_fetch_assoc(mysqli_query($connect, $querykeluar));
        }
        $keluar = intval($rowjual['qtyjual']) + intval($rowkeluar['qtykeluar']);
        $keluar1 = number_format($keluar, 2, '.', ',');
        $akhir = $awal + $masuk - $keluar;
        $akhir1 = number_format($akhir, 2, '.', ',');
        $hpp = number_format($row['hpp'], 0, '.', ',');
        $nilai = $akhir * intval($row['hpp']);
        $nilai1 = number_format($nilai, 0, '.', ',');
        //echo $awal1.'  '.$masuk1.'  '.$awal1.'<br>';
        if ($barangkosong != '') {
            if ($akhir <= 0) {
                $no++;
                echo '<tr>
            <td width="30px"  align="center">' .
                    $no .
                    '</td>
            <td >' .
                    '' .
                    $kode .
                    '</td>
            <td >' .
                    $nama .
                    '</td>
            <td  align="right">' .
                    $awal .
                    '</td>
            <td  align="right">' .
                    $masuk1 .
                    '</td>
            <td  align="right">' .
                    $keluar1 .
                    '</td>
            <td  align="right">' .
                    $akhir1 .
                    '</td>
            </tr>';
            }
        } else {
            $no++;
            echo '<tr>
            <td width="30px"  align="center">' .
                $no .
                '</td>
            <td >' .
                $kode .
                '</td>
            <td >' .
                $nama .
                '</td>
            <td  align="right">' .
                $awal .
                '</td>
            <td  align="right">' .
                $masuk1 .
                '</td>
            <td  align="right">' .
                $keluar1 .
                '</td>
            <td  align="right">' .
                $akhir1 .
                '</td>
            </tr>';
        }
        // <td  align="right">' . $hpp . '</td>
        //     		<td  align="right">' . $nilai1 . '</td>
        $jumawal = $jumawal + $awal;
        $jummasuk = $jummasuk + $masuk;
        $jumkeluar = $jumkeluar + $keluar;
        $jumakhir = $jumakhir + $akhir;
        $jumnilai = $jumnilai + $nilai;
    }
    $jumawal = number_format($jumawal, 2, '.', ',');
    $jummasuk = number_format($jummasuk, 2, '.', ',');
    $jumkeluar = number_format($jumkeluar, 2, '.', ',');
    $jumakhir = number_format($jumakhir, 2, '.', ',');
    $jumnilai = number_format($jumnilai, 0, '.', ',');
    // echo '<tr>
    // 	<td width="30px" height="20px" colspan="3">' . 'Total ' . '</td>
    // 	<td width="30px" height="20px" align="right">' . $jumawal . '</td>
    // 	<td width="30px" height="20px" align="right">' . $jummasuk . '</td>
    // 	<td width="30px" height="20px" align="right">' . $jumkeluar . '</td>
    // 	<td width="30px" height="20px" align="right">' . $jumakhir . '</td>
    // 	</tr>';
} else {
    //Rincian

    echo '<table table-layout="fixed"; cellpadding="2"; cellspacing="0"; style=font-size:11px; class="table table-striped table table-bordered;">
    <tr>
    <th width="30px" height="20"><font size="1" color="black">NO.</th>
    <th width="110px" ><font size="1" color="black">NO. DOKUMEN</th>
    <th width="70px"><font size="1" color="black">TANGGAL</th>
    <th width="100px"><font size="1" color="black">MASUK</th>
    <th width="100px"><font size="1" color="black">KELUAR</th>
    <th width="100px"><font size="1" color="black">SALDO</th>';
    while ($row = mysqli_fetch_assoc($queryh)) {
        $jumqtymasuk = 0;
        $jumqtykeluar = 0;
        $kdbarang = $row['kode'];
        $barang = $row['kode'] . ' (' . $row['nama'] . ')';
        $stock = mysqli_fetch_assoc(mysqli_query($connect, "select qty from opnamed where kdbarang='$kdbarang' and noopname='$noopname'"));
        if (isset($stock['qty'])) {
            $stockawal = $stock['qty'];
        } else {
            $stockawal = 0;
        }
        $stockawalf = number_format($stockawal, 2, '.', ',');
        $stockakhir = $stockawal;
        $saldostock = $stockawal;
        // echo $cari . '' . $stockakhir;
        echo '<tr>
    <td width="30px" height="20px" align="center">' .
            $no .
            '</td>
    <td width="80px" height="20px" colspan="4">' .
            $barang .
            '</td>
    <td height="20px" align="right">' .
            $stockawalf .
            '</td>
    </tr>';

        mysqli_query($connect, 'CREATE TEMPORARY TABLE temp_stock (nodokumen varchar(20),tanggal date, qtymasuk int(10.2) default 0,qtykeluar int(10.2) default 0)');

        //Beli
        $qry = "select tbbarang.kode,tbbarang.nama,belid.nobeli as nodokumen,belid.tglbeli as tanggal,belid.qty from tbbarang 
    inner join belid on tbbarang.kode=belid.kdbarang where tbbarang.kode='$kdbarang' and belid.proses='Y' and ";
        $qry .= ($semuaperiode == 'Y' ? "belid.tglbeli>'$tglopname'" : " (belid.tglbeli>'$tglopname' and belid.tglbeli<='$tgl2')") . ' order by tbbarang.kode';
        $queryd = mysqli_query($connect, $qry);
        while ($rowd = mysqli_fetch_assoc($queryd)) {
            $nodokumen = $rowd['nodokumen'];
            $tanggal = $rowd['tanggal'];
            $qtymasuk = $rowd['qty'];
            $qtykeluar = 0;
            mysqli_query($connect, "insert into temp_stock (nodokumen,tanggal,qtymasuk,qtykeluar) values ('$nodokumen','$tanggal','$qtymasuk','$qtykeluar')");
        }
        //Terima
        $qry = "select tbbarang.kode,tbbarang.nama,terimad.noterima as nodokumen,terimad.tglterima as tanggal,terimad.qty from tbbarang 
    			inner join terimad on tbbarang.kode=terimad.kdbarang where tbbarang.kode='$kdbarang' and terimad.proses='Y' and ";
        $qry .= ($semuaperiode == 'Y' ? "terimad.tglterima>'$tglopname'" : " (terimad.tglterima>'$tglopname' and terimad.tglterima<='$tgl2')") . ' order by tbbarang.kode';
        $queryd = mysqli_query($connect, $qry);
        while ($rowd = mysqli_fetch_assoc($queryd)) {
            $nodokumen = $rowd['nodokumen'];
            $tanggal = $rowd['tanggal'];
            $qtymasuk = $rowd['qty'];
            $qtykeluar = 0;
            mysqli_query($connect, "insert into temp_stock (nodokumen,tanggal,qtymasuk,qtykeluar) values ('$nodokumen','$tanggal','$qtymasuk','$qtykeluar')");
        }
        //Jual
        $qry = "select tbbarang.kode,tbbarang.nama,juald.nojual as nodokumen,juald.tgljual as tanggal,juald.qty from tbbarang 
    			inner join juald on tbbarang.kode=juald.kdbarang where tbbarang.kode='$kdbarang' and juald.proses='Y' and ";
        $qry .= ($semuaperiode == 'Y' ? "juald.tgljual>'$tglopname'" : " (juald.tgljual>'$tglopname' and juald.tgljual<='$tgl2')") . ' order by tbbarang.kode';
        // echo '<br>qry : ' . $qry . '<br>';
        $queryd = mysqli_query($connect, $qry);
        while ($rowd = mysqli_fetch_assoc($queryd)) {
            $nodokumen = $rowd['nodokumen'];
            $tanggal = $rowd['tanggal'];
            $qtymasuk = 0;
            $qtykeluar = $rowd['qty'];
            mysqli_query($connect, "insert into temp_stock (nodokumen,tanggal,qtymasuk,qtykeluar) values ('$nodokumen','$tanggal','$qtymasuk','$qtykeluar')");
        }
        //Terima
        $qry = "select tbbarang.kode,tbbarang.nama,keluard.nokeluar as nodokumen,keluard.tglkeluar as tanggal,keluard.qty from tbbarang 
    			inner join keluard on tbbarang.kode=keluard.kdbarang where tbbarang.kode='$kdbarang' and keluard.proses='Y' and ";
        $qry .= ($semuaperiode == 'Y' ? "keluard.tglkeluar>'$tglopname'" : " (keluard.tglkeluar>'$tglopname' and keluard.tglkeluar<='$tgl2')") . ' order by tbbarang.kode';
        // echo '<br>qry : ' . $qry . '<br>';
        $queryd = mysqli_query($connect, $qry);
        while ($rowd = mysqli_fetch_assoc($queryd)) {
            $nodokumen = $rowd['nodokumen'];
            $tanggal = $rowd['tanggal'];
            $qtymasuk = 0;
            $qtykeluar = $rowd['qty'];
            mysqli_query($connect, "insert into temp_stock (nodokumen,tanggal,qtymasuk,qtykeluar) values ('$nodokumen','$tanggal','$qtymasuk','$qtykeluar')");
        }
        $queryd = mysqli_query($connect, 'select * from temp_stock order by tanggal');
        while ($rowd = mysqli_fetch_assoc($queryd)) {
            $qtymasukf = number_format($rowd['qtymasuk'], 2, '.', ',');
            $qtykeluarf = number_format($rowd['qtykeluar'], 2, '.', ',');
            $stockakhir = $stockakhir + $rowd['qtymasuk'] - $rowd['qtykeluar'];
            $jumqtymasuk = $jumqtymasuk + $rowd['qtymasuk'];
            $jumqtykeluar = $jumqtykeluar + $rowd['qtykeluar'];
            $stockakhirf = number_format($stockakhir, 2, '.', ',');
            echo '<tr>
    <td height="20px">' .
                '' .
                '</td>
    <td height="20px">' .
                $rowd['nodokumen'] .
                '</td>
    <td height="20px">' .
                $rowd['tanggal'] .
                '</td>
    <td height="20px" align="right">' .
                $qtymasukf .
                '</td>
    <td height="20px" align="right">' .
                $qtykeluarf .
                '</td>
    <td height="20px" align="right">' .
                $stockakhirf .
                '</td>
    </tr>';
        }
        mysqli_query($connect, 'drop table temp_stock');
        $no++;
        $jumqtymasukf = number_format($jumqtymasuk, 2, '.', ',');
        $jumqtykeluarf = number_format($jumqtykeluar, 2, '.', ',');
        echo '<tr><td colspan="3" height="20px" align="right">' .
            'Total' .
            '</td>
    <td height="20px" align="right">' .
            $jumqtymasukf .
            '</td>
    <td height="20px" align="right">' .
            $jumqtykeluarf .
            '</td><td></td>';
        // <td height="20px" align="right">' . $stockakhirf . '</td></tr>';
    }
}
echo '</table><font size="1"><left>Tanggal cetak : ' . date('d-m-Y H:i:s a');
