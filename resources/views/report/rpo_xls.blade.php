<form method='get' action='rpo_export'>
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
    <input type="hidden" name="outstanding" value="<?= $outstanding ?>">
    <input type="hidden" name="rincian" value="<?= $rincian ?>">
    <input type="hidden" name="semuabarang" value="<?= $semuabarang ?>">
    <input type="hidden" name="kdbarang" value="<?= $kdbarang ?>">
    <input type="hidden" name="nmbarang" value="<?= $nmbarang ?>">
    <input type="hidden" name="semuasupplier" value="<?= $semuasupplier ?>">
    <input type="hidden" name="kdsupplier" value="<?= $kdsupplier ?>">
    <input type="hidden" name="nmsupplier" value="<?= $nmsupplier ?>">
    <input type="hidden" name="groupingsupplier" value="<?= $groupingsupplier ?>">
    <button type='submit' class='btn btn-danger'>Export ke Excel</button>
    <button type='button' class='btn btn-danger' onClick="window.print()">Print</button>
</form>

@include('report.rpo_view')
