<?php
$session = session();
// var_dump($vdata);
?>
<!-- Modal -->
<div class="modal-dialog" style="max-width: 80%;">
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
        <!-- Way 1: Display All Error Messages -->
        {{-- //submitnya gak pakai ajax --}}
        {{-- @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Whoops!</strong> There were some problems with your input.<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif --}}
        <form action="{{ $action }}" method="post" class="formsaplikasi" enctype="multipart/form-data">
            @csrf
            @if ($saplikasi->id)
                @method('put')
            @endif
            <div class="modal-body">
                <input type="hidden" class="form-control-sm" name="username" id="username"
                    value="{{ $session->get('username') }}">
                <input type="hidden" class="form-control-sm" name="logolama" id="logolama"
                    value="{{ $saplikasi->logo }}">
                {{-- <div class="col-md-6 mb-2">
                    <label class="form-check-label" for="kd_perusahaan" class="form-label mb-1">Kode Perusahaan</label>
                    <input type="text" class="form-control" name="kd_perusahaan" id="kd_perusahaan"
                        value="<?= $saplikasi->kd_perusahaan ?>"
                        {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}
                        {{ str_contains($vdata['title'], 'Detail') ? '' : 'autofocus' }}>
                </div> --}}
                {{-- <div class="col-md-6 mb-2">
                    <label class="form-check-label" for="nm_perusahaan" class="form-label mb-1">Nama Perusahaan</label>
                    <input type="text" class="form-control" name="nm_perusahaan" id="nm_perusahaan"
                        value="<?= $saplikasi->nm_perusahaan ?>"
                        {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}>
                </div>
                <div class="col-md-6 mb-2">
                    <label class="form-check-label" for="alamat" class="form-label mb-1">Alamat</label>
                    <textarea rows="3" class="form-control" name="alamat" id="alamat"
                        {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}><?= $saplikasi->alamat ?></textarea>
                </div>
                <div class="col-md-6 mb-2">
                    <label class="form-check-label" for="telp" class="form-label mb-1">Telpon</label>
                    <input type="number" class="form-control" name="telp" id="telp"
                        value="<?= $saplikasi->telp ?>" {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}>
                </div>
                <div class="col-md-6 mb-2">
                    <label class="form-check-label" for="npwp" class="form-label mb-1">NPWP</label>
                    <input type="number" class="form-control" name="npwp" id="npwp"
                        value="<?= $saplikasi->npwp ?>" {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}>
                </div>
                <div class="col-md-6 mb-2">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="aktif"
                            {{ (str_contains($vdata['title'], 'Tambah') ? 'checked' : $saplikasi->aktif == 'Y') ? 'checked' : '' }}
                            {{ str_contains($vdata['title'], 'Detail') ? 'disabled' : '' }}>
                        <label class="form-check-label" for="aktif">Aktif</label>
                    </div>
                </div> --}}
                <div class='col-md-12'>
                    <div class="row">
                        <div class='col-md-6'>
                            <table style=font-size:13px; class='table table-borderless table-sm table-hover'>
                                <tr>
                                    <td>Kode</td>
                                    <td> <input type='text' class='form-control' name='kd_perusahaan' size='20'
                                            autofocus='autofocus' value="<?= $saplikasi->kd_perusahaan ?>"
                                            {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}
                                            {{ str_contains($vdata['title'], 'Detail') ? '' : 'autofocus' }}></td>
                                <tr>
                                    <td>Perusahaan</td>
                                    <td> <input type='text' class='form-control' name='nm_perusahaan' size='100'
                                            value="<?= $saplikasi->nm_perusahaan ?>"
                                            {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}>
                                    </td>
                                <tr>
                                    <td>Perusahaan Non PPN</td>
                                    <td> <input type='text' class='form-control' name='nm_perusahaan1' size='100'
                                            value="<?= $saplikasi->nm_perusahaan1 ?>"
                                            {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}></td>
                                <tr>
                                    <td>Alamat</td>
                                    <td>
                                        <textarea rows='3' class='form-control' name='alamat' id='alamat'
                                            {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}><?= $saplikasi->alamat ?></textarea>
                                </tr>
                                <tr>
                                    <td>Telpon</td>
                                    <td> <input type='number' class='form-control' name='telp'
                                            value="<?= $saplikasi->telp ?>"
                                            {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}></td>
                                </tr>
                                <tr>
                                    <td>NPWP</td>
                                    <td> <input type='text' class='form-control' name='npwp' size='100'
                                            value="<?= $saplikasi->npwp ?>"
                                            {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}>
                                    </td>

                                <tr>
                                    <td>Nomor Rekening 1</td>
                                    <td><input type='text' class='form-control' name='norek1'
                                            value="<?= $saplikasi->norek1 ?>"
                                            {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}></td>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Nomor Rekening 2</td>
                                    <td><input type='text' class='form-control' name='norek2'
                                            value="<?= $saplikasi->norek2 ?>"
                                            {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}></td>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Jenis HPP</td>
                                    <td>
                                        <select class="form-control" name="jenis_hpp" id="jenis_hpp"
                                            {{ str_contains($vdata['title'], 'Detail') ? 'disabled' : '' }}>
                                            <option value="">[Pilih Jenis HPP]</option>
                                            <?php
                                            $arrjenis_hpp = ['FIFO', 'LIFO', 'AVERAGE'];
                                            $jml_kata = count($arrjenis_hpp);
                                            for ($c = 0; $c < $jml_kata; $c += 1) {
                                                if ($arrjenis_hpp[$c] == $saplikasi->jenis_hpp) {
                                                    echo "<option value='$arrjenis_hpp[$c]' selected>$arrjenis_hpp[$c] </option>";
                                                } else {
                                                    echo "<option value='$arrjenis_hpp[$c]'> $arrjenis_hpp[$c] </option>";
                                                }
                                            }
                                            echo '</select>';
                                            ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Direktur</td>
                                    <td><input type='text' class='form-control' name='direktur'
                                            value="<?= $saplikasi->direktur ?>"
                                            {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}></td>
                                </tr>
                                <tr>
                                    <td>Finance Manager</td>
                                    <td><input type='text' class='form-control' name='finance_mgr'
                                            value="<?= $saplikasi->finance_mgr ?>"
                                            {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}></td>
                                </tr>
                                <tr>
                                    <td>Directory Backup</td>
                                    <td><input type='text' class='form-control' name='dirbackup'
                                            value="<?= $saplikasi->dirbackup ?>"
                                            {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}></td>
                                </tr>
                                <tr>
                                    <td>Aktif</td>
                                    <td> <input type='checkbox' name='aktif'
                                            {{ (str_contains($vdata['title'], 'Tambah') ? 'checked' : $saplikasi->aktif == 'Y') ? 'checked' : '' }}
                                            {{ str_contains($vdata['title'], 'Detail') ? 'disabled' : '' }}>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class='col-md-3'>
                            <table style=font-size:13px; class='table table-borderless table-sm table-hover'>
                                <tr>
                                    <td>PPN</td>
                                    <td> <input type='number' name='ppn' class='form-control'
                                            style="text-align:right;" value="<?= $saplikasi->ppn ?>"
                                            {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}></td>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Kunci Harga Jual</td>
                                    <td>
                                        <input type='checkbox' name='kunci_harga_jual'
                                            {{ (str_contains($vdata['title'], 'Tambah') ? 'checked' : $saplikasi->kunci_harga_jual == 'Y') ? 'checked' : '' }}
                                            {{ str_contains($vdata['title'], 'Detail') ? 'disabled' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Kunci Stock</td>
                                    <td>
                                        <input type='checkbox' name='kunci_stock'
                                            {{ (str_contains($vdata['title'], 'Tambah') ? 'checked' : $saplikasi->kunci_stock == 'Y') ? 'checked' : '' }}
                                            {{ str_contains($vdata['title'], 'Detail') ? 'disabled' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Logo <input type='checkbox' class="form-check" id='llogo' name='llogo'
                                            {{ (str_contains($vdata['title'], 'Tambah') ? 'checked' : $saplikasi->llogo == 'Y') ? 'checked' : '' }}
                                            {{ str_contains($vdata['title'], 'Detail') ? 'disabled' : '' }}></td>
                                    {{-- <td> <input type='file' class='form-control' name='logo'
                                            {{ str_contains($vdata['title'], 'Detail') ? 'disabled' : '' }}> --}}


                                    <td><input type="file" accept="image/png, image/jpeg"
                                            class="custom-file-input" id="logo" name="logo"
                                            value="{{ $saplikasi->logo }}" onchange="previewLogo()"
                                            formenctype="multipart/form-data"
                                            {{ str_contains($vdata['title'], 'Detail') ? 'disabled' : '' }}></td>
                                </tr>
                                <tr>
                                    <td>
                                        <!--{{ asset('storage/uploads/' . $saplikasi->logo) }}-->
                                        <!--<img src="{{ asset('storage/uploads/' . $saplikasi->logo) }}"-->
                                        <?php
                                        // $filelogo = '../posbdg/storage/app/public/uploads/'.$saplikasi->logo
                                        ?>
                                        <!--<img src="{{ '../posbdg/storage/app/public/uploads/' . $saplikasi->logo }}" style="width:200px;" class="img-thumbnail img-preview">-->
                                        <img src="{{ asset('storage/uploads/' . $saplikasi->logo) }}"
                                            style="width:200px;" class="img-thumbnail img-preview">
                                    </td><br>
                                    <td><label for="logo" class="custom-file">Pilih Logo ...</label></td>
                                    {{-- <td><img src='img/{{ $saplikasi->logo }}' width='50'></td> --}}
                                </tr>
                                <input type='hidden' name='x' id='x' value='<?= $saplikasi->logo ?>'>
                                <tr>
                                    <td>Bulan Closing</td>
                                    <td><input type='number' class='form-control' name='closing_hpp'
                                            style="text-align:right;" value="<?= $saplikasi->closing_hpp ?>"
                                            {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}></td>
                                </tr>
                                <tr>
                                    <td>Bulan Berjalan</td>
                                    <td><input type='number' class='form-control' name='bulan'
                                            style="text-align:right;" value="<?= $saplikasi->bulan ?>"
                                            {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}></td>
                                </tr>
                                <tr>
                                    <td>Tahun Berjalan</td>
                                    <td><input type='number' class='form-control' name='tahun'
                                            style="text-align:right;" value="<?= $saplikasi->tahun ?>"
                                            {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}></td>
                                </tr>
                                <tr>
                                    <td>Tanggal Berikutnya</td>
                                    <td><input type='date' class='form-control' id='tgl_berikutnya'
                                            name='tgl_berikutnya' value="<?= $saplikasi->tgl_berikutnya ?>"
                                            {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}></td>
                                </tr>
                                <tr>
                                    <td>Nomor SO</td>
                                    <td><input type='number' class='form-control' name='noso'
                                            style="text-align:right;" value="<?= $saplikasi->noso ?>"
                                            {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}></td>
                                </tr>
                                <tr>
                                    <td>Nomor Jual</td>
                                    <td><input type='number' class='form-control' name='nojual'
                                            style="text-align:right;" value="<?= $saplikasi->nojual ?>"
                                            {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}></td>
                                </tr>
                                <tr>
                                    <td>Nomor PO</td>
                                    <td><input type='number' class='form-control' name='nopo'
                                            style="text-align:right;" value="<?= $saplikasi->nopo ?>"
                                            {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}></td>
                                </tr>
                                <tr>
                                    <td>Nomor Pembelian</td>
                                    <td><input type='number' class='form-control' name='nobeli'
                                            style="text-align:right;" value="<?= $saplikasi->nobeli ?>"
                                            {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}></td>
                                </tr>
                                <tr>
                                    <td>Nomor Penerimaan</td>
                                    <td><input type='number' class='form-control' name='noterima'
                                            style="text-align:right;" value="<?= $saplikasi->noterima ?>"
                                            {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}></td>
                                </tr>
                            </table>
                        </div>
                        <div class='col-md-3'>
                            <table style=font-size:13px; class='table table-borderless table-sm table-hover'>
                                <tr>
                                    <td>Nomor Pengeluaran</td>
                                    <td><input type='number' class='form-control' name='nokeluar'
                                            style="text-align:right;" value="<?= $saplikasi->nokeluar ?>"
                                            {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}></td>
                                </tr>
                                <tr>
                                    <td>Nomor Stock Opname</td>
                                    <td><input type='number' class='form-control' name='noopname'
                                            style="text-align:right;" value="<?= $saplikasi->noopname ?>"
                                            {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}></td>
                                </tr>
                                <tr>
                                    <td>Nomor Approval Piutang</td>
                                    <td><input type='number' class='form-control' name='noapprov'
                                            style="text-align:right;" value="<?= $saplikasi->noapprov ?>"
                                            {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}></td>
                                </tr>
                                <tr>
                                    <td>Nomor Kasir Tunai</td>
                                    <td><input type='number' class='form-control' name='nokwtunai'
                                            style="text-align:right;" value="<?= $saplikasi->nokwtunai ?>"
                                            {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}></td>
                                </tr>
                                <tr>
                                    <td>Nomor Kasir Tagihan</td>
                                    <td><input type='number' class='form-control' name='nokwtagihan'
                                            style="text-align:right;" value="<?= $saplikasi->nokwtagihan ?>"
                                            {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}></td>
                                </tr>
                                <tr>
                                    <td>Nomor Permohonan Keluar</td>
                                    <td><input type='number' class='form-control' name='nomohon'
                                            style="text-align:right;" value="<?= $saplikasi->nomohon ?>"
                                            {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}></td>
                                </tr>
                                <tr>
                                    <td>Nomor Kasir Keluar</td>
                                    <td><input type='number' class='form-control' name='nokwkeluar'
                                            style="text-align:right;" value="<?= $saplikasi->nokwkeluar ?>"
                                            {{ str_contains($vdata['title'], 'Detail') ? 'readonly' : '' }}></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                @if (str_contains($vdata['title'], 'Detail'))
                    <div class='col-md-12'>
                        <tr>
                            <td>User</td>
                            <td> <input type='text' class='form-control form-control-sm' name='user'
                                    id='user' value="{{ $saplikasi->user }}" readonly>
                            </td>
                        </tr>
                    </div>
                @endif
                <div class="modal-footer">
                    @if (str_contains($vdata['title'], 'Detail'))
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    @else
                        <button type="submit" id="btnsimpan" class="btn btn-primary btnsimpan">Simpan</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    @endif
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
        $('.formsaplikasi').submit(function(e) {
            const form = $(this)
            e.preventDefault();
            $.ajax({
                type: "post",
                url: form.attr('action'),
                // data: form.serialize(),
                data: new FormData(this), //penggunaan FormData
                processData: false,
                contentType: false,
                dataType: "json",
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
                        //     window.location.href = '/saplikasi';
                        // });
                        // window.location = '/saplikasi';
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

    function previewLogo() {
        const logo = document.querySelector('#logo');
        const logoLabel = document.querySelector('.custom-file');
        const imgPreview = document.querySelector('.img-preview');
        logoLabel.textContent = logo.files[0].name;
        const filelogo = new FileReader();
        filelogo.readAsDataURL(logo.files[0]);
        filelogo.onload = function(e) {
            imgPreview.src = e.target.result;
        }
    }
</script>
