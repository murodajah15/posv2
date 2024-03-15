<form method='get' action='rjual_export'>
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
    <input type="hidden" name="rincian" value="<?= $rincian ?>">
    <input type="hidden" name="semuabarang" value="<?= $semuabarang ?>">
    <input type="hidden" name="kdbarang" value="<?= $kdbarang ?>">
    <input type="hidden" name="nmbarang" value="<?= $nmbarang ?>">
    <input type="hidden" name="semuacustomer" value="<?= $semuacustomer ?>">
    <input type="hidden" name="kdcustomer" value="<?= $kdcustomer ?>">
    <input type="hidden" name="nmcustomer" value="<?= $nmcustomer ?>">
    <input type="hidden" name="semuasales" value="<?= $semuasales ?>">
    <input type="hidden" name="kdsales" value="<?= $kdsales ?>">
    <input type="hidden" name="nmsales" value="<?= $nmsales ?>">
    <input type="hidden" name="semuaklpcust" value="<?= $semuaklpcust ?>">
    <input type="hidden" name="kdklpcust" value="<?= $kdklpcust ?>">
    <input type="hidden" name="nmklpcust" value="<?= $nmklpcust ?>">
    <input type="hidden" name="groupingcustomer" value="<?= $groupingcustomer ?>">
    <input type="hidden" name="pilihanppn" value="<?= $pilihanppn ?>">
    <button type='submit' class='btn btn-danger'>Export ke Excel</button>
    <button type='button' class='btn btn-danger' onClick="window.print()">Print</button>
</form>

@include('report.rjual_view')
