<?php
$session = session();
$kunci_harga_terima = $saplikasi->kunci_harga_terima;
// var_dump($vdata);
?>
<!-- Modal -->
<div class="modal-dialog" style="max-width: 95%;">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="staticBackdropLabel">
                {{ $vdata['title'] . ' Detail : ' . $keluar->nokeluar }}
                {{-- @if (isset($vdata))
                    {{ $vdata['title'] }}
                @endif --}}
            </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form action="{{ $action }}" method="post" class="formeditdetail">
                @csrf
                @if ($keluard->id)
                    @method('get')
                @endif
                <?php $tgl = date('Y-m-d'); ?>
                <input type="hidden" class="form-control-sm" name="tglkeluar" id="tglkeluar"
                    value="{{ $keluar->tglkeluar }}">
                <input type="hidden" class="form-control-sm" name="id" id="id" value="{{ $keluard->id }}">
                <input type="hidden" class="form-control-sm" name="kdsupplier" id="kdsupplier"
                    value="{{ $keluar->kdsupplier }}">
                <input type="hidden" class="form-control-sm" name="username" id="username"
                    value="{{ $session->get('username') }}">
                <input type="hidden" class="form-control-sm" name="nokeluar" id="nokeluar"
                    value="{{ $keluar->nokeluar }}">
                <input type="hidden" class="form-control-sm" name="nokeluard" id="nokeluard"
                    value="{{ $keluard->nokeluar }}">
                <div class='col-md-12'>
                    <table style=font-size:12px; class='table table-striped table table-bordered'>
                        <tr>
                            <th width="18%">Kode Barang <input type="button" class='btn btn-success btn-sm'
                                    value="Clear" onclick="eraseText()">
                            <th width="40%">Nama Barang</th>
                            <th>QTY</th>
                            <th>Harga Beli</th>
                            <th>Disc (%)</th>
                            <th>Subtotal</th>
                        </tr>
                        <td>
                            <div class='input-group'> <input type='text' class='form-control form-control-sm'
                                    style="text-transform: uppercase; width: 9em;" id='kdbarang' name='kdbarang'
                                    size='50' autocomplete='off' value="{{ $keluard->kdbarang }}">
                                <span class='input-group-btn'>
                                    &nbsp<button type='button' id='srctb' class='btn btn-success btn-sm'
                                        onclick='caritbbarang()'>TB</button>
                                </span>
                        </td>
                        <input type="hidden" id="kdsatuan" name="kdsatuan" value="{{ $keluard->kdsatuan }}">
                        </td>
                        <td><input type='text' class='form-control form-control-sm' id='nmbarang' name='nmbarang'
                                value="{{ $keluard->nmbarang }}" readonly></td>
                        </td>
                        <td><input type="text" class='form-control form-control-sm' id='qty' name='qty'
                                style='width: 6em;text-align:right;' required onkeyup="validAngka(this)"
                                onblur="hit_subtotal()" value="{{ $keluard->qty }}"></td>
                        <td><input type="text" class='form-control form-control-sm' id='harga_beli' name='harga'
                                style='width: 7em;text-align:right;' onkeyup="validAngka_no_titik(this)"
                                onblur="hit_subtotal()" value="{{ $keluard->harga }}"></td>
                        <td><input type="text" class='form-control form-control-sm' id='discount' name='discount'
                                style='width: 6em;text-align:right;' onkeyup="validAngka(this)" onblur="hit_subtotal()"
                                value="{{ $keluard->discount }}">
                        </td>
                        <td><input type="text" class='form-control form-control-sm' id='subtotal'
                                name='subtotal' style='width: 10em;text-align:right;'
                                value="{{ $keluard->subtotal }}" readonly>
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
        $('#qty').on('keyup', function(e) {
            hit_subtotal();
        })
        $('#harga_beli').on('keyup', function(e) {
            hit_subtotal();
        })
        $('#discount').on('keyup', function(e) {
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
            var $url = 'repltbbarang'
            let cari = $(this).val()
            $.ajax({
                url: $url,
                type: 'get',
                data: {
                    'kode_barang': cari,
                },
                success: function(response) {
                    let data_response = JSON.parse(response);
                    if (!data_response) {
                        $('#kdbarang').val('');
                        $('#nmbarang').val('');
                        $('#kdsatuan').val('');
                        $('#harga_beli').val('');
                        $('#qty').val('');
                        caritbbarang();
                        return;
                    }
                    $('#kdbarang').val(data_response['kdbarang']);
                    $('#nmbarang').val(data_response['nmbarang']);
                    $('#kdsatuan').val(data_response['kdsatuan']);
                    $('#harga_beli').val(data_response['harga_beli']);
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
                    if (response.sukses == 'Data berhasil di simpan') {
                        $("#modaleditdetail").modal('hide');
                        reload_table_detail();
                        reload_total_detail();
                        toastr.info('Data berhasil di simpan, silahkan melanjutkan')
                        reload_table();
                    } else {
                        reload_table_detail();
                        reload_total_detail();
                        toastr.error(
                            'Data gagal di simpan, barang sudah pernah di input / stock 0')
                    }
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
    function caripo() {
        $.ajax({
            method: "GET",
            url: "<?= url('caripo') ?>",
            dataType: "json",
            success: function(response) {
                $('#modalcaripo').html(response.body)
                $("#modalcaripo").modal('show');
            }
        })
    }
    // Nampilin list data pilihan ===================
    function prosessalinpo() {
        $nopo = $('#nopo').val()
        $nokeluar = $('#nokeluar').val()
        $tglkeluar = $('#tglkeluar').val()
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
                        url: `{{ url('prosessalinpo') }}`,
                        type: "GET",
                        data: {
                            nopo: $nopo,
                            nokeluar: $nokeluar,
                            tglkeluar: $tglkeluar,
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

    function hit_subtotal() {
        document.getElementById('qty').value == "" ? document.getElementById('qty').value = 0 : document.getElementById(
            'qty').value
        document.getElementById('harga_beli').value == "" ? document.getElementById('harga_beli').value = 0 : document
            .getElementById('harga_beli').value
        document.getElementById('discount').value == "" ? document.getElementById('discount').value = 0 : document
            .getElementById('discount').value
        var lharga = (parseFloat(document.getElementById('qty').value) * parseFloat(document.getElementById(
                'harga_beli')
            .value));
        var ldisc = lharga - (lharga * (document.getElementById('discount').value)) / 100;
        var lsubtotal = ldisc;
        document.getElementById('subtotal').value = lsubtotal;
    }
</script>
