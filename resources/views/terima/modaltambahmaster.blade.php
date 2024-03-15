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
        <form action="{{ $action }}" method="post" class="formterima">
            @csrf
            @if ($terima->id)
                @method('get')
            @endif
            <?php $tgl = $saplikasi->tgl_berikutnya; ?>
            <input type="hidden" class="form-control-sm" name="id" id="id" value="{{ $terima->id }}">
            <input type="hidden" class="form-control-sm" name="username" id="username"
                value="{{ $session->get('username') }}">
            <input type="hidden" class="form-control-sm" name="noterimalama" id="noterimalama"
                value="{{ $terima->noterima }}">
            <div class="modal-body">
                <div class="row">
                    <div class='col-md-6'>
                        <table style=font-size:12px; class='table table-borderless table-sm table-hover'>
                            <tr>
                                <td>Nomor Penerimaan </td>
                                <td>
                                    <input type='text' class='form-control form-control-sm' id='noterima'
                                        name='noterima' placeholder='No. Order *' style='text-transform:uppercase'
                                        {{-- value={{ 'terima' . $saplikasi->tahun . $saplikasi->bulan . sprintf('%05s', $saplikasi->noterima) }} --}}
                                        value="{{ str_contains($vdata['title'], 'Tambah') ? 'TB' . $saplikasi->tahun . sprintf('%02s', $saplikasi->bulan) . sprintf('%05s', $saplikasi->noterima + 1) : $terima->noterima }}"
                                        readonly>
                                </td>
                            </tr>
                            <tr>
                                <td>Tanggal (M/D/Y)</td>
                                <td><input type='date' class='form-control form-control-sm' id='tglterima'
                                        name='tglterima'
                                        value="{{ str_contains($vdata['title'], 'Tambah') ? $tgl : $terima->tglterima }}"
                                        autocomplete='off' required readonly>
                                </td>
                            </tr>
                            <tr>
                                <td>No. Referensi</td>
                                <td> <input type="text" class='form-control form-control-sm' id='noreferensi'
                                        name='noreferensi' size='50' autocomplete='off'
                                        value="{{ $terima->noreferensi }}"
                                        {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}></td>
                            </tr>
                            {{-- <tr>
                                <td>No. Invoice</td>
                                <td> <input type="text" class='form-control form-control-sm' id='noinvoice'
                                        name='noinvoice' autocomplete='off' value="{{ $terima->noinvoice }}"
                                        {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}>
                                </td>
                            </tr>
                            <tr>
                                <td>Tgl. Invoice</td>
                                <td> <input type="date" class='form-control form-control-sm' id='tglinvoice'
                                        name='tglinvoice' autocomplete='off' value="{{ $terima->tglinvoice }}"
                                        {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}>
                                </td>
                            </tr> --}}
                            <tr>
                                <td>Jenis Transaksi</td>
                                <td><input type="hidden" class="form-control form-control-sm" name="kdjntranse"
                                        id="kdjntranse" value="<?= $terima['kdjntrans'] ?>">
                                    <select class="form-control form-control-sm" name='kdjntrans' id="kdjntrans"
                                        {{ str_contains($vdata['title'], 'Detail') ? 'disabled' : '' }}>
                                        <option value="">[Pilih Satuan]</option>
                                    </select>
                                </td>
                            </tr>
                            {{-- <tr>
                                <td>Jenis Order</td>
                                <td><select required id='jenis_order' name='jenis_order'
                                        class='form-control form-control-sm' style='width: 200x;'
                                        {{ str_contains($vdata['title'], 'Detail') ? 'disabled' : '' }}>
                                        <option value="URGENT">URGENT</option>
                                        <option value="NORMAL">NORMAL</option>
                                        <option value="LAIN">LAIN-LAIN</option>
                                    </select>
                                </td>
                            </tr> --}}
                            <tr>
                                <td>Biaya Lain</td>
                                <td> <input type="text" class='form-control form-control-sm' name='ket_biaya_lain'
                                        value="{{ $terima->ket_biaya_lain }}"
                                        {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}> </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td> <input type="number" style="text-align:right" class='form-control form-control-sm'
                                        name='biaya_lain' id='biaya_lain' required onblur="hit_total()"
                                        value="{{ str_contains($vdata['title'], 'Tambah') ? 0 : $terima->biaya_lain }}"
                                        {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}> </td>
                            </tr>
                            <tr>
                                <td>Tanggal Kirim<br>(M/D/Y)</td>
                                <td> <input type="date" class='form-control form-control-sm' name='tglkirim'
                                        value="{{ $terima->tglkirim }}"
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
                                        $arrcarabayar = ['TUNAI', 'KARTU-KREDIT', 'GIRO', 'CEK'];
                                        $jml_kata = count($arrcarabayar);
                                        for ($c = 0; $c < $jml_kata; $c += 1) {
                                            if ($arrcarabayar[$c] == $terima->carabayar) {
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
                                        id='tempo' value="{{ $terima->tempo }}"
                                        {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}> </td>
                            </tr>
                            <tr>
                                <td>Tanggal Jatuh Tempo<br>(M/D/Y)</td>
                                <td> <input type="date" class='form-control form-control-sm' name='tgl_jt_tempo'
                                        id='tgl_jt_tempo' value="{{ $terima->tgl_jt_tempo }}"
                                        {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}> </td>
                                </td>
                            </tr>
                            <tr>
                                <td>Subtotal</td>
                                <td> <input type="number" class='form-control form-control-sm'
                                        style="text-align:right;" name='subtotal' id='subtotalh'
                                        value="{{ str_contains($vdata['title'], 'Tambah') ? 0 : $terima->subtotal }}"
                                        readonly>
                                </td>
                            </tr>
                            <tr>
                                <td>Total Sementara</td>
                                <td> <input type="number" class='form-control form-control-sm'
                                        style="text-align:right;" id='total_sementara' name='total_sementara'
                                        value="{{ str_contains($vdata['title'], 'Tambah') ? 0 : $terima->total_sementara }}"
                                        readonly>
                                </td>
                            </tr>
                            <tr>
                                <input type='hidden' id='gnppn' value='<?= $gnppn ?>'>
                                <td>PPn (%) <input type='checkbox' id='cekboxppn' name='lppn'
                                        {{ (str_contains($vdata['title'], 'Tambah') ? 'checked' : $terima->lppn == 'Y') ? 'checked' : '' }}
                                        {{ str_contains($vdata['title'], 'Detail') ? 'disabled' : '' }}>
                                </td>
                                <td> <input type="number" class='form-control' name='ppn' id='ppn'
                                        style="text-align:right"
                                        value="{{ str_contains($vdata['title'], 'Tambah') ? $gnppn : $terima->ppn }}"
                                        size='50' onkeyup="hit_total()" onblur="hit_total()"
                                        {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}>
                                </td>
                            </tr>
                            <tr>
                                <td>Materai</td>
                                <td> <input type="number" style="text-align:right"
                                        class='form-control form-control-sm' name='materai' id='materai'
                                        onkeyup="validAngka_no_titik(this)" onblur="hit_total()"
                                        value="{{ str_contains($vdata['title'], 'Tambah') ? 0 : $terima->materai }}"
                                        {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}> </td>
                            </tr>
                            <tr>
                                <td>Total</td>
                                <td> <input type="number" style="text-align:right"
                                        class='form-control form-control-sm' name='total' id='total'
                                        value="{{ str_contains($vdata['title'], 'Tambah') ? 0 : $terima->total }}"
                                        readonly></td>
                            </tr>
                            @if (str_contains($vdata['title'], 'Detail'))
                                <tr>
                                    <td>Sudah Bayar</td>
                                    <td><input type="number" class="form-control form-control-sm"
                                            style="text-align:right" name="sudahbayar" id="sudahbayar"
                                            value="{{ $terima->sudahbayar }}" readonly>
                                    </td>
                                </tr>
                                <tr>

                                    <td>Kurang Bayar</td>
                                    <td><input type="number" class="form-control form-control-sm"
                                            style="text-align:right" name="kurangbayar" id="kurangbayar"
                                            value="{{ $terima->kurangbayar }}" readonly>
                                    </td>
                                </tr>
                            @endif
                            <tr>
                                <td>Keterangan</td>
                                <td>
                                    <textarea type='text' rows='2' class='form-control form-control-sm' id='kerangan' name='keterangan'
                                        autocomplete='off' {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}>{{ $terima->keterangan }}</textarea>
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
                                            id="user" value="{{ $terima->user }}" readonly>
                                    </td>
                                </tr>
                            @endif
                        </table>
                    </div>
                    @if (str_contains($vdata['title'], 'Detail'))
                        <div class='col-md-12'>
                            <table id='tbl-detail-terima_1' style='font-size:13px;'
                                class='table table-striped table table-bordered' width='600px'>
                                <thead>
                                    <tr>
                                        <th width='30'>No.</th>
                                        <th width='13%'>Kode Barang</th>
                                        <th width='35%'>Nama Barang</th>
                                        <th width='70'>Satuan</th>
                                        <th>QTY</th>
                                        <th>Harga Beli</th>
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

    function reload_total_detail_1() {
        $(function() {
            var vnoterima = $("#noterima").val();
            $.ajax({
                type: "get",
                data: {
                    noterima: vnoterima,
                },
                // dataType: "json",
                // url: "sototaldetail",
                url: `{{ route('terimatotaldetail') }}`,
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
            var vnoterima = $("#noterima").val();
            var table = $('#tbl-detail-terima_1').DataTable({
                ajax: "{{ url('terimadajax') }}?noterima=" + vnoterima,
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
                        targets: [0, 7],
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

    var carikdjntrans = $("#kdjntranse").val();
    $.ajax({
        url: "<?= url('ambildatatbjntranst') ?>",
        dataType: "json",
        data: {
            'kdjntrans': carikdjntrans
        },
        success: function(response) {
            if (response.data) {
                $('#kdjntrans').html(response.data);
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
        }
    })

    // tampildatatbjntrans();
    $('#kdjntrans').focusin(function(e) {
        $.ajax({
            url: "<?= url('ambildatatbjntranst') ?>",
            dataType: "json",
            success: function(response) {
                if (response.data) {
                    $('#kdjntrans').html(response.data);
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        })
    });


    $(document).ready(function() {
        reload_table_detail_1();
        reload_total_detail_1();
        // $('#cekboxppn').on('ifChanged', function(event) {
        $('#cekboxppn').on('click', function(event) {
            let ppn = document.getElementById('gnppn').value;
            if (this.checked) // if changed state is "CHECKED"
            {
                $('#ppn').val(ppn);
            } else {
                $('#ppn').val('0.00');
            }
            hit_total()
        })
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

        $('.formterima').submit(function(e) {
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
                        //     window.location.href = '/terima';
                        // });
                        // window.location = '/terima';
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
    function carijntrans() {
        $.ajax({
            method: "GET",
            url: "carijntrans",
            dataType: "json",
            success: function(response) {
                $('#modalcarijntrans').html(response.body)
                $("#modalcarijntrans").modal('show');
            }
        })
    }
    // $('#kdjntrans').on('blur', function(e) {
    //     // alert(1)
    //     let cari = $(this).val();
    //     $.ajax({
    //         url: 'repljntrans',
    //         type: 'get',
    //         data: {
    //             kode: cari
    //         },
    //         success: function(response) {
    //             let data_response = JSON.parse(response);
    //             // alert(data_response.kdjntrans)
    //             if (data_response.kdjntrans === "") {
    //                 $('#kdjntrans').val('');
    //                 $('#nmjntrans').val('');
    //                 // carijntrans();
    //                 return;
    //             }
    //             $('#nmjntrans').val(data_response['nmjntrans']);
    //         },
    //         error: function() {
    //             conpole.log('file not fount');
    //         }
    //     })
    //     // conpole.log(cari);
    // })
</script>
