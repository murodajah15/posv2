<?php
$session = session();
// var_dump($vdata);
?>
{{-- @include('home.akses'); --}}
<?php
$pakai = session('pakai');
$tambah = session('tambah');
$edit = session('edit');
$hapus = session('hapus');
$proses = session('proses');
$unproses = session('unproses');
$cetak = session('cetak');
?>
<!-- Modal -->
<div class="modal-dialog" style="max-width: 80%;">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="staticBackdropLabel">
                {{ $vdata['title'] }} Customer : {{ $tbcustomer->kode }} - {{ $tbcustomer->nama }}
                {{-- @if (isset($vdata))
                    {{ $vdata['title'] }}
                @endif --}}
            </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        {{-- <form action="{{ $action }}" method="post" class="formmultiprc"> --}}
        <form method="post" class="forminputmultiprc">
            @csrf
            {{-- @if ($tbcustomer->id)
                @method('put')
            @endif --}}
            <div class="modal-body">
                <input type="hidden" class="form-control-sm" name="username" id="username"
                    value="{{ $session->get('username') }}">
                <input type="hidden" class="form-control-sm" name="kdcustomer" id="kdcustomer"
                    value="{{ $tbcustomer->kode }}">
                <input type="hidden" class="form-control-sm" name="nmcustomer" id="nmcustomer"
                    value="{{ $tbcustomer->nama }}">
                <div class="row">
                    <div class='col-md-12'>
                        <?php
						if ($pakai == 1) { ?>
                        <div class='col-md-12'>
                            <div class="row">
                                {{-- @if ($tambah == '1') --}}
                                <div class='col-sm-2'>
                                    <input button type='Button' class='btn btn-warning btn-sm mb-2'
                                        value='Salin dari Tabel Barang' onClick='salin_tbbarang()'
                                        {{ $tambah == 1 ? '' : 'disabled' }} />
                                </div>
                                {{-- @endif --}}
                                <div class='col-sm-9'>
                                    {{-- <table style=font-size:13px; class="table table-striped table table-bordered"> --}}
                                    <td>
                                        <div class='input-group'> <input type='text'
                                                class='form-control form-control-sm' id='kdcustomersalin'
                                                name='kdcustomersalin' style="width: 5em" autocomplete='off'>
                                            <input type='text' class='form-control form-control-sm'
                                                id='nmcustomersalin' name='nmcustomersalin' style="width: 25em"
                                                autocomplete='off' readonly>
                                            <span class='input-group-btn'>
                                                <button type='button' id='src' class='btn btn-primary btn-sm'
                                                    onclick='caritbcustomer()'>Cari</button>
                                            </span>
                                            &nbsp;<input button type='Button' class='btn btn-success btn-sm'
                                                value='Salin dari Customer' onClick='salin_tbbarang_customer()'
                                                {{ $tambah == 1 ? '' : 'disabled' }} />
                                        </div>
                                    </td>
                                    {{-- </table> --}}
                                </div>
                                <div class='col-sm-1'>
                                    <button style="position: absolute; right: 1rem;"
                                        class="btn btn-outline-info btn-sm mb-2 btnreload"
                                        onclick="reload_tbl_multiprcajax()" type="button"><i
                                            class="fa fa-spinner"></i></button>
                                </div>
                            </div>
                        </div>
                        <br>
                        <?php
						} else { ?>

                        <input button type='Button' class='btn btn-primary btn-sm' value='Salin dari Tabel Barang'
                            onClick='salin_tbbarang()' disabled />
                        <?php
						}
						?>
                        <div class='col-md-12'>
                            <table style=font-size:13px; class="table table-striped table table-bordered">
                                <tr>
                                    <th width='200'>Kode Barang
                                    </th>
                                    <th width='370'>Barang</th>
                                    <th width='120'>Harga</th>
                                    <th width='120'>Discount (%)</th>
                                    <th>Aksi</th>
                                </tr>
                                <td>
                                    <div class='input-group'> <input type="button" class='btn btn-secondary btn-sm'
                                            value="Clear" onclick="eraseText()">&nbsp<input type='text'
                                            class='form-control form-control-sm' id='kdbarang' name='kdbarang'
                                            autocomplete='off' style='text-transform:uppercase' required>
                                        <span class='input-group-btn'>
                                            <button type='button' id='src' class='btn btn-primary btn-sm'
                                                onclick='caritbbarang()'>Cari</button>
                                        </span>
                                </td>
                                </td>
                                <td><input type='text' class='form-control form-control-sm' id='nmbarang'
                                        name='nmbarang' readonly></td>
                                </td>
                                <td><input type="number" style="text-align:right" class='form-control form-control-sm'
                                        id='harga' name='harga'></td>
                                </td>
                                <td><input type="number" style="text-align:right" max='100'
                                        class='form-control form-control-sm' id='discount' name='discount'
                                        onkeyup="validAngka(this)">
                                </td>
                                <td align='center' width='50px'>
                                    <button type='submit' class='btn btn-primary btn-sm'
                                        {{ $tambah == 1 ? '' : 'disabled' }}>+</button>
                                    <?php
                                    // $lakses = $pakai;
                                    // if ($lakses == 1) {
                                    //     echo "<button type='submit' class='btn btn-primary btn-sm btnaddmultiprc'>+</button>";
                                    // } else {
                                    //     echo "<button type='submit' class='btn btn-primary btn-sm' disabled>+</button>";
                                    // }
                                    ?>
                            </table>
                        </div>
                        <div class="row">
                            <div class='col-md-12'>
                                <div id="tbl_multiprcajax"></div>
                            </div>
                        </div>
                        {{-- <div class='col-md-12'>
                            <!--<table style=font-size:13px; class="table table-striped table table-bordered">-->
                            <table id="tbl_multiprc" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th width='30'>No.</th>
                                        <th width='120'>Kode Barang</th>
                                        <th width='350'>Nama Barang</th>
                                        <th width='90'>harga</th>
                                        <th width='60'>Disc (%)</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 0; ?>
                                    @foreach ($tbmultiprc as $row)
                                        <tr>
                                            <td style="text-align:center;">{{ $no }}</td>
                                            <td>{{ $row->kdbarang }}</td>
                                            <td>{{ $row->nmbarang }}</td>
                                            <td style="text-align:right;">
                                                {{ number_format($row->harga, 0, ',', '.') }}</td>
                                            <td style="text-align:right;">{{ $row->discount }}</td>
                                            <td>
                                                <a class='btn btn-primary btn-sm'
                                                    href='?m=tbmultiprc&tipe=edit_detail&id=$k[id]'>Edit</a>
                                                <input button type='Button' class='btn btn-danger btn-sm'
                                                    value='Hapus' onClick='alert_hapus_detail($k[id])' />
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div> --}}

                        <div class='col-md-12'>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary btn-sm"
                                    data-dismiss="modal">Close</button>
                                {{-- <input button type='Button' class='btn btn-danger btn-sm' value='Close'
                                    onClick="window.location.href='?m=tbmultiprc'" /> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        reload_tbl_multiprcajax()

        // $("#tbl_multiprc").DataTable({
        //     aLengthMenu: [
        //         [5, 50, 100, -1],
        //         [5, 50, 100, "All"]
        //     ],
        //     autoWidth: false,
        //     iDisplayLength: 5,
        // })


        $('.forminputmultiprc').submit(function(e) {
            const form = $(this)
            e.preventDefault();
            $.ajax({
                type: "GET",
                // url: form.attr('action'),
                url: "multiprc_simpan",
                data: form.serialize(),
                dataType: "json",
                // method: "PUT",
                beforeSend: function() {
                    $('.btnsimpan').attr('disable', 'disabled')
                    $('.btnsimpan').html('<i class="fa fa-spin fa-spinner"></i>')
                    form.find('.invalid-feedback').remove()
                    form.find('.is-invalid').removeClass('is-invalid')
                },
                complete: function() {
                    $('.btnsimpan').removeAttr('disable')
                    $('.btnsimpan').html('+')
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
                        if (response.sukses === 'Data gagal di tambah') {
                            toastr.error(
                                'Data gagal di simpan (double), silahkan melanjutkan')
                        } else {
                            toastr.info('Data berhasil di simpan, silahkan melanjutkan')
                        }
                        reload_tbl_multiprcajax();
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

    function reload_tbl_multiprcajax() {
        $kdcustomer = document.getElementById('kdcustomer').value;
        // alert($kdcustomer);
        $.ajax({
            type: "get",
            data: {
                kdcustomer: $kdcustomer, //$("#kdcustomer").val()
            },
            // dataType: "json",
            url: "multiprc_table",
            beforeSend: function(f) {
                $('.btnreload').attr('disable', 'disabled')
                $('.btnreload').html('<i class="fa fa-spin fa-spinner"></i>')
                // alert('1');
                $('#tabel_bahan_bp').html('<center>Loading Table ...</center>');
            },
            success: function(data) {
                $('#tbl_multiprcajax').html(data);
                $('.btnreload').removeAttr('disable')
                $('.btnreload').html('<i class="fa fa-spinner">')
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        })
    }

    // Nampilin list data pilihan ===================
    function caritbbarang() {
        $.ajax({
            method: "GET",
            url: "caritbbarang",
            dataType: "json",
            success: function(response) {
                $('#modalcaritbbarang').html(response.body)
                $("#modalcaritbbarang").modal('show');
            }
        })
    }
    $('#kdbarang').on('blur', function(e) {
        let cari = $(this).val();
        $.ajax({
            url: 'repltbbarang',
            type: 'get',
            data: {
                kode_barang: cari,
            },
            success: function(response) {
                let data_response = JSON.parse(response);
                if (data_response.nmbarang === "") {
                    $('#kdbarang').val('');
                    $('#nmbarang').val('');
                    $('#harga').val('');
                    // caritbbarang();
                    return;
                }
                $('#nmbarang').val(data_response['nmbarang']);
                $('#harga').val(data_response['harga_jual']);
            },
            error: function() {
                console.log('file not fount');
            }
        })
        // console.log(cari);
    })

    // Nampilin list data pilihan ===================
    function caritbcustomer() {
        $.ajax({
            method: "GET",
            url: "caritbcustomer",
            dataType: "json",
            success: function(response) {
                $('#modalcaritbcustomer').html(response.body)
                $("#modalcaritbcustomer").modal('show');
            }
        })
    }
    $('#kdcustomersalin').on('blur', function(e) {
        let cari = $(this).val();
        $.ajax({
            url: 'repltbcustomer',
            type: 'get',
            data: {
                kode: cari
            },
            success: function(response) {
                let data_response = JSON.parse(response);
                // alert(data_response.kdcustomer)
                if (data_response.kdcustomer === "") {
                    $('#kdcustomersalin').val('');
                    $('#nmcustomersalin').val('');
                    // caritbcustomer();
                    return;
                }
                $('#nmcustomersalin').val(data_response['nmcustomer']);
            },
            error: function() {
                console.log('file not fount');
            }
        })
        // console.log(cari);
    })

    function salin_tbbarang() {
        $("#imgLoad").show("");
        $kdcustomer = document.getElementById('kdcustomer').value;
        $nmcustomer = document.getElementById('nmcustomer').value;
        swal({
                title: "Yakin akan salin dari tabel barang ?",
                text: "",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url: 'multiprc_salin_tbbarang',
                        type: "GET",
                        data: {
                            // _method: "DELETE",
                            kdcustomer: $kdcustomer,
                            nmcustomer: $nmcustomer,
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
                                toastr.danger('Data gagal di salin, silahkan melanjutkan')
                            } else {
                                // swal({
                                //     title: "Data berhasil dihapus! ",
                                //     text: "",
                                //     icon: "success"
                                // })
                                reload_tbl_multiprcajax()
                                toastr.info('Data berhasil di salin, silahkan melanjutkan')
                                // .then(function() {
                                //     window.location.href = '/tbmultiprc';
                                // });
                            }
                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                            alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                        }

                    })
                }
            });
        $("#imgLoad").hide();
    };

    function edit_detail($id) {
        $.ajax({
            type: "get",
            url: `{{ url('multiprc_edit') }}`,
            dataType: "json",
            method: "GET",
            data: {
                id: $id,
            },
            success: function(response) {
                if (response.data) {
                    $('#modaleditdetail').html(response.body);
                    $('#modaleditdetail').modal('show');
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        })
    };

    function hapus_detail(id) {
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
                        url: `{{ url('tbmultiprc') }}/${id}`,
                        type: "POST",
                        data: {
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
                                toastr.danger('Data gagal dihapus silahkan melanjutkan')
                            } else {
                                // swal({
                                //     title: "Data berhasil dihapus! ",
                                //     text: "",
                                //     icon: "success"
                                // })
                                reload_tbl_multiprcajax()
                                toastr.info('Data berhasil dihapus, silahkan melanjutkan')
                                // .then(function() {
                                //     window.location.href = '/tbgudang';
                                // });
                            }
                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                            alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                            // const errors = xhr.responseJSON?.errors
                            // console.log(errors);
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
            })
    }

    // function hapus_detail($id) {
    //     swal({
    //             title: "Yakin akan dihapus ?",
    //             text: "Once deleted, you will not be able to recover this data!",
    //             icon: "warning",
    //             buttons: true,
    //             dangerMode: true,
    //         })
    //         .then((willDelete) => {
    //             if (willDelete) {
    //                 $href = "tbmultiprc/proses_hapus_detail.php?id=";
    //                 window.location.href = $href + $id;
    //                 // swal("Poof! Your imaginary file has been deleted!", {
    //                 //   icon: "success",
    //                 // });
    //             } else {
    //                 //swal("Batal Hapus!");
    //             }
    //         });
    // };

    function salin_tbbarang_customer($id) {
        $("#imgLoad").show("");
        $kdcustomer = document.getElementById('kdcustomer').value;
        $nmcustomer = document.getElementById('nmcustomer').value;
        $kdcustomersalin = document.getElementById('kdcustomersalin').value;
        $nmcustomersalin = document.getElementById('nmcustomersalin').value;
        swal({
                title: "Yakin akan salin tabel barang dari customer " + $kdcustomer + " " + $kdcustomersalin +
                    " ?",
                text: "",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url: 'multiprc_salin_tbbarang_customer',
                        type: "GET",
                        data: {
                            // _method: "DELETE",
                            kdcustomer: $kdcustomer,
                            nmcustomer: $nmcustomer,
                            nmcustomersalin: $nmcustomersalin,
                            kdcustomersalin: $kdcustomersalin,
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
                                toastr.danger('Data gagal di salin, silahkan melanjutkan')
                            } else {
                                // swal({
                                //     title: "Data berhasil dihapus! ",
                                //     text: "",
                                //     icon: "success"
                                // })
                                reload_tbl_multiprcajax()
                                toastr.info('Data berhasil di salin, silahkan melanjutkan')
                                // .then(function() {
                                //     window.location.href = '/tbmultiprc';
                                // });
                            }
                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                            alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                        }

                    })
                }
            });
        $("#imgLoad").hide();
    };
</script>

<script type="text/javascript">
    function eraseText() {
        document.getElementById("kdbarang").value = "";
        document.getElementById("nmbarang").value = "";
        document.getElementById("harga").value = "";
        document.getElementById("discount").value = "";
    }
</script>
