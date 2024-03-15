<?php
$session = session();
// var_dump($vdata);
?>
<!-- Modal -->
<div class="modal-dialog" style="max-width: 100%;">
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
        <form action="{{ $action }}" method="post" class="formtbcustomer">
            @csrf
            @if ($tbcustomer->id)
                @method('put')
            @endif
            <div class="modal-body">
                <input type="hidden" class="form-control-sm" name="username" id="username"
                    value="{{ $session->get('username') }}">
                <input type="hidden" class="form-control-sm" name="kodelama" id="kodelama"
                    value="{{ $tbcustomer->kode }}">
                <div class="tab-content">
                    <div class="tab-pane fade in active" id="data">
                        <div class='col-md-12'>
                            <div class="row">
                                <div class='col-md-6'>
                                    <table style=font-size:12px; class='table table-borderless table-sm table-hover'>
                                        <div class="col-md-6 mb-2">
                                            <td width="100">Kode</td>
                                            <td><input type="text" class="form-control form-control-sm"
                                                    name="kode" id="kode" value="{{ $tbcustomer->kode }}"
                                                    {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}
                                                    {{ str_contains($vdata['title'], 'Detail') ? '' : 'autofocus' }}>
                                            </td>
                                            <tr>
                                                <td>Jenis
                                                <td>
                                                    <select class="form-control form-control-sm" name="kelompok"
                                                        id="kelompok"
                                                        {{ str_contains($vdata['title'], 'Detail') ? 'disabled' : '' }}>
                                                        {{-- <option value="">[Pilih Jenis HPP]</option> --}}
                                                        <?php
                                                        $arrkelompok = ['Mr.', 'Ms.', 'Mrs.', 'Company'];
                                                        $jml_kata = count($arrkelompok);
                                                        for ($c = 0; $c < $jml_kata; $c += 1) {
                                                            if ($arrkelompok[$c] == $tbcustomer->kelompok) {
                                                                echo "<option value='$arrkelompok[$c]' selected>$arrkelompok[$c] </option>";
                                                            } else {
                                                                echo "<option value='$arrkelompok[$c]'> $arrkelompok[$c] </option>";
                                                            }
                                                        }
                                                        echo '</select>';
                                                        ?>
                                            <tr>
                                                <td>Nama</td>
                                                <td><input type="text" class="form-control form-control-sm"
                                                        name="nama" id="nama" value="{{ $tbcustomer->nama }}"
                                                        {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Alamat</td>
                                                <td>
                                                    <textarea rows='3' class='form-control form-control-sm' name='alamat' id='alamat'
                                                        {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}>{{ $tbcustomer->alamat }}</textarea>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Kota</td>
                                                <td> <input type='text' class='form-control form-control-sm'
                                                        name='kota' id='kota' value="{{ $tbcustomer->kota }}"
                                                        {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Kode Pos</td>
                                                <td> <input type='text' class='form-control form-control-sm'
                                                        name='kodepos' id='kodepos'
                                                        value="{{ $tbcustomer->kodepos }}"
                                                        {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Telp</td>
                                                <td> <input type='text' class='form-control form-control-sm'
                                                        name='telp1' value="{{ $tbcustomer->telp1 }}"
                                                        {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td><input type='text' class='form-control form-control-sm'
                                                        name='telp2' value="{{ $tbcustomer->telp2 }}"
                                                        {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Agama
                                                <td><select id='agama' name='agama'
                                                        class='form-control form-control-sm' style='width: 200x;'>
                                                        @foreach ($tbagama as $row)
                                                            <option name="nama" value={{ $row->nama }}>
                                                                {{ $row->nama }}
                                                            </option>' {{ $row->nama }}
                                                        @endforeach
                                                </td>
                                            <tr>
                                                <td>Tanggal Lahir (M-D-Y)</td>
                                                <td> <input type='date' class='form-control form-control-sm'
                                                        name='tgl_lahir' value="{{ $tbcustomer->tgl_lahir }}"
                                                        {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Alamat KTP<br><input button type='Button'
                                                        class='btn btn-success btn-sm' value='Salin'
                                                        onClick='salin_alamat_ktp()'
                                                        {{ str_contains($vdata['title'], 'Detail') ? 'disabled' : '' }}>
                                                </td>
                                                <td>
                                                    <textarea rows='3' class='form-control form-control-sm' name='alamat_ktp' id='alamat_ktp'
                                                        {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}> {{ $tbcustomer->alamat_ktp }}
                                                </textarea>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Kota KTP</td>
                                                <td> <input type='text' class='form-control form-control-sm'
                                                        name='kota_ktp' id='kota_ktp'
                                                        value="{{ $tbcustomer->kota_ktp }}"
                                                        {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}>
                                                </td>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Kode Pos KPT</td>
                                                <td> <input type='text' class='form-control form-control-sm'
                                                        name='kodepos_ktp' id='kodepos_ktp'
                                                        value="{{ $tbcustomer->kodepos_ktp }}"
                                                        {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}>
                                                </td>
                                            </tr>
                                    </table>
                                </div>
                                <div class='col-md-6'>
                                    <table style=font-size:12px; class='table table-borderless table-sm table-hover'>
                                        <tr>
                                            <td width="100">Alamat Kantor<br><input button type='Button'
                                                    class='btn btn-success btn-sm' value='Salin'
                                                    onClick='salin_alamat_ktr()'
                                                    {{ str_contains($vdata['title'], 'Detail') ? 'disabled' : '' }}>
                                            </td>
                                            <td>
                                                <textarea rows='3' class='form-control form-control-sm' name='alamat_ktr' id='alamat_ktr'
                                                    {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}>{{ $tbcustomer->alamat_ktr }}</textarea>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Kota Kantor</td>
                                            <td> <input type='text' class='form-control form-control-sm'
                                                    name='kota_ktr' id='kota_ktr'
                                                    value="{{ $tbcustomer->kota_ktr }}"
                                                    {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Kode Pos Kantor</td>
                                            <td> <input type='text' class='form-control form-control-sm'
                                                    name='kodepos_ktr' id='kodepos_ktr'
                                                    value="{{ $tbcustomer->kodepos_ktr }}"
                                                    {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Telp Kantor</td>
                                            <td> <input type='text' class='form-control form-control-sm'
                                                    name='telp1_ktr' value="{{ $tbcustomer->telp1_ktr }}"
                                                    {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                            <td> <input type='text' class='form-control form-control-sm'
                                                    name='telp2_ktr' value="{{ $tbcustomer->telp2_ktr }}"
                                                    {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>NPWP</td>
                                            <td> <input type='text' class='form-control form-control-sm'
                                                    name='npwp'value="{{ $tbcustomer->npwp }}"
                                                    {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Nama NPWP<br><input button type='Button'
                                                    class='btn btn-success btn-sm' value='Salin'
                                                    onClick='salin_alamat_npwp()'
                                                    {{ str_contains($vdata['title'], 'Detail') ? 'disabled' : '' }}>
                                            </td>
                                            <td> <input type='text' class='form-control form-control-sm'
                                                    name='nama_npwp' id='nama_npwp'
                                                    value="{{ $tbcustomer->nama_npwp }}"
                                                    {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Alamat NPWP</td>
                                            <td>
                                                <textarea rows='3' class='form-control form-control-sm' name='alamat_npwp' id='alamat_npwp'
                                                    {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}>{{ $tbcustomer->kota_ktr }}</textarea>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Contact Person</td>
                                            <td> <input type='text' class='form-control form-control-sm'
                                                    name='contact_person_rmh' id='contact_person_rmh'
                                                    value="{{ $tbcustomer->contact_person_rmh }}"
                                                    {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Maksimum Piutang</td>
                                            <td> <input type='number' class='form-control form-control-sm'
                                                    name='mak_piutang' value="{{ $tbcustomer->mak_piutang }}"
                                                    {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Kelompok Customer</td>
                                            <td>
                                                <div class='input-group'> <input type='text'
                                                        class='form-control form-control-sm' id='kdklpcust'
                                                        name='kdklpcust' size='50' autocomplete='off'
                                                        value="{{ $tbcustomer->kdklpcust }}"
                                                        {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}>
                                                    <span class='input-group-btn'>
                                                        <button type='button' id='src'
                                                            class='btn btn-primary btn-sm'
                                                            onclick='cari_data_klpcust()'
                                                            {{ str_contains($vdata['title'], 'Detail') ? 'disabled' : '' }}>Cari</button>
                                                    </span>
                                            </td>
                                        <tr>
                                            <td></td>
                                            <td> <input type="text" class='form-control form-control-sm'
                                                    id='nmklpcust' name='nmklpcust' size='50'
                                                    value="{{ $tbcustomer->nmklpcust }}"
                                                    {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}
                                                    readonly>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Tempo Piutang (Hari)</td>
                                            <td> <input type="number" class='form-control form-control-sm'
                                                    id='tempo' name='tempo' size='50'
                                                    value="{{ $tbcustomer->tempo }}"
                                                    {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}>
                                            </td>
                                        </tr>
                                        <?php
                                    if (strpos($vdata['title'], 'Tambah') !== false) {
                                    } else {
                                    ?>
                                        <tr>
                                            <td>User</td>
                                            <td> <input type='text' class='form-control form-control-sm'
                                                    name='user' id='user' value="{{ $tbcustomer->user }}"
                                                    readonly>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                        {{-- <tr>
                                    <td>Aktif</td>
                                    <td> <input type='checkbox' name='aktif'
                                            {{ (str_contains($vdata['title'], 'Tambah') ? 'checked' : $tbcustomer->aktif == 'Y') ? 'checked' : '' }}
                                            {{ str_contains($vdata['title'], 'Detail') ? 'disabled' : '' }}>
                                    </td>
                                </tr> --}}
                                    </table>
                                </div>
                            </div>
                            <div class="modal-footer">
                                @if (str_contains($vdata['title'], 'Detail'))
                                    <button type="button" class="btn btn-secondary btn-sm"
                                        data-dismiss="modal">Close</button>
                                @else
                                    <button type="submit" id="btnsimpan"
                                        class="btn btn-primary btnsimpan btn-sm">Simpan</button>
                                    <button type="button" class="btn btn-secondary btn-sm"
                                        data-dismiss="modal">Batal</button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="tbl_multiprice">
                </div>
        </form>
    </div>
</div>

<script>
    var myModal = document.getElementById('modaltambah')
    var myInput = document.getElementById('kode')
    // myModal.addEventListener('shown.bs.modal', function() {
    //     myInput.focus()
    // })
    $(myModal).on('shown.bs.modal', function() {
        $(this).find(myInput).focus();
    });

    $(document).ready(function() {
        $('.formtbcustomer').submit(function(e) {
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
                        $('#modaltambah').modal('hide');
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
                        //     window.location.href = '/tbcustomer';
                        // });
                        // window.location = '/tbcustomer';
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

    function salin_alamat_ktr() {
        document.getElementById('alamat_ktr').value = document.getElementById('alamat').value
        document.getElementById('kota_ktr').value = document.getElementById('kota').value
        document.getElementById('kodepos_ktr').value = document.getElementById('kodepos').value
    }

    function salin_alamat_ktp() {
        document.getElementById('alamat_ktp').value = document.getElementById('alamat').value
        document.getElementById('kota_ktp').value = document.getElementById('kota').value
        document.getElementById('kodepos_ktp').value = document.getElementById('kodepos').value
    }

    function salin_alamat_npwp() {
        document.getElementById('nama_npwp').value = document.getElementById('nama').value
        document.getElementById('alamat_npwp').value = document.getElementById('alamat').value + ' ' + document
            .getElementById('kota').value + ' ' + document.getElementById('kodepos').value
    }

    $('#kdklpcust').on('blur', function(e) {
        var checkBox = document.getElementById("kdklpcust");
        let cari = $(this).val()
        var $url = 'replklpcust'
        $.ajax({
            url: $url,
            type: 'get',
            data: {
                'kode': cari,
            },
            success: function(response) {
                let data_response = JSON.parse(response);
                if (!data_response) {
                    $('#kdklpcust').val('');
                    $('#nmklpcust').val('');
                    return;
                }
                $('#nmklpcust').val(data_response['nama']);
            },
            error: function() {
                console.log('file not fount');
            }
        })
    })

    // Nampilin list data pilihan ===================
    function cari_data_klpcust() {
        $.ajax({
            method: "GET",
            url: "cariklpcust",
            dataType: "json",
            success: function(response) {
                $('#modalcari').html(response.body)
                $("#modalcari").modal('show');
            }
        })
    }
    // Buat dapetin data waktu di klik list data yang dipilih ==========
    function post_klpcust() {
        var table = document.getElementById("table_filter_find_klpcust");
        var tbody = table.getElementsByTagName("tbody")[0];
        tbody.onclick = function(e) {
            e = e || window.event;
            var data = [];
            var target = e.srcElement || e.target;
            while (target && target.nodeName !== "TR") {
                target = target.parentNode;
            }
            if (target) {
                var cells = target.getElementsByTagName("td");
                for (var i = 0; i < cells.length; i++) {
                    data.push('--separator--' + cells[i].innerHTML);
                    dt = data.toString();

                }
            }
            dt_split = dt.split(",--separator--");
            $('#kdklpcust').val(((dt_split[0]).replace("--separator--", "")).trim());
            $('#nmklpcust').val(((dt_split[1]).replace("--separator--", "")).trim());
            //$('#alamat').val(((dt_split[2]).replace("--separator--","")).trim());
        };
    }
</script>
