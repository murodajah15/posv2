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
?>
<!-- Modal -->
<div class="modal-dialog" style="max-width: 85%;">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="staticBackdropLabel">
                {{ $vdata['title'] }}
                {{-- @if (isset($vdata))
                    {{ $vdata['title'] }}
                @endif --}}
            </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form action="{{ $action }}" method="post" class="formpo">
            @csrf
            @if ($po->id)
                @method('get')
            @endif
            <?php $tgl = $saplikasi->tgl_berikutnya; ?>
            <input type="hidden" class="form-control-sm" name="id" id="id" value="{{ $po->id }}">
            <input type="hidden" class="form-control-sm" name="username" id="username"
                value="{{ $session->get('username') }}">
            <input type="hidden" class="form-control-sm" name="nopolama" id="nopolama" value="{{ $po->nopo }}">
            <div class="modal-body">
                <div class="row">
                    <div class='col-md-6'>
                        <table style=font-size:12px; class='table table-borderless table-sm table-hover'>
                            <tr>
                                <td>Nomor Order</td>
                                <td>
                                    <input type='text' class='form-control form-control-sm' id='nopo'
                                        name='nopo' placeholder='No. Order *' style='text-transform:uppercase'
                                        {{-- value={{ 'po' . $saplikasi->tahun . $saplikasi->bulan . sprintf('%05s', $saplikasi->nopo) }} --}}
                                        value="{{ str_contains($vdata['title'], 'Tambah') ? 'PO' . $saplikasi->tahun . sprintf('%02s', $saplikasi->bulan) . sprintf('%05s', $saplikasi->nopo + 1) : $po->nopo }}"
                                        {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }} readonly>
                                </td>
                            </tr>
                            <tr>
                                <td>Tanggal (M/D/Y)</td>
                                <td><input type='date' class='form-control form-control-sm' id='tglpo'
                                        name='tglpo'
                                        value="{{ str_contains($vdata['title'], 'Tambah') ? $tgl : $po->tglpo }}"
                                        autocomplete='off' required readonly>
                                </td>
                            </tr>
                            <tr>
                                <td>No. Referensi</td>
                                <td> <input type="text" class='form-control form-control-sm' id='noreferensi'
                                        name='noreferensi' size='50' autocomplete='off'
                                        value="{{ $po->noreferensi }}"
                                        {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}></td>
                            </tr>
                            <tr>
                                <td>Supplier</td>
                                <td>
                                    <div class='input-group'> <input type='text' class='form-control form-control-sm'
                                            style="width: 3em" id='kdsupplier' name='kdsupplier' autocomplete='off'
                                            value="{{ $po->kdsupplier }}"
                                            {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}>
                                        <input type="text" class='form-control form-control-sm' style="width: 13em"
                                            id='nmsupplier' name='nmsupplier' value="{{ $po->nmsupplier }}" readonly>
                                        <span class='input-group-btn'>
                                            <button type='button' id='src'
                                                class='btn btn-primary btn-sm carisupplier' onclick='carisupplier()'
                                                {{ str_contains($vdata['title'], 'Detail') ? 'disabled' : '' }}>Cari</button>
                                        </span>
                                </td>
                            </tr>
                            {{-- <tr>
                                <td>Sales</td>
                                <td>
                                    <div class='input-group'> <input type='text' class='form-control form-control-sm'
                                            id='kdsales' name='kdsales' autocomplete='off'
                                            value="{{ $po->kdsales }}"
                                            {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}>
                                        <input type="text" class='form-control form-control-sm' style="width: 10em"
                                            id='nmsales' name='nmsales' value="{{ $po->nmsales }}" readonly>
                                        <span class='input-group-btn'>
                                            <button type='button' id='src'
                                                class='btn btn-primary btn-sm carisales' onclick='carisales()'
                                                {{ str_contains($vdata['title'], 'Detail') ? 'disabled' : '' }}>Cari</button>
                                        </span>

                                    </div>
                                </td>
                                </tr> --}}
                            <tr>
                                <td>Jenis Order</td>
                                <td><select required id='jenis_order' name='jenis_order'
                                        class='form-control form-control-sm' style='width: 200x;'
                                        {{ str_contains($vdata['title'], 'Detail') ? 'disabled' : '' }}>
                                        <option value="NORMAL">NORMAL</option>
                                        <option value="URGENT">URGENT</option>
                                        <option value="LAIN">LAIN-LAIN</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>Biaya Lain</td>
                                <td> <input type="text" class='form-control form-control-sm' name='ket_biaya_lain'
                                        value="{{ $po->ket_biaya_lain }}"
                                        {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}> </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td> <input type="number" style="text-align:right" class='form-control form-control-sm'
                                        name='biaya_lain' id='biaya_lain' required onblur="hit_total()"
                                        value="{{ str_contains($vdata['title'], 'Tambah') ? 0 : $po->biaya_lain }}"
                                        {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}> </td>
                            </tr>
                            <tr>
                                <td>Tanggal Kirim<br>(M/D/Y)</td>
                                <td> <input type="date" class='form-control form-control-sm' name='tglkirim'
                                        value="{{ $po->tglkirim }}"
                                        {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}> </td>
                            </tr>
                        </table>
                    </div>
                    <div class='col-md-6'>
                        <table style=font-size:12px; class='table table-borderless table-sm table-hover'>
                            <tr>
                                <td>Cara Bayar
                                <td>
                                    {{-- <select required id='carabayar' name='carabayar'
                                        class='form-control form-control-sm' style='width: 200x;'
                                        {{ str_contains($vdata['title'], 'Detail') ? 'disabled' : '' }}>
                                        <option value="TUNAI">TUNAI</option>
                                        <option value="KARTU-KREDIT">KARTU-KREDIT</option>
                                        <option value="GIRO">GIRO</option>
                                        <option value="CEK">CEK</option>
                                    </select> --}}
                                    <select class="form-control" name="carabayar" id="carabayar"
                                        {{ str_contains($vdata['title'], 'Detail') ? 'disabled' : '' }}>
                                        <option value="">[Pilih Cara Bayar]</option>
                                        <?php
                                        $arrcarabayar = ['TUNAI', 'TRANSFER', 'KARTU-KREDIT', 'GIRO', 'CEK'];
                                        $jml_kata = count($arrcarabayar);
                                        for ($c = 0; $c < $jml_kata; $c += 1) {
                                            if ($arrcarabayar[$c] == $po->carabayar) {
                                                echo "<option value='$arrcarabayar[$c]' selected>$arrcarabayar[$c] </option>";
                                            } else {
                                                echo "<option value='$arrcarabayar[$c]'> $arrcarabayar[$c] </option>";
                                            }
                                        }
                                        echo '</select>';
                                        ?>
                                    </select>
                            <tr>
                                <td>Tempo (Hari)</td>
                                <td> <input type="number" class='form-control form-control-sm' name='tempo'
                                        id='tempo' value="{{ $po->tempo }}"
                                        {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}> </td>
                            </tr>
                            <tr>
                                <td>Tanggal Jatuh Tempo<br>(M/D/Y)</td>
                                <td> <input type="date" class='form-control form-control-sm' name='tgl_jt_tempo'
                                        id='tgl_jt_tempo' value="{{ $po->tgl_jt_tempo }}"
                                        {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}> </td>
                                </td>
                            </tr>
                            <tr>
                                <td>Subtotal</td>
                                <td> <input type="number" class='form-control form-control-sm'
                                        style="text-align:right;" name='subtotal' id='subtotalh'
                                        value="{{ str_contains($vdata['title'], 'Tambah') ? 0 : $po->subtotal }}"
                                        readonly>
                                </td>
                            </tr>
                            <tr>
                                <td>Total Sementara</td>
                                <td> <input type="number" class='form-control form-control-sm'
                                        style="text-align:right;" id='total_sementara' name='total_sementara'
                                        value="{{ str_contains($vdata['title'], 'Tambah') ? 0 : $po->total_sementara }}"
                                        readonly>
                                </td>
                            </tr>
                            <tr>
                                <td>PPn (%)</td>
                                <td> <input type="number" style="text-align:right"
                                        class='form-control form-control-sm' name='ppn' id='ppn'
                                        onkeyup="validAngka(this)" onblur="hit_total()"
                                        value="{{ str_contains($vdata['title'], 'Tambah') ? 0 : $po->ppn }}"
                                        {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}> </td>
                                </td>
                            </tr>
                            <tr>
                                <td>Materai</td>
                                <td> <input type="number" style="text-align:right"
                                        class='form-control form-control-sm' name='materai' id='materai'
                                        onkeyup="validAngka_no_titik(this)" onblur="hit_total()"
                                        value="{{ str_contains($vdata['title'], 'Tambah') ? 0 : $po->materai }}"
                                        {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}> </td>
                            </tr>
                            <tr>
                                <td>Total</td>
                                <td> <input type="number" style="text-align:right"
                                        class='form-control form-control-sm' name='total' id='total'
                                        value="{{ str_contains($vdata['title'], 'Tambah') ? 0 : $po->total }}"
                                        readonly></td>
                            </tr>
                            <tr>
                                <td>Keterangan</td>
                                <td>
                                    <textarea type='text' rows='2' class='form-control form-control-sm' id='kerangan' name='keterangan'
                                        autocomplete='off' {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}>{{ $po->keterangan }}</textarea>
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
                                            id="user" value="{{ $po->user }}" readonly>
                                    </td>
                                </tr>
                            @endif
                        </table>
                    </div>
                    @if (str_contains($vdata['title'], 'Detail'))
                        <div class='col-md-12'>
                            <table id='tbl-detail-po_1' style='font-size:13px;'
                                class='table table-striped table table-bordered' width='600px'>
                                <thead>
                                    <tr>
                                        <th width='30'>No.</th>
                                        <th width='120'>Kode Barang</th>
                                        <th width='300'>Nama Barang</th>
                                        <th width='70'>Satuan</th>
                                        <th>QTY</th>
                                        <th>Harga</th>
                                        <th width='40'>Disc</th>
                                        <th>Subtotal</th>
                                        <th width='80'>Aksi</th>
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

    function reload_total_detail_1() {
        $(function() {
            var vnopo = $("#nopo").val();
            $.ajax({
                type: "get",
                data: {
                    nopo: vnopo,
                },
                // dataType: "json",
                // url: "pototaldetail",
                url: `{{ route('pototaldetail') }}`,
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

    function reload_table_detail_1() {
        $(function() {
            var vnopo = $("#nopo").val();
            var table = $('#tbl-detail-po_1').DataTable({
                ajax: "{{ url('podajax') }}?nopo=" + vnopo,
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
                        name: 'kdsatuan',
                        render: function(data, type, row, meta) {
                            return `${row.kdsatuan}-${row.nmsatuan}`;
                        }
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
        $('#biaya_lain').on('keyup', function(e) {
            hit_total();
        })
        $('#ppn').on('keyup', function(e) {
            hit_total();
        })
        $('#materai').on('keyup', function(e) {
            hit_total();
        })
        $('#biaya_lain').on('blur', function(e) {
            hit_total();
        })
        $('#ppn').on('blur', function(e) {
            hit_total();
        })
        $('#materai').on('blur', function(e) {
            hit_total();
        })
        $('#tempo').on('keyup', function(e) {
            tgljttempo();
        })
        $('#tempo').on('blur', function(e) {
            tgljttempo();
        })

        $('.formpo').submit(function(e) {
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
                        //     window.location.href = '/po';
                        // });
                        // window.location = '/po';
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
                console.log('file not fount');
            }
        })
        // console.log(cari);
    })
</script>
