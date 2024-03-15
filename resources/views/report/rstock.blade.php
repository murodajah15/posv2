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
                            <form method='post' target='_blank' action='rstock_xls' class="rstock_xls">
                                @csrf
                                <div class="row">
                                    <div class='col-md-12'>
                                        DOKUMEN STOCK OPNAME
                                    </div>
                                    <div class='col-md-6'>
                                        <select name='noopname' class='form-control form-control-sm' style="width: 250px;">
                                            @foreach ($opnameh as $row)
                                                <option name="noopname" value={{ $row->noopname }}> {{ $row->noopname }} |
                                                    {{ $row->tglopname }}</option>
                                            @endforeach
                                        </select>

                                    </div>
                                </div><br>
                                <div class='row'>
                                    <div class='col-md-6'>
                                        <input type='checkbox' class='form-checkbox' name='semuaperiode'
                                            id='checkall_periode' value='semuaperiode' checked> Semua Periode (M-D-Y) / Sd
                                        <?php
                                        foreach ($opnameh as $k) {
                                            $tglopname = $k['tglopname'];
                                        }
                                        ?>
                                        <input type="hidden" class='form-group' id='tanggal1' name='tanggal1'
                                            value="<?= date('Y-m-d', strtotime('+1 days', strtotime($tglopname))) ?>">
                                        <input type="date" class='form-group' id='tanggal2' name='tanggal2'
                                            value="<?= $tanggal ?>">
                                    </div>
                                </div>

                                <div class='row'>
                                    <div class='col-md-12'>
                                        <input type='checkbox' class='form-checkbox' name='rekapitulasi' id='rekapitulasi'
                                            value='rekapitulasi'>
                                        Rekapitulasi
                                    </div>
                                </div>
                                <div id='brgkosong'>
                                    <div class='row'>
                                        <div class='col-md-12'>
                                            <input type='checkbox' class='form-checkbox' name='barangkosong'
                                                id='barangkosong' value='barangkosong'>
                                            Stock Barang <= 0 </div>
                                        </div>
                                    </div>
                                    <div class='row'>
                                        <div class='col-md-6'>
                                            <input type='checkbox' class='form-checkbox' name='semuabarang'
                                                id='checkall_barang' value='semuabarang' checked> Semua Barang
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

        $('#rekapitulasi').on('click', function(e) {
            var checkBoxrekap = document.getElementById("rekapitulasi");
            if (checkBoxrekap.checked == true) {
                $('#brgkosong').show();
            } else {
                $('#brgkosong').hide();
            }
        })

        $(document).ready(function() {
            $('#brgkosong').hide();
            $(document.getElementsByName('tanggal2')).hide();
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
