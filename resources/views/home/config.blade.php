<?php
$server = env('DB_HOST'); //'localhost';
$username = env('DB_USERNAME'); // 'root';
$password = env('DB_PASSWORD'); //'';
$database = env('DB_DATABASE'); //'pos';

// Koneksi dan memilih database di server
/*mysql_connect($server,$username,$password) or die("Koneksi gagal");
 mysql_select_db($database) or die("Database tidak bisa dibuka");**/

// melakukan koneksi ke database
$connect = new mysqli($server, $username, $password, $database);

session(['connect' => $connect]);
session(['server' => $server]);
session(['userdb' => $username]);
session(['passworddb' => $password]);
session(['database' => $database]);

// cek koneksi yang kita lakukan berhasil atau tidak
if ($connect->connect_error) {
    // jika terjadi error, matikan proses dengan die() atau exit();
    die('Maaf koneksi gagal: ' . $connect->connect_error);
}
