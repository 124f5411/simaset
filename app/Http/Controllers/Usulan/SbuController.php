<?php

namespace App\Http\Controllers\Usulan;

use App\Http\Controllers\Controller;
use App\Models\DataOpd;
use App\Models\DataSatuan;
use App\Models\dataSbu;
use App\Models\KodeBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SbuController extends Controller
{
    public function index(){
        $kode_barang = KodeBarang::where('kelompok','=','2')->get();
        $satuan = DataSatuan::all();
        $instansi = DataOpd::all();
        return view('usulan.sbu',[
            'title' => 'Usulan',
            'page' => 'SBU',
            'drops' => [
                'kode_barang' => $kode_barang,
                'instansi' => $instansi,
                'satuan' => $satuan
            ]
        ]);
    }

    public function data(){
        if(Auth::user()->level == 'aset'){
            $sbu = dataSbu::where('id_kelompok','=','2')->whereIn('status',['1','2'])->get();
        }else{
            $sbu = dataSbu::where('id_kelompok','=','2')->where('id_opd','=',Auth::user()->id_opd)->get();
        }
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
                ->addColumn('aksi', function($sbu){
                    if(Auth::user()->level == 'aset'){
                        if($sbu->status == '1'){
                            $aksi = '
                            <div class="btn-group">
                            <a href="javascript:void(0)" onclick="verifSbu(`'.route('sbu.validasi',$sbu->id).'`)" class="btn btn-sm btn-primary" title="Validasi"><i class="fas fa-paper-plane"></i></a>
                            <a href="javascript:void(0)" onclick="tolakSbu(`'.route('sbu.reject',$sbu->id).'`)" class="btn btn-sm btn-danger" title="Tolak"><i class="fas fa-redo"></i></a>
                            </div>
                            ';
                        }
                        if($sbu->status == '2'){
                            $aksi = '
                            <div class="btn-group">
                                <a href="javascript:void(0)" onclick="editSbu(`'.route('sbu.update',$sbu->id).'`,'.$sbu->id.')" class="btn btn-sm btn-warning" title="Ubah" ><i class="fas fa-edit"></i></a>
                                <a href="javascript:void(0)" onclick="hapusSbu(`'.route('sbu.destroy',$sbu->id).'`)" class="btn btn-sm btn-danger" title="Hapus"><i class="fas fa-trash"></i></a>
                            </div>
                            ';
                        }
                    }
                    if((Auth::user()->level == 'operator' || Auth::user()->level == 'bendahara')){
                        if($sbu->status == '0'){
                            $aksi = '
                            <div class="btn-group">
                                <a href="javascript:void(0)" onclick="editSbu(`'.route('sbu.update',$sbu->id).'`,'.$sbu->id.')" class="btn btn-sm btn-warning" title="Ubah" ><i class="fas fa-edit"></i></a>
                                <a href="javascript:void(0)" onclick="hapusSbu(`'.route('sbu.destroy',$sbu->id).'`)" class="btn btn-sm btn-danger" title="Hapus"><i class="fas fa-trash"></i></a>
                                <a href="javascript:void(0)" onclick="verifSbu(`'.route('sbu.validasi',$sbu->id).'`)" class="btn btn-sm btn-primary" title="Validasi"><i class="fas fa-paper-plane"></i></a>
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

    public function store(Request $request){
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
            'id_opd' => Auth::user()->id_opd,
            'spesifikasi' => $request->spesifikasi,
            'harga' => $request->harga,
            'id_satuan' => $request->id_satuan,
            'id_kelompok' => 2,
            'status' => '0'
        ];

        dataSbu::create($data);
        return response()->json('usulan sbu berhasil ditambahkan',200);
    }

    public function show($id){
        $sbu = dataSbu::find($id);
        return response()->json($sbu);
    }

    public function update(Request $request,$id){
        $sbu = dataSbu::find($id);
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
            'spesifikasi' => $request->spesifikasi,
            'harga' => $request->harga,
            'id_satuan' => $request->id_satuan
        ];
        $sbu->update($data);
        return response()->json('usulan sbu berhasil diubah',200);
    }

    public function destroy($id){
        $sbu = dataSbu::find($id);
        $sbu->delete();
        return response()->json('usulan sbu berhasil dihapus', 204);
    }

    public function validasi($id){
        $sbu = dataSbu::find($id);
        if(Auth::user()->level == 'operator' || Auth::user()->level == 'bendahara'){
            $verif = ['status' => '1'];
            $respon = 'usulan sbu berhasil dikirim ke admin Aset';
        }elseif(Auth::user()->level == 'aset'){
            $verif = ['status' => '2'];
            $respon = 'usulan sbu telah diterima';
        }
        $sbu->update($verif);
        return response()->json($respon,200);
    }

    public function tolak($id){
        $sbu = dataSbu::find($id);
        $data = [
            'status' => '0'
        ];
        $sbu->update($data);
        return response()->json('usulan sbu berhasil dikembalikan',200);
    }
}
