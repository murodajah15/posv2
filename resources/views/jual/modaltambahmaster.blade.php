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
        <form action="{{ $action }}" method="post" class="formjual">
            @csrf
            @if ($jual->id)
                @method('get')
            @endif
            <?php $tgl = $saplikasi->tgl_berikutnya; ?>
            <input type="hidden" class="form-control-sm" name="id" id="id" value="{{ $jual->id }}">
            <input type="hidden" class="form-control-sm" name="username" id="username"
                value="{{ $session->get('username') }}">
            <input type="hidden" class="form-control-sm" name="nojuallama" id="nojuallama" value="{{ $jual->nojual }}">
            <div class="modal-body">
                <div class="row">
                    <div class='col-md-6'>
                        <table style=font-size:12px; class='table table-borderless table-sm table-hover'>
                            <tr>
                                <td>Nomor Order </td>
                                <td>
                                    <input type='text' class='form-control form-control-sm' id='nojual'
                                        name='nojual' placeholder='No. Order *' style='text-transform:uppercase'
                                        {{-- value={{ 'jual' . $saplikasi->tahun . $saplikasi->bulan . sprintf('%05s', $saplikasi->nojual) }} --}}
                                        value="{{ str_contains($vdata['title'], 'Tambah') ? 'JL' . $saplikasi->kd_perusahaan . $saplikasi->tahun . sprintf('%02s', $saplikasi->bulan) . sprintf('%04s', $saplikasi->nojual + 1) : $jual->nojual }}"
                                        readonly>
                                </td>
                            </tr>
                            <tr>
                                <td>Tanggal (M/D/Y)</td>
                                <td><input type='date' class='form-control form-control-sm' id='tgljual'
                                        name='tgljual'
                                        value="{{ str_contains($vdata['title'], 'Tambah') ? $tgl : $jual->tgljual }}"
                                        autocomplete='off' required readonly>
                                </td>
                            </tr>
                            <tr>
                                <td>No. Referensi</td>
                                <td> <input type="text" class='form-control form-control-sm' id='noreferensi'
                                        name='noreferensi' size='50' autocomplete='off'
                                        value="{{ $jual->noreferensi }}"
                                        {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}></td>
                            </tr>
                            <tr>
                                <td>No. PO Customer</td>
                                <td> <input type="text" class='form-control form-control-sm' id='nopo_customer'
                                        name='nopo_customer' autocomplete='off' value="{{ $jual->nopo_customer }}"
                                        {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}>
                                </td>
                            </tr>
                            <tr>
                                <td>Tgl. PO Customer</td>
                                <td> <input type="date" class='form-control form-control-sm' id='tglpo_customer'
                                        name='tglpo_customer' autocomplete='off' value="{{ $jual->tglpo_customer }}"
                                        {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}>
                                </td>
                            </tr>
                            <tr>
                                <td>Customer</td>
                                <td>
                                    <div class='input-group'> <input type='text' class='form-control form-control-sm'
                                            id='kdcustomer' name='kdcustomer' autocomplete='off'
                                            value="{{ $jual->kdcustomer }}"
                                            {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}>
                                        <input type="text" class='form-control form-control-sm' style="width: 10em"
                                            id='nmcustomer' name='nmcustomer' value="{{ $jual->nmcustomer }}" readonly>
                                        <span class='input-group-btn'>
                                            <button type='button' id='src'
                                                class='btn btn-primary btn-sm caricustomer' onclick='caricustomer()'
                                                {{ str_contains($vdata['title'], 'Detail') ? 'disabled' : '' }}>Cari</button>
                                        </span>
                                </td>
                            </tr>
                            <tr>
                                <td>Sales</td>
                                <td>
                                    <div class='input-group'> <input type='text' class='form-control form-control-sm'
                                            id='kdsales' name='kdsales' autocomplete='off'
                                            value="{{ $jual->kdsales }}"
                                            {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}>
                                        <input type="text" class='form-control form-control-sm' style="width: 10em"
                                            id='nmsales' name='nmsales' value="{{ $jual->nmsales }}" readonly>
                                        <span class='input-group-btn'>
                                            <button type='button' id='src'
                                                class='btn btn-primary btn-sm carisales' onclick='carisales()'
                                                {{ str_contains($vdata['title'], 'Detail') ? 'disabled' : '' }}>Cari</button>
                                        </span>

                                    </div>
                                </td>
                                {{-- <td><select required id='kdsales' name='kdsales' class='form-control form-control-sm'
                                        style='width: 200x;'
                                        {{ str_contains($vdata['title'], 'Detail') ? 'disabled' : '' }}>
                                        @if (str_contains($vdata['title'], 'Detail'))
                                            <input type="text" class='form-control form-control-sm' id='kdsales'
                                                name='kdsales' size='50' autocomplete='off' autofocus='autofocus'
                                                value="{{ $jual->kdsales }}" readonly>
                                            <input type="text" class='form-control form-control-sm' id='nmsales'
                                                name='nmsales' size='50' autocomplete='off' autofocus='autofocus'
                                                value="{{ $jual->nmsales }}" readonly>
                                        @else
                                            <option value="">[Pilih Sales]</option>
                                            @foreach ($tbsales as $row)
                                                <option value="{{ $row->kode }}">{{ $row->kode }} -
                                                    {{ $row->nama }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </td>
                                <td><input type='text' class='form-control form-control-sm' id='nmsales'
                                        name='nmsales' value="{{ $jual->nmsales }}" readonly></td> --}}

                            <tr>
                                <td>Jenis Order
                                <td><select required id='jenis_order' name='jenis_order'
                                        class='form-control form-control-sm' style='width: 200x;'
                                        {{ str_contains($vdata['title'], 'Detail') ? 'disabled' : '' }}>
                                        <option value="NORMAL">NORMAL</option>
                                        <option value="URGENT">URGENT</option>
                                        <option value="LAIN">LAIN-LAIN</option>
                                    </select>
                            <tr>
                                <td>Biaya Lain</td>
                                <td> <input type="text" class='form-control form-control-sm' name='ket_biaya_lain'
                                        value="{{ $jual->ket_biaya_lain }}"
                                        {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}> </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td> <input type="number" style="text-align:right"
                                        class='form-control form-control-sm' name='biaya_lain' id='biaya_lain'
                                        required onblur="hit_total()"
                                        value="{{ str_contains($vdata['title'], 'Tambah') ? 0 : $jual->biaya_lain }}"
                                        {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}> </td>
                            </tr>
                            <tr>
                                <td>Tanggal Kirim<br>(M/D/Y)</td>
                                <td> <input type="date" class='form-control form-control-sm' name='tglkirim'
                                        value="{{ $jual->tglkirim }}"
                                        {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}> </td>
                            </tr>
                            <tr>
                                <td>Kurir</td>
                                <td>
                                    <div class='input-group'> <input type='text'
                                            class='form-control form-control-sm' id='kdkurir' name='kdkurir'
                                            autocomplete='off' value="{{ $jual->kdkurir }}" readonly>
                                        <input type="text" class='form-control form-control-sm'
                                            style="width: 10em" id='nmkurir' name='nmkurir'
                                            value="{{ $jual->nmkurir }}" readonly>
                                    </div>
                                </td>
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
                                        <option value="TRANSFER">TRANSFER</option>
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
                                            if ($arrcarabayar[$c] == $jual->carabayar) {
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
                                        id='tempo' value="{{ $jual->tempo }}"
                                        {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}> </td>
                            </tr>
                            <tr>
                                <td>Tanggal Jatuh Tempo<br>(M/D/Y)</td>
                                <td> <input type="date" class='form-control form-control-sm' name='tgl_jt_tempo'
                                        id='tgl_jt_tempo' value="{{ $jual->tgl_jt_tempo }}"
                                        {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}> </td>
                                </td>
                            </tr>
                            <tr>
                                <td>Subtotal</td>
                                <td> <input type="number" class='form-control form-control-sm'
                                        style="text-align:right;" name='subtotal' id='subtotalh'
                                        value="{{ str_contains($vdata['title'], 'Tambah') ? 0 : $jual->subtotal }}"
                                        readonly>
                                </td>
                            </tr>
                            <tr>
                                <td>Total Sementara</td>
                                <td> <input type="number" class='form-control form-control-sm'
                                        style="text-align:right;" id='total_sementara' name='total_sementara'
                                        value="{{ str_contains($vdata['title'], 'Tambah') ? 0 : $jual->total_sementara }}"
                                        readonly>
                                </td>
                            </tr>
                            <tr>
                                <input type='hidden' id='gnppn' value='<?= $gnppn ?>'>
                                <td>PPn (%) <input type='checkbox' id='cekboxppn' name='lppn'
                                        {{ (str_contains($vdata['title'], 'Tambah') ? 'checked' : $jual->lppn == 'Y') ? 'checked' : '' }}
                                        {{ str_contains($vdata['title'], 'Detail') ? 'disabled' : '' }}>
                                </td>
                                {{-- <td> <input type="number" class='form-control' name='ppn' id='ppn'
                                        style="text-align:right"
                                        value="{{ str_contains($vdata['title'], 'Tambah') ? $gnppn : $jual->ppn }}"
                                        size='50' onkeyup="hit_total()" onblur="hit_total()"
                                        {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}>
                                </td> --}}
                                <td>
                                    <div class='input-group'> <input type='number'
                                            class='form-control form-control-sm' id='ppn' name='ppn'
                                            autocomplete='off'
                                            value="{{ str_contains($vdata['title'], 'Tambah') ? $gnppn : $jual->ppn }}"
                                            {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}>
                                        <input type="number" class='form-control form-control-sm'
                                            style="text-align:right; width:10em" id='nrpppn' name='nrpppn'
                                            readonly>
                                </td>
                            </tr>
                            <tr>
                                <td>Materai</td>
                                <td> <input type="number" style="text-align:right"
                                        class='form-control form-control-sm' name='materai' id='materai'
                                        onkeyup="validAngka_no_titik(this)" onblur="hit_total()"
                                        value="{{ str_contains($vdata['title'], 'Tambah') ? 0 : $jual->materai }}"
                                        {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}> </td>
                            </tr>
                            <tr>
                                <td>Total</td>
                                <td> <input type="number" style="text-align:right"
                                        class='form-control form-control-sm' name='total' id='total'
                                        value="{{ str_contains($vdata['title'], 'Tambah') ? 0 : $jual->total }}"
                                        readonly></td>
                            </tr>
                            @if (str_contains($vdata['title'], 'Detail'))
                                <tr>
                                    <td>Sudah Bayar <button type="button" class="btn btn-secondary btn-sm"
                                            id='tampilpembayaran'>Pembayaran</button></td>
                                    <td><input type="number" class="form-control form-control-sm"
                                            style="text-align:right" name="sudahbayar" id="sudahbayar"
                                            value="{{ $jual->sudahbayar }}" readonly>
                                    </td>
                                </tr>
                                <tr>

                                    <td>Kurang Bayar</td>
                                    <td><input type="number" class="form-control form-control-sm"
                                            style="text-align:right" name="kurangbayar" id="kurangbayar"
                                            value="{{ $jual->kurangbayar }}" readonly>
                                    </td>
                                </tr>
                            @endif
                            <tr>
                                <td>Keterangan</td>
                                <td>
                                    <textarea type='text' rows='2' class='form-control form-control-sm' id='kerangan' name='keterangan'
                                        autocomplete='off' {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}>{{ $jual->keterangan }}</textarea>
                                </td>
                            </tr>

                            {{-- <div class="row"> --}}
                            <?php
                if (strpos($vdata['title'], 'Tambah') !== false) {
                } else {
                ?>
                            <tr>
                                <td>
                                    <label for="nama" class="label mb-1 mt-3">User</label>
                                </td>
                                <td>
                                    <input type="text" class="form-control form-control-sm" name="user"
                                        id="user" value="{{ $jual->user }}" readonly>
                                </td>
                            </tr> <?php } ?>
                        </table>
                    </div>
                    @if (str_contains($vdata['title'], 'Detail'))
                        <div class='col-md-12'>
                            <table id='tbl-detail-jual_1' style='font-size:13px;'
                                class='table table-striped table table-bordered' width='600px'>
                                <thead>
                                    <tr>
                                        <th width='30'>No.</th>
                                        <th width='100'>Kode Barang</th>
                                        <th>Nama Barang</th>
                                        <th width='70'>Satuan</th>
                                        <th width='10'>MP</th>
                                        <th>QTY</th>
                                        <th>Harga</th>
                                        <th width='40'>Disc</th>
                                        <th>Subtotal</th>
                                        <th>Aksi</th>
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

    hitppn();
    // reload_table_detail();
    // reload_total_detail();

    function reload_total_detail_1() {
        $(function() {
            var vnojual = $("#nojual").val();
            $.ajax({
                type: "get",
                data: {
                    nojual: vnojual,
                },
                // dataType: "json",
                // url: "sototaldetail",
                url: `{{ route('jualtotaldetail') }}`,
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
            var vnojual = $("#nojual").val();
            var table = $('#tbl-detail-jual_1').DataTable({
                ajax: "{{ url('jualdajax') }}?nojual=" + vnojual,
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
                        targets: [0, 9],
                    },

                    {
                        orderable: true,
                        className: 'dt-body-right',
                        targets: [5, 6, 7, 8],
                    },
                    {
                        orderable: true,
                        className: 'dt-body-center',
                        targets: [4],
                    }
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
                        orderable: true,
                        // data: 'aktif',
                        // name: 'aktif'
                        'render': function(data, type, row) {
                            if (row.multiprc == 'Y') {
                                return `<input type="checkbox" checked disabled>`;
                            } else {
                                return `<input type="checkbox" disabled>`;
                            }
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
            hitppn();
        })
        $('#biaya_lain').on('keyup', function(e) {
            hit_total();
            hitppn();
        })
        $('#ppn').on('keyup', function(e) {
            hit_total();
            hitppn();
        })
        $('#materai').on('keyup', function(e) {
            hit_total();
        })
        $('#biaya_lain').on('blur', function(e) {
            hit_total();
            hitppn();
        })
        $('#ppn').on('blur', function(e) {
            hit_total();
            hitppn();
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

        $('.formjual').submit(function(e) {
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
                        //     window.location.href = '/jual';
                        // });
                        // window.location = '/jual';
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
    function caricustomer() {
        $.ajax({
            method: "GET",
            url: "caricustomer",
            dataType: "json",
            success: function(response) {
                $('#modalcaricustomer').html(response.body)
                $("#modalcaricustomer").modal('show');
            }
        })
    }
    $('#kdcustomer').on('blur', function(e) {
        let cari = $(this).val();
        $.ajax({
            url: 'replcustomer',
            type: 'get',
            data: {
                kode: cari
            },
            success: function(response) {
                let data_response = JSON.parse(response);
                // alert(data_response.kdcustomer)
                if (data_response.kdcustomer === "") {
                    $('#kdcustomer').val('');
                    $('#nmcustomer').val('');
                    // caricustomer();
                    return;
                }
                $('#nmcustomer').val(data_response['nmcustomer']);
            },
            error: function() {
                console.log('file not fount');
            }
        })
        // console.log(cari);
    })

    // Nampilin list data pilihan ===================
    function carisales() {
        $.ajax({
            method: "GET",
            url: "carisales",
            dataType: "json",
            success: function(response) {
                $('#modalcarisales').html(response.body)
                $("#modalcarisales").modal('show');
            }
        })
    }
    $('#kdsales').on('blur', function(e) {
        let cari = $(this).val();
        $.ajax({
            url: 'replsales',
            type: 'get',
            data: {
                kode: cari
            },
            success: function(response) {
                let data_response = JSON.parse(response);
                // alert(data_response.kdsales)
                if (data_response.kdsales === "") {
                    $('#kdsales').val('');
                    $('#nmsales').val('');
                    // carisales();
                    return;
                }
                $('#nmsales').val(data_response['nmsales']);
            },
            error: function() {
                console.log('file not fount');
            }
        })
        // console.log(cari);
    })

    $('#tampilpembayaran').on('click', function(e) {
        let cari = document.getElementById('nojual').value
        $.ajax({
            method: "GET",
            url: "tampilpembayaran",
            dataType: "json",
            data: {
                cari: cari
            },
            success: function(response) {
                $('#modaltampilpembayaran').html(response.body)
                $("#modaltampilpembayaran").modal('show');
            }
        })
    })

    function hitppn() {
        document.getElementById('nrpppn').value = document.getElementById('total_sementara').value *
            (document.getElementById('ppn').value / 100);
    }
</script>
