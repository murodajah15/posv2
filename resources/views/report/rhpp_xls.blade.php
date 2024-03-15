<form method='get' action='rhpp_export'>
    @csrf
    <?php
    $no = 1;
    ?>
    <input type="hidden" name="bulan" value="<?= $bulan ?>">
    <input type="hidden" name="tahun" value="<?= $tahun ?>">
    <input type="hidden" name="semuabarang" value="<?= $semuabarang ?>">
    <input type="hidden" name="kdbarang" value="<?= $kdbarang ?>">
    <input type="hidden" name="nmbarang" value="<?= $nmbarang ?>">
    <button type='submit' class='btn btn-danger'>Export ke Excel</button>
    <button type='button' class='btn btn-danger' onClick="window.print()">Print</button>
</form>

@include('report.rhpp_view')
