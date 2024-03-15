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
                            &nbsp;<span><button tipe="button" class="btn btn-primary btn-sm tomboltambah"
                                    {{ $tambah != 1 ? 'disabled' : '' }}> <i class="fa fa-circle-plus"></i>
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
                        <table id="tbl-mohklruang" class="table table-bordered table-hover table-sm tbl-mohklruang">
                            {{-- <table id="tbl-mohklruang" class="table table-bordered table-striped tbl-mohklruang"> --}}
                            <thead>
                                <tr>
                                    <th width=30 style="text-align:center;">#</th>
                                    <th width="80">Nomor</th>
                                    <th width="60">Tanggal</th>
                                    {{-- <th width="50">Kode</th> --}}
                                    <th width="200">Jenis</th>
                                    <th width="70">Cara Bayar</th>
                                    <th width="70">Jumlah</th>
                                    <th width="30">Proses</th>
                                    <th width="30">Batal</th>
                                    <th width="100">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- @foreach ($mohklruang as $row)
                                    <tr>
                                        <td style="text-align:center;">{{ $loop->iteration }}</td>
                                        <td>{{ $row->kode }}</td>
                                        <td>{{ $row->nama }}</td>
                                        <td>{{ $row->aktif }}</td>
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
    <div class="modal fade" id="modalcaribank" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
    </div>
    <div class="modal fade" id="modalcarijnskartu" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
    </div>
    <div class="modal fade" id="modaldetail" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
    </div>
    <div class="modal fade" id="modaleditdetail" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
    </div>
    <div class="modal fade" id="modalbatalproses" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
    </div>
    <div class="modal fade" id="modalcarisupplier" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
    </div>

    <script>
        reload_table();

        function reload_table() {

            $(function() {
                var table = $('.tbl-mohklruang').DataTable({
                    ajax: "{{ route('mohklruangajax') }}",
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
                            targets: [0, 6, 7],
                        },
                        {
                            orderable: true,
                            className: 'dt-body-right',
                            targets: [5],
                        },
                        {
                            orderable: false,
                            className: 'dt-body-center',
                            targets: [8],
                        }
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
                            data: 'nomohon',
                            name: 'nomohon',
                            // "render": function(data, type, row, meta) {
                            //     return meta.row + meta.settings._iDisplayStart + 1;
                            // }
                            // data: null,
                            render: function(data, type, row, meta) {
                                return `<a href="#" onclick="detail(${row.id})">${row.nomohon}</a>`;
                            }
                        },
                        {
                            data: 'tglmohon',
                            name: 'tglmohon'
                        },
                        // {
                        //     data: 'tgljual',
                        //     name: 'tgljual'
                        // },
                        // {
                        //     data: 'kdjnkeluar',
                        //     name: 'kdjnkeluar'
                        // },
                        {
                            data: 'nmjnkeluar',
                            name: 'nmjnkeluar'
                        },
                        {
                            data: 'carabayar',
                            name: 'carabayar'
                        },
                        {
                            data: 'subtotal',
                            name: 'subtotal',
                            render: function(data, type, row, meta) {
                                return meta.settings.fnFormatNumber(row.subtotal);
                            }
                        },
                        // {
                        //     data: 'total',
                        //     render: function(data, type, row, meta) {
                        //         return meta.settings.fnFormatNumber(row.total);
                        //     }
                        // },
                        // {
                        //     data: 'action',
                        //     name: 'action',
                        //     orderable: false,
                        //     searchable: false
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
                                            <li><a class="dropdown-item <?= $edit == 1 ? '' : 'disabled' ?>" onclick="input_mohklruangd(${row.id})" href="#" readonly><i class='fa fa-book'"></i> Detail</a></li>
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

        function reload_table1() {
            $(document).ready(function() {
                $('#tbl-mohklruang-data').DataTable({
                    // "destroy": true,
                    "aLengthMenu": [
                        [5, 50, 100, -1],
                        [5, 50, 100, "All"]
                    ],
                });
            });
            $.ajax({
                url: "<?= 'tabel_mohklruang' ?>",
                beforeSend: function(f) {
                    $('.btnreload').attr('disable', 'disabled')
                    $('.btnreload').html('<i class="fa fa-spin fa-spinner"></i>')
                    // alert('1');
                    $('#tabel_mohklruang').html('<center>Loading Table ...</center>');
                },
                success: function(data) {
                    // alert(data);
                    $('#tabel_mohklruang').html(data);
                    $('.btnreload').removeAttr('disable')
                    $('.btnreload').html('<i class="fa fa-spinner">')
                }
            })
        }

        $('.tomboltambah').click(function(e) {
            e.preventDefault();
            $.ajax({
                url: `{{ route('mohklruang.create') }}`,
                dataType: "json",
                success: function(response) {
                    // $('.viewmodal').html(response.data).show();
                    $('#modaltambahmaster').html(response.body)
                    $('#modaltambahmaster').modal('show');
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
        })

        function detail(id) {
            $.ajax({
                type: "get",
                url: `{{ url('mohklruang') }}/${id}`,
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
                url: `{{ url('mohklruang') }}/${id}/edit`,
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
                        $('#modaltambahmaster').html(response.body);
                        $('#modaltambahmaster').modal('show');
                        // $('#kode').modal('aaaaa');
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

        function input_mohklruangd(id) {
            $.ajax({
                type: "get",
                url: `{{ url('mohklruanginputmohklruangd') }}`,
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
                            url: `{{ url('mohklruangproses') }}/${id}`,
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
                url: `{{ url('mohklruangbatalproses') }}`,
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
                            url: `{{ url('mohklruangcancel') }}`,
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
                            url: `{{ url('mohklruangambil') }}`,
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
                            url: `{{ url('mohklruang') }}/${id}`,
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
                                    toastr.error(
                                        'Data gagal dihapus,<br>data sudah terpakai di transaksi !')
                                } else {
                                    // swal({
                                    //     title: "Data berhasil dihapus! ",
                                    //     text: "",
                                    //     icon: "success"
                                    // })
                                    reload_table();
                                    toastr.info('Data berhasil dihapus, silahkan melanjutkan')
                                    // .then(function() {
                                    //     window.location.href = '/mohklruang';
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

        function cetak(id) {
            $url = "{{ url('mohklruangcetak') }}?id=" + id
            window.open($url, '_blank')
        }
    </script>
@endsection

{{-- @stop --}}
