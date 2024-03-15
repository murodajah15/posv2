{{-- <form method='post' target='_blank' action='rhutang_export'> --}}
<form method='get' action='rhutang_export'>
    <?php
    date_default_timezone_set('Asia/Jakarta');
    $tanggal1 = $tanggal1;
    $tanggal2 = $tanggal2;
    $tgl1 = $tanggal1;
    $tgl2 = $tanggal2;
    $harbul = $bulanan;
    $no = 1;
    ?>
    <input type="hidden" name="tanggal1" value="<?= $tanggal1 ?>">
    <input type="hidden" name="tanggal2" value="<?= $tanggal2 ?>">
    <input type="hidden" name="tgl1" value="<?= $tgl1 ?>">
    <input type="hidden" name="tgl2" value="<?= $tgl2 ?>">
    <input type="hidden" name="belumlunas" value="<?= $belumlunas ?>">
    {{-- <input type="hidden" name="harbul" value="<?= $harbul ?>"> --}}
    <input type="hidden" name="semuaperiode" value="{{ $semuaperiode }}">
    <input type="hidden" name="semuasupplier" value="{{ $semuasupplier }}">
    <input type="hidden" name="groupingsupplier" value="{{ $groupingsupplier }}">
    <input type="hidden" name="kdsupplier" value="{{ $kdsupplier }}">
    <input type="hidden" name="nmsupplier" value="{{ $nmsupplier }}">

    <button type='submit' class='btn btn-danger'>Export ke Excel</button>
    <button type='button' class='btn btn-danger' onClick="window.print()">Print</button>
</form>

@include('report.rhutang_view')

<?php
// include 'report/rhutang_view';
?>
