@extends('/home/index')

@section('content')
    @include('home.akses')
    <?php
    $pakai = session('pakai');
    $tambah = session('tambah');
    $edit = session('edit');
    $hapus = session('hapus');
    $proses = session('proses');
    $unproses = session('unproses');
    $cetak = session('cetak');
    ?>

    {{-- @section('content') --}}
    <?php $session = session(); ?>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Dashboard</h1>
                        {{-- {{ 'aaaa' . session('llogo') }} --}}
                        {{-- {{ $session->get('username') }} --}}
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/home">Home</a></li>
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Info boxes -->
                @if (session('level') != 'GUEST')
                    <div class="row">
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box mb-3">
                                <span class="info-box-icon bg-warning elevation-1"><a href="tbcustomer"><i
                                            class="fas fa-users"></i></a></span>
                                <div class="info-box-content">
                                    @if ($pakaicustomer == '1')
                                        <a href="tbcustomer"><span class="info-box-text">Jumlah Customer</span></a>
                                    @else
                                        <span class="info-box-text">Jumlah Customer</span>
                                    @endif
                                    {{-- <span class="info-box-number">300</span> --}}
                                    <?php
                                    $jumlah_customerf = number_format($jumlah_customer, 0, ',', '.');
                                    ?>
                                    <span class="info-box-number">{{ $jumlah_customerf }}</span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-info elevation-1"><a href="po"><i
                                            class="fas fa-cog"></i></a></span>

                                <div class="info-box-content">
                                    @if ($pakaipo == '1')
                                        <a href="po"><span class="info-box-text">Outstanding PO</span></a>
                                    @else
                                        <span class="info-box-text">Outstanding PO</span>
                                    @endif
                                    <span class="info-box-number">
                                        <div class="outstandingso"></div>
                                        <?php
                                        $jumlah_outstandingpof = number_format($jumlah_outstandingpo, 0, ',', '.');
                                        ?>
                                        <span class="info-box-number">{{ $jumlah_outstandingpof }}</span>
                                    </span>
                                    <small>Document</small>
                                    </span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <!-- /.col -->
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box mb-3">
                                <span class="info-box-icon bg-danger elevation-1"><a href="beli"><i
                                            class="fas fa-book"></i></a></span>
                                <div class="info-box-content">
                                    @if ($pakaibeli == '1')
                                        <a href="beli"><span class="info-box-text">Pembelian Bulan ini</span></a>
                                    @else
                                        <span class="info-box-text">Pembelian Bulan ini</span>
                                    @endif
                                    {{-- <span class="info-box-number">41 --}}
                                    {{-- <small>Document</small> --}}
                                    <?php
                                    $jumlah_beli_blnf = number_format($jumlah_beli_bln, 0, ',', '.');
                                    ?>
                                    <span class="info-box-number">Rp. {{ $jumlah_beli_blnf }}</span>
                                    </span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box mb-3">
                                <span class="info-box-icon bg-danger elevation-1"><a href="rbeli"><i
                                            class="fas fa-book"></i></a></span>
                                <div class="info-box-content">
                                    @if ($pakaibeli == '1')
                                        <a href="rbeli"><span class="info-box-text">Total Pembelian</span></a>
                                    @else
                                        <span class="info-box-text">Total Pembelian</span>
                                    @endif
                                    {{-- <span class="info-box-number">41 --}}
                                    {{-- <small>Document</small> --}}
                                    <?php
                                    $jumlah_belif = number_format($jumlah_beli, 0, ',', '.');
                                    ?>
                                    <span class="info-box-number">Rp. {{ $jumlah_belif }}</span>
                                    </span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <!-- /.col -->

                        <!-- fix for small devices only -->
                        <div class="clearfix hidden-md-up"></div>

                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box mb-3">
                                <span class="info-box-icon bg-success elevation-1"><a href="jual"><i
                                            class="fas fa-shopping-cart"></i></a></span>
                                <div class="info-box-content">
                                    @if ($pakaijual == '1')
                                        <a href="jual"><span class="info-box-text">Penjualan Bulan
                                                ini</span></a>
                                    @else
                                        <span class="info-box-text">Penjualan Bulan ini</span>
                                    @endif
                                    {{-- <span class="info-box-number">Rp. 7.600.000.000</span> --}}
                                    <?php
                                    $jumlah_jual_blnf = number_format($jumlah_jual_bln, 0, ',', '.');
                                    ?>
                                    <span class="info-box-number">Rp. {{ $jumlah_jual_blnf }}</span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <!-- /.col -->
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box mb-3">
                                <span class="info-box-icon bg-success elevation-1"><a href="rjual"><i
                                            class="fas fa-shopping-cart"></i></a></span>
                                <div class="info-box-content">
                                    @if ($pakaijual == '1')
                                        <a href="rjual"><span class="info-box-text">Total Penjualan</span></a>
                                    @else
                                        <span class="info-box-text">Total Penjualan</span>
                                    @endif
                                    {{-- <span class="info-box-number">Rp. 7.600.000.000</span> --}}
                                    <?php
                                    $jumlah_jualf = number_format($jumlah_jual, 0, ',', '.');
                                    ?>
                                    <span class="info-box-number">Rp. {{ $jumlah_jualf }}</span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box mb-3">
                                <span class="info-box-icon bg-secondary elevation-1"><a href="rpiutang"><i
                                            class="fas fa-filter"></i></a></span>
                                <div class="info-box-content">
                                    @if ($pakaijual == '1')
                                        <a href="rpiutang"><span class="info-box-text">Total Piutang</span></a>
                                    @else
                                        <span class="info-box-text">Total Piutang</span>
                                    @endif
                                    {{-- <span class="info-box-number">Rp. 7.600.000.000</span> --}}
                                    <?php
                                    $jumlah_piutangf = number_format($jumlah_piutang, 0, ',', '.');
                                    ?>
                                    <span class="info-box-number">Rp. {{ $jumlah_piutangf }}</span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box mb-3">
                                <span class="info-box-icon bg-secondary elevation-1"><a href="rhutang"><i
                                            class="fas fa-filter"></i></a></span>
                                <div class="info-box-content">
                                    @if ($pakaibeli == '1')
                                        <a href="rhutang"><span class="info-box-text">Total Hutang</span></a>
                                    @else
                                        <span class="info-box-text">Total Hutang</span>
                                    @endif
                                    {{-- <span class="info-box-number">Rp. 7.600.000.000</span> --}}
                                    <?php
                                    $jumlah_hutangf = number_format($jumlah_hutang, 0, ',', '.');
                                    ?>
                                    <span class="info-box-number">Rp. {{ $jumlah_hutangf }}</span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header border-transparent">
                            <div class='col-md-6'>
                                <h3 class="card-title"><b>Grafik Tahun&nbsp</b></h3>
                            </div>
                            <div class='col-md-6'>
                                <input type='number' id='tahun_grafik' name='tahun_grafik' style='width:5em'
                                    class='form-control form-control-sm' onchange="check_grafik()"
                                    value="{{ old('tahun_grafik') ?? date('Y') }}">
                            </div>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                                {{-- <button type="button" class="btn btn-tool" data-card-widget="remove">
                                <i class="fas fa-times"></i>
                            </button> --}}
                            </div>
                        </div>
                        <!-- /.row -->
                        <div class="card-body p-0">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-12 col-sm-12 col-md-6">
                                        <div id='grafik_jual'></div>
                                    </div>
                                    <div class="col-12 col-sm-12 col-md-6">
                                        <div id='grafik_piuthut'></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- <div class="card">
                        <div class="card-header border-transparent">
                            <div class="card-body p-0">
                                <div class="container-fluid">
                                    <div class="row">
                                        <div class="col-12 col-sm-12 col-md-12">
                                            <div id='grafik_hutang'></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                @endif


                <!-- TABLE: LATEST ORDERS -->
                <div class="card">
                    <div class="card-header border-transparent">
                        <h3 class="card-title"><b>Daftar Barang</b></h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                            {{-- <button type="button" class="btn btn-tool" data-card-widget="remove">
                                <i class="fas fa-times"></i>
                            </button> --}}
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-0">
                        <div class="container-fluid">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-sm" id='tbbarang-home'>
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Kode</th>
                                            <th>Nama</th>
                                            <th>Satuan</th>
                                            <th>Stock</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $no = 1; ?>
                                        {{-- @foreach ($tbbarang as $row)
                                            <tr>
                                                <td>{{ $no++ }}</td>
                  <td>{{ $row->kode }}</td>
                  <td>{{ $row->nama }}</td>
                  <td>{{ $row->nmsatuan }}</td>
                  <td>{{ $row->stock }}</td>
                  </tr>
                  @endforeach --}}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- /.table-responsive -->
                    </div>
                    {{-- <!-- /.card-body -->
                    <div class="card-footer clearfix">
                        <a href="javascript:void(0)" class="btn btn-sm btn-info float-left">Place New
                            Order</a>
                        <a href="javascript:void(0)" class="btn btn-sm btn-secondary float-right">View All
                            Orders</a>
                    </div>
                    <!-- /.card-footer --> --}}
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </section>
    </div>
    <!-- /.row -->
    {{-- </div><!--/. container-fluid --> --}}
    {{-- </section> --}}
    <!-- /.content -->
    {{-- </div> --}}
    <!-- /.content-wrapper -->

    <script src="assets/plugins/jquery/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            grafik();
            $('#tbbarang-home1').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                "aLengthMenu": [
                    [10, 50, 100, -1],
                    [10, 50, 100, "All"]
                ],
                "iDisplayLength": 10
            });
        })

        function check_grafik() {
            grafik();
        }

        reload_table();

        function reload_table() {
            $(function() {
                var table = $('#tbbarang-home').DataTable({
                    ajax: "{{ route('tbbarangajax') }}",
                    scrollY: '42vh',
                    // info: true,
                    autoWidth: false,
                    // responsive: true,
                    aLengthMenu: [
                        [6, 20, 50, 100, -1],
                        [6, 20, 50, 100, "All"]
                    ],
                    iDisplayLength: 6,
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
                            className: 'dt-body-right',
                            font: 'bold',
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
                            data: 'kode',
                            name: 'kode'
                        },
                        {
                            data: 'nama',
                            name: 'nama'
                        },
                        {
                            data: 'nmsatuan',
                            name: 'nmsatuan'
                        },
                        {
                            data: 'stock',
                            render: function(data, type, row, meta) {
                                return meta.settings.fnFormatNumber(row.stock);
                            }
                        },
                    ]
                });

            });
        }
    </script>
