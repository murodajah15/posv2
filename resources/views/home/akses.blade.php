    <?php
    $cmenu = $submenu;
    $session = session();
    $username = $session->get('email');
    $level = $session->get('level');
    $pakai = 0;
    $tambah = 0;
    $edit = 0;
    $hapus = 0;
    $proses = 0;
    $unproses = 0;
    $cetak = 0;
    if (isset($userdtl->pakai)) {
        $pakai = $userdtl->pakai;
        $tambah = $userdtl->tambah;
        $edit = $userdtl->edit;
        $hapus = $userdtl->hapus;
        $proses = $userdtl->proses;
        $unproses = $userdtl->unproses;
        $cetak = $userdtl->cetak;
    }
    if ($level == 'ADMINISTRATOR') {
        $pakai = 1;
        $tambah = 1;
        $edit = 1;
        $hapus = 1;
        $proses = 1;
        $unproses = 1;
        $cetak = 1;
    }
    $ses_data = [
        'pakai' => $pakai,
        'tambah' => $tambah,
        'edit' => $edit,
        'hapus' => $hapus,
        'proses' => $proses,
        'unproses' => $unproses,
        'cetak' => $cetak,
    ];
    Session::put($ses_data);
    ?>
