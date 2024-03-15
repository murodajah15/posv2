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
                            <form method='post' target='_blank' action='rso_xls' class="rso_xls">
                                @csrf
                                <div class="row">
                                    <div class="col-md-12 bg">
                                        <input type='checkbox' class='form-checkbox' name='outstanding' id='outstanding'
                                            value='outstanding'>
                                        Outstanding
                                    </div>
                                    <div class="col-md-12 bg">
                                        <input type='checkbox' class='form-checkbox' name='semuaperiode'
                                            id='checkall_periode' value='semuaperiode'> Semua Periode (M-D-Y)
                                        <input type="date" class='form-group' name='tanggal1' value="<?= $tanggal ?>">
                                        <input type="date" class='form-group' name='tanggal2' value="<?= $tanggal ?>">
                                        <!--</div>-->
                                    </div>
                                </div>
                                <div class='row'>
                                    <div class='col-md-6'>
                                        <input type='checkbox' class='form-checkbox' name='semuacustomer'
                                            id='checkall_customer' value='semuacutomer' checked> Semua Customer
                                        <div class="input-group mb-2">
                                            <input type="text" name="kdcustomer" id="kdcustomer"
                                                class="form-control form-control-sm" style="width: 30%"
                                                placeholder="Kode Customer">
                                            <input type="text" name="nmcustomer" id="nmcustomer"
                                                class="form-control form-control-sm" style="width: 60%" readonly>
                                            <button class="btn btn-outline-secondary btn-sm" type="button"
                                                name='btn_customer' id='btn_customer' style="width: 7%" id="caricustomer">
                                                <i class="fa fa-search"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <div class='row'>
                                    <div class='col-md-6'>
                                        <input type='checkbox' class='form-checkbox' name='semuasales' id='checkall_sales'
                                            value='semuasales' checked> Semua Sales</br>
                                        <div class="input-group mb-2">
                                            <input type="text" name="kdsales" id="kdsales"
                                                class="form-control form-control-sm" style="width: 30%"
                                                placeholder="Kode Sales">
                                            <input type="text" name="nmsales" id="nmsales"
                                                class="form-control form-control-sm" style="width: 60%" readonly>
                                            <button class="btn btn-outline-secondary btn-sm" type="button" name='btn_sales'
                                                id='btn_sales' style="width: 7%" id="carisales"><i
                                                    class="fa fa-search"></i></button>
                                        </div>

                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 bg">
                                        <br><button type='submit' class='btn btn-primary btn-sm'>Cetak</button>
                                    </div>
                                </div>
                    </div>
                    </form>
                    </font>
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

    <div class="modal fade" id="modalcarisales" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
    </div>
    <div class="modal fade" id="modalcaricustomer" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
    </div>

    <script>
        $('#btn_sales').on('click', function(e) {
            $.ajax({
                method: "GET",
                url: "carisales",
                dataType: "json",
                success: function(response) {
                    $('#modalcarisales').html(response.body)
                    $("#modalcarisales").modal('show');
                }
            })
        })
        $('#btn_customer').on('click', function(e) {
            $.ajax({
                method: "GET",
                url: "caricustomer",
                dataType: "json",
                success: function(response) {
                    $('#modalcaricustomer').html(response.body)
                    $("#modalcaricustomer").modal('show');
                }
            })
        })

        $(document).ready(function() {
            $(document.getElementsByName('kdbarang')).hide();
            $(document.getElementsByName('nmbarang')).hide();
            $(document.getElementsByName('btn_barang')).hide();
            $(document.getElementsByName('kdklpcust')).hide();
            $(document.getElementsByName('nmklpcust')).hide();
            $(document.getElementsByName('src')).hide();

            $(document.getElementsByName('kdsales')).hide();
            $(document.getElementsByName('nmsales')).hide();
            $(document.getElementsByName('btn_sales')).hide();
            $(document.getElementsByName('kdcustomer')).hide();
            $(document.getElementsByName('nmcustomer')).hide();
            $(document.getElementsByName('btn_customer')).hide();

            $('#kdcustomer').on('blur', function(e) {
                let cari = $(this).val();
                $.ajax({
                    url: 'replcustomer',
                    type: 'get',
                    data: {
                        kode: cari
                    },
                    success: function(response) {
                        let data_response = JSON.parse(response);
                        if (!data_response) {
                            $('#kdcustomer').val('');
                            $('#nmcustomer').val('');
                            cari_data_customer();
                            return;
                        }
                        $('#kdcustomer').val(data_response['kdcustomer']);
                        $('#nmcustomer').val(data_response['nmcustomer']);
                    },
                    error: function() {
                        console.log('file not fount');
                    }
                })
                // console.log(cari);
            })

            $('#kdsales').on('blur', function(e) {
                let cari = $(this).val();
                $.ajax({
                    url: 'replsales',
                    type: 'get',
                    data: {
                        kode: cari
                    },
                    success: function(response) {
                        let data_response = JSON.parse(response);
                        if (!data_response) {
                            $('#kdsales').val('');
                            $('#nmsales').val('');
                            cari_data_sales();
                            return;
                        }
                        $('#kdsales').val(data_response['kdsales']);
                        $('#nmsales').val(data_response['nmsales']);
                    },
                    error: function() {
                        console.log('file not fount');
                    }
                })
                // console.log(cari);
            })

            $('#kdbarang').on('blur', function(e) {
                let cari = $(this).val();
                $.ajax({
                    url: 'repl_tbbarang',
                    type: 'get',
                    data: {
                        kode_barang: cari
                    },
                    success: function(response) {
                        let data_response = JSON.parse(response);
                        if (!data_response) {
                            $('#kdbarang').val('');
                            $('#nmbarang').val('');
                            cari_data_barang_master();
                            return;
                        }
                        $('#kdbarang').val(data_response['kode']);
                        $('#nmbarang').val(data_response['nama']);
                    },
                    error: function() {
                        console.log('file not fount');
                    }
                })
                // console.log(cari);
            })

            $('#kdklpcust').on('blur', function(e) {
                let cari = $(this).val();
                $.ajax({
                    url: 'repl_tbklpcust',
                    type: 'post',
                    data: {
                        kode: cari
                    },
                    success: function(response) {
                        let data_response = JSON.parse(response);
                        if (!data_response) {
                            $('#kdklpcust').val('');
                            $('#nmklpcust').val('');
                            cari_data_klpcust();
                            return;
                        }
                        $('#kdklpcust').val(data_response['kode']);
                        $('#nmklpcust').val(data_response['nama']);
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
