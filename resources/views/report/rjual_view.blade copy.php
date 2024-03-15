@include('home.config')

<?php
$connect = session('connect');
$nm_perusahaan = $saplikasi->nm_perusahaan;
$alamat_perusahaan = $saplikasi->alamat;
$telp_perusahaan = $saplikasi->telp;
$no = 1;

date_default_timezone_set('Asia/Jakarta');
// $tgl1 = date('Y-m-d', strtotime($tanggal1));
// $tgl2 = date('Y-m-d', strtotime($tanggal2));
$tgl1 = $tanggal1;
$tgl2 = $tanggal2;
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

if ($semuacustomer != 'Y') {
    echo 'Customer : ' . $kdcustomer . ' - ' . $nmcustomer;
} else {
    $kdcustomer = '';
}
if ($semuasales != 'Y') {
    echo 'Sales : ' . $semuasales . '   ' . $kdsales . ' - ' . $nmsales;
} else {
    $kdsales = '';
}
if ($semuaklpcust != 'Y') {
    echo 'Kelompok Customer : ' . $kdklpcust . ' - ' . $nmklpcust;
} else {
    $kdklpcust = '';
}
if ($semuabarang != 'Y') {
    echo 'Barang : ' . $kdbarang . ' - ' . $nmbarang;
} else {
    $kdbarang = '';
}

echo '</font>
  <font size="1" face="comic sans ms"><br>';

