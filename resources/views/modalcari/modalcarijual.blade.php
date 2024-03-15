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
                                    <th width="70">No.Penjualan</th>
                                    <th width="70">Tanggal</th>
                                    <th width="70">Kode</th>
                                    <th width="350">Customer</th>
                                    <th width="70">Total</th>
                                    <th width="70">Piutang</th>
                                </tr>
                            </thead>
                            <tbody id="isi_data" class="isi_data">
                                <?php $no = 0; ?>
                                @foreach ($jualh as $row)
                                    <?php $no++; ?>
                                    <tr id="tr" style="curpor: pointer; ">
                                        <td style="text-align:center;"><?= $no ?></td>
                                        <td id="td" data-bs-dismiss="modal" onclick="post_data_jual();">
                                            <?= $row->nojual ?></td>
                                        <td id="td" data-bs-dismiss="modal" onclick="post_data_jual();">
                                            <?= $row->tgljual ?></td>
                                        <td id="td" data-bs-dismiss="modal" onclick="post_data_jual();">
                                            <?= $row->kdcustomer ?></td>
                                        <td id="td" data-bs-dismiss="modal" onclick="post_data_jual();">
                                            <?= $row->nmcustomer ?></td>
                                        <td id="td" style="text-align:right;" data-bs-dismiss="modal"
                                            onclick="post_data_jual();">
                                            <?= $row->total ?></td>
                                        <td id="td" style="text-align:right;" data-bs-dismiss="modal"
                                            onclick="post_data_jual();">
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

    function post_data_jual() {
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
            $('#nojual').val(((dt_split[1]).replace("--separator--", "")).trim());
            $('#tgljual').val(((dt_split[2]).replace("--separator--", "")).trim());
            $('#kdcustomer').val(((dt_split[3]).replace("--separator--", "")).trim());
            $('#nmcustomer').val(((dt_split[4]).replace("--separator--", "")).trim());
            $('#total').val(((dt_split[5]).replace("--separator--", "")).trim());
            $('#piutang').val(((dt_split[6]).replace("--separator--", "")).trim());
            $('#bayar').val(((dt_split[6]).replace("--separator--", "")).trim());
            $('#uang').val(((dt_split[6]).replace("--separator--", "")).trim());
            $('#modalcarijual').modal('hide');
        };
    }
</script>
