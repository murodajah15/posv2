<?php
$session = session();
// var_dump($vdata);
$kunci_harga_jual = $saplikasi->kunci_harga_jual;
?>
<!-- Modal -->
<div class="modal-dialog" style="max-width: 95%;">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="staticBackdropLabel">
                {{ $vdata['title'] . ' Detail : ' . $jual->nojual }}
                {{-- @if (isset($vdata))
                    {{ $vdata['title'] }}
                @endif --}}
            </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class='col-md-3'>
                Salin dari Sales Order
                <div class='input-group'> <input type='text' class='form-control form-control-sm'
                        style="text-transform: uppercase; width: 9em;" id='noso' name='noso' size='50'
                        autocomplete='off' readonly>
                    <span class='input-group-btn'>
                        &nbsp<button type='button' id='btnso' class='btn btn-secondary btn-sm'
                            onclick='cariso()'>...</button>
                    </span>
                    <span class='input-group-btn'>
                        &nbsp<button type='button' id='btnso' class='btn btn-primary btn-sm'
                            onclick='prosessalinso()'>Proses Salin</button>
                    </span>
                </div>
            </div>
            <br>
            <form action="{{ $action }}" method="post" class="formeditdetail">
                @csrf
                @if ($juald->id)
                    @method('get')
                @endif
                <?php $tgl = date('Y-m-d'); ?>
                <input type="hidden" class="form-control-sm" name="tgljual" id="tgljual"
                    value="{{ $jual->tgljual }}">
                <input type="hidden" class="form-control-sm" name="id" id="id" value="{{ $juald->id }}">
                <input type="hidden" class="form-control-sm" name="kdcustomer" id="kdcustomer"
                    value="{{ $jual->kdcustomer }}">
                <input type="hidden" class="form-control-sm" name="username" id="username"
                    value="{{ $session->get('username') }}">
                <input type="hidden" class="form-control-sm" name="nojual" id="nojual" value="{{ $jual->nojual }}">
                <input type="hidden" class="form-control-sm" name="nojuald" id="nojuald"
                    value="{{ $juald->nojual }}">
                <div class='col-md-12'>
                    <table style=font-size:12px; class='table table-striped table table-bordered' width='600px'>
                        <tr>
                            <th width="22%">Kode Barang <input type="button" class='btn btn-success btn-sm'
                                    value="Clear" onclick="eraseText()">
                                <input type="checkbox" class="multiprc" name="multiprc" id="multiprc"
                                    {{ $juald->multiprc == 'Y' ? 'checked' : '' }}> Multi Price
                            </th>
                            <th width="40%">Barang</th>
                            <th>QTY</th>
                            <th>Harga</th>
                            <th>Disc (%)</th>
                            <th>Subtotal</th>
                        </tr>
                        <td>
                            <div class='input-group'> <input type='text' class='form-control form-control-sm'
                                    style="text-transform: uppercase; width: 7em;" id='kdbarang' name='kdbarang'
                                    size='50' autocomplete='off' value="{{ $juald->kdbarang }}">
                                <span class='input-group-btn'>
                                    &nbsp<button type='button' id='srcmp' class='btn btn-primary btn-sm'
                                        onclick='caritbmultiprc()' disabled>MP</button>
                                </span>
                                <span class='input-group-btn'>
                                    &nbsp<button type='button' id='srctb' class='btn btn-success btn-sm'
                                        onclick='caritbbarang()'>TB</button>
                                </span>
                        </td>
                        <input type="hidden" id="kdsatuan" name="kdsatuan" value="{{ $juald->kdsatuan }}">
                        </td>
                        <td><input type='text' class='form-control form-control-sm' id='nmbarang'
                                name='nmbarang' value="{{ $juald->nmbarang }}" readonly></td>
                        </td>
                        <td><input type="text" class='form-control form-control-sm' id='qty' name='qty'
                                style='width: 6em;text-align:right' required onkeyup="validAngka(this)"
                                onblur="hit_subtotal()" value="{{ $juald->qty }}"></td>
                        @if ($kunci_harga_jual == 'Y')
                            <td><input type="text" class='form-control form-control-sm' id='harga'
                                    name='harga' style='width: 7em;text-align:right' value="{{ $juald->harga }}"
                                    readonly></td>
                        @else
                            <td><input type="text" class='form-control form-control-sm' id='harga'
                                    name='harga' style='width: 7em;text-align:right'
                                    onkeyup="validAngka_no_titik(this)" onblur="hit_subtotal()"
                                    value="{{ $juald->harga }}"></td>
                        @endif
                        <td><input type="text" class='form-control form-control-sm' id='discount'
                                name='discount' style='width: 6em;text-align:right' onkeyup="validAngka(this)"
                                onblur="hit_subtotal()" value="{{ $juald->discount }}">
                        </td>
                        </td>
                        <td><input type="text" class='form-control form-control-sm' id='subtotal'
                                name='subtotal' style='width: 10em;text-align:right' value="{{ $juald->subtotal }}"
                                readonly>
                        </td>
                    </table>
            </form>
            <div class="row">
                <div class="col-md-12">
                    <div class="modal-footer">
                        @if (str_contains($vdata['title'], 'Detail'))
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        @else
                            <button type="submit" id="btnsimpan"
                                class="btn btn-primary btn-sm btnsimpan">Simpan</button>
                            <button type="button" class="btn btn-secondary btn-sm"
                                data-dismiss="modal">Batal</button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    var myModal = document.getElementById('modaleditdetail')
    var myInput = document.getElementById('kdbarang')
    $(myModal).on('shown.bs.modal', function() {
        $(this).find(myInput).focus();
    });

    $(document).ready(function() {
        if ($('#multiprc').is(':checked')) {
            document.getElementById('srctb').setAttribute("disabled", "disabled");
            document.getElementById('srcmp').removeAttribute('disabled');
        } else {
            document.getElementById('srcmp').setAttribute("disabled", "disabled");
            document.getElementById('srctb').removeAttribute('disabled');
        }

        $('#qty').on('keyup', function(e) {
            hit_subtotal();
        })
        $('#harga').on('keyup', function(e) {
            hit_subtotal();
        })
        $('#discount').on('keyup', function(e) {
            hit_subtotal();
        })
        $('#qty').on('focus', function(e) {
            hit_subtotal();
        })
        $('#discount').on('focus', function(e) {
            hit_subtotal();
        })
        $('#kdbarang').on('focus', function(e) {
            hit_subtotal();
        })
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
            let cari = $(this).val()
            let kdcustomer = $('#kdcustomer').val()
            $.ajax({
                url: $url,
                type: 'get',
                data: {
                    'kode_barang': cari,
                    'kode_customer': kdcustomer,
                },
                success: function(response) {
                    let data_response = JSON.parse(response);
                    if (!data_response) {
                        $('#kdbarang').val('');
                        $('#nmbarang').val('');
                        $('#kdsatuan').val('');
                        $('#harga').val('');
                        $('#qty').val('');
                        if (multiprc === 1) {
                            caritbmultiprc();
                        } else {
                            caritbbarang();
                        }
                        return;
                    }
                    $('#kdbarang').val(data_response['kdbarang']);
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
    })

    $('.formeditdetail').submit(function(e) {
        const form = $(this)
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: form.attr('action'),
            data: form.serialize(),
            dataType: "json",
            method: "GET",
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
                    $("#modaleditdetail").modal('hide');
                    reload_table_detail();
                    reload_total_detail();
                    if ((response.sukses).includes('berhasil di tambah') || ((response.sukses)
                            .includes('berhasil di simpan')) || ((response.sukses)
                            .includes('berhasil di update'))) {
                        toastr.info(response.sukses)
                    } else {
                        toastr.error(response.sukses)
                    }
                    reload_table();
                    // if (response.sukses == 'Data berhasil di tambah') {
                    //     $("#modaleditdetail").modal('hide');
                    //     reload_table_detail();
                    //     reload_total_detail();
                    //     toastr.info('Data berhasil di simpan, silahkan melanjutkan')
                    //     reload_table();
                    // } else {
                    //     reload_table_detail();
                    //     reload_total_detail();
                    //     toastr.error(
                    //         'Data gagal di simpan, stock kurang atau barang sudah pernah di input'
                    //     )
                    // }
                }
            },
            // error: function(xhr, ajaxOptions, thrownError) {
            //     alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            // }
            error: function(xhr, ajaxOptions, thrownError) {
                // console.log(xhr)
                const errors = xhr.responsejson?.errors
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
    // Nampilin list data pilihan ===================
    function cariso() {
        $.ajax({
            method: "GET",
            url: "<?= url('cariso') ?>",
            dataType: "json",
            success: function(response) {
                $('#modalcariso').html(response.body)
                $("#modalcariso").modal('show');
            }
        })
    }
    // Nampilin list data pilihan ===================
    function prosessalinso() {
        $noso = $('#noso').val()
        $nojual = $('#nojual').val()
        $tgljual = $('#tgljual').val()
        swal({
                title: "Yakin akan proses salin ?",
                text: "",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url: `{{ url('prosessalinso') }}`,
                        type: "GET",
                        data: {
                            noso: $noso,
                            nojual: $nojual,
                            tgljual: $tgljual,
                            _token: '{{ csrf_token() }}',
                        },
                        dataType: "json",
                        success: function(response) {
                            if (response.sukses == false) {
                                toastr.warning('Data gagal di proses, silahkan melanjutkan')
                            } else {
                                reload_table_detail()
                                reload_total_detail();
                                reload_table()
                                $("#modaleditdetail").modal('hide');
                                $("#modaldetail").modal('show');
                                toastr.info('Data berhasil di proses silahkan melanjutkan')
                            }
                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                            alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                        }

                    })
                }
            })
    }
</script>
