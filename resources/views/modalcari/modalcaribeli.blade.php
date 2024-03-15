<?php
$session = session();
// var_dump($vdata);
?>
<!-- Modal -->
{{-- <div class="modal fade" id="modalcari" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true"> --}}
<div class="modal-dialog" style="max-width: 60%;">
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
                        <table id="tblcari" class="table table-bordered table-hover table-sm">
                            <thead>
                                <tr>
                                    <th width="5">No</th>
                                    <th width="70">No.Dokumen</th>
                                    <th width="60">Tanggal</th>
                                    <th width="70">Kode</th>
                                    <th width="250">Supplier</th>
                                    <th width="70">Total</th>
                                </tr>
                            </thead>
                            <tbody id="isi_data" class="isi_data">
                                <?php $no = 0; ?>
                                @foreach ($belih as $row)
                                    <?php $no++; ?>
                                    <tr id="tr" style="curpor: pointer; ">
                                        <td style="text-align:center;"><?= $no ?></td>
                                        <td id="td" data-bs-dismiss="modal" onclick="post_data_belih();">
                                            <?= $row->nobeli ?></td>
                                        <td id="td" data-bs-dismiss="modal" onclick="post_data_belih();">
                                            <?= $row->tglbeli ?></td>
                                        <td id="td" data-bs-dismiss="modal" onclick="post_data_belih();">
                                            <?= $row->kdsupplier ?></td>
                                        <td id="td" data-bs-dismiss="modal" onclick="post_data_belih();">
                                            <?= $row->nmsupplier ?></td>
                                        <td style="text-align:right;" id="td" data-bs-dismiss="modal"
                                            onclick="post_data_belih();">
                                            <?= $row->kurangbayar ?></td>
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
        $('#tblcari').DataTable({
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

    function post_data_belih() {
        var table = document.getElementById("tblcari");
        var tbody = table.getElementsByTagName("tbody")[0];
        tbody.onclick = function(e) {
            e = e || window.event;
            var data = [];
            var target = e.srcElement || e.target;
            while (target && target.nodeName !== "TR") {
                target = target.parentNode;
            }
            if (target) {
                var cells = target.getElementsByTagName("td");
                for (var i = 0; i < cells.length; i++) {
                    data.push('--separator--' + cells[i].innerHTML);
                    dt = data.toString();
                }
            }
            dt_split = dt.split(",--separator--");
            $('#nodokumen').val(((dt_split[1]).replace("--separator--", "")).trim());
            $('#tgldokumen').val(((dt_split[2]).replace("--separator--", "")).trim());
            $('#kdsupplier').val(((dt_split[3]).replace("--separator--", "")).trim());
            $('#nmsupplier').val(((dt_split[4]).replace("--separator--", "")).trim());
            $('#uang').val(((dt_split[5]).replace("--separator--", "")).trim());
            $('#modalcaribeli').modal('hide');
        };
    }
</script>
