<?php

namespace App\Http\Controllers\Usulan;

use App\Http\Controllers\Controller;
use App\Models\dataHspk;
use App\Models\DataOpd;
use App\Models\DataSatuan;
use App\Models\KodeBarang;
use App\Models\RekeningBelanja;
use App\Models\TtdSetting;
use App\Models\UsulanSsh;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\Auth;

class HspkController extends Controller
{
    public function index(){
        if(Auth::user()->level == 'aset'){
            $instansi = DataOpd::all();
            return view('usulan.hspk.aset',[
                'title' => 'Usulan',
                'page' => 'HSPK',
                'drops' => [
                    'instansi' => $instansi
                ]
            ]);
        }
        return view('usulan.hspk.index',[
            'title' => 'Usulan',
            'page' => 'HSPK'
        ]);
    }

    public function rincian($id){
        $usulan = UsulanSsh::find(decrypt($id));
        $kode_barang = KodeBarang::where('kelompok','=','4')->get();
        $rekening = RekeningBelanja::all();
        $satuan = DataSatuan::all();
        $instansi = DataOpd::all();
        return view('usulan.hspk.rincian',[
            'title' => 'Usulan',
            'page' => 'HSPK',
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
        $hspk = UsulanSsh::select('usulan_ssh.*','_data_ssh.id as id_ssh','_data_ssh.id_kode','_data_ssh.id_usulan','_data_ssh.spesifikasi','_data_ssh.id_satuan','_data_ssh.harga','_data_ssh.status')
                        ->join('_data_ssh','usulan_ssh.id','=','_data_ssh.id_usulan')
                        ->where('usulan_ssh.id_kelompok','=',3)
                        ->whereIn('usulan_ssh.status',['1','2'])->get();

        return datatables()->of($hspk)
                ->addIndexColumn()
                ->addColumn('q_opd',function($hspk) {
                    return getValue("opd","data_opd","id = ".$hspk->id_opd);
                })
                ->addColumn('uraian',function($hspk) {
                    return getValue("uraian","referensi_kode_barang","id = ".$hspk->id_kode);
                })
                ->addColumn('satuan',function($hspk){
                    return getValue("nm_satuan","data_satuan","id = ".$hspk->id_satuan);
                })
                ->addColumn('dokumen',function($hspk){
                    $dok = '
                    <div class="btn-group">
                        <a href="'.asset('upload/hspk/'.$hspk->ssd_dokumen).'" target="_blank" class="btn btn-sm btn-danger btn-icon-split">
                            <span class="icon text-white-50">
                                <i class="fas fa-file-pdf"></i>
                            </span>
                            <span class="text">PDF</span>
                        </a>
                    </div>
                    ';
                    return $dok;
                })
                ->addColumn('aksi', function($hspk){
                    if(Auth::user()->level == 'aset'){
                        if($hspk->status == '1'){
                            $aksi = '
                            <div class="btn-group">
                                <a href="javascript:void(0)" onclick="verifHspk(`'.route('hspk.rincianValidasi',$hspk->id).'`)" class="btn btn-sm btn-primary" title="Validasi"><i class="fas fa-paper-plane"></i></a>
                                <a href="javascript:void(0)" onclick="tolakHspk(`'.route('hspk.rincianReject',$hspk->id).'`)" class="btn btn-sm btn-danger" title="Tolak"><i class="fas fa-redo"></i></a>
                            </div>
                            ';
                        }
                        if($hspk->status == '2'){
                            $aksi = 'Valid';
                        }
                    }
                    return $aksi;
                })
                ->rawColumns(['aksi','dokumen'])
                ->make(true);
    }

    public function data_rincian($id){
        $hspk = dataHspk::where('id_usulan','=',$id)->get();
        return datatables()->of($hspk)
                ->addIndexColumn()
                ->addColumn('uraian',function($hspk) {
                    return getValue("uraian","referensi_kode_barang","id = ".$hspk->id_kode);
                })
                ->addColumn('kode_barang',function($hspk) {
                    return getValue("kode_barang","referensi_kode_barang","id = ".$hspk->id_kode);
                })
                ->addColumn('rekening_belanja',function($hspk) {
                    return getValue("kode_akun","referensi_rekening_belanja","id = ".$hspk->id_rekening);
                })
                ->addColumn('satuan',function($hspk){
                    return getValue("nm_satuan","data_satuan","id = ".$hspk->id_satuan);
                })
                ->addColumn('aksi', function($hspk){
                    if((Auth::user()->level == 'operator' || Auth::user()->level == 'bendahara')){
                        if($hspk->status == '0'){
                            $aksi = '
                            <div class="btn-group">
                                <a href="javascript:void(0)" onclick="editHspk(`'.route('hspk.rincianUpdate',$hspk->id).'`,'.$hspk->id.')" class="btn btn-sm btn-warning" title="Ubah" ><i class="fas fa-edit"></i></a>
                                <a href="javascript:void(0)" onclick="hapusHspk(`'.route('hspk.rincianDestroy',$hspk->id).'`)" class="btn btn-sm btn-danger" title="Hapus"><i class="fas fa-trash"></i></a>
                            </div>
                            ';
                        }
                        if($hspk->status == '1'){
                            $aksi = 'Proccesed';
                        }

                        if($hspk->status == '2'){
                            $aksi = 'Valid';
                        }
                    }
                    return $aksi;
                })
                ->rawColumns(['aksi'])
                ->make(true);
    }

    public function data(){
        $hspk = UsulanSsh::where('id_kelompok','=','4')->where('id_opd','=',Auth::user()->id_opd)->get();
        return datatables()->of($hspk)
                ->addIndexColumn()
                ->addColumn('q_opd',function($hspk) {
                    return getValue("opd","data_opd","id = ".$hspk->id_opd);
                })
                ->addColumn('dokumen',function($hspk) {
                    if(is_null($hspk->ssd_dokumen)){
                        $aksi = '
                            <a href="javascript:void(0)" onclick="hspkUpload(`'.route('hspk.upload',$hspk->id).'`)" class="btn btn-sm btn-success btn-icon-split">
                                <span class="icon text-white-50">
                                    <i class="fas fa-upload"></i>
                                </span>
                                <span class="text">Upload</span>
                            </a>
                        ';
                    }else{
                        $aksi = '
                        <div class="btn-group">
                            <a href="'.asset('upload/hspk/'.$hspk->ssd_dokumen).'" target="_blank" class="btn btn-sm btn-danger btn-icon-split">
                                <span class="icon text-white-50">
                                    <i class="fas fa-file-pdf"></i>
                                </span>
                                <span class="text">PDF</span>
                            </a>
                            <a href="javascript:void(0)" onclick="hspkUpload(`'.route('hspk.upload',$hspk->id).'`)" class="btn btn-sm btn-success btn-icon-split">
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
                ->addColumn('rincian',function($hspk) {
                    return '
                    <a href="'.route('hspk.rincian',encrypt($hspk->id)).'" class="btn btn-sm btn-success btn-icon-split">
                        <span class="icon text-white-50">
                            <i class="fas fa-eye"></i>
                        </span>
                        <span class="text">Rincian</span>
                    </a>
                    ';
                })
                ->addColumn('aksi', function($hspk){
                    if((Auth::user()->level == 'operator' || Auth::user()->level == 'bendahara')){
                        if($hspk->status == '0'){
                            if(is_null($hspk->ssd_dokumen)){
                                $aksi = '
                                    <div class="btn-group">
                                        <a href="javascript:void(0)" onclick="editHspk(`'.route('hspk.update',$hspk->id).'`,'.$hspk->id.')" class="btn btn-sm btn-warning" title="Ubah" ><i class="fas fa-edit"></i></a>
                                        <a href="javascript:void(0)" onclick="hapusHspk(`'.route('hspk.destroy',$hspk->id).'`)" class="btn btn-sm btn-danger" title="Hapus"><i class="fas fa-trash"></i></a>
                                    </div>
                                    ';
                            }else{
                                $aksi = '
                                <div class="btn-group">
                                    <a href="javascript:void(0)" onclick="editHspk(`'.route('hspk.update',$hspk->id).'`,'.$hspk->id.')" class="btn btn-sm btn-warning" title="Ubah" ><i class="fas fa-edit"></i></a>
                                    <a href="javascript:void(0)" onclick="hapusHspk(`'.route('hspk.destroy',$hspk->id).'`)" class="btn btn-sm btn-danger" title="Hapus"><i class="fas fa-trash"></i></a>
                                    <a href="javascript:void(0)" onclick="verifHspk(`'.route('hspk.validasi',$hspk->id).'`)" class="btn btn-sm btn-primary" title="Validasi"><i class="fas fa-paper-plane"></i></a>
                                </div>
                                ';
                            }
                        }
                        if($hspk->status == '1'){
                            $aksi = 'Proccesed';
                        }

                        if($hspk->status == '2'){
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
            'tahun.required' => 'Tahun HSPK tidak boleh kosong <br />',
        ];
        $this->validate($request, $field, $pesan);
        $data = [
            'id_opd' => Auth::user()->id_opd,
            'tahun' => $request->tahun,
            'id_kelompok' => 4,
            'status' => '0'
        ];

        UsulanSsh::create($data);
        return response()->json('HSPK berhasil dibuat',200);
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

        dataHspk::create($data);
        return response()->json('item HSPK berhasil ditambahkan',200);
    }

    public function show($id){
        $hspk = UsulanSsh::find($id);
        return response()->json($hspk);
    }

    public function rincianShow($id){
        $hspk = dataHspk::find($id);
        return response()->json($hspk);
    }

    public function update(Request $request,$id){
        $asb = UsulanSsh::find($id);
        $field = [
            'tahun' => ['required']
        ];

        $pesan = [
            'tahun.required' => 'Tahun HSPK boleh kosong <br />'
        ];
        $this->validate($request, $field, $pesan);
        $data = [
            'id_opd' => Auth::user()->id_opd,
            'tahun' => $request->tahun,
            'id_kelompok' => 4,
            'status' => '0'
        ];
        $asb->update($data);
        return response()->json('HSPK berhasil diubah',200);
    }

    public function rincianUpdate(Request $request,$id){
        $hspk = dataHspk::find($id);
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

        $hspk->update($data);
        return response()->json('item HSPK berhasil diubah',200);
    }

    public function destroy($id){
        $hspk = UsulanSsh::find($id);
        $hspk->delete();
        return response()->json('HSPK berhasil dihapus', 204);
    }

    public function rincianDestroy($id){
        $hspk = dataHspk::find($id);
        $hspk->delete();
        return response()->json('item HSPK berhasil dihapus', 204);
    }

    public function upload(Request $request,$id){
        $hspk = UsulanSsh::find($id);
        $filter = [
            'ssd_dokumen' => 'required|mimes:pdf',
        ];
        $pesan = [
            'ssd_dokumen.required' => 'Dokumen HSPK tidak boleh kosong <br />',
            'ssd_dokumen.mimes' => 'Dokumen HSPK harus berformat PDF <br />'
        ];
        $this->validate($request, $filter, $pesan);
        $dok = $request->file('ssd_dokumen');
        $nm = 'hspk-'.date('Y').'-'.date('Ymdhis').'.'.$dok->getClientOriginalExtension();
        if(!is_null($hspk->ssd_dokumen)){
            unlink(public_path()."/upload/hspk/".$hspk->ssd_dokumen);
        }
        $dok->move(public_path('upload/hspk'),$nm);
        $data = [
            'ssd_dokumen' => $nm
        ];
        $hspk->update($data);
        return response()->json('dokumen ASB berhasil diupload',200);
    }

    public function validasi($id){
        $hspk = UsulanSsh::find($id);
        if(Auth::user()->level == 'operator' || Auth::user()->level == 'bendahara'){
            $verif = ['status' => '1'];
            $respon = 'usulan HSPK berhasil dikirim ke admin Aset';
            $item_status = ['status' => '1'];
            dataHspk::where('id_usulan','=',$hspk->id)->update($item_status);
        }elseif(Auth::user()->level == 'aset'){
            $verif = ['status' => '2'];
            $respon = 'usulan HSPK telah diterima';
        }
        $hspk->update($verif);
        return response()->json($respon,200);
    }

    public function tolak($id){
        $hspk = UsulanSsh::find($id);
        $data = [
            'status' => '0'
        ];
        $hspk->update($data);
        return response()->json('usulan HSPK berhasil dikembalikan',200);
    }

    public function exportPDF($id){
        $hspk = dataHspk::where('id_usulan','=',decrypt($id))->get();
        $tahun = getValue("tahun","usulan_ssh"," id = ".decrypt($id));
        $ttd = TtdSetting::where('id_opd','=',Auth::user()->id_opd)->first();
        $opd = getValue("opd","data_opd"," id =".Auth::user()->id_opd);
        $data = [
            'tahun' => $tahun,
            'instansi' => "PEMERINTAH PROVINSI PAPUA BARAT DAYA",
            'title' => "ANALISIS STANDA BELANJA TAHUN ANGGARAN",
            'hspk' => $hspk,
            'ttd' => $ttd,
            'opd' => $opd
        ];
        $pdf = PDF::loadView('pdf.hspk',$data);
        $pdf->setPaper('F4', 'landscape');
        return $pdf->stream('hspk-'.Auth::user()->id_opd.'-TA-'.$tahun.'-' . date('Y-m-d H:i:s') . '.pdf');
    }
}
