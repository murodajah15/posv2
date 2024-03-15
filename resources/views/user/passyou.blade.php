@extends('/home/index')

@section('content')
    @include('home.akses');
    <?php
    $pakai = session('pakai');
    $tambah = session('tambah');
    $edit = session('edit');
    $hapus = session('hapus');
    $proses = session('proses');
    $unproses = session('unproses');
    $cetak = session('cetak');
    ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-0">
                    <div class="col-sm-6">
                        <div class="btn-group">
                            <h4 class="m-0 text-dark">{{ $title }}&nbsp;&nbsp;</h4>
                        </div>
                        @if (session('message'))
                            <div class="text-success">{{ session('message') }}</div>
                        @endif
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/home">Home</a></li>
                            <li class="breadcrumb-item active">{{ $title }}</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
                <form action="passyou/save" class="formpassyou">
                    @csrf
                    @if ($user->id)
                        @method('put')
                    @endif
                    <input type='hidden' name='id' id='id' value="{{ $user->id }}">
                    <div class="card mt-2">
                        <div class="col-md-12 mb-2">
                            <label class="form-check-label" for="username" class="form-label mb-1">Username</label>
                            <input type="text" class="form-control" name="username" id="username"
                                value="{{ $user->username }}" readonly>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label class="form-check-label" for="email" class="form-label mb-1">Email</label>
                            <input type="text" class="form-control" name="email" id="email"
                                value="{{ $user->email }}" readonly>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-check-label" for="password" class="form-label mb-1">Passwor Lama</label>
                            <div class="input-group">
                                <input type="password" class="form-control" name="password" id="password"
                                    autocomplete="password">
                                <button class="btn btn-outline-secondary btn-sm" type="button" id="btntampilpasslama"><i
                                        class="fa fa-eye"></i></button>
                                <div class="invalid-feedback errorConfpassword">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mb-2">
                            <div class="row g-3 mb-2">
                                <div class="col">
                                    <label for="passwordbaru" class="form-check-label mb-1">Password</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" name="passwordbaru" id="passwordbaru"
                                            autocomplete="new-password">
                                        <!-- <button class="btn btn-outline-secondary btn-sm" type="button" onlick="tampil_password()" id="tampil_password">Tampil</button> -->
                                        <button class="btn btn-outline-secondary btn-sm" type="button"
                                            id="btntampilpassbaru"><i class="fa fa-eye"></i></button>
                                        <div class="invalid-feedback errorPassword">
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <label for="confirm_password" class="form-check-label mb-1">Confirm Password</label>
                                    <div class="input-group">
                                        {{-- <input type="password" class="form-control" name="confpassword" id="confpassword"> --}}
                                        <input type="password" class="form-control" name="confirm_password"
                                            id="confirm_password">
                                        <button class="btn btn-outline-secondary btn-sm" type="button"
                                            id="btntampilconfpass"><i class="fa fa-eye"></i></button>
                                        <div class="invalid-feedback errorConfpassword">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" id="btnsimpan" class="btn btn-primary btnsimpan">Update</button>
                        </div>
                    </div>
                    <!-- /.content-header -->
                </form>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="assets/plugins/jquery/jquery.min.js"></script>

    {{-- {!! $dataTable->scripts() !!} --}}
    <script>
        $(document).ready(function() {
            $('.formpassyou').submit(function(e) {
                const form = $(this)
                e.preventDefault();
                $.ajax({
                    type: "POST",
                    url: form.attr('action'),
                    data: form.serialize(),
                    dataType: "json",
                    // method: "POST",
                    beforeSend: function() {
                        $('.btnsimpan').attr('disable', 'disabled')
                        $('.btnsimpan').html('<i class="fa fa-spin fa-spinner"></i>')
                        form.find('.invalid-feedback').remove()
                        form.find('.is-invalid').removeClass('is-invalid')
                    },
                    complete: function() {
                        $('.btnsimpan').removeAttr('disable')
                        $('.btnsimpan').html('Update')
                    },
                    success: function(response) {
                        console.log(response);
                        if (response.error) {
                            if (response.error.kode) {
                                $('#passwordlama').addClass('is-invalid');
                                $('.errorpasswordlama').html(response.error.kode);
                            } else {
                                $('.errorpassword').fadeOut();
                                $('#password').removeClass('is-invalid');
                                $('#password').addClass('is-valid');
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
                            // alert(response.sukses);
                            if (response.sukses == 'Data berhasil di update') {
                                // toastr.success(response.sukses + ' silahkan melanjutkan')
                                toastr.success('Silahkan Login Ulang',
                                    'Password berhasil di update !', {
                                        timeOut: 2000,
                                        preventDuplicates: true,
                                        positionClass: 'toast-top-center',
                                        // Redirect 
                                        onHidden: function() {
                                            window.location.href = '/';
                                        }
                                    });
                            } else {
                                toastr.error(response.sukses)
                            }
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
                            // reload_table();
                            // toastr.info('Data berhasil di simpan, silahkan melanjutkan')
                            // .then(function() {
                            //     window.location.href = '/tbbank';
                            // });
                            // window.location = '/tbbank';
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
@endsection

{{-- @stop --}}
