@include('home.config')

@include('home.akses');
<?php
$pakai = session('pakai');
$tambah = session('tambah');
$edit = session('edit');
$hapus = session('hapus');
$proses = session('proses');
$unproses = session('unproses');
$cetak = session('cetak');

if ($proses != '1') {
    echo '<script>
        alert(\'Anda Tidak Berhak\')
                window.close()
    </script>';
    exit();
}
?>

<body>
    <div id="loader"></div>
    <script src="{{ asset('/') }}assets/dist/js/sweet-alert.min.js"></script>

    <label style="text-align: center">
        <br><br>
        <h2>Sedang Proses Backup ...</h2>
    </label>
    <?php
    $connect = session('connect');
    date_default_timezone_set('Asia/Jakarta');
    
    $query = mysqli_query($connect, "select * from saplikasi where aktif='Y'");
    $d = mysqli_fetch_assoc($query);
    $path = $d['dirbackup'];
    if ($path != '') {
        if (is_dir($path)) {
        } else {
            // To create the nested structure, the $recursive parameter
            // to mkdir() must be specified.
            if (!mkdir($path, 0777, true)) {
                die('Failed to create folders...');
                echo "<script>alert('Direktori '.$path.' tidak ditemukan !');history.go(-1) </script>";
                exit();
            }
        }
    }
    
    /* backup the db OR just a table */
    $server = session('server');
    $username = session('userdb');
    $password = session('passworddb');
    $database = session('database');
    $name = $database;
    $tables = '*';
    
    // echo $database;
    
    // backup_tables1($server, $username, $password, $database);
    $today = date('d-m-Y H-i-s');
    
    // function backup_tables($host, $user, $pass, $name, $tables = '*')
    // {
    $return = '';
    
    mysqli_select_db($connect, $name);
    
    //get all of the tables
    if ($tables == '*') {
        $tables = [];
        $result = mysqli_query($connect, 'SHOW TABLES');
        while ($row = mysqli_fetch_row($result)) {
            $tables[] = $row[0];
        }
    } else {
        $tables = is_array($tables) ? $tables : explode(',', $tables);
    }
    
    //cycle through
    foreach ($tables as $table) {
        $result = mysqli_query($connect, 'SELECT * FROM ' . $table);
        $num_fields = mysqli_num_fields($result);
        $return .= 'DROP TABLE ' . $table . ';';
        $row2 = mysqli_fetch_row(mysqli_query($connect, 'SHOW CREATE TABLE ' . $table));
        // var_dump($row2);
        $return .= "\n\n" . $row2[1] . ";\n\n";
    
        for ($i = 0; $i < $num_fields; $i++) {
            while ($row = mysqli_fetch_row($result)) {
                $return .= 'INSERT INTO ' . $table . ' VALUES(';
                for ($j = 0; $j < $num_fields; $j++) {
                    $row[$j] = addslashes($row[$j]);
                    $row[$j] = preg_replace("/\n/i", "\\n", $row[$j]);
                    if (isset($row[$j])) {
                        $return .= '"' . $row[$j] . '"';
                    } else {
                        $return .= '""';
                    }
                    if ($j < $num_fields - 1) {
                        $return .= ',';
                    }
                }
                $return .= ");\n";
            }
        }
        $return .= "\n\n\n";
    }
    
    //save file
    /*$handle = fopen('d:\temp\db-backup-'.time().'-'.(md5(implode(',',$tables))).'.sql','w+');**/
    $today = date('d-m-Y H-i-s');
    $handle = fopen($path . $database . '-' . $today . '.sql', 'w+');
    fwrite($handle, $return);
    fclose($handle);
    $file = $path . $database . '-' . $today . '.sql';
    ?>
    <!-- <p align="center">Data berhasil di backup <?php echo $path . $database . '-' . $today . '.sql'; ?></p>
    <p align="center" style="color: blue"><a style="cursor:pointer" onclick="location.href='download_backup.php?nama_file=<?php echo $file; ?>'" title="Download">Download</a></p> -->

    <script>
        alert('Data berhasil di Backup');
        // alert('Data berhasil di Backup ' + $path.$database + '-' + $today + ".sql");
        //history.go(-1)
    </script>

    <?php
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename=' . basename($file));
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: private');
    header('Pragma: private');
    header('Content-Length: ' . filesize($file));
    ob_clean();
    flush();
    readfile($file);
    unlink($file);
    exit();
    ?>


    <?php
    // }
    ?>

</body>
?>
?>
?>
?>
?>
?>
?>
