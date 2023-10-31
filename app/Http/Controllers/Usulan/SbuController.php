<?php

namespace App\Http\Controllers\Usulan;

use App\Http\Controllers\Controller;
use App\Models\DataOpd;
use App\Models\DataSatuan;
use App\Models\dataSbu;
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
        if(Auth::user()->level == 'aset'){
            $instansi = DataOpd::all();
            return view('usulan.sbu.aset',[
                'title' => 'Usulan',
                'page' => 'SBU',
                'drops' => [
                    'instansi' => $instansi
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
        return view('usulan.asb.rincian',[
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
        $sbu = UsulanSsh::select('usulan_ssh.*','_data_ssh.id as id_ssh','_data_ssh.id_kode','_data_ssh.id_usulan','_data_ssh.spesifikasi','_data_ssh.id_satuan','_data_ssh.harga','_data_ssh.status as s_status')
                        ->join('_data_ssh','usulan_ssh.id','=','_data_ssh.id_usulan')
                        ->where('usulan_ssh.id_kelompok','=',2)
                        ->whereIn('usulan_ssh.status',['1','2'])->get();

        return datatables()->of($sbu)
                ->addIndexColumn()
                ->addColumn('q_opd',function($sbu) {
                    return getValue("opd","data_opd","id = ".$sbu->id_opd);
                })
                ->addColumn('uraian',function($sbu) {
                    return getValue("uraian","referensi_kode_barang","id = ".$sbu->id_kode);
                })
                ->addColumn('satuan',function($sbu){
                    return getValue("nm_satuan","data_satuan","id = ".$sbu->id_satuan);
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
                            $aksi = 'Valid';
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
                ->addColumn('uraian',function($sbu) {
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
                ->addColumn('aksi', function($sbu){
                    if((Auth::user()->level == 'operator' || Auth::user()->level == 'bendahara')){
                        if($sbu->status == '0'){
                            $aksi = '
                            <div class="btn-group">
                                <a href="javascript:void(0)" onclick="editAsb(`'.route('asb.rincianUpdate',$sbu->id).'`,'.$sbu->id.')" class="btn btn-sm btn-warning" title="Ubah" ><i class="fas fa-edit"></i></a>
                                <a href="javascript:void(0)" onclick="hapusAsb(`'.route('asb.rincianDestroy',$sbu->id).'`)" class="btn btn-sm btn-danger" title="Hapus"><i class="fas fa-trash"></i></a>
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
        $sbu = UsulanSsh::where('id_kelompok','=','3')->where('id_opd','=',Auth::user()->id_opd)->get();
        return datatables()->of($sbu)
                ->addIndexColumn()
                ->addColumn('q_opd',function($sbu) {
                    return getValue("opd","data_opd","id = ".$sbu->id_opd);
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
                    }
                    return $aksi;
                })
                ->rawColumns(['aksi','rincian','dokumen'])
                ->make(true);
    }

    public function store(Request $request){
        $field = [
            'tahun' => ['required'],
        ];

        $pesan = [
            'tahun.required' => 'Tahun SBU tidak boleh kosong <br />',
        ];
        $this->validate($request, $field, $pesan);
        $data = [
            'id_opd' => Auth::user()->id_opd,
            'tahun' => $request->tahun,
            'id_kelompok' => 2,
            'status' => '0'
        ];

        UsulanSsh::create($data);
        return response()->json('ASB berhasil dibuat',200);
    }

    public function rincianStore(Request $request,$id){
        $field = [
            'id_kode' => ['required'],
            'id_rekening' => ['required'],
            'spesifikasi' => ['required'],
            'id_satuan' => ['required'],
            'harga' => ['required']
        ];

        $pesan = [
            'id_kode.required' => 'Barang tidak boleh kosong <br />',
            'id_rekening.required' => 'Rekening belanja tidak boleh kosong <br />',
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
            'harga' => $request->harga,
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
            'tahun' => ['required']
        ];

        $pesan = [
            'tahun.required' => 'Tahun SSH boleh kosong <br />'
        ];
        $this->validate($request, $field, $pesan);
        $data = [
            'id_opd' => Auth::user()->id_opd,
            'tahun' => $request->tahun,
            'id_kelompok' => 3,
            'status' => '0'
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
            'id_satuan' => ['required'],
            'harga' => ['required']
        ];

        $pesan = [
            'id_kode.required' => 'Barang tidak boleh kosong <br />',
            'id_rekening.required' => 'Rekening belanja tidak boleh kosong <br />',
            'spesifikasi.required' => 'Spesifikasi tidak boleh kosong <br />',
            'id_satuan.required' => 'Satuan tidak boleh kosong <br />',
            'harga.required' => 'Harga tidak boleh kosong <br />',
        ];
        $this->validate($request, $field, $pesan);
        $data = [
            'id_kode' => $request->id_kode,
            'id_rekening' => $request->id_rekening,
            'spesifikasi' => $request->spesifikasi,
            'harga' => $request->harga,
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

    public function tolak($id){
        $sbu = UsulanSsh::find($id);
        $data = [
            'status' => '0'
        ];
        $sbu->update($data);
        return response()->json('usulan SBU berhasil dikembalikan',200);
    }

    public function exportPDF($id){
        $sbu = dataSbu::where('id_usulan','=',decrypt($id))->get();
        $tahun = getValue("tahun","usulan_ssh"," id = ".decrypt($id));
        $ttd = TtdSetting::where('id_opd','=',Auth::user()->id_opd)->first();
        $opd = getValue("opd","data_opd"," id =".Auth::user()->id_opd);
        $data = [
            'tahun' => $tahun,
            'instansi' => "PEMERINTAH PROVINSI PAPUA BARAT DAYA",
            'title' => "STANDAR BIAYA UMUM TAHUN ANGGARAN",
            'sbu' => $sbu,
            'ttd' => $ttd,
            'opd' => $opd
        ];
        $pdf = PDF::loadView('pdf.asb',$data);
        $pdf->setPaper('F4', 'landscape');
        return $pdf->stream('sbu-'.Auth::user()->id_opd.'-TA-'.$tahun.'-' . date('Y-m-d H:i:s') . '.pdf');
    }
}
