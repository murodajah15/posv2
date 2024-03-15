<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PassyouController;
use App\Http\Controllers\SaplikasiController;
use App\Http\Controllers\HisuserController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\ResetController;
use App\Http\Controllers\UpdateprofileController;
use App\Http\Controllers\TbbarangController;
use App\Http\Controllers\TbbankController;
use App\Http\Controllers\TbgudangController;
use App\Http\Controllers\TbjntransController;
use App\Http\Controllers\TbjnbrgController;
use App\Http\Controllers\TbsatuanController;
use App\Http\Controllers\TbnegaraController;
use App\Http\Controllers\TbmoveController;
use App\Http\Controllers\TbdiscountController;
use App\Http\Controllers\TbklpcustController;
use App\Http\Controllers\TbcustomerController;
use App\Http\Controllers\TbsupplierController;
use App\Http\Controllers\TbmultiprcController;
use App\Http\Controllers\TbsalesController;
use App\Http\Controllers\TbjnkeluarController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TbmoduleController;
use App\Http\Controllers\CariController;
use App\Http\Controllers\SoController;
use App\Http\Controllers\SodController;
use App\Http\Controllers\JualController;
use App\Http\Controllers\JualdController;
use App\Http\Controllers\PoController;
use App\Http\Controllers\PodController;
use App\Http\Controllers\BeliController;
use App\Http\Controllers\BelidController;
use App\Http\Controllers\TerimaController;
use App\Http\Controllers\TerimadController;
use App\Http\Controllers\KeluarController;
use App\Http\Controllers\KeluardController;
use App\Http\Controllers\OpnameController;
use App\Http\Controllers\OpnamedController;
use App\Http\Controllers\Approv_batas_piutangController;
use App\Http\Controllers\Kasir_tunaiController;
use App\Http\Controllers\Kasir_tagihanController;
use App\Http\Controllers\Kasir_tagihandController;
use App\Http\Controllers\MohklruangController;
use App\Http\Controllers\MohklruangdController;
use App\Http\Controllers\Kasir_keluarController;
use App\Http\Controllers\Kasir_keluardController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ProsesController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\symlink;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::resource('/', \App\Http\Controllers\LoginController::class);
// Route::resource('home', \App\Http\Controllers\HomeController::class);

// Route::get('flights', function () {
//   // Only authenticated users may access this route...
// })->middleware('auth');

//Login
// Route::get('download', function () {
//   return response()->download(storage_path('app/public/backup/pos-06-11-2023 10-09-14.sql'));
// });

