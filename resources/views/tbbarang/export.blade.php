<?php
// fungsi header dengan mengirimkan raw data excel
header('Content-type: application/vnd-ms-excel');

// membuat nama file ekspor "export-to-excel.xls"
date_default_timezone_set('Asia/Jakarta');
$filename = 'Daftar Tabel Barang -' . date('d-m-Y H:i:s') . '.xls';
header("Content-Disposition: attachment; filename=$filename");

// tambahkan table
// include 'rfaktur_view.php';
// var_dump($data);

?>

<table>
    <tr>
        <th width="150">KODE</th>
        <th width="500">NAMA</th>
        <th width="70">LOKASI</th>
        <th width="70">MEREK</th>
        <th width="150">JENIS</th>
        <th width="100">SATUAN</th>
        <th width="70">NEGARA</th>
        <th width="150">MOVING</th>
        <th width="70">DISC</th>
        <th width="90">HRG.BELI</th>
        <th width="90">HRG.JUAL</th>
        <th width="70">STOCK</th>
        <th width="100">MIN.STOCK</th>
        <th width="100">MAX.STOCK</th>
        <th width="100">TGL.EXPIRED</th>
        <th width="80">NO.BACTH</th>
        {{-- <th width="70">AKTIF</th> --}}
        <th width="570">USER</th>
    </tr>
    @foreach ($tbbarang as $row)
        <tr>
            <td>{{ trim($row->kode) }}</td>
            <td>{{ $row->nama }}</td>
            <td>{{ $row->lokasi }}</td>
            <td>{{ $row->merek }}</td>
            <td>{{ $row->kdjnbrg . ' - ' . $row->nmjnbrg }}</td>
            <td>{{ $row->kdsatuan . ' - ' . $row->nmsatuan }}</td>
            <td>{{ $row->kdnegara . ' - ' . $row->nmnegara }}</td>
            <td>{{ $row->kdmove . ' - ' . $row->nmmove }}</td>
            <td>{{ $row->kddisc . ' - ' . $row->nmdisc }}</td>
            <td>{{ $row->harga_beli }}</td>
            <td>{{ $row->harga_jual }}</td>
            <td>{{ $row->stock }}</td>
            <td>{{ $row->stock_min }}</td>
            <td>{{ $row->stock_mak }}</td>
            <td>{{ $row->tglexpired }}</td>
            <td>{{ $row->nobatch }}</td>
            {{-- <td>{{ $row->aktif }}</td> --}}
            <td>{{ $row->user }}</td>
        </tr>
    @endforeach
</table>

{{-- @include('report.rbeli_view') --}}
