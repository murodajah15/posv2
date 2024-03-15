<?php
$session = session();
// var_dump($vdata);
$tambahtbnegara = $tambahtbnegara->tambah;
$tambahtbjnbrg = $tambahtbjnbrg->tambah;
$tambahtbsatuan = $tambahtbsatuan->tambah;
$tambahtbmove = $tambahtbmove->tambah;
$tambahtbdisc = $tambahtbdisc->tambah;
$kunci_harga_jual = 'N';
$pakai = 1;
$edit = 1;
$hapus = 1;
?>
<!-- Modal -->
<div class="modal-dialog" style="max-width: 100%;">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="staticBackdropLabel">
                {{ $vdata['title'] . ' : ' . $so->noso . ' - ' . $so->kdcustomer . ' - ' . $so->nmcustomer }}
                {{-- @if (isset($vdata))
                    {{ $vdata['title'] }}
                @endif --}}
            </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form action="{{ $action }}" method="post" class="formsod">
                @csrf
                @if ($so->id)
                    @method('get')
                @endif
                <?php $tgl = date('Y-m-d'); ?>
                <input type="hidden" class="form-control-sm" name="id" id="id" value="{{ $so->id }}">
                <input type="hidden" class="form-control-sm" name="kdcustomer" id="kdcustomer"
                    value="{{ $so->kdcustomer }}">
                <input type="hidden" class="form-control-sm" name="username" id="username"
                    value="{{ $session->get('username') }}">
                <input type="hidden" class="form-control-sm" name="nosod" id="nosod" value="{{ $so->noso }}">
                <div class='col-md-12'>
                    <table style=font-size:12px; class='table table-striped table table-bordered' width='600px'>
                        <tr>
                            <th>Kode Barang <input type="button" class='btn btn-success btn-sm' value="Clear"
                                    onclick="eraseText()"> <input type="checkbox" class="multiprc" name="multiprc"
                                    id="multiprc"> Multi Price</th>
                            <th>Barang</th>
                            <th>QTY</th>
                            <th>Harga</th>
                            <th>Disc (%)</th>
                            <th>Subtotal</th>
                            <th>Aksi</th>
                        </tr>
                        <td>
                            <div class='input-group'> <input type='text' class='form-control form-control-sm'
                                    style="text-transform: uppercase; width: 9em;" id='kdbarang' name='kdbarang'
                                    size='50' autocomplete='off'>
                                <span class='input-group-btn'>
                                    &nbsp<button type='button' id='srcmp' class='btn btn-primary btn-sm'
                                        onclick='caritbmultiprc()' disabled>MP</button>
                                </span>
                                <span class='input-group-btn'>
                                    &nbsp<button type='button' id='srctb' class='btn btn-success btn-sm'
                                        onclick='caritbbarang()'>TB</button>
                                </span>
                        </td>
                        <input type="hidden" id="kdsatuan" name="kdsatuan" value="">
                        </td>
                        <td><input type='text' class='form-control form-control-sm' style='width: 15em'
                                id='nmbarang' name='nmbarang' readonly></td>
                        </td>
                        <td><input type="text" class='form-control form-control-sm' value='1' id='qty'
                                name='qty' style='width: 6em' required onkeyup="validAngka(this)"
                                onblur="hit_subtotal()"></td>
                        <?php
									if ($kunci_harga_jual == 'Y') {
									?>
                        </td>
                        <td><input type="text" class='form-control form-control-sm' value='0' id='harga'
                                name='harga' style='width: 7em' readonly></td>
                        <?php
									} else {
									?>
                        </td>
                        <td><input type="text" class='form-control form-control-sm' value='0' id='harga'
                                name='harga' style='width: 7em' onkeyup="validAngka_no_titik(this)"
                                onblur="hit_subtotal()"></td>
                        <?php
									}
									?>
                        </td>
                        <td><input type="text" class='form-control form-control-sm' value='0' id='discount'
                                name='discount' style='width: 6em' onkeyup="validAngka(this)"
                                onblur="hit_subtotal()">
                        </td>
                        </td>
                        <td><input type="number" class='form-control form-control-sm' value='0' id='subtotal'
                                name='subtotal' style='width: 10em' readonly></td>
                        <td align='center' width='50px'>
                            <button type='submit' class='btn btn-primary btn-sm'>+</button>
                    </table>
            </form>
        </div>

        <div class='col-md-12'>
            <table id='tbl-detail-so' style='font-size:13px;' class='table table-striped table table-bordered'
                width='600px'>
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
                        <button type="submit" id="btnsimpan" class="btn btn-primary btnsimpan">Simpan</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    @endif
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
            var vnoso = $("#nosod").val();
            $.ajax({
                type: "get",
                data: {
                    noso: vnoso,
                },
                // dataType: "json",
                // url: "sototaldetail",
                url: `{{ route('sototaldetail') }}`,
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
            var vnoso = $("#nosod").val();
            var table = $('#tbl-detail-so').DataTable({
                ajax: "{{ url('sodajax') }}?noso=" + vnoso,
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
                    [1, 'desc']
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
                    // <a href="#${row.id}"><button onclick="detail(${row.id})" class='btn btn-sm btn-info' href='javascript:void(0)' <?= $pakai == 1 ? '' : 'disabled' ?>><i class='fa fa-eye'></i></button></a>

                    // {
                    //     data: 'subtotal',
                    //     render: function(data, type, row, meta) {
                    //         return meta.settings.fnFormatNumber(row.subtotal);
                    //     }
                    // },
                ]
            });
        });
    }

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
                var $url = 'sorepltbmultiprc'
            } else {
                var multiprc = 0
                var $url = 'sorepltbbarang'
            }
            // console.log($url);
            let cari = $(this).val()
            let cari1 = $('#kdcustomer').val()
            $.ajax({
                url: $url,
                type: 'get',
                data: {
                    'kode_barang': cari,
                    'kode_customer': cari1
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

        $('.formsod').submit(function(e) {
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
            url: `{{ url('sod') }}/${id}/edit`,
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
                        url: `{{ url('sod') }}/${id}`,
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

    $('.tambahtbjnbrg').click(function(e) {
        e.preventDefault();
        $.ajax({
            // url: "<?= url('so/tambahtbjnbrg') ?>",
            // url: "<?= url('tbjnbrg/modaltambah') ?>",
            url: `{{ route('tbjnbrg.create') }}`,
            dataType: "json",
            success: function(response) {
                $('#modaltambah').html(response.body)
                $('#modaltambah').modal('show');
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        })
    })

    var carikdjnbrg = $("#kdjnbrge").val();
    $.ajax({
        url: "<?= url('ambildatatbjnbrg') ?>",
        dataType: "json",
        data: {
            'kdjnbrg': carikdjnbrg
        },
        success: function(response) {
            if (response.data) {
                $('#kdjnbrg').html(response.data);
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
        }
    })

    // tampildatatbjnbrg();
    $('#kdjnbrg').focusin(function(e) {
        $.ajax({
            url: "<?= url('ambildatatbjnbrg') ?>",
            dataType: "json",
            success: function(response) {
                if (response.data) {
                    $('#kdjnbrg').html(response.data);
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        })
    });

    $('.tambahtbsatuan').click(function(e) {
        e.preventDefault();
        $.ajax({
            // url: "<?= url('tbsatuan/modaltambah') ?>",
            url: `{{ route('tbsatuan.create') }}`,
            dataType: "json",
            success: function(response) {
                $('#modaltambah').html(response.body)
                $('#modaltambah').modal('show');
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        })
    })

    var carikdsatuan = $("#kdsatuane").val();
    $.ajax({
        url: "<?= url('ambildatatbsatuan') ?>",
        dataType: "json",
        data: {
            'kdsatuan': carikdsatuan
        },
        success: function(response) {
            if (response.data) {
                $('#kdsatuan').html(response.data);
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
        }
    })

    // tampildatatbsatuan();
    $('#kdsatuan').focusin(function(e) {
        $.ajax({
            url: "<?= url('ambildatatbsatuan') ?>",
            dataType: "json",
            success: function(response) {
                if (response.data) {
                    $('#kdsatuan').html(response.data);
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        })
    });

    $('.tambahtbmove').click(function(e) {
        e.preventDefault();
        $.ajax({
            // url: "<?= url('tbmove.modaltambah') ?>",
            url: `{{ route('tbmove.create') }}`,
            dataType: "json",
            success: function(response) {
                $('#modaltambah').html(response.body)
                $('#modaltambah').modal('show');
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        })
    })

    var carikdmove = $("#kdmovee").val();
    // if (carikdmove != "") {
    $.ajax({
        url: "<?= url('ambildatatbmove') ?>",
        dataType: "json",
        data: {
            'kdmove': carikdmove
        },
        success: function(response) {
            if (response.data) {
                $('#kdmove').html(response.data);
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
        }
    })
    // }

    // tampildatatbmove();
    $('#kdmove').focusin(function(e) {
        $.ajax({
            url: "<?= url('ambildatatbmove') ?>",
            dataType: "json",
            success: function(response) {
                if (response.data) {
                    $('#kdmove').html(response.data);
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        })
    });

    $('.tambahtbdisc').click(function(e) {
        e.preventDefault();
        $.ajax({
            // url: "<?= url('tbdisc/modaltambah') ?>",
            url: `{{ route('tbdiscount.create') }}`,
            dataType: "json",
            success: function(response) {
                $('#modaltambah').html(response.body)
                $('#modaltambah').modal('show');
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        })
    })

    var carikddiscount = $("#kddiscounte").val();
    // if (carikddiscount != "") {
    $.ajax({
        url: "<?= url('ambildatatbdiscount') ?>",
        dataType: "json",
        data: {
            'kddiscount': carikddiscount
        },
        success: function(response) {
            if (response.data) {
                $('#kddiscount').html(response.data);
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
        }
    })
    // }

    $('.tambahtbnegara').click(function(e) {
        e.preventDefault();
        $.ajax({
            // url: "<?= url('tbnegara/modaltambah') ?>",
            url: `{{ route('tbnegara.create') }}`,
            dataType: "json",
            success: function(response) {
                $('#modaltambah').html(response.body)
                $('#modaltambah').modal('show');
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        })
    })

    $('#carinegara').click(function(e) {
        e.preventDefault();
        $.ajax({
            url: "<?= url('carinegara') ?>",
            dataType: "json",
            success: function(response) {
                $('#modalcari').html(response.body)
                $('#modalcari').modal('show');
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        })
    })
    $('#kdnegara').on('blur', function(e) {
        let cari = $(this).val()
        if (cari !== "") {
            $.ajax({
                url: "<?= url('replnegara') ?>",
                type: 'get',
                data: {
                    'kode': cari
                },
                success: function(data) {
                    let data_response = JSON.parse(data);
                    if (data_response['kdnegara'] == '') {
                        $('#kdnegara').val('');
                        $('#nmnegara').val('');
                        return;
                    } else {
                        $('#kdnegara').val(data_response['kdnegara']);
                        $('#nmnegara').val(data_response['nmnegara']);
                    }
                },
                error: function() {
                    $('#kdnegara').val('');
                    $('#nmnegara').val('');
                    return;
                    // console.log('file not fount');
                }
            })
            // console.log(cari);
        }
    })

    // Nampilin list data pilihan ===================
    function caritbbarang() {
        $.ajax({
            method: "GET",
            url: "<?= url('socaritbbarang') ?>",
            dataType: "json",
            success: function(response) {
                $('#modalcaritbbarang').html(response.body)
                $("#modalcaritbbarang").modal('show');
            }
        })
    }
    // Nampilin list data pilihan ===================
    function caritbmultiprc() {
        var cari = $('#kdcustomer').val()
        $.ajax({
            method: "GET",
            url: "<?= url('socaritbmultiprc') ?>",
            dataType: "json",
            data: {
                kode_customer: cari
            },
            success: function(response) {
                $('#modalcaritbmultiprc').html(response.body)
                $("#modalcaritbmultiprc").modal('show');
            }
        })
    }

    // Nampilin list data pilihan ===================
    function carisales() {
        $.ajax({
            method: "GET",
            url: "carisales",
            dataType: "json",
            success: function(response) {
                $('#modalcarisales').html(response.body)
                $("#modalcarisales").modal('show');
            }
        })
    }
    $('#kdsales').on('blur', function(e) {
        let cari = $(this).val();
        $.ajax({
            url: 'replsales',
            type: 'get',
            data: {
                kode: cari
            },
            success: function(response) {
                let data_response = JSON.parse(response);
                // alert(data_response.kdsales)
                if (data_response.kdsales === "") {
                    $('#kdsales').val('');
                    $('#nmsales').val('');
                    // carisales();
                    return;
                }
                $('#nmsales').val(data_response['nmsales']);
            },
            error: function() {
                console.log('file not fount');
            }
        })
        // console.log(cari);
    })

    function tgljttempo() {
        let tempo = document.getElementById('tempo').value
        let start = $("#tanggal").val();
        let result = new Date(start);
        let end = result.setDate(result.getDate() + parseFloat(tempo));
        let ed = new Date(end);
        let d = ed.getDate();
        let m = ed.getMonth() + 1;
        let y = ed.getFullYear();
        // console.log(y + '-' + (m <= 9 ? '0' + m : m) + '-' + (d <= 9 ? '0' + d : d));
        document.getElementById('tgljttempo').value = (y + '-' + (m <= 9 ? '0' + m : m) + '-' + (d <= 9 ? '0' + d : d));
        // select date(po_part.tanggal) as tanggal from po_part where date(po_part.tanggal) = '2023-07-05'
    }

    function hit_total() {
        let textnbiaya1 = document.getElementById("nbiaya1").val()
        let nbiaya1 = textnbiaya1.replace(/,/g, "");
        if (nbiaya1 === "") {
            nbiaya1 = 0;
        }
        let textnbiaya2 = document.getElementById("nbiaya2").val()
        let nbiaya2 = textnbiaya2.replace(/,/g, "");
        if (nbiaya2 === "") {
            nbiaya2 = 0;
        }
        // let textrp_ppn = document.getElementById("rp_ppn").val()
        // let rp_ppn = textrp_ppn.replace(/,/g, "");
        // if (rp_ppn === "") {
        //   rp_ppn = 0;
        // }
        let textppn = document.getElementById("ppn").val()
        let ppn = textppn.replace(/,/g, "");
        if (ppn === "") {
            ppn = 0;
        }
        let textmaterai = document.getElementById("materai").val()
        let materai = textmaterai.replace(/,/g, "");
        if (materai === "") {
            materai = 0;
        }
        let textsubtotal = document.getElementById("subtotalh").val()
        let subtotal = textsubtotal.replace(/,/g, "");
        let total_biaya = parseFloat(nbiaya1) + parseFloat(nbiaya2)
        document.getElementById("total_biaya").value = total_biaya.toLocaleString('en-US');
        let totalsmt = parseFloat(total_biaya) + parseFloat(subtotal)
        document.getElementById("totalsmt").value = totalsmt.toLocaleString('en-US');
        let rp_ppn = parseFloat(totalsmt) * (parseFloat(ppn) / 100);
        document.getElementById("rp_ppn").value = rp_ppn.toLocaleString('en-US');
        let total = parseFloat(totalsmt) + parseFloat(rp_ppn) + parseFloat(materai)
        document.getElementById("total").value = total.toLocaleString('en-US');
    }

    function eraseText() {
        document.getElementById("kdbarang").value = "";
        document.getElementById("nmbarang").value = "";
        document.getElementById("kdsatuan").value = "";
        document.getElementById("qty").value = "0";
        document.getElementById("harga").value = "0";
        document.getElementById("discount").value = "0";
        document.getElementById("subtotal").value = "0";
    }

    function hit_subtotal() {
        document.getElementById('qty').value == "" ? document.getElementById('qty').value = 0 : document.getElementById(
            'qty').value
        document.getElementById('harga').value == "" ? document.getElementById('harga').value = 0 : document
            .getElementById('harga').value
        document.getElementById('discount').value == "" ? document.getElementById('discount').value = 0 : document
            .getElementById('discount').value
        var lharga = (parseFloat(document.getElementById('qty').value) * parseFloat(document.getElementById('harga')
            .value));
        var ldisc = lharga - (lharga * (document.getElementById('discount').value)) / 100;
        var lsubtotal = ldisc;
        document.getElementById('subtotal').value = lsubtotal;
    }

    function hit_total() {
        document.getElementById('biaya_lain').value == "" ? document.getElementById('biaya_lain').value = 0 : document
            .getElementById('biaya_lain').value
        document.getElementById('materai').value == "" ? document.getElementById('materai').value = 0 : document
            .getElementById('materai').value
        document.getElementById('ppn').value == "" ? document.getElementById('ppn').value = 0 : document.getElementById(
            'ppn').value
        var lbiaya_lain = parseFloat(document.getElementById('biaya_lain').value);
        var ltotal_sementara = (parseFloat(document.getElementById('biaya_lain').value) + parseFloat(document
            .getElementById('subtotal').value));
        var lppn = ltotal_sementara * (parseFloat(document.getElementById('ppn').value) / 100);
        var lmaterai = parseFloat(document.getElementById('materai').value);
        var ltotal = ltotal_sementara + lmaterai + lppn;
        document.getElementById('total_sementara').value = ltotal_sementara;
        document.getElementById('total').value = ltotal;
    }
</script>
