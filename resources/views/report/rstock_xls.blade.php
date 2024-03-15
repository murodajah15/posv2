<form method='get' action='rstock_export'>
    @csrf
    <?php
    $tgl1 = $tanggal1;
    $tgl2 = $tanggal2;
    $no = 1;
    if (isset($_POST['bentuk'])) {
      ?>
    <input type='checkbox' style='display:none;' name='bentuk' value="bentuk" checked='checked'>
    <?php
      } else {
      ?>
    <input type='checkbox' style='display:none;' name='bentuk' value="bentuk">
    <?php
      }
      if (isset($_POST['pilihan'])) {
      ?>
    <input type='checkbox' style='display:none;' name='pilihan' value="Perbarang" checked='checked'>
    <?php
      } else {
      ?>
    <input type='checkbox' style='display:none;' name='pilihan' value="Perbarang">
    <?php
      }
      ?>

    <?php
    // $barangkosong = isset($_POST['barangkosong']) ? $_POST['barangkosong'] : '';
    ?>

    <input type="hidden" name="semuaperiode" value="<?= $semuaperiode ?>">
    <input type="hidden" name="tanggal1" value="<?= $tanggal1 ?>">
    <input type="hidden" name="tanggal2" value="<?= $tanggal2 ?>">
    <input type="hidden" name="tgl1" value="<?= $tanggal1 ?>">
    <input type="hidden" name="tgl2" value="<?= $tanggal2 ?>">
    <input type="hidden" name="noopname" value="<?= $noopname ?>">
    <input type="hidden" name="rekapitulasi" value="<?= $rekapitulasi ?>">
    <input type="hidden" name="barangkosong"
        value="<?= isset($_POST['barangkosong']) ? $_POST['barangkosong'] : '' ?>">
    <input type="hidden" name="semuabarang" value="<?= $semuabarang ?>">
    <input type="hidden" name="kdbarang" value="<?= $kdbarang ?>">
    <input type="hidden" name="nmbarang" value="<?= $nmbarang ?>">
    <button type='submit' class='btn btn-danger'>Export ke Excel</button>
    <button type='button' class='btn btn-danger' onClick="window.print()">Print</button>
</form>

@include('report.rstock_view')
