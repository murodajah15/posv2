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
                        <table id="tblcarikurir" class="table table-bordered table-hover table-sm">
                            <thead>
                                <tr>
                                    <th width="5">No</th>
                                    <th width="60">Kode</th>
                                    <th width="200">Nama</th>
                                    <th width="450">Alamat</th>
                                </tr>
                            </thead>
                            {{-- <tbody id="isi_data" class="isi_data"> --}}
                            <tbody>
                                <?php $no = 0; ?>
                                @foreach ($tbkurir as $row)
                                    <?php $no++; ?>
                                    <tr id="tr" style="cursor: pointer; ">
                                        <td style="text-align:center;"><?= $no ?></td>
                                        <td id="td" data-bs-dismiss="modal" onclick="post_data_kurir();">
                                            <?= $row->kode ?></td>
                                        <td id="td" data-bs-dismiss="modal" onclick="post_data_kurir();">
                                            <?= $row->nama ?></td>
                                        <td id="td" data-bs-dismiss="modal" onclick="post_data_kurir();">
                                            <?= $row->alamat . ' ' . $row->kota . ' ' . $row->kodepos . ' ' . $row->telp ?>
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
        $('#tblcarikurir').DataTable({
            destroy: true,
            "aLengthMenu": [
                [5, 50, 100, -1],
                [5, 50, 100, "All"]
            ],
            "iDisplayLength": 5
        })
    });

    function post_data_kurir() {
        var table = document.getElementById("tblcarikurir");
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
            $('#kdkurir').val(((dt_split[1]).replace("--separator--", "")).trim());
            $('#nmkurir').val(((dt_split[2]).replace("--separator--", "")).trim());
            $('#modalcarikurir').modal('hide');
            // $('#modaltambahmaster').modal('show');
            $('#kdkurir').focus();
        };
    }
</script>
