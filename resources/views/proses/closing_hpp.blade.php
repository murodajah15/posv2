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
                            <h4 class="m-0 text-dark">{{ $title }}&nbsp;&nbsp;</h4><b
                                style="color:blue;">{{ 'Closing terakhir : ' . $saplikasi->closing_hpp }}</b>
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
                                    <div class='col-md-12'>
                                        <marquee><input type="text" name="sedangproses" id="sedangproses"
                                                style="color:red;" class="form-control-plaintext"
                                                value="Sedang Proses Closing ..." aria-label="Disabled input example"
                                                disabled readonly></marquee>
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
                                <div class="row">
                                    <div class='col-md-4'>
                                        <table class="table table-bordered table-striped table-hover">
                                            <tr>
                                                <td>Bulan Periode </td>
                                                <td><select name="bulan" id="bulan"
                                                        class='form-control form-control-sm'>
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
                                            </tr>
                                            <tr>
                                                <td>Tahun Periode </td>
                                                <td>
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
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Bulan Periode Berikutnya </td>
                                                <td><select name="bulan1" id="bulan1"
                                                        class='form-control form-control-sm'>
                                                        <!--<option selected="selected" ></option>-->
                                                        <?php
                                                        $bulan1 = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                                                        $jlh_bln = count($bulan1);
                                                        $month = date('m') + 1;
                                                        if ($month > 12) {
                                                            $month = 1;
                                                        }
                                                        for ($c = 0; $c < $jlh_bln; $c++) {
                                                            if ($c == $month) {
                                                                echo "<option value='$c' selected>$bulan1[$c] </option>";
                                                            } else {
                                                                echo "<option value='$c'> $bulan1[$c] </option>";
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Tahun Periode Berikutnya</td>
                                                <td>
                                                    <?php
                                                    $month = date('m') + 1;
                                                    $now = date('Y');
                                                    if ($month > 11) {
                                                        $now = date('Y') + 1;
                                                    }
                                                    echo "<select name='tahun1' id='tahun1' class='form-control form-control-sm'>";
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
                                        </table>
                                        <input button type='Button' class='btn btn-primary btn-sm' value='Proses'
                                            onClick='alert_proses()' /><br>
                                        <br>
                                    </div>
                                    <div class='col-md-8'>
                                        <table id="user_last_login" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th width='30'>No.</th>
                                                    <th>User Login</th>
                                                    <th>Last Login</th>
                                                </tr>
                                            </thead>
                                            <?php $no = 1; ?>
                                            @foreach ($user as $k)
                                                <tr>
                                                    <td>{{ $no++ }}</td>
                                                    <td>{{ $k->username }}</td>
                                                    <td>{{ $k->last_login }}</td>
                                                </tr>
                                            @endforeach
                                        </table>
                                        <br>
                                        <table id="user_closing" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th width='30'>No.</th>
                                                    <th width='90'>Periode</th>
                                                    <th>Tgl. Closing</th>
                                                    <th>User</th>
                                                    <th width='70'>Aksi</th>
                                                </tr>
                                            </thead>

                                            <?php $no = 1;
                                                foreach ($close_hpp as $k){
                                                    $id = $k->periode;
                                                    ?>
                                            <tr>
                                                <td align='center'>{{ $no }}</td>
                                                <td>{{ $k->periode }}</td>
                                                <td>{{ $k->tgl_closing }}</td>
                                                <td>{{ $k->user_closing }}</td>
                                                <?php 
                                                    if ($k->status == 'Y'){
                                                        ?>
                                                <td><button type='button' class='btn btn-danger btn-sm'
                                                        onClick="alert_unclosing({{ $id }})">
                                                        Closed</span>
                                                    </button></td>
                                                <?php
                                                    }else{
                                                        echo "<td>Batal Closing</span></td>";
                                                    }
                                                    ?>
                                            </tr>
                                            <?php 
                                                    $no++;
                                                }
                                                ?>
                                        </table>
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

    <script>
        $(document).ready(function() {
            $(document.getElementsByName('sedangproses')).hide();
            $('#user_last_login').DataTable({
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
            $('#user_closing').DataTable({
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
                    title: "Yakin akan Proses Closing Bulanan ?",
                    text: "",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willCetak) => {
                    if (willCetak) {
                        $(document.getElementsByName('sedangproses')).show();
                        //$href = "module/closing/proses_closing_harian.php?id="+document.getElementById("tglberikutnya").value;
                        var cek1 = document.getElementById('bulan').value;
                        var cek2 = document.getElementById('tahun').value;
                        var nbulan1 = document.getElementById('bulan1').value;
                        var ntahun1 = document.getElementById('tahun1').value;
                        var cek3 = document.getElementById('username').value;
                        var cnoopname = document.getElementById('noopname').value;
                        var bulan = "&bulan="
                        var tahun = "&tahun="
                        var bulan1 = "&bulan1="
                        var tahun1 = "&tahun1="
                        var username = "&username="
                        var noopname = "&noopname="
                        var cek = cek1 + tahun + cek2 + username + cek3 + bulan1 + nbulan1 + tahun1 + ntahun1 +
                            noopname + cnoopname;

                        let random = '';
                        const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
                        const charactersLength = characters.length;
                        let counter = 0;
                        while (counter < length) {
                            random += characters.charAt(Math.floor(Math.random() * charactersLength));
                            counter += 1;
                        }
                        var href = "closing_hpp_proses?id=" + cek + '&ref=' + random;
                        window.location.href = href;
                        // window.open($href, "_blank");
                    } else {
                        //swal("Batal Hapus!");
                    }
                });
        };

        function alert_unclosing($id) {
            swal({
                    title: "Yakin akan di Unclosing Bulanan ?",
                    text: "",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willUnclosing) => {
                    if (willUnclosing) {
                        var cek1 = document.getElementById('bulan').value;
                        var cek2 = document.getElementById('tahun').value;
                        var bulan = "&bulan="
                        var tahun = "&tahun="
                        let random = '';
                        const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
                        const charactersLength = characters.length;
                        let counter = 0;
                        while (counter < length) {
                            random += characters.charAt(Math.floor(Math.random() * charactersLength));
                            counter += 1;
                        }
                        $href = "closing_hpp_unproses?id=" + $id + bulan + cek1 + tahun + cek2 +
                            random;
                        window.location.href = $href
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
