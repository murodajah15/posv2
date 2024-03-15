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
                            &nbsp;<span><button tipe="button" class="btn btn-primary btn-sm tomboltambah"
                                    {{ $pakai != 1 ? 'disabled' : '' }}> <i class="fa fa-circle-plus"></i>
                                    Tambah Data Baru</button></span>
                        </div>
                        @if (session('message'))
                            <div class="text-success">{{ session('message') }}</div>
                        @endif
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/home">Home</a></li>
                            <li class="breadcrumb-item active">{{ $title }}</li>
                            &nbsp;&nbsp;<button style="display:inline" class="btn btn-outline-info btn-sm mb-2 btnreload"
                                onclick="reload_table()" type="button"><i class="fa fa-spinner"></i></button>
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
                        {{-- <table id="tbl-bank" class="table table-bordered table-hover tbl-bank"> --}}
                        {{-- <table id="tbl-user" class="table table-bordered table-striped tbl-user">
                            <thead>
                                <tr>
                                    <th width=30 style="text-align:center;">#</th>
                                    <th width="100">Nama User</th>
                                    <th width="250">Email</th>
                                    <th width="150">Nama Lengkap</th>
                                    <th width="80">Level</th>
                                    <th width="10">Aktif</th>
                                    <th width="140">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table> --}}
                        {!! $dataTable->table() !!}
                    </div>
                    {{-- </div> --}}
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->
        </div>
    </div>


    <div class="viewmodal" style="display: none;"></div>

    <!-- jQuery -->
    <script src="assets/plugins/jquery/jquery.min.js"></script>

    <div class="modal fade" id="modalakses" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
    </div>
    <div class="modal fade" id="modaltambah" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
    </div>
    <div class="modal fade" id="modaledit" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
    </div>
    <div class="modal fade" id="modaldetail" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
    </div>

    {!! $dataTable->scripts() !!}
    <script>
        // reload_table();

        function reload_table() {
            window.LaravelDataTables['user-table'].ajax.reload();

            $(function() {
                // var table = $('.tbl-user').DataTable({
                //     ajax: "{{ route('userajax') }}",
                //     // beforeSend: function(jqXHR) {
                //     //     jqXHR.overrideMimeType("application/json");
                //     //     $('#p1').append('<h3> The beforeSend() function called. </h3>');
                //     // }
                //     // scrollCollapse: true,
                //     // scrollY: '50vh',
                //     destroy: true,
                //     processing: true,
                //     serverSide: true,
                //     paging: true,
                //     lengthChange: true,
                //     searching: true,
                //     ordering: true,
                //     columnDefs: [{
                //             className: 'dt-body-left',
                //             targets: [3],
                //         },
                //         {
                //             className: 'dt-body-left',
                //             targets: [4],
                //         },

                //         {
                //             orderable: false,
                //             className: 'dt-body-center',
                //             targets: [0],
                //         },
                //         {
                //             orderable: false,
                //             className: 'dt-body-center',
                //             targets: [5],
                //         }
                //     ],
                //     order: [
                //         [1, 'asc']
                //     ],
                //     info: true,
                //     autoWidth: true,
                //     responsive: true,
                //     aLengthMenu: [
                //         [5, 50, 100, -1],
                //         [5, 50, 100, "All"]
                //     ],
                //     autoWidth: false,
                //     iDisplayLength: 5,
                //     columns: [{
                //             orderable: false,
                //             "data": null,
                //             "searchable": false,
                //             "render": function(data, type, row, meta) {
                //                 return meta.row + meta.settings._iDisplayStart + 1;
                //             }
                //         },
                //         {
                //             orderable: true,
                //             // data: 'kode1',
                //             // name: 'kode1'
                //             data: 'username',
                //             name: 'username'
                //             // data: null,
                //             // render: function(data, type, row, meta) {
                //             //     return `<a href="#" onclick="detail(${row.id})">${row.kode}</a>`;
                //             // }
                //         },
                //         {
                //             data: 'email',
                //             name: 'email'
                //         },
                //         {
                //             data: 'nama_lengkap',
                //             name: 'nama_lengkap'
                //         },
                //         {
                //             data: 'level',
                //             name: 'level'
                //         },
                //         {
                //             data: 'aktif',
                //             name: 'aktif'
                //         },
                //         // {
                //         //     data: 'action',
                //         //     name: 'action',
                //         //     orderable: false,
                //         //     searchable: false
                //         // },
                //         {
                //             data: null,
                //             render: function(data, type, row, meta) {
                //                 return `<a href="#${row.id}"><button onclick="akses(${row.id})" class='btn btn-sm btn-success' href='javascript:void(0)' <?= $pakai == 1 ? '' : 'disabled' ?>><i class='fa fa-universal-access'></i></button></a>
            //                 <a href="#${row.id}"><button onclick="detail(${row.id})" class='btn btn-sm btn-info' href='javascript:void(0)' <?= $pakai == 1 ? '' : 'disabled' ?>><i class='fa fa-eye'></i></button></a>
            //                 <a href="#${row.id}"><button onclick="edit(${row.id})" class='btn btn-sm btn-warning' href='javascript:void(0)' <?= $edit == 1 ? '' : 'disabled' ?>> <i class='fa fa-edit'></i></button></a>
            //                 <a href="#${row.id},${row.kode}"><button onclick="hapus(${row.id})" class='btn btn-sm btn-danger' href='javascript:void(0)' <?= $hapus == 1 ? '' : 'disabled' ?>><i class='fa fa-trash'></i></button></a>`;

                //             }
                //         },
                //     ]
                // });

            });
        }


        $('.tomboltambah').click(function(e) {
            // window.location = '{{ route('user.create') }}'; // tambah dengan form
            e.preventDefault();
            $.ajax({
                url: `{{ route('user.create') }}`,
                dataType: "json",
                success: function(response) {
                    // $('.viewmodal').html(response.data).show();
                    $('#modaltambah').html(response.body)
                    $('#modaltambah').modal('show');
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                }
            })
        })

        function detail(id) {
            $.ajax({
                type: "get",
                url: `{{ url('user_show') }}`,
                dataType: "json",
                data: {
                    id: id,
                    _method: "GET",
                    _token: '{{ csrf_token() }}',
                },
                success: function(response) {
                    if (response.data) {
                        // console.log(response.data);
                        $('#modaldetail').html(response.body);
                        $('#modaldetail').modal('show');
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                }
            })
        }

        function edit(id) {
            $.ajax({
                type: "get",
                url: `{{ url('user') }}/${id}/edit`,
                dataType: "json",
                success: function(response) {
                    if (response.data) {
                        // let data_response = JSON.parse(response.data);
                        // alert(data_response.kode);
                        // console.log(response.data);
                        // $('.viewmodal').html(response.data).show();
                        $('#modaledit').html(response.body);
                        $('#modaledit').modal('show');
                        // $('#kode').modal('aaaaa');
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                }
            })
        }

        function akses(id) {
            $.ajax({
                type: "get",
                url: `{{ url('user_akses') }}`,
                dataType: "json",
                data: {
                    id: id,
                    _method: "GET",
                    _token: '{{ csrf_token() }}',
                },
                success: function(response) {
                    if (response.data) {
                        // let data_response = JSON.parse(response.data);
                        // alert(data_response.kode);
                        // console.log(response.data);
                        // $('.viewmodal').html(response.data).show();
                        $('#modalakses').html(response.body);
                        $('#modalakses').modal('show');
                        // $('#kode').modal('aaaaa');
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                }
            })
        }

        function hapus(id) {
            swal({
                    title: "Yakin akan hapus ?",
                    text: "Once deleted, you will not be able to recover this data!",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            url: `{{ url('user') }}/${id}`,
                            type: "POST",
                            data: {
                                _method: "DELETE",
                                _token: '{{ csrf_token() }}',
                            },
                            dataType: "json",
                            success: function(response) {
                                if (response.sukses == false) {
                                    swal({
                                        title: "Data gagal dihapus!",
                                        text: "",
                                        icon: "error"
                                    })
                                } else {
                                    swal({
                                        title: "Data berhasil dihapus! ",
                                        text: "",
                                        icon: "success"
                                    })
                                    reload_table();
                                    toastr.info('Data berhasil dihapus silahkan melanjutkan')
                                    // .then(function() {
                                    //     window.location.href = '/tbuser';
                                    // });
                                }
                            },
                            error: function(xhr, ajaxOptions, thrownError) {
                                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                            }

                        })
                    }
                })
        }
    </script>
@endsection

{{-- @stop --}}
