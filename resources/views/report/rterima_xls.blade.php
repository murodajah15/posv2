<form method='get' action='rterima_export'>
    @csrf
    <?php
    $tgl1 = $tanggal1;
    $tgl2 = $tanggal2;
    $no = 1;
    ?>
    <input type="hidden" name="semuaperiode" value="<?= $semuaperiode ?>">
    <input type="hidden" name="tanggal1" value="<?= $tanggal1 ?>">
    <input type="hidden" name="tanggal2" value="<?= $tanggal2 ?>">
    <input type="hidden" name="tgl1" value="<?= $tanggal1 ?>">
    <input type="hidden" name="tgl2" value="<?= $tanggal2 ?>">
    <button type='submit' class='btn btn-danger'>Export ke Excel</button>
    <button type='button' class='btn btn-danger' onClick="window.print()">Print</button>
</form>

@include('report.rterima_view')
