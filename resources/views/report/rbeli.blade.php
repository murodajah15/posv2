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
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/home">Home</a></li>
                            <li class="breadcrumb-item active">{{ $title }}</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
                <div class="card mt-2">
                    <div class="card-body">
                        <?php
                        $tanggal = date('Y-m-d');
                        $tahun = date('Y');
                        // $tgl = getdate();
                        // $tahun = $tgl['year'];
                        ?>
                        <font face="calibri">
                            <form method='post' target='_blank' action='rbeli_xls' class="rbeli_xls">
                                @csrf
                                <div class="row">
                                    <div class="col-md-12 bg">
                                        <input type='checkbox' class='form-checkbox' name='semuaperiode'
                                            id='checkall_periode' value='semuaperiode'> Semua Periode (M-D-Y)
                                        <input type="date" class='form-group' name='tanggal1' value="<?= $tanggal ?>">
                                        <input type="date" class='form-group' name='tanggal2' value="<?= $tanggal ?>">
                                        <!--</div>-->
                                    </div>
                                </div>
                                <div class='row'>
                                    <div class='col-md-12'>
                                        <input type='checkbox' class='form-checkbox' name='rincian' id='rincian'
                                            value='rincian' checked>
                                        Dengan Rincian
                                    </div>
                                </div>
                                <div class='row'>
                                    <div class='col-md-6'>
                                        <input type='checkbox' class='form-checkbox' name='semuabarang' id='checkall_barang'
                                            value='semuacutomer' checked> Semua Barang
                                        <div class="input-group mb-2">
                                            <input type="text" name="kdbarang" id="kdbarang"
                                                class="form-control form-control-sm" style="width: 30%"
                                                placeholder="Kode Barang">
                                            <input type="text" name="nmbarang" id="nmbarang"
                                                class="form-control form-control-sm" style="width: 60%" readonly>
                                            <button class="btn btn-outline-secondary btn-sm" type="button"
                                                name='btn_barang' id='btn_barang' style="width: 7%" id="caribarang">
                                                <i class="fa fa-search"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <div class='row'>
                                    <div class='col-md-6'>
                                        <input type='checkbox' class='form-checkbox' name='semuasupplier'
                                            id='checkall_supplier' value='semuacutomer' checked> Semua supplier
                                        <div class="input-group mb-2">
                                            <input type="text" name="kdsupplier" id="kdsupplier"
                                                class="form-control form-control-sm" style="width: 30%"
                                                placeholder="Kode Supplier">
                                            <input type="text" name="nmsupplier" id="nmsupplier"
                                                class="form-control form-control-sm" style="width: 60%" readonly>
                                            <button class="btn btn-outline-secondary btn-sm" type="button"
                                                name='btn_supplier' id='btn_supplier' style="width: 7%" id="carisupplier">
                                                <i class="fa fa-search"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <div class='row'>
                                    <div class='col-md-12'>
                                        <input type='checkbox' class='form-checkbox' name='groupingsupplier'
                                            id='groupingsupplier' value='groupingsupplier' checked>
                                        Grouping Supplier
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 bg">
                                        <br><button type='submit' class='btn btn-primary btn-sm'>Cetak</button>
                                    </div>
                                </div>
                            </form>
                        </font>
                    </div>
                </div>
                <div id='tbl-rso'></div>
                {{-- </div> --}}
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->
    </div>
    </div>


    <div class="viewmodal" style="display: none;"></div>

    <!-- jQuery -->
    <script src="assets/plugins/jquery/jquery.min.js"></script>

    <div class="modal fade" id="modalcarisupplier" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
    </div>
    <div class="modal fade" id="modalcaritbbarang" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
    </div>

    <script>
        $('#btn_barang').on('click', function(e) {
            $.ajax({
                method: "GET",
                url: "caritbbarang",
                dataType: "json",
                success: function(response) {
                    $('#modalcaritbbarang').html(response.body)
                    $("#modalcaritbbarang").modal('show');
                }
            })
        })
        $('#btn_supplier').on('click', function(e) {
            $.ajax({
                method: "GET",
                url: "carisupplier",
                dataType: "json",
                success: function(response) {
                    $('#modalcarisupplier').html(response.body)
                    $("#modalcarisupplier").modal('show');
                }
            })
        })

        $(document).ready(function() {
            $(document.getElementsByName('kdbarang')).hide();
            $(document.getElementsByName('nmbarang')).hide();
            $(document.getElementsByName('btn_barang')).hide();
            $(document.getElementsByName('kdsupplier')).hide();
            $(document.getElementsByName('nmsupplier')).hide();
            $(document.getElementsByName('btn_supplier')).hide();

            $('#kdbarang').on('blur', function(e) {
                let cari = $(this).val();
                $.ajax({
                    url: 'repltbbarang',
                    type: 'get',
                    data: {
                        kode_barang: cari
                    },
                    success: function(response) {
                        let data_response = JSON.parse(response);
                        if (!data_response) {
                            $('#kdbarang').val('');
                            $('#nmbarang').val('');
                            cari_data_barang();
                            return;
                        }
                        $('#kdbarang').val(data_response['kdbarang']);
                        $('#nmbarang').val(data_response['nmbarang']);
                    },
                    error: function() {
                        console.log('file not fount');
                    }
                })
                // console.log(cari);
            })

            $('#kdsupplier').on('blur', function(e) {
                let cari = $(this).val();
                $.ajax({
                    url: 'replsupplier',
                    type: 'get',
                    data: {
                        kode: cari
                    },
                    success: function(response) {
                        let data_response = JSON.parse(response);
                        if (!data_response) {
                            $('#kdsupplier').val('');
                            $('#nmsupplier').val('');
                            cari_data_supplier();
                            return;
                        }
                        $('#kdsupplier').val(data_response['kdsupplier']);
                        $('#nmsupplier').val(data_response['nmsupplier']);
                    },
                    error: function() {
                        console.log('file not fount');
                    }
                })
                // console.log(cari);
            })

        });
    </script>
@endsection

{{-- @stop --}}
