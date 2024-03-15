@extends('/home/index')

@section('content')
    @include('home.akses');
    <?php
    // dd('aaaa' . $title);
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
                            <h4 class="m-0 text-dark">{{ $title }}&nbsp;</h4>
                            &nbsp;<span><button type="button" class="btn btn-primary btn-sm tomboltambah"
                                    {{ $pakai != 1 ? 'disabled' : '' }}> <i class="fa fa-circle-plus"></i>
                                    Tambah</button></span>
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
                <div class="card mt-2">
                    <div class="card-body">
                        <table id="tbl-opname" class="table table-bordered table-hover table-sm tbl-opname">
                            {{-- <table id="so" class="table table-bordered table-striped so"> --}}
                            <thead>
                                <tr>
                                    <th width=30 style="text-align:center;">#</th>
                                    <th width="120">No.Opname</th>
                                    <th width="80">Tanggal</th>
                                    <th width="250">Pelaksana</th>
                                    <th width="450">Keterangan</th>
                                    <th width="50">Proses</th>
                                    <th width="50">Batal</th>
                                    <th width="120">Aksi</th>
                                </tr>
                            </thead>
                            {{-- @foreach ($opnameh as $row)
                                <tr>
                                    <td style="text-align:center;">{{ $loop->iteration }}</td>
                                    <td>{{ $row->noopname }}</td>
                                    <td>{{ $row->tglopname }}</td>
                                    <td width="100">
                                        <button type="button" class="btn btn-success btn-sm"><i
                                                class="fa fa-eye"></i></button>
                                        <form onsubmit="return deletedata('{{ $row->id }}')" style="display:inline"
                                            methode="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" title="Hapus Data" class="btn btn-danger btn-sm"> <i
                                                    class="fas fa-trash-alt"></i></button>
                                        </form>
                                    </td>
                            @endforeach --}}
                            <tbody>
                            </tbody>
                        </table>
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

    <div class="modal fade" id="modaltambahmaster" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
    </div>
    <div class="modal fade" id="modaltambah" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
    </div>
    <div class="modal fade" id="modaltambahtbl" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
    </div>
    <div class="modal fade" id="modaldetail" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
    </div>
    <div class="modal fade" id="modaleditdetail" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
    </div>
    <div class="modal fade" id="modalcaritbbarang" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
    </div>
    <div class="modal fade" id="modalbatalproses" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
    </div>

    <script>
        reload_table();

        function reload_table() {
            $(function() {
                var table = $('.tbl-opname').DataTable({
                    ajax: "{{ route('opnameajax') }}",
                    // beforeSend: function(jqXHR) {
                    //     jqXHR.overrideMimeType("application/json");
                    //     $('#p1').append('<h3> The beforeSend() function called. </h3>');
                    // }
                    // scrollCollapse: true,
                    scrollY: '42vh',
                    // info: true,
                    // responsive: true,
                    autoWidth: false,
                    aLengthMenu: [
                        [5, 50, 100, -1],
                        [5, 50, 100, "All"]
                    ],
                    iDisplayLength: 5,
                    scrollX: true,
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    paging: true,
                    lengthChange: true,
                    searching: true,
                    ordering: true,
                    columnDefs: [{
                            className: 'dt-body-center',
                            targets: [0],
                        },
                        {
                            orderable: true,
                            className: 'dt-body-left',
                            targets: [2],
                        },
                        {
                            orderable: true,
                            className: 'dt-body-left',
                            targets: [4],
                        },
                        {
                            orderable: true,
                            className: 'dt-body-center',
                            targets: [5],
                        },
                        {
                            orderable: false,
                            className: 'dt-body-center',
                            targets: [6],
                        },
                    ],
                    order: [
                        [1, 'desc']
                    ],
                    columns: [{
                            orderable: false,
                            "data": null,
                            "searchable": false,
                            "render": function(data, type, row, meta) {
                                return meta.row + meta.settings._iDisplayStart + 1;
                            }
                        },
                        {
                            orderable: true,
                            // data: 'kode1',
                            // name: 'kode1'
                            data: 'noopname',
                            name: 'noopname',
                            // "render": function(data, type, row, meta) {
                            //     return meta.row + meta.settings._iDisplayStart + 1;
                            // }
                            // data: null,
                            render: function(data, type, row, meta) {
                                return `<a href="#" onclick="detail(${row.id})">${row.noopname}</a>`;
                            }
                        },
                        {
                            data: 'tglopname',
                            name: 'tglopname'
                        },
                        {
                            data: 'pelaksana',
                            name: 'pelaksana'
                        },
                        {
                            data: 'keterangan',
                            name: 'keterangan'
                        },
                        // {
                        //     data: 'proses',
                        //     name: 'proses'
                        // },
                        // {
                        //     data: 'batal',
                        //     name: 'batal'
                        // },
                        {
                            orderable: true,
                            // data: 'aktif',
                            // name: 'aktif'
                            'render': function(data, type, row) {
                                if (row.proses == 'Y') {
                                    return `<input type="checkbox" checked disabled>`;
                                } else {
                                    return `<input type="checkbox" disabled>`;
                                }
                            }
                        },
                        {
                            orderable: true,
                            // data: 'aktif',
                            // name: 'aktif'
                            'render': function(data, type, row) {
                                if (row.batal == 'Y') {
                                    return `<input type="checkbox" checked disabled>`;
                                } else {
                                    return `<input type="checkbox" disabled>`;
                                }
                            }
                        },
                        // {
                        //     data: null,
                        //     render: function(data, type, row, meta) {
                        //         return `<a href="#${row.id}"><button onclick="detail(${row.id})" class='btn btn-sm btn-info' href='javascript:void(0)' <?= $pakai == 1 ? '' : 'disabled' ?>><i class='fa fa-eye'></i></button></a>
                    //         <a href="#${row.id}"><button onclick="edit(${row.id})" class='btn btn-sm btn-warning' href='javascript:void(0)' <?= $edit == 1 ? '' : 'disabled' ?>><i class='fa fa-edit'></i></button></a>
                    //         <a href="#${row.id},${row.kode}"><button onclick="hapus(${row.id})" class='btn btn-sm btn-danger' href='javascript:void(0)' <?= $hapus == 1 ? '' : 'disabled' ?>><i class='fa fa-trash'></i></button></a>`;

                        //     }
                        // },
                        {
                            data: null,
                            render: function(data, type, row, meta) {
                                // if (row.batal == 'Y') {
                                //     return `Canceled`;
                                // } else {
                                //     if (row.proses == 'Y') {
                                //         return `<div class="btn-group" role="group">
                            //         <button type="button" class="btn bt n-secondary btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                            //         Dropdown
                            //         </button>
                            //         <div class="dropdown-menu">
                            //             <a class="dropdown-item" href="#">Dropdown link</a>
                            //             <a class="dropdown-item" href="#">Dropdown link</a>
                            //         </div>
                            //     </div>`
                                //     }
                                // }

                                if (row.batal == 'Y') {
                                    return `<div class="btn-group" role="group">
                                                <button id="btnGroupDrop1" type="button" class="btn btn-warning btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                                    Pilih Aksi
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                                    <li><a class="dropdown-item <?= $hapus == 1 ? '' : 'disabled' ?>"" onclick="ambil(${row.id})" href="#" readonly><i class='fa fa-arrow-left'"></i> Ambil</a></li>
                                                </ul>
                                            </div>`;
                                } else {
                                    if (row.proses == 'Y') {
                                        return `<div class="btn-group" role="group">
                                            <button id="btnGroupDrop1" type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                                Pilih Aksi
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                                <li><a class="dropdown-item <?= $pakai == 1 ? '' : 'disabled' ?>" onclick="detail(${row.id})" href="#" readonly><i class='fa fa-eye'"></i> View</a></li>
                                                <li><a class="dropdown-item <?= $unproses == 1 ? '' : 'disabled' ?>"" onclick="unproses(${row.id})" href="#" readonly><i class='fa fa-arrow-left'"></i> Unproses</a></li>
                                                <li><a class="dropdown-item <?= $cetak == 1 ? '' : 'disabled' ?>"" onclick="cetak(${row.id})" href="#" readonly><i class='fa fa-print'"></i> Cetak Nota</a></li>
                                            </ul>
                                            </div>`;
                                    } else {
                                        return `<div class="btn-group" role="group">
                                        <button id="btnGroupDrop1" type="button" class="btn btn-secondary btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                            Pilih Aksi
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                            <li><a class="dropdown-item <?= $pakai == 1 ? '' : 'disabled' ?>" onclick="detail(${row.id})" href="#" readonly><i class='fa fa-eye'"></i> View</a></li>
                                            <li><a class="dropdown-item <?= $edit == 1 ? '' : 'disabled' ?>" onclick="edit(${row.id})" href="#" readonly><i class='fa fa-edit'"></i> Edit</a></li>
                                            <li><a class="dropdown-item <?= $edit == 1 ? '' : 'disabled' ?>" onclick="salin_barang(${row.id})" href="#" readonly><i class='fa fa-copy'"></i> Salin Barang</a></li>
                                            <li><a class="dropdown-item <?= $edit == 1 ? '' : 'disabled' ?>" onclick="input_opnamed(${row.id})" href="#" readonly><i class='fa fa-book'"></i> Detail</a></li>
                                            <li><a class="dropdown-item <?= $proses == 1 ? '' : 'disabled' ?>" onclick="proses(${row.id})" href="#" readonly><i class='fa fa-arrow-right'"></i> Proses</a></li>
                                            <li><a class="dropdown-item <?= $hapus == 1 ? '' : 'disabled' ?>" onclick="cancel(${row.id})" href="#" readonly><i class='fa fa-ban'"></i> Cancel</a></li>
                                            <li><a class="dropdown-item <?= session('level') == 'ADMINISTRATOR' ? '' : 'disabled' ?>" onclick="hapus(${row.id})" href="#" readonly><i class='fa fa-trash'"></i> Hapus</a></li>
                                        </ul>
                                        </div>`;
                                    }
                                    // <li><a class="dropdown-item <?= $hapus == 1 ? '' : 'disabled' ?>" onclick="hapus(${row.id})" href="#" readonly><i class='fa fa-trash'"></i> Hapus</a></li>
                                }
                            }
                        },
                    ]
                });

            });
        }

        $('.tomboltambah').click(function(e) {
            e.preventDefault();
            $.ajax({
                url: `{{ route('opname.create') }}`,
                dataType: "json",
                success: function(response) {
                    $('#modaltambahmaster').html(response.body)
                    $('#modaltambahmaster').modal('show');
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    if (xhr.status == '401' || xhr.status == '419') {
                        toastr.error('Login Expired, silahkan login ulang')
                        toastr.options = {
                            "closeButton": false,
                            "debug": false,
                            "newestOnTop": false,
                            "progressBar": true,
                            "positionClass": "toast-top-center",
                            "preventDuplicates": false,
                            "onclick": null,
                            "showDuration": "300",
                            "hideDuration": "1000",
                            "timeOut": "5000",
                            "extendedTimeOut": "1000",
                            "showEasing": "swing",
                            "hideEasing": "linear",
                            "showMethod": "fadeIn",
                            "hideMethod": "fadeOut"
                        }
                        window.location.href = "{{ route('actionlogout') }}";
                    }
                }
            })
        })

        function detail(id) {
            $.ajax({
                type: "get",
                url: `{{ url('opname') }}/${id}`,
                dataType: "json",
                data: {
                    id: id,
                    _method: "GET",
                    _token: '{{ csrf_token() }}',
                },
                success: function(response) {
                    if (response.data) {
                        // console.log(response.data);
                        $('#modaltambahmaster').html(response.body);
                        $('#modaltambahmaster').modal('show');
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    // alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                    // const errors = xhr.responseJSON?.errors
                    // console.log(errors);
                    if (xhr.status == '401' || xhr.status == '419') {
                        toastr.error('Login Expired, silahkan login ulang')
                        toastr.options = {
                            "closeButton": false,
                            "debug": false,
                            "newestOnTop": false,
                            "progressBar": true,
                            "positionClass": "toast-top-center",
                            "preventDuplicates": false,
                            "onclick": null,
                            "showDuration": "300",
                            "hideDuration": "1000",
                            "timeOut": "5000",
                            "extendedTimeOut": "1000",
                            "showEasing": "swing",
                            "hideEasing": "linear",
                            "showMethod": "fadeIn",
                            "hideMethod": "fadeOut"
                        }
                        window.location.href = "{{ route('actionlogout') }}";
                    }
                }
            })
        }

        function edit(id) {
            $.ajax({
                type: "get",
                url: `{{ url('opname') }}/${id}/edit`,
                dataType: "json",
                data: {
                    id: id,
                    methode: "get",
                },
                success: function(response) {
                    if (response.data) {
                        $('#modaltambahmaster').html(response.body);
                        $('#modaltambahmaster').modal('show');
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    if (xhr.status == '401' || xhr.status == '419') {
                        toastr.error('Login Expired, silahkan login ulang')
                        toastr.options = {
                            "closeButton": false,
                            "debug": false,
                            "newestOnTop": false,
                            "progressBar": true,
                            "positionClass": "toast-top-center",
                            "preventDuplicates": false,
                            "onclick": null,
                            "showDuration": "300",
                            "hideDuration": "1000",
                            "timeOut": "5000",
                            "extendedTimeOut": "1000",
                            "showEasing": "swing",
                            "hideEasing": "linear",
                            "showMethod": "fadeIn",
                            "hideMethod": "fadeOut"
                        }
                        window.location.href = "{{ route('actionlogout') }}";
                    }
                }
            })
        }

        function salin_barang(id) {
            swal({
                    title: "Yakin akan Salin Barang ?",
                    text: "",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            url: `{{ url('opnamesalinbarang') }}/${id}`,
                            type: "POST",
                            data: {
                                id: id,
                                _token: '{{ csrf_token() }}',
                            },
                            dataType: "json",
                            success: function(response) {
                                if (response.sukses == false) {
                                    toastr.warning(
                                        'Data gagal di salin !')
                                } else {
                                    reload_table();
                                    toastr.info('Data berhasil di salin')
                                }
                            },
                            error: function(xhr, ajaxOptions, thrownError) {
                                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                            }

                        })
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
                            url: `{{ url('opname') }}/${id}`,
                            type: "POST",
                            data: {
                                id: id,
                                _method: "DELETE",
                                _token: '{{ csrf_token() }}',
                            },
                            dataType: "json",
                            success: function(response) {
                                if (response.sukses == false) {
                                    // swal({
                                    //     title: "Data gagal dihapus!",
                                    //     text: "",
                                    //     icon: "error"
                                    // })
                                    toastr.warning('Data gagal di hapus silahkan melanjutkan')
                                } else {
                                    // swal({
                                    //     title: "Data berhasil dihapus! ",
                                    //     text: "",
                                    //     icon: "success"
                                    // })
                                    reload_table();
                                    toastr.info('Data berhasil dihapus, silahkan melanjutkan')
                                    // .then(function() {
                                    //     window.location.href = '/tbbarang';
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

        function proses(id) {
            swal({
                    title: "Yakin akan Proses ?",
                    text: "Data show on Report!",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            url: `{{ url('opnameproses') }}/${id}`,
                            type: "POST",
                            data: {
                                id: id,
                                _token: '{{ csrf_token() }}',
                            },
                            dataType: "json",
                            success: function(response) {
                                if (response.sukses == false) {
                                    toastr.warning(
                                        'Data gagal di proses (total masih 0), silahkan melanjutkan')
                                } else {
                                    reload_table();
                                    toastr.info('Data berhasil di proses silahkan melanjutkan')
                                }
                            },
                            error: function(xhr, ajaxOptions, thrownError) {
                                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                            }

                        })
                    }
                })
        }

        function unproses(id) {
            // swal({
            //         title: "Yakin akan UnProses ?",
            //         text: "Data not show on Report!",
            //         icon: "warning",
            //         buttons: true,
            //         dangerMode: true,
            //     })
            //     .then((willDelete) => {
            //         if (willDelete) {
            $.ajax({
                type: "get",
                url: `{{ url('opnamebatalproses') }}`,
                dataType: "json",
                data: {
                    id: id,
                    methode: "get",
                },
                success: function(response) {
                    if (response.data) {
                        $('#modalbatalproses').html(response.body);
                        $('#modalbatalproses').modal('show');
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    if (xhr.status == '401' || xhr.status == '419') {
                        toastr.error('Login Expired, silahkan login ulang')
                        toastr.options = {
                            "closeButton": false,
                            "debug": false,
                            "newestOnTop": false,
                            "progressBar": true,
                            "positionClass": "toast-top-center",
                            "preventDuplicates": false,
                            "onclick": null,
                            "showDuration": "300",
                            "hideDuration": "1000",
                            "timeOut": "5000",
                            "extendedTimeOut": "1000",
                            "showEasing": "swing",
                            "hideEasing": "linear",
                            "showMethod": "fadeIn",
                            "hideMethod": "fadeOut"
                        }
                        window.location.href = "{{ route('actionlogout') }}";
                    }
                }
            })
        }

        function unprosesxx(id) {
            swal({
                    title: "Yakin akan UnProses ?",
                    text: "Data not show on Report!",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            url: `{{ url('opnameunproses') }}`,
                            type: "POST",
                            data: {
                                id: id,
                                _token: '{{ csrf_token() }}',
                            },
                            dataType: "json",
                            success: function(response) {
                                if (response.sukses == false) {
                                    toastr.warning('Data gagal di unproses, silahkan melanjutkan')
                                } else {
                                    reload_table();
                                    toastr.info('Data berhasil di unproses silahkan melanjutkan')
                                }
                            },
                            error: function(xhr, ajaxOptions, thrownError) {
                                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                            }

                        })
                    }
                })
        }

        function cancel(id) {
            swal({
                    title: "Yakin akan Cancel ?",
                    text: "Data not show on Report!",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            url: `{{ url('opnamecancel') }}`,
                            type: "POST",
                            data: {
                                id: id,
                                _token: '{{ csrf_token() }}',
                            },
                            dataType: "json",
                            success: function(response) {
                                if (response.sukses == false) {
                                    toastr.warning('Data gagal di cancel, silahkan melanjutkan')
                                } else {
                                    reload_table();
                                    toastr.info('Data berhasil di cancel silahkan melanjutkan')
                                }
                            },
                            error: function(xhr, ajaxOptions, thrownError) {
                                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                            }

                        })
                    }
                })
        }

        function ambil(id) {
            swal({
                    title: "Yakin akan Ambil ?",
                    text: "Data will be active",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            url: `{{ url('opnameambil') }}`,
                            type: "POST",
                            data: {
                                id: id,
                                _token: '{{ csrf_token() }}',
                            },
                            dataType: "json",
                            success: function(response) {
                                if (response.sukses == false) {
                                    toastr.warning('Data gagal di ambil, silahkan melanjutkan')
                                } else {
                                    reload_table();
                                    toastr.info('Data berhasil di ambil silahkan melanjutkan')
                                }
                            },
                            error: function(xhr, ajaxOptions, thrownError) {
                                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                            }

                        })
                    }
                })
        }

        function input_opnamed(id) {
            $.ajax({
                type: "get",
                url: `{{ url('opnameinputopnamed') }}`,
                dataType: "json",
                data: {
                    id: id,
                    methode: "get",
                },
                success: function(response) {
                    if (response.data) {
                        $('#modaldetail').html(response.body);
                        $('#modaldetail').modal('show');
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    if (xhr.status == '401' || xhr.status == '419') {
                        toastr.error('Login Expired, silahkan login ulang')
                        toastr.options = {
                            "closeButton": false,
                            "debug": false,
                            "newestOnTop": false,
                            "progressBar": true,
                            "positionClass": "toast-top-center",
                            "preventDuplicates": false,
                            "onclick": null,
                            "showDuration": "300",
                            "hideDuration": "1000",
                            "timeOut": "5000",
                            "extendedTimeOut": "1000",
                            "showEasing": "swing",
                            "hideEasing": "linear",
                            "showMethod": "fadeIn",
                            "hideMethod": "fadeOut"
                        }
                        window.location.href = "{{ route('actionlogout') }}";
                    }
                }
            })
        }

        function cetak(id) {
            $url = "{{ url('opnamecetak') }}?id=" + id
            window.open($url, '_blank')
        }

        // Nampilin list data pilihan ===================
        function caritbbarang() {
            $.ajax({
                method: "GET",
                url: "<?= url('caritbbarang') ?>",
                dataType: "json",
                success: function(response) {
                    $('#modalcaritbbarang').html(response.body)
                    $("#modalcaritbbarang").modal('show');
                }
            })
        }

        function tgljttempo() {
            let tempo = document.getElementById('tempo').value
            let start = $("#tglopname").val();
            let result = new Date(start);
            let end = result.setDate(result.getDate() + parseFloat(tempo));
            let ed = new Date(end);
            let d = ed.getDate();
            let m = ed.getMonth() + 1;
            let y = ed.getFullYear();
            // console.log(y + '-' + (m <= 9 ? '0' + m : m) + '-' + (d <= 9 ? '0' + d : d));
            document.getElementById('tgl_jt_tempo').value = (y + '-' + (m <= 9 ? '0' + m : m) + '-' + (d <= 9 ? '0' + d :
                d));
            // select date(po_part.tanggal) as tanggal from po_part where date(po_part.tanggal) = '2023-07-05'
        }

        function hit_total() {
            var biaya_lain = document.getElementById("biaya_lain").value
            if (biaya_lain === "") {
                biaya_lain = 0;
            }
            var ppn = document.getElementById("ppn").value
            if (ppn === "") {
                ppn = 0;
            }
            var materai = document.getElementById("materai").value
            if (materai === "") {
                materai = 0;
            }
            var subtotal = parseFloat(document.getElementById("subtotalh").value)
            var total_sementara = parseFloat(biaya_lain) + parseFloat(subtotal)
            document.getElementById("total_sementara").value = parseFloat(total_sementara)
            var rp_ppn = parseFloat(total_sementara) * parseFloat((ppn / 100))
            var total = parseFloat(total_sementara) + parseFloat(rp_ppn) + parseFloat(materai)
            document.getElementById("total").value = total
        }

        function eraseText() {
            document.getElementById("kdbarang").value = "";
            document.getElementById("nmbarang").value = "";
            document.getElementById("kdsatuan").value = "";
            document.getElementById("qty").value = "0";
            document.getElementById("harga").value = "0";
            document.getElementById("discount").value = "0";
            document.getElementById("subtotal").value = "0";
        }

        function hit_subtotal() {
            document.getElementById('qty').value == "" ? document.getElementById('qty').value = 0 : document.getElementById(
                'qty').value
            document.getElementById('harga').value == "" ? document.getElementById('harga').value = 0 : document
                .getElementById('harga').value
            document.getElementById('discount').value == "" ? document.getElementById('discount').value = 0 : document
                .getElementById('discount').value
            var lharga = (parseFloat(document.getElementById('qty').value) * parseFloat(document.getElementById('harga')
                .value));
            var ldisc = lharga - (lharga * (document.getElementById('discount').value)) / 100;
            var lsubtotal = ldisc;
            document.getElementById('subtotal').value = lsubtotal;
        }
    </script>
@endsection

{{-- @stop --}}
