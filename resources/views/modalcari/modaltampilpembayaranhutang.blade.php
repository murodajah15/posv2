<?php
$session = session();
var_dump($vdata);
?>
<!-- Modal -->
{{-- <div class="modal fade" id="modalcari" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true"> --}}
<div class="modal-dialog" style="max-width: 40%;">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="staticBackdropLabel">
                {{ $vdata['title'] }}
            </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form method="post" class="formcari">
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12 margin-tb">
                        <table id="tbltunai" class="table table-bordered table-hover table-sm">
                            <thead>
                                <tr>
                                    <th width="5">No</th>
                                    <th width="80">No.Kwitansi</th>
                                    <th width="70">Tanggal</th>
                                    <th width="70">Cara Bayar</th>
                                    <th width="80">Bayar</th>
                                </tr>
                            </thead>
                            <tbody id="isi_data" class="isi_data">
                                <?php $no = 0; ?>
                                @foreach ($kasir_keluard as $row)
                                    <?php
                                    $no++;
                                    $uang = number_format($row->uang, 0, '.', ',');
                                    ?>
                                    <tr id="tr" style="curpor: pointer; ">
                                        <td style="text-align:center;"><?= $no ?></td>
                                        <td id="td" data-bs-dismiss="modal" onclick="post_data_kasir_tunai();">
                                            <?= $row->nokwitansi ?></td>
                                        <td id="td" data-bs-dismiss="modal" onclick="post_data_kasir_tunai();">
                                            <?= $row->tglkwitansi ?></td>
                                        <td id="td" data-bs-dismiss="modal" onclick="post_data_kasir_tunai();">
                                            <?= $row->carabayar ?></td>
                                        <td id="td" data-bs-dismiss="modal" style="text-align:right;"
                                            onclick="post_data_kasir_tunai();">
                                            <?= $uang ?></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </form>
    </div>
</div>
{{-- </div> --}}


<script>
    $(document).ready(function() {
        $('#tbltunai').DataTable({
            destroy: true,
            columnDefs: [{
                orderable: true,
                searchable: false,
                className: 'dt-body-center',
                targets: [0],
            }],
            order: [
                [1, 'desc']
            ],
            "aLengthMenu": [
                [5, 50, 100, -1],
                [5, 50, 100, "All"]
            ],
            "iDisplayLength": 5,
        })
    });
</script>
