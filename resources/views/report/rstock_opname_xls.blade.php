<form method='get' action='rstock_opname_export'>
    @csrf
    <?php
    $no = 1;
    ?>
    <input type="hidden" name="noopname" value="<?= $noopname ?>">
    <button type='submit' class='btn btn-danger'>Export ke Excel</button>
    <button type='button' class='btn btn-danger' onClick="window.print()">Print</button>
</form>

@include('report.rstock_opname_view')
