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
$qry = "select keterangan,carabayar,user_input,nokwitansi,tglkwitansi,nojual,nmcustomer,piutang,bayar,if(carabayar='Cash',bayar,0) as cash,
        if(carabayar='Transfer',bayar,0) as transfer,
        if(carabayar='Cek/Giro',bayar,0) as cek_giro,
        if(carabayar='Debit Card',bayar,0) as debit_card,
        if(carabayar='Credit Card',bayar,0) as credit_card,
        piutang-bayar as sisa_piutang from kasir_tagihan where proses='Y'";
// $qry .= $kdcustomer == "" ? " " : " and kdcustomer='$kdcustomer'";
$qry .= $carabayar == '' ? ' ' : " and carabayar='$carabayar'";
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

?>
@include('report.judulreport')
<?php
echo 'Tanggal : ' . $tanggal . '</font>';

if ($groupingcustomer == 'Y') {
    $query = mysqli_query($connect, 'select kdcustomer,nmcustomer from kasir_tagihand group by kdcustomer');
    echo '<table table-layout="fixed"; cellpadding="2"; cellspacing="0"; style=font-size:11px; class="table table-striped table table-bordered;">
        <tr>
          <th width="30px" height="20"><font size="1" color="black">NO.</th>
          <th width="120px"><font size="1" color="black">NO. KWITANSI</th>
          <th width="80px"><font size="1" color="black">TANGGAL</th>
          <th width="120px"><font size="1" color="black">NO. PENJUALAN</th>
          <th width="80px">CARA BAYAR</th>
          <th width="80px">TOTAL</th>
          <th width="120px">KASIR</th>
          <th width="120px">KETERANGAN</th>';
    $no = 0;
    $grandtotal = 0;
    while ($row = mysqli_fetch_assoc($query)) {
        $kdcustomer = $row['kdcustomer'];
        $qry = "select kasir_tagihan.*,kasir_tagihand.nokwitansi,kasir_tagihand.bayar from kasir_tagihan inner join kasir_tagihand 
        on kasir_tagihan.nokwitansi=kasir_tagihand.nokwitansi where kasir_tagihand.kdcustomer='$kdcustomer' and kasir_tagihan.proses='Y'";
        $qry .= $semuaperiode == 'Y' ? ' ' : " and (kasir_tagihan.tglkwitansi>='$tgl1' and kasir_tagihan.tglkwitansi<='$tgl2')";
        $qry .= $carabayar == '' ? ' ' : " and carabayar='$carabayar'";
        $qry .= $nmkasir == '' ? ' ' : " and nmkasir='$nmkasir'";
        $queryd = mysqli_query($connect, $qry);
        if (mysqli_num_rows($queryd) > 0) {
            $no++;
            echo '<tr><td align="center">' .
                $no .
                '</td>
            <td colspan="7">Customer : ' .
                $row['kdcustomer'] .
                ' - ' .
                $row['nmcustomer'] .
                '</td>';
            $nod = 0;
            $jumbayar = 0;
            while ($rowd = mysqli_fetch_assoc($queryd)) {
                $nod++;
                $bayarf = number_format($rowd['bayar'], 0, '.', ',');
                $jumbayar = $jumbayar + $rowd['bayar'];
                $tglkwitansif = $rowd['tglkwitansi'];
                echo '<tr><td align="right">' .
                    $nod .
                    '</td><td>' .
                    $rowd['nokwitansi'] .
                    '</td>
        <td>' .
                    $tglkwitansif .
                    '</td>
        <td>' .
                    $rowd['nojual'] .
                    '</td>
        <td>' .
                    $rowd['carabayar'] .
                    '</td>
        <td align="right">' .
                    $bayarf .
                    '</td>
        <td>' .
                    $rowd['user_input'] .
                    '</td>
        <td>' .
                    $rowd['keterangan'] .
                    '</td>';
            }
            $grandtotal = $grandtotal + $jumbayar;
            $jumbayarf = number_format($jumbayar, 0, '.', ',');
            echo '<tr><td colspan="6">Total</td><td align="right">' . $jumbayarf . '</td><td></td></tr>';
        }
    }
    $grandtotalf = number_format($grandtotal, 0, '.', ',');
    echo '<tr><td colspan="6">Grand Total</td><td align="right">' . $grandtotalf . '</td><td></td></tr>';
} else {
    if ($groupingcarabayar == 'Y') {
        echo '<table table-layout="fixed"; cellpadding="2"; cellspacing="0"; style=font-size:11px; class="table table-striped table table-bordered;">
        <tr>
          <th width="30px" height="20"><font size="1" color="black">NO.</th>
          <th width="120px"><font size="1" color="black">NO. KWITANSI</th>
          <th width="80px"><font size="1" color="black">TANGGAL</th>
          <th width="120px"><font size="1" color="black">NO. PENJUALAN</th>
          <th width="350px"><font size="1" color="black">CUSTOMER</th>
          <th width="80px">CARA BAYAR</th>
          <th width="80px">TOTAL</th>
          <th width="120px">KASIR</th>
        </tr>';
        $qry = "select carabayar from kasir_tagihan where proses='Y'";
        $qry .= $semuaperiode == 'Y' ? ' ' : " and (kasir_tagihan.tglkwitansi>='$tgl1' and kasir_tagihan.tglkwitansi<='$tgl2')";
        $qry .= 'group by carabayar';
        $querycarabayar = mysqli_query($connect, $qry);
        // var_dump(mysqli_fetch_assoc($querycarabayar));
        $gtbayar = 0;
        while ($rowcarabayar = mysqli_fetch_assoc($querycarabayar)) {
            $jumbayar = 0;
            $no = 0;
            $groupcarabayar = $rowcarabayar['carabayar'];
            $qryh = "select * from kasir_tagihan where carabayar='$groupcarabayar'";
            $qryh .= $semuaperiode == 'Y' ? ' ' : " and (kasir_tagihan.tglkwitansi>='$tgl1' and kasir_tagihan.tglkwitansi<='$tgl2')";
            $qryh .= 'order by tglkwitansi';
            $queryh = mysqli_query($connect, $qryh);
            while ($row = mysqli_fetch_assoc($queryh)) {
                $nokwitansi = $row['nokwitansi'];
                // echo '2' . $groupcarabayar . $nokwitansi . '<br>';
                $no++;
                if ($carabayar != '') {
                    if ($groupcarabayar == $carabayar) {
                        $queryd = mysqli_query($connect, "select * from kasir_tagihand where nokwitansi='$nokwitansi'");
                        while ($rowd = mysqli_fetch_assoc($queryd)) {
                            $tglkwitansif = $row['tglkwitansi'];
                            $bayarf = number_format($rowd['bayar'], 0, '.', ',');
                            echo '<tr><td align="center">' .
                                $no .
                                '</td>
              <td>' .
                                $row['nokwitansi'] .
                                '</td>
              <td>' .
                                $tglkwitansif .
                                '</td>
              <td>' .
                                $rowd['nojual'] .
                                '</td>
              <td>' .
                                $rowd['kdcustomer'] .
                                ' - ' .
                                $rowd['nmcustomer'] .
                                '</td>
              <td>' .
                                $row['carabayar'] .
                                '</td>
              <td align="right">' .
                                $bayarf .
                                '</td>
              <td>' .
                                $row['user_input'] .
                                '</td>
              </tr>';
                            $jumbayar = $jumbayar + $rowd['bayar'];
                            $gtbayar = $gtbayar + $rowd['bayar'];
                        }
                    }
                } else {
                    $queryd = mysqli_query($connect, "select * from kasir_tagihand where nokwitansi='$nokwitansi' ");
                    while ($rowd = mysqli_fetch_assoc($queryd)) {
                        $tglkwitansif = $row['tglkwitansi'];
                        $bayarf = number_format($rowd['bayar'], 0, '.', ',');
                        echo '<tr><td align="center">' .
                            $no .
                            '</td>
              <td>' .
                            $row['nokwitansi'] .
                            '</td>
              <td>' .
                            $tglkwitansif .
                            '</td>
              <td>' .
                            $rowd['nojual'] .
                            '</td>
              <td>' .
                            $rowd['kdcustomer'] .
                            ' - ' .
                            $rowd['nmcustomer'] .
                            '</td>
              <td>' .
                            $row['carabayar'] .
                            '</td>
              <td align="right">' .
                            $bayarf .
                            '</td>
              <td>' .
                            $row['user_input'] .
                            '</td>
              </tr>';
                        $jumbayar = $jumbayar + $rowd['bayar'];
                        $gtbayar = $gtbayar + $rowd['bayar'];
                    }
                }
            }
            $jumbayarf = number_format($jumbayar, 0, '.', ',');
            echo '<tr><td colspan=6> Total </td>
      <td style="text-align:right;">' .
                $jumbayarf .
                '</td><td></td>';
        }
        $gtbayarf = number_format($gtbayar, 0, '.', ',');
        echo '<tr><td colspan=6> Grand Total </td>
    <td style="text-align:right;">' .
            $gtbayarf .
            '</td><td></td>';
    } else {
        echo '<table table-layout="fixed"; cellpadding="2"; cellspacing="0"; style=font-size:11px; class="table table-striped table table-bordered;">
        <tr>
          <th width="30px" height="20" rowspan="2"><font size="1" color="black">NO.</th>
          <th width="90px" rowspan="2"><font size="1" color="black">NO. KWITANSI<br>NO. PPENJUALAN</th>
          <th width="75px" rowspan="2"><font size="1" color="black">TANGGAL</th>
          <th colspan="5">CARA BAYAR</th>
          <th width="80px" rowspan="2">TOTAL</th>
          <th width="150px" rowspan="2">KETERANGAN</th>
          <tr><th width="70px">TUNAI</th>
          <th width="70px">TRANSFER</th>
          <th width="70px">CEK/GIRO</th>
          <th width="70px">DEBIT<br>CARD</th>
          <th width="70px">CREDIT<br>CARD</th>
        </tr>';
        $gtotalcash = 0;
        $gtotaltransfer = 0;
        $gtotalcek_giro = 0;
        $gtotaldebit_card = 0;
        $gtotalcredit_card = 0;
        $gtotalbayar = 0;
        while ($row = mysqli_fetch_assoc($queryh)) {
            $nokwitansi = $row['nokwitansi'];
            $qryd = "select kasir_tagihan.keterangan,kasir_tagihand.nojual, kasir_tagihand.bayar, kasir_tagihand.kdcustomer,kasir_tagihand.nmcustomer,jualh.tgljual,
            if(kasir_tagihan.carabayar='Cash',kasir_tagihand.bayar,0) as cash,
            if(kasir_tagihan.carabayar='Transfer',kasir_tagihand.bayar,0) as transfer,
            if(kasir_tagihan.carabayar='Cek/Giro',kasir_tagihand.bayar,0) as cek_giro,
            if(kasir_tagihan.carabayar='Debit Card',kasir_tagihand.bayar,0) as debit_card,
            if(kasir_tagihan.carabayar='Credit Card',kasir_tagihand.bayar,0) as credit_card
            from kasir_tagihand inner join kasir_tagihan on kasir_tagihan.nokwitansi=kasir_tagihand.nokwitansi inner join jualh on jualh.nojual=kasir_tagihand.nojual where kasir_tagihand.nokwitansi='$nokwitansi' ";
            $qryd .= $kdcustomer == '' ? ' ' : " and kasir_tagihand.kdcustomer='$kdcustomer'";
            $queryd = mysqli_query($connect, $qryd);
            $rowd = mysqli_fetch_assoc($queryd);
            $record_d = mysqli_num_rows($queryd);
            if ($record_d > 0) {
                $piutang = number_format($row['piutang'], 0, '.', ',');
                $cash = number_format($row['cash'], 0, '.', ',');
                $transfer = number_format($row['transfer'], 0, '.', ',');
                $cek_giro = number_format($row['cek_giro'], 0, '.', ',');
                $debit_card = number_format($row['debit_card'], 0, '.', ',');
                $credit_card = number_format($row['credit_card'], 0, '.', ',');
                $sisa_piutang = number_format($row['sisa_piutang'], 0, '.', ',');
                $bayar = number_format($row['bayar'], 0, '.', ',');
                $tglkwitansi = $row['tglkwitansi'];
                echo '<tr>
            <td width="30px" align="center">' .
                    $no .
                    '</td>
            <td >' .
                    $row['nokwitansi'] .
                    '</td>
            <td >' .
                    $tglkwitansi .
                    '</td>
            <td colspan="7" >' .
                    $rowd['kdcustomer'] .
                    ' - ' .
                    $rowd['nmcustomer'] .
                    '</td>
          </tr>';
                $no++;
            }

            $totalcash = 0;
            $totaltransfer = 0;
            $totalcek_giro = 0;
            $totaldebit_card = 0;
            $totalcredit_card = 0;
            $totalsisa_piutang = 0;
            $totalpiutang = 0;
            $totalbayar = 0;
            $nod = 1;
            $queryd = mysqli_query($connect, $qryd);
            while ($d = mysqli_fetch_assoc($queryd)) {
                $cash = number_format($d['cash'], 0, '.', ',');
                $transfer = number_format($d['transfer'], 0, '.', ',');
                $cek_giro = number_format($d['cek_giro'], 0, '.', ',');
                $debit_card = number_format($d['debit_card'], 0, '.', ',');
                $credit_card = number_format($d['credit_card'], 0, '.', ',');
                $bayar = number_format($d['bayar'], 0, '.', ',');
                echo '<tr><td align="center">' .
                    $nod .
                    '</td>
                  <td>' .
                    $d['nojual'] .
                    '</td>
                  <td colspan="1">' .
                    $d['tgljual'] .
                    '</td>
                  <td align="right">' .
                    $cash .
                    '</td>
                  <td align="right">' .
                    $transfer .
                    '</td>
                  <td align="right">' .
                    $cek_giro .
                    '</td>
                  <td align="right">' .
                    $debit_card .
                    '</td>
                  <td align="right">' .
                    $credit_card .
                    '</td>
                  <td align="right">' .
                    $bayar .
                    '</td>
                  <td>' .
                    $d['keterangan'] .
                    '</td>
                  </tr>';
                $nod++;
                $totalcash = $totalcash + $d['cash'];
                $totaltransfer = $totaltransfer + $d['transfer'];
                $totalcek_giro = $totalcek_giro + $d['cek_giro'];
                $totaldebit_card = $totaldebit_card + $d['debit_card'];
                $totalcredit_card = $totalcredit_card + $d['credit_card'];
                $totalbayar = $totalbayar + $d['bayar'];
            }

            if ($record_d > 0) {
                $gtotalcash = $gtotalcash + $totalcash;
                $gtotaltransfer = $gtotaltransfer + $totaltransfer;
                $gtotalcek_giro = $gtotalcek_giro + $totalcek_giro;
                $gtotaldebit_card = $gtotaldebit_card + $totaldebit_card;
                $gtotalcredit_card = $gtotalcredit_card + $totalcredit_card;
                $gtotalbayar = $gtotalbayar + $totalbayar;

                $totalcash = number_format($totalcash, 0, '.', ',');
                $totaltransfer = number_format($totaltransfer, 0, '.', ',');
                $totalcek_giro = number_format($totalcek_giro, 0, '.', ',');
                $totaldebit_card = number_format($totaldebit_card, 0, '.', ',');
                $totalcredit_card = number_format($totalcredit_card, 0, '.', ',');
                $totalbayar = number_format($totalbayar, 0, '.', ',');
                echo '<tr><td colspan="3" height="20px" align="left">' .
                    'Total' .
                    '</td>
          <td height="20px" align="right">' .
                    $totalcash .
                    '</td>
          <td height="20px" align="right">' .
                    $totaltransfer .
                    '</td>
          <td height="20px" align="right">' .
                    $totalcek_giro .
                    '</td>
          <td height="20px" align="right">' .
                    $totaldebit_card .
                    '</td>
          <td height="20px" align="right">' .
                    $totalcredit_card .
                    '</td>
          <td height="20px" align="right">' .
                    $totalbayar .
                    '</td>
          <td></td>
        </tr>';
            }
        }
        $gtotalcash = number_format($gtotalcash, 0, '.', ',');
        $gtotaltransfer = number_format($gtotaltransfer, 0, '.', ',');
        $gtotalcek_giro = number_format($gtotalcek_giro, 0, '.', ',');
        $gtotaldebit_card = number_format($gtotaldebit_card, 0, '.', ',');
        $gtotalcredit_card = number_format($gtotalcredit_card, 0, '.', ',');
        $gtotalbayar = number_format($gtotalbayar, 0, '.', ',');
        echo '<tr><td colspan="3" height="20px" align="left">' .
            'Grand Total' .
            '</td>
      <td height="20px" align="right">' .
            $gtotalcash .
            '</td>
      <td height="20px" align="right">' .
            $gtotaltransfer .
            '</td>
      <td height="20px" align="right">' .
            $gtotalcek_giro .
            '</td>
      <td height="20px" align="right">' .
            $gtotaldebit_card .
            '</td>
      <td height="20px" align="right">' .
            $gtotalcredit_card .
            '</td>
      <td height="20px" align="right">' .
            $gtotalbayar .
            '</td>
      <td></td>
    </tr>';

        echo '</table><font size="1"><left>Tanggal cetak : ' . date('d-m-Y H:i:s a');

        echo '<br><br><table border="0">
          <tr>
          <th width="30px" height="50" valign="top"><font size="1" color="black">KASIR</th>
          <tr><td height="20px" width="120" align="center">' .
            $nmkasir .
            '</td>
        </tr>';
    }
}
// <tr><td height="20px" width="120" align="center">( ' . $nmkasir . ')</td>
