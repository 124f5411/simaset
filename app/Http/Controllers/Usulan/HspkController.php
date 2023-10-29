<?php

namespace App\Http\Controllers\Usulan;

use App\Http\Controllers\Controller;
use App\Models\dataHspk;
use App\Models\DataOpd;
use App\Models\DataSatuan;
use App\Models\KodeBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HspkController extends Controller
{
    public function index(){
        $kode_barang = KodeBarang::where('kelompok','=','4')->get();
        $satuan = DataSatuan::all();
        $instansi = DataOpd::all();
        return view('usulan.hspk',[
            'title' => 'Usulan',
            'page' => 'HSPK',
            'drops' => [
                'kode_barang' => $kode_barang,
                'instansi' => $instansi,
                'satuan' => $satuan
            ]
        ]);
    }

    public function data(){
        if(Auth::user()->level == 'aset'){
            $hspk = dataHspk::where('id_kelompok','=','4')->whereIn('status',['1','2'])->get();
        }else{
            $hspk = dataHspk::where('id_kelompok','=','4')->where('id_opd','=',Auth::user()->id_opd)->get();
        }
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
                ->addColumn('aksi', function($hspk){
                    if(Auth::user()->level == 'aset'){
                        if($hspk->status == '1'){
                            $aksi = '
                            <div class="btn-group">
                            <a href="javascript:void(0)" onclick="verifHspk(`'.route('hspk.validasi',$hspk->id).'`)" class="btn btn-sm btn-primary" title="Validasi"><i class="fas fa-paper-plane"></i></a>
                            <a href="javascript:void(0)" onclick="tolakHspk(`'.route('hspk.reject',$hspk->id).'`)" class="btn btn-sm btn-danger" title="Tolak"><i class="fas fa-redo"></i></a>
                            </div>
                            ';
                        }
                        if($hspk->status == '2'){
                            $aksi = '
                            <div class="btn-group">
                                <a href="javascript:void(0)" onclick="editHspk(`'.route('hspk.update',$hspk->id).'`,'.$hspk->id.')" class="btn btn-sm btn-warning" title="Ubah" ><i class="fas fa-edit"></i></a>
                                <a href="javascript:void(0)" onclick="hapusHspk(`'.route('hspk.destroy',$hspk->id).'`)" class="btn btn-sm btn-danger" title="Hapus"><i class="fas fa-trash"></i></a>
                            </div>
                            ';
                        }
                    }
                    if((Auth::user()->level == 'operator' || Auth::user()->level == 'bendahara')){
                        if($hspk->status == '0'){
                            $aksi = '
                            <div class="btn-group">
                                <a href="javascript:void(0)" onclick="editHspk(`'.route('hspk.update',$hspk->id).'`,'.$hspk->id.')" class="btn btn-sm btn-warning" title="Ubah" ><i class="fas fa-edit"></i></a>
                                <a href="javascript:void(0)" onclick="hapusHspk(`'.route('hspk.destroy',$hspk->id).'`)" class="btn btn-sm btn-danger" title="Hapus"><i class="fas fa-trash"></i></a>
                                <a href="javascript:void(0)" onclick="verifHspk(`'.route('hspk.validasi',$hspk->id).'`)" class="btn btn-sm btn-primary" title="Validasi"><i class="fas fa-paper-plane"></i></a>
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
            'id_kelompok' => 4,
            'status' => '0'
        ];

        dataHspk::create($data);
        return response()->json('usulan hspk berhasil ditambahkan',200);
    }

    public function show($id){
        $hspk = dataHspk::find($id);
        return response()->json($hspk);
    }

    public function update(Request $request,$id){
        $hspk = dataHspk::find($id);
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
        $hspk->update($data);
        return response()->json('usulan hspk berhasil diubah',200);
    }

    public function destroy($id){
        $hspk = dataHspk::find($id);
        $hspk->delete();
        return response()->json('usulan hspk berhasil dihapus', 204);
    }

    public function validasi($id){
        $hspk = dataHspk::find($id);
        if(Auth::user()->level == 'operator' || Auth::user()->level == 'bendahara'){
            $verif = ['status' => '1'];
            $respon = 'usulan hspk berhasil dikirim ke admin Aset';
        }elseif(Auth::user()->level == 'aset'){
            $verif = ['status' => '2'];
            $respon = 'usulan hspk telah diterima';
        }
        $hspk->update($verif);
        return response()->json($respon,200);
    }

    public function tolak($id){
        $hspk = dataHspk::find($id);
        $data = [
            'status' => '0'
        ];
        $hspk->update($data);
        return response()->json('usulan hspk berhasil dikembalikan',200);
    }
}
