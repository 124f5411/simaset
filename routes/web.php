<?php

use App\Http\Controllers\Barang\KdpController;
use App\Http\Controllers\Barang\PeralatanController;
use App\Http\Controllers\Barang\TanahController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KontrakController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\Pengaturan\AdminAsetController;
use App\Http\Controllers\Pengaturan\AdminController;
use App\Http\Controllers\Pengaturan\BendaharaController;
use App\Http\Controllers\Pengaturan\InstansiController;
use App\Http\Controllers\Pengaturan\OperatorController;
use App\Http\Controllers\Pengaturan\TtdController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\Referensi\hakTanahController;
use App\Http\Controllers\Referensi\jenisController;
use App\Http\Controllers\Referensi\KelompokController;
use App\Http\Controllers\Referensi\KibController;
use App\Http\Controllers\Referensi\KodeBarangController;
use App\Http\Controllers\Referensi\SatuanController;
use App\Http\Controllers\Referensi\statusTanahController;
use App\Http\Controllers\Usulan\AsbController;
use App\Http\Controllers\Usulan\HspkController;
use App\Http\Controllers\Usulan\SbuController;
use App\Http\Controllers\Usulan\SshController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    if(Auth::check()){
        return redirect('/dashboard');
    }
    return view('main.index');
})->name('main');

// Route::get('/main',[MainController::class,'index'])->name('main');
Route::post('/authenticate',[MainController::class,'c_auth'])->name('userCheck');

