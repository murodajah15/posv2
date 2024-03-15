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
                            {{-- &nbsp;<button style="display:inline" class="btn btn-outline-info btn-sm mb-2 btnreload"
                                onclick="reload_table()" type="button"><i class="fa fa-spinner"></i></button> --}}
                            {{-- &nbsp;<span><button tipe="button" class="btn btn-primary btn-sm tomboltambah"
                                    {{ $pakai != 1 ? 'disabled' : '' }}> <i class="fa fa-circle-plus"></i>
                                    Tambah</button></span> --}}
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
                {{-- <div id="tabel_bank"></div> --}}
                {{-- <div class="container-fluid">
                    <div class="row mb-2"> --}}
                <div class="card mt-2">
                    {{-- <div class="card-header">
                        <h3 class="card-title">DataTable with default features</h3>
                    </div> --}}
                    <!-- /.card-header -->
                    <div class="card-body">
                        {{-- <p align='center'><a target="_blank" class='btn btn-primary' href='module/user/proses_backup.php'
                                onClick="return confirm('Anda yakin akan proses backup database ?')">
                                <span class='glyphicon glyphicon-record'></span></button> Proses Backup</a></p> --}}
                        <button type="button" onclick="proses()" class="btn btn-primary">Proses</button>
                    </div>
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->
        </div>
    </div>

    <script>
        function proses() {
            swal({
                    title: "Yakin akan proses backup ?",
                    text: "", //"Once deleted, you will not be able to recover this data!",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        window.location.href = `{{ url('backup_proses') }}`
                        toastr.info('Data berhasil di backup, silahkan melanjutkan')
                        // $.ajax({
                        //     url: `{{ url('backup_proses') }}`,
                        //     type: "GET",
                        //     // dataType: "json",
                        //     headers: {
                        //         "Content-Type": "application / octet - stream",
                        //         "Content-Disposition": "attachment"
                        //     },
                        //     success: function(response) {
                        //         if (response.sukses == false) {
                        //             // swal({
                        //             //     title: "Data gagal di backup!",
                        //             //     text: "",
                        //             //     icon: "error"
                        //             // })
                        //             toastr.danger('Data gagal di backup silahkan melanjutkan')
                        //         } else {
                        //             // swal({
                        //             //     title: "Data berhasil di backup! ",
                        //             //     text: "",
                        //             //     icon: "success"
                        //             // })
                        //             toastr.info('Data berhasil di backup, silahkan melanjutkan')
                        //             // .then(function() {
                        //             //     window.location.href = '/hisuser';
                        //             // });
                        //         }
                        //     },
                        //     error: function(xhr, ajaxOptions, thrownError) {
                        //         alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                        //     }
                        // })
                    }
                })
        }
    </script>
@endsection

{{-- @stop --}}
