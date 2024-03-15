<?php
$session = session();
// dd($vdata);
// $tambahtbnegara = $tambahtbnegara->tambah;
// $tambahtbjnbrg = $tambahtbjnbrg->tambah;
// $tambahtbsatuan = $tambahtbsatuan->tambah;
// $tambahtbmove = $tambahtbmove->tambah;
// $tambahtbdisc = $tambahtbdisc->tambah;
$tambah = $userdtl->tambah;
$edit = $userdtl->edit;
$hapus = $userdtl->hapus;
?>
<!-- Modal -->
<div class="modal-dialog" style="max-width: 100%;">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="staticBackdropLabel">
                {{ $vdata['title'] . ' : ' . $po->nopo . ' - ' . $po->kdsupplier . ' - ' . $po->nmsupplier }}
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
            <input type="hidden" class="form-control-sm" name="id" id="id" value="{{ $po->id }}">
            <input type="hidden" class="form-control-sm" name="kdsupplier" id="kdsupplier"
                value="{{ $po->kdsupplier }}">
            <input type="hidden" class="form-control-sm" name="username" id="username"
                value="{{ $session->get('username') }}">
            <input type="hidden" class="form-control-sm" name="nopod" id="nopod" value="{{ $po->nopo }}">
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
                        <table id='tbl-detail-po' style='font-size:13px;'
                            class='table table-striped table table-bordered' width='600px'>
                            <thead>
                                <tr>
                                    <th width='30'>No.</th>
                                    <th width='120'>Kode Barang</th>
                                    <th width='300'>Nama Barang</th>
                                    <th width='50'>Satuan</th>
                                    <th>QTY</th>
                                    <th width='90'>Harga</th>
                                    <th width='40'>Disc</th>
                                    <th>Subtotal</th>
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
    var myModal = document.getElementById('modaldetail')
    var myInput = document.getElementById('kdbarang')
    $(myModal).on('shown.bs.modal', function() {
        $(this).find(myInput).focus();
    });

    reload_table_detail();
    reload_total_detail();

    function reload_total_detail() {
        $(function() {
            var vnopo = $("#nopod").val();
            $.ajax({
                type: "get",
                data: {
                    nopo: vnopo,
                },
                // dataType: "json",
                // url: "sototaldetail",
                url: `{{ route('pototaldetail') }}`,
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

    function reload_table_detail() {
        $(function() {
            var vnopo = $("#nopod").val();
            var table = $('#tbl-detail-po').DataTable({
                ajax: "{{ url('podajax') }}?nopo=" + vnopo,
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
                        targets: [0, 8],
                    },

                    {
                        orderable: true,
                        className: 'dt-body-right',
                        targets: [4, 5, 6, 7],
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
                        orderable: true,
                        // data: 'kode1',
                        // name: 'kode1'
                        data: 'kdbarang',
                        name: 'kdbarang'
                        // "render": function(data, type, row, meta) {
                        //     return meta.row + meta.settings._iDisplayStart + 1;
                        // }
                        // data: null,
                        // render: function(data, type, row, meta) {
                        //     return `<a href="#" onclick="detail(${row.id})">${row.kode}</a>`;
                        // }
                    },
                    {
                        data: 'nmbarang',
                        name: 'nmbarang'
                    },
                    {
                        data: 'kdsatuan',
                        name: 'kdsatuan'
                    },
                    {
                        data: 'qty',
                        render: function(data, type, row, meta) {
                            return meta.settings.fnFormatNumber(row.qty);
                        }
                    },
                    {
                        data: 'harga',
                        name: 'harga',
                        render: function(data, type, row, meta) {
                            return meta.settings.fnFormatNumber(row.harga);
                        }
                    },
                    {
                        data: 'discount',
                        name: 'discount'
                    },
                    {
                        data: 'subtotal',
                        render: function(data, type, row, meta) {
                            return meta.settings.fnFormatNumber(row.subtotal);
                        }
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

    $('.tomboltambahdetail').click(function(e) {
        e.preventDefault();
        var vnopo = $("#nopod").val();
        $.ajax({
            url: `{{ route('pod.create') }}`,
            dataType: "json",
            data: {
                'nopo': vnopo,
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

    $(document).ready(function() {
        // $('#multiprc').on('ifChanged', function(event) {
        $('#multiprc').on('click', function(event) {
            if (this.checked) // if changed state is "CHECKED"
            {
                document.getElementById('srctb').setAttribute("disabled", "disabled");
                document.getElementById('srcmp').removeAttribute('disabled');
            } else {
                document.getElementById('srcmp').setAttribute("disabled", "disabled");
                document.getElementById('srctb').removeAttribute('disabled');
            }
        })

        $('#kdbarang').on('blur', function(e) {
            var checkBox = document.getElementById("multiprc");
            if (checkBox.checked == true) {
                var multiprc = 1
                var $url = 'repltbmultiprc'
            } else {
                var multiprc = 0
                var $url = 'repltbbarang'
            }
            // console.log($url);
            let cari = $(this).val()
            let cari1 = $('#kdsupplier').val()
            $.ajax({
                url: $url,
                type: 'get',
                data: {
                    'kode_barang': cari,
                    'kode_supplier': cari1
                },
                success: function(response) {
                    let data_response = JSON.parse(response);
                    if (!data_response) {
                        $('#nmbarang').val('');
                        $('#kdsatuan').val('');
                        $('#harga').val('');
                        $('#qty').val('');
                        if (multiprc === 1) {
                            socaritbmultiprc();
                        } else {
                            socaritbbarang();
                        }
                        return;
                    }
                    $('#nmbarang').val(data_response['nmbarang']);
                    $('#kdsatuan').val(data_response['kdsatuan']);
                    $('#harga').val(data_response['harga_jual']);
                    hit_subtotal();
                },
                error: function() {
                    console.log('file not fount');
                }
            })
        })
        // }
        // console.log(cari);

        $('.formpod').submit(function(e) {
            const form = $(this)
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: form.attr('action'),
                data: form.serialize(),
                dataType: "json",
                method: "POST",
                beforeSend: function() {
                    $('.btnsimpan').attr('disable', 'disabled')
                    $('.btnsimpan').html('<i class="fa fa-spin fa-spinner"></i>')
                    form.find('.invalid-feedback').remove()
                    form.find('.is-invalid').removeClass('is-invalid')
                },
                complete: function() {
                    $('.btnsimpan').removeAttr('disable')
                    $('.btnsimpan').html('Simpan')
                },
                success: function(response) {
                    // console.log(response);
                    if (response.error) {
                        if (response.error.kode) {
                            $('#kode').addClass('is-invalid');
                            $('.errorKode').html(response.error.kode);
                        } else {
                            $('.errorKode').fadeOut();
                            $('#kode').removeClass('is-invalid');
                            $('#kode').addClass('is-valid');
                        }
                        if (response.error.nama) {
                            $('#nama').addClass('is-invalid');
                            $('.errorNama').html(response.error.nama);
                        } else {
                            $('.errorNama').fadeOut();
                            $('#nama').removeClass('is-invalid');
                            $('#nama').addClass('is-valid');
                        }
                    } else {
                        if (response.sukses == 'Data berhasil di tambah') {
                            reload_table_detail();
                            reload_total_detail();
                            toastr.info('Data berhasil di simpan, silahkan melanjutkan')
                            $('#kdbarang').value = ""
                            reload_table();
                        } else {
                            reload_table_detail();
                            reload_total_detail();
                            toastr.error(
                                'Data gagal di simpan, barang sudah pernah di input')
                        }
                    }
                },
                // error: function(xhr, ajaxOptions, thrownError) {
                //     alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                // }
                error: function(xhr, ajaxOptions, thrownError) {
                    // console.log(xhr)
                    const errors = xhr.responseJSON?.errors
                    // console.log(errors);
                    if (errors) {
                        let i = 0;
                        for ([key, message] of Object.entries(errors)) {
                            i++;
                            if (i == 1) {
                                form.find(`[name="${key}"]`).focus()
                            }
                            console.log(key, message);
                            form.find(`[name="${key}"]`)
                                .addClass('is-invalid')
                                .parent()
                                .append(`<div class="invalid-feedback">${message}</div>`)
                        }
                    }

                    // console.log(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                }

            });
            return false;
        })

    });

    function editdetail(id) {
        $.ajax({
            type: "get",
            url: `{{ url('pod') }}/${id}/edit`,
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
                        url: `{{ url('pod') }}/${id}`,
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
</script>