Route::group(['middleware' => 'auth'], function (){
    Route::get('/dashboard',[DashboardController::class,'index'])->name('dashboard');
    Route::post('/logout',[DashboardController::class,'keluar'])->name('logout');

    Route::put('profil/password/{id}',[ProfilController::class,'password'])->name('profil.password');

    Route::get('/kontrak',[KontrakController::class,'index'])->name('kontrak.index');
    Route::get('/kontrak/data',[KontrakController::class,'data'])->name('kontrak.data');
    Route::get('/kontrak/{id}',[KontrakController::class,'show'])->name('kontrak.show');
    Route::post('kontrak',[KontrakController::class,'store'])->name('kontrak.store');
    Route::put('/kontrak/{id}',[KontrakController::class,'update'])->name('kontrak.update');
    Route::delete('/kontrak/{id}',[KontrakController::class,'destroy'])->name('kontrak.destroy');

    Route::get('pengaturan/instansi',[InstansiController::class,'index'])->name('instansi.index');
    Route::get('pengaturan/admin',[AdminController::class,'index'])->name('admin.index');
    Route::get('pengaturan/adminaset',[AdminAsetController::class,'index'])->name('adminaset.index');
    Route::get('pengaturan/bendahara',[BendaharaController::class,'index'])->name('bendahara.index');
    Route::get('pengaturan/operator',[OperatorController::class,'index'])->name('operator.index');
    Route::get('pengaturan/pb',[TtdController::class,'index'])->name('pb.index');

    Route::get('pb/show/{id}',[TtdController::class,'show'])->name('ttd.show');
    Route::post('pb/store',[TtdController::class,'store'])->name('ttd.store');
    Route::put('pb/update/{id}',[TtdController::class,'update'])->name('ttd.update');

    Route::get('admin/data',[AdminController::class,'data'])->name('admin.data');
    Route::get('admin/{id}',[AdminController::class,'show'])->name('admin.show');
    Route::post('admin',[AdminController::class,'store'])->name('admin.store');
    Route::put('admin/{id}',[AdminController::class,'update'])->name('admin.update');
    Route::delete('admin/{id}',[AdminController::class,'destroy'])->name('admin.destroy');

    Route::get('adminaset/data',[AdminAsetController::class,'data'])->name('adminaset.data');
    Route::get('adminaset/{id}',[AdminAsetController::class,'show'])->name('adminaset.show');
    Route::post('adminaset',[AdminAsetController::class,'store'])->name('adminaset.store');
    Route::put('adminaset/{id}',[AdminAsetController::class,'update'])->name('adminaset.update');
    Route::delete('adminaset/{id}',[AdminAsetController::class,'destroy'])->name('adminaset.destroy');

    Route::get('bendahara/data',[BendaharaController::class,'data'])->name('bendahara.data');
    Route::get('bendahara/{id}',[BendaharaController::class,'show'])->name('bendahara.show');
    Route::post('bendahara',[BendaharaController::class,'store'])->name('bendahara.store');
    Route::put('bendahara/{id}',[BendaharaController::class,'update'])->name('bendahara.update');
    Route::delete('bendahara/{id}',[BendaharaController::class,'destroy'])->name('bendahara.destroy');

    Route::get('operator/data',[OperatorController::class,'data'])->name('operator.data');
    Route::get('operator/{id}',[OperatorController::class,'show'])->name('operator.show');
    Route::post('operator',[OperatorController::class,'store'])->name('operator.store');
    Route::put('operator/{id}',[OperatorController::class,'update'])->name('operator.update');
    Route::delete('operator/{id}',[OperatorController::class,'destroy'])->name('operator.destroy');

    Route::get('referensi/kib',[KibController::class,'index'])->name('kib.index');
    Route::get('referensi/jenis',[jenisController::class,'index'])->name('jenis.index');
    Route::get('referensi/status_tanah',[statusTanahController::class,'index'])->name('status_tanah.index');
    Route::get('referensi/hak_tanah',[hakTanahController::class,'index'])->name('hak_tanah.index');
    Route::get('referensi/kode_barang',[KodeBarangController::class,'index'])->name('kode_barang.index');
    Route::get('referensi/kelompok',[KelompokController::class,'index'])->name('kelompok.index');
    Route::get('referensi/satuan',[SatuanController::class,'index'])->name('satuan.index');

    Route::get('usulan/ssh',[SshController::class,'index'])->name('ssh.index');
    Route::get('usulan/asb',[AsbController::class,'index'])->name('asb.index');
    Route::get('usulan/sbu',[SbuController::class,'index'])->name('sbu.index');
    Route::get('usulan/hspk',[HspkController::class,'index'])->name('hspk.index');


    Route::get('instansi/data',[InstansiController::class,'data'])->name('instansi.data');
    Route::get('instansi/{id}',[InstansiController::class,'show'])->name('instansi.show');
    Route::put('instansi/{id}',[InstansiController::class,'update'])->name('instansi.update');
    Route::post('instansi',[InstansiController::class,'store'])->name('instansi.store');
    Route::delete('instansi/{id}',[InstansiController::class,'destroy'])->name('instansi.destroy');

    Route::get('kib/data',[KibController::class,'data'])->name('kib.data');
    Route::get('kib/{id}',[KibController::class,'show'])->name('kib.show');
    Route::put('kib/{id}',[KibController::class,'update'])->name('kib.update');
    Route::post('kib/',[KibController::class,'store'])->name('kib.store');
    Route::delete('kib/{id}',[KibController::class,'destroy'])->name('kib.destroy');

    Route::get('jenis/data',[jenisController::class,'data'])->name('jenis.data');
    Route::get('jenis/{id}',[jenisController::class,'show'])->name('jenis.show');
    Route::put('jenis/{id}',[jenisController::class,'update'])->name('jenis.update');
    Route::post('jenis/',[jenisController::class,'store'])->name('jenis.store');
    Route::delete('jenis/{id}',[jenisController::class,'destroy'])->name('jenis.destroy');

    Route::get('kelompok/data',[KelompokController::class,'data'])->name('kelompok.data');
    Route::get('kelompok/{id}',[KelompokController::class,'show'])->name('kelompok.show');
    Route::post('kelompok',[KelompokController::class,'store'])->name('kelompok.store');
    Route::put('kelompok/{id}',[KelompokController::class,'update'])->name('kelompok.update');
    Route::delete('kelompok/{id}',[KelompokController::class,'destroy'])->name('kelompok.destroy');

    Route::get('status_tanah/data',[statusTanahController::class,'data'])->name('status_tanah.data');
    Route::get('status_tanah/{id}',[statusTanahController::class,'show'])->name('status_tanah.show');
    Route::put('status_tanah/{id}',[statusTanahController::class,'update'])->name('status_tanah.update');
    Route::post('status_tanah/',[statusTanahController::class,'store'])->name('status_tanah.store');
    Route::delete('status_tanah/{id}',[statusTanahController::class,'destroy'])->name('status_tanah.destroy');

    Route::get('satuan/data',[SatuanController::class,'data'])->name('satuan.data');
    Route::get('satuan/{id}',[SatuanController::class,'show'])->name('satuan.show');
    Route::post('satuan',[SatuanController::class,'store'])->name('satuan.store');
    Route::put('satuan/{id}',[SatuanController::class,'update'])->name('satuan.update');
    Route::delete('satuan/{id}',[SatuanController::class,'destroy'])->name('satuan.destroy');

    Route::get('hak_tanah/data',[hakTanahController::class,'data'])->name('hak_tanah.data');
    Route::get('hak_tanah/{id}',[hakTanahController::class,'show'])->name('hak_tanah.show');
    Route::put('hak_tanah/{id}',[hakTanahController::class,'update'])->name('hak_tanah.update');
    Route::post('hak_tanah/',[hakTanahController::class,'store'])->name('hak_tanah.store');
    Route::delete('hak_tanah/{id}',[hakTanahController::class,'destroy'])->name('hak_tanah.destroy');


    Route::get('barang/tanah',[TanahController::class,'index'])->name('tanah.index');
    Route::get('barang/tanah/{id}',[TanahController::class,'detail'])->name('tanah.detail');
    Route::get('barang/peralatan',[PeralatanController::class,'index'])->name('peralatan.index');
    Route::get('barang/kdp',[KdpController::class,'index'])->name('kdp.index');

    Route::get('tanah/data/{id}',[TanahController::class,'data'])->name('tanah.data');
    Route::get('tanah/all',[TanahController::class,'data_all'])->name('tanah.all');
    Route::get('tanah/register/{kode}/{id_opd}',[TanahController::class,'getRegister'])->name('tanah.getRegister');
    Route::post('tanah/{id}',[TanahController::class,'store'])->name('tanah.store');
    Route::get('tanah/{id}',[TanahController::class,'show'])->name('tanah.show');
    Route::put('tanah/{id}',[TanahController::class,'update'])->name('tanah.update');
    Route::put('tanah/valid/{id}',[TanahController::class,'validasi'])->name('tanah.validasi');
    Route::put('tanah/tolak/{id}',[TanahController::class,'tolak'])->name('tanah.reject');
    Route::delete('tanah/{id}',[TanahController::class,'destroy'])->name('tanah.destroy');

    Route::get('peralatan/data',[PeralatanController::class,'data'])->name('peralatan.data');
    Route::get('peralatan/{id}',[PeralatanController::class,'show'])->name('peralatan.show');
    Route::post('peralatan',[PeralatanController::class,'store'])->name('peralatan.store');
    Route::put('peralatan/{id}',[PeralatanController::class,'update'])->name('peralatan.update');
    Route::put('peralatan/valid/{id}',[PeralatanController::class,'validasi'])->name('peralatan.validasi');
    Route::delete('peralatan/{id}',[PeralatanController::class,'destroy'])->name('peralatan.destroy');
    Route::put('peralatan/tolak/{id}',[PeralatanController::class,'tolak'])->name('peralatan.reject');
    Route::get('peralatan/register/{kode}/{id_opd}',[PeralatanController::class,'getRegister'])->name('peralatan.getRegister');

    Route::get('kdp/data',[KdpController::class,'data'])->name('kdp.data');
    Route::get('kdp/{id}',[KdpController::class,'show'])->name('kdp.show');
    Route::post('kdp',[KdpController::class,'store'])->name('kdp.store');
    Route::put('kdp/{id}',[KdpController::class,'update'])->name('kdp.update');
    Route::put('kdp/valid/{id}',[KdpController::class,'validasi'])->name('kdp.validasi');
    Route::delete('kdp/{id}',[KdpController::class,'destroy'])->name('kdp.destroy');
    Route::put('kdp/tolak/{id}',[KdpController::class,'tolak'])->name('kdp.reject');
    Route::get('kdp/register/{kode}/{id_opd}',[KdpController::class,'getRegister'])->name('kdp.getRegister');
    Route::get('kdp/kontrak/{id}/{id_opd}',[KdpController::class,'getKontrak'])->name('kdp.getKontrak');

    Route::get('kode_barang/data',[KodeBarangController::class,'data'])->name('kode_barang.data');
    Route::get('kode_barang/{id}',[KodeBarangController::class,'show'])->name('kode_barang.show');
    Route::post('kode_barang',[KodeBarangController::class,'store'])->name('kode_barang.store');
    Route::post('kode_barang/import',[KodeBarangController::class,'import'])->name('kode_barang.import');
    Route::put('kode_barang/{id}',[KodeBarangController::class,'update'])->name('kode_barang.update');
    Route::delete('kode_barang/{id}',[KodeBarangController::class,'destroy'])->name('kode_barang.destroy');

    Route::get('ssh/datas',[SshController::class,'datas'])->name('ssh.datas');
    Route::get('ssh/data',[SshController::class,'data'])->name('ssh.data');
    Route::get('ssh/{id}',[SshController::class,'show'])->name('ssh.show');
    Route::post('ssh',[SshController::class,'store'])->name('ssh.store');
    Route::put('ssh/{id}',[SshController::class,'update'])->name('ssh.update');
    Route::delete('ssh/{id}',[SshController::class,'destroy'])->name('ssh.destroy');
    Route::put('ssh/valid/{id}',[SshController::class,'validasi'])->name('ssh.validasi');
    Route::put('ssh/tolak/{id}',[SshController::class,'tolak'])->name('ssh.reject');
    Route::put('ssh/upload/{id}',[SshController::class,'upload'])->name('ssh.upload');


    Route::get('ssh/rincian/{id}',[SshController::class,'data_rincian'])->name('ssh.data_rincian');
    Route::get('ssh/rincian/item/{id}',[SshController::class,'rincian'])->name('ssh.rincian');
    Route::get('ssh/rincian/show/{id}',[SshController::class,'rincianShow'])->name('ssh.rincianShow');
    Route::post('ssh/rincian/{id}',[SshController::class,'rincianStore'])->name('ssh.rincianStore');
    Route::put('ssh/rincian/{id}',[SshController::class,'rincianUpdate'])->name('ssh.rincianUpdate');
    Route::delete('ssh/rincian/{id}',[SshController::class,'rincianDestroy'])->name('ssh.rincianDestroy');
    Route::put('ssh/rincian/valid/{id}',[SshController::class,'rincianValidasi'])->name('ssh.rincianValidasi');
    Route::put('ssh/rincian/tolak/{id}',[SshController::class,'rincianTolak'])->name('ssh.rincianReject');



    Route::get('asb/data',[AsbController::class,'data'])->name('asb.data');
    Route::get('asb/{id}',[AsbController::class,'show'])->name('asb.show');
    Route::post('asb',[AsbController::class,'store'])->name('asb.store');
    Route::put('asb/{id}',[AsbController::class,'update'])->name('asb.update');
    Route::delete('asb/{id}',[AsbController::class,'destroy'])->name('asb.destroy');
    Route::put('asb/valid/{id}',[AsbController::class,'validasi'])->name('asb.validasi');
    Route::put('asb/tolak/{id}',[AsbController::class,'tolak'])->name('asb.reject');

    Route::get('sbu/data',[SbuController::class,'data'])->name('sbu.data');
    Route::get('sbu/{id}',[SbuController::class,'show'])->name('sbu.show');
    Route::post('sbu',[SbuController::class,'store'])->name('sbu.store');
    Route::put('sbu/{id}',[SbuController::class,'update'])->name('sbu.update');
    Route::delete('sbu/{id}',[SbuController::class,'destroy'])->name('sbu.destroy');
    Route::put('sbu/valid/{id}',[SbuController::class,'validasi'])->name('sbu.validasi');
    Route::put('sbu/tolak/{id}',[SbuController::class,'tolak'])->name('sbu.reject');

    Route::get('hspk/data',[HspkController::class,'data'])->name('hspk.data');
    Route::get('hspk/{id}',[HspkController::class,'show'])->name('hspk.show');
    Route::post('hspk',[HspkController::class,'store'])->name('hspk.store');
    Route::put('hspk/{id}',[HspkController::class,'update'])->name('hspk.update');
    Route::delete('hspk/{id}',[HspkController::class,'destroy'])->name('hspk.destroy');
    Route::put('hspk/valid/{id}',[HspkController::class,'validasi'])->name('hspk.validasi');
    Route::put('hspk/tolak/{id}',[HspkController::class,'tolak'])->name('hspk.reject');
});
