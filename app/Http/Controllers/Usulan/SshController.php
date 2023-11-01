<?php

namespace App\Http\Controllers\Usulan;

use App\Http\Controllers\Controller;
use App\Models\DataOpd;
use App\Models\DataSatuan;
use App\Models\dataSsh;
use App\Models\KodeBarang;
use App\Models\RekeningBelanja;
use App\Models\TtdSetting;
use App\Models\UsulanSsh;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class SshController extends Controller
{
    public function index(){
        if(Auth::user()->level == 'aset'){
            $kode_barang = KodeBarang::where('kelompok','=','1')->get();
            $rekening = RekeningBelanja::all();
            $satuan = DataSatuan::all();
            $instansi = DataOpd::all();
            return view('usulan.ssh.aset',[
                'title' => 'Usulan',
                'page' => 'SSH',
                'drops' => [
                    'kode_barang' => $kode_barang,
                    'rekening' => $rekening,
                    'instansi' => $instansi,
                    'satuan' => $satuan
                ]
            ]);
        }
        return view('usulan.ssh.index',[
            'title' => 'Usulan',
            'page' => 'SSH'
        ]);
    }

    public function rincian($id){
        $usulan = UsulanSsh::find(decrypt($id));
        $kode_barang = KodeBarang::where('kelompok','=','1')->get();
        $rekening = RekeningBelanja::all();
        $satuan = DataSatuan::all();
        $instansi = DataOpd::all();
        return view('usulan.ssh.rincian',[
            'title' => 'Usulan',
            'page' => 'SSH',
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
        $ssh = UsulanSsh::select('usulan_ssh.*','_data_ssh.id as id_ssh','_data_ssh.id_kode','_data_ssh.id_usulan','_data_ssh.spesifikasi','_data_ssh.id_satuan','_data_ssh.harga','_data_ssh.status as s_status','_data_ssh.id_rekening','_data_ssh.uraian')
                        ->join('_data_ssh','usulan_ssh.id','=','_data_ssh.id_usulan')
                        ->where('usulan_ssh.id_kelompok','=',1)
                        ->whereIn('_data_ssh.status',['1','2'])->get();

        return datatables()->of($ssh)
                ->addIndexColumn()
                ->addColumn('q_opd',function($ssh) {
                    return getValue("opd","data_opd","id = ".$ssh->id_opd);
                })
                ->addColumn('usulan',function($ssh) {
                    if(is_null($ssh->induk_perubahan)){
                        $usulan = "Mohon diubah";
                    }

                    if($ssh->induk_perubahan == "1"){
                        $usulan = "Induk";
                    }

                    if($ssh->induk_perubahan == "2"){
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
                ->addColumn('satuan',function($ssh){
                    return getValue("nm_satuan","data_satuan","id = ".$ssh->id_satuan);
                })
                ->addColumn('harga',function($ssh) {
                    return "Rp. ".number_format($ssh->harga, 2, ",", ".");
                })
                ->addColumn('dokumen',function($ssh){
                    $dok = '
                    <div class="btn-group">
                        <a href="'.asset('upload/ssh/'.$ssh->ssd_dokumen).'" target="_blank" class="btn btn-sm btn-danger btn-icon-split">
                            <span class="icon text-white-50">
                                <i class="fas fa-file-pdf"></i>
                            </span>
                            <span class="text">PDF</span>
                        </a>
                    </div>
                    ';
                    return $dok;
                })
                ->addColumn('aksi', function($ssh){
                    if(Auth::user()->level == 'aset'){
                        if($ssh->s_status == '1'){
                            $aksi = '
                                <div class="btn-group">
                                    <a href="javascript:void(0)" onclick="verifSsh(`'.route('ssh.rincianValidasi',$ssh->id_ssh).'`)" class="btn btn-sm btn-primary" title="Validasi"><i class="fas fa-paper-plane"></i></a>
                                    <a href="javascript:void(0)" onclick="tolakSsh(`'.route('ssh.rincianReject',$ssh->id_ssh).'`)" class="btn btn-sm btn-danger" title="Tolak"><i class="fas fa-redo"></i></a>
                                </div>
                            ';
                        }
                        if($ssh->s_status == '2'){
                            $aksi = '
                            <div class="btn-group">
                                <a href="javascript:void(0)" onclick="editSsh(`'.route('ssh.rincianUpdate',$ssh->id_ssh).'`,'.$ssh->id_ssh.')" class="btn btn-sm btn-warning" title="Ubah" ><i class="fas fa-edit"></i></a>
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
        $ssh = dataSsh::where('id_usulan','=',$id)->get();
        return datatables()->of($ssh)
                ->addIndexColumn()
                ->addColumn('uraian_id',function($ssh) {
                    return getValue("uraian","referensi_kode_barang","id = ".$ssh->id_kode);
                })
                ->addColumn('kode_barang',function($ssh) {
                    return getValue("kode_barang","referensi_kode_barang","id = ".$ssh->id_kode);
                })
                ->addColumn('rekening_belanja',function($ssh) {
                    return getValue("kode_akun","referensi_rekening_belanja","id = ".$ssh->id_rekening);
                })
                ->addColumn('satuan',function($ssh){
                    return getValue("nm_satuan","data_satuan","id = ".$ssh->id_satuan);
                })
                ->addColumn('harga',function($ssh) {
                    return "Rp. ".number_format($ssh->harga, 2, ",", ".");
                })
                ->addColumn('aksi', function($ssh){
                    if((Auth::user()->level == 'operator' || Auth::user()->level == 'bendahara')){
                        if($ssh->status == '0'){
                            $aksi = '
                            <div class="btn-group">
                                <a href="javascript:void(0)" onclick="editSsh(`'.route('ssh.rincianUpdate',$ssh->id).'`,'.$ssh->id.')" class="btn btn-sm btn-warning" title="Ubah" ><i class="fas fa-edit"></i></a>
                                <a href="javascript:void(0)" onclick="hapusSsh(`'.route('ssh.rincianDestroy',$ssh->id).'`)" class="btn btn-sm btn-danger" title="Hapus"><i class="fas fa-trash"></i></a>
                            </div>
                            ';
                        }
                        if($ssh->status == '1'){
                            $aksi = 'Proccesed';
                        }

                        if($ssh->status == '2'){
                            $aksi = 'Valid';
                        }
                    }
                    return $aksi;
                })
                ->rawColumns(['aksi'])
                ->make(true);
    }


    public function data(){
        $ssh = UsulanSsh::where('id_kelompok','=','1')->where('id_opd','=',Auth::user()->id_opd)->get();
        return datatables()->of($ssh)
                ->addIndexColumn()
                ->addColumn('q_opd',function($ssh) {
                    return getValue("opd","data_opd","id = ".$ssh->id_opd);
                })
                ->addColumn('usulan',function($ssh) {
                    if(is_null($ssh->induk_perubahan)){
                        $usulan = "Mohon diubah";
                    }

                    if($ssh->induk_perubahan == "1"){
                        $usulan = "Induk";
                    }

                    if($ssh->induk_perubahan == "2"){
                        $usulan = "Perubahan";
                    }
                    return $usulan;
                })
                ->addColumn('dokumen',function($ssh) {
                    if(is_null($ssh->ssd_dokumen)){
                        $aksi = '
                            <a href="javascript:void(0)" onclick="sshUpload(`'.route('ssh.upload',$ssh->id).'`)" class="btn btn-sm btn-success btn-icon-split">
                                <span class="icon text-white-50">
                                    <i class="fas fa-upload"></i>
                                </span>
                                <span class="text">Upload</span>
                            </a>
                        ';
                    }else{
                        $aksi = '
                        <div class="btn-group">
                            <a href="'.asset('upload/ssh/'.$ssh->ssd_dokumen).'" target="_blank" class="btn btn-sm btn-danger btn-icon-split">
                                <span class="icon text-white-50">
                                    <i class="fas fa-file-pdf"></i>
                                </span>
                                <span class="text">PDF</span>
                            </a>
                            <a href="javascript:void(0)" onclick="sshUpload(`'.route('ssh.upload',$ssh->id).'`)" class="btn btn-sm btn-success btn-icon-split">
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
                ->addColumn('rincian',function($ssh) {
                    return '
                    <a href="'.route('ssh.rincian',encrypt($ssh->id)).'" class="btn btn-sm btn-success btn-icon-split">
                        <span class="icon text-white-50">
                            <i class="fas fa-eye"></i>
                        </span>
                        <span class="text">Rincian</span>
                    </a>
                    ';
                })
                ->addColumn('aksi', function($ssh){
                    if((Auth::user()->level == 'operator' || Auth::user()->level == 'bendahara')){
                        if($ssh->status == '0'){
                            if(is_null($ssh->ssd_dokumen)){
                                $aksi = '
                                    <div class="btn-group">
                                        <a href="javascript:void(0)" onclick="editSsh(`'.route('ssh.update',$ssh->id).'`,'.$ssh->id.')" class="btn btn-sm btn-warning" title="Ubah" ><i class="fas fa-edit"></i></a>
                                        <a href="javascript:void(0)" onclick="hapusSsh(`'.route('ssh.destroy',$ssh->id).'`)" class="btn btn-sm btn-danger" title="Hapus"><i class="fas fa-trash"></i></a>
                                    </div>
                                    ';
                            }else{
                                $aksi = '
                                <div class="btn-group">
                                    <a href="javascript:void(0)" onclick="editSsh(`'.route('ssh.update',$ssh->id).'`,'.$ssh->id.')" class="btn btn-sm btn-warning" title="Ubah" ><i class="fas fa-edit"></i></a>
                                    <a href="javascript:void(0)" onclick="hapusSsh(`'.route('ssh.destroy',$ssh->id).'`)" class="btn btn-sm btn-danger" title="Hapus"><i class="fas fa-trash"></i></a>
                                    <a href="javascript:void(0)" onclick="verifSsh(`'.route('ssh.validasi',$ssh->id).'`)" class="btn btn-sm btn-primary" title="Validasi"><i class="fas fa-paper-plane"></i></a>
                                </div>
                                ';
                            }
                        }
                        if($ssh->status == '1'){
                            $aksi = 'Terkirim';
                        }

                        if($ssh->status == '2'){
                            $aksi = 'Valid';
                        }

                        if($ssh->status == '3'){
                            // $aksi = 'Ditolak, mohon cek rincian';
                            $aksi = '
                                <div class="btn-group">
                                    <a href="javascript:void(0)" onclick="editSsh(`'.route('ssh.update',$ssh->id).'`,'.$ssh->id.')" class="btn btn-sm btn-warning" title="Ubah" ><i class="fas fa-edit"></i></a>
                                    <a href="javascript:void(0)" onclick="hapusSsh(`'.route('ssh.destroy',$ssh->id).'`)" class="btn btn-sm btn-danger" title="Hapus"><i class="fas fa-trash"></i></a>
                                    <a href="javascript:void(0)" onclick="verifSsh(`'.route('ssh.validasi',$ssh->id).'`)" class="btn btn-sm btn-primary" title="Validasi"><i class="fas fa-paper-plane"></i></a>
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
            'tahun.required' => 'Tahun SSH tidak boleh kosong <br />',
            'induk_perubahan.required' => 'Jenis usulan tidak boleh kosong <br />',
        ];
        $this->validate($request, $field, $pesan);
        $data = [
            'id_opd' => Auth::user()->id_opd,
            'tahun' => $request->tahun,
            'induk_perubahan' => $request->induk_perubahan,
            'id_kelompok' => 1,
            'status' => '0'
        ];

        UsulanSsh::create($data);
        return response()->json('SSH berhasil dibuat',200);
    }

    public function rincianStore(Request $request,$id){
        $field = [
            'id_kode' => ['required'],
            'id_rekening' => ['required'],
            'uraian' => ['required'],
            'spesifikasi' => ['required'],
            'id_satuan' => ['required'],
            'harga' => ['required']
        ];

        $pesan = [
            'id_kode.required' => 'Barang tidak boleh kosong <br />',
            'id_rekening.required' => 'Rekening belanja tidak boleh kosong <br />',
            'uraian.required' => 'Uraian tidak boleh kosong <br />',
            'spesifikasi.required' => 'Spesifikasi tidak boleh kosong <br />',
            'id_satuan.required' => 'Satuan tidak boleh kosong <br />',
            'harga.required' => 'Harga tidak boleh kosong <br />',
        ];
        $this->validate($request, $field, $pesan);
        $data = [
            'id_kode' => $request->id_kode,
            'id_rekening' => $request->id_rekening,
            'id_usulan' => $id,
            'spesifikasi' => $request->spesifikasi,
            'uraian' => $request->uraian,
            'harga' => $request->harga,
            'id_satuan' => $request->id_satuan,
            'status' => '0'
        ];

        dataSsh::create($data);
        return response()->json('item ssh berhasil ditambahkan',200);
    }

    public function show($id){
        $ssh = UsulanSsh::find($id);
        return response()->json($ssh);
    }

    public function rincianShow($id){
        $ssh = dataSsh::find($id);
        return response()->json($ssh);
    }

    public function update(Request $request,$id){
        $ssh = UsulanSsh::find($id);
        $field = [
            'tahun' => ['required'],
            'induk_perubahan' => ['required']
        ];

        $pesan = [
            'tahun.required' => 'Tahun SSH tidak boleh kosong <br />',
            'induk_perubahan.required' => 'Jenis usulan tidak boleh kosong <br />',
        ];
        $this->validate($request, $field, $pesan);
        $data = [
            'id_opd' => Auth::user()->id_opd,
            'tahun' => $request->tahun,
            'induk_perubahan' => $request->induk_perubahan,
            'id_kelompok' => 1
        ];
        $ssh->update($data);
        return response()->json('SSH berhasil diubah',200);
    }

    public function rincianUpdate(Request $request,$id){
        $ssh = dataSsh::find($id);
        $field = [
            'id_kode' => ['required'],
            'id_rekening' => ['required'],
            'spesifikasi' => ['required'],
            'uraian' => ['required'],
            'id_satuan' => ['required'],
            'harga' => ['required']
        ];

        $pesan = [
            'id_kode.required' => 'Barang tidak boleh kosong <br />',
            'id_rekening.required' => 'Rekening belanja tidak boleh kosong <br />',
            'spesifikasi.required' => 'Spesifikasi tidak boleh kosong <br />',
            'id_satuan.required' => 'Satuan tidak boleh kosong <br />',
            'uraian.required' => 'Uraian tidak boleh kosong <br />',
            'harga.required' => 'Harga tidak boleh kosong <br />',
        ];
        $this->validate($request, $field, $pesan);
        $data = [
            'id_kode' => $request->id_kode,
            'id_rekening' => $request->id_rekening,
            'spesifikasi' => $request->spesifikasi,
            'uraian' => $request->uraian,
            'harga' => $request->harga,
            'id_satuan' => $request->id_satuan
        ];

        $ssh->update($data);
        return response()->json('item ssh berhasil diubah',200);
    }

    public function destroy($id){
        $ssh = UsulanSsh::find($id);
        $ssh->delete();
        return response()->json('SSH berhasil dihapus', 204);
    }

    public function rincianDestroy($id){
        $ssh = dataSsh::find($id);
        $ssh->delete();
        return response()->json('item ssh berhasil dihapus', 204);
    }

    public function upload(Request $request,$id){
        $ssh = UsulanSsh::find($id);
        $filter = [
            'ssd_dokumen' => 'required|mimes:pdf',
        ];
        $pesan = [
            'ssd_dokumen.required' => 'Dokumen SSH tidak boleh kosong <br />',
            'ssd_dokumen.mimes' => 'Dokumen SSH harus berformat PDF <br />'
        ];
        $this->validate($request, $filter, $pesan);
        $dok = $request->file('ssd_dokumen');
        $nm = 'ssh-'.date('Y').'-'.date('Ymdhis').'.'.$dok->getClientOriginalExtension();
        if(!is_null($ssh->ssd_dokumen)){
            unlink(public_path()."/upload/ssh/".$ssh->ssd_dokumen);
        }
        $dok->move(public_path('upload/ssh'),$nm);
        $data = [
            'ssd_dokumen' => $nm
        ];
        $ssh->update($data);
        return response()->json('dokumen ssh berhasil diupload',200);
    }

    public function validasi($id){
        $ssh = UsulanSsh::find($id);
        if(Auth::user()->level == 'operator' || Auth::user()->level == 'bendahara'){
            $verif = ['status' => '1'];
            $respon = 'usulan SSH berhasil dikirim ke admin Aset';
            $item_status = ['status' => '1'];
            dataSsh::where('id_usulan','=',$ssh->id)->update($item_status);
        }elseif(Auth::user()->level == 'aset'){
            $verif = ['status' => '2'];
            $respon = 'usulan SSH telah diterima';
        }
        $ssh->update($verif);
        return response()->json($respon,200);
    }

    public function rincianValidasi($id){
        $ssh = dataSsh::find($id);
        $verif = ['status' => '2'];
        $respon = 'usulan SSH telah diterima';
        $ssh->update($verif);
        return response()->json($respon,200);
    }

    public function tolak($id){
        $ssh = UsulanSsh::find($id);
        $data = [
            'status' => '0'
        ];
        $ssh->update($data);
        return response()->json('usulan SSH berhasil dikembalikan',200);
    }

    public function rincianTolak(Request $request,$id){
        $ssh = dataSsh::find($id);
        $filter = [
            'keterangan' => 'required'
        ];
        $pesan = [
            'keterangan.required' => 'Keterangan tolak tidak boleh kosong <br />'
        ];
        $this->validate($request, $filter, $pesan);
        $data = [
            'status' => '0',
            'keterangan' => $request->keterangan
        ];
        $ssh->update($data);
        $usulan = [
            'status' => '3'
        ];
        UsulanSsh::where('id','=',$ssh->id_usulan)->update($usulan);
        return response()->json('usulan SSH berhasil dikembalikan',200);
    }

    public function exportPDF($id){
        $ssh = dataSsh::where('id_usulan','=',decrypt($id))->get();
        $usulan = UsulanSsh::find(decrypt($id));
        $jenis = ($usulan->induk_perubahan == "1") ? "induk" : "perubahan";
        $ttd = TtdSetting::where('id_opd','=',Auth::user()->id_opd)->first();
        $opd = getValue("opd","data_opd"," id =".Auth::user()->id_opd);
        $data = [
            'tahun' => $usulan->tahun,
            'instansi' => "PEMERINTAH PROVINSI PAPUA BARAT DAYA",
            'title' => "USULAN ".strtoupper($jenis)." STANDAR SATUAN HARGA TAHUN ANGGARAN",
            'ssh' => $ssh,
            'ttd' => $ttd,
            'opd' => $opd
        ];
        $pdf = PDF::loadView('pdf.ssh',$data);
        $pdf->setPaper('F4', 'landscape');
        return $pdf->stream('ssh-'.$jenis.'-'.Auth::user()->id_opd.'-TA-'.$usulan->tahun.'-' . date('Y-m-d H:i:s') . '.pdf');
    }

}
