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
                            {{-- &nbsp;<button style="display:inline" class="btn btn-outline-info btn-sm mb-2 btnreload"
                                onclick="reload_table()" type="button"><i class="fa fa-spinner"></i></button> --}}
                            {{-- &nbsp;<span><button tipe="button" class="btn btn-primary btn-sm tomboltambah"
                                    {{ $pakai != 1 ? 'disabled' : '' }}> <i class="fa fa-circle-plus"></i>
                                    Tambah</button></span> --}}
                        </div>
                        @if (session('message'))
                            <div class="text-success">{{ session('message') }}</div>
                        @endif
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/home">Home</a></li>
                            <li class="breadcrumb-item active">{{ $title }}</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
                <font face="calibri">
                    <hr size="10px">
                    <form method='post'>
                        <h4><input type='checkbox' id="checkall_resetfile" /> File</h4>
                        <div class="row">
                            <div class="col-md-4 bg">
                                <input type='checkbox' name='tbbarang_r' id='tbbarang_r'> Tabel Barang<br>
                                <input type='checkbox' name='tbgudang_r' id='tbgudang_r'> Tabel Gudang<br>
                                <input type='checkbox' name='tbjntrans_r' id='tbjntrans_r'> Tabel Jenis Transaksi<br>
                                <input type='checkbox' name='tbjnbrg_r' id='tbjnbrg_r'> Tabel Jenis Barang<br>
                                <input type='checkbox' name='tbsatuan_r' id='tbsatuan_r'> Tabel Satuan<br>
                            </div>
                            <div class="col-md-4 bg">
                                <input type='checkbox' name='tbnegara_r' id='tbnegara_r'> Tabel Negara<br>
                                <input type='checkbox' name='tbmove_r' id='tbmove_r'> Tabel Perputaran Barang<br>
                                <input type='checkbox' name='tbdiscount_r' id='tbdiscount_r'> Tabel Discount<br>
                                <input type='checkbox' name='tbcustomer_r' id='tbcustomer_r'> Tabel Customer<br>
                                <input type='checkbox' name='tbsupplier_r' id='tbsupplier_r'> Tabel Supplier<br>
                            </div>
                            <div class="col-md-4 bg">
                                <input type='checkbox' name='tbmultiprc_r' id='tbmultiprc_r'> Tabel Multi Price<br>
                                <input type='checkbox' name='tbsales_r' id='tbsales_r'> Tabel Sales<br>
                                <input type='checkbox' name='tbbank_r' id='tbbank_r'> Tabel Bank<br>
                                <input type='checkbox' name='tbjnkeluar_r' id='tbjnkeluar_r'> Tabel Jenis
                                Pengeluaran<br><br><br>
                            </div>
                        </div>
                        <h4><input type='checkbox' id="checkall_resettransaksi" /> Transaksi</h4>
                        <div class="row">
                            <div class="col-md-4 bg">
                                <input type='checkbox' name='so' id='so' value='1'> Sales Order<br>
                                <input type='checkbox' name='jual' id='jual'> Pejualan<br>
                                <input type='checkbox' name='po' id='po'> Purchase Order (PO)<br>
                                <input type='checkbox' name='beli' id='beli'> Penerimaan Pembelian<br>
                            </div>
                            <div class="col-md-4 bg">
                                <input type='checkbox' name='terima' id='terima'> Penerimaan Barang<br>
                                <input type='checkbox' name='keluar' id='keluar'> Pengeluaran Barang<br>
                                <input type='checkbox' name='opname' id='opname'> Stock Opname<br>
                                <input type="checkbox" name='approv' id='approv'> Approval
                                Batas
                                Piutang<br>
                            </div>
                            <div class="col-md-4 bg">
                                <input type='checkbox' name='kasir_tunai' id='kasir_tunai'> Kasir Penerimaan
                                Tunai<br>
                                <input type='checkbox' name='kasir_tagihan' id='kasir_tagihan'> Kasir Penerimaan
                                Tagihan<br>
                                <input type='checkbox' name='moh_keluar' id='moh_keluar'> Permohonan Keluar
                                Uang<br>
                                <input type='checkbox' name='keluar_uang' id='keluar_uang'> Kasir Pengeluaran
                                Uang<br><br><br>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 bg">
                                <!-- <p align="center"> -->
                                <button type='button' class='btn btn-danger' onClick='proses()'>Proses
                                    Reset</button>&nbsp;
                                {{-- <button type='button' class='btn btn-warning' onClick='proses_alter()'>Proses Alter
                                    Default
                                    Value</button> --}}
                                <!-- </p> -->
                            </div>
                        </div>
                    </form>
                </font>
            </div>
            <!-- /.content-header -->
        </div>
    </div>

    <script>
        function proses() {
            swal({
                    title: "Yakin akan proses Reset ?",
                    text: "", //"Once deleted, you will not be able to recover this data!",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    const form = $(this)
                    if (willDelete) {
                        // $approv = document.getElementById("approv").value;
                        // alert($approv);
                        $.ajax({
                            url: `{{ url('reset_proses') }}`,
                            type: "GET",
                            data: form.serialize(),
                            // data: {
                            //     approv: $approv,
                            //     _token: '{{ csrf_token() }}',
                            // },
                            dataType: "json",
                            success: function(response) {
                                if (response.sukses == false) {
                                    // swal({
                                    //     title: "Data gagal dihapus!",
                                    //     text: "",
                                    //     icon: "error"
                                    // })
                                    toastr.danger('Data gagal di reset, silahkan melanjutkan')
                                } else {
                                    // swal({
                                    //     title: "Data berhasil dihapus! ",
                                    //     text: "",
                                    //     icon: "success"
                                    // })
                                    toastr.info('Data berhasil di reset, silahkan melanjutkan')
                                    // .then(function() {
                                    //     window.location.href = '/tbjnkeluar';
                                    // });
                                }
                            },
                            error: function(xhr, ajaxOptions, thrownError) {
                                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                            }

                        })
                    }
                })
        }

        function proses_alter() {
            swal({
                    title: "Yakin akan proses Alter ?",
                    text: "", //"Once deleted, you will not be able to recover this data!",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            url: `{{ url('alert_proses') }}`,
                            type: "POST",
                            data: {
                                _method: "DELETE",
                                _token: '{{ csrf_token() }}',
                            },
                            dataType: "json",
                            success: function(response) {
                                if (response.sukses == false) {
                                    // swal({
                                    //     title: "Data gagal dihapus!",
                                    //     text: "",
                                    //     icon: "error"
                                    // })
                                    toastr.danger('Data gagal di alter, silahkan melanjutkan')
                                } else {
                                    // swal({
                                    //     title: "Data berhasil dihapus! ",
                                    //     text: "",
                                    //     icon: "success"
                                    // })
                                    reload_table();
                                    toastr.info('Data berhasil di alter, silahkan melanjutkan')
                                    // .then(function() {
                                    //     window.location.href = '/tbjnkeluar';
                                    // });
                                }
                            },
                            error: function(xhr, ajaxOptions, thrownError) {
                                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                            }

                        })
                    }
                })
        }
    </script>
@endsection

{{-- @stop --}}
