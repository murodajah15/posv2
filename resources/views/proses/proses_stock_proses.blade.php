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
    <div id="loader"></div>
    <script src="{{ asset('/') }}assets/dist/js/sweet-alert.min.js"></script>

    <center>
        <font size=15>Sedang proses ulang stock ..</font>
    </center>
    <?php
    $connect = session('connect');
    date_default_timezone_set('Asia/Jakarta');
    $aktif = 'Y';
    $noopname = $_GET['noopname'];
    
    $querytbabrang = mysqli_query($connect, 'select * from tbbarang order by kode');
    $jumrec = mysqli_num_rows($querytbabrang);
    $gagal = 0;
    $i = 1;
    ?>
    <div id="progress" style="width:500px; border:1px solid #ccc;"></div>
    <div id="information"></div>
    <?php
    echo 'Proses stock_barang ...<br>';
    $queryopnameh = mysqli_query($connect, "select * from opnameh where noopname='$noopname'");
    $k = mysqli_fetch_assoc($queryopnameh);
    $tglopname = $k['tglopname'];
    while ($databarang = mysqli_fetch_assoc($querytbabrang)) {
        $kdbarang = $databarang['kode'];
        // if ($kdbarang == 'SKF000005') {
        $qtyawal = 0;
        mysqli_query($connect, "update tbbarang set stock=0 where kode='$kdbarang'");
        $queryopnamed = mysqli_query($connect, "select * from opnamed where kdbarang='$kdbarang' and noopname='$noopname'");
        $k = mysqli_fetch_assoc($queryopnamed);
        if (isset($k)) {
            $qtyawal = $k['qty'];
        }
        $querybeli = mysqli_query($connect, "select sum(qty) as qtybeli from belid where kdbarang='$kdbarang' and tglbeli>'$tglopname' and proses='Y'");
        $k = mysqli_fetch_assoc($querybeli);
        $totalqtybeli = $k['qtybeli'];
        $queryterima = mysqli_query($connect, "select sum(qty) as qtyterima from terimad where kdbarang='$kdbarang' and tglterima>'$tglopname' and proses='Y'");
        $k = mysqli_fetch_assoc($queryterima);
        $totalqtyterima = $k['qtyterima'];
        $queryjual = mysqli_query($connect, "select sum(qty) as qtyjual from juald where kdbarang='$kdbarang' and tgljual>'$tglopname' and proses='Y'");
        $k = mysqli_fetch_assoc($queryjual);
        $totalqtyjual = $k['qtyjual'];
        $querykeluar = mysqli_query($connect, "select sum(qty) as qtykeluar from keluard where kdbarang='$kdbarang' and tglkeluar>'$tglopname' and proses='Y'");
        $k = mysqli_fetch_assoc($querykeluar);
        $totalqtykeluar = $k['qtykeluar'];
        $qtyakhir = $qtyawal + $totalqtybeli + $totalqtyterima - $totalqtyjual - $totalqtykeluar;
        mysqli_query($connect, "update tbbarang set stock='$qtyakhir' where kode='$kdbarang'");
        // }
        $percent = round(($i / $jumrec) * 100, 0) . '%';
        // Javascript for updating the progress bar and information
        echo '<script language="javascript">
                                                        		  document.getElementById("progress").innerHTML="<div style=\"width:' .
            $percent .
            ';background-color:#ddd;\">&nbsp;</div>";
                                                        		  document.getElementById("information").innerHTML="' .
            $percent .
            ' row(s) processed.";
                                                        		  </script>';
        // This is for the buffer achieve the minimum size in order to flush data
        echo str_repeat(' ', 1024 * 64);
        // Send output to browser immediately
        flush();
        // Sleep one second so we can see the delay
        sleep(0);
        $i++;
    }
    ?>
    <script>
        swal({
            title: "Proses ulang stock selesai ",
            text: "",
            icon: "success"
        }).then(function() {
            window.location.href = 'proses_stock';
        });
    </script>
    <?php
    ?>
</body>
