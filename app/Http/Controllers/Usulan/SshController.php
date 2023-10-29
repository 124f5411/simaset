<?php

namespace App\Http\Controllers\Usulan;

use App\Http\Controllers\Controller;
use App\Models\DataOpd;
use App\Models\DataSatuan;
use App\Models\dataSsh;
use App\Models\KodeBarang;
use App\Models\UsulanSsh;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SshController extends Controller
{
    public function index(){
        if(Auth::user()->level == 'aset'){
            return view('usulan.ssh.aset',[
                'title' => 'Usulan',
                'page' => 'SSH'
            ]);
        }
        return view('usulan.ssh.index',[
            'title' => 'Usulan',
            'page' => 'SSH'
        ]);
    }

    public function rincian($id){

    }

    public function usulan($id){

    }


    public function data(){
        $ssh = UsulanSsh::where('id_kelompok','=','1')->where('id_opd','=',Auth::user()->id_opd)->get();
        return datatables()->of($ssh)
                ->addIndexColumn()
                ->addColumn('q_opd',function($ssh) {
                    return getValue("opd","data_opd","id = ".$ssh->id_opd);
                })
                ->addColumn('dokumen',function($ssh) {
                    return (!is_null($ssh->ssd_dokumen)) ? "OK" : "-";
                })
                ->addColumn('rincian',function($ssh) {
                    return '
                    <a href="#" class="btn btn-sm btn-success btn-icon-split">
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
                            $aksi = '
                            <div class="btn-group">
                                <a href="javascript:void(0)" onclick="editSsh(`'.route('ssh.update',$ssh->id).'`,'.$ssh->id.')" class="btn btn-sm btn-warning" title="Ubah" ><i class="fas fa-edit"></i></a>
                                <a href="javascript:void(0)" onclick="hapusSsh(`'.route('ssh.destroy',$ssh->id).'`)" class="btn btn-sm btn-danger" title="Hapus"><i class="fas fa-trash"></i></a>
                                <a href="javascript:void(0)" onclick="verifSsh(`'.route('ssh.validasi',$ssh->id).'`)" class="btn btn-sm btn-primary" title="Validasi"><i class="fas fa-paper-plane"></i></a>
                                <a href="javascript:void(0)" onclick="verifSsh(`'.route('ssh.validasi',$ssh->id).'`)" class="btn btn-sm btn-success" title="Upload"><i class="fas fa-upload"></i></a>
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
                ->rawColumns(['aksi','rincian'])
                ->make(true);
    }

    public function store(Request $request){
        $field = [
            'tahun' => ['required'],
        ];

        $pesan = [
            'tahun.required' => 'Tahun SSH tidak boleh kosong <br />',
        ];
        $this->validate($request, $field, $pesan);
        $data = [
            'id_opd' => Auth::user()->id_opd,
            'tahun' => $request->tahun,
            'id_kelompok' => 1,
            'status' => '0'
        ];

        UsulanSsh::create($data);
        return response()->json('SSH berhasil dibuat',200);
    }

    public function show($id){
        $ssh = UsulanSsh::find($id);
        return response()->json($ssh);
    }

    public function update(Request $request,$id){
        $ssh = UsulanSsh::find($id);
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
            'id_kelompok' => 1,
            'status' => '0'
        ];
        $ssh->update($data);
        return response()->json('SSH berhasil diubah',200);
    }

    public function destroy($id){
        $ssh = UsulanSsh::find($id);
        $ssh->delete();
        return response()->json('SSH berhasil dihapus', 204);
    }

}
