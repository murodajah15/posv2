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
        <form action="{{ $action }}" class="formuser" enctype="multipart/form-data">
            @csrf
            @if ($user->id)
                @method('put')
            @endif
            <input type="hidden" class="form-control-sm" name="photolama" id="photolama" value="{{ $user->photo }}">
            <div class="modal-body">
                <input type="hidden" class="form-control-sm" name="usernamelama" id="usernamelama"
                    value="{{ $user->username }}">
                <input type="hidden" class="form-control-sm" name="emaillama" id="emaillama"
                    value="{{ $user->email }}">
                <div class="row g-3 mb-2">
                    <input type="hidden" class="form-control-sm" name="usernamelama" id="usernamelama"
                        value="{{ $user->username }}">
                    <div class="col-md-6 mb-0">
                        <label for="username" class="form-label mb-1">Nama User</label>
                        <input type="text" class="form-control" name="username" id="username"
                            value="{{ $user->username }}"
                            {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}>
                    </div>
                </div>
                <div class="row g-3 mb-0">
                    <div class="col-md-12 mb-2">
                        <label for="email" class="form-label mb-1">Email</label>
                        <input type="text" class="form-control" name="email" id="email"
                            value="{{ $user->email }}" {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}>
                    </div>
                </div>
                <div class="row g-3 mb-2">
                    <div class="col">
                        <label for="nama" class="form-label mb-1">Nama Lengkap</label>
                        <input type="text" class="form-control" name="nama_lengkap" id="nama_lengkap"
                            value="{{ $user->nama_lengkap }}"
                            {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}>
                    </div>
                    <div class="col">
                        <label for="nama" class="form-label mb-1">No. HP</label>
                        <input type="text" class="form-control" name="telp" id="telp"
                            value="{{ $user->telp }}"
                            {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}>
                    </div>
                </div>
                <div class="row g-3 mb-2">
                    @if (str_contains($vdata['title'], 'Edit'))
                        <div class="col-md-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="resetpassword" name="resetpassword">
                                <h5 style="color:red">Reset Password</h5>
                            </div>
                        </div>
                    @endif
                    <div class="col">
                        <label for="nama" class="form-label mb-1">Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" name="password" id="password"
                                autocomplete="new-password" placeholder="password"
                                {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}>
                            <!-- <button class="btn btn-outline-secondary btn-sm" type="button" onlick="tampil_password()" id="tampil_password">Tampil</button> -->
                            <button class="btn btn-outline-secondary btn-sm" type="button" id="btntampilpass"><i
                                    class="fa fa-eye"></i></button>
                            <div class="invalid-feedback errorPassword">
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <label for="nama" class="form-label mb-1">Confirm Password</label>
                        <div class="input-group">
                            {{-- <input type="password" class="form-control" name="confpassword" id="confpassword"> --}}
                            <input type="password" class="form-control" name="confirm_password"
                                id="confirm_password" {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}>
                            <button class="btn btn-outline-secondary btn-sm" type="button" id="btntampilconfpass"><i
                                    class="fa fa-eye"></i></button>
                            <div class="invalid-feedback errorConfpassword">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row g-3 mb-2">
                    <!-- <div class="col-md-6 mb-2"> -->
                    <div class="col">
                        <label for="nama" class="form-label mb-1">Level</label>
                        <select class="form-control" name="level" id="level"
                            {{ str_contains($vdata['title'], 'Detail') ? 'disabled' : '' }}>
                            <option value="">[Pilih Level]</option>
                            <?php
                            $arrlevel = ['ADMINISTRATOR', 'ADMIN', 'GUEST'];
                            $jml_kata = count($arrlevel);
                            for ($c = 0; $c < $jml_kata; $c += 1) {
                                if ($arrlevel[$c] == $user->level) {
                                    echo "<option value='$arrlevel[$c]' selected>$arrlevel[$c] </option>";
                                } else {
                                    echo "<option value='$arrlevel[$c]'> $arrlevel[$c] </option>";
                                }
                            }
                            echo '</select>';
                            ?>
                        </select>
                    </div>
                </div>
                <div class="row g-3 mb-2">
                    <div class="col">
                        <label for="nama" class="form-label mb-1">Photo</label>
                        {{-- <div class="col-md-12 mb-3">
                            <img src="/img/default.png" class="img-thumbnail img-preview" style="width:50%">
                        </div> --}}
                        <div class="col-sm-12">
                            <img src="{{ asset('storage/' . $user->photo) }}" class="img-thumbnail img-preview">
                        </div>
                        <div class="col-sm-12">
                            <div class="mb-3">
                                <input type="file" accept="image/png, image/jpeg" class="custom-file-input"
                                    id="photo" name="photo" value="{{ $user->photo }}"
                                    onchange="previewImg()" formenctype="multipart/form-data"
                                    {{ str_contains($vdata['title'], 'Detail') ? 'disabled' : '' }}>
                                <div class="invalid-feedback errorPhoto">
                                </div>
                                <label for="photo" class="custom-file-label">Pilih gambar..</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row g-3 mb-2">
                    <div class="col-md-12 mb-2">
                        <label for="aktif" class="form-label mb-1">Aktif</label><br>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="aktif" name="aktif"
                                {{ (str_contains($vdata['title'], 'Tambah') ? 'checked' : $user->aktif == 'Y') ? 'checked' : '' }}
                                {{ str_contains($vdata['title'], 'Detail') ? 'disabled' : '' }}>

                        </div>
                    </div>
                </div>
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

    var myInput = document.getElementById('username')
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
                type: "post",
                url: form.attr('action'),
                // data: form.serialize(),
                data: new FormData(this), //penggunaan FormData
                processData: false,
                contentType: false,
                dataType: "json",
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

    function previewImg() {
        const photo = document.querySelector('#photo');
        const photoLabel = document.querySelector('.custom-file-label');
        const imgPreview = document.querySelector('.img-preview');
        photoLabel.textContent = photo.files[0].name;
        const filePhoto = new FileReader();
        filePhoto.readAsDataURL(photo.files[0]);
        filePhoto.onload = function(e) {
            imgPreview.src = e.target.result;
        }
    }
</script>
