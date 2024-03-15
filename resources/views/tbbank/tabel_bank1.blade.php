<?php
$cmenu = $submenu;
$session = session();
$username = $session->get('email');
?>
<div class="container mt-1">
    <table id="tbl-bank-data" class="table table-striped tbl-bank-data" style="width:100%">
        <thead>
            <tr>
                <th width="30">No.</th>
                <th>Kode</th>
                <th>Nama</th>
                <th>Aktif</th>
                <th>User</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $n = 1;
            foreach ($tbbank as $k) :
            ?>
            <tr>
                <td class="text-center" scope="row"><?= $n++ ?></td>
                <td><a href="#" onClick="detail({{ $k['id'] }})">{{ $k['kode'] }}</a></td>
                <td>{{ $k['nama'] }}</td>
                <td>
                    <div class="form-check form-switch"><input class="form-check-input" type="checkbox" id="aktif"
                            name="aktif" {{ $k['aktif'] == 'Y' ? 'checked' : '' }} disabled></div>
                </td>
                <td>{{ $k['user'] }}</td>
                <td width="100">
                    <button type='Button' class='btn btn-warning btn-sm' onClick="edit(<?= $k['id'] ?>)"
                        {{ $session->get('edit') != 1 ? 'disabled' : '' }}><i class="fa fa-edit"></i></button>
                    <button type='Button' class='btn btn-danger btn-sm' onClick="hapus(`<?= $k['id'] ?>`)"
                        {{ $session->get('hapus') != 1 ? 'disabled' : '' }}><i class="fa fa-trash"></i></button>
                </td>
            </tr>
            <?php
            endforeach;
            ?>
        </tbody>
    </table>
</div>

<script src="assets/plugins/jquery/jquery.min.js"></script>
<script>
    $(function() {
        $(document).ready(function() {
            $('#tbl-bank-data').DataTable({
                destroy: true,
                "aLengthMenu": [
                    [5, 50, 100, -1],
                    [5, 50, 100, "All"]
                ],
                "iDisplayLength": 5
            })
        })
    })
</script>
