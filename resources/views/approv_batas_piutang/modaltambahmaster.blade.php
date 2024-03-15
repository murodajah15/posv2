<?php
$session = session();
// var_dump($vdata);
// $tambahtbnegara = $tambahtbnegara->tambah;
// $tambahtbjnbrg = $tambahtbjnbrg->tambah;
// $tambahtbsatuan = $tambahtbsatuan->tambah;
// $tambahtbmove = $tambahtbmove->tambah;
// $tambahtbdisc = $tambahtbdisc->tambah;
$edit = '';
$hapus = '';
$gnppn = session('ppn');
?>
<!-- Modal -->
<div class="modal-dialog" style="max-width: 55%;">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="staticBackdropLabel">
                {{ $vdata['title'] }}
                {{-- @if (isset($vdata))
                    {{ $vdata['title'] }}
                @endif --}}
                {{-- <h6 class="text-capitalize">{{ Riskihajar\Terbilang\Facades\Terbilang::make(1000, ' Rupiah') }}</h2> --}}
                {{-- <h6 class="text-capitalize">{{ Terbilang::make(1000, ' Rupiah') }}</h2> --}}
            </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form action="{{ $action }}" method="post" class="formapprov_batas_piutang">
            @csrf
            @if ($approv_batas_piutang->id)
                @method('get')
            @endif
            <?php $tgl = $saplikasi->tgl_berikutnya; ?>
            <input type="hidden" class="form-control-sm" name="id" id="id"
                value="{{ $approv_batas_piutang->id }}">
            <input type="hidden" class="form-control-sm" name="username" id="username"
                value="{{ $session->get('username') }}">
            <input type="hidden" class="form-control-sm" name="noapprovlama" id="noapprovlama"
                value="{{ $approv_batas_piutang->noapprov }}">
            <div class="modal-body">
                <div class="row">
                    <div class='col-md-12'>
                        <table style=font-size:12px; class='table table-borderless table-sm table-hover'>
                            <tr>
                                <td>Nomor Approval</td>
                                <td>
                                    <input type='text' class='form-control form-control-sm' id='noapprov'
                                        name='noapprov' placeholder='Nomor *' style='text-transform:uppercase'
                                        {{-- value={{ 'approv_batas_piutang' . $saplikasi->tahun . $saplikasi->bulan . sprintf('%05s', $saplikasi->approv) }} --}}
                                        value="{{ str_contains($vdata['title'], 'Tambah') ? 'AP' . $saplikasi->tahun . sprintf('%02s', $saplikasi->bulan) . sprintf('%05s', $saplikasi->noapprov + 1) : $approv_batas_piutang->noapprov }}"
                                        readonly>
                                </td>
                            </tr>
                            <tr>
                                <td>Tanggal (M/D/Y)</td>
                                <td><input type='date' class='form-control form-control-sm' id='tglapprov'
                                        name='tglapprov' value="{{ $tgl }}" autocomplete='off' required
                                        readonly>
                                </td>
                            </tr>
                            <tr>
                                <td>Penjualan</td>
                                <td>
                                    <div class='input-group'> <input type='text' class='form-control form-control-sm'
                                            style="width: 5em" id='nojual' name='nojual' autocomplete='off'
                                            value="{{ $approv_batas_piutang->nojual }}"
                                            {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}>
                                        <input type="text" class='form-control form-control-sm' style="width: 2em"
                                            id='tgljual' name='tgljual' value="{{ $approv_batas_piutang->tgljual }}"
                                            readonly>
                                        <input type="number" style="text-align:right"
                                            class='form-control form-control-sm' name='total' id='total'
                                            value="{{ str_contains($vdata['title'], 'Tambah') ? 0 : $approv_batas_piutang->total }}"
                                            readonly>
                                        <span class='input-group-btn'>
                                            <button type='button' id='src'
                                                class='btn btn-primary btn-sm carijual' onclick='carijual()'
                                                {{ str_contains($vdata['title'], 'Detail') ? 'disabled' : '' }}>Cari</button>
                                        </span>
                                </td>
                            </tr>
                            <tr>
                                <td>Customer</td>
                                <td><input type="text" class='form-control form-control-sm' id='nmcustomer'
                                        name='nmcustomer' value="{{ $approv_batas_piutang->nmcustomer }}" readonly>
                                </td>
                            </tr>
                            @if (str_contains($vdata['title'], 'Detail'))
                                <tr>
                                    <td>Sudah Bayar</td>
                                    <td><input type="number" class="form-control form-control-sm"
                                            style="text-align:right" name="sudahbayar" id="sudahbayar"
                                            value="{{ $approv_batas_piutang->sudahbayar }}" readonly>
                                    </td>
                                </tr>
                                <tr>

                                    <td>Kurang Bayar</td>
                                    <td><input type="number" class="form-control form-control-sm"
                                            style="text-align:right" name="kurangbayar" id="kurangbayar"
                                            value="{{ $approv_batas_piutang->kurangbayar }}" readonly>
                                    </td>
                                </tr>
                            @endif
                            <tr>
                                <td>Keterangan</td>
                                <td>
                                    <textarea type='text' rows='3' class='form-control form-control-sm' id='kerangan' name='keterangan'
                                        autocomplete='off' {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}>{{ $approv_batas_piutang->keterangan }}</textarea>
                                </td>
                            </tr>

                            {{-- <div class="row"> --}}
                            @if (str_contains($vdata['title'], 'Detail'))
                                <tr>
                                    <td>
                                        <label for="nama" class="label mb-1 mt-3">User</label>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm" name="user"
                                            id="user" value="{{ $approv_batas_piutang->user }}" readonly>
                                    </td>
                                </tr>
                            @endif
                        </table>
                    </div>
                    <div class="col-md-12">
                        <div class="modal-footer">
                            @if (str_contains($vdata['title'], 'Detail'))
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            @else
                                <button type="submit" id="btnsimpan"
                                    class="btn btn-primary btnsimpan">Simpan</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            @endif
                        </div>
                    </div>


        </form>
    </div>
