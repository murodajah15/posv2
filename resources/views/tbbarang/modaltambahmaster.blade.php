<?php
$session = session();
// var_dump($vdata);
$tambahtbnegara = $tambahtbnegara->tambah;
$tambahtbjnbrg = $tambahtbjnbrg->tambah;
$tambahtbsatuan = $tambahtbsatuan->tambah;
$tambahtbmove = $tambahtbmove->tambah;
$tambahtbdisc = $tambahtbdisc->tambah;
$tambahtbgudang = $tambahtbgudang->tambah;

?>
<!-- Modal -->
<div class="modal-dialog" style="max-width: 80%;">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="staticBackdropLabel">
                {{ $vdata['title'] }}
                {{-- @if (isset($vdata))
                    {{ $vdata['title'] }}
                @endif --}}
            </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <!-- Way 1: Display All Error Messages -->
        {{-- //submitnya gak pakai ajax --}}
        {{-- @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Whoops!</strong> There were some problems with your input.<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif --}}
        <form action="{{ $action }}" method="post" class="formtbbarang">
            @csrf
            @if ($tbbarang->id)
                @method('put')
            @endif
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <input type="hidden" class="form-control-sm" name="username" id="username"
                            value="{{ $session->get('username') }}">
                        <input type="hidden" class="form-control-sm" name="kodelama" id="kodelama"
                            value="{{ $tbbarang->kode }}">
                        <div class="col-md-12">
                            <label for="kode" class="label mb-1">Kode</label>
                            <input type="text" class="form-control form-control-sm mt-1" name="kode"
                                id="kode" value="{{ $tbbarang->kode }}" {{-- {{ str_contains($session->get('level'), 'ADMINISTRATOR') ? '' : 'readonly' }} --}}
                                {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}
                                {{ str_contains($vdata['title'], 'Detail') ? '' : 'autofocus' }}>
                        </div>
                        <div class="col-md-12">
                            <label for="nama" class="label mb-1">Nama</label>
                            <input type="text" class="form-control form-control-sm mb-2" name="nama"
                                id="nama" value="{{ $tbbarang->nama }}"
                                {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}>
                        </div>
                        <div class="col-md-12">
                            <div class="input-group mb-2">
                                <input type="text" name="kdnegara" id="kdnegara"
                                    class="form-control form-control-sm" placeholder="Buatan Negara"
                                    aria-label="Buatan Negara" value="{{ $tbbarang->kdnegara }}" style="width:3em;"
                                    {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}>
                                <input type="text" name="nmnegara" id="nmnegara"
                                    class="form-control form-control-sm" style="width:20em;"
                                    value="{{ isset($tbnegara->nama) ? $tbnegara->nama : '' }}" readonly>
                                <button class="btn btn-outline-secondary btn-sm" type="button" id="carinegara"
                                    {{ str_contains($vdata['title'], 'Detail') ? 'disabled' : '' }}><i
                                        class="fa fa-search"></i></button>
                                <button class="btn btn-outline-primary btn-sm tambahtbnegara" type="button"
                                    <?= $tambahtbnegara == 1 ? (str_contains($vdata['title'], 'Detail') ? 'disabled' : '') : 'disabled' ?>><i
                                        class="fa fa-plus"></i></button>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="input-group mb-2">
                                <input type="hidden" class="form-control form-control-sm" name="kdgudange"
                                    id="kdgudange" value="<?= $tbbarang['kdgudang'] ?>">
                                <select class="form-control form-control-sm" name='kdgudang' id="kdgudang"
                                    {{ str_contains($vdata['title'], 'Detail') ? 'disabled' : '' }}>
                                    <option value="">[Pilih Gudang]</option>
                                </select>
                                <button class="btn btn-outline-primary btn-sm tambahtbgudang" type="button"
                                    {{ $tambahtbgudang == 1 ? (str_contains($vdata['title'], 'Detail') ? 'disabled' : '') : 'disabled' }}><i
                                        class="fa fa-plus"></i></button>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label for="lokasi" class="label mb-1">Lokasi</label>
                            <input type="text" class="form-control form-control-sm" name="lokasi" id="lokasi"
                                value="{{ $tbbarang->lokasi }}"
                                {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}>
                        </div>
                        <div class="col-md-12">
                            <label for="merek" class="label mb-1">Merek</label>
                            <input type="text" class="form-control mb-2" name="merek" id="merek"
                                value="{{ $tbbarang->merek }}"
                                {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}>
                        </div>
                        <div class="col-md-12">
                            <div class="input-group mb-2">
                                <input type="hidden" class="form-control form-control-sm" name="kdjnbrge"
                                    id="kdjnbrge" value="<?= $tbbarang['kdjnbrg'] ?>">
                                <select class="form-control form-control-sm" name='kdjnbrg' id="kdjnbrg"
                                    {{ str_contains($vdata['title'], 'Detail') ? 'disabled' : '' }}>
                                    <option value="">[Pilih Jenis]</option>
                                </select>
                                <button class="btn btn-outline-primary btn-sm tambahtbjnbrg" type="button"
                                    {{ $tambahtbjnbrg == 1 ? (str_contains($vdata['title'], 'Detail') ? 'disabled' : '') : 'disabled' }}><i
                                        class="fa fa-plus"></i></button>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="input-group mb-2">
                                <input type="hidden" class="form-control form-control-sm" name="kdsatuane"
                                    id="kdsatuane" value="<?= $tbbarang['kdsatuan'] ?>">
                                <select class="form-control form-control-sm" name='kdsatuan' id="kdsatuan"
                                    {{ str_contains($vdata['title'], 'Detail') ? 'disabled' : '' }}>
                                    <option value="">[Pilih Satuan]</option>
                                </select>
                                <button class="btn btn-outline-primary btn-sm tambahtbsatuan" type="button"
                                    <?= $tambahtbsatuan == 1 ? (str_contains($vdata['title'], 'Detail') ? 'disabled' : '') : 'disabled' ?>><i
                                        class="fa fa-plus"></i></button>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="input-group mb-2">
                                <input type="hidden" class="form-control form-control-sm" name="kdmovee"
                                    id="kdmovee" value="<?= $tbbarang['kdmove'] ?>">
                                <select class="form-control form-control-sm" name='kdmove' id="kdmove"
                                    {{ str_contains($vdata['title'], 'Detail') ? 'disabled' : '' }}>
                                    @if (str_contains($vdata['title'], 'Tambah'))
                                        <option value="">[Pilih Moving]</option>
                                    @else
                                    @endif
                                </select>
                                <button class="btn btn-outline-primary btn-sm tambahtbmove" type="button"
                                    <?= $tambahtbmove == 1 ? (str_contains($vdata['title'], 'Detail') ? 'disabled' : '') : 'disabled' ?>><i
                                        class="fa fa-plus"></i></button>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="input-group mb-2">
                                <input type="hidden" class="form-control form-control-sm" name="kddiscounte"
                                    id="kddiscounte" value="<?= $tbbarang['kddiscount'] ?>">
                                <select class="form-control form-control-sm" name='kddiscount' id="kddiscount"
                                    {{ str_contains($vdata['title'], 'Detail') ? 'disabled' : '' }}>
                                    <option value="">[Pilih Kode Discount]</option>
                                </select>
                                <button class="btn btn-outline-primary btn-sm tambahtbdisc" type="button"
                                    <?= $tambahtbdisc == 1 ? (str_contains($vdata['title'], 'Detail') ? 'disabled' : '') : 'disabled' ?>><i
                                        class="fa fa-plus"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="col-md-12">
                            <label for="harga_jual" class="label mb-1">Harga Jual</label>
                            <input type="number" class="form-control form-control-sm" name="harga_jual"
                                id="harga_jual" style="text-align:right" step="any"
                                value="{{ $tbbarang->harga_jual }}"
                                {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}>
                        </div>
                        <div class="col-md-12">
                            <label for="harga_beli" class="label mb-1">Harga Beli</label>
                            <input type="number" class="form-control form-control-sm" name="harga_beli"
                                id="harga_beli" style="text-align:right" step="any"
                                value="{{ $tbbarang->harga_beli }}"
                                {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}>
                        </div>
                        <div class="col-md-12">
                            <label for="harga_beli_lama" class="label mb-1">Harga Beli Lama</label>
                            <input type="number" class="form-control form-control-sm" name="harga_beli_lama"
                                id="harga_beli_lama" style="text-align:right" step="any"
                                value="{{ $tbbarang->harga_beli_lama }}" readonly>
                        </div>
                        <div class="col-md-12">
                            <label for="hpp" class="label mb-1">HPP</label>
                            <input type="number" class="form-control form-control-sm" name="hpp" id="hpp"
                                style="text-align:right" step="any" value="{{ $tbbarang->hpp }}" readonly>
                        </div>
                        <div class="col-md-12">
                            <label for="hpp_lama" class="label mb-1">HPP Lama</label>
                            <input type="number" class="form-control form-control-sm" name="hpp_lama"
                                id="hpp_lama" style="text-align:right" step="any"
                                value="{{ $tbbarang->hpp_lama }}" readonly>
                        </div>
                        <div class="col-md-12">
                            <label for="stock" class="label mb-1">Stock</label>
                            <input type="number" class="form-control form-control-sm" name="stock" id="stock"
                                style="text-align:right" step="any" value="{{ $tbbarang->stock }}" readonly>
                        </div>
                        <div class="col-md-12">
                            <label for="stock_min" class="label mb-1">Stock Min</label>
                            <input type="number" class="form-control form-control-sm" name="stock_min"
                                id="stock_min" style="text-align:right" step="any"
                                value="{{ $tbbarang->stock_min }}"
                                {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}>
                        </div>
                        <div class="col-md-12">
                            <label for="stock_mak" class="label mb-1">Stock Mak</label>
                            <input type="number" class="form-control form-control-sm" name="stock_mak"
                                id="stock_mak" style="text-align:right" step="any"
                                value="{{ $tbbarang->stock_mak }}"
                                {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}>
                        </div>
                        <div class="col-md-12">
                            <label for="nobatch" class="label mb-1">No. Batch</label>
                            <input type="text" class="form-control form-control-sm" name="nobatch" id="nobatch"
                                step="any" value="{{ $tbbarang->nobatch }}"
                                {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}>
                            <label for="tglexpired" class="label mb-1">Tanggal Expired</label>
                            <input type="date" class="form-control form-control-sm" name="tglexpired"
                                id="tglexpired" step="any" value="{{ $tbbarang->tglexpired }}"
                                {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}>
                            {{-- <label for="nama" class="label mb-1">Aktif</label><br>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="aktif" name="aktif" checked>
                        </div> --}}
                        </div>
                    </div>
                </div>
                {{-- <div class="row"> --}}
                <?php
                if (strpos($vdata['title'], 'Tambah') !== false) {
                } else {
                ?>
                <div class="col-md-12">
                    <label for="nama" class="label mb-1 mt-3">User</label>
                    <input type="text" class="form-control form-control-sm" name="user" id="user"
                        value="{{ $tbbarang->user }}" readonly>
                </div> <?php } ?>
                <div class="modal-footer">
                    @if (str_contains($vdata['title'], 'Detail'))
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    @else
                        <button type="submit" id="btnsimpan" class="btn btn-primary btnsimpan">Simpan</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    @endif
                </div>
            </div>
            {{-- </div> --}}
        </form>
    </div>
</div>


<script>
    var myModal = document.getElementById('modaltambahmaster')
    var myInput = document.getElementById('kode')
    // myModal.addEventListener('shown.bs.modal', function() {
    //     myInput.focus()
    // })
    $(myModal).on('shown.bs.modal', function() {
        $(this).find(myInput).focus();
    });

    $(document).ready(function() {
        $('.formtbbarang').submit(function(e) {
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
                        $('#modaltambahmaster').modal('hide');
                        // swal({
                        //     title: "Data berhasil disimpann",
                        //     text: "",
                        //     icon: "success",
                        //     buttons: true,
                        //     dangerMode: true,
                        // })
                        // swal({
                        //     title: response.sukses,
                        //     // title: 'Sukses',
                        //     text: "Silahkan dilanjutkan",
                        //     icon: "success",
                        // })
                        reload_table();
                        toastr.info('Data berhasil di simpan, silahkan melanjutkan')
                        // .then(function() {
                        //     window.location.href = '/tbbarang';
                        // });
                        // window.location = '/tbbarang';
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

    var carikdgudang = $("#kdgudange").val();
    $.ajax({
        url: "<?= url('ambildatatbgudang') ?>",
        dataType: "json",
        data: {
            'kdgudang': carikdgudang
        },
        success: function(response) {
            if (response.data) {
                $('#kdgudang').html(response.data);
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
        }
    })

    // tampildatatbgudang();
    $('#kdgudang').focusin(function(e) {
        $.ajax({
            url: "<?= url('ambildatatbgudang') ?>",
            dataType: "json",
            success: function(response) {
                if (response.data) {
                    $('#kdgudang').html(response.data);
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        })
    });

    $('.tambahtbgudang').click(function(e) {
        e.preventDefault();
        $.ajax({
            // url: "<?= url('tbbarang/tambahtbgudang') ?>",
            // url: "<?= url('tbgudang/modaltambah') ?>",
            url: `{{ route('tbgudang.create') }}`,
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

    $('.tambahtbjnbrg').click(function(e) {
        e.preventDefault();
        $.ajax({
            // url: "<?= url('tbbarang/tambahtbjnbrg') ?>",
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
</script>
