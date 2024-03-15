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
                            <form method='post' target='_blank' action='rkasir_tunai_xls' class="rkasir_tunai_xls">
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
                                <div class="row">
                                    <div class="col-md-2">
                                        KASIR
                                        <select name='nmkasir' class='form-control' style="width: 200px;">
                                            <option width="30" value=''> - PILIH KASIR - </option>";
                                            @foreach ($user as $row)
                                                <option name="nmkasir" value={{ $row->username }}>{{ $row->username }} |
                                                    {{ $row->nama_lengkap }} </option>
                                            @endforeach
                                        </select>
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
