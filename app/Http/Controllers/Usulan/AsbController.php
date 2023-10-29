<?php

namespace App\Http\Controllers\Usulan;

use App\Http\Controllers\Controller;
use App\Models\dataAsb;
use App\Models\DataOpd;
use App\Models\DataSatuan;
use App\Models\KodeBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AsbController extends Controller
{
    public function index(){
        $kode_barang = KodeBarang::where('kelompok','=','3')->get();
        $satuan = DataSatuan::all();
        $instansi = DataOpd::all();
        return view('usulan.asb',[
            'title' => 'Usulan',
            'page' => 'ASB',
            'drops' => [
                'kode_barang' => $kode_barang,
                'instansi' => $instansi,
                'satuan' => $satuan
            ]
        ]);
    }

    public function data(){
        if(Auth::user()->level == 'aset'){
            $asb = dataAsb::where('id_kelompok','=','3')->whereIn('status',['1','2'])->get();
        }else{
            $asb = dataAsb::where('id_kelompok','=','3')->where('id_opd','=',Auth::user()->id_opd)->get();
        }
        return datatables()->of($asb)
                ->addIndexColumn()
                ->addColumn('q_opd',function($asb) {
                    return getValue("opd","data_opd","id = ".$asb->id_opd);
                })
                ->addColumn('uraian',function($asb) {
                    return getValue("uraian","referensi_kode_barang","id = ".$asb->id_kode);
                })
                ->addColumn('satuan',function($asb){
                    return getValue("nm_satuan","data_satuan","id = ".$asb->id_satuan);
                })
                ->addColumn('aksi', function($asb){
                    if(Auth::user()->level == 'aset'){
                        if($asb->status == '1'){
                            $aksi = '
                            <div class="btn-group">
                            <a href="javascript:void(0)" onclick="verifAsb(`'.route('asb.validasi',$asb->id).'`)" class="btn btn-sm btn-primary" title="Validasi"><i class="fas fa-paper-plane"></i></a>
                            <a href="javascript:void(0)" onclick="tolakAsb(`'.route('asb.reject',$asb->id).'`)" class="btn btn-sm btn-danger" title="Tolak"><i class="fas fa-redo"></i></a>
                            </div>
                            ';
                        }
                        if($asb->status == '2'){
                            $aksi = '
                            <div class="btn-group">
                                <a href="javascript:void(0)" onclick="editAsb(`'.route('asb.update',$asb->id).'`,'.$asb->id.')" class="btn btn-sm btn-warning" title="Ubah" ><i class="fas fa-edit"></i></a>
                                <a href="javascript:void(0)" onclick="hapusAsb(`'.route('asb.destroy',$asb->id).'`)" class="btn btn-sm btn-danger" title="Hapus"><i class="fas fa-trash"></i></a>
                            </div>
                            ';
                        }
                    }
                    if((Auth::user()->level == 'operator' || Auth::user()->level == 'bendahara')){
                        if($asb->status == '0'){
                            $aksi = '
                            <div class="btn-group">
                                <a href="javascript:void(0)" onclick="editAsb(`'.route('asb.update',$asb->id).'`,'.$asb->id.')" class="btn btn-sm btn-warning" title="Ubah" ><i class="fas fa-edit"></i></a>
                                <a href="javascript:void(0)" onclick="hapusAsb(`'.route('asb.destroy',$asb->id).'`)" class="btn btn-sm btn-danger" title="Hapus"><i class="fas fa-trash"></i></a>
                                <a href="javascript:void(0)" onclick="verifAsb(`'.route('asb.validasi',$asb->id).'`)" class="btn btn-sm btn-primary" title="Validasi"><i class="fas fa-paper-plane"></i></a>
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
            'id_kelompok' => 3,
            'status' => '0'
        ];

        dataAsb::create($data);
        return response()->json('usulan asb berhasil ditambahkan',200);
    }

    public function show($id){
        $asb = dataAsb::find($id);
        return response()->json($asb);
    }

    public function update(Request $request,$id){
        $asb = dataAsb::find($id);
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
        $asb->update($data);
        return response()->json('usulan asb berhasil diubah',200);
    }

    public function destroy($id){
        $asb = dataAsb::find($id);
        $asb->delete();
        return response()->json('usulan asb berhasil dihapus', 204);
    }

    public function validasi($id){
        $asb = dataAsb::find($id);
        if(Auth::user()->level == 'operator' || Auth::user()->level == 'bendahara'){
            $verif = ['status' => '1'];
            $respon = 'usulan asb berhasil dikirim ke admin Aset';
        }elseif(Auth::user()->level == 'aset'){
            $verif = ['status' => '2'];
            $respon = 'usulan asb telah diterima';
        }
        $asb->update($verif);
        return response()->json($respon,200);
    }

    public function tolak($id){
        $asb = dataAsb::find($id);
        $data = [
            'status' => '0'
        ];
        $asb->update($data);
        return response()->json('usulan asb berhasil dikembalikan',200);
    }
}
