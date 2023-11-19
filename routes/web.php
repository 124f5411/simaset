<?php

use App\Http\Controllers\Barang\KdpController;
use App\Http\Controllers\Barang\PeralatanController;
use App\Http\Controllers\Barang\TanahController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\getKodeController;
use App\Http\Controllers\Import\KodeBarangKontrakController;
use App\Http\Controllers\Import\KodeBarangUsulanController;
use App\Http\Controllers\Import\RekeningController;
use App\Http\Controllers\Input\RincianController;
use App\Http\Controllers\Input\UsulanController;
use App\Http\Controllers\Kib\KibAController;
use App\Http\Controllers\Kib\KibBController;
use App\Http\Controllers\KontrakController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\MonitorController;
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
use App\Http\Controllers\Referensi\KodeOpdController;
use App\Http\Controllers\Referensi\KodeUrusanController;
use App\Http\Controllers\Referensi\RekeningBelanjaController;
use App\Http\Controllers\Referensi\SatuanController;
use App\Http\Controllers\Referensi\statusTanahController;
use App\Http\Controllers\RincianKontrakController;
use App\Http\Controllers\Usulan\AsbController;
use App\Http\Controllers\Usulan\HspkController;
use App\Http\Controllers\Usulan\SbuController;
use App\Http\Controllers\Usulan\SshController;
use App\Models\RekeningBelanja;
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
    Route::get('/kontrak/opd/data',[KontrakController::class,'data_opd'])->name('kontrak.opd');

    Route::get('/kontrak/{id}',[KontrakController::class,'show'])->name('kontrak.show');
    Route::post('kontrak',[KontrakController::class,'store'])->name('kontrak.store');
    Route::put('/kontrak/{id}',[KontrakController::class,'update'])->name('kontrak.update');
    Route::delete('/kontrak/{id}',[KontrakController::class,'destroy'])->name('kontrak.destroy');

    Route::get('/kontrak/rincian/{id}',[RincianKontrakController::class,'index'])->name('kontrak.rincian.index');
    Route::get('/kontrak/rincian/show/{id}',[RincianKontrakController::class,'show'])->name('kontrak.rincian.show');
    Route::get('/kontrak/rincian/data/{id}',[RincianKontrakController::class,'data'])->name('kontrak.rincian.data');
    Route::get('/kontrak/getregister/{kode}/{id_kontrak}',[RincianKontrakController::class,'getRegister'])->name('kontrak.rincian.getregister');

    Route::put('kontrak/detail/update/{id}',[RincianKontrakController::class,'update'])->name('kontrak.detail.update');
    Route::post('kontrak/detail/{id}',[RincianKontrakController::class,'store'])->name('kontrak.rincian.store');
    Route::delete('kontrak/detail/hapus/{id}',[RincianKontrakController::class,'destroy'])->name('kontrak.detail.destroy');

    Route::get('kib/a',[KibAController::class,'index'])->name('kiba.index');
    Route::get('kib/a/data',[KibAController::class,'data'])->name('kiba.data');

    Route::get('kib/b',[KibBController::class,'index'])->name('kibb.index');
    Route::get('kib/b/data',[KibBController::class,'data'])->name('kibb.data');

    Route::get('pengaturan/instansi',[InstansiController::class,'index'])->name('instansi.index');
    Route::get('pengaturan/admin',[AdminController::class,'index'])->name('admin.index');
    Route::get('pengaturan/adminaset',[AdminAsetController::class,'index'])->name('adminaset.index');
    Route::get('pengaturan/bendahara',[BendaharaController::class,'index'])->name('bendahara.index');
    Route::get('pengaturan/operator',[OperatorController::class,'index'])->name('operator.index');

    Route::get('import/rekening/index',[RekeningController::class,'index'])->name('import.rekening.index');
    Route::post('import/rekening',[RekeningController::class,'import'])->name('import.rekening');

    Route::get('import/usulan/index',[KodeBarangUsulanController::class,'index'])->name('import.usulan.index');
    Route::post('import/usulan',[RekeningController::class,'import'])->name('import.usulan');

    Route::get('import/kontrak/index',[KodeBarangKontrakController::class,'index'])->name('import.kontrak.index');
    Route::post('import/kontrak',[KodeBarangKontrakController::class,'import'])->name('import.kontrak');


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
    Route::get('referensi/kode_barang_kontrak',[KodeBarangKontrakController::class,'list'])->name('kode_barang_kontrak.list');
    Route::get('referensi/rekening_belanja',[RekeningBelanjaController::class,'index'])->name('rekening_belanja.index');
    Route::get('referensi/kelompok',[KelompokController::class,'index'])->name('kelompok.index');
    Route::get('referensi/satuan',[SatuanController::class,'index'])->name('satuan.index');
    Route::get('referensi/urusan',[KodeUrusanController::class,'index'])->name('urusan.index');
    Route::get('referensi/opd',[KodeOpdController::class,'index'])->name('opd.index');


    Route::get('opd/data',[KodeOpdController::class,'data'])->name('opd.data');
    Route::get('opd/data/{id}',[KodeOpdController::class,'show'])->name('opd.show');
    Route::post('opd/data',[KodeOpdController::class,'store'])->name('opd.store');
    // Route::post('opd/data/biro',[KodeOpdController::class,'biroStore'])->name('opd.birostore');
    Route::put('opd/data/{id}',[KodeOpdController::class,'update'])->name('opd.update');
    Route::delete('opd/data/{id}',[KodeOpdController::class,'destroy'])->name('opd.destroy');

    Route::get('urusan/dropUrusan',[KodeUrusanController::class,'dropUrusan'])->name('urusan.dropUrusan');
    Route::get('urusan/dropSubUrusan',[KodeUrusanController::class,'dropSubUrusan'])->name('urusan.suburusan');
    Route::get('urusan/getKodeBidang/{id}',[KodeUrusanController::class,'getKodeBidang'])->name('urusan.getkodebidang');
    Route::get('urusan/kodeUrusan/{id}',[KodeUrusanController::class,'kodeUrusan'])->name('urusan.kodeUrusan');
    Route::get('urusan/kodeBiro',[KodeUrusanController::class,'kodeBiro'])->name('urusan.biro');



    Route::get('kode/barang/kontrak/data',[KodeBarangKontrakController::class,'data'])->name('kode.barang.kontrak.data');
    Route::get('kode/barang/kontrak/kelompokdropdown',[KodeBarangKontrakController::class,'dropdownKelompok'])->name('kode.barang.kontrak.kelompokdropdown');
    Route::get('kode/barang/kontrak/kelompok',[KodeBarangKontrakController::class,'getKelompok'])->name('kode.barang.kontrak.kelompok');
    Route::get('kode/barang/kontrak/objek',[KodeBarangKontrakController::class,'getObjek'])->name('kode.barang.kontrak.objek');
    Route::get('kode/barang/kontrak/jenis',[KodeBarangKontrakController::class,'getJenis'])->name('kode.barang.kontrak.jenis');
    Route::get('kode/barang/kontrak/rincian',[KodeBarangKontrakController::class,'getRincian'])->name('kode.barang.kontrak.rincian');
    Route::get('kode/barang/kontrak/subrincian',[KodeBarangKontrakController::class,'getSubRincian'])->name('kode.barang.kontrak.subrincian');
    Route::get('kode/barang/kontrak/{id}',[KodeBarangKontrakController::class,'show'])->name('kode.barang.kontrak.show');
    Route::post('kode/barang/kontrak',[KodeBarangKontrakController::class,'store'])->name('kode.barang.kontrak.store');
    Route::put('kode/barang/kontrak/{id}',[KodeBarangKontrakController::class,'update'])->name('kode.barang.kontrak.update');
    Route::delete('kode/barang/kontrak/{id}',[KodeBarangKontrakController::class,'destroy'])->name('kode.barang.kontrak.destroy');

    Route::get('ambilKode/kelompok',[getKodeController::class,'kodeKelompok'])->name('ambilkode.kelompok');
    Route::get('ambilKode/jenis/{id}',[getKodeController::class,'kodeJenis'])->name('ambilkode.jenis');
    Route::get('ambilKode/objek/{id}',[getKodeController::class,'kodeObjek'])->name('ambilkode.objek');
    Route::get('ambilKode/rincian/{id}',[getKodeController::class,'kodeRincian'])->name('ambilkode.rincian');
    Route::get('ambilKode/subrincian/{id}',[getKodeController::class,'kodeSubRincian'])->name('ambilkode.subrincian');
    Route::get('ambilKode/barang/{id}',[getKodeController::class,'kodeBarang'])->name('ambilkode.barang');

    Route::get('kodeJenis/{id}',[getKodeController::class,'getJenis'])->name('getkode.jenis');


    Route::get('kodeObjek/{id}',[getKodeController::class,'getObjek'])->name('getkode.objek');

    Route::get('kodeRincian/{id}',[getKodeController::class,'getRincian'])->name('getkode.rincian');

    Route::get('kodeSubRincian/{id}',[getKodeController::class,'getSubRincian'])->name('getkode.subrincian');
    Route::get('kodeBarang/{id}',[getKodeController::class,'getBarang'])->name('getkode.barang');

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
    Route::get('kode_barang/dropdown',[KodeBarangController::class,'dataBarang'])->name('kode_barang.dropdown');
    Route::get('kode_barang/{id}',[KodeBarangController::class,'show'])->name('kode_barang.show');
    Route::post('kode_barang',[KodeBarangController::class,'store'])->name('kode_barang.store');
    Route::post('kode_barang/import',[KodeBarangController::class,'import'])->name('kode_barang.import');
    Route::put('kode_barang/{id}',[KodeBarangController::class,'update'])->name('kode_barang.update');
    Route::delete('kode_barang/{id}',[KodeBarangController::class,'destroy'])->name('kode_barang.destroy');

    Route::get('rekening_belanja/data',[RekeningBelanjaController::class,'data'])->name('rekening_belanja.data');
    Route::get('rekening_belanja/dropdown',[RekeningBelanjaController::class,'dataRekening'])->name('rekening_belanja.dropdown');
    Route::get('rekening_belanja/{id}',[RekeningBelanjaController::class,'show'])->name('rekening_belanja.show');
    Route::post('rekening_belanja',[RekeningBelanjaController::class,'store'])->name('rekening_belanja.store');
    Route::post('rekening_belanja/import',[RekeningBelanjaController::class,'import'])->name('rekening_belanja.import');
    Route::put('rekening_belanja/{id}',[RekeningBelanjaController::class,'update'])->name('rekening_belanja.update');
    Route::delete('rekening_belanja/{id}',[RekeningBelanjaController::class,'destroy'])->name('rekening_belanja.destroy');

    Route::get('ssh/datas',[SshController::class,'datas'])->name('ssh.datas');
    Route::get('ssh/data',[SshController::class,'data'])->name('ssh.data');
    Route::get('ssh/{id}',[SshController::class,'show'])->name('ssh.show');
    Route::post('ssh',[SshController::class,'store'])->name('ssh.store');
    Route::put('ssh/{id}',[SshController::class,'update'])->name('ssh.update');
    Route::delete('ssh/{id}',[SshController::class,'destroy'])->name('ssh.destroy');
    Route::put('ssh/valid/{id}',[SshController::class,'validasi'])->name('ssh.validasi');
    Route::put('ssh/tolak/{id}',[SshController::class,'tolak'])->name('ssh.reject');
    Route::put('ssh/upload/{id}',[SshController::class,'upload'])->name('ssh.upload');
    Route::get('ssh/pdf/{id}',[SshController::class,'exportPDF'])->name('ssh.export');


    Route::get('ssh/opd/{id}',[SshController::class,'instansi'])->name('ssh.instansi');
    Route::get('ssh/opd/rinci/{id}',[SshController::class,'asetRinci'])->name('ssh.asetRinci');
    Route::get('ssh/opd/rincian/{id}',[SshController::class,'rincianAset'])->name('ssh.rincianAset');
    Route::get('ssh/opd/aset/{id}',[SshController::class,'asetInstansi'])->name('ssh.asetInstansi');
    Route::post('ssh/export',[SshController::class,'export'])->name('ssh.exportForm');
    Route::get('ssh/opd/aset/export/{id}',[SshController::class,'exportAsetInstansi'])->name('ssh.exportAsetInstansi');
    Route::get('ssh/export/pdf/{tahun}/{jenis}',[SshController::class,'exportAset'])->name('ssh.exportAset');

    Route::get('ssh/rincian/{id}',[SshController::class,'data_rincian'])->name('ssh.data_rincian');
    Route::get('ssh/rincian/item/{id}',[SshController::class,'rincian'])->name('ssh.rincian');
    Route::get('ssh/rincian/show/{id}',[SshController::class,'rincianShow'])->name('ssh.rincianShow');
    Route::post('ssh/rincian/{id}',[SshController::class,'rincianStore'])->name('ssh.rincianStore');
    Route::put('ssh/rincian/{id}',[SshController::class,'rincianUpdate'])->name('ssh.rincianUpdate');
    Route::delete('ssh/rincian/{id}',[SshController::class,'rincianDestroy'])->name('ssh.rincianDestroy');
    Route::put('ssh/rincian/valid/{id}',[SshController::class,'rincianValidasi'])->name('ssh.rincianValidasi');
    Route::put('ssh/rincian/tolak/{id}',[SshController::class,'rincianTolak'])->name('ssh.rincianReject');


    Route::get('asb/datas',[AsbController::class,'datas'])->name('asb.datas');
    Route::get('asb/data',[AsbController::class,'data'])->name('asb.data');
    Route::get('asb/{id}',[AsbController::class,'show'])->name('asb.show');
    Route::post('asb',[AsbController::class,'store'])->name('asb.store');
    Route::put('asb/{id}',[AsbController::class,'update'])->name('asb.update');
    Route::delete('asb/{id}',[AsbController::class,'destroy'])->name('asb.destroy');
    Route::put('asb/valid/{id}',[AsbController::class,'validasi'])->name('asb.validasi');
    Route::put('asb/tolak/{id}',[AsbController::class,'tolak'])->name('asb.reject');
    Route::put('asb/upload/{id}',[AsbController::class,'upload'])->name('asb.upload');
    Route::get('asb/pdf/{id}',[AsbController::class,'exportPDF'])->name('asb.export');

    Route::get('asb/opd/{id}',[AsbController::class,'instansi'])->name('asb.instansi');
    Route::get('asb/opd/rinci/{id}',[AsbController::class,'asetRinci'])->name('asb.asetRinci');
    Route::get('asb/opd/rincian/{id}',[AsbController::class,'rincianAset'])->name('asb.rincianAset');
    Route::get('asb/opd/aset/{id}',[AsbController::class,'asetInstansi'])->name('asb.asetInstansi');
    Route::post('asb/export',[AsbController::class,'export'])->name('asb.exportForm');
    Route::get('asb/opd/aset/export/{id}',[AsbController::class,'exportAsetInstansi'])->name('asb.exportAsetInstansi');
    Route::get('asb/export/pdf/{tahun}/{jenis}',[AsbController::class,'exportAset'])->name('asb.exportAset');

    Route::get('asb/rincian/{id}',[AsbController::class,'data_rincian'])->name('asb.data_rincian');
    Route::get('asb/rincian/item/{id}',[AsbController::class,'rincian'])->name('asb.rincian');
    Route::get('asb/rincian/show/{id}',[AsbController::class,'rincianShow'])->name('asb.rincianShow');
    Route::post('asb/rincian/{id}',[AsbController::class,'rincianStore'])->name('asb.rincianStore');
    Route::put('asb/rincian/{id}',[AsbController::class,'rincianUpdate'])->name('asb.rincianUpdate');
    Route::delete('asb/rincian/{id}',[AsbController::class,'rincianDestroy'])->name('asb.rincianDestroy');
    Route::put('asb/rincian/valid/{id}',[AsbController::class,'rincianValidasi'])->name('asb.rincianValidasi');
    Route::put('asb/rincian/tolak/{id}',[AsbController::class,'rincianTolak'])->name('asb.rincianReject');


    Route::get('sbu/datas',[SbuController::class,'datas'])->name('sbu.datas');
    Route::get('sbu/data',[SbuController::class,'data'])->name('sbu.data');
    Route::get('sbu/{id}',[SbuController::class,'show'])->name('sbu.show');
    Route::post('sbu',[SbuController::class,'store'])->name('sbu.store');
    Route::put('sbu/{id}',[SbuController::class,'update'])->name('sbu.update');
    Route::delete('sbu/{id}',[SbuController::class,'destroy'])->name('sbu.destroy');
    Route::put('sbu/valid/{id}',[SbuController::class,'validasi'])->name('sbu.validasi');
    Route::put('sbu/tolak/{id}',[SbuController::class,'tolak'])->name('sbu.reject');
    Route::put('sbu/upload/{id}',[SbuController::class,'upload'])->name('sbu.upload');
    Route::get('sbu/pdf/{id}',[SbuController::class,'exportPDF'])->name('sbu.export');

    Route::get('sbu/opd/{id}',[SbuController::class,'instansi'])->name('sbu.instansi');
    Route::get('sbu/opd/rinci/{id}',[SbuController::class,'asetRinci'])->name('sbu.asetRinci');
    Route::get('sbu/opd/rincian/{id}',[SbuController::class,'rincianAset'])->name('sbu.rincianAset');
    Route::get('sbu/opd/aset/{id}',[SbuController::class,'asetInstansi'])->name('sbu.asetInstansi');
    Route::post('sbu/export',[SbuController::class,'export'])->name('sbu.exportForm');
    Route::get('sbu/opd/aset/export/{id}',[SbuController::class,'exportAsetInstansi'])->name('sbu.exportAsetInstansi');
    Route::get('sbu/export/pdf/{tahun}/{jenis}',[SbuController::class,'exportAset'])->name('sbu.exportAset');

    Route::get('sbu/rincian/{id}',[SbuController::class,'data_rincian'])->name('sbu.data_rincian');
    Route::get('sbu/rincian/item/{id}',[SbuController::class,'rincian'])->name('sbu.rincian');
    Route::get('sbu/rincian/show/{id}',[SbuController::class,'rincianShow'])->name('sbu.rincianShow');
    Route::post('sbu/rincian/{id}',[SbuController::class,'rincianStore'])->name('sbu.rincianStore');
    Route::put('sbu/rincian/{id}',[SbuController::class,'rincianUpdate'])->name('sbu.rincianUpdate');
    Route::delete('sbu/rincian/{id}',[SbuController::class,'rincianDestroy'])->name('sbu.rincianDestroy');
    Route::put('sbu/rincian/valid/{id}',[SbuController::class,'rincianValidasi'])->name('sbu.rincianValidasi');
    Route::put('sbu/rincian/tolak/{id}',[SbuController::class,'rincianTolak'])->name('sbu.rincianReject');

    Route::get('hspk/datas',[HspkController::class,'datas'])->name('hspk.datas');
    Route::get('hspk/data',[HspkController::class,'data'])->name('hspk.data');
    Route::get('hspk/{id}',[HspkController::class,'show'])->name('hspk.show');
    Route::post('hspk',[HspkController::class,'store'])->name('hspk.store');
    Route::put('hspk/{id}',[HspkController::class,'update'])->name('hspk.update');
    Route::delete('hspk/{id}',[HspkController::class,'destroy'])->name('hspk.destroy');
    Route::put('hspk/valid/{id}',[HspkController::class,'validasi'])->name('hspk.validasi');
    Route::put('hspk/tolak/{id}',[HspkController::class,'tolak'])->name('hspk.reject');
    Route::put('hspk/upload/{id}',[HspkController::class,'upload'])->name('hspk.upload');
    Route::get('hspk/pdf/{id}',[HspkController::class,'exportPDF'])->name('hspk.export');

    Route::get('hspk/opd/{id}',[HspkController::class,'instansi'])->name('hspk.instansi');
    Route::get('hspk/opd/rinci/{id}',[HspkController::class,'asetRinci'])->name('hspk.asetRinci');
    Route::get('hspk/opd/rincian/{id}',[HspkController::class,'rincianAset'])->name('hspk.rincianAset');
    Route::get('hspk/opd/aset/{id}',[HspkController::class,'asetInstansi'])->name('hspk.asetInstansi');
    Route::post('hspk/export',[HspkController::class,'export'])->name('hspk.exportForm');
    Route::get('hspk/opd/aset/export/{id}',[HspkController::class,'exportAsetInstansi'])->name('hspk.exportAsetInstansi');
    Route::get('hspk/export/pdf/{tahun}/{jenis}',[HspkController::class,'exportAset'])->name('hspk.exportAset');

    Route::get('hspk/rincian/{id}',[HspkController::class,'data_rincian'])->name('hspk.data_rincian');
    Route::get('hspk/rincian/item/{id}',[HspkController::class,'rincian'])->name('hspk.rincian');
    Route::get('hspk/rincian/show/{id}',[HspkController::class,'rincianShow'])->name('hspk.rincianShow');
    Route::post('hspk/rincian/{id}',[HspkController::class,'rincianStore'])->name('hspk.rincianStore');
    Route::put('hspk/rincian/{id}',[HspkController::class,'rincianUpdate'])->name('hspk.rincianUpdate');
    Route::delete('hspk/rincian/{id}',[HspkController::class,'rincianDestroy'])->name('hspk.rincianDestroy');
    Route::put('hspk/rincian/valid/{id}',[HspkController::class,'rincianValidasi'])->name('hspk.rincianValidasi');
    Route::put('hspk/rincian/tolak/{id}',[HspkController::class,'rincianTolak'])->name('hspk.rincianReject');


    Route::get('monitor/{any}',[MonitorController::class,'index'])->name('monitor.index');
    Route::post('monitor/data/{any}',[MonitorController::class,'getData'])->name('monitor.data');

    Route::get('usulan',[UsulanController::class,'index'])->name('usulan.index');
    Route::get('usulan/data',[UsulanController::class,'data'])->name('usulan.data');
    Route::post('usulan',[UsulanController::class,'store'])->name('usulan.store');
    Route::get('usulan/{id}',[UsulanController::class,'show'])->name('usulan.show');
    Route::put('usulan/{id}',[UsulanController::class,'update'])->name('usulan.update');
    Route::delete('usulan/{id}',[UsulanController::class,'destroy'])->name('usulan.destroy');
    Route::put('usulan/upload/{id}',[UsulanController::class,'upload'])->name('usulan.upload');
    Route::put('usulan/valid/{id}',[UsulanController::class,'validasi'])->name('usulan.validasi');


    Route::get('rincian/{id}',[RincianController::class,'index'])->name('rincian.index');
    Route::post('usulan/rincian/data/{id}',[RincianController::class,'data'])->name('rincian.data');
    Route::post('usulan/rincian/{id}',[RincianController::class,'store'])->name('rincian.store');
    Route::get('usulan/rincian/{id}',[RincianController::class,'show'])->name('rincian.show');
    Route::put('usulan/rincian/{id}',[RincianController::class,'update'])->name('rincian.update');
    Route::delete('usulan/rincian/{id}',[RincianController::class,'destroy'])->name('rincian.destroy');

    Route::get('rincian/rekening/{id}',[RincianController::class,'showRekening'])->name('rincian.rekening');

    Route::post('usulan/rekening/{id}',[RincianController::class,'detailStore'])->name('rincian.rekening.store');
    Route::delete('usulan/rekening/{id}',[RincianController::class,'detailDestroy'])->name('rincian.rekening.destroy');

    Route::get('usulan/rincian/pdf/{id}',[RincianController::class,'export'])->name('rincian.export')->withoutMiddleware(['auth']);
});
