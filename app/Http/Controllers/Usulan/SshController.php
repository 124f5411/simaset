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
        $kode_barang = KodeBarang::where('kelompok','=','1')->get();
        $satuan = DataSatuan::all();
        $instansi = DataOpd::all();
        return view('usulan.ssh.rincian',[
            'title' => 'Usulan',
            'page' => 'SSH',
            'drops' => [
                'kode_barang' => $kode_barang,
                'instansi' => $instansi,
                'satuan' => $satuan
            ]
        ]);
    }

    public function usulan($id){
        if(Auth::user()->level == 'aset'){
            $ssh = dataSsh::where('id_kelompok','=','1')->whereIn('status',['1','2'])->get();
        }else{
            $ssh = dataSsh::where('id_usulan','=',$id)->get();
        }
        return datatables()->of($ssh)
                ->addIndexColumn()
                ->addColumn('uraian',function($ssh) {
                    return getValue("uraian","referensi_kode_barang","id = ".$ssh->id_kode);
                })
                ->addColumn('satuan',function($ssh){
                    return getValue("nm_satuan","data_satuan","id = ".$ssh->id_satuan);
                })
                ->addColumn('aksi', function($ssh){
                    if(Auth::user()->level == 'aset'){
                        if($ssh->status == '1'){
                            $aksi = '
                            <div class="btn-group">
                            <a href="javascript:void(0)" onclick="verifSsh(`'.route('ssh.validasi',$ssh->id).'`)" class="btn btn-sm btn-primary" title="Validasi"><i class="fas fa-paper-plane"></i></a>
                            <a href="javascript:void(0)" onclick="tolakSsh(`'.route('ssh.reject',$ssh->id).'`)" class="btn btn-sm btn-danger" title="Tolak"><i class="fas fa-redo"></i></a>
                            </div>
                            ';
                        }
                        if($ssh->status == '2'){
                            $aksi = '
                            <div class="btn-group">
                                <a href="javascript:void(0)" onclick="editSsh(`'.route('asb.update',$ssh->id).'`,'.$ssh->id.')" class="btn btn-sm btn-warning" title="Ubah" ><i class="fas fa-edit"></i></a>
                                <a href="javascript:void(0)" onclick="hapusSsh(`'.route('asb.destroy',$ssh->id).'`)" class="btn btn-sm btn-danger" title="Hapus"><i class="fas fa-trash"></i></a>
                            </div>
                            ';
                        }
                    }
                    if((Auth::user()->level == 'operator' || Auth::user()->level == 'bendahara')){
                        if($ssh->status == '0'){
                            $aksi = '
                            <div class="btn-group">
                                <a href="javascript:void(0)" onclick="editSsh(`'.route('ssh.update',$ssh->id).'`,'.$ssh->id.')" class="btn btn-sm btn-warning" title="Ubah" ><i class="fas fa-edit"></i></a>
                                <a href="javascript:void(0)" onclick="hapusSsh(`'.route('ssh.destroy',$ssh->id).'`)" class="btn btn-sm btn-danger" title="Hapus"><i class="fas fa-trash"></i></a>
                                <a href="javascript:void(0)" onclick="verifSsh(`'.route('ssh.validasi',$ssh->id).'`)" class="btn btn-sm btn-primary" title="Validasi"><i class="fas fa-paper-plane"></i></a>
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

    public function rincianStore(Request $request,$id){
        $field = [
            'id_kode' => ['required'],
            'spesifikasi' => ['required'],
            'id_satuan' => ['required'],
            'harga' => ['required']
        ];

        $pesan = [
            'id_kode.required' => 'Barang tidak boleh kosong <br />',
            'spesifikasi.required' => 'Spesifikasi tidak boleh kosong <br />',
            'id_satuan.required' => 'Satuan tidak boleh kosong <br />',
            'harga.required' => 'Harga tidak boleh kosong <br />',
        ];
        $this->validate($request, $field, $pesan);
        $data = [
            'id_kode' => $request->id_kode,
            'id_usulan' => $id,
            'spesifikasi' => $request->spesifikasi,
            'harga' => $request->harga,
            'id_satuan' => $request->id_satuan,
            'status' => '0'
        ];

        dataSsh::create($data);
        return response()->json('usulan ssh berhasil ditambahkan',200);
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
