<?php

namespace App\Http\Controllers\Input;

use App\Http\Controllers\Controller;
use App\Models\dataSsh;
use App\Models\UsulanSsh;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsulanController extends Controller
{
    public function index(){
        return view('input.usulan',[
            'title' => 'Input',
            'page' => 'Usulan'
        ]);
    }

    public function data(){
        $usulan = UsulanSsh::where('id_opd','=',Auth::user()->id_opd);
        return datatables()->of($usulan)
                ->addIndexColumn()
                ->addColumn('q_opd',function($usulan) {
                    return getValue("opd","data_opd","id = ".$usulan->id_opd);
                })
                ->addColumn('usulan',function($usulan) {
                    $jenis = [
                        '1' => 'Induk',
                        '2' => 'Perubahan'
                    ];
                    return $jenis[$usulan->induk_perubahan];
                })
                ->addColumn('dokumen',function($usulan) {
                    if(is_null($usulan->ssd_dokumen)){
                        $aksi = '
                            <a href="javascript:void(0)" onclick="sshUpload(`'.route('usulan.upload',$usulan->id).'`)" class="btn btn-sm btn-success btn-icon-split">
                                <span class="icon text-white-50">
                                    <i class="fas fa-upload"></i>
                                </span>
                                <span class="text">Upload</span>
                            </a>
                        ';
                    }else{
                        $aksi = '
                        <div class="btn-group">
                            <a href="'.asset('upload/usulan/'.$usulan->ssd_dokumen).'" target="_blank" class="btn btn-sm btn-danger btn-icon-split">
                                <span class="icon text-white-50">
                                    <i class="fas fa-file-pdf"></i>
                                </span>
                                <span class="text">PDF</span>
                            </a>
                            <a href="javascript:void(0)" onclick="sshUpload(`'.route('usulan.upload',$usulan->id).'`)" class="btn btn-sm btn-success btn-icon-split">
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
                ->addcolumn('rincian',function($usulan){
                    return '
                    <a href="'.route('rincian.index',encrypt($usulan->id)).'" class="btn btn-sm btn-success btn-icon-split">
                        <span class="icon text-white-50">
                            <i class="fas fa-eye"></i>
                        </span>
                        <span class="text">Rincian</span>
                    </a>
                    ';
                })
                ->addColumn('aksi', function($usulan) {
                    $aksi = [
                        '0' => (is_null($usulan->ssd_dokumen)) ? '<div class="btn-group">
                                <a href="javascript:void(0)" onclick="editSsh(`'.route('usulan.update',$usulan->id).'`,'.$usulan->id.')" class="btn btn-sm btn-warning" title="Ubah" ><i class="fas fa-edit"></i></a>
                                <a href="javascript:void(0)" onclick="hapusSsh(`'.route('usulan.destroy',$usulan->id).'`)" class="btn btn-sm btn-danger" title="Hapus"><i class="fas fa-trash"></i></a>
                                </div>' : '<div class="btn-group">
                                            <a href="javascript:void(0)" onclick="editSsh(`'.route('usulan.update',$usulan->id).'`,'.$usulan->id.')" class="btn btn-sm btn-warning" title="Ubah" ><i class="fas fa-edit"></i></a>
                                            <a href="javascript:void(0)" onclick="hapusSsh(`'.route('usulan.destroy',$usulan->id).'`)" class="btn btn-sm btn-danger" title="Hapus"><i class="fas fa-trash"></i></a>
                                            <a href="javascript:void(0)" onclick="verifSsh(`'.route('usulan.validasi',$usulan->id).'`)" class="btn btn-sm btn-primary" title="Validasi"><i class="fas fa-paper-plane"></i></a>
                                        </div>',
                        '1' => 'Terkirim',
                        '2' => 'Valid',
                        '3' => '
                                <div class="btn-group">
                                    <a href="javascript:void(0)" onclick="editSsh(`'.route('usulan.update',$usulan->id).'`,'.$usulan->id.')" class="btn btn-sm btn-warning" title="Ubah" ><i class="fas fa-edit"></i></a>
                                    <a href="javascript:void(0)" onclick="hapusSsh(`'.route('usulan.destroy',$usulan->id).'`)" class="btn btn-sm btn-danger" title="Hapus"><i class="fas fa-trash"></i></a>
                                    <a href="javascript:void(0)" onclick="verifSsh(`'.route('usulan.validasi',$usulan->id).'`)" class="btn btn-sm btn-primary" title="Validasi"><i class="fas fa-paper-plane"></i></a>
                                </div>
                                <div class="badge badge-danger mt-2 text-wrap" style="width: 6rem;">
                                    Ditolak<br>cek rincian
                                </div>
                                '
                    ];
                    return $aksi[$usulan->status];
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
            'status' => '0'
        ];

        UsulanSsh::create($data);
        return response()->json('Usulan berhasil dibuat',200);
    }

    public function show($id){
        $usulan = UsulanSsh::find($id);
        return response()->json($usulan);
    }

    public function update(Request $request,$id){
        $usulan = UsulanSsh::find($id);
        $field = [
            'tahun' => ['required'],
            'induk_perubahan' => ['required']
        ];

        $pesan = [
            'tahun.required' => 'Tahun Usulan tidak boleh kosong <br />',
            'induk_perubahan.required' => 'Jenis usulan tidak boleh kosong <br />',
        ];
        $this->validate($request, $field, $pesan);
        $data = [
            'tahun' => $request->tahun,
            'induk_perubahan' => $request->induk_perubahan
        ];
        $usulan->update($data);
        return response()->json('Usulan berhasil diubah',200);
    }

    public function destroy($id){
        $usulan = UsulanSsh::find($id);
        $usulan->delete();
        return response()->json('Usulan berhasil dihapus', 204);
    }

    public function upload(Request $request,$id){
        $usulan = UsulanSsh::find($id);
        $filter = [
            'ssd_dokumen' => 'required|mimes:pdf',
        ];
        $pesan = [
            'ssd_dokumen.required' => 'Dokumen Pakta Usulan tidak boleh kosong <br />',
            'ssd_dokumen.mimes' => 'Dokumen Pakta Usulan harus berformat PDF <br />'
        ];
        $this->validate($request, $filter, $pesan);
        $dok = $request->file('ssd_dokumen');
        $nm = 'ssh-'.date('Y').'-'.date('Ymdhis').'.'.$dok->getClientOriginalExtension();
        // if(!is_null($usulan->ssd_dokumen)){
        //     unlink(public_path()."/upload/usulan/".$usulan->ssd_dokumen);
        // }
        $dok->move(public_path('upload/usulan'),$nm);
        $data = [
            'ssd_dokumen' => $nm
        ];
        $usulan->update($data);
        return response()->json('Dokumen Pakta Usulan berhasil diupload',200);
    }

    public function validasi($id){
        $usulan = UsulanSsh::find($id);
        $verif = ['status' => '1'];
        $respon = 'usulan berhasil dikirim ke admin Aset';
        $item_status = ['status' => '1'];
        dataSsh::where('id_usulan','=',$usulan->id)->update($item_status);
        $usulan->update($verif);
        return response()->json($respon,200);
    }
}