if ($rincian == 'Y') {
    if ($groupingcustomer == 'Y') {
        echo '<table table-layout="fixed"; cellpadding="2"; cellspacing="0"; style=font-size:11px; class="table table-striped table table-bordered;">
    <tr>
    <th width="30px" height="20"><font size="1" color="black"><b>NO.</th>
    <th width="100px"><font size="1" color="black"><b>NO. JUAL</th>
    <th width="75px"><font size="1" color="black"><b>TANGGAL</th>
    <th width="75px"><font size="1" color="black"><b>KODE BARANG</th>
    <th width="300px"><font size="1" color="black"><b>NAMA BARANG</th>
    <th width="60px"><font size="1" color="black"><b>QTY</th>
    <th width="70px"><font size="1" color="black"><b>HARGA</th>
    <th width="100px"><font size="1" color="black"><b>SUBTOTAL</th>
    <th width="80px"><font size="1" color="black"><b>DISC.</th>
    <th width="30px"><font size="1" color="black"><b>PPN<br>(%)</th>
    <th width="80px"><font size="1" color="black"><b>PPN</th>
    <th width="100px"><font size="1" color="black"><b>TOTAL</th>
    <th width="100px"><font size="1" color="black"><b>JUMLAH<br>PEMBAYARAN</th>
    <th width="100px"><font size="1" color="black"><b>SISA<br>PEMBAYARAN</th>
    </tr>';
        $no = 1;
        $grandqty = 0;
        $grandsubtotal = 0;
        $granddiscount = 0;
        $grandppn = 0;
        $grandtotal = 0;
        $grandbayar = 0;
        $grandsisa = 0;
        $q = "select jualh.*,tbcustomer.kdklpcust,tbcustomer.nmklpcust from jualh inner join tbcustomer on tbcustomer.kode=jualh.kdcustomer where jualh.proses='Y'";
        $q .= $kdklpcust == '' ? ' ' : " and tbcustomer.kdklpcust='$kdklpcust'";
        $q .= $kdsales == '' ? ' ' : " and jualh.kdsales='$kdsales'";
        $q .= $kdcustomer == '' ? ' ' : " and jualh.kdcustomer='$kdcustomer'";
        $q .= $pilihanppn == 'ppn' ? ' and jualh.ppn>0' : '';
        $q .= $pilihanppn == 'nonppn' ? ' and jualh.ppn=0' : '';
        $q .= $semuaperiode == 'Y' ? ' ' : " and (jualh.tgljual>='$tgl1' and jualh.tgljual<='$tgl2')";
        $q .= ' group by tbcustomer.kdklpcust order by tbcustomer.kdklpcust desc';
        $qryklp = mysqli_query($connect, $q);
        // var_dump(mysqli_fetch_assoc($qryklp));
        while ($rowklp = mysqli_fetch_assoc($qryklp)) {
            $kdklpcust = $rowklp['kdklpcust'];
            //aaa
            // $jumrech = mysqli_num_rows(mysqli_query($connect, $qh));
            // if ($jumrech > 0) {
            echo '<tr>
            <td height="10px" align="center">&nbsp;' .
                $no .
                '</td>
            <td colspan=13 height="10px" align="left"><b>Kelompok Customer : ' .
                $rowklp['kdklpcust'] .
                ' - ' .
                $rowklp['nmklpcust'] .
                '</b></td>';
            // }
            $qrygroupcust = mysqli_query($connect, "select jualh.*,tbcustomer.kdklpcust from jualh left outer join tbcustomer on jualh.kdcustomer=tbcustomer.kode where tbcustomer.kdklpcust='$kdklpcust' group by jualh.kdcustomer order by jualh.kdcustomer");
            $ng = 0;
            $gtsubtotal = 0;
            $gtdiscount = 0;
            $gtbayar = 0;
            $gtsisa = 0;
            $gtppn = 0;
            $gttotal = 0;
            $gtqty = 0;
            $no++;
            while ($rowgc = mysqli_fetch_assoc($qrygroupcust)) {
                $kdcustomer = $rowgc['kdcustomer'];
                $recjual = 0;
                $qh = "select jualh.*, tbcustomer.kdklpcust,tbcustomer.nmklpcust from jualh left join tbcustomer on tbcustomer.kode=jualh.kdcustomer where proses='Y' and jualh.kdcustomer='$kdcustomer'";
                $qh .= $kdklpcust == '' ? ' ' : " and tbcustomer.kdklpcust='$kdklpcust'";
                $qh .= $kdsales == '' ? ' ' : " and jualh.kdsales='$kdsales'";
                // $qh .= $kdcustomer == '' ? " " : " and jualh.kdcustomer='$kdcustomer'";
                $qh .= $pilihanppn == 'ppn' ? ' and jualh.ppn>0' : '';
                $qh .= $pilihanppn == 'nonppn' ? ' and jualh.ppn=0' : '';
                $qh .= $semuaperiode == 'Y' ? ' ' : " and (jualh.tgljual>='$tgl1' and jualh.tgljual<='$tgl2')";
                $qh .= ' order by jualh.kdcustomer';
                $queryh = mysqli_query($connect, $qh);
                // var_dump(mysqli_fetch_assoc($queryh));
                $gqty = 0;
                $gsubtotal = 0;
                $gdiscount = 0;
                $gppn = 0;
                $gtotal = 0;
                $gbayar = 0;
                $gsisa = 0;
                $recdok = mysqli_num_rows(mysqli_query($connect, $qh));
                //bbbb
                if ($recdok > 0) {
                    $ng++;
                    echo '<tr>
                        <td height="10px" align="right">&nbsp;' .
                        $ng .
                        '</td>
                        <td height="10px" colspan=11>&nbsp;Customer : ' .
                        $rowgc['kdcustomer'] .
                        '-' .
                        $rowgc['nmcustomer'] .
                        ', Sales : ' .
                        $rowgc['kdsales'] .
                        '-' .
                        $rowgc['nmsales'] .
                        '</td>';
                    echo '<td></td><td></td>';
                }
                while ($row = mysqli_fetch_assoc($queryh)) {
                    $nojual = $row['nojual'];
                    if ($semuabarang == 'Y') {
                        //semua barang
                        $queryd = mysqli_query(
                            $connect,
                            "select juald.*,jualh.sudahbayar,jualh.kurangbayar from juald inner join jualh on jualh.nojual=juald.nojual
                            where jualh.kdcustomer='$kdcustomer' and juald.nojual='$nojual'",
                        );
                    } else {
                        $queryd = mysqli_query(
                            $connect,
                            "select juald.*,jualh.sudahbayar,jualh.kurangbayar from juald inner join jualh on jualh.nojual=juald.nojual
                            where jualh.kdcustomer='$kdcustomer' and juald.nojual='$nojual' juald.kdbarang='$kdbarang'",
                        );
                    }
                    while ($detail = mysqli_fetch_assoc($queryd)) {
                        $harga = number_format($detail['harga'], 0, '.', ',');
                        $subtotal = $detail['harga'] * $detail['qty'];
                        $subtotalf = number_format($subtotal, 0, '.', ',');
                        $discount = $subtotal * ($detail['discount'] / 100);
                        $discountf = number_format($discount, 0, '.', ',');
                        $nppn = ($subtotal - $discount) * ($row['ppn'] / 100);
                        $nppnf = number_format($nppn, 0, '.', ',');
                        $nppnprf = number_format($row['ppn'], 0, '.', ',');
                        $total = $subtotal - $discount + $nppn;
                        $totalf = number_format($total, 0, '.', ',');
                        echo '<tr>
                        <td></td>
                        <td height="10px" align="left">' .
                            $row['nojual'] .
                            '</td>
                        <td height="10px" align="left">' .
                            $row['tgljual'] .
                            '</td>
                        <td height="10px" align="left">' .
                            $detail['kdbarang'] .
                            '</td>
                        <td height="10px" align="left">' .
                            $detail['nmbarang'] .
                            '</td>
                        <td height="10px" align="right">' .
                            $detail['qty'] .
                            '</td>
                        <td height="10px" align="right">' .
                            $harga .
                            '</td>
                        <td height="10px" align="right">' .
                            $subtotalf .
                            '</td>
                        <td height="10px" align="right">' .
                            $discountf .
                            '</td>
                        <td height="10px" align="right">' .
                            $nppnprf .
                            '</td>
                        <td height="10px" align="right">' .
                            $nppnf .
                            '</td>
                        <td height="10px" align="right">' .
                            $totalf .
                            '</td>
                        <td></td><td></td></tr>';
                        //subtotal
                        $gqty = $gqty + $detail['qty'];
                        $gsubtotal = $gsubtotal + $detail['subtotal'];
                        $gdiscount = $gdiscount + $discount;
                        $gppn = $gppn + $nppn;
                        $gtotal = $gtotal + $total;
                        //total
                        $gtqty = $gtqty + $detail['qty'];
                        $gtsubtotal = $gtsubtotal + $detail['subtotal'];
                        $gtdiscount = $gtdiscount + $discount;
                        $gtppn = $gtppn + $nppn;
                        $gttotal = $gttotal + $total;
                        //Grand total
                        $grandqty = $grandqty + $detail['qty'];
                        $grandsubtotal = $grandsubtotal + $detail['subtotal'];
                        $granddiscount = $granddiscount + $discount;
                        $grandppn = $grandppn + $nppn;
                        $grandtotal = $grandtotal + $total;
                    }
                    $gbayar = $gbayar + $row['sudahbayar'];
                    $gsisa = $gsisa + $row['kurangbayar'];
                    $gtbayar = $gtbayar + $row['sudahbayar'];
                    $gtsisa = $gtsisa + $row['kurangbayar'];
                    $grandbayar = $grandbayar + $row['sudahbayar'];
                    $grandsisa = $grandsisa + $row['kurangbayar'];
                }
                $gqtyf = number_format($gqty, 2, '.', ',');
                $gsubtotalf = number_format($gsubtotal, 0, '.', ',');
                $gdiscountf = number_format($gdiscount, 0, '.', ',');
                $gppnf = number_format($gppn, 0, '.', ',');
                $gtotalf = number_format($gtotal, 0, '.', ',');
                $gbayarf = number_format($gbayar, 0, '.', ',');
                $gsisaf = number_format($gsisa, 0, '.', ',');
                if ($recdok > 0) {
                    echo '<tr>
                    <td></td><td colspan="4" height="10px" align="left" style="font-weight:bold;">' .
                        'Subtotal &nbsp;</td>' .
                        '
                    <td align="right" style="font-weight:bold;">' .
                        $gqtyf .
                        '</td><td></td>' .
                        '
                    <td align="right" style="font-weight:bold;">' .
                        $gsubtotalf .
                        '</td>' .
                        '
                    <td align="right" style="font-weight:bold;">' .
                        $gdiscountf .
                        '</td><td></td>' .
                        '
                    <td align="right" style="font-weight:bold;">' .
                        $gppnf .
                        '</td>' .
                        '
                    <td align="right" style="font-weight:bold;">' .
                        $gtotalf .
                        '
                    <td align="right" style="font-weight:bold;">' .
                        $gbayarf .
                        '
                    <td align="right" style="font-weight:bold;">' .
                        $gsisaf .
                        '
                    </td></tr>';
                }
            }
            $gtqtyf = number_format($gtqty, 2, '.', ',');
            $gtsubtotalf = number_format($gtsubtotal, 0, '.', ',');
            $gtdiscountf = number_format($gtdiscount, 0, '.', ',');
            $gtppnf = number_format($gtppn, 0, '.', ',');
            $gttotalf = number_format($gttotal, 0, '.', ',');
            $gtbayarf = number_format($gtbayar, 0, '.', ',');
            $gtsisaf = number_format($gtsisa, 0, '.', ',');
            echo '<tr>
            <td></td><td colspan="4" height="10px" align="left" style="font-weight:bold;">' .
                'Total Kelompok Customer ' .
                $rowklp['kdklpcust'] .
                ' - ' .
                $rowklp['nmklpcust'] .
                '</td>' .
                '
            <td align="right" style="font-weight:bold;">' .
                $gtqtyf .
                '</td><td></td>' .
                '
            <td align="right" style="font-weight:bold;">' .
                $gtsubtotalf .
                '</td>' .
                '
            <td align="right" style="font-weight:bold;">' .
                $gtdiscountf .
                '</td><td></td>' .
                '
            <td align="right" style="font-weight:bold;">' .
                $gtppnf .
                '</td>' .
                '
            <td align="right" style="font-weight:bold;">' .
                $gttotalf .
                '
            <td align="right" style="font-weight:bold;">' .
                $gtbayarf .
                '
            <td align="right" style="font-weight:bold;">' .
                $gtsisaf .
                '
            </td></tr>';
        }
        $grandqtyf = number_format($grandqty, 2, '.', ',');
        $grandsubtotalf = number_format($grandsubtotal, 0, '.', ',');
        $granddiscountf = number_format($granddiscount, 0, '.', ',');
        $grandppnf = number_format($grandppn, 0, '.', ',');
        $grandtotalf = number_format($grandtotal, 0, '.', ',');
        $grandbayarf = number_format($grandbayar, 0, '.', ',');
        $grandsisaf = number_format($grandsisa, 0, '.', ',');
        echo '<tr>
        <td></td><td colspan="4" height="10px" align="left" style="font-weight:bold;">' .
            'Grand Total &nbsp;</td>' .
            '
        <td align="right" style="font-weight:bold;">' .
            $grandqtyf .
            '</td><td></td>' .
            '
        <td align="right" style="font-weight:bold;">' .
            $grandsubtotalf .
            '</td>' .
            '
        <td align="right" style="font-weight:bold;">' .
            $granddiscountf .
            '</td><td></td>' .
            '
        <td align="right" style="font-weight:bold;">' .
            $grandppnf .
            '</td>' .
            '
        <td align="right" style="font-weight:bold;">' .
            $grandtotalf .
            '
        <td align="right" style="font-weight:bold;">' .
            $grandbayarf .
            '
        <td align="right" style="font-weight:bold;">' .
            $grandsisaf .
            '
        </td></tr>';
        echo '</table>';
    } else {
        echo '<table table-layout="fixed"; cellpadding="2"; cellspacing="0"; style=font-size:11px; class="table table-striped table table-bordered;">
        <tr>
        <th width="30px" height="20"><font size="1" color="black"><b>NO.</th>
        <th width="75px"><font size="1" color="black"><b>KODE BARANG</th>
        <th width="300px"><font size="1" color="black"><b>NAMA BARANG</th>
        <th width="60px"><font size="1" color="black"><b>QTY</th>
        <th width="80px"><font size="1" color="black"><b>HARGA</th>
        <th width="100px"><font size="1" color="black"><b>SUBTOTAL</th>
        <th width="80px"><font size="1" color="black"><b>DISC.</th>
        <th width="50px"><font size="1" color="black"><b>PPN (%)</th>
        <th width="80px"><font size="1" color="black"><b>PPN</th>
        <th width="100px"><font size="1" color="black"><b>TOTAL</th>
        <th width="100px"><font size="1" color="black"><b>JUMLAH<br>PEMBAYARAN</th>
        <th width="100px"><font size="1" color="black"><b>SISA<br>PEMBAYARAN</th>
        </tr>';
        $no = 1;
        $grandqty = 0;
        $grandsubtotal = 0;
        $granddiscount = 0;
        $grandppn = 0;
        $grandtotal = 0;
        $grandbayar = 0;
        $grandsisa = 0;
        // $q = "select jualh.*,tbcustomer.kdklpcust,tbcustomer.nmklpcust from jualh inner join tbcustomer on tbcustomer.kode=jualh.kdcustomer where jualh.proses='Y'";
        // $q .= $kdsales == '' ? " " : " and jualh.kdsales='$kdsales'";
        // $q .= $kdklpcus=<>'' ? " " : " and tbcustomer.kdklpcust='$kdklpcust'";
        // $q .= " group by tbcustomer.kdklpcust order by tbcustomer.kdklpcust desc";
        // $qryklp = mysqli_query($connect, $q);
        // while ($rowklp = mysqli_fetch_assoc($qryklp)) {
        // 	$kdklpcust = $rowklp['kdklpcust'];
        $nno = 1;
        //aaa
        $qh = "select jualh.*, tbcustomer.kdklpcust,tbcustomer.nmklpcust from jualh inner join tbcustomer on tbcustomer.kode=jualh.kdcustomer where proses='Y'";
        $qh .= $kdklpcust == '' ? ' ' : " and tbcustomer.kdklpcust='$kdklpcust'";
        $qh .= $kdsales == '' ? ' ' : " and jualh.kdsales='$kdsales'";
        $qh .= $kdcustomer == '' ? ' ' : " and jualh.kdcustomer='$kdcustomer'";
        $qh .= $pilihanppn == 'ppn' ? ' and ppn>0' : '';
        $qh .= $pilihanppn == 'nonppn' ? ' and ppn=0' : '';
        $qh .= $semuaperiode == 'Y' ? ' ' : " and (tgljual>='$tgl1' and tgljual<='$tgl2')";
        $qh .= ' order by tgljual,nojual';
        // $jumrech = mysqli_num_rows(mysqli_query($connect, $qh));
        // if ($jumrech > 0) {
        // 	echo '<tr>
        // 			<td height="10px" align="center">&nbsp;' . $no . '</td>
        // 			<td colspan=11 height="10px" align="left"><b>Kelompok Customer : ' . $rowklp["kdklpcust"] . ' - ' . $rowklp["nmklpcust"] . '</b></td>';
        // }
        $gtsubtotal = 0;
        $gtdiscount = 0;
        $gtotalbayar = 0;
        $gtotalsisa = 0;
        $gtppn = 0;
        $gttotal = 0;
        $gtqty = 0;
        $queryh = mysqli_query($connect, $qh);
        while ($row = mysqli_fetch_assoc($queryh)) {
            // if ($row['kdklpcust'] == $kdklpcust) {
            $tgljual = $row['tgljual'];
            $nojual = $row['nojual'];
            $ppn = $row['ppn'];
            $sudahbayarf = number_format($row['sudahbayar'], 0, '.', ',');
            $kurangbayarf = number_format($row['kurangbayar'], 0, '.', ',');
            if ($semuabarang == 'Y') {
                //semua barang
                $queryd = mysqli_query($connect, "select * from juald where nojual='$nojual'");
            } else {
                $queryd = mysqli_query($connect, "select * from juald where nojual='$nojual' and kdbarang='$kdbarang'");
            }
            $jumrec = mysqli_num_rows($queryd);
            if ($jumrec > 0) {
                $gsubtotal = 0;
                $gdiscount = 0;
                $gppn = 0;
                $gtotal = 0;
                $jumqty = 0;
                echo '<tr>
                <td height="10px" align="right">&nbsp;' .
                    $nno .
                    '</td>
                <td height="10px" colspan=9>&nbsp;' .
                    $row['nojual'] .
                    ', ' .
                    $tgljual .
                    ', ' .
                    $row['kdcustomer'] .
                    '-' .
                    $row['nmcustomer'] .
                    ', ' .
                    $row['kdsales'] .
                    '-' .
                    $row['nmsales'] .
                    '</td>';
                echo '<td></td><td></td>';
                // echo '<td height="10px" align="right">' . $sudahbayarf  . '</td>
                // <td height="10px" align="right">' . $kurangbayarf  . '</td></tr>';
                while ($detail = mysqli_fetch_assoc($queryd)) {
                    $harga = number_format($detail['harga'], 0, '.', ',');
                    $subtotal = $detail['harga'] * $detail['qty'];
                    $subtotalf = number_format($subtotal, 0, '.', ',');
                    $discount = $subtotal * ($detail['discount'] / 100);
                    $discountf = number_format($discount, 0, '.', ',');
                    $nppn = ($subtotal - $discount) * ($ppn / 100);
                    $nppnf = number_format($nppn, 0, '.', ',');
                    $nppnprf = number_format($ppn, 0, '.', ',');
                    $total = $subtotal - $discount + $nppn;
                    $totalf = number_format($total, 0, '.', ',');
                    $gtqty = $gtqty + $detail['qty'];
                    $jumqty = $jumqty + $detail['qty'];
                    echo '<tr>
                    <td></td>
                    <td height="10px" align="left">' .
                        $detail['kdbarang'] .
                        '</td>
                    <td height="10px" align="left">' .
                        $detail['nmbarang'] .
                        '</td>
                    <td height="10px" align="right">' .
                        $detail['qty'] .
                        '</td>
                    <td height="10px" align="right">' .
                        $harga .
                        '</td>
                    <td height="10px" align="right">' .
                        $subtotalf .
                        '</td>
                    <td height="10px" align="right">' .
                        $discountf .
                        '</td>
                    <td height="10px" align="right">' .
                        $nppnprf .
                        '</td>
                    <td height="10px" align="right">' .
                        $nppnf .
                        '</td>
                    <td height="10px" align="right">' .
                        $totalf .
                        '</td>
                    <td></td><td></td></tr>';
                    $gsubtotal = $gsubtotal + $detail['subtotal'];
                    $gdiscount = $gdiscount + $discount;
                    $gppn = $gppn + $nppn;
                    $gtotal = $gtotal + $total;

                    $grandqty = $grandqty + $detail['qty'];
                    $grandsubtotal = $grandsubtotal + $detail['subtotal'];
                    $granddiscount = $granddiscount + $discount;
                    $grandppn = $grandppn + $nppn;
                    $grandtotal = $grandtotal + $total;
                }
                $grandbayar = $grandbayar + $row['sudahbayar'];
                $gsubtotalf = number_format($gsubtotal, 0, '.', ',');
                $gdiscountf = number_format($gdiscount, 0, '.', ',');
                $gppnf = number_format($gppn, 0, '.', ',');
                $gtotalf = number_format($gtotal, 0, '.', ',');
                $jumqtyf = number_format($jumqty, 2, '.', ',');
                $gtsubtotal = $gtsubtotal + $gsubtotal;
                $gtdiscount = $gtdiscount + $gdiscount;
                $gtppn = $gtppn + $gppn;
                $gttotal = $gttotal + $gtotal;
                $gtotalbayar = $gtotalbayar + $row['sudahbayar'];
                $gtotalsisa = $gtotalsisa + $row['kurangbayar'];
                echo '<tr>
                <td></td><td colspan="2" height="10px" colspan=4 align="left" style="color:blue;">' .
                    'Sub Total &nbsp;</td>' .
                    '
                <td align="right" style="color:blue;">' .
                    $jumqtyf .
                    '</td>' .
                    '
                <td></td>
                <td align="right" style="color:blue;">' .
                    $gsubtotalf .
                    '</td>' .
                    '
                <td align="right" style="color:blue;">' .
                    $gdiscountf .
                    '</td>' .
                    '<td></td>
                <td align="right" style="color:blue;">' .
                    $gppnf .
                    '</td>' .
                    '
                <td align="right" style="color:blue;">' .
                    $gtotalf .
                    '</td>
                <td height="10px" align="right" style="color:blue;">' .
                    $sudahbayarf .
                    '</td>
                <td height="10px" align="right" style="color:blue;">' .
                    $kurangbayarf .
                    '</td></tr></tr>';
                $nno++;
            }
            // }
        }
        $grandsisa = $grandtotal - $grandbayar;
        $gtsubtotalf = number_format($gtsubtotal, 0, '.', ',');
        $gtdiscountf = number_format($gtdiscount, 0, '.', ',');
        $gtppnf = number_format($gtppn, 0, '.', ',');
        $gttotalf = number_format($gttotal, 0, '.', ',');
        $gtotalbayarf = number_format($gtotalbayar, 0, '.', ',');
        $gtotalsisaf = number_format($gtotalsisa, 0, '.', ',');
        $gtqtyf = number_format($gtqty, 2, '.', ',');
        echo '<tr>
        <td></td><td colspan="2" height="10px" align="left" style="font-weight:bold;">' .
            'Total &nbsp;</td>' .
            '
        <td align="right" style="font-weight:bold;">' .
            $gtqtyf .
            '</td><td></td>' .
            '
        <td align="right" style="font-weight:bold;">' .
            $gtsubtotalf .
            '</td>' .
            '
        <td align="right" style="font-weight:bold;">' .
            $gtdiscountf .
            '</td><td></td>' .
            '
        <td align="right" style="font-weight:bold;">' .
            $gtppnf .
            '</td>' .
            '
        <td align="right" style="font-weight:bold;">' .
            $gttotalf .
            '
        <td align="right" style="font-weight:bold;">' .
            $gtotalbayarf .
            '
        <td align="right" style="font-weight:bold;">' .
            $gtotalsisaf .
            '
        </td></tr>';
        $no++;
    }
    $grandqtyf = number_format($grandqty, 2, '.', ',');
    $grandsubtotalf = number_format($grandsubtotal, 0, '.', ',');
    $granddiscountf = number_format($granddiscount, 0, '.', ',');
    $grandppnf = number_format($grandppn, 0, '.', ',');
    $grandtotalf = number_format($grandtotal, 0, '.', ',');
    $grandbayarf = number_format($grandbayar, 0, '.', ',');
    $grandsisaf = number_format($grandsisa, 0, '.', ',');
    // if ($kdklpcus=<>'') {
    // 	echo '<tr>
    // 		<td></td><td colspan="2" height="10px" align="left" style="font-weight:bold;">' . 'Grand Total &nbsp;</td>' . '
    // 		<td align="right" style="font-weight:bold;">' . $grandqtyf . '</td><td></td>' . '
    // 		<td align="right" style="font-weight:bold;">' . $grandsubtotalf . '</td>' . '
    // 		<td align="right" style="font-weight:bold;">' . $granddiscountf . '</td><td></td>' . '
    // 		<td align="right" style="font-weight:bold;">' . $grandppnf . '</td>' . '
    // 		<td align="right" style="font-weight:bold;">' . $grandtotalf . '
    // 		<td align="right" style="font-weight:bold;">' . $grandbayarf . '
    // 		<td align="right" style="font-weight:bold;">' . $grandsisaf . '
    // 		</td></tr>';
    // 	echo '</table>';
    // }
    // }
} else {
    echo '<table table-layout="fixed"; cellpadding="2"; cellspacing="0"; style=font-size:11px; class="table table-striped table table-bordered;">
    <tr>
    <th width="20px" height="20"><font size="1" color="black"><b>NO.</th>
    <th width="110px"><font size="1" color="black"><b>NOMOR</th>
    <th width="75px"><font size="1" color="black"><b>TANGGAL</th>
    <th width="60px"><font size="1" color="black"><b>KODE<br>CUSTOMER</th>
    <th width="350px"><font size="1" color="black"><b>CUSTOMER</th>
    <th width="120px"><font size="1" color="black"><b>SALES</th>
    <th width="80px"><font size="1" color="black"><b>CARA BAYAR</th>
    <th width="80px"><font size="1" color="black"><b>SUBTOTAL</th>
    <th width="75x"><font size="1" color="black"><b>DISC.</th>
    <th width="80px"><font size="1" color="black"><b>PPN</th>
    <th width="90px"><font size="1" color="black"><b>TOTAL</th>
    <th width="90px"><font size="1" color="black"><b>JUMLAH<br>PEMBAYARAN</th>
    <th width="90px"><font size="1" color="black"><b>SISA<br>PEMBAYARAN</th>
    </tr>';
    //ccc
    $nno = 1;
    $no = 1;
    $grandsubtotal = 0;
    $granddiscount = 0;
    $grandbayar = 0;
    $grandsisa = 0;
    $grandppn = 0;
    $grandtotal = 0;
    $grandqty = 0;
    $q = "select jualh.*,tbcustomer.kdklpcust,tbcustomer.nmklpcust from jualh inner join tbcustomer on tbcustomer.kode=jualh.kdcustomer where jualh.proses='Y'";
    $q .= $kdsales == '' ? ' ' : " and jualh.kdsales='$kdsales'";
    $q .= $kdklpcust == '' ? ' ' : " and tbcustomer.kdklpcust='$kdklpcust'";
    $q .= $kdcustomer == '' ? ' ' : " and jualh.kdcustomer='$kdcustomer'";
    $q .= $pilihanppn == 'ppn' ? ' and jualh.ppn>0' : '';
    $q .= $pilihanppn == 'nonppn' ? ' and jualh.ppn=0' : '';
    $q .= $semuaperiode == 'Y' ? ' ' : " and (jualh.tgljual>='$tgl1' and jualh.tgljual<='$tgl2')";
    $q .= $groupingcustomer == 'Y' ? ' group by tbcustomer.kdklpcust order by tbcustomer.kdklpcust desc' : ' group by tbcustomer.kdklpcust limit 1';
    $qryklp = mysqli_query($connect, $q);
    while ($rowklp = mysqli_fetch_assoc($qryklp)) {
        $kdklpcust = $rowklp['kdklpcust'];
        if ($groupingcustomer == 'Y') {
            echo '<tr>
            <td height="10px" align="center">&nbsp;<b>' .
                $no .
                '</td>
            <td colspan=12 height="10px" align="left"><b>Kelompok Customer : ' .
                $rowklp['kdklpcust'] .
                ' - ' .
                $rowklp['nmklpcust'] .
                '</b></td>';
            $nno = 1;
        }
        //aaa
        $qh = "select jualh.*, tbcustomer.kdklpcust,tbcustomer.nmklpcust from jualh inner join tbcustomer on tbcustomer.kode=jualh.kdcustomer where proses='Y'";
        $qh .= $kdklpcust == '' ? ' ' : " and tbcustomer.kdklpcust='$kdklpcust'";
        $qh .= $kdsales == '' ? ' ' : " and jualh.kdsales='$kdsales'";
        $qh .= $kdcustomer == '' ? ' ' : " and jualh.kdcustomer='$kdcustomer'";
        $qh .= $pilihanppn == 'ppn' ? ' and ppn>0' : '';
        $qh .= $pilihanppn == 'nonppn' ? ' and ppn=0' : '';
        $qh .= $semuaperiode == 'Y' ? ' ' : " and (tgljual>='$tgl1' and tgljual<='$tgl2')";
        // $qh .= " order by kdcustomer,tgljual";
        $qh .= $groupingcustomer == 'Y' ? ' order by kdcustomer,tgljual' : 'group by nojual order by tgljual';
        $kdcustomer = '';
        $nocust = 0;
        $gtsubtotal = 0;
        $gtdiscount = 0;
        $gtotalbayar = 0;
        $gtotalsisa = 0;
        $gtppn = 0;
        $gttotal = 0;
        $gtqty = 0;
        $queryh = mysqli_query($connect, $qh);
        while ($row = mysqli_fetch_assoc($queryh)) {
            if ($groupingcustomer == 'Y') {
                if ($row['kdklpcust'] == $kdklpcust) {
                    if ($nocust == 0 and $kdcustomer == $row['kdcustomer']) {
                        echo '<tr>
                        <td></td>
                        <td colspan=9 style="color:blue;">Total</td>
                        <td style="color:blue;text-align:right;">' .
                            $row['total'] .
                            '</td>
                        <td style="color:blue;text-align:right;">' .
                            $row['sudahbayar'] .
                            '</td>
                        <td style="color:blue;text-align:right;">' .
                            $row['kurangbayar'] .
                            '</td>';
                    }
                    if ($kdcustomer != $row['kdcustomer']) {
                        $nocust++;
                        $qjum = "select sum(total) as total,sum(kurangbayar) as kurangbayar,sum(sudahbayar) as sudahbayar from jualh where kdcustomer='$row[kdcustomer]'";
                        $qjum .= $kdsales == '' ? ' ' : " and jualh.kdsales='$kdsales'";
                        $qjum .= $pilihanppn == 'ppn' ? ' and ppn>0' : '';
                        $qjum .= $pilihanppn == 'nonppn' ? ' and ppn=0' : '';
                        $qjum .= $semuaperiode == 'Y' ? ' ' : " and (tgljual>='$tgl1' and tgljual<='$tgl2')";
                        $total = mysqli_fetch_assoc(mysqli_query($connect, $qjum));
                        if ($groupingcustomer == 'Y') {
                            echo '<tr>
                            <td></td>
                            <td colspan=9 style="color:blue;">Customer : ' .
                                $nocust .
                                '. ' .
                                $row['kdcustomer'] .
                                ' - ' .
                                $row['nmcustomer'] .
                                '</td>
                            <td style="color:blue;text-align:right">' .
                                number_format($total['total'], 0, '.', ',') .
                                '</td>
                            <td style="color:blue;text-align:right">' .
                                number_format($total['sudahbayar'], 0, '.', ',') .
                                '</td>
                            <td style="color:blue;text-align:right">' .
                                number_format($total['kurangbayar'], 0, '.', ',') .
                                '</td>';
                        }
                    }
                    $kdcustomer = $row['kdcustomer'];
                    $tgljual = $row['tgljual'];
                    $nojual = $row['nojual'];
                    $ppn = $row['ppn'];
                    $rp_ppn = $row['total_sementara'] * ($ppn / 100);
                    $subtotal = $row['subtotal'];
                    $total = $row['total'];
                    $subtotalf = number_format($subtotal, 0, '.', ',');
                    $rp_ppnf = number_format($rp_ppn, 0, '.', ',');
                    $totalf = number_format($total, 0, '.', ',');
                    $sudahbayarf = number_format($row['sudahbayar'], 0, '.', ',');
                    $kurangbayarf = number_format($row['kurangbayar'], 0, '.', ',');
                    if ($semuabarang == 'Y') {
                        //semua barang
                        $queryd = mysqli_query($connect, "select * from juald where nojual='$nojual'");
                    } else {
                        $queryd = mysqli_query($connect, "select * from juald where nojual='$nojual' and kdbarang='$kdbarang'");
                    }
                    $jumrec = mysqli_num_rows($queryd);
                    if ($jumrec > 0) {
                        $jumsubtotal = 0;
                        $jumdiscount = 0;
                        $rp_ppn = 0;
                        $total = 0;
                        while ($detail = mysqli_fetch_assoc($queryd)) {
                            $harga = $detail['harga'] * $detail['qty'];
                            $discount = $harga * ($detail['discount'] / 100);
                            $jumsubtotal = $jumsubtotal + $detail['harga'] * $detail['qty'];
                            $jumdiscount = $jumdiscount + $jumsubtotal * ($detail['discount'] / 100);
                            $rp_ppn = $rp_ppn + ($harga - $discount) * ($ppn / 100);
                            $total = $jumsubtotal - $jumdiscount + $rp_ppn;
                            $gtqty = $gtqty + $detail['qty'];
                        }
                        $subtotalf = number_format($jumsubtotal, 0, '.', ',');
                        $discountf = number_format($jumdiscount, 0, '.', ',');
                        $rp_ppnf = number_format($rp_ppn, 0, '.', ',');
                        $totalf = number_format($total, 0, '.', ',');
                        echo '<tr>
                        <td height="10px" align="right">&nbsp;' .
                            $nno .
                            '</td>
                        <td height="10px">' .
                            $nojual .
                            '</td>
                        <td height="10px" align="center">' .
                            $tgljual .
                            '</td>
                        <td height="10px">' .
                            $row['kdcustomer'] .
                            '</td>
                        <td height="10px">' .
                            $row['nmcustomer'] .
                            '</td>
                        <td height="10px">' .
                            $row['kdsales'] .
                            ' - ' .
                            $row['nmsales'] .
                            '</td>
                        <td height="10px">' .
                            $row['carabayar'] .
                            '</td>
                        <td height="10px" align="right">' .
                            $subtotalf .
                            '</td>
                        <td height="10px" align="right">' .
                            $discountf .
                            '</td>
                        <td height="10px" align="right">' .
                            $rp_ppnf .
                            '</td>
                        <td height="10px" align="right">' .
                            $totalf .
                            '</td>
                        <td height="10px" align="right">' .
                            $sudahbayarf .
                            '</td>
                        <td height="10px" align="right">' .
                            $kurangbayarf .
                            '</td>
                        </tr>';
                        $gtsubtotal = $gtsubtotal + $subtotal;
                        $gtdiscount = $gtdiscount + $discount;
                        $gtppn = $gtppn + $rp_ppn;
                        $gttotal = $gttotal + $total;
                        $gtotalbayar = $gtotalbayar + $row['sudahbayar'];
                        $gtotalsisa = $gtotalsisa + $row['kurangbayar'];

                        $grandsubtotal = $grandsubtotal + $subtotal;
                        $granddiscount = $granddiscount + $discount;
                        $grandbayar = $grandbayar + $row['sudahbayar'];
                        $grandppn = $grandppn + $rp_ppn;
                        $grandtotal = $grandtotal + $total;
                    }
                    $nno++;
                }
            } else {
                $kdcustomer = $row['kdcustomer'];
                $tgljual = $row['tgljual'];
                $nojual = $row['nojual'];
                $ppn = $row['ppn'];
                $rp_ppn = $row['total_sementara'] * ($ppn / 100);
                $subtotal = $row['subtotal'];
                $total = $row['total'];
                $subtotalf = number_format($subtotal, 0, '.', ',');
                $rp_ppnf = number_format($rp_ppn, 0, '.', ',');
                $totalf = number_format($total, 0, '.', ',');
                $sudahbayarf = number_format($row['sudahbayar'], 0, '.', ',');
                $kurangbayarf = number_format($row['kurangbayar'], 0, '.', ',');
                if ($semuabarang == 'Y') {
                    //semua barang
                    $queryd = mysqli_query($connect, "select * from juald where nojual='$nojual'");
                } else {
                    $queryd = mysqli_query($connect, "select * from juald where nojual='$nojual' and kdbarang='$kdbarang'");
                }
                $jumrec = mysqli_num_rows($queryd);
                if ($jumrec > 0) {
                    $jumsubtotal = 0;
                    $jumdiscount = 0;
                    $rp_ppn = 0;
                    $total = 0;
                    while ($detail = mysqli_fetch_assoc($queryd)) {
                        $harga = $detail['harga'] * $detail['qty'];
                        $discount = $harga * ($detail['discount'] / 100);
                        $jumsubtotal = $jumsubtotal + $detail['harga'] * $detail['qty'];
                        $jumdiscount = $jumdiscount + $jumsubtotal * ($detail['discount'] / 100);
                        $rp_ppn = $rp_ppn + ($harga - $discount) * ($ppn / 100);
                        $total = $jumsubtotal - $jumdiscount + $rp_ppn;
                        $gtqty = $gtqty + $detail['qty'];
                    }
                    $subtotalf = number_format($jumsubtotal, 0, '.', ',');
                    $discountf = number_format($jumdiscount, 0, '.', ',');
                    $rp_ppnf = number_format($rp_ppn, 0, '.', ',');
                    $totalf = number_format($total, 0, '.', ',');
                    echo '<tr>
                    <td height="10px" align="right">&nbsp;' .
                        $nno .
                        '</td>
                    <td height="10px">' .
                        $nojual .
                        '</td>
                    <td height="10px" align="center">' .
                        $tgljual .
                        '</td>
                    <td height="10px">' .
                        $row['kdcustomer'] .
                        '</td>
                    <td height="10px">' .
                        $row['nmcustomer'] .
                        '</td>
                    <td height="10px">' .
                        $row['kdsales'] .
                        ' - ' .
                        $row['nmsales'] .
                        '</td>
                    <td height="10px">' .
                        $row['carabayar'] .
                        '</td>
                    <td height="10px" align="right">' .
                        $subtotalf .
                        '</td>
                    <td height="10px" align="right">' .
                        $discountf .
                        '</td>
                    <td height="10px" align="right">' .
                        $rp_ppnf .
                        '</td>
                    <td height="10px" align="right">' .
                        $totalf .
                        '</td>
                    <td height="10px" align="right">' .
                        $sudahbayarf .
                        '</td>
                    <td height="10px" align="right">' .
                        $kurangbayarf .
                        '</td>
                    </tr>';
                    $gtsubtotal = $gtsubtotal + $subtotal;
                    $gtdiscount = $gtdiscount + $discount;
                    $gtppn = $gtppn + $rp_ppn;
                    $gttotal = $gttotal + $total;
                    $gtotalbayar = $gtotalbayar + $row['sudahbayar'];
                    $gtotalsisa = $gtotalsisa + $row['kurangbayar'];

                    $grandsubtotal = $grandsubtotal + $subtotal;
                    $granddiscount = $granddiscount + $discount;
                    $grandbayar = $grandbayar + $row['sudahbayar'];
                    $grandppn = $grandppn + $rp_ppn;
                    $grandtotal = $grandtotal + $total;
                }
                $nno++;
            }
        }
        $no++;
        $gtsubtotalf = number_format($gtsubtotal, 0, '.', ',');
        $gtdiscountf = number_format($gtdiscount, 0, '.', ',');
        $gtppnf = number_format($gtppn, 0, '.', ',');
        $gttotalf = number_format($gttotal, 0, '.', ',');
        $gtsudahbayarf = number_format($gtotalbayar, 0, '.', ',');
        $gtkurangbayarf = number_format($gtotalsisa, 0, '.', ',');
        $gtqtyf = number_format($gtqty, 2, '.', ',');
        if ($groupingcustomer == 'Y') {
            echo '<tr>
            <td colspan="7" height="10px" align="left" style="font-weight:bold;">&nbsp;' .
                'Total &nbsp;</td>' .
                '
            <td align="right" style="font-weight:bold;">' .
                $gtsubtotalf .
                '</td>' .
                '
            <td align="right" style="font-weight:bold;">' .
                $gtdiscountf .
                '</td>' .
                '
            <td align="right" style="font-weight:bold;">' .
                $gtppnf .
                '</td>' .
                '
            <td align="right" style="font-weight:bold;">' .
                $gttotalf .
                '
            <td align="right" style="font-weight:bold;">' .
                $gtsudahbayarf .
                '
            <td align="right" style="font-weight:bold;">' .
                $gtkurangbayarf .
                '
            </td></tr>';
        }
    }
    $grandsubtotalf = number_format($grandsubtotal, 0, '.', ',');
    $granddiscountf = number_format($granddiscount, 0, '.', ',');
    $grandbayarf = number_format($grandbayar, 0, '.', ',');
    $grandsisa = $grandtotal - $grandbayar;
    $grandsisaf = number_format($grandsisa, 0, '.', ',');
    $grandppnf = number_format($grandppn, 0, '.', ',');
    $grandtotalf = number_format($grandtotal, 0, '.', ',');
    $grandtqtyf = number_format($grandqty, 0, '.', ',');
    if ($kdklpcust == '') {
        echo '<tr>
        <td colspan="7" height="10px" align="left" style="font-weight:bold;">&nbsp;' .
            'Grand Total &nbsp;</td>' .
            '
        <td align="right" style="font-weight:bold;">' .
            $grandsubtotalf .
            '</td>' .
            '
        <td align="right" style="font-weight:bold;">' .
            $granddiscountf .
            '</td>' .
            '
        <td align="right" style="font-weight:bold;">' .
            $grandppnf .
            '</td>' .
            '
        <td align="right" style="font-weight:bold;">' .
            $grandtotalf .
            '
        <td align="right" style="font-weight:bold;">' .
            $grandbayarf .
            '
        <td align="right" style="font-weight:bold;">' .
            $grandsisaf .
            '
        </td></tr></table>';
    }
}

echo '<font size="1"><left>Tanggal cetak : ' . date('d-m-Y H:i:s a') . '<br>';
