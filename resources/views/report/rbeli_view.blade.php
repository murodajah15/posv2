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
    echo 'Barang : ' . $kdbarang . ' - ' . $nmbarang;
} else {
    $kdbarang = '';
}

echo '</font>
  <font size="1" face="comic sans ms"><br>';

$no = 1;

if ($semuaperiode == 'Y') {
    $tanggal = 'Semua Periode';
    if ($semuasupplier == 'Y') {
        $queryh = mysqli_query($connect, "select * from belih where proses='Y' order by tglbeli");
    } else {
        $queryh = mysqli_query($connect, "select * from belih where proses='Y' and kdsupplier='$kdsupplier' order by tglbeli");
    }
} else {
    $tanggal = $tanggal1 . ' s/d ' . $tanggal2;
    if ($semuasupplier == 'Y') {
        $queryh = mysqli_query($connect, "select * from belih where proses='Y' and (tglbeli>='$tgl1' and tglbeli<='$tgl2') order by tglbeli");
    } else {
        $queryh = mysqli_query($connect, "select * from belih where proses='Y' and (tglbeli>='$tgl1' and tglbeli<='$tgl2') and kdsupplier='$kdsupplier' order by tglbeli");
    }
    //echo $tgl1.'  '.$tgl2;
}

if ($rincian == 'Y') {
    if (isset($_POST['perbarang'])) {
        echo '<font size="2">Perbarang : ' . $kdbarang . ' - ' . $nmbarang . '</font>';
        echo '<table table-layout="fixed"; cellpadding="2"; cellspacing="0"; style=font-size:11px; class="table table-striped table table-bordered;">
   <tr>
    <th width="30px" height="20"><font size="1" color="black">NO.</th>
        <th width="80px"><font size="1" color="black">NO. PEMBELIAN</th>
    <th width="55px"><font size="1" color="black">TANGGAL</th>
    <th width="90px"><font size="1" color="black">KODE SUPPLIER</th>
    <th width="300px"><font size="1" color="black">NAMA SUPPLIER</th>
    <th width="50px"><font size="1" color="black">QTY</th>
    <th width="60px"><font size="1" color="black">HARGA</th>
    <th width="70px"><font size="1" color="black">SUBTOTAL</th>
    <th width="60px"><font size="1" color="black">DISC.</th>
    <th width="60px"><font size="1" color="black">PPN</th>
    <th width="70px"><font size="1" color="black">TOTAL</th>
   </tr>';
        $grandqty = 0;
        $grandtotal = 0;
        $grandbeli = 0;
        $grandppn = 0;
        $granddiscount = 0;
        $no = 1;
        while ($row = mysqli_fetch_assoc($queryh)) {
            $nobeli = $row['nobeli'];
            if ($semuaperiode == 'Y') {
                $tanggal = 'Semua Periode';
                $queryd = mysqli_query($connect, "select belih.kdsupplier,belih.nmsupplier,belih.nobeli,belih.tglbeli,belih.noinvoice,belih.nmsupplier,belid.kdbarang,belid.nmbarang,belid.qty,belid.harga,belid.discount,belid.subtotal from belih inner join belid on belih.nobeli=belid.nobeli where belih.proses='Y' and belih.nobeli='$nobeli' and belid.kdbarang='$kdbarang'");
            } else {
                $tanggal = $tanggal1 . ' s/d ' . $tanggal2;
                $queryd = mysqli_query($connect, "select belih.kdsupplier,belih.nmsupplier,belih.nobeli,belih.tglbeli,belih.noinvoice,belih.nmsupplier,belid.kdbarang,belid.nmbarang,belid.qty,belid.harga,belid.discount,belid.subtotal from belih inner join belid on belih.nobeli=belid.nobeli where belih.proses='Y' and (belih.tglbeli>='$tgl1' and belih.tglbeli<='$tgl2') and belid.nobeli='$nobeli' and belid.kdbarang='$kdbarang'");
            }
            $rowdata = mysqli_num_rows($queryd);
            $subtotalbeli = 0;
            $subtotalppn = 0;
            $subtotaldiscount = 0;
            $jumsubtotal = 0;
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
                    $nbeli = $rowd['qty'] * $rowd['harga'];
                    $beli = number_format($nbeli, 0, '.', ',');
                    $nqty = $rowd['qty'];
                    echo '<tr>
     <td style="text-align:right;">' .
                        $no .
                        '</td>
          <td width="90px" >&nbsp;' .
                        $rowd['nobeli'] .
                        '</td>
     <td width="75px" >&nbsp;' .
                        $rowd['tglbeli'] .
                        '</td>
     <td width="90px" >&nbsp;' .
                        $rowd['kdsupplier'] .
                        '</td>
     <td width="200px" >&nbsp;' .
                        $rowd['nmsupplier'] .
                        '</td>
     <td width="50px"  align="right">' .
                        $qty .
                        '</td>
     <td width="60px"  align="right">' .
                        $harga .
                        '</td>
     <td width="70px"  align="right">' .
                        $beli .
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
                    $grandbeli = $grandbeli + $nbeli;
                    $grandppn = $grandppn + $nppn;
                    $granddiscount = $granddiscount + $ndiscount;
                    $subtotalbeli = $subtotalbeli + $nbeli;
                    $subtotalppn = $subtotalppn + $nppn;
                    $subtotaldiscount = $subtotaldiscount + $ndiscount;
                    $grandtotal = $grandtotal + $nsubtotal;
                    $jumsubtotal = $jumsubtotal + $nsubtotal;
                }
                $no++;
            }
        }
        // $subtotalbeli = number_format($subtotalbeli, 0, ".", ",");
        // $subtotalppn = number_format($subtotalppn, 0, ".", ",");
        // $subtotaldiscount = number_format($subtotaldiscount, 0, ".", ",");
        // $total = number_format($jumsubtotal, 0, ".", ",");
        // echo  '<tr><td colspan="7" height="20px" align="left">&nbsp;' . "Total" . '&nbsp;</td>
        // 	<td height="20px" align="right">' . $subtotalbeli . '</td>
        // 	<td height="20px" align="right">' . $subtotaldiscount . '</td>
        // 	<td height="20px" align="right">' . $subtotalppn . '</td>
        // 	<td height="20px" align="right">' . $total . '</td>
        // </tr>';
        if ($semuasupplier == 'Y') {
            $grandqty = number_format($grandqty, 2, '.', ',');
            $grandtotal = number_format($grandtotal, 0, '.', ',');
            $grandbeli = number_format($grandbeli, 0, '.', ',');
            $grandppn = number_format($grandppn, 0, '.', ',');
            $granddiscount = number_format($granddiscount, 0, '.', ',');
            echo '<tr><td colspan="5" height="20px" align="left">&nbsp;' .
                'Grand Total' .
                '&nbsp;</td>
      <td height="20px" align="right">' .
                $grandqty .
                '</td>
      <td></td>
      <td height="20px" align="right">' .
                $grandbeli .
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
      </tr>';
        }
        echo '</table>';
    } else {
        if (isset($_POST['groupingsupplier'])) {
            echo '<table table-layout="fixed"; cellpadding="2"; cellspacing="0"; style=font-size:11px; class="table table-striped table table-bordered;">
   <tr>
    <th width="30px" height="20"><font size="1" color="black">NO.</th>
    <th width="95px"><font size="1" color="black">NO. PEMBELIAN</th>
        <th width="80px"><font size="1" color="black">TANGGAL</th>
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
                    $queryh = mysqli_query($connect, "select * from belih where proses='Y' group by kdsupplier order by kdsupplier");
                } else {
                    $queryh = mysqli_query($connect, "select * from belih where proses='Y' and kdsupplier='$kdsupplier' group by kdsupplier order by kdsupplier");
                }
            } else {
                $tanggal = $tanggal1 . ' s/d ' . $tanggal2;
                if ($semuasupplier == 'Y') {
                    $queryh = mysqli_query($connect, "select * from belih where proses='Y' and (tglbeli>='$tgl1' and tglbeli<='$tgl2') group by kdsupplier order by kdsupplier");
                } else {
                    $queryh = mysqli_query($connect, "select * from belih where proses='Y' and (tglbeli>='$tgl1' and tglbeli<='$tgl2') and kdsupplier='$kdsupplier' group by kdsupplier order by kdsupplier");
                }
                //echo $tgl1.'  '.$tgl2;
            }
            $no = 1;
            $gtsubtotal = 0;
            $gtppn = 0;
            $gttotal = 0;
            $grandbeli = 0;
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
                if ($semuaperiode == 'Y') {
                    $tanggal = 'Semua Periode';
                    $queryd = mysqli_query($connect, "select * from belih where proses='Y' and kdsupplier='$kdsupplier' order by tglbeli");
                } else {
                    $tanggal = $tanggal1 . ' s/d ' . $tanggal2;
                    $queryd = mysqli_query($connect, "select * from belih where proses='Y' and (tglbeli>='$tgl1' and tglbeli<='$tgl2') and kdsupplier='$kdsupplier' order by tglbeli");
                }
                $nono = 1;
                $jumsubtotal = 0;
                $jumppn = 0;
                $jumtotal = 0;

                $subtotalbeli = 0;
                $subtotalppn = 0;
                $subtotaldiscount = 0;
                $total = 0;
                $subqty = 0;
                while ($row = mysqli_fetch_assoc($queryd)) {
                    $nobeli = $row['nobeli'];
                    $gtsubtotal = $gtsubtotal + $row['total_sementara'];
                    $gtppn = $gtppn + $row['total_sementara'] * ($row['ppn'] / 100);
                    $gttotal = $gttotal + $row['total'];
                    $tglbeli = $row['tglbeli'];
                    $supplier = $row['kdsupplier'] . ' - ' . $row['nmsupplier'];
                    $jumsubtotal = $jumsubtotal + $row['total_sementara'];
                    $jumppn = $jumppn + $row['total_sementara'] * ($row['ppn'] / 100);
                    $jumtotal = $jumtotal + $row['total'];
                    $total_sementaraf = number_format($row['total_sementara'], 0, '.', ',');
                    $ppnf = number_format($row['total_sementara'] * ($row['ppn'] / 100), 0, '.', ',');
                    $totalf = number_format($row['total'], 0, '.', ',');
                    // echo  '<tr>
                    // 	<td style="text-align:center;">' . $nono . '</td>
                    // 	<td>' . $tglbeli . '</td>
                    // 	<td>' . $row["nobeli"] . '</td>
                    //   <td>' . $row["carabayar"] . '</td>
                    // 	<td style="text-align:right;">' . $total_sementaraf . '</td>
                    // 	<td style="text-align:right;">' . $ppnf . '</td>
                    // 	<td style="text-align:right;">' . $totalf . '</td>
                    // </tr>';
                    $nono++;
                    $querybelid = mysqli_query($connect, "select * from belid where nobeli='$nobeli'");
                    while ($rowbelid = mysqli_fetch_assoc($querybelid)) {
                        $qty = number_format($rowbelid['qty'], 2, '.', ',');
                        $harga = number_format($rowbelid['harga'], 0, '.', ',');
                        $ndiscount = $rowbelid['qty'] * $rowbelid['harga'] * ($rowbelid['discount'] / 100);
                        $discount = number_format($ndiscount, 0, '.', ',');
                        $nppn = ($rowbelid['qty'] * $rowbelid['harga'] - $ndiscount) * ($row['ppn'] / 100);
                        $ppn = number_format($nppn, 0, '.', ',');
                        $harga = number_format($rowbelid['harga'], 0, '.', ',');
                        $nsubtotal = $rowbelid['qty'] * $rowbelid['harga'] - $ndiscount + $nppn;
                        $subtotal = number_format($nsubtotal, 0, '.', ',');
                        $nbeli = $rowbelid['qty'] * $rowbelid['harga'];
                        $beli = number_format($nbeli, 0, '.', ',');
                        $nqty = $rowbelid['qty'];
                        echo '<tr>
     <td style="text-align:center;">' .
                            $nono .
                            '</td>
     <td>' .
                            $row['nobeli'] .
                            '</td>
          <td>' .
                            $tglbeli .
                            '</td>
          <td>' .
                            $row['carabayar'] .
                            '</td>
     <td>' .
                            $rowbelid['kdbarang'] .
                            '</td>
          <td>' .
                            $rowbelid['nmbarang'] .
                            '</td>
          <td width="50px"  align="right">' .
                            $qty .
                            '</td>
     <td width="60px"  align="right">' .
                            $harga .
                            '</td>
     <td width="70px"  align="right">' .
                            $beli .
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
                        $grandbeli = $grandbeli + $nbeli;
                        $grandppn = $grandppn + $nppn;
                        $granddiscount = $granddiscount + $ndiscount;
                        $subtotalbeli = $subtotalbeli + $nbeli;
                        $subtotalppn = $subtotalppn + $nppn;
                        $subtotaldiscount = $subtotaldiscount + $ndiscount;
                        $grandtotal = $grandtotal + $nsubtotal;
                        $jumsubtotal = $jumsubtotal + $nsubtotal;
                        $subqty = $subqty + $nqty;
                        $grandqty = $grandqty + $nqty;
                    }
                }
                $subqtyf = number_format($subqty, 2, '.', ',');
                $subtotalbelif = number_format($subtotalbeli, 0, '.', ',');
                $subtotalppnf = number_format($subtotalppn, 0, '.', ',');
                $subtotaldiscountf = number_format($subtotaldiscount, 0, '.', ',');
                $jumtotalf = number_format($jumtotal, 0, '.', ',');
                echo '<tr><td colspan="6" height="20px" align="left">&nbsp;' .
                    'Total' .
                    '&nbsp;</td>
        <td height="20px" align="right">' .
                    $subqtyf .
                    '</td>
        <td></td>
        <td height="20px" align="right">' .
                    $subtotalbelif .
                    '</td>
        <td height="20px" align="right">' .
                    $subtotaldiscountf .
                    '</td>
        <td height="20px" align="right">' .
                    $subtotalppnf .
                    '</td>
        <td height="20px" align="right">' .
                    $jumtotalf .
                    '</td>
        </tr>';
                $no++;
            }
            if ($semuasupplier == 'Y') {
                $grandqtyf = number_format($grandqty, 2, '.', ',');
                $grandtotalf = number_format($grandtotal, 0, '.', ',');
                $grandbelif = number_format($grandbeli, 0, '.', ',');
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
                    $grandbelif .
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
    <th width="300px"><font size="1" color="black">NAMA BARANG</th>
    <th width="50px"><font size="1" color="black">QTY</th>
    <th width="60px"><font size="1" color="black">HARGA</th>
    <th width="60px"><font size="1" color="black">SUBTOTAL</th>
    <th width="60px"><font size="1" color="black">DISC.</th>
    <th width="60px"><font size="1" color="black">PPN</th>
    <th width="70px"><font size="1" color="black">TOTAL</th>
   </tr>';
            $grandtotal = 0;
            $grandbeli = 0;
            $grandppn = 0;
            $granddiscount = 0;
            $no = 1;
            while ($row = mysqli_fetch_assoc($queryh)) {
                $nobeli = $row['nobeli'];
                echo '<tr>
        <td align=center>' .
                    $no .
                    '</td>
        <td colspan="9" width="573px" height="35px" align="left">&nbsp;' .
                    'No. Beli : ' .
                    $row['nobeli'] .
                    ', Tanggal : ' .
                    $row['tglbeli'] .
                    ', No. Invoice : ' .
                    $row['noinvoice'] .
                    ', Biaya Lain : ' .
                    $row['biaya_lain'] .
                    ', Supplier : ' .
                    $row['kdsupplier'] .
                    ' - ' .
                    $row['nmsupplier'] .
                    '</td>';
                if ($semuaperiode == 'Y') {
                    $tanggal = 'Semua Periode';
                    $queryd = mysqli_query($connect, "select belih.nobeli,belih.tglbeli,belih.noinvoice,belih.nmsupplier,belid.kdbarang,belid.nmbarang,belid.qty,belid.harga,belid.discount,belid.subtotal from belih inner join belid on belih.nobeli=belid.nobeli where belih.proses='Y' and belih.nobeli='$nobeli'");
                } else {
                    $tanggal = $tanggal1 . ' s/d ' . $tanggal2;
                    $queryd = mysqli_query($connect, "select belih.nobeli,belih.tglbeli,belih.noinvoice,belih.nmsupplier,belid.kdbarang,belid.nmbarang,belid.qty,belid.harga,belid.discount,belid.subtotal from belih inner join belid on belih.nobeli=belid.nobeli where belih.proses='Y' and (belih.tglbeli>='$tgl1' and belih.tglbeli<='$tgl2') and belid.nobeli='$nobeli'");
                }
                $subtotalbeli = 0;
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
                    $nbeli = $rowd['qty'] * $rowd['harga'];
                    $beli = number_format($nbeli, 0, '.', ',');
                    echo '<tr>
     <td></td></td>
     <td width="90px" >&nbsp;' .
                        $rowd['kdbarang'] .
                        '</td>
     <td width="200px" >&nbsp;' .
                        $rowd['nmbarang'] .
                        '</td>
     <td width="50px"  align="right">' .
                        $qty .
                        '</td>
     <td width="60px"  align="right">' .
                        $harga .
                        '</td>
     <td width="60px"  align="right">' .
                        $beli .
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
                    $grandbeli = $grandbeli + $nbeli;
                    $grandppn = $grandppn + $nppn;
                    $granddiscount = $granddiscount + $ndiscount;
                    $subtotalbeli = $subtotalbeli + $nbeli;
                    $subtotalppn = $subtotalppn + $nppn;
                    $subtotaldiscount = $subtotaldiscount + $ndiscount;
                    $grandtotal = $grandtotal + $nsubtotal;
                    $jumsubtotal = $jumsubtotal + $nsubtotal;
                }
                $subtotalbeli = number_format($subtotalbeli, 0, '.', ',');
                $subtotalppn = number_format($subtotalppn, 0, '.', ',');
                $subtotaldiscount = number_format($subtotaldiscount, 0, '.', ',');
                $total = number_format($jumsubtotal, 0, '.', ',');
                echo '<tr><td colspan="5" height="20px" align="left">&nbsp;' .
                    'Total' .
                    '&nbsp;</td>
   <td height="20px" align="right">' .
                    $subtotalbeli .
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
            $grandtotal = number_format($grandtotal, 0, '.', ',');
            $grandbeli = number_format($grandbeli, 0, '.', ',');
            $grandppn = number_format($grandppn, 0, '.', ',');
            $granddiscount = number_format($granddiscount, 0, '.', ',');
            echo '<tr><td colspan="5" height="20px" align="left">&nbsp;' .
                'Grand Total' .
                '&nbsp;</td>
  <td height="20px" align="right">' .
                $grandbeli .
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
        }
    }
} else {
    if (isset($_POST['groupingsupplier'])) {
        if ($rincian == 'Y') {
            // Dengan rincian, grouping supplier
            echo '<table table-layout="fixed"; cellpadding="2"; cellspacing="0"; style=font-size:11px; class="table table-striped table table-bordered;">
   <tr>
    <th width="30px" height="20"><font size="1" color="black">NO.</th>
    <th width="95px"><font size="1" color="black">NO. PEMBELIAN</th>
        <th width="80px"><font size="1" color="black">TANGGAL</th>
        <th width="90px"><font size="1" color="black">KODE BARANG</th>
    <th width="300px"><font size="1" color="black">NAMA BARANG</th>
    <th width="50px"><font size="1" color="black">QTY</th>
    <th width="60px"><font size="1" color="black">HARGA</th>
    <th width="60px"><font size="1" color="black">SUBTOTAL</th>
    <th width="60px"><font size="1" color="black">DISC.</th>
    <th width="60px"><font size="1" color="black">PPN</th>
    <th width="70px"><font size="1" color="black">TOTAL</th>
   </tr>';
        } else {
            // Tanpa rincian, grouping supplier
            if ($semuaperiode == 'Y') {
                $tanggal = 'Semua Periode';
                if ($semuasupplier == 'Y') {
                    $queryh = mysqli_query($connect, "select * from belih where proses='Y' group by kdsupplier order by kdsupplier");
                } else {
                    $queryh = mysqli_query($connect, "select * from belih where proses='Y' and kdsupplier='$kdsupplier' group by kdsupplier order by kdsupplier");
                }
            } else {
                $tanggal = $tanggal1 . ' s/d ' . $tanggal2;
                if ($semuasupplier == 'Y') {
                    $queryh = mysqli_query($connect, "select * from belih where proses='Y' and (tglbeli>='$tgl1' and tglbeli<='$tgl2') order by kdsupplier");
                } else {
                    $queryh = mysqli_query($connect, "select * from belih where proses='Y' and (tglbeli>='$tgl1' and tglbeli<='$tgl2') and kdsupplier='$kdsupplier' group by kdsupplier order by kdsupplier");
                }
                //echo $tgl1.'  '.$tgl2;
            }
            echo '<table table-layout="fixed"; cellpadding="2"; cellspacing="0"; style=font-size:11px; class="table table-striped table table-bordered;">
   <tr>
    <th width="30px" height="20"><font size="1" color="black">NO.</th>
    <th width="95px"><font size="1" color="black">NO. PEMBELIAN</th>
        <th width="80px"><font size="1" color="black">TANGGAL</th>
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
                    $queryd = mysqli_query($connect, "select * from belih where proses='Y' and kdsupplier='$kdsupplier' order by tglbeli");
                } else {
                    $tanggal = $tanggal1 . ' s/d ' . $tanggal2;
                    $queryd = mysqli_query($connect, "select * from belih where proses='Y' and (tglbeli>='$tgl1' and tglbeli<='$tgl2') and kdsupplier='$kdsupplier' order by tglbeli");
                }
                $nono = 1;
                $jumsubtotal = 0;
                $jumppn = 0;
                $jumtotal = 0;
                while ($row = mysqli_fetch_assoc($queryd)) {
                    $gtsubtotal = $gtsubtotal + $row['total_sementara'];
                    $gtppn = $gtppn + $row['total_sementara'] * ($row['ppn'] / 100);
                    $gttotal = $gttotal + $row['total'];
                    $tglbeli = $row['tglbeli'];
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
                        $row['nobeli'] .
                        '</td>
          <td>' .
                        $tglbeli .
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
        }
    } else {
        echo '<table table-layout="fixed"; cellpadding="2"; cellspacing="0"; style=font-size:11px; class="table table-striped table table-bordered;">
   <tr>
    <th width="30px" height="20"><font size="1" color="black">NO.</th>
    <th width="95px"><font size="1" color="black">NO. PEMBELIAN</th>
        <th width="80px"><font size="1" color="black">TANGGAL</th>
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
            $tglbeli = $row['tglbeli'];
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
                $row['nobeli'] .
                '</td>
          <td>' .
                $tglbeli .
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
