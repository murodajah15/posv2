@extends('/home/index')

@include('home.config')

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
    
    $connect = session('connect');
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
                            <form method='post'>
                                @csrf
                                <input type='hidden' id='username' name='username' value={{ $username }}>
                                <div class="row">
                                    <div class='col-md-4'>
                                        <table class="table table-bordered table-striped table-hover">
                                            <tr>
                                                <td>Tanggal Closing (M/D/Y)</td>
                                                <td><input type='date' class='form-control' id='tgl_proses'
                                                        name='tgl_proses' value="<?= $tgl_proses ?>" size='50'
                                                        autocomplete='off' readonly></td>
                                            </tr>
                                            <tr>
                                                <td>Tanggal Data Berikutnya (M/D/Y)</td>
                                                <td><input type='date' class='form-control' id='tgl_berikutnya'
                                                        name='tgl_berikutnya' value="<?= $tgl_berikutnya ?>" size='50'
                                                        autocomplete='off'></td>
                                            </tr>
                                        </table>

                                        <tr>
                                            <td><input type='checkbox' class='form-check' name='resetnomor' id='resetnomor'>
                                                <label id='lblreset'>
                                                    <font color='red'> Reset nomor transaksi untuk awal bulan
                                                        berikutnya<br>
                                                    </font>
                                                </label>
                                            </td>
                                        </tr>
                                        <div id="isireset">
                                            <tr>
                                                <td>Bulan </td>
                                                <td><select name="bulan" id="bulan" class='form-control'>
                                                        <!--<option selected="selected" ></option>-->
                                                        <?php
                                                        $bulan = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                                                        $jlh_bln = count($bulan);
                                                        $month = date('m');
                                                        for ($c = 0; $c < $jlh_bln; $c++) {
                                                            if ($c - 1 == $month) {
                                                                echo "<option value='$c' selected>$bulan[$c] </option>";
                                                            } else {
                                                                echo "<option value='$c'> $bulan[$c] </option>";
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Tahun</td>
                                                <td>
                                                    <?php
                                                    $now = date('Y');
                                                    echo "<select name='tahun' id='tahun' class='form-control'>";
                                                    for ($a = $now - 3; $a <= $now; $a++) {
                                                        if ($a == $now) {
                                                            echo "<option value='$a' selected>$a </option>";
                                                        } else {
                                                            echo "<option value='$a'>$a</option>";
                                                        }
                                                    }
                                                    echo '</select>';
                                                    ?>
                                                </td>
                                            </tr>
                                        </div>
                                        <br><br>
                                        <input button type='Button' class='btn btn-primary btn-sm' value='Proses'
                                            onClick='alert_proses()' /><br>
                                    </div>
                                    <div class='col-md-8'>
                                        <table id="tbl-closing" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th width='30'>No.</th>
                                                    <th width="70">Tgl. Closing</th>
                                                    <th width="90">Tgl. Berikutnya</th>
                                                    <th width="150">User</th>
                                                    <th>Created</th>
                                                </tr>
                                            </thead>
                                            @foreach ($closing_harian as $k)
                                                <tr>
                                                    <td align='center'>{{ $no }}</td>
                                                    <td>{{ $k->tglclosing }}</td>
                                                    <td>{{ $k->tglberikut }}</td>
                                                    <td>{{ $k->user }}</td>
                                                    <td>{{ $k->created_at }}</td>
                                                    <?php $no++; ?>
                                            @endforeach
                                        </table>
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

    <script>
        $(document).ready(function() {
            $('#tbl-closing').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                "aLengthMenu": [
                    [5, 50, 100, -1],
                    [5, 50, 100, "All"]
                ],
                "iDisplayLength": 5
            });
            $(document.getElementById('isireset')).hide();
            $('#resetnomor').on('click', function(event) {
                if (this.checked) { // if changed state is "CHECKED"
                    $(document.getElementById('isireset')).show();
                } else {
                    $(document.getElementById('isireset')).hide();
                }
            })
        });

        function alert_proses() {
            swal({
                    title: "Yakin akan di Proses Closing Harian ?",
                    text: "",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willCetak) => {
                    if (willCetak) {
                        //$href = "module/closing/proses_closing_harian.php?id="+document.getElementById("tglberikutnya").value;
                        var cek1 = document.getElementById('tgl_berikutnya').value
                        var cek2 = document.getElementById('username').value
                        var cek3 = "&username="
                        var nbulan = document.getElementById('bulan').value;
                        var ntahun = document.getElementById('tahun').value;
                        var nresetnomor = $("#resetnomor:checked")
                            .val(); //document.getElementById('resetnomor').value; 
                        var bulan = "&bulan="
                        var tahun = "&tahun="
                        var resetnomor = "&resetnomor="
                        var cek = cek1 + cek3 + cek2 + bulan + nbulan + tahun + ntahun + resetnomor + nresetnomor;
                        $href = "closing_harian_proses?id=" + cek;
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
