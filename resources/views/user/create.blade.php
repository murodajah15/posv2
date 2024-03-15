@extends('/home/index')

@section('content')
    {{-- @include('home.akses'); --}}
    <?php
    $cmenu = $submenu;
    $session = session();
    $username = $session->get('email');
    $level = $session->get('level');
    $pakai = 0;
    $tambah = 0;
    $edit = 0;
    $hapus = 0;
    $proses = 0;
    $unproses = 0;
    $cetak = 0;
    if ($level == 'ADMINISTRATOR') {
        $pakai = 1;
        $tambah = 1;
        $edit = 1;
        $hapus = 1;
        $proses = 1;
        $unproses = 1;
        $cetak = 1;
    } else {
        if (isset($userdtl->pakai)) {
            $pakai = $userdtl->pakai;
            $tambah = $userdtl->tambah;
            $edit = $userdtl->edit;
            $hapus = $userdtl->hapus;
            $proses = $userdtl->proses;
            $unproses = $userdtl->unproses;
            $cetak = $userdtl->cetak;
        }
    }
    $ses_data = [
        'pakai' => $pakai,
        'tambah' => $tambah,
        'edit' => $edit,
        'hapus' => $hapus,
        'proses' => $proses,
        'unproses' => $unproses,
        'cetak' => $cetak,
    ];
    Session::put($ses_data);
    ?>
    <?php
    $session = session();
    ?>
    <!-- Way 1: Display All Error Messages -->
    {{-- {{ 'aaa' . $errors }} --}}
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
                            <li class="breadcrumb-item"><a href="{{ url('/user') }}">Tabel User</a></li>
                            <li class="breadcrumb-item active">{{ $title }}</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
                <div class="card mt-2">
                    {{-- <div class="card-header">
                        <h3 class="card-title">DataTable with default features</h3>
                    </div> --}}
                    <!-- /.card-header -->
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <strong>Whoops!</strong> There were some problems with your input.<br><br>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form action="{{ url('user') }}" method="post" class="formuser">
                            {{-- <form methode="post" class="formuser"> --}}
                            @csrf
                            @if ($user->id)
                                @method('put')
                            @endif
                            <input type="hidden" class="form-control" name="username_input" id="username_input"
                                value="{{ $session->get('username') }}">
                            <input type="hidden" class="form-control" name="kodelama" id="kodelama"
                                value="{{ $user->kode }}">
                            <div class="col-md-6 mb-2">
                                <label for="kode" class="form-label mb-1">Nama User</label>
                                <input type="text" class="form-control" value="{{ $user->username }}" name="username"
                                    id="username" autofocus>
                                @error('username')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror

                            </div>
                            <div class="col-md-12 mb-2">
                                <label for="nama" class="form-label mb-1">Email</label>
                                <input type="text" class="form-control" name="email" value="{{ $user->email }}"
                                    id="email">
                                @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <div class="invalid-feedback errorEmail">
                                </div>
                            </div>
                            <div class="col-md-12 mb-2">
                                <label for="nama" class="form-label mb-1">Aktif</label><br>
                                <div class="form-check form-switch">
                                    <input class="form-check-input mb-2" type="checkbox" id="aktif" name="aktif"
                                        {{ ($title == 'Tambah Data Tabel Bank' ? 'checked' : $user->aktif == 'Y') ? 'checked' : '' }}>
                                </div>
                            </div>
                            <br>
                            <div class="col-md-12 mb-2 mt-2">
                                <button type="submit" id="btnsimpan" class="btn btn-primary btnsimpan">Simpan</button>
                                <button type="button" class="btn btn-secondary" onclick="history.back()">Batal</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
            {{-- </div> --}}
        </div><!-- /.container-fluid -->

        <script src="{{ asset('/') }}assets/plugins/jquery/jquery.min.js"></script>
        <script>
            $(document).ready(function() {
                $('.formuser').on('submit', function(e) {
                    const form = $(this)
                    e.preventDefault();
                    $.ajax({
                        type: "post",
                        url: form.attr('action'),
                        data: form.serialize(),
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
                            toastr.info('Data berhasil di simpan, silahkan melanjutkan')
                            // $('#modaltambah').modal('hide');
                            swal({
                                title: "Data berhasil disimpann",
                                text: "",
                                icon: "success",
                                buttons: true,
                                dangerMode: true,
                            })
                            swal({
                                    title: response.sukses,
                                    // title: 'Sukses',
                                    text: "Silahkan dilanjutkan",
                                    icon: "success",
                                })
                                // reload_table();
                                .then(function() {
                                    window.location.href = '/user';
                                });
                            window.location = '/user';

                        },
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

        <script></script>
    @endsection
