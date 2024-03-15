<?php
$session = session();
// var_dump($vdata);
?>
<!-- Modal -->
<div class="modal-dialog" style="max-width: 60%;">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="staticBackdropLabel">
                {{ $vdata['title'] . ' Detail : ' . $mohklruang->nomohon }}
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
                @if ($mohklruangd->id)
                    @method('get')
                @endif
                <?php $tgl = date('Y-m-d'); ?>
                <input type="hidden" class="form-control-sm" name="id" id="id"
                    value="{{ $mohklruangd->id }}">
                {{-- <input type="hidden" class="form-control-sm" name="kdsupplier" id="kdsupplier"
                    value="{{ $mohklruang->kdsupplier }}"> --}}
                <input type="hidden" class="form-control-sm" name="tgldokumen" id="tgldokumen"
                    value="{{ $mohklruang->tglmohon }}">
                <input type="hidden" class="form-control-sm" name="username" id="username"
                    value="{{ $session->get('username') }}">
                <input type="hidden" class="form-control-sm" name="nomohon" id="nomohon"
                    value="{{ $mohklruang->nomohon }}">
                <input type="hidden" class="form-control-sm" name="nomohond" id="nomohond"
                    value="{{ $mohklruangd->nomohon }}">
                <div class='col-md-12'>
                    <table style=font-size:12px; class='table table-striped table table-bordered' width='600px'>
                        <tr>
                            <td>No. Dokumen</td>
                            <td><input type="text" class='form-control form-control-sm' id='nodokumen'
                                    name='nodokumen' required value="{{ $mohklruangd->nodokumen }}"></td>
                        </tr>
                        {{-- <tr>
                            <td>Tanggal Dokumen</td>
                            <td><input type="date" class='form-control form-control-sm' id='tgldokumen'
                                    name='tgldokumen' required onkeyup="validAngka(this)" onblur="hit_subtotal()"
                                    value="{{ $mohklruangd->tgldokumen }}"></td>
                        </tr> --}}
                        <tr>
                            <td>Supplier</td>
                            <td>
                                <div class='input-group'> <input type='text' class='form-control form-control-sm'
                                        style="width:4em" id='kdsupplier' name='kdsupplier' autocomplete='off'
                                        value="{{ $mohklruang->kdsupplier }}" readonly>
                                    <input type="text" class='form-control form-control-sm' style="width:20em"
                                        id='nmsupplier' name='nmsupplier' value="{{ $mohklruang->nmsupplier }}"
                                        readonly>
                            </td>
                        </tr>
                        <tr>
                            <td>Jumlah</td>
                            <td><input type="number" class='form-control form-control-sm' id='uang' name='uang'
                                    style='text-align:right;' required value="{{ $mohklruangd->uang }}"></td>
                        </tr>
                        <tr>
                            <td>Keterangan</td>
                            <td>
                                <textarea type='text' rows='2' class='form-control form-control-sm' id='kerangan' name='keterangan'
                                    autocomplete='off' {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}>{{ $mohklruangd->keterangan }}</textarea>
                            </td>
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

    function eraseText() {
        document.getElementById('bayar').value = 0
    }
</script>
