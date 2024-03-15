<?php
$session = session();
// var_dump($vdata);
?>
<!-- Modal -->
{{-- <div class="modal fade" id="modalcari" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true"> --}}
<div class="modal-dialog" style="max-width: 40%;">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="staticBackdropLabel">
                {{ $vdata['title'] . ' No : ' . $jual->nojual }}
            </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form action="{{ $action }}" method="post" class="forminputkurir">
            @csrf
            @if ($jual->id)
                @method('get')
            @endif
            <input type="hidden" class="form-control-sm" name="id" id="id" value="{{ $jual->id }}">
            <input type="hidden" class="form-control-sm" name="username" id="username"
                value="{{ $session->get('username') }}">
            <div class="modal-body">
                <div class="row">
                    <div class='col-md-12'>
                        <table style=font-size:12px; class='table table-borderless table-sm table-hover'>
                            <tr>
                                <td>Kurir</td>
                                <td>
                                    <div class='input-group'> <input type='text' class='form-control form-control-sm'
                                            id='kdkurir' name='kdkurir' autocomplete='off'
                                            value="{{ $jual->kdkurir }}"
                                            {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}>
                                        {{-- {{ $jual->sudahbayar > 0 ? 'readonly' : '' }}> --}}
                                        <input type="text" class='form-control form-control-sm' style="width: 10em"
                                            id='nmkurir' name='nmkurir' value="{{ $jual->nmkurir }}" readonly>
                                        <span class='input-group-btn'>
                                            <button type='button' id='src'
                                                class='btn btn-primary btn-sm carikurir'
                                                onclick='carikurir()'>Cari</button>
                                            {{-- {{ $jual->sudahbayar > 0 ? 'disabled' : '' }}>Cari</button> --}}
                                        </span>

                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            {{-- @if ($jual->sudahbayar <= 0) --}}
            <div class="modal-footer">
                <button type="submit" id="btnsimpan" class="btn btn-primary btnsimpan">Simpan</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
            </div>
            {{-- @endif --}}
        </form>
    </div>
</div>
{{-- </div> --}}

<script>
    $(document).ready(function() {
        var myModal = document.getElementById('modalinputkurir')
        var myInput = document.getElementById('catatan')
        $(myModal).on('shown.bs.modal', function() {
            $(this).find(myInput).focus();
        });

        $('.forminputkurir').submit(function(e) {
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
                        $('#modaltambah').modal('hide');
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
                        // reload_table();
                        toastr.info('Data berhasil di simpan, silahkan melanjutkan')
                        // .then(function() {
                        //     window.location.href = '/so';
                        // });
                        // window.location = '/so';
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

    function carikurir() {
        $.ajax({
            method: "GET",
            url: "carikurir",
            dataType: "json",
            success: function(response) {
                $('#modalcarikurir').html(response.body)
                $("#modalcarikurir").modal('show');
            }
        })
    }
    $('#kdkurir').on('blur', function(e) {
        let cari = $(this).val();
        $.ajax({
            url: 'replkurir',
            type: 'get',
            data: {
                kode: cari
            },
            success: function(response) {
                let data_response = JSON.parse(response);
                // alert(data_response.kdkurir)
                if (data_response.kdkurir === "") {
                    $('#kdkurir').val('');
                    $('#nmkurir').val('');
                    // carikurir();
                    return;
                }
                $('#nmkurir').val(data_response['nmkurir']);
            },
            error: function() {
                console.log('file not fount');
            }
        })
        // console.log(cari);
    })
</script>
