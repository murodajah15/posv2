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
        <form action="{{ $action }}" method="post" class="formkasir_tagihan">
            @csrf
            @if ($kasir_tagihan->id)
                @method('get')
            @endif
            <?php $tgl = $saplikasi->tgl_berikutnya; ?>
            <input type="hidden" class="form-control-sm" name="id" id="id"
                value="{{ $kasir_tagihan->id }}">
            <input type="hidden" class="form-control-sm" name="username" id="username"
                value="{{ $session->get('username') }}">
            <input type="hidden" class="form-control-sm" name="nokwitansilama" id="nokwitansilama"
                value="{{ $kasir_tagihan->nokwitansi }}">
            <div class="modal-body">
                <div class="row">
                    <div class='col-md-6'>
                        <table style=font-size:12px; class='table table-borderless table-sm table-hover'>
                            <tr>
                                <td>Nomor Kwitansi</td>
                                <td>
                                    <input type='text' class='form-control form-control-sm' id='nokwitansi'
                                        name='nokwitansi' placeholder='Nomor *' style='text-transform:uppercase'
                                        {{-- value={{ 'kasir_tagihan' . $saplikasi->tahun . $saplikasi->bulan . sprintf('%05s', $saplikasi->kwitansi) }} --}}
                                        value="{{ str_contains($vdata['title'], 'Tambah') ? 'KWT' . $saplikasi->tahun . sprintf('%02s', $saplikasi->bulan) . sprintf('%04s', $saplikasi->nokwtagihan + 1) : $kasir_tagihan->nokwitansi }}"
                                        readonly>
                                </td>
                            </tr>
                            <tr>
                                <td>Tanggal (M/D/Y)</td>
                                <td><input type='date' class='form-control form-control-sm' id='tglkwitansi'
                                        name='tglkwitansi'
                                        value="{{ str_contains($vdata['title'], 'Tambah') ? $tgl : $kasir_tagihan->tglkwitansi }}"
                                        autocomplete='off' required
                                        {{ str_contains($vdata['title'], 'Detail') ? 'Readonly' : '' }}>
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
                                            if ($arrlevel[$c] == $kasir_tagihan->jnskwitansi) {
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
                                <td>Customer</td>
                                <td><input type="text" class='form-control form-control-sm' id='nmcustomer'
                                        name='nmcustomer'
                                        {{ str_contains($vdata['title'], 'Detail') ? 'disabled' : '' }}
                                        value="{{ $kasir_tagihan->nmcustomer }}">
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class='col-md-6'>
                        <table style=font-size:12px; class='table table-borderless table-sm table-hover'>
                            <td>Cara Bayar
                            <td><input type="radio" name="carabayar" id="carabayar" value="Cash"
                                    {{ $kasir_tagihan->carabayar == 'Cash' ? 'checked' : '' }}
                                    {{ str_contains($vdata['title'], 'Detail') ? 'disabled' : '' }} required> Cash
                            <td><input type="radio" name="carabayar" id="carabayar" value="Transfer"
                                    {{ $kasir_tagihan->carabayar == 'Transfer' ? 'checked' : '' }}
                                    {{ str_contains($vdata['title'], 'Detail') ? 'disabled' : '' }} required>
                                Transfer
                            <td><input type="radio" name="carabayar" id="carabayar" value="Cek/Giro"
                                    {{ $kasir_tagihan->carabayar == 'Cek/Giro' ? 'checked' : '' }}
                                    {{ str_contains($vdata['title'], 'Detail') ? 'disabled' : '' }} required>
                                Cek/Giro
                            <td><input type="radio" name="carabayar" id="carabayar" value="Debit Card"
                                    {{ $kasir_tagihan->carabayar == 'Debit Card' ? 'checked' : '' }}
                                    {{ str_contains($vdata['title'], 'Detail') ? 'disabled' : '' }} required>
                                Debit Card
                            <td><input type="radio" name="carabayar" id="carabayar" value="Credit Card"
                                    {{ $kasir_tagihan->carabayar == 'Credit Card' ? 'checked' : '' }}
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
                                            value="{{ $kasir_tagihan->kdbank }}" autocomplete='off'>
                                        <input type="text" class='form-control form-control-sm' name='nmbank'
                                            id='nmbank' style="width: 15em" value="{{ $kasir_tagihan->nmbank }}"
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
                                            value="{{ $kasir_tagihan->kdjnskartu }}" autocomplete='off'>
                                        <input type="text" class='form-control form-control-sm' name='nmjnskartu'
                                            id='nmjnskartu' style="width: 15em"
                                            value="{{ $kasir_tagihan->nmjnskartu }}"
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
                                        name='norek' value="{{ $kasir_tagihan->norek }}"
                                        {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}
                                        size='50'></td>
                            </tr>
                            <tr>
                                <td>No. Giro/Cek</td>
                                <td> <input type="text" class='form-control form-control-sm' id='nocekgiro'
                                        name='nocekgiro' value="{{ $kasir_tagihan->nocekgiro }}"
                                        {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}
                                        size='50'></td>
                            </tr>
                            <tr>
                                <td>Tgl. Terima Cek (M/D/Y)</td>
                                <td><input type='date' class='form-control form-control-sm' id='tglterimacekgiro'
                                        value="{{ $kasir_tagihan->tglterimacekgiro }}"
                                        {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}
                                        name='tglterimacekgiro' size='50' autocomplete='off'></td>
                            </tr>
                            <tr>
                                <td>Tgl. Jt.Tempo Cek (M/D/Y)</td>
                                <td><input type='date' class='form-control form-control-sm' id='tgljttempocekgiro'
                                        value="{{ $kasir_tagihan->tgljttempocekgiro }}"
                                        {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}
                                        name='tgljttempocekgiro' size='50' autocomplete='off'></td>
                            </tr>
                            <tr>
                                <td>Total</td>
                                <td><input type='number' class='form-control form-control-sm' id='total'
                                        style="text-align:right;" value="{{ $kasir_tagihan->total }}" readonly
                                        name='total' size='50' autocomplete='off'></td>
                            </tr>
                            <tr>
                                <td>Keterangan</td>
                                <td>
                                    <textarea type='text' rows='3' class='form-control form-control-sm' id='kerangan' name='keterangan'
                                        autocomplete='off' {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}>{{ $kasir_tagihan->keterangan }}</textarea>
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
                                            id="user" value="{{ $kasir_tagihan->user }}" readonly>
                                    </td>
                                </tr>
                            @endif
                        </table>
                    </div>
                    @if (str_contains($vdata['title'], 'Detail'))
                        <div class='col-md-12'>
                            <table id='tbl-detail-kasir_tagihan_1' style='font-size:13px;'
                                class='table table-striped table table-bordered' width='600px'>
                                <thead>
                                    <tr>
                                        <th width='30'>No.</th>
                                        <th width='120'>No. Penjualan</th>
                                        <th width='80'>Kode</th>
                                        <th width='300'>Customer</th>
                                        <th width='90'>Piutang</th>
                                        <th width='90'>Bayar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <div class='col-md-12'>
                            <div id="total_detail_1"></div>
                        </div>
                    @endif
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

    reload_table_detail_1();
    reload_total_detail_1();

    function reload_table_detail_1() {
        $(function() {
            var vnokwitansi = $("#nokwitansi").val();
            var table = $('#tbl-detail-kasir_tagihan_1').DataTable({
                ajax: "{{ url('kasir_tagihandajax') }}?nokwitansi=" + vnokwitansi,
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
                        targets: [0],
                    },

                    {
                        orderable: true,
                        className: 'dt-body-right',
                        targets: [4, 5],
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
                        data: 'nojual',
                        name: 'nojual'
                        // "render": function(data, type, row, meta) {
                        //     return meta.row + meta.settings._iDisplayStart + 1;
                        // }
                        // data: null,
                        // render: function(data, type, row, meta) {
                        //     return `<a href="#" onclick="detail(${row.id})">${row.kode}</a>`;
                        // }
                    },
                    {
                        data: 'kdcustomer',
                        name: 'kdcustomer'
                    },
                    {
                        data: 'nmcustomer',
                        name: 'nmcustomer'
                    },
                    {
                        data: 'piutang',
                        render: function(data, type, row, meta) {
                            return meta.settings.fnFormatNumber(row.piutang);
                        }
                    },
                    {
                        data: 'bayar',
                        name: 'bayar',
                        render: function(data, type, row, meta) {
                            return meta.settings.fnFormatNumber(row.bayar);
                        }
                    },
                ]
            });
        });
    }

    function reload_total_detail_1() {
        $(function() {
            var vnokwitansi = $("#nokwitansi").val();
            $.ajax({
                type: "get",
                data: {
                    nokwitansi: vnokwitansi,
                },
                // dataType: "json",
                // url: "sototaldetail",
                url: `{{ route('kasir_tagihantotaldetail') }}`,
                beforeSend: function(f) {
                    $('#total_detail_1').attr('disable', 'disabled')
                    $('#total_detail_1').html('<i class="fa fa-spin fa-spinner"></i>')
                    $('#total_detail_1').html('<center>Loading Data ...</center>');
                },
                success: function(response) {
                    // $('#total_detail_1').html(data);
                    $('#total_detail_1').removeAttr('disable')
                    $('#total_detail_1').html('<i class="fa fa-spinner">')
                    $('#total_detail_1').html(response.body);
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                }
            })
        })
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

        $('.formkasir_tagihan').submit(function(e) {
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
                        //     window.location.href = '/kasir_tagihan';
                        // });
                        // window.location = '/kasir_tagihan';
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
