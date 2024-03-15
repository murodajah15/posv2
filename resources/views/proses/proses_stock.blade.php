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
    
    $tgl_proses = date('Y-m-d');
    $tgl_berikutnya = strtotime('+1 day');
    $tgl_berikutnya = date('Y-m-d', $tgl_berikutnya);
    $no = 1;
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
                            <li class="breadcrumb-item active">
                                {{ $title }}
                            </li>
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
                            <form method='post'>
                                @csrf
                                <input type="hidden" class="form-control form-control-sm" name="username" id="username"
                                    value="{{ $username }}">
                                <div class="row">
                                    <div class='col-md-6'>
                                        <table class="table table-bordered table-striped table-hover">
                                            <tr>
                                                <td>No. Dokumen Stock Opname</td>
                                                <td>
                                                    <select class='form-control form-control-sm' name='noopname'
                                                        id='noopname'>
                                                        @foreach ($opnameh as $k)
                                                            <option value="{{ $k->noopname }}">{{ $k->noopname }} |
                                                                {{ $k->tglopname }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                            </tr>
                                            <br>
                                        </table>
                                    </div>
                                </div>
                                <input type="text" name="sedangproses" id="kdbarang" class="form-control-plaintext"
                                    value="Sedang Proses Ulang Stock ..." aria-label="Disabled input example" disabled
                                    readonly>
                                <input button type='Button' class='btn btn-primary btn-sm' value='Proses'
                                    onClick='alert_proses()' /><br>
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

    <script>
        $(document.getElementsByName('sedangproses')).hide();

        function alert_proses() {
            swal({
                    title: "Yakin akan di Proses ?",
                    text: "",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willCetak) => {
                    if (willCetak) {
                        $(document.getElementsByName('sedangproses')).show();
                        //$href = "module/closing/proses_closing_harian.php?id="+document.getElementById("tglberikutnya").value;
                        var cek1 = document.getElementById('noopname').value;
                        var cek = cek1;
                        $href = "proses_stock_proses?noopname=" + cek;
                        //$href = "module/closing/proses_closing_harian.php?id="+"2019-09-18&username=$asal";
                        window.location.href = $href;
                        //window.open($href+$id,"_blank");
                        //window.location.href = $href+$id;
                        // swal("Poof! Your imaginary file has been deleted!", {
                        //   icon: "success",
                        // });
                    } else {
                        //swal("Batal Hapus!");
                    }
                });
        };
    </script>
@endsection

{{-- @stop --}}
