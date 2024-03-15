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
                            <form method='post' target='_blank' action='rjual_xls' class="rjual_xls">
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
                                            value='rincian'>
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
                                            value='semuasales' checked> Semua Sales
                                        <div class="input-group mb-2">
                                            <input type="text" name="kdsales" id="kdsales"
                                                class="form-control form-control-sm" style="width: 30%"
                                                placeholder="Kode Sales">
                                            <input type="text" name="nmsales" id="nmsales"
                                                class="form-control form-control-sm" style="width: 60%" readonly>
                                            <button class="btn btn-outline-secondary btn-sm" type="button"
                                                name='btn_sales' id='btn_sales' style="width: 7%" id="carisales"><i
                                                    class="fa fa-search"></i></button>
                                        </div>

                                    </div>
                                </div>
                                <div class='row'>
                                    <div class='col-md-6'>
                                        <input type='checkbox' class='form-checkbox' name='semuaklpcust'
                                            id='checkall_klpcust' value='semuaklpcust' checked> Semua Kelompok Customer
                                        <div class="input-group mb-2">
                                            <input type="text" name="kdklpcust" id="kdklpcust"
                                                class="form-control form-control-sm" style="width: 30%"
                                                placeholder="Kode klpcust">
                                            <input type="text" name="nmklpcust" id="nmklpcust"
                                                class="form-control form-control-sm" style="width: 60%" readonly>
                                            <button class="btn btn-outline-secondary btn-sm" type="button"
                                                name='btn_klpcust' id='btn_klpcust' style="width: 7%" id="cariklpcust">
                                                <i class="fa fa-search"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <div class='row'>
                                    <div class='col-md-12'>
                                        <input type='checkbox' class='form-checkbox' name='groupingcustomer'
                                            id='groupingcustomer' value='groupingcustomer'>
                                        Grouping Customer
                                    </div>
                                </div>
                                <div class="row">
                                    <div class='col-md-12'>
                                        <label class="radio-inline">
                                            <input type="radio" name="pilihanppn" id="ppnnonppn" value="ppnnonppn"
                                                checked> PPN dan Non PPN&nbsp;&nbsp;&nbsp;
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="pilihanppn" id="ppn" value="ppn">
                                            PPN&nbsp;&nbsp;&nbsp;
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="pilihanppn" id="nonppn" value="nonppn"> Non
                                            PPN
                                        </label>
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
    <div class="modal fade" id="modalcariklpcust" data-backdrop="static" data-keyboard="false" tabindex="-1"
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
        $('#btn_klpcust').on('click', function(e) {
            $.ajax({
                method: "GET",
                url: "cariklpcust",
                dataType: "json",
                success: function(response) {
                    $('#modalcariklpcust').html(response.body)
                    $("#modalcariklpcust").modal('show');
                }
            })
        })
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
            $(document.getElementsByName('btn_klpcust')).hide();

            $(document.getElementsByName('kdsales')).hide();
            $(document.getElementsByName('nmsales')).hide();
            $(document.getElementsByName('btn_sales')).hide();
            $(document.getElementsByName('kdcustomer')).hide();
            $(document.getElementsByName('nmcustomer')).hide();
            $(document.getElementsByName('btn_customer')).hide();

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
                            cari_data_barang_master();
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

            $('#kdklpcust').on('blur', function(e) {
                let cari = $(this).val();
                $.ajax({
                    url: 'replklpcust',
                    type: 'get',
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
                        $('#kdklpcust').val(data_response['kdklpcust']);
                        $('#nmklpcust').val(data_response['nmklpcust']);
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
