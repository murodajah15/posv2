<?php
$session = session();
// var_dump($vdata);
?>
{{-- @include('home.akses'); --}}
<?php
$pakai = session('pakai');
$tambah = session('tambah');
$edit = session('edit');
$hapus = session('hapus');
$proses = session('proses');
$unproses = session('unproses');
$cetak = session('cetak');
?>

<div class='col-md-12'>
    <!--<table style=font-size:13px; class="table table-striped table table-bordered">-->
    <table id="tbl_multiprc" class="table table-bordered table-hover">
        <thead>
            <tr>
                <th width='30'>No.</th>
                <th width='120'>Kode Barang</th>
                <th width='350'>Nama Barang</th>
                <th width='90'>harga</th>
                <th width='60'>Disc (%)</th>
                <th width='90'>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 0; ?>
            @foreach ($tbmultiprc as $row)
                <?php $no++; ?>
                <tr>
                    <td style="text-align:center;">{{ $no }}</td>
                    <td>{{ $row->kdbarang }}</td>
                    <td>{{ $row->nmbarang }}</td>
                    <td style="text-align:right;">
                        {{ number_format($row->harga, 0, ',', '.') }}</td>
                    <td style="text-align:right;">{{ $row->discount }}</td>
                    <td>
                        <input button type='Button' class='btn btn-primary btn-sm btnedit' value='Edit'
                            onClick='edit_detail({{ $row->id }})' {{ $tambah == 1 ? '' : 'disabled' }} />
                        <input button type='Button' class='btn btn-danger btn-sm btnhapus' value='Hapus'
                            onClick='hapus_detail({{ $row->id }})' {{ $tambah == 1 ? '' : 'disabled' }} />
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script>
    $("#tbl_multiprc").DataTable({
        aLengthMenu: [
            [5, 50, 100, -1],
            [5, 50, 100, "All"]
        ],
        autoWidth: false,
        iDisplayLength: 5,
        destroy: true,
    })

    // function alert_hapus_detail(id) {
    //     swal({
    //             title: "Yakin akan dihapus ?",
    //             text: "Once deleted, you will not be able to recover this data!",
    //             icon: "warning",
    //             buttons: true,
    //             dangerMode: true,
    //         })
    //         .then((willDelete) => {
    //             $.ajax({
    //                 url: `{{ url('tbmultiprc') }}/${id}`,
    //                 type: "POST",
    //                 data: {
    //                     _method: "DELETE",
    //                     _token: '{{ csrf_token() }}',
    //                 },
    //                 dataType: "json",
    //                 success: function(response) {
    //                     if (response.sukses == false) {
    //                         toastr.error('Data berhasil dihapus silahkan melanjutkan')
    //                     } else {
    //                         reload_tbl_multiprcajax()
    //                         toastr.info('Data berhasil dihapus, silahkan melanjutkan')
    //                     }
    //                 },
    //                 error: function(xhr, ajaxOptions, thrownError) {
    //                     alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
    //                 }

    //             })
    //         });
    // }
</script>
