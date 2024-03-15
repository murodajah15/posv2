<?php
$session = session();
// var_dump($vdata);
?>
<!-- Modal -->
<div class="modal-dialog" style="max-width: 50%;">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="staticBackdropLabel">
                {{ $vdata['title'] }}
            </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form action="{{ $action }}" method="post" class="formtbjntrans">
            @csrf
            @if ($tbjntrans->id)
                @method('put')
            @endif
            <div class="modal-body">
                <input type="hidden" class="form-control-sm" name="id" id="id"
                    value="<?= $tbjntrans->id ?>">
                <input type="hidden" class="form-control-sm" name="username" id="username"
                    value="{{ $session->get('username') }}">
                <input type="hidden" class="form-control-sm" name="kodelama" id="kodelama"
                    value="<?= $tbjntrans->kode ?>">
                <div class="col-md-6 mb-2">
                    <label for="kode" class="form-label mb-1">Kode</label>
                    <input type="text" class="form-control" name="kode" id="kode"
                        value="<?= $tbjntrans->kode ?>" {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}
                        {{ str_contains($vdata['title'], 'Detail') ? '' : 'autofocus' }}>
                </div>
                <div class="col-md-12 mb-2">
                    <label for="nama" class="form-label mb-1">Nama</label>
                    <input type="text" class="form-control" name="nama" id="nama"
                        value="<?= $tbjntrans->nama ?>" {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}>
                </div>
                <div class="col-md-12 mb-2">
                    <label for="keterangan" class="form-label mb-1">keterangan</label>
                    <input type="text" class="form-control" name="keterangan" id="keterangan"
                        value="<?= $tbjntrans->keterangan ?>"
                        {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}>
                </div>
                <?php
                if (strpos($vdata['title'], 'Tambah') !== false) {
                } else {
                ?>
                <div class="col-md-12 mb-2">
                    <label for="nama" class="form-label mb-1">User</label>
                    <input type="text" class="form-control" name="user" id="user"
                        value="{{ $tbjntrans->user }}" readonly>
                </div> <?php } ?>
            </div>
            <div class="modal-footer">
                @if (str_contains($vdata['title'], 'Detail'))
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                @else
                    <button type="submit" id="btnsimpan" class="btn btn-primary btnsimpan">Simpan</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                @endif
            </div>
        </form>
    </div>
</div>


<script>
    var myModal = document.getElementById('modaltambah')
    var myInput = document.getElementById('kode')
    // myModal.addEventListener('shown.bs.modal', function() {
    //     myInput.focus()
    // })
    $(myModal).on('shown.bs.modal', function() {
        $(this).find(myInput).focus();
    });

    $(document).ready(function() {
        $('.formtbjntrans').submit(function(e) {
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
                        reload_table();
                        toastr.info('Data berhasil di simpan, silahkan melanjutkan')
                        // .then(function() {
                        //     window.location.href = '/tbjntrans';
                        // });
                        // window.location = '/tbjntrans';
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
</script>
