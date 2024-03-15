<?php
$session = session();
// var_dump($vdata);
?>
<!-- Modal -->
<div class="modal-dialog" style="max-width: 60%;">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="staticBackdropLabel">
                {{ $vdata['title'] . ' Detail : ' . $kasir_tagihan->nokwitansi }}
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
                @if ($kasir_tagihand->id)
                    @method('get')
                @endif
                <?php $tgl = date('Y-m-d'); ?>
                <input type="hidden" class="form-control-sm" name="id" id="id"
                    value="{{ $kasir_tagihand->id }}">
                {{-- <input type="hidden" class="form-control-sm" name="kdcustomer" id="kdcustomer"
                    value="{{ $kasir_tagihan->kdcustomer }}"> --}}
                <input type="hidden" class="form-control-sm" name="username" id="username"
                    value="{{ $session->get('username') }}">
                <input type="hidden" class="form-control-sm" name="nokwitansi" id="nokwitansi"
                    value="{{ $kasir_tagihan->nokwitansi }}">
                <input type="hidden" class="form-control-sm" name="nokwitansid" id="nokwitansid"
                    value="{{ $kasir_tagihand->nokwitansi }}">
                <div class='col-md-12'>
                    <table style=font-size:12px; class='table table-striped table table-bordered' width='600px'>
                        {{-- <tr>
                                <th>Penjualan <input type="button" class='btn btn-success btn-sm' value="Clear"
                                        onclick="eraseText()">
                                <th widh="200">Customer</th>
                                <th>Piutang</th>
                                <th>Bayar</th>
                            </tr> --}}
                        {{-- <tr>
                            <td>
                                <div class='input-group'> <input type='text' class='form-control form-control-sm'
                                        style="text-transform: uppercase; width: 9em;" id='nojual' name='nojual'
                                        size='50' autocomplete='off' value="{{ $kasir_tagihand->nojual }}">
                                    <span class='input-group-btn'>
                                        &nbsp<button type='button' id='srctb' class='btn btn-success btn-sm'
                                            onclick='carijualpiutang()'>...</button>
                                    </span>
                            </td>
                        </tr>
                        <tr>
                            <td><input type='text' class='form-control form-control-sm' style='width: 20em'
                                    id='nmcustomer' name='nmcustomer' value="{{ $kasir_tagihand->nmcustomer }}"
                                    readonly>
                            </td>
                        </tr>
                        <tr>
                            <td><input type='text' class='form-control form-control-sm'
                                    style='width: 20em; text-align:right;' id='piutang' name='piutang'
                                    value="{{ $kasir_tagihand->piutang }}" readonly></td>
                            </td>
                        </tr>
                        <tr>
                            <td><input type="text" class='form-control form-control-sm' id='bayar' name='bayar'
                                    style='width: 6em; text-align:right;' required onkeyup="validAngka(this)"
                                    onblur="hit_subtotal()" value="{{ $kasir_tagihand->bayar }}"></td>
                        </tr> --}}
                        <tr>
                            <td>Nomor Penjualan</td>
                            <td>
                                <div class='input-group'> <input type='text' class='form-control form-control-sm'
                                        style="text-transform:uppercase" id='nojual' name='nojual' size='50'
                                        autocomplete='off' value="{{ $kasir_tagihand->nojual }}">
                                    <span class='input-group-btn'>
                                        &nbsp<button type='button' id='srctb' class='btn btn-success btn-sm'
                                            onclick='carijualpiutang()'>...</button>
                                    </span>
                            </td>
                        </tr>
                        <tr>
                            <td>Customer</td>
                            <td>
                                <div class='input-group'> <input type='text' class='form-control form-control-sm'
                                        style="text-transform: uppercase; width: 3em;" id='kdcustomer' name='kdcustomer'
                                        size='50' autocomplete='off' value="{{ $kasir_tagihand->kdcustomer }}"
                                        readonly>
                                    <input type='text' class='form-control form-control-sm' id='nmcustomer'
                                        name='nmcustomer' style="text-transform: uppercase; width: 20em;"
                                        value="{{ $kasir_tagihand->nmcustomer }}" readonly>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>Piutang</td>
                            <td><input type='text' class='form-control form-control-sm' style='text-align:right;'
                                    id='piutang' name='piutang' value="{{ $kasir_tagihand->piutang }}" readonly></td>
                            </td>
                        </tr>
                        <tr>
                            <td>Bayar</td>
                            <td><input type="text" class='form-control form-control-sm' id='bayar' name='bayar'
                                    style='text-align:right;' required onkeyup="validAngka(this)"
                                    onblur="hit_subtotal()" value="{{ $kasir_tagihand->bayar }}"></td>
                        </tr>
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
                            <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Batal</button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    var myModal = document.getElementById('modaleditdetail')
    var myInput = document.getElementById('nojual')
    $(myModal).on('shown.bs.modal', function() {
        $(this).find(myInput).focus();
    });

    // $(document).ready(function() {
    //     $('#nojual').on('blur', function(e) {
    //         var $url = 'repljualpiutang'
    //         let cari = $(this).val()
    //         $.ajax({
    //             url: $url,
    //             type: 'get',
    //             data: {
    //                 'kode': cari,
    //             },
    //             success: function(response) {
    //                 let data_response = JSON.parse(response);
    //                 if (!data_response) {
    //                     $('#nojual').val('');
    //                     $('#nmcustomer').val('');
    //                     $('#kurangbayar').val('');
    //                     return;
    //                 }
    //                 $('#nojual').val(data_response['nojual']);
    //                 $('#nmcustomer').val(data_response['nmcustomer']);
    //                 $('#piutang').val(data_response['piutang']);
    //             },
    //             error: function() {
    //                 console.log('file not fount');
    //             }
    //         })
    //     })
    //     // }
    //     // console.log(cari);
    // })

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
                    if (response.sukses == 'Data berhasil di tambah') {
                        $("#modaleditdetail").modal('hide');
                        reload_table_detail();
                        reload_total_detail();
                        toastr.info('Data berhasil di simpan, silahkan melanjutkan')
                        reload_table();
                    } else {
                        reload_table_detail();
                        reload_total_detail();
                        toastr.error(
                            'Data gagal di simpan, penjualan sudah pernah di input')
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
    function carijualpiutang() {
        $.ajax({
            method: "GET",
            url: "carijualpiutang",
            dataType: "json",
            success: function(response) {
                $('#modalcarijual').html(response.body)
                $("#modalcarijual").modal('show');
            }
        })
    }
    $('#nojual').on('blur', function(e) {
        // alert(1)
        let cari = $(this).val();
        $.ajax({
            url: 'repljualpiutang',
            type: 'get',
            data: {
                kode: cari
            },
            success: function(response) {
                let data_response = JSON.parse(response);
                // alert(data_response.nmcustomer)
                if (data_response.kdjual === "") {
                    $('#nojual').val('');
                    $('#tgljual').val('');
                    $('#piutang').val('');
                    $('#bayar').val('');
                    $('#uang').val('');
                    $('#nmcustomer').val('');
                    // carijual();
                    return;
                }
                // $('#kdcustomer').val(data_response['kdcustomer']);
                // $('#nmcustomer').val(data_response['nmcustomer']);
                $('#nojual').val(data_response['nojual']);
                $('#tgljual').val(data_response['tgljual']);
                $('#piutang').val(data_response['piutang']);
                $('#bayar').val(data_response['piutang']);
                $('#uang').val(data_response['piutang']);
            },
            error: function() {
                console.log('file not fount');
            }
        })
        // console.log(cari);
    })

    function eraseText() {
        document.getElementById('bayar').value = 0
    }
</script>
