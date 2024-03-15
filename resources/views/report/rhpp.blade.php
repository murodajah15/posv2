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
                            <form method='post' target='_blank' action='rhpp_xls' class="rhpp_xls">
                                @csrf
                                <div class='row'>
                                    <div class='col-md-12'>
                                        <b>
                                            <font color="blue">Data yang ditampilkan adalah data yang sudah CLOSING
                                                BULANAN</font><br><br>
                                        </b>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        Bulan Periode
                                        <select name="bulan" id="bulan" class='form-control form-control-sm '>
                                            <!--<option selected="selected" ></option>-->
                                            <?php
                                            $bulan = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                                            $jlh_bln = count($bulan);
                                            $month = date('m');
                                            for ($c = 0; $c < $jlh_bln; $c++) {
                                                if ($c == $month) {
                                                    echo "<option value='$c' selected>$bulan[$c] </option>";
                                                } else {
                                                    echo "<option value='$c'> $bulan[$c] </option>";
                                                }
                                            }
                                            ?>
                                        </select>
                                        </td>
                                    </div>
                                    <div class="col-md-2">
                                        Tahun Periode
                                        <?php
                                        $now = date('Y');
                                        echo "<select name='tahun' id='tahun' class='form-control form-control-sm'>";
                                        for ($a = $now - 3; $a <= $now; $a++) {
                                            if ($a == $now) {
                                                echo "<option value='$a' selected>$a </option>";
                                            } else {
                                                echo "<option value='$a'>$a</option>";
                                            }
                                        }
                                        echo '</select>';
                                        ?>
                                    </div>
                                </div>
                                <br>
                                <div class='row'>
                                    <div class='col-md-6'>
                                        <input type='checkbox' class='form-checkbox' name='semuabarang' id='checkall_barang'
                                            value='semuabarang' checked> Semua Barang
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
                                <div class="row">
                                    <div class="col-md-12 bg">
                                        <br><button type='submit' class='btn btn-primary btn-sm'>Cetak</button>
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

        $(document).ready(function() {
            $(document.getElementsByName('kdbarang')).hide();
            $(document.getElementsByName('nmbarang')).hide();
            $(document.getElementsByName('btn_barang')).hide();

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

        });
    </script>
@endsection

{{-- @stop --}}
