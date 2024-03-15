<?php
$session = session();
// var_dump($vdata);
// exit();
?>
<!-- Modal -->
<div class="modal-dialog" style="max-width: 80%;">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="staticBackdropLabel">
                {{ $vdata['title'] . ' ' . $user->username . ' - ' . $user->email }}
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
        <form action="{{ $action }}" method="post" class="formuser">
            @csrf
            @if ($user->id)
                @method('post')
            @endif
            <div class="modal-body">
                {{-- <div class="row g-3 mb-2">
                    <div class="col-md-4">
                        <label for="username" class="form-label mb-1">Nama User</label>
                        <input type="text" class="form-control" name="username" id="username"
                            value="{{ $user->username }}" readonly>
                    </div>
                    <div class="col-md-8">
                        <label for="email" class="form-label mb-1">Email</label>
                        <input type="text" class="form-control" name="email" id="email"
                            value="{{ $user->email }}" readonly>
                    </div>
                </div> --}}
                <button type="submit" id="btnsimpan" class="btn btn-primary btn-sm btnsimpan">Simpan</button>
                <button type="button" class="btn btn-secondary btn-sm mt-1 mb-1" data-dismiss="modal">Close</button>
                <input type="hidden" name="username" id="username" value="{{ $user->username }}">
                <table table id="tbl-akses-user1" class="table table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>No. Urut</th>
                            <th>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="cbmodulesemua"> Module
                                </div>
                            </th>
                            <th>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="cbpakaisemua"> Pakai
                                </div>
                            </th>
                            <th><input class="form-check-input" type="checkbox" id="cbtambahsemua"> Tambah</th>
                            <th><input class="form-check-input" type="checkbox" id="cbeditsemua"> Edit</th>
                            <th><input class="form-check-input" type="checkbox" id="cbhapussemua"> Hapus</th>
                            <th><input class="form-check-input" type="checkbox" id="cbprosessemua"> Proses</th>
                            <th><input class="form-check-input" type="checkbox" id="cbunprosessemua"> Unproses</th>
                            <th><input class="form-check-input" type="checkbox" id="cbcetaksemua"> Cetak</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // $username = $email;
                        $n = 1;
                        foreach ($tbmodule as $m) :
                          $pakai = 0;
                          $tambah = 0;
                          $edit = 0;
                          $hapus = 0;
                          $proses = 0;
                          $unproses = 0;
                          $cetak = 0;
                        ?>
                        <tr>
                            <th class="text-center" scope="row"><?= $n++ ?></th>
                            <!-- <td><input class="form-check-input" type="checkbox" name="nurut[]"></td> -->
                            <td align="center"><input type="hidden" name="nurut[]"
                                    value="<?= $m['nurut'] ?>"><?= $m['nurut'] ?></td>
                            <td><input type="hidden" name="cmodule[]" value="<?= $m['cmodule'] ?>"><?= $m['cmodule'] ?>
                                <input type="hidden" name="cmenu[]" value="<?= $m['cmenu'] ?>">
                                <input type="hidden" name="cmainmenu[]" value="<?= $m['cmainmenu'] ?>">
                                <input type="hidden" name="nlevel[]" value="<?= $m['nlevel'] ?>">
                                <input type="hidden" name="cparent[]" value="<?= $m['cparent'] ?>">
                            </td>
                            <?php
                            foreach ($userdtl as $row):
                                if ($row['username'] == $user->username and $row['cmodule'] == $m['cmodule']) {
                                    $pakai = $row['pakai'];
                                    $tambah = $row['tambah'];
                                    $edit = $row['edit'];
                                    $hapus = $row['hapus'];
                                    $proses = $row['proses'];
                                    $unproses = $row['unproses'];
                                    $cetak = $row['cetak'];
                                }
                            endforeach;
                            ?>
                            <td>
                                <input class="form-check-input cbpakai" type="checkbox" name="pakai[]"
                                    {{ $pakai == 1 ? 'checked' : '' }} value="{{ $m['cmodule'] }}">
                            </td>
                            <td><input class="form-check-input cbtambah" type="checkbox" name="tambah[]"
                                    <?= $tambah == 1 ? 'checked' : '' ?> value="{{ $m['cmodule'] }}"></td>
                            <td><input class="form-check-input cbedit" type="checkbox" name="edit[]"
                                    <?= $edit == 1 ? 'checked' : '' ?> value="{{ $m['cmodule'] }}"></td>
                            <td><input class="form-check-input cbhapus" type="checkbox" name="hapus[]"
                                    <?= $hapus == 1 ? 'checked' : '' ?> value="{{ $m['cmodule'] }}"></td>
                            <td><input class="form-check-input cbproses" type="checkbox" name="proses[]"
                                    <?= $proses == 1 ? 'checked' : '' ?> value="{{ $m['cmodule'] }}"></td>
                            <td><input class="form-check-input cbunproses" type="checkbox" name="unproses[]"
                                    <?= $unproses == 1 ? 'checked' : '' ?> value="{{ $m['cmodule'] }}"></td>
                            <td><input class="form-check-input cbcetak" type="checkbox" name="cetak[]"
                                    <?= $cetak == 1 ? 'checked' : '' ?> value="{{ $m['cmodule'] }}"></td>


                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="modal-footer">
                    <button type="submit" id="btnsimpan" class="btn btn-primary btn-sm btnsimpan">Simpan</button>
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    var myModal = document.getElementById('modaltambah')
    var myInput = document.getElementById('email')
    // myModal.addEventListener('shown.bs.modal', function() {
    //     myInput.focus()
    // })
    // $(document).on('shown.bs.modal', function(e) {
    //     $('input:visible:enabled:first', e.target).focus();
    // });
    // $(document).on('shown.bs.modal', function(e) {
    //     myInput.focus();
    // });

    $(myModal).on('shown.bs.modal', function() {
        $(this).find(myInput).focus();
    });

    $(document).ready(function() {
        $('.formuser').submit(function(e) {
            const form = $(this)
            e.preventDefault();
            $.ajax({
                url: form.attr('action'),
                data: form.serialize(),
                dataType: "json",
                type: "POST",
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
                        if (response.error.username) {
                            $('#username').addClass('is-invalid');
                            $('.errorusername').html(response.error.username);
                        } else {
                            $('.errorusername').fadeOut();
                            $('#username').removeClass('is-invalid');
                            $('#username').addClass('is-valid');
                        }
                        if (response.error.email) {
                            $('#email').addClass('is-invalid');
                            $('.erroremail').html(response.error.email);
                        } else {
                            $('.erroremail').fadeOut();
                            $('#email').removeClass('is-invalid');
                            $('#email').addClass('is-valid');
                        }
                    } else {
                        // $('#modaltambah').modal('hide');
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
                        $('#modalakses').modal('hide');
                        // .then(function() {
                        //     window.location.href = '/user';
                        // });
                        // window.location = '/user';
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
                            // console.log(i)
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

    $('#btntampilpass').click(function(e) {
        e.preventDefault();
        // membuat variabel berisi tipe input dari id='pass', id='pass' adalah form input password 
        var x = document.getElementById('password').type;
        //membuat if kondisi, jika tipe x adalah password maka jalankan perintah di bawahnya
        if (x == 'password') {
            //ubah form input password menjadi text
            document.getElementById('password').type = 'text';
            //ubah icon mata terbuka menjadi tertutup
            document.getElementById('btntampilpass').innerHTML = `<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-eye-slash-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M10.79 12.912l-1.614-1.615a3.5 3.5 0 0 1-4.474-4.474l-2.06-2.06C.938 6.278 0 8 0 8s3 5.5 8 5.5a7.029 7.029 0 0 0 2.79-.588zM5.21 3.088A7.028 7.028 0 0 1 8 2.5c5 0 8 5.5 8 5.5s-.939 1.721-2.641 3.238l-2.062-2.062a3.5 3.5 0 0 0-4.474-4.474L5.21 3.089z"/>
                                                        <path d="M5.525 7.646a2.5 2.5 0 0 0 2.829 2.829l-2.83-2.829zm4.95.708l-2.829-2.83a2.5 2.5 0 0 1 2.829 2.829z"/>
                                                        <path fill-rule="evenodd" d="M13.646 14.354l-12-12 .708-.708 12 12-.708.708z"/>
                                                        </svg>`;
        } else {
            //ubah form input password menjadi text
            document.getElementById('password').type = 'password';
            //ubah icon mata terbuka menjadi tertutup
            document.getElementById('btntampilpass').innerHTML = `<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-eye-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z"/>
                                                        <path fill-rule="evenodd" d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8zm8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z"/>
                                                        </svg>`;
        }
    })

    $('#btntampilconfpass').click(function(e) {
        e.preventDefault();
        // membuat variabel berisi tipe input dari id='pass', id='pass' adalah form input password 
        var x = document.getElementById('confirm_password').type;
        //membuat if kondisi, jika tipe x adalah confirm_password maka jalankan perintah di bawahnya
        if (x == 'password') {
            //ubah form input confirm_password menjadi text
            document.getElementById('confirm_password').type = 'text';
            //ubah icon mata terbuka menjadi tertutup
            document.getElementById('btntampilconfpass').innerHTML = `<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-eye-slash-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M10.79 12.912l-1.614-1.615a3.5 3.5 0 0 1-4.474-4.474l-2.06-2.06C.938 6.278 0 8 0 8s3 5.5 8 5.5a7.029 7.029 0 0 0 2.79-.588zM5.21 3.088A7.028 7.028 0 0 1 8 2.5c5 0 8 5.5 8 5.5s-.939 1.721-2.641 3.238l-2.062-2.062a3.5 3.5 0 0 0-4.474-4.474L5.21 3.089z"/>
                                                        <path d="M5.525 7.646a2.5 2.5 0 0 0 2.829 2.829l-2.83-2.829zm4.95.708l-2.829-2.83a2.5 2.5 0 0 1 2.829 2.829z"/>
                                                        <path fill-rule="evenodd" d="M13.646 14.354l-12-12 .708-.708 12 12-.708.708z"/>
                                                        </svg>`;
        } else {
            //ubah form input password menjadi text
            document.getElementById('confirm_password').type = 'password';
            //ubah icon mata terbuka menjadi tertutup
            document.getElementById('btntampilconfpass').innerHTML = `<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-eye-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z"/>
                                                        <path fill-rule="evenodd" d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8zm8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z"/>
                                                        </svg>`;
        }
    })

    $('#cbmodulesemua').click(function(e) {
        if ($(this).is(":checked")) {
            $('.cbpakai').prop('checked', this.value = 1)
            $('.cbtambah').prop('checked', this.value = 1)
            $('.cbedit').prop('checked', this.value = 1)
            $('.cbhapus').prop('checked', this.value = 1)
            $('.cbproses').prop('checked', this.value = 1)
            $('.cbunproses').prop('checked', this.value = 1)
            $('.cbcetak').prop('checked', this.value = 1)
        } else {
            $('.cbpakai').prop('checked', this.value = 0)
            $('.cbtambah').prop('checked', this.value = 0)
            $('.cbedit').prop('checked', this.value = 0)
            $('.cbhapus').prop('checked', this.value = 0)
            $('.cbproses').prop('checked', this.value = 0)
            $('.cbunproses').prop('checked', this.value = 0)
            $('.cbcetak').prop('checked', this.value = 0)
        }
    })
    $('#cbpakaisemua').click(function(e) {
        if ($(this).is(":checked")) {
            $('.cbpakai').prop('checked', this.value = 1)
        } else {
            $('.cbpakai').prop('checked', this.value = 0)
        }
    })
    $('#cbtambahsemua').click(function(e) {
        if ($(this).is(":checked")) {
            $('.cbtambah').prop('checked', this.value = 1)
        } else {
            $('.cbtambah').prop('checked', this.value = 0)
        }
    })
    $('#cbeditsemua').click(function(e) {
        if ($(this).is(":checked")) {
            $('.cbedit').prop('checked', this.value = 1)
        } else {
            $('.cbedit').prop('checked', this.value = 0)
        }
    })
    $('#cbhapussemua').click(function(e) {
        if ($(this).is(":checked")) {
            $('.cbhapus').prop('checked', this.value = 1)
        } else {
            $('.cbhapus').prop('checked', this.value = 0)
        }
    })
    $('#cbprosessemua').click(function(e) {
        if ($(this).is(":checked")) {
            $('.cbproses').prop('checked', this.value = 1)
        } else {
            $('.cbproses').prop('checked', this.value = 0)
        }
    })
    $('#cbunprosessemua').click(function(e) {
        if ($(this).is(":checked")) {
            $('.cbunproses').prop('checked', this.value = 1)
        } else {
            $('.cbunproses').prop('checked', this.value = 0)
            // $('.cbunproses').prop('checked', this.value = 1)
            // $('#cbpakaisemua').click(function(e) {
            //   if ($(this).is(":checked")) {
            //     $('.cbpakai').prop('checked', this.value = 1)
            //   } else {
            //     $('.cbpakai').prop('checked', this.value = 0)
            //   }
            // })
            // } else {
            //   $('.cbunproses').prop('checked', this.value = 0)
            //   $('#cbpakaisemua').click(function(e) {
            //     if ($(this).is(":checked")) {
            //       $('.cbpakai').prop('checked', this.value = 1)
            //     } else {
            //       $('.cbpakai').prop('checked', this.value = 0)
            //     }
            //   })
        }
    })
    $('#cbcetaksemua').click(function(e) {
        if ($(this).is(":checked")) {
            $('.cbcetak').prop('checked', this.value = 1)
        } else {
            $('.cbcetak').prop('checked', this.value = 0)
        }
    })
</script>
