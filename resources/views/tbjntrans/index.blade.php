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
                        <table id="tbl-jntrans" class="table table-bordered table-hover table-sm tbl-jntrans">
                            {{-- <table id="tbl-jntrans" class="table table-bordered table-striped tbl-jntrans"> --}}
                            <thead>
                                <tr>
                                    <th width=30 style="text-align:center;">#</th>
                                    <th width="50">Kode</th>
                                    <th width="250">Nama</th>
                                    <th width="50">Keterangan</th>
                                    <th width="100">Aksi</th>
                                </tr>
                            </thead>
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

    <div class="modal fade" id="modaltambah" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
    </div>

    <script>
        reload_table();

        function reload_table() {

            $(function() {
                var table = $('.tbl-jntrans').DataTable({
                    ajax: "{{ route('tbjntransajax') }}",
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
                            orderable: false,
                            className: 'dt-body-center',
                            targets: [0],
                        },
                        {
                            orderable: false,
                            className: 'dt-body-center',
                            targets: [4],
                        },
                    ],
                    order: [
                        [1, 'asc']
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
                            data: 'kode',
                            name: 'kode',
                            // data: null,
                            render: function(data, type, row, meta) {
                                return `<a href="#" onclick="detail(${row.id})">${row.kode}</a>`;
                            }
                        },
                        {
                            data: 'nama',
                            name: 'nama'
                        },
                        {
                            data: 'keterangan',
                            name: 'keterangan'
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
                                return `<div class="btn-group" role="group">
                                            <button id="btnGroupDrop1" type="button" class="btn btn-secondary btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                                Pilih Aksi
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                                <li><a class="dropdown-item <?= $pakai == 1 ? '' : 'disabled' ?>" onclick="detail(${row.id})" href="#" readonly><i class='fa fa-eye'"></i> View</a></li>
                                                <li><a class="dropdown-item <?= $edit == 1 ? '' : 'disabled' ?>" onclick="edit(${row.id})" href="#" readonly><i class='fa fa-edit'"></i> Edit</a></li>
                                                <li><a class="dropdown-item <?= $hapus == 1 ? '' : 'disabled' ?>" onclick="hapus(${row.id})" href="#" readonly><i class='fa fa-trash'"></i> Hapus</a></li>
                                            </ul>
                                        </div>`;

                            },
                        },
                    ]
                });

            });
        }

        function reload_table1() {
            $(document).ready(function() {
                $('#tbl-jntrans-data').DataTable({
                    // "destroy": true,
                    "aLengthMenu": [
                        [5, 50, 100, -1],
                        [5, 50, 100, "All"]
                    ],
                });
            });
            $.ajax({
                url: "<?= 'tabel_jntrans' ?>",
                beforeSend: function(f) {
                    $('.btnreload').attr('disable', 'disabled')
                    $('.btnreload').html('<i class="fa fa-spin fa-spinner"></i>')
                    // alert('1');
                    $('#tabel_jntrans').html('<center>Loading Table ...</center>');
                },
                success: function(data) {
                    // alert(data);
                    $('#tabel_jntrans').html(data);
                    $('.btnreload').removeAttr('disable')
                    $('.btnreload').html('<i class="fa fa-spinner">')
                }
            })
        }

        $('.tomboltambah').click(function(e) {
            e.preventDefault();
            $.ajax({
                url: `{{ route('tbjntrans.create') }}`,
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
                url: `{{ url('tbjntrans') }}/${id}`,
                dataType: "json",
                data: {
                    id: id,
                    _method: "GET",
                    _token: '{{ csrf_token() }}',
                },
                success: function(response) {
                    if (response.data) {
                        // console.log(response.data);
                        $('#modaltambah').html(response.body);
                        $('#modaltambah').modal('show');
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
                url: `{{ url('tbjntrans') }}/${id}/edit`,
                dataType: "json",
                success: function(response) {
                    if (response.data) {
                        // let data_response = JSON.parse(response.data);
                        // alert(data_response.kode);
                        // console.log(response.data);
                        // $('.viewmodal').html(response.data).show();
                        $('#modaltambah').html(response.body);
                        $('#modaltambah').modal('show');
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
                            url: `{{ url('tbjntrans') }}/${id}`,
                            type: "POST",
                            data: {
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
                                    toastr.danger('Data berhasil dihapus silahkan melanjutkan')
                                } else {
                                    // swal({
                                    //     title: "Data berhasil dihapus! ",
                                    //     text: "",
                                    //     icon: "success"
                                    // })
                                    reload_table();
                                    toastr.info('Data berhasil dihapus, silahkan melanjutkan')
                                    // .then(function() {
                                    //     window.location.href = '/tbjntrans';
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
