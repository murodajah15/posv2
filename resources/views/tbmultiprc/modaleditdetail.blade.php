<?php
$session = session();
// dd($vdata);
?>
<!-- Modal -->
<div class="modal-dialog" style="max-width: 50%;">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="staticBackdropLabel">
                {{ $vdata['title'] }} - {{ $vdata['kdcustomer'] }} - {{ $vdata['nmcustomer'] }}
            </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form action="{{ $action }}" method="post" class="formedittbmultiprc">
            @csrf
            {{-- @if ($tbmultiprc->id)
                @method('put')
            @endif --}}
            <div class="modal-body">
                <input type="hidden" class="form-control-sm" name="id" id="id"
                    value="{{ $tbmultiprc->id }}">
                <input type="hidden" class="form-control-sm" name="username" id="username"
                    value="{{ $session->get('username') }}">
                <input type="hidden" class="form-control-sm" name="kdcustomer" id="kdcustomer"
                    value="{{ $tbmultiprc->kdcustomer }}">
                Kode Barang
                <input type='text' class="form-control form-control-sm mb-2" name="kdbarang" id="kdbarang"
                    value="{{ $tbmultiprc->kdbarang }}" readonly>
                Nama Barang
                <input type='text' class="form-control form-control-sm mb-2" name="nmbarang" id="nmbarang"
                    value="{{ $tbmultiprc->nmbarang }}" readonly>
                Harga
                <input type='number' class="form-control form-control-sm mb-2" name="harga" id="harga"
                    value="{{ $tbmultiprc->harga }}">
                Discount
                <input type='number' class="form-control form-control-sm" name="discount" id="discount"
                    value="{{ $tbmultiprc->discount }}">
                <div class="modal-footer">
                    @if (str_contains($vdata['title'], 'Detail'))
                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                    @else
                        <button type="submit" id="btnsimpan" class="btn btn-primary btnsimpan btn-sm">Simpan</button>
                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Batal</button>
                    @endif
                </div>
            </div>
    </div>
    </form>
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
        $('.formedittbmultiprc').submit(function(e) {
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
                        $('#modaleditdetail').modal('hide');
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
                        reload_tbl_multiprcajax();
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
