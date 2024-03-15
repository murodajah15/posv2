@include('home.akses')
<?php
$pakai = session('pakai');
$tambah = session('tambah');
$edit = session('edit');
$hapus = session('hapus');
$proses = session('proses');
$unproses = session('unproses');
$cetak = session('cetak');

use Illuminate\Support\Facades\DB;

// date_default_timezone_set('Asia/Jakarta');
// $tgl11 = date('d-m-Y', strtotime($tanggal1));
// $tgl22 = date('d-m-Y', strtotime($tanggal2));

$nm_perusahaan = $saplikasi->nm_perusahaan;
$alamat_perusahaan = $saplikasi->alamat;
$telp_perusahaan = $saplikasi->telp;
$no = 1;

if ($semuaperiode != 'Y') {
    $tanggal = $tanggal1 . ' s/d ' . $tanggal2;
} else {
    $tanggal = 'Semua Periode';
}

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

$queryh = DB::table('soh')->where('proses', 'Y');
if ($semuaperiode != 'Y') {
    $queryh = $queryh->where('tglso', '>=', $tanggal1)->where('tglso', '<=', $tanggal2);
}
if ($kdcustomer != '') {
    $queryh = $queryh->where('kdcustomer', '=', $kdcustomer);
}
if ($kdsales != '') {
    $queryh = $queryh->where('kdsales', '=', $kdsales);
}
$queryh = $queryh->orderBy('tglso');
$queryh = $queryh->get();

?>
@include('report.judulreport')
<?php
echo 'Tanggal : ' .
    "$tanggal" .
    '<br></font>

  <table table-layout="fixed"; cellpadding="2"; cellspacing="0"; style=font-size:11px; class="table table-striped table table-bordered;">
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
$grandjual = 0;
$grandppn = 0;
$granddiscount = 0;
$no = 1;
foreach ($queryh as $row) {
    echo '<tr>
  <td align=center>' .
        $no .
        '</td>' .
        '
   <td colspan="9" width="573px" height="35px" align="left">' .
        'No. SO : ' .
        $row->noso .
        ', Tanggal : ' .
        date('d-m-Y', strtotime($row->tglso)) .
        ', No. Invoice : ' .
        $row->noreferensi .
        ', Biaya Lain : ' .
        $row->biaya_lain .
        ', Customer : ' .
        $row->nmcustomer .
        '</td>';
    $noso = $row->noso;
    if ($semuaperiode == 'Y') {
        $tanggal = 'Semua Periode';
        $queryd = DB::table('soh')
            ->where('soh.proses', 'Y')
            ->where('soh.noso', '=', $noso)
            ->join('sod', 'sod.noso', '=', 'soh.noso')
            ->select('soh.noso', 'soh.tglso', 'soh.noreferensi', 'soh.nmcustomer', 'sod.kdbarang', 'sod.nmbarang', 'sod.qty', 'sod.harga', 'sod.discount', 'sod.subtotal')
            ->get();
        // $queryd = mysqli_query($connect, "select soh.noso,soh.tglso,soh.noreferensi,soh.nmcustomer,sod.kdbarang,sod.nmbarang,
        // sod.qty,sod.harga,sod.discount,sod.subtotal from soh inner join sod on soh.noso=sod.noso where soh.proses='Y' and soh.noso='$noso'");
    } else {
        $tanggal = $tanggal1 . ' s/d ' . $tanggal2;
        $queryd = DB::table('soh')
            ->where('soh.proses', 'Y')
            ->where('soh.tglso', '>=', $tanggal1)
            ->where('soh.tglso', '<=', $tanggal2)
            ->where('soh.noso', '=', $noso)
            ->join('sod', 'sod.noso', '=', 'soh.noso')
            ->select('soh.noso', 'soh.tglso', 'soh.noreferensi', 'soh.nmcustomer', 'sod.kdbarang', 'sod.nmbarang', 'sod.qty', 'sod.harga', 'sod.discount', 'sod.subtotal')
            ->get();
        // $queryd = mysqli_query($connect, "select soh.noso,soh.tglso,soh.noreferensi,soh.nmcustomer,sod.kdbarang,sod.nmbarang,sod.qty,sod.harga,sod.discount,sod.subtotal from soh inner join sod on soh.noso=sod.noso where soh.proses='Y' and (soh.tglso>='$tgl1' and soh.tglso<='$tgl2') and sod.noso='$noso'");
    }
    $subtotaljual = 0;
    $subtotalppn = 0;
    $subtotaldiscount = 0;
    $jumsubtotal = 0;
    foreach ($queryd as $rowd) {
        $qty = number_format($rowd->qty, 2, '.', ',');
        $harga = number_format($rowd->harga, 0, '.', ',');
        $ndiscount = $rowd->qty * $rowd->harga * ($rowd->discount / 100);
        $discount = number_format($ndiscount, 0, '.', ',');
        $nppn = ($rowd->qty * $rowd->harga - $ndiscount) * ($row->ppn / 100);
        $ppn = number_format($nppn, 0, '.', ',');
        $harga = number_format($rowd->harga, 0, '.', ',');
        $nsubtotal = $rowd->qty * $rowd->harga - $ndiscount + $nppn;
        $subtotal = number_format($nsubtotal, 0, '.', ',');
        $njual = $rowd->qty * $rowd->harga;
        $jual = number_format($njual, 0, '.', ',');
        echo '<tr>
     <td></td>
     <td width="90px" >' .
            $rowd->kdbarang .
            '</td>
     <td width="200px" >' .
            $rowd->nmbarang .
            '</td>
     <td width="50px"  align="right">' .
            $qty .
            '</td>
     <td width="60px"  align="right">' .
            $harga .
            '</td>
     <td width="60px"  align="right">' .
            $jual .
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
        $grandjual = $grandjual + $njual;
        $grandppn = $grandppn + $nppn;
        $granddiscount = $granddiscount + $ndiscount;
        $subtotaljual = $subtotaljual + $njual;
        $subtotalppn = $subtotalppn + $nppn;
        $subtotaldiscount = $subtotaldiscount + $ndiscount;
        $grandtotal = $grandtotal + $nsubtotal;
        $jumsubtotal = $jumsubtotal + $nsubtotal;
    }
    $subtotaljual = number_format($subtotaljual, 0, '.', ',');
    $subtotalppn = number_format($subtotalppn, 0, '.', ',');
    $subtotaldiscount = number_format($subtotaldiscount, 0, '.', ',');
    $total = number_format($jumsubtotal, 0, '.', ',');
    echo '<tr><td colspan="5" height="20px" align="left">' .
        'Total' .
        '</td>
   <td height="20px" align="right">' .
        $subtotaljual .
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
$grandjual = number_format($grandjual, 0, '.', ',');
$grandppn = number_format($grandppn, 0, '.', ',');
$granddiscount = number_format($granddiscount, 0, '.', ',');
echo '<tr><td colspan="5" height="20px" align="left">' .
    'Grand Total' .
    '</td>
  <td height="20px" align="right">' .
    $grandjual .
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

echo '<font size="1"><left>Tanggal cetak : ' . date('d-m-Y H:i:s a') . '<br>';
