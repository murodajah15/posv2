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
<div class="modal-dialog" style="max-width: 95%;">
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
        <form action="{{ $action }}" method="post" class="formkasir_tunai">
            @csrf
            @if ($kasir_tunai->id)
                @method('get')
            @endif
            <?php $tgl = $saplikasi->tgl_berikutnya; ?>
            <input type="hidden" class="form-control-sm" name="id" id="id" value="{{ $kasir_tunai->id }}">
            <input type="hidden" class="form-control-sm" name="username" id="username"
                value="{{ $session->get('username') }}">
            <input type="hidden" class="form-control-sm" name="nokwitansilama" id="nokwitansilama"
                value="{{ $kasir_tunai->nokwitansi }}">
            <div class="modal-body">
                <div class="row">
                    <div class='col-md-6'>
                        <table style=font-size:12px; class='table table-borderless table-sm table-hover'>
                            <tr>
                                <td>Nomor kwitansial</td>
                                <td>
                                    <input type='text' class='form-control form-control-sm' id='nokwitansi'
                                        name='nokwitansi' placeholder='Nomor *' style='text-transform:uppercase'
                                        {{-- value={{ 'kasir_tunai' . $saplikasi->tahun . $saplikasi->bulan . sprintf('%05s', $saplikasi->kwitansi) }} --}}
                                        value="{{ str_contains($vdata['title'], 'Tambah') ? 'KW' . $saplikasi->tahun . sprintf('%02s', $saplikasi->bulan) . sprintf('%05s', $saplikasi->nokwtunai + 1) : $kasir_tunai->nokwitansi }}"
                                        readonly>
                                </td>
                            </tr>
                            <tr>
                                <td>Tanggal (M/D/Y)</td>
                                <td><input type='date' class='form-control form-control-sm' id='tglkwitansi'
                                        name='tglkwitansi'
                                        value="{{ str_contains($vdata['title'], 'Tambah') ? $tgl : $kasir_tunai->tglkwitansi }}"
                                        autocomplete='off' required readonly>
                                </td>
                            </tr>
                            <tr>
                                <td>Jenis Kwitansi
                                <td><select class="form-control form-control-sm" name="jnskwitansi" id="jnskwitansi"
                                        {{ str_contains($vdata['title'], 'Detail') ? 'disabled' : '' }} required>
                                        <option value="">[Pilih Jenis Kwitansi]</option>
                                        <?php
                                        $arrlevel = ['UANG MUKA', 'PELUNASAN'];
                                        $jml_kata = count($arrlevel);
                                        for ($c = 0; $c < $jml_kata; $c += 1) {
                                            if ($arrlevel[$c] == $kasir_tunai->jnskwitansi) {
                                                echo "<option value='$arrlevel[$c]' selected>$arrlevel[$c] </option>";
                                            } else {
                                                echo "<option value='$arrlevel[$c]'> $arrlevel[$c] </option>";
                                            }
                                        }
                                        echo '</select>';
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>Penjualan</td>
                                <td>
                                    <div class='input-group'> <input type='text' class='form-control form-control-sm'
                                            style="width: 5em" id='nojual' name='nojual' autocomplete='off'
                                            value="{{ $kasir_tunai->nojual }}"
                                            {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}>
                                        {{-- <input type="text" class='form-control form-control-sm' style="width: 2em"
                                            id='tgljual' name='tgljual' value="{{ $kasir_tunai->tgljual }}" readonly> --}}
                                        {{-- <input type="number" style="text-align:right"
                                            class='form-control form-control-sm' name='total' id='total'
                                            value="{{ str_contains($vdata['title'], 'Tambah') ? 0 : $kasir_tunai->total }}"
                                            readonly> --}}
                                        <input type="text" class='form-control form-control-sm' id='nmcustomer'
                                            name='nmcustomer' style="width: 15em"
                                            value="{{ $kasir_tunai->nmcustomer }}" readonly>
                                        <span class='input-group-btn'>
                                            <button type='button' id='src'
                                                class='btn btn-primary btn-sm carijualpiutang'
                                                onclick='carijualpiutang()'
                                                {{ str_contains($vdata['title'], 'Detail') ? 'disabled' : '' }}>Cari</button>
                                        </span>
                                    </div>
                                </td>
                            </tr>
                            {{-- <tr>
                                <td>Customer</td>
                                <td><input type="text" class='form-control form-control-sm' id='nmcustomer'
                                        name='nmcustomer' value="{{ $kasir_tunai->nmcustomer }}" readonly>
                                </td>
                            </tr> --}}
                            <tr>
                                <td>Nilai Piutang</td>
                                <td align='right'> <input type="number" class='form-control form-control-sm'
                                        id='piutang' name='piutang' value="{{ $kasir_tunai->piutang }}"
                                        size='50' style='text-align:right' readonly>
                                </td>
                            </tr>
                            <tr>
                                <td>Nilai Bayar</td>
                                <td align='right'> <input type="number" class='form-control form-control-sm'
                                        id='bayar' name='bayar' value="{{ $kasir_tunai->bayar }}" size='50'
                                        style='text-align:right'
                                        {{ str_contains($vdata['title'], 'Detail') ? 'disabled' : '' }}
                                        onkeyup="validAngka_no_titik(this)" onblur="hit_kembali()" required></td>
                            </tr>
                            <tr>
                                <td>Uang diterima </td>
                                <td> <input type="number" class='form-control form-control-sm' id='uang'
                                        name='uang' value="{{ $kasir_tunai->uang }}" size='65'
                                        style='text-align:right'
                                        {{ str_contains($vdata['title'], 'Detail') ? 'disabled' : '' }}
                                        onkeyup="validAngka_no_titik(this)" onblur="hit_kembali()" required></td>
                            <tr>
                                <td>Kembali </td>
                                <td> <input type="number" class='form-control form-control-sm' id='kembali'
                                        name='kembali' value="{{ $kasir_tunai->kembali }}" size='35'
                                        style='text-align:right' readonly required>
                                </td>
                            </tr>
                            @if (str_contains($vdata['title'], 'Detail'))
                                {{-- <tr>
                                    <td>Sudah Bayar</td>
                                    <td><input type="number" class="form-control form-control-sm"
                                            style="text-align:right" name="sudahbayar" id="sudahbayar"
                                            value="{{ $kasir_tunai->sudahbayar }}" readonly>
                                    </td>
                                </tr>
                                <tr>

                                    <td>Kurang Bayar</td>
                                    <td><input type="number" class="form-control form-control-sm"
                                            style="text-align:right" name="kurangbayar" id="kurangbayar"
                                            value="{{ $kasir_tunai->kurangbayar }}" readonly>
                                    </td>
                                </tr> --}}
                            @endif
                        </table>
                    </div>
                    <div class='col-md-6'>
                        <table style=font-size:12px; class='table table-borderless table-sm table-hover'>
                            <td>Cara Bayar
                            <td><input type="radio" name="carabayar" id="carabayar" value="Cash"
                                    {{ $kasir_tunai->carabayar == 'Cash' ? 'checked' : '' }}
                                    {{ str_contains($vdata['title'], 'Detail') ? 'disabled' : '' }} required> Cash
                            <td><input type="radio" name="carabayar" id="carabayar" value="Transfer"
                                    {{ $kasir_tunai->carabayar == 'Transfer' ? 'checked' : '' }}
                                    {{ str_contains($vdata['title'], 'Detail') ? 'disabled' : '' }} required>
                                Transfer
                            <td><input type="radio" name="carabayar" id="carabayar" value="Cek/Giro"
                                    {{ $kasir_tunai->carabayar == 'Cek/Giro' ? 'checked' : '' }}
                                    {{ str_contains($vdata['title'], 'Detail') ? 'disabled' : '' }} required>
                                Cek/Giro
                            <td><input type="radio" name="carabayar" id="carabayar" value="Debit Card"
                                    {{ $kasir_tunai->carabayar == 'Debit Card' ? 'checked' : '' }}
                                    {{ str_contains($vdata['title'], 'Detail') ? 'disabled' : '' }} required>
                                Debit Card
                            <td><input type="radio" name="carabayar" id="carabayar" value="Credit Card"
                                    {{ $kasir_tunai->carabayar == 'Credit Card' ? 'checked' : '' }}
                                    {{ str_contains($vdata['title'], 'Detail') ? 'disabled' : '' }} required>
                                Credit Card
                        </table>
                        <table style=font-size:12px; class='table table-borderless table-sm table-hover'>
                            <tr>
                                <td>Bank</td>
                                <td>
                                    <div class='input-group'>
                                        <input type='text' class='form-control form-control-sm' name='kdbank'
                                            id='kdbank' style="width: 5em"
                                            {{ str_contains($vdata['title'], 'Detail') ? 'disabled' : '' }}
                                            value="{{ $kasir_tunai->kdbank }}" autocomplete='off'>
                                        <input type="text" class='form-control form-control-sm' name='nmbank'
                                            id='nmbank' style="width: 15em" value="{{ $kasir_tunai->nmbank }}"
                                            readonly>
                                        <span class='input-group-btn'>
                                            <button type='button' id='src' class='btn btn-primary btn-sm'
                                                {{ str_contains($vdata['title'], 'Detail') ? 'disabled' : '' }}
                                                onclick='caritbbank()'>Cari</button>
                                        </span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>Jenis Kartu</td>
                                <td>
                                    <div class='input-group'>
                                        <input type='text' class='form-control form-control-sm' name='kdjnskartu'
                                            id='kdjnskartu' style="width: 5em"
                                            {{ str_contains($vdata['title'], 'Detail') ? 'disabled' : '' }}
                                            value="{{ $kasir_tunai->kdjnskartu }}" autocomplete='off'>
                                        <input type="text" class='form-control form-control-sm' name='nmjnskartu'
                                            id='nmjnskartu' style="width: 15em"
                                            value="{{ $kasir_tunai->nmjnskartu }}"
                                            {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}
                                            size='50' readonly>
                                        <span class='input-group-btn'>
                                            <button type='button' id='src' class='btn btn-primary btn-sm'
                                                {{ str_contains($vdata['title'], 'Detail') ? 'disabled' : '' }}
                                                onclick='caritbjnskartu()'>Cari</button>
                                        </span>
                                </td>
                            </tr>
                            {{-- </table>
                        <table style=font-size:12px; class='table table-borderless table-sm table-hover'> --}}
                            <tr>
                                <td>No. Rekening</td>
                                <td> <input type="text" class='form-control form-control-sm' id='norek'
                                        name='norek' value="{{ $kasir_tunai->norek }}"
                                        {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}
                                        size='50'></td>
                            </tr>
                            <tr>
                                <td>No. Giro/Cek</td>
                                <td> <input type="text" class='form-control form-control-sm' id='nocekgiro'
                                        name='nocekgiro' value="{{ $kasir_tunai->nocekgiro }}"
                                        {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}
                                        size='50'></td>
                            </tr>
                            <tr>
                                <td>Tgl. Terima Cek (M/D/Y)</td>
                                <td><input type='date' class='form-control form-control-sm' id='tglterimacekgiro'
                                        value="{{ $kasir_tunai->tglterimacekgiro }}"
                                        {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}
                                        name='tglterimacekgiro' size='50' autocomplete='off'></td>
                            </tr>
                            <tr>
                                <td>Tgl. Jt.Tempo Cek (M/D/Y)</td>
                                <td><input type='date' class='form-control form-control-sm' id='tgljttempocekgiro'
                                        value="{{ $kasir_tunai->tgljttempocekgiro }}"
                                        {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}
                                        name='tgljttempocekgiro' size='50' autocomplete='off'></td>
                            </tr>

                            <tr>
                                <td>Keterangan</td>
                                <td>
                                    <textarea type='text' rows='3' class='form-control form-control-sm' id='kerangan' name='keterangan'
                                        autocomplete='off' {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}>{{ $kasir_tunai->keterangan }}</textarea>
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
                                            id="user" value="{{ $kasir_tunai->user }}" readonly>
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
            var vkwitansi = $("#kwitansi").val();
            var table = $('#tbl-detail-kasir_tunai_1').DataTable({
                ajax: "{{ url('kasir_tunaidajax') }}?kwitansi=" + vkwitansi,
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

    $('#biaya_lain').on('keyup', function(e) {
        hit_total();
    })

    $(document).ready(function() {
        reload_table_detail_1();

        $('#bayar').on('keyup', function(e) {
            hit_kembali();
        })
        $('#uang').on('keyup', function(e) {
            hit_kembali();
        })

        $('.formkasir_tunai').submit(function(e) {
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
                        //     window.location.href = '/kasir_tunai';
                        // });
                        // window.location = '/kasir_tunai';
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
    function carijualpiutang() {
        $.ajax({
            method: "GET",
            url: "carijualpiutang",
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
            url: 'repljualpiutang',
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
                    $('#piutang').val('');
                    $('#bayar').val('');
                    $('#uang').val('');
                    $('#nmcustomer').val('');
                    // carijual();
                    return;
                }
                $('#nojual').val(data_response['nojual']);
                $('#tgljual').val(data_response['tgljual']);
                $('#piutang').val(data_response['piutang']);
                $('#bayar').val(data_response['piutang']);
                $('#uang').val(data_response['piutang']);
                $('#nmcustomer').val(data_response['nmcustomer']);
            },
            error: function() {
                console.log('file not fount');
            }
        })
        // console.log(cari);
    })

    // Nampilin list data pilihan ===================
    function caritbbank() {
        $.ajax({
            method: "GET",
            url: "caritbbank",
            dataType: "json",
            success: function(response) {
                $('#modalcaribank').html(response.body)
                $("#modalcaribank").modal('show');
            }
        })
    }
    $('#kdbank').on('blur', function(e) {
        // alert(1)
        let cari = $(this).val();
        $.ajax({
            url: 'repltbbank',
            type: 'get',
            data: {
                kode: cari
            },
            success: function(response) {
                let data_response = JSON.parse(response);
                // alert(data_response.kdjual)
                if (data_response.kdjual === "") {
                    $('#kdbank').val('');
                    $('#nmbank').val('');
                    // carijual();
                    return;
                }
                $('#kdbank').val(data_response['kdbank']);
                $('#nmbank').val(data_response['nmbank']);
            },
            error: function() {
                console.log('file not fount');
            }
        })
        // console.log(cari);
    })

    // Nampilin list data pilihan ===================
    function caritbjnskartu() {
        $.ajax({
            method: "GET",
            url: "caritbjnskartu",
            dataType: "json",
            success: function(response) {
                $('#modalcarijnskartu').html(response.body)
                $("#modalcarijnskartu").modal('show');
            }
        })
    }
    $('#kdjnskartu').on('blur', function(e) {
        // alert(1)
        let cari = $(this).val();
        $.ajax({
            url: 'repltbjnskartu',
            type: 'get',
            data: {
                kode: cari
            },
            success: function(response) {
                let data_response = JSON.parse(response);
                // alert(data_response.kdjual)
                if (data_response.kdjual === "") {
                    $('#kdjnskartu').val('');
                    $('#nmjnskartu').val('');
                    // carijual();
                    return;
                }
                $('#kdjnskartu').val(data_response['kdjnskartu']);
                $('#nmjnskartu').val(data_response['nmjnskartu']);
            },
            error: function() {
                console.log('file not fount');
            }
        })
        // console.log(cari);
    })

    function hit_kembali() {
        var bayar = document.getElementById("bayar").value
        if (bayar === "") {
            bayar = 0;
        }
        var uang = document.getElementById("uang").value
        if (uang === "") {
            uang = 0;
        }
        var kembali = parseFloat(uang) - parseFloat(bayar)
        document.getElementById("kembali").value = kembali
    }
</script>