</div>


<script>
    var myModal = document.getElementById('modaltambahmaster')
    var myInput = document.getElementById('noreferensi')
    // myModal.addEventListener('shown.bs.modal', function() {
    //     myInput.focus()
    // })
    $(myModal).on('shown.bs.modal', function() {
        $(this).find(myInput).focus();
    });

    // reload_table_detail();
    // reload_total_detail();

    function reload_table_detail_1() {
        $(function() {
            var vapprov = $("#approv").val();
            var table = $('#tbl-detail-approv_batas_piutang_1').DataTable({
                ajax: "{{ url('approv_batas_piutangdajax') }}?approv=" + vapprov,
                type: "GET",
                destroy: true,
                processing: true,
                serverSide: true,
                paging: true,
                lengthChange: true,
                searching: true,
                ordering: true,
                columnDefs: [{
                        className: 'dt-body-center',
                        targets: [0, 8],
                    },

                    {
                        orderable: true,
                        className: 'dt-body-right',
                        targets: [4, 5, 6, 7],
                    },
                ],
                order: [
                    [1, 'asc']
                ],
                info: true,
                autoWidth: true,
                responsive: true,
                aLengthMenu: [
                    [5, 50, 100, -1],
                    [5, 50, 100, "All"]
                ],
                autoWidth: false,
                iDisplayLength: 5,
                columns: [{
                        orderable: false,
                        "data": null,
                        "searchable": false,
                        "render": function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {
                        orderable: true,
                        // data: 'kode1',
                        // name: 'kode1'
                        data: 'kdbarang',
                        name: 'kdbarang'
                        // "render": function(data, type, row, meta) {
                        //     return meta.row + meta.settings._iDisplayStart + 1;
                        // }
                        // data: null,
                        // render: function(data, type, row, meta) {
                        //     return `<a href="#" onclick="detail(${row.id})">${row.kode}</a>`;
                        // }
                    },
                    {
                        data: 'nmbarang',
                        name: 'nmbarang'
                    },
                    {
                        data: 'kdsatuan',
                        name: 'kdsatuan'
                    },
                    {
                        data: 'qty',
                        render: function(data, type, row, meta) {
                            return meta.settings.fnFormatNumber(row.qty);
                        }
                    },
                    {
                        data: 'harga',
                        name: 'harga',
                        render: function(data, type, row, meta) {
                            return meta.settings.fnFormatNumber(row.harga);
                        }
                    },
                    {
                        data: 'discount',
                        name: 'discount'
                    },
                    {
                        data: 'subtotal',
                        render: function(data, type, row, meta) {
                            return meta.settings.fnFormatNumber(row.subtotal);
                        }
                    },
                    {
                        data: null,
                        render: function(data, type, row, meta) {
                            return `<a href="#${row.id}"><button onclick="editdetail(${row.id})" class='btn btn-sm btn-warning' href='javascript:void(0)' <?= $edit == 1 ? '' : 'disabled' ?>><i class='fa fa-edit'></i></button></a>
                            <a href="#${row.id},${row.kode}"><button onclick="hapusdetail(${row.id})" class='btn btn-sm btn-danger' href='javascript:void(0)' <?= $hapus == 1 ? '' : 'disabled' ?>><i class='fa fa-trash'></i></button></a>`;

                        }
                    },
                ]
            });
        });
    }

    $(document).ready(function() {
        reload_table_detail_1();

        $('.formapprov_batas_piutang').submit(function(e) {
            const form = $(this)
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: form.attr('action'),
                data: form.serialize(),
                dataType: "json",
                method: "POST",
                beforeSend: function() {
                    $('.btnsimpan').attr('disable', 'disabled')
                    $('.btnsimpan').html('<i class="fa fa-spin fa-spinner"></i>')
                    form.find('.invalid-feedback').remove()
                    form.find('.is-invalid').removeClass('is-invalid')
                },
                complete: function() {
                    $('.btnsimpan').removeAttr('disable')
                    $('.btnsimpan').html('Simpan')
                },
                success: function(response) {
                    // console.log(response);
                    if (response.error) {
                        if (response.error.kode) {
                            $('#kode').addClass('is-invalid');
                            $('.errorKode').html(response.error.kode);
                        } else {
                            $('.errorKode').fadeOut();
                            $('#kode').removeClass('is-invalid');
                            $('#kode').addClass('is-valid');
                        }
                        if (response.error.nama) {
                            $('#nama').addClass('is-invalid');
                            $('.errorNama').html(response.error.nama);
                        } else {
                            $('.errorNama').fadeOut();
                            $('#nama').removeClass('is-invalid');
                            $('#nama').addClass('is-valid');
                        }
                    } else {
                        $('#modaltambahmaster').modal('hide');
                        // swal({
                        //     title: "Data berhasil disimpann",
                        //     text: "",
                        //     icon: "success",
                        //     buttons: true,
                        //     dangerMode: true,
                        // })
                        // swal({
                        //     title: response.sukses,
                        //     // title: 'Sukses',
                        //     text: "Silahkan dilanjutkan",
                        //     icon: "success",
                        // })
                        reload_table();
                        toastr.info('Data berhasil di simpan, silahkan melanjutkan')
                        // .then(function() {
                        //     window.location.href = '/approv_batas_piutang';
                        // });
                        // window.location = '/approv_batas_piutang';
                    }
                },
                // error: function(xhr, ajaxOptions, thrownError) {
                //     alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                // }
                error: function(xhr, ajaxOptions, thrownError) {
                    // console.log(xhr)
                    const errors = xhr.responseJSON?.errors
                    // console.log(errors);
                    if (errors) {
                        let i = 0;
                        for ([key, message] of Object.entries(errors)) {
                            i++;
                            if (i == 1) {
                                form.find(`[name="${key}"]`).focus()
                            }
                            console.log(key, message);
                            form.find(`[name="${key}"]`)
                                .addClass('is-invalid')
                                .parent()
                                .append(`<div class="invalid-feedback">${message}</div>`)
                        }
                    }

                    // console.log(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                }

            });
            return false;
        })
    });

    // Nampilin list data pilihan ===================
    function carijual() {
        $.ajax({
            method: "GET",
            url: "carijual",
            dataType: "json",
            success: function(response) {
                $('#modalcarijual').html(response.body)
                $("#modalcarijual").modal('show');
            }
        })
    }
    $('#nojual').on('blur', function(e) {
        // alert(1)
        let cari = $(this).val();
        $.ajax({
            url: 'repljual',
            type: 'get',
            data: {
                kode: cari
            },
            success: function(response) {
                let data_response = JSON.parse(response);
                // alert(data_response.kdjual)
                if (data_response.kdjual === "") {
                    $('#nojual').val('');
                    $('#tgljual').val('');
                    $('#total').val('');
                    $('#nmcustomer').val('');
                    // carijual();
                    return;
                }
                $('#nojual').val(data_response['nojual']);
                $('#tgljual').val(data_response['tgljual']);
                $('#total').val(data_response['total']);
                $('#nmcustomer').val(data_response['nmcustomer']);
            },
            error: function() {
                console.log('file not fount');
            }
        })
        // console.log(cari);
    })
</script>
