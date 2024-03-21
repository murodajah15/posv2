@include('home.config')

<?php
$connect = session('connect');
$nm_perusahaan = $saplikasi->nm_perusahaan;
$alamat_perusahaan = $saplikasi->alamat;
$telp_perusahaan = $saplikasi->telp;

date_default_timezone_set('Asia/Jakarta');
$tgl1 = date('Y-m-d', strtotime($tanggal1));
$tgl2 = date('Y-m-d', strtotime($tanggal2));
$no = 1;

$tanggal = $semuaperiode == 'Y' ? 'Semua Periode' : $tanggal1 . ' s/d ' . $tanggal2;
$qry = "select * from kasir_keluarh where proses='Y'";
// $qry .= $kdsupplier == "" ? " " : " and kdcustomer='$kdsupplier'";
$qry .= $nmkasir == '' ? ' ' : " and user_input='$nmkasir'";
$qry .= $semuaperiode == 'Y' ? ' ' : " and (tglkwitansi>='$tgl1' and tglkwitansi<='$tgl2')";
$queryh = mysqli_query($connect, $qry);

$cek = mysqli_num_rows($queryh);
if (empty($cek)) {
    echo '<script>
        alert(\'Tidak Ada sesuai kriteria\')
                window.close()
    </script>';
}

if ($semuasupplier != 'Y') {
    echo 'supplier : ' . $kdsupplier . ' - ' . $nmsupplier;
} else {
    $kdsupplier = '';
}

?>
@include('report.judulreport')
<?php
echo 'Tanggal : ' . $tanggal . '</font>';

if ($semuasupplier == 'Y') {
    if ($groupingsupplier) {
        echo '<table table-layout="fixed"; cellpadding="2"; cellspacing="0"; style=font-size:11px; class="table table-striped table table-bordered;">
   <tr>
    <th width="30px" height="20"><font size="1" color="black">NO.</th>
    <th width="100px"><font size="1" color="black">NO. DOKUMEN</th>
    <th width="75px"><font size="1" color="black">TANGGAL</th>
    <th width="350px"><font size="1" color="black">SUPPLIER</th>
    <th width="90px"><font size="1" color="black">TOTAL</th>
   </tr>';
        $grandtotal = 0;
        while ($row = mysqli_fetch_assoc($queryh)) {
            echo '<tr>
   <td colspan="5" width="573px" height="35px" align="left">' .
                'No. Kwitansi : ' .
                $row['nokwitansi'] .
                ', Tanggal : ' .
                $row['tglkwitansi'] .
                ', Jenis : ' .
                $row['nmjnkeluar'] .
                ', Cara Bayar : ' .
                $row['carabayar'] .
                '</td>';
            $nokwitansi = $row['nokwitansi'];
            if ($semuaperiode == 'Y') {
                $tanggal = 'Semua Periode';
                $queryd = mysqli_query($connect, "select kasir_keluarh.nokwitansi,kasir_keluarh.tglkwitansi,kasir_keluarh.nmjnkeluar,kasir_keluard.nodokumen,kasir_keluard.tgldokumen,kasir_keluard.uang,kasir_keluard.kdsupplier,kasir_keluard.nmsupplier from kasir_keluarh inner join kasir_keluard on kasir_keluarh.nokwitansi=kasir_keluard.nokwitansi where kasir_keluarh.proses='Y' and kasir_keluarh.nokwitansi='$nokwitansi'");
            } else {
                $tanggal = $tanggal1 . ' s/d ' . $tanggal2;
                $queryd = mysqli_query($connect, "select kasir_keluarh.nokwitansi,kasir_keluarh.tglkwitansi,kasir_keluarh.nmjnkeluar,kasir_keluard.nmsupplier,kasir_keluard.nodokumen,kasir_keluard.tgldokumen,kasir_keluard.uang,kasir_keluard.kdsupplier,kasir_keluard.nmsupplier from kasir_keluarh inner join kasir_keluard on kasir_keluarh.nokwitansi=kasir_keluard.nokwitansi where kasir_keluarh.proses='Y' and (kasir_keluarh.tglkwitansi>='$tgl1' and kasir_keluarh.tglkwitansi<='$tgl2') and kasir_keluard.nokwitansi='$nokwitansi'");
            }
            $jumsubtotal = 0;
            while ($rowd = mysqli_fetch_assoc($queryd)) {
                //$subtotal_ppn = $rowd['subtotal'] - ($rowd['subtotal'] * ($row['ppn']/100));
                $uang = number_format($rowd['uang'], 0, '.', ',');
                $supplier = $rowd['kdsupplier'] . '-' . $rowd['nmsupplier'];
                $tgldokumen = $rowd['tgldokumen'];
                echo '<tr>
     <td width="30px"  align="center">' .
                    $no .
                    '</td>
     <td width="100px" >' .
                    $rowd['nodokumen'] .
                    '</td>
     <td width="50px"  align="center">' .
                    $tgldokumen .
                    '</td>
     <td width="250px" >' .
                    $supplier .
                    '</td>
     <td width="80px"  align="right">' .
                    $uang .
                    '</td>
    </tr>';
                $no++;
                $jumsubtotal = $jumsubtotal + $rowd['uang'];
                $grandtotal = $grandtotal + $rowd['uang'];
            }
            $total = number_format($jumsubtotal, 0, '.', ',');
            echo '<tr><td colspan="4" height="20px" align="left">' .
                'Total' .
                '</td>
   <td height="20px" align="right">' .
                $total .
                '</td>
  </tr>';
        }
        $grandtotal = number_format($grandtotal, 0, '.', ',');
        echo '<tr><td colspan="4" height="20px" align="left">' .
            'Grand Total' .
            '</td>
  <td height="20px" align="right">' .
            $grandtotal .
            '</td>
  </tr>';
        echo '</table><font size="1"><left>Tanggal cetak : ' . date('d-m-Y H:i:s a');
        echo '<br><br><table border="0">
    <tr>
    <th width="30px" height="50" valign="top"><font size="1" color="black">KASIR</th>
    <tr><td height="20px" width="120" align="center">' .
            $nmkasir .
            '</td>
   </tr></table>';
    } else {
        echo '<table table-layout="fixed"; cellpadding="2"; cellspacing="0"; style=font-size:11px; class="table table-striped table table-bordered;">
   <tr>
    <th width="30px" height="20"><font size="1" color="black">NO.</th>
        <th width="100px"><font size="1" color="black">NO. KWITANSI</th>
        <th width="75px"><font size="1" color="black">TANGGAL<br>KWITANSI</th>
    <th width="100px"><font size="1" color="black">NO. DOKUMEN</th>
    <th width="75px"><font size="1" color="black">TANGGAL<br>DOKUMEN</th>
        <th width="90px"><font size="1" color="black">CARA BAYAR</th>
    <th width="90px"><font size="1" color="black">TOTAL</th>
   </tr>';
        if ($semuaperiode == 'Y') {
            $tanggal = 'Semua Periode';
            $queryh = mysqli_query($connect, "select kasir_keluarh.carabayar,kasir_keluarh.nokwitansi,kasir_keluarh.tglkwitansi,kasir_keluarh.nmjnkeluar,kasir_keluard.nodokumen,kasir_keluard.tgldokumen,kasir_keluard.uang,kasir_keluard.kdsupplier,kasir_keluard.nmsupplier from kasir_keluarh inner join kasir_keluard on kasir_keluarh.nokwitansi=kasir_keluard.nokwitansi where kasir_keluarh.proses='Y' group by kasir_keluard.kdsupplier order by kasir_keluard.kdsupplier");
        } else {
            $tanggal = $tanggal1 . ' s/d ' . $tanggal2;
            $queryh = mysqli_query($connect, "select kasir_keluarh.carabayar,kasir_keluarh.nokwitansi,kasir_keluarh.tglkwitansi,kasir_keluarh.nmjnkeluar,kasir_keluard.nodokumen,kasir_keluard.tgldokumen,kasir_keluard.uang,kasir_keluard.kdsupplier,kasir_keluard.nmsupplier from kasir_keluarh inner join kasir_keluard on kasir_keluarh.nokwitansi=kasir_keluard.nokwitansi where kasir_keluarh.proses='Y' and (kasir_keluarh.tglkwitansi>='$tgl1' and kasir_keluarh.tglkwitansi<='$tgl2') group by kasir_keluard.kdsupplier order by kasir_keluard.kdsupplier");
        }
        $grandtotal = 0;
        $no = 0;
        while ($rowh = mysqli_fetch_assoc($queryh)) {
            $kdsupplier = $rowh['kdsupplier'];
            if ($semuaperiode == 'Y') {
                $tanggal = 'Semua Periode';
                $queryd = mysqli_query($connect, "select kasir_keluarh.carabayar,kasir_keluarh.nokwitansi,kasir_keluarh.tglkwitansi,kasir_keluarh.nmjnkeluar,kasir_keluard.nodokumen,kasir_keluard.tgldokumen,kasir_keluard.uang,kasir_keluard.kdsupplier,kasir_keluard.nmsupplier from kasir_keluarh inner join kasir_keluard on kasir_keluarh.nokwitansi=kasir_keluard.nokwitansi where kasir_keluarh.proses='Y' and kasir_keluard.kdsupplier='$kdsupplier'");
            } else {
                $tanggal = $tanggal1 . ' s/d ' . $tanggal2;
                $queryd = mysqli_query($connect, "select kasir_keluarh.carabayar,kasir_keluarh.nokwitansi,kasir_keluarh.tglkwitansi,kasir_keluarh.nmjnkeluar,kasir_keluard.nodokumen,kasir_keluard.tgldokumen,kasir_keluard.uang,kasir_keluard.kdsupplier,kasir_keluard.nmsupplier from kasir_keluarh inner join kasir_keluard on kasir_keluarh.nokwitansi=kasir_keluard.nokwitansi where kasir_keluarh.proses='Y' and (kasir_keluarh.tglkwitansi>='$tgl1' and kasir_keluarh.tglkwitansi<='$tgl2') and kasir_keluard.kdsupplier='$kdsupplier'");
            }

            if (mysqli_num_rows($queryd) > 0) {
                $no++;
                echo '<tr>
        <td style="text-align:center;color:blue;">' .
                    $no .
                    '</td>
    <td colspan=6 width="100px" style="color:blue;">' .
                    $rowh['kdsupplier'] .
                    ' - ' .
                    $rowh['nmsupplier'] .
                    '</td>';
                $jumsubtotal = 0;
                $nno = 0;
                while ($rowd = mysqli_fetch_assoc($queryd)) {
                    $nno++;
                    $uang = number_format($rowd['uang'], 0, '.', ',');
                    $supplier = $rowd['kdsupplier'] . '-' . $rowd['nmsupplier'];
                    $tglkwitansi = $rowd['tglkwitansi'];
                    $tgldokumen = $rowd['tgldokumen'];
                    echo '<tr>
     <td width="30px"  align="center">' .
                        $nno .
                        '</td>
     <td width="100px" >' .
                        $rowd['nokwitansi'] .
                        '</td>
     <td width="75px" align="center">' .
                        $tglkwitansi .
                        '</td>
     <td width="100px" >' .
                        $rowd['nodokumen'] .
                        '</td>
     <td width="75px"  align="center">' .
                        $tgldokumen .
                        '</td>
     <td width="80px" >' .
                        $rowd['carabayar'] .
                        '</td>
     <td width="80px"  align="right">' .
                        $uang .
                        '</td>
    </tr>';
                    $jumsubtotal = $jumsubtotal + $rowd['uang'];
                    $grandtotal = $grandtotal + $rowd['uang'];
                }
            }
            $jumsubtotalf = number_format($jumsubtotal, 0, '.', ',');
            echo '<tr><td colspan="6" height="20px" align="left">' .
                'Total' .
                '</td>
  <td height="20px" align="right">' .
                $jumsubtotalf .
                '</td>
  </tr>';
        }
        if ($semuasupplier == 'Y') {
            $grandtotalf = number_format($grandtotal, 0, '.', ',');
            echo '<tr><td colspan="6" height="20px" align="left">' .
                'Grand Total' .
                '</td>
  <td height="20px" align="right">' .
                $grandtotalf .
                '</td>
  </tr>';
            echo '</table><font size="1"><left>Tanggal cetak : ' . date('d-m-Y H:i:s a');
        }
        echo '</table>';
    }
} else {
    //persupplier
    echo '<font size="2">Persupplier : ' . $kdsupplier . ' - ' . $nmsupplier . '</font>';
    echo '<table table-layout="fixed"; cellpadding="2"; cellspacing="0"; style=font-size:11px; class="table table-striped table table-bordered;">
   <tr>
    <th width="30px" height="20"><font size="1" color="black">NO.</th>
        <th width="100px"><font size="1" color="black">NO. KWITANSI</th>
        <th width="75px"><font size="1" color="black">TANGGAL<br>KWITANSI</th>
    <th width="100px"><font size="1" color="black">NO. DOKUMEN</th>
    <th width="75px"><font size="1" color="black">TANGGAL<br>DOKUMEN</th>
        <th width="90px"><font size="1" color="black">CARA BAYAR</th>
    <th width="90px"><font size="1" color="black">TOTAL</th>
   </tr>';
    $grandtotal = 0;
    while ($row = mysqli_fetch_assoc($queryh)) {
        $nokwitansi = $row['nokwitansi'];
        if ($semuaperiode == 'Y') {
            $tanggal = 'Semua Periode';
            $queryd = mysqli_query($connect, "select kasir_keluarh.carabayar,kasir_keluarh.nokwitansi,kasir_keluarh.tglkwitansi,kasir_keluarh.nmjnkeluar,kasir_keluard.nodokumen,kasir_keluard.tgldokumen,kasir_keluard.uang,kasir_keluard.kdsupplier,kasir_keluard.nmsupplier from kasir_keluarh inner join kasir_keluard on kasir_keluarh.nokwitansi=kasir_keluard.nokwitansi where kasir_keluarh.proses='Y' and kasir_keluarh.nokwitansi='$nokwitansi' and kasir_keluard.kdsupplier='$kdsupplier'");
        } else {
            $tanggal = $tanggal1 . ' s/d ' . $tanggal2;
            $queryd = mysqli_query($connect, "select kasir_keluarh.carabayar,kasir_keluarh.nokwitansi,kasir_keluarh.tglkwitansi,kasir_keluarh.nmjnkeluar,kasir_keluard.nodokumen,kasir_keluard.tgldokumen,kasir_keluard.uang,kasir_keluard.kdsupplier,kasir_keluard.nmsupplier from kasir_keluarh inner join kasir_keluard on kasir_keluarh.nokwitansi=kasir_keluard.nokwitansi where kasir_keluarh.proses='Y' and (kasir_keluarh.tglkwitansi>='$tgl1' and kasir_keluarh.tglkwitansi<='$tgl2') and kasir_keluard.nokwitansi='$nokwitansi' and kasir_keluard.kdsupplier='$kdsupplier'");
        }
        $jumsubtotal = 0;
        while ($rowd = mysqli_fetch_assoc($queryd)) {
            //$subtotal_ppn = $rowd['subtotal'] - ($rowd['subtotal'] * ($row['ppn']/100));
            $uang = number_format($rowd['uang'], 0, '.', ',');
            $supplier = $rowd['kdsupplier'] . '-' . $rowd['nmsupplier'];
            $tglkwitansi = $rowd['tglkwitansi'];
            $tgldokumen = $rowd['tgldokumen'];
            echo '<tr>
     <td width="30px"  align="center">' .
                $no .
                '</td>
     <td width="100px" >' .
                $rowd['nokwitansi'] .
                '</td>
     <td width="75px" align="center">' .
                $tglkwitansi .
                '</td>
     <td width="100px" >' .
                $rowd['nodokumen'] .
                '</td>
     <td width="50px" align="center">' .
                $tgldokumen .
                '</td>
     <td width="100px" >' .
                $rowd['carabayar'] .
                '</td>
     <td width="80px" align="right">' .
                $uang .
                '</td>
    </tr>';
            $no++;
            $jumsubtotal = $jumsubtotal + $rowd['uang'];
            $grandtotal = $grandtotal + $rowd['uang'];
        }
        // $total = number_format($jumsubtotal, 0, ".", ",");
        // echo '<tr><td colspan="4" height="20px" align="left">' . "Total" . '</td>
        // 	<td height="20px" align="right">' . $total . '</td>
        // </tr>';
    }
    $grandtotal = number_format($grandtotal, 0, '.', ',');
    echo '<tr><td colspan="6" height="20px" align="left">' .
        'Grand Total' .
        '</td>
  <td height="20px" align="right">' .
        $grandtotal .
        '</td>
  </tr>';
    echo '</table><font size="1"><left>Tanggal cetak : ' . date('d-m-Y H:i:s a');
}
