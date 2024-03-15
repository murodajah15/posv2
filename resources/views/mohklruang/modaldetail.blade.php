<?php
$session = session();
// dd($vdata);
$tambah = $userdtl->tambah;
$edit = $userdtl->edit;
$hapus = $userdtl->hapus;
?>
<!-- Modal -->
<div class="modal-dialog" style="max-width: 70%;">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="staticBackdropLabel">
                {{ $vdata['title'] . ' : ' . $mohklruang->nomohon . ' - ' . $mohklruang->nmsupplier }}
                {{-- @if (isset($vdata))
                    {{ $vdata['title'] }}
                @endif --}}
            </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <?php $tgl = date('Y-m-d'); ?>
            <input type="hidden" class="form-control-sm" name="id" id="id" value="{{ $mohklruang->id }}">
            <input type="hidden" class="form-control-sm" name="username" id="username"
                value="{{ $session->get('username') }}">
            <input type="hidden" class="form-control-sm" name="nomohond" id="nomohond"
                value="{{ $mohklruang->nomohon }}">
            {{-- <div class='col-md-12'> --}}
            <div class="row mb-0">
                <div class="col-sm-6">
                    <div class="btn-group">
                        &nbsp;<span><button type="button" class="btn btn-primary btn-sm tomboltambahdetail"
                                <?= $tambah == 1 ? '' : 'disabled' ?>> <i class="fa fa-circle-plus"></i>
                                Tambah</button></span>
                    </div>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <button type="button" style="display:inline; float: right;"
                        class="btn btn-outline-info btn-sm mb-2 btnreload" onclick="reload_table_detail()"
                        type="button"><i class="fa fa-spinner"></i></button>
                </div><!-- /.col -->
            </div><!-- /.row -->
            {{-- </div> --}}
            <div class="card mt-2">
                <div class="card-body">
                    <div class='col-md-12'>
                        <table id='tbl-detail-mohklruang' style='font-size:13px;'
                            class='table table-striped table table-bordered' width='600px'>
                            <thead>
                                <tr>
                                    <th width='30'>No.</th>
                                    <th width='120'>Dokumen</th>
                                    <th width='80'>Jumlah</th>
                                    <th width='350'>Keterangan</th>
                                    <th width='80'>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                        <div class="row mt-2 mb-2">
                            <div class='col-md-12'>
                                <div id="total_detail"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="modal-footer">
                                @if (str_contains($vdata['title'], 'Detail'))
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                @else
                                    <button type="submit" id="btnsimpan"
                                        class="btn btn-primary btnsimpan">Simpan</button>
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    reload_table_detail();
    reload_total_detail();

    function reload_table_detail() {
        $(function() {
            var vnomohon = $("#nomohond").val();
            var table = $('#tbl-detail-mohklruang').DataTable({
                ajax: "{{ url('mohklruangdajax') }}?nomohon=" + vnomohon,
                type: "GET",
                destroy: true,
                processing: true,
                serverSide: true,
                paging: true,
                lengthChange: true,
                searching: true,
                ordering: true,
                columnDefs: [{
                        className: 'dt-body-center',
                        targets: [0, 4],
                    },
                    {
                        orderable: true,
                        className: 'dt-body-right',
                        targets: [2],
                    },
                ],
                order: [
                    [1, 'asc']
                ],
                info: true,
                autoWidth: true,
                responsive: true,
                aLengthMenu: [
                    [5, 50, 100, -1],
                    [5, 50, 100, "All"]
                ],
                autoWidth: false,
                iDisplayLength: 5,
                columns: [{
                        orderable: false,
                        "data": null,
                        "searchable": false,
                        "render": function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {
                        data: 'nodokumen',
                        name: 'nodokumen'
                    },
                    {
                        data: 'uang',
                        name: 'uang',
                        render: function(data, type, row, meta) {
                            return meta.settings.fnFormatNumber(row.uang);
                        }
                    },
                    {
                        data: 'keterangan',
                        name: 'keterangan'
                    },
                    {
                        data: null,
                        render: function(data, type, row, meta) {
                            return `<a href="#${row.id}"><button onclick="editdetail(${row.id})" class='btn btn-sm btn-warning' href='javascript:void(0)' <?= $edit == 1 ? '' : 'disabled' ?>><i class='fa fa-edit'></i></button></a>
                            <a href="#${row.id},${row.kode}"><button onclick="hapusdetail(${row.id})" class='btn btn-sm btn-danger' href='javascript:void(0)' <?= $hapus == 1 ? '' : 'disabled' ?>><i class='fa fa-trash'></i></button></a>`;
                        }
                    },
                ]
            });
        });
    }

    function reload_total_detail() {
        $(function() {
            var vnomohon = $("#nomohond").val();
            $.ajax({
                type: "get",
                data: {
                    nomohon: vnomohon,
                },
                // dataType: "json",
                // url: "sototaldetail",
                url: `{{ route('mohklruangtotaldetail') }}`,
                beforeSend: function(f) {
                    $('#total_detail').attr('disable', 'disabled')
                    $('#total_detail').html('<i class="fa fa-spin fa-spinner"></i>')
                    $('#total_detail').html('<center>Loading Data ...</center>');
                },
                success: function(response) {
                    // $('#total_detail').html(data);
                    $('#total_detail').removeAttr('disable')
                    $('#total_detail').html('<i class="fa fa-spinner">')
                    $('#total_detail').html(response.body);
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                }
            })
        })
    }

    $('.tomboltambahdetail').click(function(e) {
        e.preventDefault();
        var vnomohon = $("#nomohond").val();
        $.ajax({
            url: `{{ route('mohklruangd.create') }}`,
            dataType: "json",
            data: {
                'nomohon': vnomohon,
            },
            success: function(response) {
                $('#modaleditdetail').html(response.body)
                $('#modaleditdetail').modal('show');
            },
            error: function(xhr, ajaxOptions, thrownError) {
                if (xhr.status == '401' || xhr.status == '419') {
                    toastr.error('Login Expired, silahkan login ulang')
                    toastr.options = {
                        "closeButton": false,
                        "debug": false,
                        "newestOnTop": false,
                        "progressBar": true,
                        "positionClass": "toast-top-center",
                        "preventDuplicates": false,
                        "onclick": null,
                        "showDuration": "300",
                        "hideDuration": "1000",
                        "timeOut": "5000",
                        "extendedTimeOut": "1000",
                        "showEasing": "swing",
                        "hideEasing": "linear",
                        "showMethod": "fadeIn",
                        "hideMethod": "fadeOut"
                    }
                    window.location.href = "{{ route('actionlogout') }}";
                }
            }
        })
    })

    function hapusdetail(id) {
        swal({
                title: "Yakin akan hapus ?",
                text: "Once deleted, you will not be able to recover this data!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url: `{{ url('mohklruangd') }}/${id}`,
                        type: "POST",
                        data: {
                            id: id,
                            _method: "DELETE",
                            _token: '{{ csrf_token() }}',
                        },
                        dataType: "json",
                        success: function(response) {
                            if (response.sukses == false) {
                                // swal({
                                //     title: "Data gagal dihapus!",
                                //     text: "",
                                //     icon: "error"
                                // })
                                toastr.warning('Data gagal di hapus silahkan melanjutkan')
                            } else {
                                // swal({
                                //     title: "Data berhasil dihapus! ",
                                //     text: "",
                                //     icon: "success"
                                // })
                                reload_table_detail();
                                reload_total_detail();
                                reload_table();
                                toastr.info('Data berhasil dihapus, silahkan melanjutkan')
                                // .then(function() {
                                //     window.location.href = '/tbbarang';
                                // });
                            }
                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                            alert(xhr.status + "\n" + xhr.responseText + "\n" +
                                thrownError);
                        }

                    })
                }
            })
    }

    function editdetail(id) {
        $.ajax({
            type: "get",
            url: `{{ url('mohklruangd') }}/${id}/edit`,
            dataType: "json",
            data: {
                id: id,
                methode: "get",
            },
            success: function(response) {
                if (response.data) {
                    $('#modaleditdetail').html(response.body);
                    $('#modaleditdetail').modal('show');
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                if (xhr.status == '401' || xhr.status == '419') {
                    toastr.error('Login Expired, silahkan login ulang')
                    toastr.options = {
                        "closeButton": false,
                        "debug": false,
                        "newestOnTop": false,
                        "progressBar": true,
                        "positionClass": "toast-top-center",
                        "preventDuplicates": false,
                        "onclick": null,
                        "showDuration": "300",
                        "hideDuration": "1000",
                        "timeOut": "5000",
                        "extendedTimeOut": "1000",
                        "showEasing": "swing",
                        "hideEasing": "linear",
                        "showMethod": "fadeIn",
                        "hideMethod": "fadeOut"
                    }
                    window.location.href = "{{ route('actionlogout') }}";
                }
            }
        })
    }
</script>
