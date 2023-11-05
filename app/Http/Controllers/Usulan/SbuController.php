<?php

namespace App\Http\Controllers\Usulan;

use App\Http\Controllers\Controller;
use App\Models\DataOpd;
use App\Models\DataSatuan;
use App\Models\dataSbu;
use App\Models\DetailRincianUsulan;
use App\Models\KodeBarang;
use App\Models\RekeningBelanja;
use App\Models\TtdSetting;
use App\Models\UsulanSsh;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\Auth;

class SbuController extends Controller
{
    public function index(){
        // if(Auth::user()->level == 'aset'){
        //     $kode_barang = KodeBarang::where('kelompok','=','2')->get();
        //     $rekening = RekeningBelanja::all();
        //     $satuan = DataSatuan::all();
        //     $instansi = DataOpd::all();
        //     return view('usulan.sbu.aset',[
        //         'title' => 'Usulan',
        //         'page' => 'SBU',
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
            $tahun  = UsulanSsh::select('tahun')->where('id_kelompok','=','2')->where('status','=','1')->groupBy('tahun')->get();
            $jenis  = UsulanSsh::select('induk_perubahan')->where('id_kelompok','=','2')->where('status','=','1')->groupBy('induk_perubahan')->get();
            return view('usulan.sbu.aset.index',[
                'title' => 'Usulan',
                'page' => 'SBU',
                'drops' => [
                    'instansi' => $instansi,
                    'tahun' => $tahun,
                    'jenis' => $jenis
                ]
            ]);
        }
        return view('usulan.sbu.index',[
            'title' => 'Usulan',
            'page' => 'SBU'
        ]);
    }

    public function rincian($id){
        $usulan = UsulanSsh::find(decrypt($id));
        $kode_barang = KodeBarang::where('kelompok','=','2')->get();
        $rekening = RekeningBelanja::all();
        $satuan = DataSatuan::all();
        $instansi = DataOpd::all();
        return view('usulan.sbu.rincian',[
            'title' => 'Usulan',
            'page' => 'SBU',
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
        $sbu = UsulanSsh::select('usulan_ssh.*','_data_ssh.id as id_ssh','_data_ssh.id_kode','_data_ssh.id_usulan','_data_ssh.spesifikasi','_data_ssh.id_satuan','_data_ssh.harga','_data_ssh.status as s_status','_data_ssh.id_rekening','_data_ssh.uraian')
                        ->join('_data_ssh','usulan_ssh.id','=','_data_ssh.id_usulan')
                        ->where('usulan_ssh.id_kelompok','=',2)
                        ->whereIn('_data_ssh.status',['1','2'])->get();

        return datatables()->of($sbu)
                ->addIndexColumn()
                ->addColumn('q_opd',function($sbu) {
                    return getValue("opd","data_opd","id = ".$sbu->id_opd);
                })
                ->addColumn('usulan',function($sbu) {
                    if(is_null($sbu->induk_perubahan)){
                        $usulan = "Mohon diubah";
                    }

                    if($sbu->induk_perubahan == "1"){
                        $usulan = "Induk";
                    }

                    if($sbu->induk_perubahan == "2"){
                        $usulan = "Perubahan";
                    }
                    return $usulan;
                })
                ->addColumn('uraian',function($ssh) {
                    return getValue("uraian_id","referensi_kode_barang","id = ".$ssh->id_kode);
                })
                ->addColumn('kode_barang',function($ssh) {
                    return getValue("kode_barang","referensi_kode_barang","id = ".$ssh->id_kode);
                })
                ->addColumn('rekening_belanja',function($ssh) {
                    return getValue("kode_akun","referensi_rekening_belanja","id = ".$ssh->id_rekening);
                })
                ->addColumn('satuan',function($sbu){
                    return getValue("nm_satuan","data_satuan","id = ".$sbu->id_satuan);
                })
                ->addColumn('harga',function($sbu) {
                    return "Rp. ".number_format($sbu->harga, 2, ",", ".");
                })
                ->addColumn('dokumen',function($sbu){
                    $dok = '
                    <div class="btn-group">
                        <a href="'.asset('upload/sbu/'.$sbu->ssd_dokumen).'" target="_blank" class="btn btn-sm btn-danger btn-icon-split">
                            <span class="icon text-white-50">
                                <i class="fas fa-file-pdf"></i>
                            </span>
                            <span class="text">PDF</span>
                        </a>
                    </div>
                    ';
                    return $dok;
                })
                ->addColumn('aksi', function($sbu){
                    if(Auth::user()->level == 'aset'){
                        if($sbu->s_status == '1'){
                            $aksi = '
                            <div class="btn-group">
                                <a href="javascript:void(0)" onclick="verifSbu(`'.route('sbu.rincianValidasi',$sbu->id_ssh).'`)" class="btn btn-sm btn-primary" title="Validasi"><i class="fas fa-paper-plane"></i></a>
                                <a href="javascript:void(0)" onclick="tolakSbu(`'.route('sbu.rincianReject',$sbu->id_ssh).'`)" class="btn btn-sm btn-danger" title="Tolak"><i class="fas fa-redo"></i></a>
                            </div>
                            ';
                        }
                        if($sbu->s_status == '2'){
                            $aksi = '
                            <div class="btn-group">
                                <a href="javascript:void(0)" onclick="editSbu(`'.route('sbu.rincianUpdate',$sbu->id_ssh).'`,'.$sbu->id_ssh.')" class="btn btn-sm btn-warning" title="Ubah" ><i class="fas fa-edit"></i></a>
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
        $sbu = dataSbu::where('id_usulan','=',$id)->get();
        return datatables()->of($sbu)
                ->addIndexColumn()
                ->addColumn('uraian_id',function($sbu) {
                    return getValue("uraian","referensi_kode_barang","id = ".$sbu->id_kode);
                })
                ->addColumn('kode_barang',function($sbu) {
                    return getValue("kode_barang","referensi_kode_barang","id = ".$sbu->id_kode);
                })
                ->addColumn('rekening_belanja',function($sbu) {
                    return getValue("kode_akun","referensi_rekening_belanja","id = ".$sbu->id_rekening);
                })
                ->addColumn('satuan',function($sbu){
                    return getValue("nm_satuan","data_satuan","id = ".$sbu->id_satuan);
                })
                ->addColumn('harga',function($sbu) {
                    return "Rp. ".number_format($sbu->harga, 2, ",", ".");
                })
                ->addColumn('aksi', function($sbu){
                    if((Auth::user()->level == 'operator' || Auth::user()->level == 'bendahara')){
                        if($sbu->status == '0'){
                            $aksi = '
                            <div class="btn-group">
                                <a href="javascript:void(0)" onclick="editSbu(`'.route('sbu.rincianUpdate',$sbu->id).'`,'.$sbu->id.')" class="btn btn-sm btn-warning" title="Ubah" ><i class="fas fa-edit"></i></a>
                                <a href="javascript:void(0)" onclick="hapusSbu(`'.route('sbu.rincianDestroy',$sbu->id).'`)" class="btn btn-sm btn-danger" title="Hapus"><i class="fas fa-trash"></i></a>
                            </div>
                            ';
                        }
                        if($sbu->status == '1'){
                            $aksi = 'Proccesed';
                        }

                        if($sbu->status == '2'){
                            $aksi = 'Valid';
                        }
                    }
                    return $aksi;
                })
                ->rawColumns(['aksi'])
                ->make(true);
    }

    public function data(){
        $sbu = UsulanSsh::where('id_kelompok','=','2')->where('id_opd','=',Auth::user()->id_opd)->get();
        return datatables()->of($sbu)
                ->addIndexColumn()
                ->addColumn('q_opd',function($sbu) {
                    return getValue("opd","data_opd","id = ".$sbu->id_opd);
                })
                ->addColumn('usulan',function($sbu) {
                    if(is_null($sbu->induk_perubahan)){
                        $usulan = "Mohon diubah";
                    }

                    if($sbu->induk_perubahan == "1"){
                        $usulan = "Induk";
                    }

                    if($sbu->induk_perubahan == "2"){
                        $usulan = "Perubahan";
                    }
                    return $usulan;
                })
                ->addColumn('dokumen',function($sbu) {
                    if(is_null($sbu->ssd_dokumen)){
                        $aksi = '
                            <a href="javascript:void(0)" onclick="sbuUpload(`'.route('sbu.upload',$sbu->id).'`)" class="btn btn-sm btn-success btn-icon-split">
                                <span class="icon text-white-50">
                                    <i class="fas fa-upload"></i>
                                </span>
                                <span class="text">Upload</span>
                            </a>
                        ';
                    }else{
                        $aksi = '
                        <div class="btn-group">
                            <a href="'.asset('upload/sbu/'.$sbu->ssd_dokumen).'" target="_blank" class="btn btn-sm btn-danger btn-icon-split">
                                <span class="icon text-white-50">
                                    <i class="fas fa-file-pdf"></i>
                                </span>
                                <span class="text">PDF</span>
                            </a>
                            <a href="javascript:void(0)" onclick="sbuUpload(`'.route('sbu.upload',$sbu->id).'`)" class="btn btn-sm btn-success btn-icon-split">
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
                ->addColumn('rincian',function($sbu) {
                    return '
                    <a href="'.route('sbu.rincian',encrypt($sbu->id)).'" class="btn btn-sm btn-success btn-icon-split">
                        <span class="icon text-white-50">
                            <i class="fas fa-eye"></i>
                        </span>
                        <span class="text">Rincian</span>
                    </a>
                    ';
                })
                ->addColumn('aksi', function($sbu){
                    if((Auth::user()->level == 'operator' || Auth::user()->level == 'bendahara')){
                        if($sbu->status == '0'){
                            if(is_null($sbu->ssd_dokumen)){
                                $aksi = '
                                    <div class="btn-group">
                                        <a href="javascript:void(0)" onclick="editSbu(`'.route('sbu.update',$sbu->id).'`,'.$sbu->id.')" class="btn btn-sm btn-warning" title="Ubah" ><i class="fas fa-edit"></i></a>
                                        <a href="javascript:void(0)" onclick="hapusSbu(`'.route('sbu.destroy',$sbu->id).'`)" class="btn btn-sm btn-danger" title="Hapus"><i class="fas fa-trash"></i></a>
                                    </div>
                                    ';
                            }else{
                                $aksi = '
                                <div class="btn-group">
                                    <a href="javascript:void(0)" onclick="editSbu(`'.route('sbu.update',$sbu->id).'`,'.$sbu->id.')" class="btn btn-sm btn-warning" title="Ubah" ><i class="fas fa-edit"></i></a>
                                    <a href="javascript:void(0)" onclick="hapusSbu(`'.route('sbu.destroy',$sbu->id).'`)" class="btn btn-sm btn-danger" title="Hapus"><i class="fas fa-trash"></i></a>
                                    <a href="javascript:void(0)" onclick="verifSbu(`'.route('sbu.validasi',$sbu->id).'`)" class="btn btn-sm btn-primary" title="Validasi"><i class="fas fa-paper-plane"></i></a>
                                </div>
                                ';
                            }
                        }
                        if($sbu->status == '1'){
                            $aksi = 'Terkirim';
                        }

                        if($sbu->status == '2'){
                            $aksi = 'Valid';
                        }

                        if($sbu->status == '3'){
                            // $aksi = 'Ditolak, mohon cek rincian';
                            $aksi = '
                                <div class="btn-group">
                                    <a href="javascript:void(0)" onclick="editSbu(`'.route('sbu.update',$sbu->id).'`,'.$sbu->id.')" class="btn btn-sm btn-warning" title="Ubah" ><i class="fas fa-edit"></i></a>
                                    <a href="javascript:void(0)" onclick="hapusSbu(`'.route('sbu.destroy',$sbu->id).'`)" class="btn btn-sm btn-danger" title="Hapus"><i class="fas fa-trash"></i></a>
                                    <a href="javascript:void(0)" onclick="verifSbu(`'.route('sbu.validasi',$sbu->id).'`)" class="btn btn-sm btn-primary" title="Validasi"><i class="fas fa-paper-plane"></i></a>
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
            'tahun.required' => 'Tahun SBU tidak boleh kosong <br />',
            'induk_perubahan.required' => 'Jenis usulan tidak boleh kosong <br />',
        ];
        $this->validate($request, $field, $pesan);
        $data = [
            'id_opd' => Auth::user()->id_opd,
            'tahun' => $request->tahun,
            'induk_perubahan' => $request->induk_perubahan,
            'id_kelompok' => 2,
            'status' => '0'
        ];

        UsulanSsh::create($data);
        return response()->json('SBU berhasil dibuat',200);
    }

    public function rincianStore(Request $request,$id){
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
            // 'id_rekening' => $request->id_rekening,
            'id_usulan' => $id,
            'spesifikasi' => $request->spesifikasi,
            'uraian' => $request->uraian,
            'harga' => $request->harga,
            'tkdn' => $request->harga,
            'id_satuan' => $request->id_satuan,
            'status' => '0'
        ];

        dataSbu::create($data);
        return response()->json('item SBU berhasil ditambahkan',200);
    }

    public function show($id){
        $sbu = UsulanSsh::find($id);
        return response()->json($sbu);
    }

    public function rincianShow($id){
        $sbu = dataSbu::find($id);
        return response()->json($sbu);
    }

    public function update(Request $request,$id){
        $sbu = UsulanSsh::find($id);
        $field = [
            'tahun' => ['required'],
            'induk_perubahan' => ['required']
        ];

        $pesan = [
            'tahun.required' => 'Tahun SBU tidak boleh kosong <br />',
            'induk_perubahan.required' => 'Jenis usulan tidak boleh kosong <br />',
        ];
        $this->validate($request, $field, $pesan);
        $data = [
            'id_opd' => Auth::user()->id_opd,
            'tahun' => $request->tahun,
            'induk_perubahan' => $request->induk_perubahan,
            'id_kelompok' => 2
        ];
        $sbu->update($data);
        return response()->json('SBU berhasil diubah',200);
    }

    public function rincianUpdate(Request $request,$id){
        $sbu = dataSbu::find($id);
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
            'uraian.required' => 'Uraian tidak boleh kosong <br />',
            'id_satuan.required' => 'Satuan tidak boleh kosong <br />',
            'harga.required' => 'Harga tidak boleh kosong <br />',
            'tkdn.required' => 'T K D N tidak boleh kosong <br />',
        ];
        $this->validate($request, $field, $pesan);
        $data = [
            'id_kode' => $request->id_kode,
            // 'id_rekening' => $request->id_rekening,
            'spesifikasi' => $request->spesifikasi,
            'uraian' => $request->uraian,
            'harga' => $request->harga,
            'tkdn' => $request->tkdn,
            'id_satuan' => $request->id_satuan
        ];

        $sbu->update($data);
        return response()->json('item SBU berhasil diubah',200);
    }

    public function destroy($id){
        $sbu = UsulanSsh::find($id);
        $sbu->delete();
        return response()->json('SBU berhasil dihapus', 204);
    }

    public function rincianDestroy($id){
        $sbu = dataSbu::find($id);
        $sbu->delete();
        return response()->json('item SBU berhasil dihapus', 204);
    }

    public function upload(Request $request,$id){
        $sbu = UsulanSsh::find($id);
        $filter = [
            'ssd_dokumen' => 'required|mimes:pdf',
        ];
        $pesan = [
            'ssd_dokumen.required' => 'Dokumen SSH tidak boleh kosong <br />',
            'ssd_dokumen.mimes' => 'Dokumen SSH harus berformat PDF <br />'
        ];
        $this->validate($request, $filter, $pesan);
        $dok = $request->file('ssd_dokumen');
        $nm = 'sbu-'.date('Y').'-'.date('Ymdhis').'.'.$dok->getClientOriginalExtension();
        if(!is_null($sbu->ssd_dokumen)){
            unlink(public_path()."/upload/sbu/".$sbu->ssd_dokumen);
        }
        $dok->move(public_path('upload/sbu'),$nm);
        $data = [
            'ssd_dokumen' => $nm
        ];
        $sbu->update($data);
        return response()->json('dokumen SBU berhasil diupload',200);
    }

    public function validasi($id){
        $sbu = UsulanSsh::find($id);
        if(Auth::user()->level == 'operator' || Auth::user()->level == 'bendahara'){
            $verif = ['status' => '1'];
            $respon = 'usulan SBU berhasil dikirim ke admin Aset';
            $item_status = ['status' => '1'];
            dataSbu::where('id_usulan','=',$sbu->id)->update($item_status);
        }elseif(Auth::user()->level == 'aset'){
            $verif = ['status' => '2'];
            $respon = 'usulan SBU telah diterima';
        }
        $sbu->update($verif);
        return response()->json($respon,200);
    }

    public function rincianValidasi($id){
        $sbu = dataSbu::find($id);
        $verif = ['status' => '2'];
        $respon = 'usulan SBU telah diterima';
        $sbu->update($verif);
        return response()->json($respon,200);
    }

    public function tolak($id){
        $sbu = UsulanSsh::find($id);
        $data = [
            'status' => '0'
        ];
        $sbu->update($data);
        return response()->json('usulan SBU berhasil dikembalikan',200);
    }

    public function rincianTolak(Request $request,$id){
        $id_usulan = getValue("id_usulan","_data_ssh"," id = ".$id);
        $sbu = dataSbu::find($id);
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
        $sbu->update($data);

        dataSbu::where('id_usulan','=',$id_usulan)->update(['status' => '0']);

        $usulan = [
            'status' => '3'
        ];

        UsulanSsh::where('id','=',$sbu->id_usulan)->update($usulan);
        return response()->json('usulan SBU berhasil dikembalikan',200);
    }

    public function exportPDF($id){
        $sbu = dataSbu::where('id_usulan','=',decrypt($id))->get();
        $usulan = UsulanSsh::find(decrypt($id));
        $jenis = ($usulan->induk_perubahan == "1") ? "induk" : "perubahan";

        $ttd = TtdSetting::where('id_opd','=',Auth::user()->id_opd)->first();
        $opd = getValue("opd","data_opd"," id =".Auth::user()->id_opd);
        $data = [
            'tahun' => $usulan->tahun,
            'instansi' => "PEMERINTAH PROVINSI PAPUA BARAT DAYA",
            'title' => "USULAN ".strtoupper($jenis)." STANDAR BIAYA UMUM TAHUN ANGGARAN",
            'sbu' => $sbu,
            'ttd' => $ttd,
            'opd' => $opd
        ];
        $pdf = PDF::loadView('pdf.sbu',$data);
        $pdf->setPaper('F4', 'landscape');
        return $pdf->stream('sbu-'.$jenis.'-'.Auth::user()->id_opd.'-TA-'.$usulan->tahun.'-' . date('Y-m-d H:i:s') . '.pdf');
    }

    public function instansi($id){
        return view('usulan.sbu.aset.instansi',[
            'title' => 'Usulan',
            'page' => 'SBU',
            'opd' => strtoupper(getValue("opd","data_opd"," id = ".decrypt($id)))
        ]);
    }

    public function asetInstansi($id){
        $sbu = UsulanSsh::where('id_opd','=',decrypt($id))->whereIn('status',['1','2'])->get();
        return datatables()->of($sbu)
                ->addIndexColumn()
                ->addColumn('usulan',function($sbu) {
                    if(is_null($sbu->induk_perubahan)){
                        $usulan = "Mohon diubah";
                    }

                    if($sbu->induk_perubahan == "1"){
                        $usulan = "Induk";
                    }

                    if($sbu->induk_perubahan == "2"){
                        $usulan = "Perubahan";
                    }
                    return $usulan;
                })
                ->addColumn('dokumen',function($sbu){
                    $dok = '
                    <div class="btn-group">
                        <a href="'.asset('upload/usulan/'.$sbu->ssd_dokumen).'" target="_blank" class="btn btn-sm btn-danger btn-icon-split">
                            <span class="icon text-white-50">
                                <i class="fas fa-file-pdf"></i>
                            </span>
                            <span class="text">PDF</span>
                        </a>
                    </div>
                    ';
                    return $dok;
                })
                ->addColumn('rincian',function($sbu) {
                    return '
                    <a href="'.route('sbu.asetRinci',encrypt($sbu->id)).'" class="btn btn-sm btn-success btn-icon-split">
                        <span class="icon text-white-50">
                            <i class="fas fa-eye"></i>
                        </span>
                        <span class="text">Rincian</span>
                    </a>
                    ';
                })
                ->addColumn('export', function($sbu){
                    $aksi = '
                    <a href="javascript:void(0)" onclick="window.open(`'.route('sbu.exportAsetInstansi',encrypt($sbu->id)).'`,`Title`,`directories=no,titlebar=no,toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=1024,height = 720`)" class="btn btn-sm btn-danger btn-icon-split mt-2 float-right">
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
        $sbu = dataSbu::where('id_usulan','=',decrypt($id))->where('id_kelompok','=','2')->whereIn('status',['1','2'])->get();
        return datatables()->of($sbu)
                ->addIndexColumn()
                ->addColumn('uraian_id',function($sbu) {
                    return getValue("uraian","referensi_kode_barang","id = ".$sbu->id_kode);
                })
                ->addColumn('kode_barang',function($sbu) {
                    return getValue("kode_barang","referensi_kode_barang","id = ".$sbu->id_kode);
                })
                ->addColumn('rekening_belanja',function($sbu) {
                    $details = DetailRincianUsulan::where('id_ssh','=',$sbu->id)->get();
                    $show = "";
                        foreach($details as $detail){
                            $show .= getValue("kode_akun","referensi_rekening_belanja","id = ".$detail->kode_akun).'<br>';
                        }
                    return $show;
                })
                ->addColumn('satuan',function($sbu){
                    return getValue("nm_satuan","data_satuan","id = ".$sbu->id_satuan);
                })
                ->addColumn('harga',function($sbu) {
                    return "Rp. ".number_format($sbu->harga, 2, ",", ".");
                })
                ->addColumn('aksi', function($sbu){
                    if($sbu->status == '1'){
                        $aksi = '
                            <div class="btn-group">
                                <a href="javascript:void(0)" onclick="verifSbu(`'.route('sbu.rincianValidasi',$sbu->id).'`)" class="btn btn-sm btn-primary" title="Validasi"><i class="fas fa-paper-plane"></i></a>
                                <a href="javascript:void(0)" onclick="tolakSbu(`'.route('sbu.rincianReject',$sbu->id).'`)" class="btn btn-sm btn-danger" title="Tolak"><i class="fas fa-redo"></i></a>
                            </div>
                        ';
                    }
                    if($sbu->status == '2'){
                        $aksi = '
                        <div class="btn-group">
                            <a href="javascript:void(0)" onclick="editSbu(`'.route('sbu.rincianUpdate',$sbu->id).'`,'.$sbu->id.')" class="btn btn-sm btn-warning" title="Ubah" ><i class="fas fa-edit"></i></a>
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
        $kode_barang = KodeBarang::where('kelompok','=','2')->get();
        $rekening = RekeningBelanja::all();
        $satuan = DataSatuan::all();
        $instansi = DataOpd::all();
        return view('usulan.sbu.aset.rincian',[
            'title' => 'Usulan',
            'page' => 'SBU',
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
        $sbu = dataSbu::where('id_usulan','=',decrypt($id))->where('id_kelompok','=','2')->whereIn('status',['2'])->get();
        $usulan = UsulanSsh::find(decrypt($id));
        $jenis = ($usulan->induk_perubahan == "1") ? "induk" : "perubahan";
        $ttd = TtdSetting::where('id_opd','=',$usulan->id_opd)->first();
        $opd = getValue("opd","data_opd"," id =".$usulan->id_opd);
        $data = [
            'tahun' => $usulan->tahun,
            'instansi' => "PEMERINTAH PROVINSI PAPUA BARAT DAYA",
            'title' => "USULAN ".strtoupper($jenis)." STANDAR BIAYA UMUM TAHUN ANGGARAN",
            'sbu' => $sbu,
            'ttd' => $ttd,
            'opd' => $opd
        ];
        $pdf = PDF::loadView('pdf.sbu.instansi',$data);
        $pdf->setPaper('F4', 'landscape');
        return $pdf->stream('sbu-'.$jenis.'-'.Auth::user()->id_opd.'-TA-'.$usulan->tahun.'-' . date('Y-m-d H:i:s') . '.pdf');
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
        $sbu = dataSbu::select('_data_ssh.*','usulan_ssh.id as usulan_id','usulan_ssh.induk_perubahan','usulan_ssh.tahun','usulan_ssh.induk_perubahan')
                        ->join('usulan_ssh','_data_ssh.id_usulan','=','usulan_ssh.id')
                        ->where('_data_ssh.id_kelompok','=','2')
                        ->where('_data_ssh.status','=','2')
                        ->where('usulan_ssh.tahun','like','%'.$tahun.'%')
                        ->where('usulan_ssh.induk_perubahan','=',$jenis)
                        ->get();
        $jenis = ($jenis == "1") ? "induk" : "perubahan";
        $data = [
            'tahun' => $tahun,
            'instansi' => "PEMERINTAH PROVINSI PAPUA BARAT DAYA",
            'title' => "USULAN ".strtoupper($jenis)." STANDAR BIAYA UMUM TAHUN ANGGARAN",
            'sbu' => $sbu,
        ];
        $pdf = PDF::loadView('pdf.sbu.aset',$data);
        $pdf->setPaper('F4', 'landscape');
        return $pdf->stream('sbu-'.$tahun.'-'.date('Y-m-d H:i:s').'.pdf');
    }
}
