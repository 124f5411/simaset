<?php

namespace App\Http\Controllers\Usulan;

use App\Http\Controllers\Controller;
use App\Models\dataAsb;
use App\Models\DataOpd;
use App\Models\DataSatuan;
use App\Models\dataSsh;
use App\Models\DetailRincianUsulan;
use App\Models\KodeBarang;
use App\Models\RekeningBelanja;
use App\Models\TtdSetting;
use App\Models\UsulanSsh;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class AsbController extends Controller
{
    public function index(){
        // if(Auth::user()->level == 'aset'){
        //     $kode_barang = KodeBarang::where('kelompok','=','3')->get();
        //     $rekening = RekeningBelanja::all();
        //     $satuan = DataSatuan::all();
        //     $instansi = DataOpd::all();
        //     return view('usulan.asb.aset',[
        //         'title' => 'Usulan',
        //         'page' => 'ASB',
        //         'drops' => [
        //             'kode_barang' => $kode_barang,
        //             'rekening' => $rekening,
        //             'instansi' => $instansi,
        //             'satuan' => $satuan
        //         ]
        //     ]);
        // }

        if(Auth::user()->level == 'aset'){
            $instansi = DataOpd::all();
            $tahun  = UsulanSsh::select('tahun')->where('id_kelompok','=','3')->where('status','=','1')->groupBy('tahun')->get();
            $jenis  = UsulanSsh::select('induk_perubahan')->where('id_kelompok','=','3')->where('status','=','1')->groupBy('induk_perubahan')->get();
            return view('usulan.asb.aset.index',[
                'title' => 'Usulan',
                'page' => 'ASB',
                'drops' => [
                    'instansi' => $instansi,
                    'tahun' => $tahun,
                    'jenis' => $jenis
                ]
            ]);
        }
        return view('usulan.asb.index',[
            'title' => 'Usulan',
            'page' => 'ASB'
        ]);
    }

    public function rincian($id){
        $usulan = UsulanSsh::find(decrypt($id));
        $kode_barang = KodeBarang::where('kelompok','=','3')->get();
        $rekening = RekeningBelanja::all();
        $satuan = DataSatuan::all();
        $instansi = DataOpd::all();
        return view('usulan.asb.rincian',[
            'title' => 'Usulan',
            'page' => 'ASB',
            'drops' => [
                'kode_barang' => $kode_barang,
                'rekening' => $rekening,
                'instansi' => $instansi,
                'satuan' => $satuan
            ],
            'usulan_status' => $usulan->status
        ]);
    }

    public function datas(){
        $asb = UsulanSsh::select('usulan_ssh.*','_data_ssh.id as id_ssh','_data_ssh.id_kode','_data_ssh.id_usulan','_data_ssh.spesifikasi','_data_ssh.id_satuan','_data_ssh.harga','_data_ssh.status as s_status','_data_ssh.id_rekening','_data_ssh.uraian')
                        ->join('_data_ssh','usulan_ssh.id','=','_data_ssh.id_usulan')
                        ->where('usulan_ssh.id_kelompok','=',3)
                        ->whereIn('_data_ssh.status',['1','2'])->get();

        return datatables()->of($asb)
                ->addIndexColumn()
                ->addColumn('q_opd',function($asb) {
                    return getValue("opd","data_opd","id = ".$asb->id_opd);
                })
                ->addColumn('usulan',function($asb) {
                    if(is_null($asb->induk_perubahan)){
                        $usulan = "Mohon diubah";
                    }

                    if($asb->induk_perubahan == "1"){
                        $usulan = "Induk";
                    }

                    if($asb->induk_perubahan == "2"){
                        $usulan = "Perubahan";
                    }
                    return $usulan;
                })
                ->addColumn('uraian_id',function($ssh) {
                    return getValue("uraian","referensi_kode_barang","id = ".$ssh->id_kode);
                })
                ->addColumn('kode_barang',function($ssh) {
                    return getValue("kode_barang","referensi_kode_barang","id = ".$ssh->id_kode);
                })
                ->addColumn('rekening_belanja',function($ssh) {
                    return getValue("kode_akun","referensi_rekening_belanja","id = ".$ssh->id_rekening);
                })
                ->addColumn('satuan',function($asb){
                    return getValue("nm_satuan","data_satuan","id = ".$asb->id_satuan);
                })
                ->addColumn('harga',function($asb) {
                    return "Rp. ".number_format($asb->harga, 2, ",", ".");
                })
                ->addColumn('tkdn',function($asb) {
                    return (!is_null($asb->tkdn)) ? $asb->tkdn : "";
                })
                ->addColumn('dokumen',function($asb){
                    $dok = '
                    <div class="btn-group">
                        <a href="'.asset('upload/asb/'.$asb->ssd_dokumen).'" target="_blank" class="btn btn-sm btn-danger btn-icon-split">
                            <span class="icon text-white-50">
                                <i class="fas fa-file-pdf"></i>
                            </span>
                            <span class="text">PDF</span>
                        </a>
                    </div>
                    ';
                    return $dok;
                })
                ->addColumn('aksi', function($asb){
                    if(Auth::user()->level == 'aset'){
                        if($asb->s_status == '1'){
                            $aksi = '
                            <div class="btn-group">
                                <a href="javascript:void(0)" onclick="verifAsb(`'.route('asb.rincianValidasi',$asb->id_ssh).'`)" class="btn btn-sm btn-primary" title="Validasi"><i class="fas fa-paper-plane"></i></a>
                                <a href="javascript:void(0)" onclick="tolakAsb(`'.route('asb.rincianReject',$asb->id_ssh).'`)" class="btn btn-sm btn-danger" title="Tolak"><i class="fas fa-redo"></i></a>
                            </div>
                            ';
                        }
                        if($asb->s_status == '2'){
                            $aksi = '
                            <div class="btn-group">
                                <a href="javascript:void(0)" onclick="editAsb(`'.route('asb.rincianUpdate',$asb->id_ssh).'`,'.$asb->id_ssh.')" class="btn btn-sm btn-warning" title="Ubah" ><i class="fas fa-edit"></i></a>
                            </div>
                            ';
                        }
                    }
                    return $aksi;
                })
                ->rawColumns(['aksi','dokumen'])
                ->make(true);
    }

    public function data_rincian($id){
        $asb = dataAsb::where('id_usulan','=',$id)->get();
        return datatables()->of($asb)
                ->addIndexColumn()
                ->addColumn('uraian_id',function($asb) {
                    return getValue("uraian","referensi_kode_barang","id = ".$asb->id_kode);
                })
                ->addColumn('kode_barang',function($asb) {
                    return getValue("kode_barang","referensi_kode_barang","id = ".$asb->id_kode);
                })
                ->addColumn('rekening_belanja',function($asb) {
                    return getValue("kode_akun","referensi_rekening_belanja","id = ".$asb->id_rekening);
                })
                ->addColumn('satuan',function($asb){
                    return getValue("nm_satuan","data_satuan","id = ".$asb->id_satuan);
                })
                ->addColumn('harga',function($asb) {
                    return "Rp. ".number_format($asb->harga, 2, ",", ".");
                })
                ->addColumn('tkdn',function($asb) {
                    return (!is_null($asb->tkdn)) ? $asb->tkdn : "";
                })
                ->addColumn('aksi', function($asb){
                    if((Auth::user()->level == 'operator' || Auth::user()->level == 'bendahara')){
                        if($asb->status == '0'){
                            $aksi = '
                            <div class="btn-group">
                                <a href="javascript:void(0)" onclick="editAsb(`'.route('asb.rincianUpdate',$asb->id).'`,'.$asb->id.')" class="btn btn-sm btn-warning" title="Ubah" ><i class="fas fa-edit"></i></a>
                                <a href="javascript:void(0)" onclick="hapusAsb(`'.route('asb.rincianDestroy',$asb->id).'`)" class="btn btn-sm btn-danger" title="Hapus"><i class="fas fa-trash"></i></a>
                            </div>
                            ';
                        }
                        if($asb->status == '1'){
                            $aksi = 'Proccesed';
                        }

                        if($asb->status == '2'){
                            $aksi = 'Valid';
                        }
                    }
                    return $aksi;
                })
                ->rawColumns(['aksi'])
                ->make(true);
    }

    public function data(){
        $asb = UsulanSsh::where('id_kelompok','=','3')->where('id_opd','=',Auth::user()->id_opd)->get();
        return datatables()->of($asb)
                ->addIndexColumn()
                ->addColumn('q_opd',function($asb) {
                    return getValue("opd","data_opd","id = ".$asb->id_opd);
                })
                ->addColumn('usulan',function($asb) {
                    if(is_null($asb->induk_perubahan)){
                        $usulan = "Mohon diubah";
                    }

                    if($asb->induk_perubahan == "1"){
                        $usulan = "Induk";
                    }

                    if($asb->induk_perubahan == "2"){
                        $usulan = "Perubahan";
                    }
                    return $usulan;
                })
                ->addColumn('dokumen',function($asb) {
                    if(is_null($asb->ssd_dokumen)){
                        $aksi = '
                            <a href="javascript:void(0)" onclick="asbUpload(`'.route('asb.upload',$asb->id).'`)" class="btn btn-sm btn-success btn-icon-split">
                                <span class="icon text-white-50">
                                    <i class="fas fa-upload"></i>
                                </span>
                                <span class="text">Upload</span>
                            </a>
                        ';
                    }else{
                        $aksi = '
                        <div class="btn-group">
                            <a href="'.asset('upload/asb/'.$asb->ssd_dokumen).'" target="_blank" class="btn btn-sm btn-danger btn-icon-split">
                                <span class="icon text-white-50">
                                    <i class="fas fa-file-pdf"></i>
                                </span>
                                <span class="text">PDF</span>
                            </a>
                            <a href="javascript:void(0)" onclick="asbUpload(`'.route('asb.upload',$asb->id).'`)" class="btn btn-sm btn-success btn-icon-split">
                                <span class="icon text-white-50">
                                    <i class="fas fa-upload"></i>
                                </span>
                                <span class="text">Ubah</span>
                            </a>
                        </div>
                        ';
                    }
                    return $aksi;
                })
                ->addColumn('rincian',function($asb) {
                    return '
                    <a href="'.route('asb.rincian',encrypt($asb->id)).'" class="btn btn-sm btn-success btn-icon-split">
                        <span class="icon text-white-50">
                            <i class="fas fa-eye"></i>
                        </span>
                        <span class="text">Rincian</span>
                    </a>
                    ';
                })
                ->addColumn('aksi', function($asb){
                    if((Auth::user()->level == 'operator' || Auth::user()->level == 'bendahara')){
                        if($asb->status == '0'){
                            if(is_null($asb->ssd_dokumen)){
                                $aksi = '
                                    <div class="btn-group">
                                        <a href="javascript:void(0)" onclick="editAsb(`'.route('asb.update',$asb->id).'`,'.$asb->id.')" class="btn btn-sm btn-warning" title="Ubah" ><i class="fas fa-edit"></i></a>
                                        <a href="javascript:void(0)" onclick="hapusAsb(`'.route('asb.destroy',$asb->id).'`)" class="btn btn-sm btn-danger" title="Hapus"><i class="fas fa-trash"></i></a>
                                    </div>
                                    ';
                            }else{
                                $aksi = '
                                <div class="btn-group">
                                    <a href="javascript:void(0)" onclick="editAsb(`'.route('asb.update',$asb->id).'`,'.$asb->id.')" class="btn btn-sm btn-warning" title="Ubah" ><i class="fas fa-edit"></i></a>
                                    <a href="javascript:void(0)" onclick="hapusAsb(`'.route('asb.destroy',$asb->id).'`)" class="btn btn-sm btn-danger" title="Hapus"><i class="fas fa-trash"></i></a>
                                    <a href="javascript:void(0)" onclick="verifAsb(`'.route('asb.validasi',$asb->id).'`)" class="btn btn-sm btn-primary" title="Validasi"><i class="fas fa-paper-plane"></i></a>
                                </div>
                                ';
                            }
                        }
                        if($asb->status == '1'){
                            $aksi = 'Terkirim';
                        }

                        if($asb->status == '2'){
                            $aksi = 'Valid';
                        }

                        if($asb->status == '3'){
                            // $aksi = 'Ditolak, mohon cek rincian';
                            $aksi = '
                                <div class="btn-group">
                                    <a href="javascript:void(0)" onclick="editAsb(`'.route('asb.update',$asb->id).'`,'.$asb->id.')" class="btn btn-sm btn-warning" title="Ubah" ><i class="fas fa-edit"></i></a>
                                    <a href="javascript:void(0)" onclick="hapusAsb(`'.route('asb.destroy',$asb->id).'`)" class="btn btn-sm btn-danger" title="Hapus"><i class="fas fa-trash"></i></a>
                                    <a href="javascript:void(0)" onclick="verifAsb(`'.route('asb.validasi',$asb->id).'`)" class="btn btn-sm btn-primary" title="Validasi"><i class="fas fa-paper-plane"></i></a>
                                </div><br>
                                <div class="badge badge-danger mt-2 text-wrap" style="width: 6rem;">
                                    Ditolak<br>cek rincian
                                </div>
                                ';
                        }
                    }
                    return $aksi;
                })
                ->rawColumns(['aksi','rincian','dokumen'])
                ->make(true);
    }

    public function store(Request $request){
        $field = [
            'tahun' => ['required'],
            'induk_perubahan' => ['required']
        ];

        $pesan = [
            'tahun.required' => 'Tahun ASB tidak boleh kosong <br />',
            'induk_perubahan.required' => 'Jenis usulan tidak boleh kosong <br />',
        ];
        $this->validate($request, $field, $pesan);
        $data = [
            'id_opd' => Auth::user()->id_opd,
            'tahun' => $request->tahun,
            'induk_perubahan' => $request->induk_perubahan,
            'id_kelompok' => 3,
            'status' => '0'
        ];

        UsulanSsh::create($data);
        return response()->json('ASB berhasil dibuat',200);
    }

    public function rincianStore(Request $request,$id){
        $field = [
            'id_kode' => ['required'],
            'id_rekening' => ['required'],
            'uraian' => ['required'],
            'spesifikasi' => ['required'],
            'id_satuan' => ['required'],
            'harga' => ['required'],
            'tkdn' => ['required']
        ];

        $pesan = [
            'id_kode.required' => 'Barang tidak boleh kosong <br />',
            'id_rekening.required' => 'Rekening belanja tidak boleh kosong <br />',
            'uraian.required' => 'Uraian tidak boleh kosong <br />',
            'spesifikasi.required' => 'Spesifikasi tidak boleh kosong <br />',
            'id_satuan.required' => 'Satuan tidak boleh kosong <br />',
            'harga.required' => 'Harga tidak boleh kosong <br />',
            'tkdn.required' => 'T K D N tidak boleh kosong <br />',
        ];
        $this->validate($request, $field, $pesan);
        $data = [
            'id_kode' => $request->id_kode,
            'tkdn' => $request->tkdn,
            'id_usulan' => $id,
            'spesifikasi' => $request->spesifikasi,
            'uraian' => $request->uraian,
            'harga' => $request->harga,
            'id_satuan' => $request->id_satuan,
            'status' => '0'
        ];

        dataAsb::create($data);
        return response()->json('item ASB berhasil ditambahkan',200);
    }

    public function show($id){
        $ssh = UsulanSsh::find($id);
        return response()->json($ssh);
    }

    public function rincianShow($id){
        $asb = dataAsb::find($id);
        return response()->json($asb);
    }

    public function update(Request $request,$id){
        $asb = UsulanSsh::find($id);
        $field = [
            'tahun' => ['required'],
            'induk_perubahan' => ['required']
        ];

        $pesan = [
            'tahun.required' => 'Tahun ASB tidak boleh kosong <br />',
            'induk_perubahan.required' => 'Jenis usulan tidak boleh kosong <br />',
        ];
        $this->validate($request, $field, $pesan);
        $data = [
            'id_opd' => Auth::user()->id_opd,
            'tahun' => $request->tahun,
            'induk_perubahan' => $request->induk_perubahan,
            'id_kelompok' => 3
        ];
        $asb->update($data);
        return response()->json('ASB berhasil diubah',200);
    }

    public function rincianUpdate(Request $request,$id){
        $asb = dataAsb::find($id);
        $field = [
            'id_kode' => ['required'],
            'id_rekening' => ['required'],
            'spesifikasi' => ['required'],
            'uraian' => ['required'],
            'id_satuan' => ['required'],
            'harga' => ['required'],
            'tkdn' => ['required']
        ];

        $pesan = [
            'id_kode.required' => 'Barang tidak boleh kosong <br />',
            'id_rekening.required' => 'Rekening belanja tidak boleh kosong <br />',
            'spesifikasi.required' => 'Spesifikasi tidak boleh kosong <br />',
            'uraian.required' => 'Uraian tidak boleh kosong <br />',
            'id_satuan.required' => 'Satuan tidak boleh kosong <br />',
            'harga.required' => 'Harga tidak boleh kosong <br />',
            'tkdn.required' => 'T K D N tidak boleh kosong <br />',
        ];
        $this->validate($request, $field, $pesan);
        $data = [
            'id_kode' => $request->id_kode,
            'tkdn' => $request->tkdn,
            'uraian' => $request->uraian,
            'spesifikasi' => $request->spesifikasi,
            'harga' => $request->harga,
            'id_satuan' => $request->id_satuan
        ];

        $asb->update($data);
        return response()->json('item ASB berhasil diubah',200);
    }

    public function destroy($id){
        $asb = UsulanSsh::find($id);
        $asb->delete();
        return response()->json('SSH berhasil dihapus', 204);
    }

    public function rincianDestroy($id){
        $asb = dataAsb::find($id);
        $asb->delete();
        return response()->json('item ASB berhasil dihapus', 204);
    }

    public function upload(Request $request,$id){
        $asb = UsulanSsh::find($id);
        $filter = [
            'ssd_dokumen' => 'required|mimes:pdf',
        ];
        $pesan = [
            'ssd_dokumen.required' => 'Dokumen SSH tidak boleh kosong <br />',
            'ssd_dokumen.mimes' => 'Dokumen SSH harus berformat PDF <br />'
        ];
        $this->validate($request, $filter, $pesan);
        $dok = $request->file('ssd_dokumen');
        $nm = 'asb-'.date('Y').'-'.date('Ymdhis').'.'.$dok->getClientOriginalExtension();
        if(!is_null($asb->ssd_dokumen)){
            unlink(public_path()."/upload/asb/".$asb->ssd_dokumen);
        }
        $dok->move(public_path('upload/asb'),$nm);
        $data = [
            'ssd_dokumen' => $nm
        ];
        $asb->update($data);
        return response()->json('dokumen ASB berhasil diupload',200);
    }

    public function validasi($id){
        $asb = UsulanSsh::find($id);
        if(Auth::user()->level == 'operator' || Auth::user()->level == 'bendahara'){
            $verif = ['status' => '1'];
            $respon = 'usulan ASB berhasil dikirim ke admin Aset';
            $item_status = ['status' => '1'];
            dataAsb::where('id_usulan','=',$asb->id)->update($item_status);
        }elseif(Auth::user()->level == 'aset'){
            $verif = ['status' => '2'];
            $respon = 'usulan ASB telah diterima';
        }
        $asb->update($verif);
        return response()->json($respon,200);
    }

    public function rincianValidasi($id){
        $asb = dataSsh::find($id);
        $verif = ['status' => '2'];
        $respon = 'usulan ASB telah diterima';
        $asb->update($verif);
        return response()->json($respon,200);
    }

    public function tolak($id){
        $asb = UsulanSsh::find($id);
        $data = [
            'status' => '0'
        ];
        $asb->update($data);
        return response()->json('usulan ASB berhasil dikembalikan',200);
    }

    public function rincianTolak(Request $request,$id){
        $id_usulan = getValue("id_usulan","_data_ssh"," id = ".$id);
        $asb = dataAsb::find($id);
        $filter = [
            'keterangan' => 'required'
        ];
        $pesan = [
            'keterangan.required' => 'Keterangan tolak tidak boleh kosong <br />'
        ];
        $this->validate($request, $filter, $pesan);

        $data = [
            'keterangan' => $request->keterangan
        ];
        $asb->update($data);

        dataAsb::where('id_usulan','=',$id_usulan)->update(['status' => '0']);

        $usulan = [
            'status' => '3'
        ];

        UsulanSsh::where('id','=',$asb->id_usulan)->update($usulan);
        return response()->json('usulan ASB berhasil dikembalikan',200);
    }

    public function exportPDF($id){
        $asb = dataSsh::where('id_usulan','=',decrypt($id))->get();
        $usulan = UsulanSsh::find(decrypt($id));
        $jenis = ($usulan->induk_perubahan == "1") ? "induk" : "perubahan";

        $ttd = TtdSetting::where('id_opd','=',Auth::user()->id_opd)->first();
        $opd = getValue("opd","data_opd"," id =".Auth::user()->id_opd);
        $data = [
            'tahun' => $usulan->tahun,
            'instansi' => "PEMERINTAH PROVINSI PAPUA BARAT DAYA",
            'title' => "USULAN ".strtoupper($jenis)." ANALISIS STANDAR BELANJA TAHUN ANGGARAN",
            'asb' => $asb,
            'ttd' => $ttd,
            'opd' => $opd
        ];
        $pdf = PDF::loadView('pdf.asb',$data);
        $pdf->setPaper('legal', 'landscape');
        return $pdf->stream('asb-'.$jenis.'-'.Auth::user()->id_opd.'-TA-'.$usulan->tahun.'-' . date('Y-m-d H:i:s') . '.pdf');
    }

    public function instansi($id){
        return view('usulan.asb.aset.instansi',[
            'title' => 'Usulan',
            'page' => 'ASB',
            'opd' => strtoupper(getValue("opd","data_opd"," id = ".decrypt($id)))
        ]);
    }

    public function asetInstansi($id){
        $asb = UsulanSsh::where('id_kelompok','=','3')->where('id_opd','=',decrypt($id))->whereIn('status',['1','2'])->get();
        return datatables()->of($asb)
                ->addIndexColumn()
                ->addColumn('usulan',function($asb) {
                    if(is_null($asb->induk_perubahan)){
                        $usulan = "Mohon diubah";
                    }

                    if($asb->induk_perubahan == "1"){
                        $usulan = "Induk";
                    }

                    if($asb->induk_perubahan == "2"){
                        $usulan = "Perubahan";
                    }
                    return $usulan;
                })
                ->addColumn('dokumen',function($asb){
                    $dok = '
                    <div class="btn-group">
                        <a href="'.asset('upload/usulan/'.$asb->ssd_dokumen).'" target="_blank" class="btn btn-sm btn-danger btn-icon-split">
                            <span class="icon text-white-50">
                                <i class="fas fa-file-pdf"></i>
                            </span>
                            <span class="text">PDF</span>
                        </a>
                    </div>
                    ';
                    return $dok;
                })
                ->addColumn('rincian',function($asb) {
                    return '
                    <a href="'.route('asb.asetRinci',encrypt($asb->id)).'" class="btn btn-sm btn-success btn-icon-split">
                        <span class="icon text-white-50">
                            <i class="fas fa-eye"></i>
                        </span>
                        <span class="text">Rincian</span>
                    </a>
                    ';
                })
                ->addColumn('export', function($asb){
                    $aksi = '
                    <a href="javascript:void(0)" onclick="window.open(`'.route('asb.exportAsetInstansi',encrypt($asb->id)).'`,`Title`,`directories=no,titlebar=no,toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=1024,height = 720`)" class="btn btn-sm btn-danger btn-icon-split mt-2 float-right">
                        <span class="icon text-white-50">
                            <i class="fas fa-file-pdf"></i>
                        </span>
                        <span class="text">EXPORT</span>
                    </a>
                    ';
                    return $aksi;
                })
                ->rawColumns(['export','rincian','dokumen'])
                ->make(true);
    }

    public function rincianAset($id){
        $asb = dataAsb::where('id_usulan','=',decrypt($id))->where('id_kelompok','=','3')->whereIn('status',['1','2'])->get();
        return datatables()->of($asb)
                ->addIndexColumn()
                ->addColumn('uraian_id',function($asb) {
                    return getValue("uraian","referensi_kode_barang","id = ".$asb->id_kode);
                })
                ->addColumn('kode_barang',function($asb) {
                    return getValue("kode_barang","referensi_kode_barang","id = ".$asb->id_kode);
                })
                ->addColumn('rekening_belanja',function($asb) {
                    $details = DetailRincianUsulan::where('id_ssh','=',$asb->id)->get();
                    $show = "";
                        foreach($details as $detail){
                            $show .= getValue("kode_akun","referensi_rekening_belanja","id = ".$detail->kode_akun).'<br>';
                        }
                    return $show;
                })
                ->addColumn('satuan',function($asb){
                    return getValue("nm_satuan","data_satuan","id = ".$asb->id_satuan);
                })
                ->addColumn('harga',function($asb) {
                    return "Rp. ".number_format($asb->harga, 2, ",", ".");
                })
                ->addColumn('aksi', function($asb){
                    if($asb->status == '1'){
                        $aksi = '
                            <div class="btn-group">
                                <a href="javascript:void(0)" onclick="verifAsb(`'.route('asb.rincianValidasi',$asb->id).'`)" class="btn btn-sm btn-primary" title="Validasi"><i class="fas fa-paper-plane"></i></a>
                                <a href="javascript:void(0)" onclick="tolakAsb(`'.route('asb.rincianReject',$asb->id).'`)" class="btn btn-sm btn-danger" title="Tolak"><i class="fas fa-redo"></i></a>
                            </div>
                        ';
                    }
                    if($asb->status == '2'){
                        $aksi = '
                        <div class="btn-group">
                            <a href="javascript:void(0)" onclick="editAsb(`'.route('asb.rincianUpdate',$asb->id).'`,'.$asb->id.')" class="btn btn-sm btn-warning" title="Ubah" ><i class="fas fa-edit"></i></a>
                        </div>
                        ';
                    }
                    return $aksi;
                })
                ->rawColumns(['aksi','rekening_belanja'])
                ->make(true);
    }

    public function asetRinci($id){
        $usulan = UsulanSsh::find(decrypt($id));
        $opd = getValue("opd","data_opd"," id = ".$usulan->id_opd);
        $jenis = ($usulan->induk_perubahan == '1') ? "Induk" : "Perubahan";
        $kode_barang = KodeBarang::where('kelompok','=','3')->get();
        $rekening = RekeningBelanja::all();
        $satuan = DataSatuan::all();
        $instansi = DataOpd::all();
        return view('usulan.asb.aset.rincian',[
            'title' => 'Usulan',
            'page' => 'ASB',
            'drops' => [
                'kode_barang' => $kode_barang,
                'rekening' => $rekening,
                'instansi' => $instansi,
                'satuan' => $satuan,
            ],
            'opd' => $opd,
            'tahun' => $usulan->tahun,
            'jenis' => $jenis
        ]);
    }

    public function exportAsetInstansi($id){
        $asb = dataSsh::where('id_usulan','=',decrypt($id))->where('id_kelompok','=','3')->whereIn('status',['2'])->get();
        $usulan = UsulanSsh::find(decrypt($id));
        $jenis = ($usulan->induk_perubahan == "1") ? "induk" : "perubahan";
        $detail = DetailRincianUsulan::where('id_ssh','=',$asb[0]->id)->get();
        $ttd = TtdSetting::where('id_opd','=',$usulan->id_opd)->first();
        $opd = getValue("opd","data_opd"," id =".$usulan->id_opd);
        $data = [
            'tahun' => $usulan->tahun,
            'instansi' => "PEMERINTAH PROVINSI PAPUA BARAT DAYA",
            'title' => "USULAN ".strtoupper($jenis)." ANALISIS STANDA BELANJA TAHUN ANGGARAN",
            'asb' => $asb,
            'ttd' => $ttd,
            'opd' => $opd,
            'id_ssh' => $asb[0]->id,
            'detail' => $detail->count()
        ];
        $pdf = PDF::loadView('pdf.asb.instansi',$data);
        $pdf->setPaper('legal', 'landscape');
        return $pdf->stream('asb-'.$jenis.'-'.Auth::user()->id_opd.'-TA-'.$usulan->tahun.'-' . date('Y-m-d H:i:s') . '.pdf');
    }

    public function export(Request $request){
        $filter = [
            'tahun' => 'required',
            'jenis' => 'required'
        ];
        $pesan = [
            'tahun.required' => 'Tahun tidak boleh kosong <br />',
            'jenis.required' => 'Jenis usulan tidak boleh kosong <br />'
        ];
        $this->validate($request, $filter, $pesan);
        $data = [
            'tahun' => $request->tahun,
            'jenis' => $request->jenis
        ];
        return response()->json($data);
    }

    public function exportAset($tahun,$jenis){
        $asb = dataAsb::select('_data_ssh.*','usulan_ssh.id as usulan_id','usulan_ssh.induk_perubahan','usulan_ssh.tahun','usulan_ssh.induk_perubahan')
                        ->join('usulan_ssh','_data_ssh.id_usulan','=','usulan_ssh.id')
                        ->where('usulan_ssh.id_kelompok','=','3')
                        ->where('_data_ssh.status','=','2')
                        ->where('usulan_ssh.tahun','like','%'.$tahun.'%')
                        ->where('usulan_ssh.induk_perubahan','=',$jenis)
                        ->get();
        $jenis = ($jenis == "1") ? "induk" : "perubahan";
        $detail = DetailRincianUsulan::where('id_ssh','=',$asb[0]->id)->get();
        $data = [
            'tahun' => $tahun,
            'instansi' => "PEMERINTAH PROVINSI PAPUA BARAT DAYA",
            'title' => "USULAN ".strtoupper($jenis)." ANALISIS STANDA BELANJA TAHUN ANGGARAN",
            'asb' => $asb,
            'id_ssh' => $asb[0]->id,
            'detail' => $detail->count()
        ];
        $pdf = PDF::loadView('pdf.asb.aset',$data);
        $pdf->setPaper('legal', 'landscape');
        return $pdf->stream('asb-'.$tahun.'-'.date('Y-m-d H:i:s').'.pdf');
    }
}
