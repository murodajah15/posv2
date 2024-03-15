@include('home.config')

<?php
$connect = session('connect');
$nm_perusahaan = $saplikasi->nm_perusahaan;
$alamat_perusahaan = $saplikasi->alamat;
$telp_perusahaan = $saplikasi->telp;
$no = 1;

date_default_timezone_set('Asia/Jakarta');
$tgl1 = date('Y-m-d', strtotime($tanggal1));
$tgl2 = date('Y-m-d', strtotime($tanggal2));
$no = 1;

if ($semuaperiode != 'Y') {
    $tanggal = $tanggal1 . ' s/d ' . $tanggal2;
} else {
    $tanggal = 'Semua Periode';
}

?>
@include('report.judulreport')
<?php
echo 'Tanggal : ' . $tanggal . '</font>';

if ($semuasupplier != 'Y') {
    echo 'Supplier : ' . $kdsupplier . ' - ' . $nmsupplier;
} else {
    $kdsupplier = '';
}
if ($semuabarang != 'Y') {
    echo 'Barang1 : ' . $kdbarang . ' - ' . $nmbarang;
} else {
    $kdbarang = '';
}

echo '</font>
  <font size="1" face="comic sans ms"><br>';
if ($outstanding == 'Y') {
    $tanggal = 'Outstanding s/d ' . $tanggal2;
    if ($semuasupplier == 'Y') {
        $queryh = mysqli_query($connect, "select * from poh where proses='Y' and terima='N' order by tglpo");
    } else {
        $queryh = mysqli_query($connect, "select * from poh where proses='Y' and terima='N' and kdsupplier='$kdsupplier' order by tglpo");
    }
} else {
    if ($semuaperiode == 'Y') {
        $tanggal = 'Semua Periode';
        if ($semuasupplier == 'Y') {
            $queryh = mysqli_query($connect, "select * from poh where proses='Y' order by tglpo");
        } else {
            $queryh = mysqli_query($connect, "select * from poh where proses='Y' and kdsupplier='$kdsupplier' order by tglpo");
        }
    } else {
        $tanggal = $tanggal1 . ' s/d ' . $tanggal2;
        if ($semuasupplier == 'Y') {
            $queryh = mysqli_query($connect, "select * from poh where proses='Y' and (tglpo>='$tgl1' and tglpo<='$tgl2') order by tglpo");
        } else {
            $queryh = mysqli_query($connect, "select * from poh where proses='Y' and (tglpo>='$tgl1' and tglpo<='$tgl2') and kdsupplier='$kdsupplier' order by tglpo");
        }
    }
}

