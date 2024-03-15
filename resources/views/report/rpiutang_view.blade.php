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

if ($semuacustomer != 'Y') {
    echo 'Customer : ' . $kdcustomer . ' - ' . $nmcustomer;
} else {
    $kdcustomer = '';
}

$tanggal = $semuaperiode == 'Y' ? 'Semua Periode' : $tanggal1 . ' s/d ' . $tanggal2;

?>
@include('report.judulreport')
<?php if ($bulanan == 'N') {
    echo '<font size="2">HARIAN<br>Tanggal : ' . $tanggal . '<br></font>';
    if ($semuacustomer == 'Y') {
        //semua customer
        if ($semuaperiode == 'Y') {
            $tanggal = 'Semua Periode';
            if ($belumlunas == 'Y') {
                $queryh = mysqli_query($connect, "select nojual,tgljual,nojual,nmcustomer,noinvoice,tglinvoice,carabayar,total,sudahbayar,kurangbayar from jualh where proses='Y' and kurangbayar>0 order by tgljual");
            } else {
                $queryh = mysqli_query($connect, "select nojual,tgljual,nojual,nmcustomer,noinvoice,tglinvoice,carabayar,total,sudahbayar,kurangbayar from jualh where proses='Y' order by tgljual");
            }
        } else {
            $tanggal = $tanggal1 . ' s/d ' . $tanggal2;
            if ($belumlunas == 'Y') {
                $queryh = mysqli_query($connect, "select nojual,tgljual,nojual,nmcustomer,noinvoice,tglinvoice,carabayar,total,sudahbayar,kurangbayar from jualh  where proses='Y' and (tgljual>='$tgl1' and tgljual<='$tgl2') and kurangbayar>0  order by tgljual");
            } else {
                $queryh = mysqli_query($connect, "select nojual,tgljual,nojual,nmcustomer,noinvoice,tglinvoice,carabayar,total,sudahbayar,kurangbayar from jualh  where proses='Y' and (tgljual>='$tgl1' and tgljual<='$tgl2') order by tgljual");
            }
        }
    } else {
        if ($semuaperiode == 'Y') {
            $tanggal = 'Semua Periode';
            if ($belumlunas == 'Y') {
                $queryh = mysqli_query($connect, "select nojual,tgljual,nojual,nmcustomer,noinvoice,tglinvoice,carabayar,total,sudahbayar,kurangbayar from jualh where proses='Y' and kurangbayar>0 and kdcustomer='$kdcustomer' order by tgljual");
            } else {
                $queryh = mysqli_query($connect, "select nojual,tgljual,nojual,nmcustomer,noinvoice,tglinvoice,carabayar,total,sudahbayar,kurangbayar from jualh where proses='Y' and kdcustomer='$kdcustomer' order by tgljual");
            }
        } else {
            $tanggal = $tanggal1 . ' s/d ' . $tanggal2;
            if ($belumlunas == 'Y') {
                $queryh = mysqli_query($connect, "select nojual,tgljual,nojual,nmcustomer,noinvoice,tglinvoice,carabayar,total,sudahbayar,kurangbayar from jualh  where proses='Y' and (tgljual>='$tgl1' and tgljual<='$tgl2') and kurangbayar>0 and kdcustomer='$kdcustomer' order by tgljual");
            } else {
                $queryh = mysqli_query($connect, "select nojual,tgljual,nojual,nmcustomer,noinvoice,tglinvoice,carabayar,total,sudahbayar,kurangbayar from jualh  where proses='Y' and (tgljual>='$tgl1' and tgljual<='$tgl2') and kurangbayar>0 and kdcustomer='$kdcustomer' and kurangbayar>0 order by tgljual");
            }
            //echo $tgl1.'  '.$tgl2;
        }
    }

    $cek = mysqli_num_rows($queryh);
    if (empty($cek)) {
        echo '<script>
            alert(\'Tidak Ada sesuai kriteria\')
                    window.close()
        </script>';
    }

    if ($groupingcustomer == 'Y') {
        echo '<table table-layout="fixed"; cellpadding="2"; cellspacing="0"; style=font-size:11px; class="table table-striped table table-bordered;">
      <tr>
        <th width="30px" height="20"><font size="1" color="black">NO.</th>
        <th width="110px"><font size="1" color="black">NO. JUAL</th>
        <th width="75px"><font size="1" color="black">TGL. JUAL</th>
        <th width="90px"><font size="1" color="black">CARA BAYAR</th>
        <th width="80px"><font size="1" color="black">PIUTANG</th>
        <th width="80px"><font size="1" color="black">BAYAR</th>
        <th width="80px"><font size="1" color="black">SISA</th>
      </tr>';
        $gttotal = 0;
        $gtsudahbayar = 0;
        $gtkurangbayar = 0;
        if ($semuaperiode == 'Y') {
            //semua periode
            if ($semuacustomer == 'Y') {
                //semua customer
                if ($belumlunas == 'Y') {
                    $querygroup = mysqli_query($connect, "select * from jualh where proses='Y' and kurangbayar>0 group by kdcustomer order by kdcustomer");
                } else {
                    $querygroup = mysqli_query($connect, "select * from jualh where proses='Y' group by kdcustomer order by kdcustomer");
                }
            } else {
                if ($belumlunas == 'Y') {
                    $querygroup = mysqli_query($connect, "select * from jualh where proses='Y' and kurangbayar>0 and kdcustomer='$kdcustomer' group by kdcustomer order by kdcustomer");
                } else {
                    $querygroup = mysqli_query($connect, "select * from jualh where proses='Y' and kdcustomer='$kdcustomer' group by kdcustomer order by kdcustomer");
                }
            }
        } else {
            if ($belumlunas == 'Y') {
                $querygroup = mysqli_query($connect, "select * from jualh where proses='Y' and kurangbayar>0 and (tgljual>='$tgl1' and tgljual<='$tgl2') group by kdcustomer order by kdcustomer");
            } else {
                $querygroup = mysqli_query($connect, "select * from jualh where proses='Y' and (tgljual>='$tgl1' and tgljual<='$tgl2') group by kdcustomer order by kdcustomer");
            }
        }
        while ($rowgroup = mysqli_fetch_assoc($querygroup)) {
            $kdcustomer = $rowgroup['kdcustomer'];
            $customer = $rowgroup['kdcustomer'] . ' - ' . $rowgroup['nmcustomer'];
            echo '<tr>
     <td style="text-align:center;color:blue;">' .
                $no .
                '</td>
     <td colspan=11 style="color:blue;">Customer : ' .
                $customer .
                '</td>
    </tr>';
            if ($semuaperiode == 'Y') {
                //semua periode
                if ($belumlunas == 'Y') {
                    $queryh = mysqli_query($connect, "select * from jualh where proses='Y' and kdcustomer='$kdcustomer' and kurangbayar>0 order by tgljual");
                } else {
                    $queryh = mysqli_query($connect, "select * from jualh where proses='Y' and kdcustomer='$kdcustomer' order by tgljual");
                }
            } else {
                if ($belumlunas == 'Y') {
                    $queryh = mysqli_query($connect, "select * from jualh where proses='Y' and kdcustomer='$kdcustomer' and kurangbayar>0 and (tgljual>='$tgl1' and tgljual<='$tgl2') order by tgljual");
                } else {
                    $queryh = mysqli_query($connect, "select * from jualh where proses='Y' and kdcustomer='$kdcustomer' and (tgljual>='$tgl1' and tgljual<='$tgl2') order by tgljual");
                }
            }
            $nno = 1;
            $jumtotal = 0;
            $jumsudahbayar = 0;
            $jumkurangbayar = 0;
            while ($rowh = mysqli_fetch_assoc($queryh)) {
                $tgljualf = date('d-m-Y', strtotime($rowh['tgljual']));
                $tgljual = $rowh['tgljual'];
                $totalf = number_format($rowh['total'], 0, '.', ',');
                $sudahbayarf = number_format($rowh['sudahbayar'], 0, '.', ',');
                $kurangbayarf = number_format($rowh['kurangbayar'], 0, '.', ',');
                echo '<tr>
     <td style="text-align:center;">' .
                    $nno .
                    '</td>
          <td>' .
                    $rowh['nojual'] .
                    '</td>
     <td>' .
                    $tgljual .
                    '</td>
     <td>' .
                    $rowh['carabayar'] .
                    '</td>
     <td style="text-align:right;">' .
                    $totalf .
                    '</td>
     <td style="text-align:right;">' .
                    $sudahbayarf .
                    '</td>
     <td style="text-align:right;">' .
                    $kurangbayarf .
                    '</td>
    </tr>';
                $nno++;
                $jumtotal = $jumtotal + $rowh['total'];
                $jumsudahbayar = $jumsudahbayar + $rowh['sudahbayar'];
                $jumkurangbayar = $jumkurangbayar + $rowh['kurangbayar'];
                $gttotal = $gttotal + $rowh['total'];
                $gtsudahbayar = $gtsudahbayar + $rowh['sudahbayar'];
                $gtkurangbayar = $gtkurangbayar + $rowh['kurangbayar'];
            }
            $no++;
            $jumtotalf = number_format($jumtotal, 0, '.', ',');
            $jumsudahbayarf = number_format($jumsudahbayar, 0, '.', ',');
            $jumkurangbayarf = number_format($jumkurangbayar, 0, '.', ',');
            echo '<tr><td colspan="4" height="20px" align="left">&nbsp;' .
                'Total' .
                '&nbsp;</td>
      <td style="text-align:right;">' .
                $jumtotalf .
                '</td>
      <td style="text-align:right;">' .
                $jumsudahbayarf .
                '</td>
      <td style="text-align:right;">' .
                $jumkurangbayarf .
                '</td>';
        }
        if ($semuacustomer == 'Y') {
            //semua customer
            $gttotalf = number_format($gttotal, 0, '.', ',');
            $gtsudahbayarf = number_format($gtsudahbayar, 0, '.', ',');
            $gtkurangbayarf = number_format($gtkurangbayar, 0, '.', ',');
            echo '<tr><td colspan="4" height="20px" align="left">&nbsp;' .
                'Grand Total' .
                '&nbsp;</td>
        <td style="text-align:right;">' .
                $gttotalf .
                '</td>
        <td style="text-align:right;">' .
                $gtsudahbayarf .
                '</td>
        <td style="text-align:right;">' .
                $gtkurangbayarf .
                '</td>';
        }
        echo '</table>';
    } else {
        echo '<table table-layout="fixed"; cellpadding="2"; cellspacing="0"; style=font-size:11px; class="table table-striped table table-bordered;">
      <tr>
        <th width="30px" height="20"><font size="1" color="black">NO.</th>
        <th width="110px"><font size="1" color="black">NO. JUAL</th>
        <th width="75px"><font size="1" color="black">TGL. JUAL</th>
        <th width="300px"><font size="1" color="black">CUSTOMER</th>
        <th width="90px"><font size="1" color="black">CARA BAYAR</th>
        <th width="80px"><font size="1" color="black">PIUTANG</th>
        <th width="80px"><font size="1" color="black">BAYAR</th>
        <th width="80px"><font size="1" color="black">SISA</th>
      </tr>';
        $totalpiutang = 0;
        $totalsisa_piutang = 0;
        $totalbayar = 0;
        while ($row = mysqli_fetch_assoc($queryh)) {
            //$subtotal_ppn = $rowd['subtotal'] - ($rowd['subtotal'] * ($row['ppn']/100));
            $piutang = number_format($row['total'], 0, '.', ',');
            $sudahbayar = number_format($row['sudahbayar'], 0, '.', ',');
            $kurangbayar = number_format($row['kurangbayar'], 0, '.', ',');
            $tgljual = $row['tgljual'];
            $tglinvoice = $row['tglinvoice'];
            echo '<tr>
        <td width="30px"  align="center">' .
                $no .
                '</td>
        <td>&nbsp;' .
                $row['nojual'] .
                '</td>
        <td>&nbsp;' .
                $tgljual .
                '</td>
        <td>&nbsp;' .
                $row['nmcustomer'] .
                '</td>
        <td>&nbsp;' .
                $row['carabayar'] .
                '</td>
        <td align="right">' .
                $piutang .
                '</td>
        <td align="right">' .
                $sudahbayar .
                '</td>
        <td align="right">' .
                $kurangbayar .
                '</td>
      </tr>';
            $no++;
            $totalsisa_piutang = $totalsisa_piutang + $row['kurangbayar'];
            $totalpiutang = $totalpiutang + $row['total'];
            $totalbayar = $totalbayar + $row['sudahbayar'];
        }
        $totalpiutang = number_format($totalpiutang, 0, '.', ',');
        $totalsisa_piutang = number_format($totalsisa_piutang, 0, '.', ',');
        $totalbayar = number_format($totalbayar, 0, '.', ',');
        echo '<tr><td colspan="5"  align="left">&nbsp;' .
            'Total' .
            '&nbsp;</td>
    <td  align="right">' .
            $totalpiutang .
            '</td>
    <td  align="right">' .
            $totalbayar .
            '</td>
    <td  align="right">' .
            $totalsisa_piutang .
            '</td>
  </tr>';
    }
    echo '</table><font size="1"><left>Tanggal cetak : ' . date('d-m-Y H:i:s a');
} else {
    //Bulanan
    if ($semuacustomer == 'Y') {
        //semua customer
        if ($semuaperiode == 'Y') {
            $tanggal = 'Semua Periode';
            if ($belumlunas == 'Y') {
                $queryh = mysqli_query($connect, "select * from jualh where proses='Y' group by tgljual order by tgljual and kurangbayar>0");
            } else {
                $queryh = mysqli_query($connect, "select * from jualh where proses='Y' group by tgljual order by tgljual");
            }
        } else {
            $tanggal = $tanggal1 . ' s/d ' . $tanggal2;
            if ($belumlunas == 'Y') {
                $queryh = mysqli_query($connect, "select * from jualh where proses='Y' and (tgljual>='$tgl1' and tgljual<='$tgl2') group by tgljual order by tgljual");
            } else {
                $queryh = mysqli_query($connect, "select * from jualh where proses='Y' and (tgljual>='$tgl1' and tgljual<='$tgl2') and kurangbayar>0 group by tgljual order by tgljual");
            }
            //echo $tgl1.'  '.$tgl2;
        }
    } else {
        if ($semuaperiode == 'Y') {
            $tanggal = 'Semua Periode';
            if ($belumlunas == 'Y') {
                $queryh = mysqli_query($connect, "select * from jualh where proses='Y' and kdcustomer='$kdcustomer' group by tgljual order by tgljual and kurangbayar>0");
            } else {
                $queryh = mysqli_query($connect, "select * from jualh where proses='Y' and kdcustomer='$kdcustomer' group by tgljual order by tgljual");
            }
        } else {
            $tanggal = $tanggal1 . ' s/d ' . $tanggal2;
            if ($belumlunas == 'Y') {
                $queryh = mysqli_query($connect, "select * from jualh where proses='Y' and (tgljual>='$tgl1' and tgljual<='$tgl2') and kdcustomer='$kdcustomer' group by tgljual order by tgljual");
            } else {
                $queryh = mysqli_query($connect, "select * from jualh where proses='Y' and (tgljual>='$tgl1' and tgljual<='$tgl2') and kdcustomer='$kdcustomer' group by tgljual order by tgljual and kurangbayar>0");
            }
            //echo $tgl1.'  '.$tgl2;
        }
    }

    $cek = mysqli_num_rows($queryh);
    if (empty($cek)) {
        echo '<script>
            alert(\'Tidak Ada sesuai kriteria\')
                    window.close()
        </script>';
    }

    echo '<font size="2">BULANAN<br>Tanggal : ' . $tanggal . '<br></font>';

    echo '<table table-layout="fixed"; cellpadding="2"; cellspacing="0"; style=font-size:11px; class="table table-striped table table-bordered;">
      <tr>
        <th width="30px" height="20"><font size="1" color="black">NO.</th>
        <th width="75px"><font size="1" color="black">TGL. JUAL</th>
        <th width="90px"><font size="1" color="black">PIUTANG</th>
        <th width="90px"><font size="1" color="black">BAYAR</th>
        <th width="90px"><font size="1" color="black">SISA</th>
      </tr>';
    $totalpiutang = 0;
    $totalsisa_piutang = 0;
    $totalbayar = 0;
    while ($row = mysqli_fetch_assoc($queryh)) {
        $tgljual = $row['tgljual'];
        if ($belumlunas == 'Y') {
            $queryhlagi = mysqli_query($connect, "select sum(total) as total,sum(subtotal) as subtotal,sum(sudahbayar) as sudahbayar,sum(kurangbayar) as kurangbayar from jualh where tgljual='$tgljual' and kurangbayar>0 and proses='Y'");
        } else {
            $queryhlagi = mysqli_query($connect, "select sum(total) as total,sum(subtotal) as subtotal,sum(sudahbayar) as sudahbayar,sum(kurangbayar) as kurangbayar from jualh where tgljual='$tgljual' and proses='Y'");
        }
        while ($rowhlagi = mysqli_fetch_assoc($queryhlagi)) {
            $piutang = number_format($rowhlagi['total'], 0, '.', ',');
            $sudahbayar = number_format($rowhlagi['sudahbayar'], 0, '.', ',');
            $kurangbayar = number_format($rowhlagi['kurangbayar'], 0, '.', ',');
            $totalsisa_piutang = $totalsisa_piutang + $rowhlagi['kurangbayar'];
            $totalpiutang = $totalpiutang + $rowhlagi['total'];
            $totalbayar = $totalbayar + $rowhlagi['sudahbayar'];
        }
        $month = date('m', strtotime($tgljual));
        echo '<tr>
        <td width="30px"  align="center">' .
            $no .
            '</td>
        <td>&nbsp;' .
            $tgljual .
            '</td>
        <td align="right">' .
            $piutang .
            '</td>
        <td align="right">' .
            $sudahbayar .
            '</td>
        <td align="right">' .
            $kurangbayar .
            '</td>
      </tr>';
        // echo '<tr>
        //     <td width="30px"  align="center">' . $no . '</td>
        //     <td>&nbsp;' . $tgljual . '</td>
        //     <td align="right">&nbsp;' . $piutang . '&nbsp;</td>
        //     <td align="right">&nbsp;' . $sudahbayar . '&nbsp;</td>
        //     <td align="right">&nbsp;' . $kurangbayar . '&nbsp;</td>
        //   </tr>';
        $no++;
    }
    $totalpiutang = number_format($totalpiutang, 0, '.', ',');
    $totalsisa_piutang = number_format($totalsisa_piutang, 0, '.', ',');
    $totalbayar = number_format($totalbayar, 0, '.', ',');
    echo '<tr><td colspan="2"  align="left">&nbsp;' .
        'Total' .
        '&nbsp;</td>
    <td  align="right">' .
        $totalpiutang .
        '</td>
    <td  align="right">' .
        $totalbayar .
        '</td>
    <td  align="right">' .
        $totalsisa_piutang .
        '</td>
  </tr>';
    // echo '<tr><td colspan="2"  align="left">&nbsp;' . "Total" . '&nbsp;</td>
    //   <td  align="right">&nbsp;' . $totalpiutang . '&nbsp;</td>
    //   <td  align="right">&nbsp;' . $totalbayar . '&nbsp;</td>
    //   <td  align="right">&nbsp;' . $totalsisa_piutang . '&nbsp;</td>
    // </tr>';
    echo '</table><font size="1"><left>Tanggal cetak : ' . date('d-m-Y H:i:s a');
}
