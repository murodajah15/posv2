<?php
$session = session();
// dd($vdata);
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
        <form action="{{ $action }}" method="post" class="formmohklruangh">
            @csrf
            @if ($mohklruangh->id)
                @method('get')
            @endif
            <?php $tgl = $saplikasi->tgl_berikutnya; ?>
            <input type="hidden" class="form-control-sm" name="id" id="id" value="{{ $mohklruangh->id }}">
            <input type="hidden" class="form-control-sm" name="username" id="username"
                value="{{ $session->get('username') }}">
            <input type="hidden" class="form-control-sm" name="nomohonlama" id="nomohonlama"
                value="{{ $mohklruangh->nomohon }}">
            <div class="modal-body">
                <div class="row">
                    <div class='col-md-6'>
                        <table style=font-size:12px; class='table table-borderless table-sm table-hover'>
                            <tr>
                                <td>Nomor mohon</td>
                                <td>
                                    <input type='text' class='form-control form-control-sm' id='nomohon'
                                        name='nomohon' placeholder='Nomor *' style='text-transform:uppercase'
                                        {{-- value={{ 'mohklruangh' . $saplikasi->tahun . $saplikasi->bulan . sprintf('%05s', $saplikasi->mohon) }} --}}
                                        value="{{ str_contains($vdata['title'], 'Tambah') ? 'PK' . $saplikasi->tahun . sprintf('%02s', $saplikasi->bulan) . sprintf('%04s', $saplikasi->nokwtagihan + 1) : $mohklruangh->nomohon }}"
                                        readonly>
                                </td>
                            </tr>
                            <tr>
                                <td>Tanggal (M/D/Y)</td>
                                <td><input type='date' class='form-control form-control-sm' id='tglmohon'
                                        name='tglmohon' value="{{ $tgl }}" autocomplete='off' required
                                        readonly>
                                </td>
                            </tr>
                            <tr>
                                <td>Jenis Pengeluaran</td>
                                <td><input type="hidden" class="form-control form-control-sm" name="kdjnkeluare"
                                        id="kdjnkeluare" value="<?= $mohklruangh['kdjnkeluar'] ?>">
                                    <select class="form-control form-control-sm" name='kdjnkeluar' id="kdjnkeluar"
                                        {{ str_contains($vdata['title'], 'Detail') ? 'disabled' : '' }} required>
                                        {{-- <option value="">[Pilih Jenis Pengeluaran]</option> --}}
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>Supplier</td>
                                <td>
                                    <div class='input-group'> <input type='text' class='form-control form-control-sm'
                                            style="width:4em" id='kdsupplier' name='kdsupplier' autocomplete='off'
                                            value="{{ $mohklruangh->kdsupplier }}"
                                            {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}>
                                        <input type="text" class='form-control form-control-sm' style="width:20em"
                                            id='nmsupplier' name='nmsupplier' value="{{ $mohklruangh->nmsupplier }}"
                                            readonly>
                                        <span class='input-group-btn'>
                                            <button type='button' id='src'
                                                class='btn btn-primary btn-sm carisupplier' onclick='carisupplier()'
                                                {{ str_contains($vdata['title'], 'Detail') ? 'disabled' : '' }}>Cari</button>
                                        </span>
                                </td>
                            </tr>
                            <tr>
                                <td>Subtotal</td>
                                <td><input type="number" class='form-control form-control-sm' id='subtotal'
                                        name='subtotal' style="text-align:right" value="{{ $mohklruangh->subtotal }}"
                                        readonly>
                                </td>
                            </tr>
                            <tr>
                                <td>Materai</td>
                                <td><input type="number" class='form-control form-control-sm' id='materai'
                                        name='materai' style="text-align:right"
                                        {{ str_contains($vdata['title'], 'Detail') ? 'disabled' : '' }}
                                        value="{{ $mohklruangh->materai }}">
                                </td>
                            </tr>
                            <tr>
                                <td>Total</td>
                                <td><input type="number" class='form-control form-control-sm' id='total'
                                        name='total' style="text-align:right" value="{{ $mohklruangh->total }}"
                                        readonly>
                                </td>
                            </tr>
                            <tr>
                                <td>Bayar</td>
                                <td><input type="number" class='form-control form-control-sm' id='bayar'
                                        name='bayar' style="text-align:right" value="{{ $mohklruangh->bayar }}"
                                        readonly>
                                </td>
                            </tr>
                            <tr>
                                <td>Kurang Bayar</td>
                                <td><input type="number" class='form-control form-control-sm' id='kurang'
                                        name='kurang' style="text-align:right" value="{{ $mohklruangh->kurang }}"
                                        readonly>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class='col-md-6'>
                        <table style=font-size:12px; class='table table-borderless table-sm table-hover'>
                            <td>Cara Bayar
                            <td><input type="radio" name="carabayar" id="carabayar" value="Cash"
                                    {{ $mohklruangh->carabayar == 'Cash' ? 'checked' : '' }}
                                    {{ str_contains($vdata['title'], 'Detail') ? 'disabled' : '' }} required> Cash
                            <td><input type="radio" name="carabayar" id="carabayar" value="Transfer"
                                    {{ $mohklruangh->carabayar == 'Transfer' ? 'checked' : '' }}
                                    {{ str_contains($vdata['title'], 'Detail') ? 'disabled' : '' }} required>
                                Transfer
                            <td><input type="radio" name="carabayar" id="carabayar" value="Cek/Giro"
                                    {{ $mohklruangh->carabayar == 'Cek/Giro' ? 'checked' : '' }}
                                    {{ str_contains($vdata['title'], 'Detail') ? 'disabled' : '' }} required>
                                Cek/Giro
                            <td><input type="radio" name="carabayar" id="carabayar" value="Debit Card"
                                    {{ $mohklruangh->carabayar == 'Debit Card' ? 'checked' : '' }}
                                    {{ str_contains($vdata['title'], 'Detail') ? 'disabled' : '' }} required>
                                Debit Card
                            <td><input type="radio" name="carabayar" id="carabayar" value="Credit Card"
                                    {{ $mohklruangh->carabayar == 'Credit Card' ? 'checked' : '' }}
                                    {{ str_contains($vdata['title'], 'Detail') ? 'disabled' : '' }} required>
                                Credit Card
                        </table>
                        <table style=font-size:12px; class='table table-borderless table-sm table-hover'>
                            <tr>
                                <td>Bank</td>
                                <td>
                                    <div class='input-group'>
                                        <input type='text' class='form-control form-control-sm' name='kdbank'
                                            id='kdbank' style="width: 4em"
                                            {{ str_contains($vdata['title'], 'Detail') ? 'disabled' : '' }}
                                            value="{{ $mohklruangh->kdbank }}" autocomplete='off'>
                                        <input type="text" class='form-control form-control-sm' name='nmbank'
                                            id='nmbank' style="width: 15em" value="{{ $mohklruangh->nmbank }}"
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
                                            id='kdjnskartu' style="width: 4em"
                                            {{ str_contains($vdata['title'], 'Detail') ? 'disabled' : '' }}
                                            value="{{ $mohklruangh->kdjnskartu }}" autocomplete='off'>
                                        <input type="text" class='form-control form-control-sm' name='nmjnskartu'
                                            id='nmjnskartu' style="width: 15em"
                                            value="{{ $mohklruangh->nmjnskartu }}"
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
                                        name='norek' value="{{ $mohklruangh->norek }}"
                                        {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}
                                        size='50'></td>
                            </tr>
                            <tr>
                                <td>No. Giro/Cek</td>
                                <td> <input type="text" class='form-control form-control-sm' id='nocekgiro'
                                        name='nocekgiro' value="{{ $mohklruangh->nocekgiro }}"
                                        {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}
                                        size='50'></td>
                            </tr>
                            <tr>
                                <td>Tgl. Terima Cek (M/D/Y)</td>
                                <td><input type='date' class='form-control form-control-sm' id='tglterimacekgiro'
                                        value="{{ $mohklruangh->tglterimacekgiro }}"
                                        {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}
                                        name='tglterimacekgiro' size='50' autocomplete='off'></td>
                            </tr>
                            <tr>
                                <td>Tgl. Jt.Tempo Cek (M/D/Y)</td>
                                <td><input type='date' class='form-control form-control-sm' id='tgljttempocekgiro'
                                        value="{{ $mohklruangh->tgljttempocekgiro }}"
                                        {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}
                                        name='tgljttempocekgiro' size='50' autocomplete='off'></td>
                            </tr>
                            <tr>
                                <td>Keterangan</td>
                                <td>
                                    <textarea type='text' rows='3' class='form-control form-control-sm' id='kerangan' name='keterangan'
                                        autocomplete='off' {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}>{{ $mohklruangh->keterangan }}</textarea>
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
                                            id="user" value="{{ $mohklruangh->user }}" readonly>
                                    </td>
                                </tr>
                            @endif
                        </table>
                    </div>
                    @if (str_contains($vdata['title'], 'Detail'))
                        <div class='col-md-12'>
                            <table id='tbl-detail-mohklruangh_1' style='font-size:13px;'
                                class='table table-striped table table-bordered' width='600px'>
                                <thead>
                                    <tr>
                                        <th width='30'>No.</th>
                                        <th width='120'>No. Penjualan</th>
                                        <th width='80'>Kode</th>
                                        <th width='300'>Supplier</th>
                                        <th width='90'>Jumlah</th>
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
    var myInput = document.getElementById('materai')
    // myModal.addEventListener('shown.bs.modal', function() {
    //     myInput.focus()
    // })
    $(myModal).on('shown.bs.modal', function() {
        $(this).find(myInput).focus();
    });

    // reload_table_detail_1();
    // reload_total_detail_1();

    function reload_table_detail_1() {
        $(function() {
            var vnomohon = $("#nomohon").val();
            var table = $('#tbl-detail-mohklruangh_1').DataTable({
                ajax: "{{ url('mohklruangdajax') }}?nomohon=" + vnomohon,
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
                        targets: [4],
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
                        data: 'nomohon',
                        name: 'nomohon'
                        // "render": function(data, type, row, meta) {
                        //     return meta.row + meta.settings._iDisplayStart + 1;
                        // }
                        // data: null,
                        // render: function(data, type, row, meta) {
                        //     return `<a href="#" onclick="detail(${row.id})">${row.kode}</a>`;
                        // }
                    },
                    {
                        data: 'kdsupplier',
                        name: 'kdsupplier'
                    },
                    {
                        data: 'nmsupplier',
                        name: 'nmsupplier'
                    },
                    {
                        data: 'uang',
                        name: 'uang',
                        render: function(data, type, row, meta) {
                            return meta.settings.fnFormatNumber(row.bayar);
                        }
                    },
                ]
            });
        });
    }

    function reload_total_detail() {
        $(function() {
            var vnomohon = $("#nomohon").val();
            $.ajax({
                type: "get",
                data: {
                    nomohon: vnomohon,
                },
                // dataType: "json",
                // url: "sototaldetail",
                url: `{{ route('mohklruangtotaldetail') }}`,
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

    $(document).ready(function() {
        reload_table_detail_1();

        $('#materai').on('keyup', function(e) {
            hit_total();
        })

        function hit_total() {
            alert(1)
            var subtotal = document.getElementById("subtotal").value
            if (subtotal === "") {
                subtotal = 0;
            }
            var materai = document.getElementById("materai").value
            if (materai === "") {
                materai = 0;
            }
            var total = parseFloat(subtotal) + parseFloat(materai)
            document.getElementById("total").value = total
        }

        $('.formmohklruangh').submit(function(e) {
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
                        //     window.location.href = '/mohklruangh';
                        // });
                        // window.location = '/mohklruangh';
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

    var carikdjnkeluar = $("#kdjnkeluare").val();
    $.ajax({
        url: "<?= url('ambildatatbjnkeluar') ?>",
        dataType: "json",
        data: {
            'kdjnkeluar': carikdjnkeluar
        },
        success: function(response) {
            if (response.data) {
                $('#kdjnkeluar').html(response.data);
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
        }
    })

    // tampildatatbjnkeluar();
    $('#kdjnkeluar').focusin(function(e) {
        $.ajax({
            url: "<?= url('ambildatatbjnkeluar') ?>",
            dataType: "json",
            success: function(response) {
                if (response.data) {
                    $('#kdjnkeluar').html(response.data);
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
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

    // Nampilin list data pilihan ===================
    function carisupplier() {
        $.ajax({
            method: "GET",
            url: "carisupplier",
            dataType: "json",
            success: function(response) {
                $('#modalcarisupplier').html(response.body)
                $("#modalcarisupplier").modal('show');
            }
        })
    }
    $('#kdsupplier').on('blur', function(e) {
        // alert(1)
        let cari = $(this).val();
        $.ajax({
            url: 'replsupplier',
            type: 'get',
            data: {
                kode: cari
            },
            success: function(response) {
                let data_response = JSON.parse(response);
                // alert(data_response.kdsupplier)
                if (data_response.kdsupplier === "") {
                    $('#kdsupplier').val('');
                    $('#nmsupplier').val('');
                    // carisupplier();
                    return;
                }
                $('#nmsupplier').val(data_response['nmsupplier']);
            },
            error: function() {
                conpole.log('file not fount');
            }
        })
        // conpole.log(cari);
    })
</script>