if ($rincian == 'Y' or $rincian == 'N' and $semuabarang == 'N') {
    if ($semuabarang == 'N') {
        echo '<font size="2">Perbarang : ' . $kdbarang . ' - ' . $nmbarang . '</font>';
        echo '<table table-layout="fixed"; cellpadding="2"; cellspacing="0"; style=font-size:11px; class="table table-striped table table-bordered;">
   <tr>
    <th width="30px" height="20"><font size="1" color="black">NO.</th>
        <th width="90px"><font size="1" color="black">NO. PO</th>
    <th width="75px"><font size="1" color="black">TGL. PO</th>
    <th width="90px"><font size="1" color="black">KODE SUPPLIER</th>
    <th width="200px"><font size="1" color="black">NAMA SUPPLIER</th>
    <th width="50px"><font size="1" color="black">QTY</th>
    <th width="60px"><font size="1" color="black">HARGA</th>
    <th width="60px"><font size="1" color="black">SUBTOTAL</th>
    <th width="60px"><font size="1" color="black">DISC.</th>
    <th width="60px"><font size="1" color="black">PPN</th>
    <th width="70px"><font size="1" color="black">TOTAL</th>
   </tr>';
        $grandqty = 0;
        $grandtotal = 0;
        $grandpo = 0;
        $grandppn = 0;
        $granddiscount = 0;
        $no = 1;
        while ($row = mysqli_fetch_assoc($queryh)) {
            $nopo = $row['nopo'];
            if ($semuaperiode == 'Y') {
                $tanggal = 'Semua Periode';
                $queryd = mysqli_query($connect, "select poh.nopo,poh.tglpo,poh.noreferensi,poh.kdsupplier,poh.nmsupplier,pod.kdbarang,pod.nmbarang,pod.qty,pod.harga,pod.discount,pod.subtotal from poh inner join pod on poh.nopo=pod.nopo where poh.proses='Y' and poh.nopo='$nopo' and pod.kdbarang='$kdbarang'");
            } else {
                $tanggal = $tanggal1 . ' s/d ' . $tanggal2;
                $queryd = mysqli_query($connect, "select poh.nopo,poh.tglpo,poh.noreferensi,poh.kdsupplier,poh.nmsupplier,pod.kdbarang,pod.nmbarang,pod.qty,pod.harga,pod.discount,pod.subtotal from poh inner join pod on poh.nopo=pod.nopo where poh.proses='Y' and (poh.tglpo>='$tgl1' and poh.tglpo<='$tgl2') and pod.nopo='$nopo' and pod.kdbarang='$kdbarang'");
            }
            $subtotalpo = 0;
            $subtotalppn = 0;
            $subtotaldiscount = 0;
            $jumsubtotal = 0;
            $rowdata = mysqli_num_rows($queryd);
            if ($rowdata > 0) {
                while ($rowd = mysqli_fetch_assoc($queryd)) {
                    //$subtotal_ppn = $rowd['subtotal'] - ($rowd['subtotal'] * ($row['ppn']/100));
                    $qty = number_format($rowd['qty'], 2, '.', ',');
                    $harga = number_format($rowd['harga'], 0, '.', ',');
                    $ndiscount = $rowd['qty'] * $rowd['harga'] * ($rowd['discount'] / 100);
                    $discount = number_format($ndiscount, 0, '.', ',');
                    $nppn = ($rowd['qty'] * $rowd['harga'] - $ndiscount) * ($row['ppn'] / 100);
                    $ppn = number_format($nppn, 0, '.', ',');
                    $harga = number_format($rowd['harga'], 0, '.', ',');
                    $nsubtotal = $rowd['qty'] * $rowd['harga'] - $ndiscount + $nppn;
                    $subtotal = number_format($nsubtotal, 0, '.', ',');
                    $npo = $rowd['qty'] * $rowd['harga'];
                    $po = number_format($npo, 0, '.', ',');
                    $nqty = $rowd['qty'];
                    echo '<tr>
     <td align="right">' .
                        $no .
                        '</td>
     <td width="90px" >' .
                        $rowd['nopo'] .
                        '</td>
     <td width="75px" >' .
                        $rowd['tglpo'] .
                        '</td>
     <td width="90px" >' .
                        $rowd['kdsupplier'] .
                        '</td>
     <td width="200px" >' .
                        $rowd['nmsupplier'] .
                        '</td>
     <td width="50px"  align="right">' .
                        $qty .
                        '</td>
     <td width="60px"  align="right">' .
                        $harga .
                        '</td>
     <td width="60px"  align="right">' .
                        $po .
                        '</td>
     <td width="40px"  align="right">' .
                        $discount .
                        '</td>
     <td width="40px"  align="right">' .
                        $ppn .
                        '</td>
     <td width="70px"  align="right">' .
                        $subtotal .
                        '</td>
    </tr>';
                    $grandqty = $grandqty + $nqty;
                    $grandpo = $grandpo + $npo;
                    $grandppn = $grandppn + $nppn;
                    $granddiscount = $granddiscount + $ndiscount;
                    $subtotalpo = $subtotalpo + $npo;
                    $subtotalppn = $subtotalppn + $nppn;
                    $subtotaldiscount = $subtotaldiscount + $ndiscount;
                    $grandtotal = $grandtotal + $nsubtotal;
                    $jumsubtotal = $jumsubtotal + $nsubtotal;
                }
                $no++;
            }
        }
        $grandqty = number_format($grandqty, 2, '.', ',');
        $grandtotal = number_format($grandtotal, 0, '.', ',');
        $grandpo = number_format($grandpo, 0, '.', ',');
        $grandppn = number_format($grandppn, 0, '.', ',');
        $granddiscount = number_format($granddiscount, 0, '.', ',');
        echo '<tr><td colspan="5" height="20px" align="left">' .
            'Grand Total' .
            '</td>
    <td height="20px" align="right">' .
            $grandqty .
            '</td>
    <td></td>
  <td height="20px" align="right">' .
            $grandpo .
            '</td>
  <td height="20px" align="right">' .
            $granddiscount .
            '</td>
  <td height="20px" align="right">' .
            $grandppn .
            '</td>
  <td height="20px" align="right">' .
            $grandtotal .
            '</td>
  </tr></table>';
        echo '</table>';
    } else {
        if ($groupingsupplier == 'Y') {
            echo '<table table-layout="fixed"; cellpadding="2"; cellspacing="0"; style=font-size:11px; class="table table-striped table table-bordered;">
   <tr>
    <th width="30px" height="20"><font size="1" color="black">NO.</th>
    <th width="95px"><font size="1" color="black">NO. PO</th>
        <th width="80px"><font size="1" color="black">TANGGAL<br>PO</th>
    <th width="95px"><font size="1" color="black">CARA BAYAR</th>
        <th width="90px"><font size="1" color="black">KODE BARANG</th>
    <th width="300px"><font size="1" color="black">NAMA BARANG</th>
    <th width="50px"><font size="1" color="black">QTY</th>
    <th width="60px"><font size="1" color="black">HARGA</th>
    <th width="60px"><font size="1" color="black">SUBTOTAL</th>
    <th width="60px"><font size="1" color="black">DISC.</th>
    <th width="60px"><font size="1" color="black">PPN</th>
    <th width="70px"><font size="1" color="black">TOTAL</th>
   </tr>';
            if ($semuaperiode == 'Y') {
                $tanggal = 'Semua Periode';
                if ($semuasupplier == 'Y') {
                    $queryh = mysqli_query($connect, "select * from poh where proses='Y' group by kdsupplier order by kdsupplier");
                } else {
                    $queryh = mysqli_query($connect, "select * from poh where proses='Y' and kdsupplier='$kdsupplier' group by kdsupplier order by kdsupplier");
                }
            } else {
                $tanggal = $tanggal1 . ' s/d ' . $tanggal2;
                if ($semuasupplier == 'Y') {
                    $queryh = mysqli_query($connect, "select * from poh where proses='Y' and (tglpo>='$tgl1' and tglpo<='$tgl2') group by kdsupplier order by kdsupplier");
                } else {
                    $queryh = mysqli_query($connect, "select * from poh where proses='Y' and (tglpo>='$tgl1' and tglpo<='$tgl2') and kdsupplier='$kdsupplier' group by kdsupplier order by kdsupplier");
                }
                //echo $tgl1.'  '.$tgl2;
            }
            $no = 1;
            $gtsubtotal = 0;
            $gtppn = 0;
            $gttotal = 0;
            $grandpo = 0;
            $grandppn = 0;
            $granddiscount = 0;
            $grandtotal = 0;
            $grandqty = 0;
            while ($row = mysqli_fetch_assoc($queryh)) {
                $kdsupplier = $row['kdsupplier'];
                $supplier = $row['kdsupplier'] . ' - ' . $row['nmsupplier'];
                echo '<tr>
     <td style="text-align:center;color:blue;">' .
                    $no .
                    '</td>
     <td colspan=11 style="color:blue;">' .
                    $supplier .
                    '</td>
    </tr>';
                $nopo = $row['nopo'];
                if ($semuaperiode == 'Y') {
                    $tanggal = 'Semua Periode';
                    $queryd = mysqli_query($connect, "select * from poh where proses='Y' and kdsupplier='$kdsupplier' order by tglpo");
                    // $queryd = mysqli_query($connect, "select poh.nopo,poh.tglpo,poh.noreferensi,poh.nmsupplier,pod.kdbarang,pod.nmbarang,pod.qty,pod.harga,pod.discount,pod.subtotal from poh inner join pod on poh.nopo=pod.nopo where poh.proses='Y' and poh.kdsupplier='$kdsupplier'");
                } else {
                    $tanggal = $tanggal1 . ' s/d ' . $tanggal2;
                    $queryd = mysqli_query($connect, "select * from poh where proses='Y' and (tglpo>='$tgl1' and tglpo<='$tgl2') and kdsupplier='$kdsupplier' order by tglpo");
                    // $queryd = mysqli_query($connect, "select poh.nopo,poh.tglpo,poh.noreferensi,poh.nmsupplier,pod.kdbarang,pod.nmbarang,pod.qty,pod.harga,pod.discount,pod.subtotal from poh inner join pod on poh.nopo=pod.nopo where poh.proses='Y' and (poh.tglpo>='$tgl1' and poh.tglpo<='$tgl2') and kdsupplier='$kdsupplier'");
                }
                $nono = 1;
                $jumppn = 0;
                $jumtotal = 0;
                $subtotalpo = 0;
                $subtotalppn = 0;
                $subtotaldiscount = 0;
                $jumsubtotal = 0;
                $subtotalpo = 0;
                $subtotalppn = 0;
                $subtotaldiscount = 0;
                $total = 0;
                $subqty = 0;
                while ($rowd = mysqli_fetch_assoc($queryd)) {
                    $nopo = $rowd['nopo'];
                    $gtsubtotal = $gtsubtotal + $row['total_sementara'];
                    $gtppn = $gtppn + $row['total_sementara'] * ($rowd['ppn'] / 100);
                    $gttotal = $gttotal + $row['total'];
                    $tglpo = $row['tglpo'];
                    $supplier = $row['kdsupplier'] . ' - ' . $row['nmsupplier'];
                    $jumppn = $jumppn + $row['total_sementara'] * ($rowd['ppn'] / 100);
                    $jumtotal = $jumtotal + $row['total'];
                    $total_sementaraf = number_format($row['total_sementara'], 0, '.', ',');
                    $ppnf = number_format($row['total_sementara'] * ($rowd['ppn'] / 100), 0, '.', ',');
                    $totalf = number_format($row['total'], 0, '.', ',');
                    //   echo  '<tr>
                    // 	<td></td>
                    // 	<td width="90px" >' . $rowd["kdbarang"] . '</td>
                    // 	<td width="200px" >' . $rowd["nmbarang"] . '</td>
                    // 	<td width="50px"  align="right">' . $qty . '</td>
                    // 	<td width="60px"  align="right">' . $harga . '</td>
                    // 	<td width="60px"  align="right">' . $po . '</td>
                    // 	<td width="40px"  align="right">' . $discount . '</td>
                    // 	<td width="40px"  align="right">' . $ppn . '</td>
                    // 	<td width="70px"  align="right">' . $subtotal . '</td>
                    // </tr>';
                    // $subtotalpo = 0;
                    // $subtotalppn = 0;
                    // $subtotaldiscount = 0;
                    // $total = 0;
                    // $subqty = 0;
                    // echo $nopo . ' - ' . $rowd['ppn'] . '<br>';
                    $querypod = mysqli_query($connect, "select * from pod where nopo='$nopo'");
                    while ($rowpod = mysqli_fetch_assoc($querypod)) {
                        $qty = number_format($rowpod['qty'], 2, '.', ',');
                        $harga = number_format($rowpod['harga'], 0, '.', ',');
                        $ndiscount = $rowpod['qty'] * $rowpod['harga'] * ($rowpod['discount'] / 100);
                        $discount = number_format($ndiscount, 0, '.', ',');
                        $nppn = ($rowpod['qty'] * $rowpod['harga'] - $ndiscount) * ($rowd['ppn'] / 100);
                        $ppn = number_format($nppn, 0, '.', ',');
                        $harga = number_format($rowpod['harga'], 0, '.', ',');
                        $nsubtotal = $rowpod['qty'] * $rowpod['harga'] - $ndiscount + $nppn;
                        $subtotal = number_format($nsubtotal, 0, '.', ',');
                        $npo = $rowpod['qty'] * $rowpod['harga'];
                        $po = number_format($npo, 0, '.', ',');
                        $nqty = $rowpod['qty'];
                        $tglpo = $row['tglpo'];
                        echo '<tr>
            <td style="text-align:center;">' .
                            $nono .
                            '</td>
            <td>' .
                            $rowpod['nopo'] .
                            '</td>
            <td>' .
                            $tglpo .
                            '</td>
            <td>' .
                            $row['carabayar'] .
                            '</td>
            <td>' .
                            $rowpod['kdbarang'] .
                            '</td>
            <td>' .
                            $rowpod['nmbarang'] .
                            '</td>
            <td width="50px"  align="right">' .
                            $qty .
                            '</td>
            <td width="60px"  align="right">' .
                            $harga .
                            '</td>
            <td width="70px"  align="right">' .
                            $po .
                            '</td>
            <td width="40px"  align="right">' .
                            $discount .
                            '</td>
            <td width="40px"  align="right">' .
                            $ppn .
                            '</td>
            <td width="70px"  align="right">' .
                            $subtotal .
                            '</td>
            </tr>';
                        $grandpo = $grandpo + $npo;
                        $grandppn = $grandppn + $nppn;
                        $granddiscount = $granddiscount + $ndiscount;
                        $subtotalpo = $subtotalpo + $npo;
                        $subtotalppn = $subtotalppn + $nppn;
                        $subtotaldiscount = $subtotaldiscount + $ndiscount;
                        $grandtotal = $grandtotal + $nsubtotal;
                        $jumsubtotal = $jumsubtotal + $nsubtotal;
                        $subqty = $subqty + $nqty;
                        $grandqty = $grandqty + $nqty;
                        $nono++;
                    }
                }
                $subqtyf = number_format($subqty, 2, '.', ',');
                $subtotalpof = number_format($subtotalpo, 0, '.', ',');
                $subtotalppnf = number_format($subtotalppn, 0, '.', ',');
                $subtotaldiscountf = number_format($subtotaldiscount, 0, '.', ',');
                $totalf = number_format($jumsubtotal, 0, '.', ',');
                echo '<tr><td colspan="6" height="20px" align="left">&nbsp;' .
                    'Total' .
                    '&nbsp;</td>
      <td height="20px" align="right">' .
                    $subqtyf .
                    '</td>
      <td></td>
   <td height="20px" align="right">' .
                    $subtotalpof .
                    '</td>
   <td height="20px" align="right">' .
                    $subtotaldiscountf .
                    '</td>
   <td height="20px" align="right">' .
                    $subtotalppnf .
                    '</td>
   <td height="20px" align="right">' .
                    $totalf .
                    '</td>
  </tr>';
                $no++;
            }
            if ($semuasupplier == 'Y') {
                $grandqtyf = number_format($grandqty, 2, '.', ',');
                $grandtotalf = number_format($grandtotal, 0, '.', ',');
                $grandpof = number_format($grandpo, 0, '.', ',');
                $grandppnf = number_format($grandppn, 0, '.', ',');
                $granddiscountf = number_format($granddiscount, 0, '.', ',');
                echo '<tr><td colspan="6" height="20px" align="left">&nbsp;' .
                    'Grand Total' .
                    '&nbsp;</td>
        <td height="20px" align="right">' .
                    $grandqtyf .
                    '</td>
        <td></td>
        <td height="20px" align="right">' .
                    $grandpof .
                    '</td>
        <td height="20px" align="right">' .
                    $granddiscountf .
                    '</td>
        <td height="20px" align="right">' .
                    $grandppnf .
                    '</td>
        <td height="20px" align="right">' .
                    $grandtotalf .
                    '</td></tr>';
            }
            echo '</table>';
        } else {
            echo '<table table-layout="fixed"; cellpadding="2"; cellspacing="0"; style=font-size:11px; class="table table-striped table table-bordered;">
   <tr>
    <th width="30px" height="20"><font size="1" color="black">NO.</th>
    <th width="90px"><font size="1" color="black">KODE BARANG</th>
    <th width="200px"><font size="1" color="black">NAMA BARANG</th>
    <th width="50px"><font size="1" color="black">QTY</th>
    <th width="60px"><font size="1" color="black">HARGA</th>
    <th width="60px"><font size="1" color="black">SUBTOTAL</th>
    <th width="60px"><font size="1" color="black">DISC.</th>
    <th width="60px"><font size="1" color="black">PPN</th>
    <th width="70px"><font size="1" color="black">TOTAL</th>
   </tr>';
            $grandtotal = 0;
            $grandpo = 0;
            $grandppn = 0;
            $granddiscount = 0;
            $no = 1;
            while ($row = mysqli_fetch_assoc($queryh)) {
                echo '<tr>
    <td align=center>' .
                    $no .
                    '</td>
   <td colspan="9" width="573px" height="35px" align="left">' .
                    'No. PO : ' .
                    $row['nopo'] .
                    ', Tanggal : ' .
                    $row['tglpo'] .
                    ', No. Referensi : ' .
                    $row['noreferensi'] .
                    ', Biaya Lain : ' .
                    $row['biaya_lain'] .
                    ', Customer : ' .
                    $row['nmsupplier'] .
                    '</td>';
                $nopo = $row['nopo'];
                if ($semuaperiode == 'Y') {
                    $tanggal = 'Semua Periode';
                    $queryd = mysqli_query($connect, "select poh.nopo,poh.tglpo,poh.noreferensi,poh.nmsupplier,pod.kdbarang,pod.nmbarang,pod.qty,pod.harga,pod.discount,pod.subtotal from poh inner join pod on poh.nopo=pod.nopo where poh.proses='Y' and poh.nopo='$nopo'");
                } else {
                    $tanggal = $tanggal1 . ' s/d ' . $tanggal2;
                    $queryd = mysqli_query($connect, "select poh.nopo,poh.tglpo,poh.noreferensi,poh.nmsupplier,pod.kdbarang,pod.nmbarang,pod.qty,pod.harga,pod.discount,pod.subtotal from poh inner join pod on poh.nopo=pod.nopo where poh.proses='Y' and (poh.tglpo>='$tgl1' and poh.tglpo<='$tgl2') and pod.nopo='$nopo'");
                }
                $subtotalpo = 0;
                $subtotalppn = 0;
                $subtotaldiscount = 0;
                $jumsubtotal = 0;
                while ($rowd = mysqli_fetch_assoc($queryd)) {
                    //$subtotal_ppn = $rowd['subtotal'] - ($rowd['subtotal'] * ($row['ppn']/100));
                    $qty = number_format($rowd['qty'], 2, '.', ',');
                    $harga = number_format($rowd['harga'], 0, '.', ',');
                    $ndiscount = $rowd['qty'] * $rowd['harga'] * ($rowd['discount'] / 100);
                    $discount = number_format($ndiscount, 0, '.', ',');
                    $nppn = ($rowd['qty'] * $rowd['harga'] - $ndiscount) * ($row['ppn'] / 100);
                    $ppn = number_format($nppn, 0, '.', ',');
                    $harga = number_format($rowd['harga'], 0, '.', ',');
                    $nsubtotal = $rowd['qty'] * $rowd['harga'] - $ndiscount + $nppn;
                    $subtotal = number_format($nsubtotal, 0, '.', ',');
                    $npo = $rowd['qty'] * $rowd['harga'];
                    $po = number_format($npo, 0, '.', ',');
                    echo '<tr>
     <td></td>
     <td width="90px" >' .
                        $rowd['kdbarang'] .
                        '</td>
     <td width="200px" >' .
                        $rowd['nmbarang'] .
                        '</td>
     <td width="50px"  align="right">' .
                        $qty .
                        '</td>
     <td width="60px"  align="right">' .
                        $harga .
                        '</td>
     <td width="60px"  align="right">' .
                        $po .
                        '</td>
     <td width="40px"  align="right">' .
                        $discount .
                        '</td>
     <td width="40px"  align="right">' .
                        $ppn .
                        '</td>
     <td width="70px"  align="right">' .
                        $subtotal .
                        '</td>
    </tr>';
                    $grandpo = $grandpo + $npo;
                    $grandppn = $grandppn + $nppn;
                    $granddiscount = $granddiscount + $ndiscount;
                    $subtotalpo = $subtotalpo + $npo;
                    $subtotalppn = $subtotalppn + $nppn;
                    $subtotaldiscount = $subtotaldiscount + $ndiscount;
                    $grandtotal = $grandtotal + $nsubtotal;
                    $jumsubtotal = $jumsubtotal + $nsubtotal;
                }
                $subtotalpo = number_format($subtotalpo, 0, '.', ',');
                $subtotalppn = number_format($subtotalppn, 0, '.', ',');
                $subtotaldiscount = number_format($subtotaldiscount, 0, '.', ',');
                $total = number_format($jumsubtotal, 0, '.', ',');
                echo '<tr><td colspan="5" height="20px" align="left">' .
                    'Total' .
                    '</td>
        <td height="20px" align="right">' .
                    $subtotalpo .
                    '</td>
        <td height="20px" align="right">' .
                    $subtotaldiscount .
                    '</td>
        <td height="20px" align="right">' .
                    $subtotalppn .
                    '</td>
        <td height="20px" align="right">' .
                    $total .
                    '</td>
        </tr>';
                $no++;
            }
            if ($semuasupplier == 'Y') {
                $grandtotal = number_format($grandtotal, 0, '.', ',');
                $grandpo = number_format($grandpo, 0, '.', ',');
                $grandppn = number_format($grandppn, 0, '.', ',');
                $granddiscount = number_format($granddiscount, 0, '.', ',');
                echo '<tr><td colspan="5" height="20px" align="left">' .
                    'Grand Total' .
                    '</td>
        <td height="20px" align="right">' .
                    $grandpo .
                    '</td>
        <td height="20px" align="right">' .
                    $granddiscount .
                    '</td>
        <td height="20px" align="right">' .
                    $grandppn .
                    '</td>
        <td height="20px" align="right">' .
                    $grandtotal .
                    '</td></tr>';
            }
            echo '</table>';
        }
    }
} else {
    // Tanpa rincian, grouping supplier
    if ($groupingsupplier == 'Y') {
        if ($semuaperiode == 'Y') {
            $tanggal = 'Semua Periode';
            if ($semuasupplier == 'Y') {
                $queryh = mysqli_query($connect, "select * from poh where proses='Y' group by kdsupplier order by kdsupplier");
            } else {
                $queryh = mysqli_query($connect, "select * from poh where proses='Y' and kdsupplier='$kdsupplier' group by kdsupplier order by tglpo");
            }
        } else {
            $tanggal = $tanggal1 . ' s/d ' . $tanggal2;
            if ($semuasupplier == 'Y') {
                $queryh = mysqli_query($connect, "select * from poh where proses='Y' and (tglpo>='$tgl1' and tglpo<='$tgl2') order by tglpo");
            } else {
                $queryh = mysqli_query($connect, "select * from poh where proses='Y' and (tglpo>='$tgl1' and tglpo<='$tgl2') and kdsupplier='$kdsupplier' group by kdsupplier order by tglpo");
            }
            //echo $tgl1.'  '.$tgl2;
        }
        echo '<table table-layout="fixed"; cellpadding="2"; cellspacing="0"; style=font-size:11px; class="table table-striped table table-bordered;">
   <tr>
    <th width="30px" height="20"><font size="1" color="black">NO1.</th>
    <th width="80px"><font size="1" color="black">TANGGAL</th>
    <th width="95px"><font size="1" color="black">NO. PO</th>
        <th width="95px"><font size="1" color="black">CARA BAYAR</th>
    <th width="100px"><font size="1" color="black">SUBTOTAL</th>
    <th width="70px"><font size="1" color="black">PPN</th>
    <th width="100px"><font size="1" color="black">TOTAL</th>
   </tr>';
        $no = 1;
        $gtsubtotal = 0;
        $gtppn = 0;
        $gttotal = 0;
        while ($row = mysqli_fetch_assoc($queryh)) {
            $kdsupplier = $row['kdsupplier'];
            $supplier = $row['kdsupplier'] . ' - ' . $row['nmsupplier'];
            echo '<tr>
     <td style="text-align:center;color:blue;">' .
                $no .
                '</td>
     <td colspan=6 style="color:blue;">' .
                $supplier .
                '</td>
    </tr>';
            if ($semuaperiode == 'Y') {
                $tanggal = 'Semua Periode';
                $queryd = mysqli_query($connect, "select * from poh where proses='Y' and kdsupplier='$kdsupplier' order by tglpo");
            } else {
                $tanggal = $tanggal1 . ' s/d ' . $tanggal2;
                $queryd = mysqli_query($connect, "select * from poh where proses='Y' and (tglpo>='$tgl1' and tglpo<='$tgl2') and kdsupplier='$kdsupplier' order by tglpo");
            }
            $nono = 1;
            $jumsubtotal = 0;
            $jumppn = 0;
            $jumtotal = 0;
            while ($row = mysqli_fetch_assoc($queryd)) {
                $gtsubtotal = $gtsubtotal + $row['total_sementara'];
                $gtppn = $gtppn + $row['total_sementara'] * ($row['ppn'] / 100);
                $gttotal = $gttotal + $row['total'];
                $tglpo = $row['tglpo'];
                $supplier = $row['kdsupplier'] . ' - ' . $row['nmsupplier'];
                $jumsubtotal = $jumsubtotal + $row['total_sementara'];
                $jumppn = $jumppn + $row['total_sementara'] * ($row['ppn'] / 100);
                $jumtotal = $jumtotal + $row['total'];
                $total_sementaraf = number_format($row['total_sementara'], 0, '.', ',');
                $ppnf = number_format($row['total_sementara'] * ($row['ppn'] / 100), 0, '.', ',');
                $totalf = number_format($row['total'], 0, '.', ',');
                echo '<tr>
     <td style="text-align:center;">' .
                    $nono .
                    '</td>
     <td>' .
                    $tglpo .
                    '</td>
     <td>' .
                    $row['nopo'] .
                    '</td>
          <td>' .
                    $row['carabayar'] .
                    '</td>
     <td style="text-align:right;">' .
                    $total_sementaraf .
                    '</td>
     <td style="text-align:right;">' .
                    $ppnf .
                    '</td>
     <td style="text-align:right;">' .
                    $totalf .
                    '</td>
    </tr>';
                $nono++;
            }
            $jumsubtotalf = number_format($jumsubtotal, 0, '.', ',');
            $jumppnf = number_format($jumppn, 0, '.', ',');
            $jumtotalf = number_format($jumtotal, 0, '.', ',');
            echo '<tr>
     <td colspan=4 style="text-align:right;">' .
                'total' .
                '</td>
     <td style="text-align:right;">' .
                $jumsubtotalf .
                '</td>
     <td style="text-align:right;">' .
                $jumppnf .
                '</td>
     <td style="text-align:right;">' .
                $jumtotalf .
                '</td>
    </tr>';
            $no++;
        }
        if ($semuasupplier == 'Y') {
            $gtsubtotalf = number_format($gtsubtotal, 0, '.', ',');
            $gtppnf = number_format($gtppn, 0, '.', ',');
            $gttotalf = number_format($gttotal, 0, '.', ',');
            echo '<tr>
            <td colspan=4 style="text-align:right;">' .
                'Grand total' .
                '</td>
            <td style="text-align:right;">' .
                $gtsubtotalf .
                '</td>
            <td style="text-align:right;">' .
                $gtppnf .
                '</td>
            <td style="text-align:right;">' .
                $gttotalf .
                '</td>
          </tr>';
        }
        echo '</table>';
    } else {
        echo '<table table-layout="fixed"; cellpadding="2"; cellspacing="0"; style=font-size:11px; class="table table-striped table table-bordered;">
   <tr>
    <th width="30px" height="20"><font size="1" color="black">NO.</th>
    <th width="80px"><font size="1" color="black">TANGGAL</th>
    <th width="95px"><font size="1" color="black">NO. PO</th>
    <th width="350px"><font size="1" color="black">SUPPLIER</th>
    <th width="100px"><font size="1" color="black">SUBTOTAL</th>
    <th width="70px"><font size="1" color="black">PPN</th>
    <th width="100px"><font size="1" color="black">TOTAL</th>
   </tr>';
        $no = 1;
        $jumsubtotal = 0;
        $jumppn = 0;
        $jumtotal = 0;
        while ($row = mysqli_fetch_assoc($queryh)) {
            $tglpo = $row['tglpo'];
            $supplier = $row['kdsupplier'] . ' - ' . $row['nmsupplier'];
            $jumsubtotal = $jumsubtotal + $row['total_sementara'];
            $jumppn = $jumppn + $row['total_sementara'] * ($row['ppn'] / 100);
            $jumtotal = $jumtotal + $row['total'];
            $total_sementaraf = number_format($row['total_sementara'], 0, '.', ',');
            $ppnf = number_format($row['total_sementara'] * ($row['ppn'] / 100), 0, '.', ',');
            $totalf = number_format($row['total'], 0, '.', ',');
            echo '<tr>
     <td style="text-align:center;">' .
                $no .
                '</td>
     <td>' .
                $tglpo .
                '</td>
     <td>' .
                $row['nopo'] .
                '</td>
     <td>' .
                $supplier .
                '</td>
     <td style="text-align:right;">' .
                $total_sementaraf .
                '</td>
     <td style="text-align:right;">' .
                $ppnf .
                '</td>
     <td style="text-align:right;">' .
                $totalf .
                '</td>
    </tr>';
            $no++;
        }
        if ($semuasupplier == 'Y') {
            $jumsubtotalf = number_format($jumsubtotal, 0, '.', ',');
            $jumppnf = number_format($jumppn, 0, '.', ',');
            $jumtotalf = number_format($jumtotal, 0, '.', ',');
            echo '<tr><td colspan="4" height="20px" align="left">&nbsp;' .
                'Grand Total' .
                '&nbsp;</td>
      <td height="20px" align="right">' .
                $jumsubtotalf .
                '</td>
      <td height="20px" align="right">' .
                $jumppnf .
                '</td>
      <td height="20px" align="right">' .
                $jumtotalf .
                '</td></tr>';
        }
        echo '</table>';
    }
}
echo '<font size="1"><left>Tanggal cetak : ' . date('d-m-Y H:i:s a') . '<br>';
