<?php
$session = session();
// var_dump($vdata);
?>
<!-- Modal -->
<div class="modal-dialog" style="max-width: 60%;">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="staticBackdropLabel">
                {{ $vdata['title'] . ' Detail : ' . $kasir_keluar->nokwitansi }}
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
                @if ($kasir_keluard->id)
                    @method('get')
                @endif
                <?php $tgl = date('Y-m-d'); ?>
                <input type="hidden" class="form-control-sm" name="id" id="id"
                    value="{{ $kasir_keluard->id }}">
                {{-- <input type="hidden" class="form-control-sm" name="kdcustomer" id="kdcustomer"
                    value="{{ $kasir_keluar->kdcustomer }}"> --}}
                <input type="hidden" class="form-control-sm" name="username" id="username"
                    value="{{ $session->get('username') }}">
                <input type="hidden" class="form-control-sm" name="nokwitansi" id="nokwitansi"
                    value="{{ $kasir_keluar->nokwitansi }}">
                <input type="hidden" class="form-control-sm" name="nokwitansid" id="nokwitansid"
                    value="{{ $kasir_keluard->nokwitansi }}">
                <div class='col-md-12'>
                    <table style=font-size:12px; class='table table-striped table table-bordered' width='600px'>
                        <tr>
                            <td>Nomor Permohonan <input type="checkbox" class="permohonan" name="permohonan"
                                    id="permohonan"></td>
                            <td>
                                <div class='input-group'> <input type='text' class='form-control form-control-sm'
                                        style="text-transform:uppercase" id='nomohon' name='nomohon' size='50'
                                        autocomplete='off' value="{{ $kasir_keluard->nomohon }}" readonly>
                                    <span class='input-group-btn'>
                                        &nbsp<button type='button' id='btncarinomohon' class='btn btn-success btn-sm'
                                            onclick='carimohklruang()' disabled>...</button>
                                    </span>
                            </td>
                        </tr>
                        <tr>
                            <td>Nomor Dokumen</td>
                            <td>
                                <div class='input-group'> <input type='text' class='form-control form-control-sm'
                                        style="width: 3em;" id='nodokumen' name='nodokumen' autocomplete='off'
                                        value="{{ $kasir_keluard->nodokumen }}">
                                    <input type='text' class='form-control form-control-sm' style="width: 15em;"
                                        id='tgldokumen' name='tgldokumen' autocomplete='off'
                                        value="{{ $kasir_keluard->tgldokumen }}" readonly>
                                    <span class='input-group-btn'>
                                        &nbsp<button type='button' id='btncarinobeli' class='btn btn-success btn-sm'
                                            onclick='caribeli()'>...</button>
                                    </span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>Supplier</td>
                            <td>
                                <div class='input-group'> <input type='text' class='form-control form-control-sm'
                                        style="text-transform: uppercase; width: 3em;" id='kdsupplier' name='kdsupplier'
                                        autocomplete='off' value="{{ $kasir_keluard->kdsupplier }}" readonly>
                                    <input type='text' class='form-control form-control-sm' id='nmsupplier'
                                        name='nmsupplier' style="text-transform: uppercase; width: 20em;"
                                        value="{{ $kasir_keluard->nmsupplier }}" readonly>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>Bayar</td>
                            <td><input type="number" class='form-control form-control-sm' id='uang' name='uang'
                                    style='text-align:right;' value="{{ $kasir_keluard->uang }}" required></td>
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
    var myInput = document.getElementById('nodokumen')
    $(myModal).on('shown.bs.modal', function() {
        $(this).find(myInput).focus();
    });

    $('#permohonan').on('click', function(event) {
        if (this.checked) // if changed state is "CHECKED"
        {
            document.getElementById('btncarinomohon').disabled = false
            document.getElementById('nomohon').readOnly = false
            document.getElementById('btncarinobeli').disabled = true
            document.getElementById('nodokumen').readOnly = true
            document.getElementById('uang').readOnly = true
        } else {
            document.getElementById('btncarinomohon').disabled = true
            document.getElementById('nomohon').readOnly = true
            document.getElementById('btncarinobeli').disabled = false
            document.getElementById('nodokumen').readOnly = false
            document.getElementById('uang').readOnly = false
        }
    })
    $(document).ready(function() {
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

    // Nampilin list data pilihan ===================
    function carimohklruang() {
        $.ajax({
            method: "GET",
            url: "carimohklruang",
            dataType: "json",
            success: function(response) {
                $('#modalcarimohklruang').html(response.body)
                $("#modalcarimohklruang").modal('show');
            }
        })
    }
    $('#nomohon').on('blur', function(e) {
        // alert(1)
        let cari = $(this).val();
        $.ajax({
            url: 'replmohklruang',
            type: 'get',
            data: {
                kode: cari
            },
            success: function(response) {
                let data_response = JSON.parse(response);
                // alert(data_response.nmcustomer)
                if (data_response.kdjual === "") {
                    $('#nomohon').val('');
                    return;
                }
                // $('#kdcustomer').val(data_response['kdcustomer']);
                // $('#supplier').val(data_response['supplier']);
                $('#nomohon').val(data_response['nodokumen']);
            },
            error: function() {
                console.log('file not fount');
            }
        })
        // console.log(cari);
    })

    // Nampilin list data pilihan ===================
    function caribeli() {
        $.ajax({
            method: "GET",
            url: "caribeli",
            dataType: "json",
            success: function(response) {
                $('#modalcaribeli').html(response.body)
                $("#modalcaribeli").modal('show');
            }
        })
    }
    $('#nodokumen').on('blur', function(e) {
        // alert(1)
        let cari = $(this).val();
        $.ajax({
            url: 'replbeli',
            type: 'get',
            data: {
                kode: cari
            },
            success: function(response) {
                let data_response = JSON.parse(response);
                // alert(data_response.nmcustomer)
                if (data_response.kdjual === "") {
                    $('#nodokumen').val('');
                    $('#tgldokumen').val('');
                    $('#uang').val('');
                    $('#kdsupplier').val('');
                    $('#nmsupplier').val('');
                    // carijual();
                    return;
                }
                // $('#kdcustomer').val(data_response['kdcustomer']);
                // $('#supplier').val(data_response['supplier']);
                $('#nodokumen').val(data_response['nodokumen']);
                $('#tgldokumen').val(data_response['tgldokumen']);
                $('#kdsupplier').val(data_response['kdsupplier']);
                $('#nmsupplier').val(data_response['nmsupplier']);
                $('#uang').val(data_response['uang']);
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