Route::middleware('auth')->group(function () {
  //Home
  Route::get('home', [HomeController::class, 'index'])->name('index');
  Route::get('contact', [HomeController::class, 'contact'])->name('contact');
  Route::get('actionlogout', [LoginController::class, 'actionlogout'])->name('actionlogout');
  //Dashboard
  Route::get('dashboard', [DashboardController::class, 'index'])->name('index');
  Route::get('grafik', [DashboardController::class, 'grafik'])->name('grafik');
  Route::resource('/user', \App\Http\Controllers\UserController::class);
  Route::resource('/passyou', \App\Http\Controllers\PassyouController::class);
  Route::resource('/updateprofile', \App\Http\Controllers\UpdateprofileController::class);
  //Tbmodule
  Route::resource('/tbmodule', \App\Http\Controllers\TbmoduleController::class);
  Route::get('tbmoduleajax', [TbmoduleController::class, 'tbmoduleajax'])->name('tbmoduleajax');
  //Users
  // Route::get('users', [UsersController::class, 'index'])->name('users.index');
  Route::get('userajax', [UserController::class, 'userajax'])->name('userajax');
  // Route::get('user_show', [UserController::class, 'show'])->name('user_show');
  Route::get('user_akses', [UserController::class, 'user_akses'])->name('user_akses');
  Route::post('simpanakses', [UserController::class, 'simpanakses'])->name('simpanakses');
  //Saplikasi
  Route::resource('/saplikasi', \App\Http\Controllers\SaplikasiController::class);
  Route::get('saplikasiajax', [SaplikasiController::class, 'saplikasiajax'])->name('saplikasiajax');
  //Hisuser
  Route::resource('/hisuser', \App\Http\Controllers\HisuserController::class);
  Route::get('hisuserajax', [HisuserController::class, 'hisuserajax'])->name('hisuserajax');
  //Backup
  Route::resource('/backup', \App\Http\Controllers\BackupController::class);
  Route::get('backup_proses', [BackupController::class, 'backup_proses'])->name('backup_proses');
  Route::get('reset', [ResetController::class, 'reset'])->name('reset');
  Route::get('reset_proses', [ResetController::class, 'reset_proses'])->name('reset_proses');
  Route::get('alter_proses', [ResetController::class, 'alter_proses'])->name('alter_proses');
  // Tabel-tabel
  Route::resource('/tbbank', \App\Http\Controllers\TbbankController::class);
  Route::get('tbbankajax', [TbbankController::class, 'tbbankajax'])->name('tbbankajax');
  Route::resource('/tbjnkeluar', \App\Http\Controllers\TbjnkeluarController::class);
  Route::get('tbjnkeluarajax', [TbjnkeluarController::class, 'tbjnkeluarajax'])->name('tbjnkeluarajax');
  Route::resource('/tbgudang', \App\Http\Controllers\TbgudangController::class);
  Route::get('tbgudangajax', [TbgudangController::class, 'tbgudangajax'])->name('tbgudangajax');
  Route::resource('/tbjntrans', \App\Http\Controllers\TbjntransController::class);
  Route::get('tbjntransajax', [TbjntransController::class, 'tbjntransajax'])->name('tbjntransajax');
  Route::resource('/tbjnbrg', \App\Http\Controllers\TbjnbrgController::class);
  Route::get('tbjnbrgajax', [TbjnbrgController::class, 'tbjnbrgajax'])->name('tbjnbrgajax');
  Route::resource('/tbsatuan', \App\Http\Controllers\TbsatuanController::class);
  Route::get('tbsatuanajax', [TbsatuanController::class, 'tbsatuanajax'])->name('tbsatuanajax');
  Route::resource('/tbnegara', \App\Http\Controllers\TbnegaraController::class);
  Route::get('tbnegaraajax', [TbnegaraController::class, 'tbnegaraajax'])->name('tbnegaraajax');
  Route::resource('/tbmove', \App\Http\Controllers\TbmoveController::class);
  Route::get('tbmoveajax', [TbmoveController::class, 'tbmoveajax'])->name('tbmoveajax');
  Route::resource('/tbdiscount', \App\Http\Controllers\TbdiscountController::class);
  Route::get('tbdiscountajax', [TbdiscountController::class, 'tbdiscountajax'])->name('tbdiscountajax');
  Route::resource('/tbklpcust', \App\Http\Controllers\TbklpcustController::class);
  Route::get('tbklpcustajax', [TbklpcustController::class, 'tbklpcustajax'])->name('tbklpcustajax');
  Route::resource('/tbcustomer', \App\Http\Controllers\TbcustomerController::class);
  Route::get('tbcustomerajax', [TbcustomerController::class, 'tbcustomerajax'])->name('tbcustomerajax');
  // Route::get('cariklpcust', [TbcustomerController::class, 'cariklpcust'])->name('cariklpcust');
  // Route::get('replklpcust', [TbcustomerController::class, 'replklpcust'])->name('replklpcust');
  Route::resource('/tbsupplier', \App\Http\Controllers\TbsupplierController::class);
  Route::get('tbsupplierajax', [TbsupplierController::class, 'tbsupplierajax'])->name('tbsupplierajax');
  Route::resource('/tbsales', \App\Http\Controllers\TbsalesController::class);
  Route::get('tbsalesajax', [TbsalesController::class, 'tbsalesajax'])->name('tbsalesajax');
  Route::resource('/tbmultiprc', \App\Http\Controllers\TbmultiprcController::class);
  Route::get('tbmultiprcajax', [TbmultiprcController::class, 'tbmultiprcajax'])->name('tbmultiprcajax');
  Route::get('inputmultiprc', [TbmultiprcController::class, 'inputmultiprc'])->name('inputmultiprc');
  // Route::get('caritbcustomer', [TbmultiprcController::class, 'caritbcustomer'])->name('caritbcustomer');
  // Route::get('repltbcustomer', [TbmultiprcController::class, 'repltbcustomer'])->name('repltbcustomer');
  // Route::get('caritbbarang', [TbmultiprcController::class, 'caritbbarang'])->name('caritbbarang');
  // Route::get('repltbbarang', [TbmultiprcController::class, 'repltbbarang'])->name('repltbbarang');
  Route::get('multiprc_table', [TbmultiprcController::class, 'multiprc_table'])->name('multiprc_table');
  Route::get('multiprc_simpan', [TbmultiprcController::class, 'multiprc_simpan'])->name('multiprc_simpan');
  Route::get('multiprc_salin_tbbarang', [TbmultiprcController::class, 'multiprc_salin_tbbarang'])->name('multiprc_salin_tbbarang');
  Route::get('multiprc_salin_tbbarang_customer', [TbmultiprcController::class, 'multiprc_salin_tbbarang_customer'])->name('multiprc_salin_tbbarang_customer');
  Route::get('multiprc_edit', [TbmultiprcController::class, 'multiprc_edit'])->name('multiprc_edit');
  Route::post('multiprc_update', [TbmultiprcController::class, 'multiprc_update'])->name('multiprc_update');
  Route::resource('/tbbarang', \App\Http\Controllers\TbbarangController::class);
  Route::get('tbbarangajax', [TbbarangController::class, 'tbbarangajax'])->name('tbbarangajax');
  Route::get('ambildatatbnegara', [TbbarangController::class, 'ambildatatbnegara'])->name('ambildatatbnegara');
  Route::get('ambildatatbjnbrg', [TbbarangController::class, 'ambildatatbjnbrg'])->name('ambildatatbjnbrg');
  Route::get('ambildatatbmove', [TbbarangController::class, 'ambildatatbmove'])->name('ambildatatbmove');
  Route::get('ambildatatbdiscount', [TbbarangController::class, 'ambildatatbdiscount'])->name('ambildatatbdiscount');
  Route::get('ambildatatbsatuan', [TbbarangController::class, 'ambildatatbsatuan'])->name('ambildatatbsatuan');
  Route::get('ambildatatbgudang', [TbbarangController::class, 'ambildatatbgudang'])->name('ambildatatbgudang');
  Route::get('carinegara', [TbbarangController::class, 'carinegara'])->name('carinegara');
  Route::get('replnegara', [TbbarangController::class, 'replnegara'])->name('replnegara');
  Route::get('downloadtbbarang', [TbbarangController::class, 'download'])->name('download');

  // Transaksi
  Route::get('cariklpcust', [CariController::class, 'cariklpcust'])->name('cariklpcust');
  Route::get('replklpcust', [CariController::class, 'replklpcust'])->name('replklpcust');
  Route::get('caricustomer', [CariController::class, 'caricustomer'])->name('caricustomer');
  Route::get('replcustomer', [CariController::class, 'replcustomer'])->name('replcustomer');
  Route::get('carisales', [CariController::class, 'carisales'])->name('carisales');
  Route::get('replsales', [CariController::class, 'replsales'])->name('replsales');
  Route::get('carikurir', [CariController::class, 'carikurir'])->name('carikurir');
  Route::get('replkurir', [CariController::class, 'replkurir'])->name('replkurir');
  Route::get('caritbbank', [CariController::class, 'caritbbank'])->name('caritbbank');
  Route::get('repltbbank', [CariController::class, 'repltbbank'])->name('repltbbank');
  Route::get('caritbjnskartu', [CariController::class, 'caritbjnskartu'])->name('caritbjnskartu');
  Route::get('repltbjnskartu', [CariController::class, 'repltbjnskartu'])->name('repltbjnskartu');
  Route::get('caritbbarangbeli', [CariController::class, 'caritbbarangbeli'])->name('caritbbarangbeli');
  Route::get('repltbbarangbeli', [CariController::class, 'repltbbarangbeli'])->name('repltbbarangbeli');
  Route::get('caritbbarang', [CariController::class, 'caritbbarang'])->name('caritbbarang');
  Route::get('repltbbarang', [CariController::class, 'repltbbarang'])->name('repltbbarang');
  Route::get('caritbmultiprc', [CariController::class, 'caritbmultiprc'])->name('caritbmultiprc');
  Route::get('repltbmultiprc', [CariController::class, 'repltbmultiprc'])->name('repltbmultiprc');
  Route::get('carisupplier', [CariController::class, 'carisupplier'])->name('carisupplier');
  Route::get('carisupplierdetail', [CariController::class, 'carisupplierdetail'])->name('carisupplierdetail');
  Route::get('replsupplier', [CariController::class, 'replsupplier'])->name('replsupplier');
  Route::get('carijual', [CariController::class, 'carijual'])->name('carijual');
  Route::get('carijualpiutang', [CariController::class, 'carijualpiutang'])->name('carijualpiutang');
  Route::get('repljual', [CariController::class, 'repljual'])->name('repljual');
  Route::get('repljualpiutang', [CariController::class, 'repljualpiutang'])->name('repljualpiutang');
  Route::get('carimohklruang', [CariController::class, 'carimohklruang'])->name('carimohklruang');
  Route::get('replmohklruang', [CariController::class, 'replmohklruang'])->name('replmohklruang');
  Route::get('caribeli', [CariController::class, 'caribeli'])->name('caribeli');
  Route::get('replbeli', [CariController::class, 'replbeli'])->name('replbeli');
  Route::get('tampilpembayaran', [CariController::class, 'tampilpembayaran'])->name('tampilpembayaran');
  // Route::get('caricustomer', [SoController::class, 'caricustomer'])->name('caricustomer');
  // Route::get('replcustomer', [SoController::class, 'replcustomer'])->name('replcustomer');
  // Route::get('carisales', [SoController::class, 'carisales'])->name('carisales');
  // Route::get('replsales', [SoController::class, 'replsales'])->name('replsales');
  // Route::get('socaritbbarang', [SoController::class, 'socaritbbarang'])->name('socaritbbarang');
  // Route::get('sorepltbbarang', [SoController::class, 'sorepltbbarang'])->name('sorepltbbarang');
  // Route::get('socaritbmultiprc', [SoController::class, 'socaritbmultiprc'])->name('socaritbmultiprc');
  // Route::get('sorepltbmultiprc', [SoController::class, 'sorepltbmultiprc'])->name('sorepltbmultiprc');
  //Sales Order
  Route::resource('/so', \App\Http\Controllers\SoController::class);
  Route::get('soajax', [SoController::class, 'soajax'])->name('soajax');
  Route::get('soupdate', [SoController::class, 'update'])->name('update');
  Route::post('soproses/{soproses}', [SoController::class, 'soproses'])->name('soproses');
  Route::post('sounproses', [SoController::class, 'sounproses'])->name('sounproses');
  Route::post('socancel', [SoController::class, 'socancel'])->name('socancel');
  Route::post('soambil', [SoController::class, 'soambil'])->name('soambil');
  Route::resource('/sod', \App\Http\Controllers\SodController::class);
  Route::get('sodajax', [SoController::class, 'sodajax'])->name('sodajax');
  Route::get('sotambahdetail', [SoController::class, 'sotambahdetail'])->name('sotambahdetail');
  Route::get('sototaldetail', [SoController::class, 'sototaldetail'])->name('sototaldetail');
  Route::get('soinputsod', [SoController::class, 'soinputsod'])->name('soinputsod');
  Route::get('sodstore', [SodController::class, 'store'])->name('sodstore');
  Route::get('sodupdate', [SodController::class, 'update'])->name('update');
  Route::get('socetak', [SoController::class, 'socetak'])->name('socetak');
  Route::get('socetakpl', [SoController::class, 'socetakpl'])->name('socetakpl');
  Route::get('sobatalproses', [SoController::class, 'sobatalproses'])->name('sobatalproses');
  Route::get('sobatalprosesok', [SoController::class, 'sobatalprosesok'])->name('sobatalprosesok');
  //Penjualan
  Route::resource('/jual', \App\Http\Controllers\JualController::class);
  Route::get('jualajax', [JualController::class, 'jualajax'])->name('jualajax');
  Route::get('jualupdate', [JualController::class, 'update'])->name('update');
  Route::post('jualproses/{jualproses}', [JualController::class, 'jualproses'])->name('jualproses');
  Route::post('jualunproses', [JualController::class, 'jualunproses'])->name('jualunproses');
  Route::post('Jualcancel', [JualController::class, 'Jualcancel'])->name('Jualcancel');
  Route::post('jualambil', [JualController::class, 'jualambil'])->name('jualambil');
  Route::resource('/juald', \App\Http\Controllers\JualdController::class);
  Route::get('jualdajax', [JualController::class, 'jualdajax'])->name('jualdajax');
  Route::get('cariso', [JualController::class, 'cariso'])->name('cariso');
  Route::get('replso', [JualController::class, 'replso'])->name('replso');
  Route::get('prosessalinso', [JualController::class, 'prosessalinso'])->name('prosessalinso');
  Route::get('jualtambahdetail', [JualController::class, 'jualtambahdetail'])->name('jualtambahdetail');
  Route::get('jualtotaldetail', [JualController::class, 'jualtotaldetail'])->name('jualtotaldetail');
  Route::get('jualinputjuald', [JualController::class, 'jualinputjuald'])->name('jualinputjuald');
  Route::get('jualdstore', [JualdController::class, 'store'])->name('jualdstore');
  Route::get('jualdupdate', [JualdController::class, 'update'])->name('update');
  Route::get('jualcetakfp', [JualController::class, 'jualcetakfp'])->name('jualcetakfp');
  Route::get('jualcetaksj', [JualController::class, 'jualcetaksj'])->name('jualcetaksj');
  Route::get('jualbatalproses', [JualController::class, 'jualbatalproses'])->name('jualbatalproses');
  Route::get('jualbatalprosesok', [JualController::class, 'jualbatalprosesok'])->name('jualbatalprosesok');
  Route::get('jualkurir', [JualController::class, 'jualkurir'])->name('jualkurir');
  Route::get('jualkurirsimpan', [JualController::class, 'jualkurirsimpan'])->name('jualkurirsimpan');
  //Purchase Order
  Route::resource('/po', \App\Http\Controllers\PoController::class);
  Route::get('poajax', [PoController::class, 'poajax'])->name('poajax');
  Route::get('poupdate', [PoController::class, 'update'])->name('update');
  Route::post('poproses/{poproses}', [PoController::class, 'poproses'])->name('poproses');
  Route::post('pounproses', [PoController::class, 'pounproses'])->name('pounproses');
  Route::post('pocancel', [PoController::class, 'pocancel'])->name('pocancel');
  Route::post('poambil', [PoController::class, 'poambil'])->name('poambil');
  Route::resource('/pod', \App\Http\Controllers\PodController::class);
  Route::get('podajax', [PoController::class, 'podajax'])->name('podajax');
  Route::get('potambahdetail', [PoController::class, 'potambahdetail'])->name('potambahdetail');
  Route::get('pototaldetail', [PoController::class, 'pototaldetail'])->name('pototaldetail');
  Route::get('poinputpod', [PoController::class, 'poinputpod'])->name('poinputpod');
  Route::get('podstore', [PodController::class, 'store'])->name('podstore');
  Route::get('podupdate', [PodController::class, 'update'])->name('update');
  Route::get('pocetak', [PoController::class, 'pocetak'])->name('pocetak');
  Route::get('pobatalproses', [PoController::class, 'pobatalproses'])->name('pobatalproses');
  Route::get('pobatalprosesok', [PoController::class, 'pobatalprosesok'])->name('pobatalprosesok');
  //Pembelian
  Route::resource('/beli', \App\Http\Controllers\BeliController::class);
  Route::get('beliajax', [BeliController::class, 'beliajax'])->name('beliajax');
  Route::get('beliupdate', [BeliController::class, 'update'])->name('update');
  Route::post('beliproses/{beliproses}', [BeliController::class, 'beliproses'])->name('beliproses');
  Route::post('beliunproses', [BeliController::class, 'beliunproses'])->name('beliunproses');
  Route::post('belicancel', [BeliController::class, 'belicancel'])->name('belicancel');
  Route::post('beliambil', [BeliController::class, 'beliambil'])->name('beliambil');
  Route::resource('/belid', \App\Http\Controllers\BelidController::class);
  Route::get('belidajax', [BeliController::class, 'belidajax'])->name('belidajax');
  Route::get('carigudang', [BeliController::class, 'carigudang'])->name('carigudang');
  Route::get('replgudang', [BeliController::class, 'replgudang'])->name('replgudang');
  Route::get('caripo', [BeliController::class, 'caripo'])->name('caripo');
  Route::get('replpo', [BeliController::class, 'replpo'])->name('replpo');
  Route::get('prosessalinpo', [BeliController::class, 'prosessalinpo'])->name('prosessalinpo');
  Route::get('belitambahdetail', [BeliController::class, 'belitambahdetail'])->name('belitambahdetail');
  Route::get('belitotaldetail', [BeliController::class, 'belitotaldetail'])->name('belitotaldetail');
  Route::get('beliinputbelid', [BeliController::class, 'beliinputbelid'])->name('beliinputbelid');
  Route::get('belidstore', [BelidController::class, 'store'])->name('belidstore');
  Route::get('belidupdate', [BelidController::class, 'update'])->name('update');
  Route::get('belicetak', [BeliController::class, 'belicetak'])->name('belicetak');
  Route::get('belibatalproses', [BeliController::class, 'belibatalproses'])->name('belibatalproses');
  Route::get('belibatalprosesok', [BeliController::class, 'belibatalprosesok'])->name('belibatalprosesok');
  //Penerimaan Barang
  Route::resource('/terima', \App\Http\Controllers\TerimaController::class);
  Route::get('terimaajax', [TerimaController::class, 'terimaajax'])->name('terimaajax');
  Route::get('ambildatatbjntranst', [TerimaController::class, 'ambildatatbjntranst'])->name('ambildatatbjntranst');
  Route::get('carikdjntrans', [TerimaController::class, 'carikdjntrans'])->name('carikdjntrans');
  Route::get('terimaupdate', [TerimaController::class, 'update'])->name('update');
  Route::post('terimaproses/{terimaproses}', [TerimaController::class, 'terimaproses'])->name('terimaproses');
  Route::post('terimaunproses', [TerimaController::class, 'terimaunproses'])->name('terimaunproses');
  Route::post('terimacancel', [TerimaController::class, 'terimacancel'])->name('terimacancel');
  Route::post('terimaambil', [TerimaController::class, 'terimaambil'])->name('terimaambil');
  Route::resource('/terimad', \App\Http\Controllers\TerimadController::class);
  Route::get('terimadajax', [TerimaController::class, 'terimadajax'])->name('terimadajax');
  Route::get('terimatambahdetail', [TerimaController::class, 'terimatambahdetail'])->name('terimatambahdetail');
  Route::get('terimatotaldetail', [TerimaController::class, 'terimatotaldetail'])->name('terimatotaldetail');
  Route::get('terimainputterimad', [TerimaController::class, 'terimainputterimad'])->name('terimainputterimad');
  Route::get('terimadstore', [TerimadController::class, 'store'])->name('terimadstore');
  Route::get('terimadupdate', [TerimadController::class, 'update'])->name('update');
  Route::get('terimacetak', [TerimaController::class, 'terimacetak'])->name('terimacetak');
  Route::get('terimabatalproses', [TerimaController::class, 'terimabatalproses'])->name('terimabatalproses');
  Route::get('terimabatalprosesok', [TerimaController::class, 'terimabatalprosesok'])->name('terimabatalprosesok');
  //Pengeluaran Barang
  Route::resource('/keluar', \App\Http\Controllers\KeluarController::class);
  Route::get('keluarajax', [KeluarController::class, 'keluarajax'])->name('keluarajax');
  Route::get('ambildatatbjntransk', [KeluarController::class, 'ambildatatbjntransk'])->name('ambildatatbjntransk');
  Route::get('carikdjntrans', [KeluarController::class, 'carikdjntrans'])->name('carikdjntrans');
  Route::get('keluarupdate', [KeluarController::class, 'update'])->name('update');
  Route::post('keluarproses/{keluarproses}', [KeluarController::class, 'keluarproses'])->name('keluarproses');
  Route::post('keluarunproses', [KeluarController::class, 'keluarunproses'])->name('keluarunproses');
  Route::post('keluarcancel', [KeluarController::class, 'keluarcancel'])->name('keluarcancel');
  Route::post('keluarambil', [KeluarController::class, 'keluarambil'])->name('keluarambil');
  Route::resource('/keluard', \App\Http\Controllers\KeluardController::class);
  Route::get('keluardajax', [KeluarController::class, 'keluardajax'])->name('keluardajax');
  Route::get('keluartambahdetail', [KeluarController::class, 'keluartambahdetail'])->name('keluartambahdetail');
  Route::get('keluartotaldetail', [KeluarController::class, 'keluartotaldetail'])->name('keluartotaldetail');
  Route::get('keluarinputkeluard', [KeluarController::class, 'keluarinputkeluard'])->name('keluarinputkeluard');
  Route::get('keluardstore', [KeluardController::class, 'store'])->name('keluardstore');
  Route::get('keluardupdate', [KeluardController::class, 'update'])->name('update');
  Route::get('keluarcetak', [KeluarController::class, 'keluarCetak'])->name('keluarcetak');
  Route::get('keluarbatalproses', [KeluarController::class, 'keluarbatalproses'])->name('keluarbatalproses');
  Route::get('keluarbatalprosesok', [KeluarController::class, 'keluarbatalprosesok'])->name('keluarbatalprosesok');
  //Stock Opname
  Route::resource('/opname', \App\Http\Controllers\OpnameController::class);
  Route::get('opnameajax', [OpnameController::class, 'opnameajax'])->name('opnameajax');
  Route::get('opnameupdate', [OpnameController::class, 'update'])->name('update');
  Route::post('opnamesalinbarang/{opnamesalinbarang}', [OpnameController::class, 'opnamesalinbarang'])->name('opnamesalinbarang');
  Route::post('opnameproses/{opnameproses}', [OpnameController::class, 'opnameproses'])->name('opnameproses');
  Route::post('opnameunproses', [OpnameController::class, 'opnameunproses'])->name('opnameunproses');
  Route::post('opnamecancel', [OpnameController::class, 'opnamecancel'])->name('opnamecancel');
  Route::post('opnameambil', [OpnameController::class, 'opnameambil'])->name('opnameambil');
  Route::resource('/opnamed', \App\Http\Controllers\OpnamedController::class);
  Route::get('opnamedajax', [OpnameController::class, 'opnamedajax'])->name('opnamedajax');
  Route::get('opnametambahdetail', [OpnameController::class, 'opnametambahdetail'])->name('opnametambahdetail');
  Route::get('opnametotaldetail', [OpnameController::class, 'opnametotaldetail'])->name('opnametotaldetail');
  Route::get('opnameinputopnamed', [OpnameController::class, 'opnameinputopnamed'])->name('opnameinputopnamed');
  Route::get('opnamedstore', [OpnamedController::class, 'store'])->name('opnamedstore');
  Route::get('opnamedupdate', [OpnamedController::class, 'update'])->name('update');
  Route::get('opnamecetak', [OpnameController::class, 'opnameCetak'])->name('opnamecetak');
  Route::get('opnamebatalproses', [OpnameController::class, 'opnamebatalproses'])->name('opnamebatalproses');
  Route::get('opnamebatalprosesok', [OpnameController::class, 'opnamebatalprosesok'])->name('opnamebatalprosesok');
  //Approv batas piutang
  Route::resource('/approv_batas_piutang', \App\Http\Controllers\Approv_batas_piutangController::class);
  Route::get('approv_batas_piutangajax', [Approv_batas_piutangController::class, 'approv_batas_piutangajax'])->name('approv_batas_piutangajax');
  Route::get('approv_batas_piutangupdate', [Approv_batas_piutangController::class, 'update'])->name('update');
  Route::post('approv_batas_piutangproses/{approv_batas_piutangproses}', [Approv_batas_piutangController::class, 'approv_batas_piutangproses'])->name('approv_batas_piutangproses');
  Route::post('approv_batas_piutangunproses', [Approv_batas_piutangController::class, 'approv_batas_piutangunproses'])->name('approv_batas_piutangunproses');
  Route::post('approv_batas_piutangcancel', [Approv_batas_piutangController::class, 'approv_batas_piutangcancel'])->name('approv_batas_piutangcancel');
  Route::post('approv_batas_piutangambil', [Approv_batas_piutangController::class, 'approv_batas_piutangambil'])->name('approv_batas_piutangambil');
  Route::get('approv_batas_piutangcetak', [Approv_batas_piutangController::class, 'approv_batas_piutangCetak'])->name('approv_batas_piutangcetak');
  Route::get('approv_batas_piutangbatalproses', [Approv_batas_piutangController::class, 'approv_batas_piutangbatalproses'])->name('approv_batas_piutangbatalproses');
  Route::get('approv_batas_piutangbatalprosesok', [Approv_batas_piutangController::class, 'approv_batas_piutangbatalprosesok'])->name('approv_batas_piutangbatalprosesok');
  //Kasir tunai
  Route::resource('/kasir_tunai', \App\Http\Controllers\Kasir_tunaiController::class);
  Route::get('kasir_tunaiajax', [Kasir_tunaiController::class, 'kasir_tunaiajax'])->name('kasir_tunaiajax');
  Route::get('kasir_tunaiupdate', [Kasir_tunaiController::class, 'update'])->name('update');
  Route::post('kasir_tunaiproses/{kasir_tunaiproses}', [Kasir_tunaiController::class, 'kasir_tunaiproses'])->name('kasir_tunaiproses');
  Route::post('kasir_tunaiunproses', [Kasir_tunaiController::class, 'kasir_tunaiunproses'])->name('kasir_tunaiunproses');
  Route::post('kasir_tunaicancel', [Kasir_tunaiController::class, 'kasir_tunaicancel'])->name('kasir_tunaicancel');
  Route::post('kasir_tunaiambil', [Kasir_tunaiController::class, 'kasir_tunaiambil'])->name('kasir_tunaiambil');
  Route::get('kasir_tunaicetak', [Kasir_tunaiController::class, 'kasir_tunaiCetak'])->name('kasir_tunaicetak');
  Route::get('kasir_tunaibatalproses', [Kasir_tunaiController::class, 'kasir_tunaibatalproses'])->name('kasir_tunaibatalproses');
  Route::get('kasir_tunaibatalprosesok', [Kasir_tunaiController::class, 'kasir_tunaibatalprosesok'])->name('kasir_tunaibatalprosesok');
  //Kasir tagihan
  Route::resource('/kasir_tagihan', \App\Http\Controllers\Kasir_tagihanController::class);
  Route::get('kasir_tagihanajax', [Kasir_tagihanController::class, 'kasir_tagihanajax'])->name('kasir_tagihanajax');
  Route::get('kasir_tagihanupdate', [Kasir_tagihanController::class, 'update'])->name('update');
  Route::resource('/kasir_tagihand', \App\Http\Controllers\Kasir_tagihandController::class);
  Route::get('kasir_tagihandajax', [Kasir_tagihandController::class, 'kasir_tagihandajax'])->name('kasir_tagihandajax');
  Route::get('kasir_tagihantambahdetail', [Kasir_tagihandController::class, 'kasir_tagihantambahdetail'])->name('kasir_tagihantambahdetail');
  Route::get('kasir_tagihantotaldetail', [Kasir_tagihandController::class, 'kasir_tagihantotaldetail'])->name('kasir_tagihantotaldetail');
  Route::get('kasir_tagihaninputkasir_tagihand', [Kasir_tagihandController::class, 'kasir_tagihaninputkasir_tagihand'])->name('kasir_tagihaninputkasir_tagihand');
  Route::get('kasir_tagihandstore', [Kasir_tagihandController::class, 'store'])->name('kasir_tagihandstore');
  Route::get('kasir_tagihandupdate', [Kasir_tagihandController::class, 'update'])->name('update');
  Route::post('kasir_tagihanproses/{kasir_tagihanproses}', [Kasir_tagihanController::class, 'kasir_tagihanproses'])->name('kasir_tagihanproses');
  Route::post('kasir_tagihanunproses', [Kasir_tagihanController::class, 'kasir_tagihanunproses'])->name('kasir_tagihanunproses');
  Route::post('kasir_tagihancancel', [Kasir_tagihanController::class, 'kasir_tagihancancel'])->name('kasir_tagihancancel');
  Route::post('kasir_tagihanambil', [Kasir_tagihanController::class, 'kasir_tagihanambil'])->name('kasir_tagihanambil');
  Route::get('kasir_tagihancetak', [Kasir_tagihanController::class, 'kasir_tagihanCetak'])->name('kasir_tagihancetak');
  Route::get('kasir_tagihanbatalproses', [Kasir_tagihanController::class, 'kasir_tagihanbatalproses'])->name('kasir_tagihanbatalproses');
  Route::get('kasir_tagihanbatalprosesok', [Kasir_tagihanController::class, 'kasir_tagihanbatalprosesok'])->name('kasir_tagihanbatalprosesok');
  //Permohonan Keluar Uang
  Route::resource('/mohklruang', \App\Http\Controllers\MohklruangController::class);
  Route::get('mohklruangajax', [MohklruangController::class, 'mohklruangajax'])->name('mohklruangajax');
  Route::get('ambildatatbjnkeluar', [MohklruangController::class, 'ambildatatbjnkeluar'])->name('ambildatatbjnkeluar');
  Route::get('carikdjnkeluar', [MohklruangController::class, 'carikdjnkeluar'])->name('carikdjnkeluar');
  Route::get('mohklruangupdate', [MohklruangController::class, 'update'])->name('update');
  Route::resource('/mohklruangd', \App\Http\Controllers\MohklruangdController::class);
  Route::get('mohklruangdajax', [MohklruangdController::class, 'mohklruangdajax'])->name('mohklruangdajax');
  Route::get('mohklruangtambahdetail', [MohklruangdController::class, 'mohklruangtambahdetail'])->name('mohklruangtambahdetail');
  Route::get('mohklruangtotaldetail', [MohklruangdController::class, 'mohklruangtotaldetail'])->name('mohklruangtotaldetail');
  Route::get('mohklruanginputmohklruangd', [MohklruangdController::class, 'mohklruanginputmohklruangd'])->name('mohklruanginputmohklruangd');
  Route::get('mohklruangdstore', [MohklruangdController::class, 'store'])->name('mohklruangdstore');
  Route::get('mohklruangdupdate', [MohklruangdController::class, 'update'])->name('update');
  Route::post('mohklruangproses/{mohklruangproses}', [MohklruangController::class, 'mohklruangproses'])->name('mohklruangproses');
  Route::post('mohklruangunproses', [MohklruangController::class, 'mohklruangunproses'])->name('mohklruangunproses');
  Route::post('mohklruangcancel', [MohklruangController::class, 'mohklruangcancel'])->name('mohklruangcancel');
  Route::post('mohklruangambil', [MohklruangController::class, 'mohklruangambil'])->name('mohklruangambil');
  Route::get('mohklruangcetak', [MohklruangController::class, 'mohklruangCetak'])->name('mohklruangcetak');
  Route::get('mohklruangbatalproses', [MohklruangController::class, 'mohklruangbatalproses'])->name('mohklruangbatalproses');
  Route::get('mohklruangbatalprosesok', [MohklruangController::class, 'mohklruangbatalprosesok'])->name('mohklruangbatalprosesok');
  //Kasir keluar
  Route::resource('/kasir_keluar', \App\Http\Controllers\Kasir_keluarController::class);
  Route::get('kasir_keluarajax', [Kasir_keluarController::class, 'kasir_keluarajax'])->name('kasir_keluarajax');
  Route::get('kasir_keluarupdate', [Kasir_keluarController::class, 'update'])->name('update');
  Route::resource('/kasir_keluard', \App\Http\Controllers\Kasir_keluardController::class);
  Route::get('kasir_keluardajax', [Kasir_keluardController::class, 'kasir_keluardajax'])->name('kasir_keluardajax');
  Route::get('kasir_keluartambahdetail', [Kasir_keluardController::class, 'kasir_keluartambahdetail'])->name('kasir_keluartambahdetail');
  Route::get('kasir_keluartotaldetail', [Kasir_keluardController::class, 'kasir_keluartotaldetail'])->name('kasir_keluartotaldetail');
  Route::get('kasir_keluarinputkasir_keluard', [Kasir_keluardController::class, 'kasir_keluarinputkasir_keluard'])->name('kasir_keluarinputkasir_keluard');
  Route::get('kasir_keluardstore', [Kasir_keluardController::class, 'store'])->name('kasir_keluardstore');
  Route::get('kasir_keluardupdate', [Kasir_keluardController::class, 'update'])->name('update');
  Route::post('kasir_keluarproses/{kasir_keluarproses}', [Kasir_keluarController::class, 'kasir_keluarproses'])->name('kasir_keluarproses');
  Route::post('kasir_keluarunproses', [Kasir_keluarController::class, 'kasir_keluarunproses'])->name('kasir_keluarunproses');
  Route::post('kasir_keluarcancel', [Kasir_keluarController::class, 'kasir_keluarcancel'])->name('kasir_keluarcancel');
  Route::post('kasir_keluarambil', [Kasir_keluarController::class, 'kasir_keluarambil'])->name('kasir_keluarambil');
  Route::get('kasir_keluarcetak', [Kasir_keluarController::class, 'kasir_keluarCetak'])->name('kasir_keluarcetak');
  Route::get('kasir_keluarbatalproses', [Kasir_keluarController::class, 'kasir_keluarbatalproses'])->name('kasir_keluarbatalproses');
  Route::get('kasir_keluarbatalprosesok', [Kasir_keluarController::class, 'kasir_keluarbatalprosesok'])->name('kasir_keluarbatalprosesok');
  //Proses
  // Route::get('closing_harian', [ReportController::class, 'closing_harian'])->name('closing_harian');
  // Route::post('closing_harian_proses', [ReportController::class, 'closing_harian_proses'])->name('closing_harian_proses');
  // Route::get('closing_hpp', [ReportController::class, 'closing_hpp'])->name('closing_hpp');
  // Route::post('closing_hpp_proses', [ReportController::class, 'closing_hpp_proses'])->name('closing_hpp_proses');
  // Route::get('closing_harian', [ReportController::class, 'closing_harian'])->name('closing_harian');
  // Route::post('closing_harian_proses', [ReportController::class, 'closing_harian_proses'])->name('closing_harian_proses');
  // Route::get('proses_stock', [ReportController::class, 'proses_stock'])->name('proses_stock');
  // Route::post('proses_stock_proses', [ReportController::class, 'proses_stock_proses'])->name('proses_stock_proses');
  // Route::get('backup', [ReportController::class, 'backup'])->name('backup');
  // Route::post('backup_proses', [ReportController::class, 'backup_proses'])->name('backup_proses');
  //Report-Report
  Route::get('rfaktur', [ReportController::class, 'rfaktur'])->name('rfaktur');
  Route::post('rfaktur_xls', [ReportController::class, 'rfaktur_xls'])->name('rfaktur_xls');
  Route::get('rfaktur_export', [ReportController::class, 'rfaktur_export'])->name('rfaktur_export');
  Route::get('rso', [ReportController::class, 'rso'])->name('rso');
  Route::post('rso_xls', [ReportController::class, 'rso_xls'])->name('rso_xls');
  Route::get('rso_export', [ReportController::class, 'rso_export'])->name('rso_export');
  Route::get('rjual', [ReportController::class, 'rjual'])->name('rjual');
  Route::post('rjual_xls', [ReportController::class, 'rjual_xls'])->name('rjual_xls');
  Route::get('rjual_export', [ReportController::class, 'rjual_export'])->name('rjual_export');
  Route::get('rrating', [ReportController::class, 'rrating'])->name('rrating');
  Route::post('rrating_xls', [ReportController::class, 'rrating_xls'])->name('rrating_xls');
  Route::get('rrating_export', [ReportController::class, 'rrating_export'])->name('rrating_export');
  Route::get('rpo', [ReportController::class, 'rpo'])->name('rpo');
  Route::post('rpo_xls', [ReportController::class, 'rpo_xls'])->name('rpo_xls');
  Route::get('rpo_export', [ReportController::class, 'rpo_export'])->name('rpo_export');
  Route::get('rbeli', [ReportController::class, 'rbeli'])->name('rbeli');
  Route::post('rbeli_xls', [ReportController::class, 'rbeli_xls'])->name('rbeli_xls');
  Route::get('rbeli_export', [ReportController::class, 'rbeli_export'])->name('rbeli_export');
  Route::get('rterima', [ReportController::class, 'rterima'])->name('rterima');
  Route::post('rterima_xls', [ReportController::class, 'rterima_xls'])->name('rterima_xls');
  Route::get('rterima_export', [ReportController::class, 'rterima_export'])->name('rterima_export');
  Route::get('rkeluar', [ReportController::class, 'rkeluar'])->name('rkeluar');
  Route::post('rkeluar_xls', [ReportController::class, 'rkeluar_xls'])->name('rkeluar_xls');
  Route::get('rkeluar_export', [ReportController::class, 'rkeluar_export'])->name('rkeluar_export');
  Route::get('rstock_opname', [ReportController::class, 'rstock_opname'])->name('rstock_opname');
  Route::post('rstock_opname_xls', [ReportController::class, 'rstock_opname_xls'])->name('rstock_opname_xls');
  Route::get('rstock_opname_export', [ReportController::class, 'rstock_opname_export'])->name('rstock_opname_export');
  Route::get('rstock', [ReportController::class, 'rstock'])->name('rstock');
  Route::post('rstock_xls', [ReportController::class, 'rstock_xls'])->name('rstock_xls');
  Route::get('rstock_export', [ReportController::class, 'rstock_export'])->name('rstock_export');
  Route::get('rhpp', [ReportController::class, 'rhpp'])->name('rhpp');
  Route::post('rhpp_xls', [ReportController::class, 'rhpp_xls'])->name('rhpp_xls');
  Route::get('rhpp_export', [ReportController::class, 'rhpp_export'])->name('rhpp_export');
  Route::get('rkasir_tunai', [ReportController::class, 'rkasir_tunai'])->name('rkasir_tunai');
  Route::post('rkasir_tunai_xls', [ReportController::class, 'rkasir_tunai_xls'])->name('rkasir_tunai_xls');
  Route::get('rkasir_tunai_export', [ReportController::class, 'rkasir_tunai_export'])->name('rkasir_tunai_export');
  Route::get('rkasir_tagihan', [ReportController::class, 'rkasir_tagihan'])->name('rkasir_tagihan');
  Route::post('rkasir_tagihan_xls', [ReportController::class, 'rkasir_tagihan_xls'])->name('rkasir_tagihan_xls');
  Route::get('rkasir_tagihan_export', [ReportController::class, 'rkasir_tagihan_export'])->name('rkasir_tagihan_export');
  Route::get('rpermohonan_keluar_uang', [ReportController::class, 'rpermohonan_keluar_uang'])->name('rpermohonan_keluar_uang');
  Route::post('rpermohonan_keluar_uang_xls', [ReportController::class, 'rpermohonan_keluar_uang_xls'])->name('rpermohonan_keluar_uang_xls');
  Route::get('rpermohonan_keluar_uang_export', [ReportController::class, 'rpermohonan_keluar_uang_export'])->name('rpermohonan_keluar_uang_export');
  Route::get('rkasir_keluar', [ReportController::class, 'rkasir_keluar'])->name('rkasir_keluar');
  Route::post('rkasir_keluar_xls', [ReportController::class, 'rkasir_keluar_xls'])->name('rkasir_keluar_xls');
  Route::get('rkasir_keluar_export', [ReportController::class, 'rkasir_keluar_export'])->name('rkasir_keluar_export');
  Route::get('rpiutang', [ReportController::class, 'rpiutang'])->name('rpiutang');
  Route::post('rpiutang_xls', [ReportController::class, 'rpiutang_xls'])->name('rpiutang_xls');
  Route::get('rpiutang_export', [ReportController::class, 'rpiutang_export'])->name('rpiutang_export');
  Route::get('rhutang', [ReportController::class, 'rhutang'])->name('rhutang');
  Route::post('rhutang_xls', [ReportController::class, 'rhutang_xls'])->name('rhutang_xls');
  Route::get('rhutang_export', [ReportController::class, 'rhutang_export'])->name('rhutang_export');
  //Proses Menu
  Route::get('closing_harian', [ProsesController::class, 'closing_harian'])->name('closing_harian');
  Route::get('closing_harian_proses', [ProsesController::class, 'closing_harian_proses'])->name('closing_harian_proses');
  Route::get('closing_hpp', [ProsesController::class, 'closing_hpp'])->name('closing_hpp');
  Route::get('closing_hpp_proses', [ProsesController::class, 'closing_hpp_proses'])->name('closing_hpp_proses');
  Route::get('closing_hpp_unproses', [ProsesController::class, 'closing_hpp_unproses'])->name('closing_hpp_unproses');
  Route::get('proses_stock', [ProsesController::class, 'proses_stock'])->name('proses_stock');
  Route::get('proses_stock_proses', [ProsesController::class, 'proses_stock_proses'])->name('proses_stock_proses');
  Route::get('backup', [ProsesController::class, 'backup'])->name('backup');
  Route::get('backup_proses', [ProsesController::class, 'backup_proses'])->name('backup_proses');
});

Route::get('/link', function () {
  Artisan::call('storage:link');
  echo 'ok';
});
Route::get('/linkstorage', function () {
  $targetFolder = base_path() . '/storage/app/public';
  $linkFolder = $_SERVER['DOCUMENT_ROOT'] . '/storage';
  symlink($targetFolder, $linkFolder);
});
Route::get('generate', function () {
  \Illuminate\Support\Facades\Artisan::call('storage:link');
  echo 'ok';
});

Route::get('/', [LoginController::class, 'index'])->name('login');
Route::post('actionlogin', [LoginController::class, 'actionlogin'])->name('actionlogin');

//REGISTER
Route::get('register', [RegisterController::class, 'register'])->name('register');
Route::post('register/action', [RegisterController::class, 'actionregister'])->name('actionregister');