@endsection
{{-- @stop --}}

@section('grafik')
    {{-- <script src="https://code.highcharts.com/highcharts.js"></script> --}}
    <script src="assets/plugins/highcharts/highcharts.js"></script>
    <script>
        function grafik() {
            var tahun = document.getElementById('tahun_grafik').value
            $.ajax({
                url: `{{ url('grafik') }}`,
                dataType: "json",
                data: {
                    'tahun_grafik': tahun
                },
                success: function(response) {
                    // alert(1)
                    // $('#grafik_jual').html(response.body);
                    Highcharts.chart('grafik_jual', {
                        chart: {
                            type: 'column'
                        },
                        title: {
                            text: 'Penjualan VS Pembelian Tahun ' + tahun,
                            align: 'left'
                        },
                        subtitle: {
                            text: '<a target="_blank" ' +
                                'href="#"></a>',
                            align: 'left'
                        },
                        xAxis: {
                            // categories: ['USA', 'China', 'Brazil', 'EU', 'India', 'Russia'],
                            categories: response.data.categoriestahunan,
                            crosshair: true,
                            accessibility: {
                                description: 'Countries'
                            }
                        },
                        yAxis: {
                            min: 0,
                            title: {
                                text: 'Dalam Jutaan'
                                // text: ''
                            }
                        },
                        tooltip: {
                            // valueSuffix: ' (1000 MT)'
                            valueSuffix: ''
                        },
                        plotOptions: {
                            series: {
                                borderWidth: 0,
                                dataLabels: {
                                    enabled: true,
                                    format: '{point.y:.1f}'
                                }
                            }
                        },
                        series: [{
                                name: 'Penjualan',
                                // data: [51, 13, 55]
                                data: response.data.totaljualtahunan
                            },
                            {
                                name: 'Pembelian',
                                data: response.data.totalbelitahunan
                            }
                        ]
                    });


                    Highcharts.chart('grafik_piuthut', {
                        colors: ['#2f7ed8', 'red'],
                        chart: {
                            type: 'column'
                        },
                        title: {
                            text: 'Saldo Piutang VS Saldo Hutang Tahun ' + tahun,
                            align: 'left'
                        },
                        subtitle: {
                            text: '<a target="_blank" ' +
                                'href="#"></a>',
                            align: 'left'
                        },
                        xAxis: {
                            // categories: ['USA', 'China', 'Brazil', 'EU', 'India', 'Russia'],
                            categories: response.data.categoriestahunan,
                            crosshair: true,
                            accessibility: {
                                description: 'Countries'
                            }
                        },
                        yAxis: {
                            min: 0,
                            title: {
                                text: 'Dalam Jutaan'
                                // text: ''
                            }
                        },
                        tooltip: {
                            // valueSuffix: ' (1000 MT)'
                            valueSuffix: ''
                        },
                        plotOptions: {
                            series: {
                                borderWidth: 0,
                                dataLabels: {
                                    enabled: true,
                                    format: '{point.y:.1f}'
                                }
                            }
                        },
                        series: [{
                                name: 'Piutang',
                                // data: [51, 13, 55]
                                data: response.data.totalpiutang
                            },
                            {
                                name: 'Hutang',
                                data: response.data.totalhutang
                            }
                        ]
                    });



                    Highcharts.chart('grafik_piutang', {
                        colors: ['#2f7ed8'],
                        chart: {
                            type: 'area'
                        },
                        title: {
                            text: 'Piutang ' + tahun,
                            align: 'left'
                        },
                        subtitle: {
                            text: '<a target="_blank" ' +
                                'href="#"></a>',
                            align: 'left'
                        },
                        xAxis: {
                            // categories: ['USA', 'China', 'Brazil', 'EU', 'India', 'Russia'],
                            categories: response.data.categoriestahunan,
                            crosshair: true,
                            accessibility: {
                                description: 'Countries'
                            }
                        },
                        yAxis: {
                            min: 0,
                            title: {
                                text: 'Dalam Jutaan'
                                // text: ''
                            }
                        },
                        tooltip: {
                            // valueSuffix: ' (1000 MT)'
                            valueSuffix: ''
                        },
                        plotOptions: {
                            column: {
                                pointPadding: 0.2,
                                borderWidth: 0
                            }
                        },
                        plotOptions: {
                            series: {
                                borderWidth: 0,
                                dataLabels: {
                                    enabled: true,
                                    format: '{point.y:.1f}'
                                }
                            }
                        },
                        series: [{
                            name: 'Piutang',
                            // data: [51, 13, 55]
                            data: response.data.totalpiutang
                        }, ]
                    });

                    Highcharts.chart('grafik_hutang', {
                        colors: ['#f242f5'],
                        chart: {
                            type: 'area'
                        },
                        title: {
                            text: 'Hutang ' + tahun,
                            align: 'left'
                        },
                        subtitle: {
                            text: '<a target="_blank" ' +
                                'href="#"></a>',
                            align: 'left'
                        },
                        xAxis: {
                            // categories: ['USA', 'China', 'Brazil', 'EU', 'India', 'Russia'],
                            categories: response.data.categoriestahunan,
                            crosshair: true,
                            accessibility: {
                                description: 'Countries'
                            }
                        },
                        yAxis: {
                            min: 0,
                            title: {
                                text: 'Dalam Jutaan'
                                // text: ''
                            }
                        },
                        tooltip: {
                            // valueSuffix: ' (1000 MT)'
                            valueSuffix: ''
                        },
                        // plotOptions: {
                        //     column: {
                        //         pointPadding: 0.2,
                        //         borderWidth: 0
                        //     }
                        // },
                        plotOptions: {
                            series: {
                                borderWidth: 0,
                                dataLabels: {
                                    enabled: true,
                                    format: '{point.y:.1f}'
                                }
                            }
                        },
                        series: [{
                            name: 'Hutang',
                            // data: [51, 13, 55]
                            data: response.data.totalhutang
                        }, ]
                    });

                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                }
            })
            // var date = new Date();
            // // var tahun = date.getFullYear();
            // var bulan = date.getMonth();
            // var tanggal = date.getDate();
            // var hari = date.getDay();
            // var jam = date.getHours();
            // var menit = date.getMinutes();
            // var detik = date.getSeconds();
            // var tahun = document.getElementById('tahun_grafik').value;
            // // alert({!! json_encode($totaljualtahunan) !!});
            // switch (hari) {
            //     case 0:
            //         hari = "Minggu";
            //         break;
            //     case 1:
            //         hari = "Senin";
            //         break;
            //     case 2:
            //         hari = "Selasa";
            //         break;
            //     case 3:
            //         hari = "Rabu";
            //         break;
            //     case 4:
            //         hari = "Kamis";
            //         break;
            //     case 5:
            //         hari = "Jum'at";
            //         break;
            //     case 6:
            //         hari = "Sabtu";
            //         break;
            // }
            // switch (bulan) {
            //     case 0:
            //         bulan = "Januari";
            //         break;
            //     case 1:
            //         bulan = "Februari";
            //         break;
            //     case 2:
            //         bulan = "Maret";
            //         break;
            //     case 3:
            //         bulan = "April";
            //         break;
            //     case 4:
            //         bulan = "Mei";
            //         break;
            //     case 5:
            //         bulan = "Juni";
            //         break;
            //     case 6:
            //         bulan = "Juli";
            //         break;
            //     case 7:
            //         bulan = "Agustus";
            //         break;
            //     case 8:
            //         bulan = "September";
            //         break;
            //     case 9:
            //         bulan = "Oktober";
            //         break;
            //     case 10:
            //         bulan = "November";
            //         break;
            //     case 11:
            //         bulan = "Desember";
            //         break;
            // }
            // // var tampilTanggal = "Tanggal: " + hari + ", " + tanggal + " " + bulan + " " + tahun;
            // // document.getElementById("tampil").innerHTML = tampilTanggal;
            // // alert({!! json_encode($totaljualtahunan) !!});
            // Highcharts.chart('grafik_piutang', {
            //     chart: {
            //         type: 'area'
            //     },
            //     title: {
            //         text: 'Piutang ' + tahun,
            //         align: 'left'
            //     },
            //     subtitle: {
            //         text: '<a target="_blank" ' +
            //             'href="#"></a>',
            //         align: 'left'
            //     },
            //     xAxis: {
            //         // categories: ['USA', 'China', 'Brazil', 'EU', 'India', 'Russia'],
            //         categories: {!! json_encode($categoriestahunan) !!},
            //         crosshair: true,
            //         accessibility: {
            //             description: 'Countries'
            //         }
            //     },
            //     yAxis: {
            //         min: 0,
            //         title: {
            //             text: 'Dalam Jutaan'
            //             // text: ''
            //         }
            //     },
            //     tooltip: {
            //         // valueSuffix: ' (1000 MT)'
            //         valueSuffix: ''
            //     },
            //     // plotOptions: {
            //     //     column: {
            //     //         pointPadding: 0.2,
            //     //         borderWidth: 0
            //     //     }
            //     // },
            //     plotOptions: {
            //         series: {
            //             borderWidth: 0,
            //             dataLabels: {
            //                 enabled: true,
            //                 format: '{point.y:.1f}'
            //             }
            //         }
            //     },
            //     series: [{
            //         name: 'Piutang',
            //         // data: [51, 13, 55]
            //         data: {!! json_encode($totalpiutang) !!}
            //     }, ]
            // });

            // Highcharts.chart('grafik_jual', {
            //     chart: {
            //         type: 'column'
            //     },
            //     title: {
            //         text: 'Penjualan VS Pembelian Tahun ' + tahun,
            //         align: 'left'
            //     },
            //     subtitle: {
            //         text: '<a target="_blank" ' +
            //             'href="#"></a>',
            //         align: 'left'
            //     },
            //     xAxis: {
            //         // categories: ['USA', 'China', 'Brazil', 'EU', 'India', 'Russia'],
            //         categories: {!! json_encode($categoriestahunan) !!},
            //         crosshair: true,
            //         accessibility: {
            //             description: 'Countries'
            //         }
            //     },
            //     yAxis: {
            //         min: 0,
            //         title: {
            //             text: 'Dalam Jutaan'
            //             // text: ''
            //         }
            //     },
            //     tooltip: {
            //         // valueSuffix: ' (1000 MT)'
            //         valueSuffix: ''
            //     },
            //     plotOptions: {
            //         series: {
            //             borderWidth: 0,
            //             dataLabels: {
            //                 enabled: true,
            //                 format: '{point.y:.1f}'
            //             }
            //         }
            //     },
            //     series: [{
            //             name: 'Penjualan',
            //             // data: [51, 13, 55]
            //             data: {!! json_encode($totaljualtahunan) !!}
            //         },
            //         {
            //             name: 'Pembelian',
            //             data: {!! json_encode($totalbelitahunan) !!}
            //         }
            //     ]
            // });
        }
    </script>
@stop
