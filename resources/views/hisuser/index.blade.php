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
                        <table id="tbl-hisuser" class="table table-bordered table-striped tbl-hisuser">
                            <thead>
                                <tr>
                                    <th width=30 style="text-align:center;">#</th>
                                    <th width="130">Tanggal</th>
                                    <th width="200">Dokumen</th>
                                    <th width="150">Form</th>
                                    <th width="100">Status</th>
                                    <th width="100">User</th>
                                    <th>Catatan</th>
                                    <th width="30">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- @foreach ($hisuser as $row)
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

    <div class="modal fade" id="modaltambah" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
    </div>
    <div class="modal fade" id="modaledit" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
    </div>
    <div class="modal fade" id="modaldetail" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
    </div>

    <script>
        reload_table();

        function reload_table() {

            $(function() {
                var table = $('.tbl-hisuser').DataTable({
                    ajax: "{{ route('hisuserajax') }}",
                    // beforeSend: function(jqXHR) {
                    //     jqXHR.overrideMimeType("application/json");
                    //     $('#p1').append('<h3> The beforeSend() function called. </h3>');
                    // }
                    // scrollCollapse: true,
                    scrollY: '42vh',
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    paging: true,
                    lengthChange: true,
                    searching: true,
                    ordering: true,
                    columnDefs: [{
                            // className: 'dt-body-left',
                            targets: [1],
                        },
                        {
                            orderable: false,
                            className: 'dt-body-center',
                            targets: [0],
                        },
                        {
                            // className: 'dt-body-center',
                            targets: [4],
                        },
                        // {
                        //     // className: 'dt-body-center',
                        //     targets: [5],
                        // }
                    ],
                    order: [
                        [1, 'desc']
                    ],
                    info: true,
                    autoWidth: true,
                    responsive: true,
                    aLengthMenu: [
                        [5, 50, 100, -1],
                        [5, 50, 100, "All"]
                    ],
                    autoWidth: false,
                    iDisplayLength: 5,
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
                            data: 'datetime',
                            name: 'datetime',
                        },
                        {
                            // orderable: true,
                            // data: 'kode1',
                            // name: 'kode1'
                            data: 'dokumen',
                            name: 'dokumen',
                            // data: null,
                            // render: function(data, type, row, meta) {
                            //     return `<a href="#" onclick="detail(${row.id})">${row.kode}</a>`;
                            // }
                        },
                        {
                            data: 'form',
                            name: 'form',
                        },
                        {
                            data: 'status',
                            name: 'status',
                        },
                        {
                            data: 'user',
                            name: 'user',
                        },
                        {
                            data: 'catatan',
                            name: 'catatan',
                        },
                        // {
                        //     data: 'action',
                        //     name: 'action',
                        //     orderable: false,
                        //     searchable: false
                        // },
                        {
                            data: null,
                            render: function(data, type, row, meta) {
                                return `<a href="#${row.id}"><button onclick="hapus(${row.id})" class='btn btn-sm btn-danger' href='javascript:void(0)' <?= $pakai == 1 ? '' : 'disabled' ?>><i class='fa fa-trash'></i></button></a>`;

                            }
                        },
                    ]
                });

            });
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
                            url: `{{ url('hisuser') }}/${id}`,
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
                                    //     window.location.href = '/hisuser';
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
