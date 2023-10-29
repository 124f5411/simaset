<?php

namespace App\Http\Controllers\Barang;

use App\Http\Controllers\Controller;
use App\Models\DataJenis;
use App\Models\DataKibB;
use App\Models\DataKontrak;
use App\Models\DataOpd;
use App\Models\DataPeralatan;
use App\Models\KodeBarang;
use App\Models\MasterKib;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PeralatanController extends Controller
{
    public function index(){
        // $view = (Auth::user()->level == 'admin') ? 'barang.tanah.index' : 'barang.tanah.userindex';
        $master = MasterKib::where('kib','=','B')->get();
        $kontrak = DataKontrak::where('opd','=',Auth::user()->id_opd)->get();
        $kode_barang = KodeBarang::where('kib','=','B')->get();
        $instansi = DataOpd::all();
        return view('barang.peralatan.index',[
            'title' => 'Barang',
            'page' => 'Peralatan',
            'drops' => [
                'kontrak' => $kontrak,
                'instansi' => $instansi,
                'kode_barang' =>  $kode_barang
            ]
        ]);

    }



    public function data(){
        if(Auth::user()->level == 'aset'){
            $peralatan = DataPeralatan::whereIn('status',['1','2'])->get();
        }elseif((Auth::user()->level == 'operator' || Auth::user()->level == 'bendahara')){
            $peralatan = DataPeralatan::where('opd','=',Auth::user()->id_opd)->get();
        }


        return datatables()->of($peralatan)
                ->addIndexColumn()
                ->addColumn('q_opd',function($peralatan) {
                    return getValue("opd","data_opd","id = ".$peralatan->opd);
                })
                ->addColumn('nm_peralatan', function($peralatan){
                    return getValue("uraian","referensi_kode_barang","kode_barang = '$peralatan->kode'");
                })
                ->addColumn('aksi', function($peralatan){
                    if(Auth::user()->level == 'aset'){
                        if($peralatan->status == '1'){
                            $aksi = '
                            <div class="btn-group">
                            <a href="javascript:void(0)" onclick="verifPeralatan(`'.route('peralatan.validasi',$peralatan->id).'`)" class="btn btn-sm btn-primary" title="Validasi"><i class="fas fa-paper-plane"></i></a>
                            <a href="javascript:void(0)" onclick="tolakPeralatan(`'.route('peralatan.reject',$peralatan->id).'`)" class="btn btn-sm btn-danger" title="Tolak"><i class="fas fa-redo"></i></a>
                            </div>
                            ';
                        }
                        if($peralatan->status == '2'){
                            $aksi = '
                            <div class="btn-group">
                                <a href="javascript:void(0)" onclick="editPeralatan(`'.route('peralatan.update',$peralatan->id).'`,'.$peralatan->id.')" class="btn btn-sm btn-warning" title="Ubah" ><i class="fas fa-edit"></i></a>
                                <a href="javascript:void(0)" onclick="hapusPeralatan(`'.route('peralatan.destroy',$peralatan->id).'`)" class="btn btn-sm btn-danger" title="Hapus"><i class="fas fa-trash"></i></a>
                            </div>
                            ';
                        }
                    }
                    if((Auth::user()->level == 'operator' || Auth::user()->level == 'bendahara')){
                        if($peralatan->status == '0'){
                            $aksi = '
                            <div class="btn-group">
                                <a href="javascript:void(0)" onclick="editPeralatan(`'.route('peralatan.update',$peralatan->id).'`,'.$peralatan->id.')" class="btn btn-sm btn-warning" title="Ubah" ><i class="fas fa-edit"></i></a>
                                <a href="javascript:void(0)" onclick="hapusPeralatan(`'.route('peralatan.destroy',$peralatan->id).'`)" class="btn btn-sm btn-danger" title="Hapus"><i class="fas fa-trash"></i></a>
                                <a href="javascript:void(0)" onclick="verifPeralatan(`'.route('peralatan.validasi',$peralatan->id).'`)" class="btn btn-sm btn-primary" title="Validasi"><i class="fas fa-paper-plane"></i></a>
                            </div>
                            ';
                        }
                        if($peralatan->status == '1'){
                            $aksi = 'Proccesed';
                        }

                        if($peralatan->status == '2'){
                            $aksi = 'Valid';
                        }
                    }
                    return $aksi;

                })
                ->rawColumns(['aksi'])
                ->make(true);
    }

    public function getRegister($kode,$id_opd){
        $register = DataPeralatan::where([
            ['kode','=',$kode],
            ['opd','=',decrypt($id_opd)]
        ])->get();
        if($register->count() == 0){
            $reg = '000000';
        }else{
            $reg = $register[0]->max('register');
        }

        $urut = (int) substr($reg, 1, 6);
		$urut++;
		$regis = sprintf("%06s", $urut);
        return response()->json($regis);
    }

    public function store(Request $request){
        $field = [
            'id_kontrak' => ['required'],
            // 'harga' => ['required'],
            'kode' => ['required'],
            'register' => ['required'],
            // 'merek' => ['required'],
            // 'spek' => ['required'],
            'bahan' => ['required'],
            'tahun' => ['required'],
            // 'pabrik' => ['required'],
            // 'no_rangka' => ['required'],
            // 'no_mesin' => ['required'],
            // 'nopol' => ['required'],
            // 'no_bpkb' => ['required'],
            'asal' => ['required']
        ];

        $pesan = [
            'id_kontrak.required' => 'Nomor kontrak tidak boleh kosong <br />',
            // 'harga.required' => 'Harga tanah tidak boleh kosong <br />',
            'kode.required' => 'Kode tidak boleh kosong <br />',
            'register.required' => 'Nomor register tidak boleh kosong <br />',
            // 'merek.required' => 'Merek tidak boleh kosong <br />',
            // 'spek.required' => 'Spek tidak boleh kosong <br />',
            'bahan.required' => 'Bahan tidak boleh kosong <br />',
            'tahun.required' => 'Tahun tidak boleh kosong <br />',
            // 'pabrik.required' => 'Pabrik tidak boleh kosong <br />',
            // 'no_rangka.required' => 'No rangka tidak boleh kosong <br />',
            // 'no_mesin.required' => 'No mesin tidak boleh kosong <br />',
            // 'nopol.required' => 'No polisi tidak boleh kosong <br />',
            // 'no_bpkb.required' => 'No BPKP tidak boleh kosong <br />',
            'asal.required' => 'Asal usul anggaran tidak boleh kosong <br />',
            'jumlah.required' => 'Jumlah peralatan tidak boleh kosong <br />'
        ];
        $this->validate($request, $field, $pesan);
        $data = [
            'opd' => Auth::user()->id_opd,
            'id_kontrak' => $request->id_kontrak,
            // 'harga' => $request->harga,
            'kode' => $request->kode,
            'register' => $request->register,
            // 'merek' => $request->merek,
            // 'spek' => $request->spek,
            'bahan' => $request->bahan,
            'tahun' => $request->tahun,
            // 'pabrik' => $request->pabrik,
            // 'no_rangka' => $request->no_rangka,
            // 'no_mesin' => $request->no_mesin,
            // 'nopol' => $request->nopol,
            // 'no_bpkb' => $request->no_bpkb,
            'asal' => $request->asal,
            'status' => '0'
        ];

        if($request->filled('harga')){
            $data['harga'] = $request->harga;
        }
        if($request->filled('merek')){
            $data['merek'] = $request->merek;
        }

        if($request->filled('spek')){
            $data['spek'] = $request->spek;
        }

        if($request->filled('pabrik')){
            $data['pabrik'] = $request->pabrik;
        }

        if($request->filled('no_rangka')){
            $data['no_rangka'] = $request->no_rangka;
        }

        if($request->filled('no_mesin')){
            $data['no_mesin'] = $request->no_mesin;
        }

        if($request->filled('nopol')){
            $data['nopol'] = $request->nopol;
        }

        if($request->filled('no_bpkb')){
            $data['no_bpkb'] = $request->no_bpkb;
        }

        if($request->filled('keterangan')){
            $data['keterangan'] = $request->keterangan;
        }

        DataPeralatan::create($data);
        return response()->json('data peralatan berhasil ditambahkan',200);
    }

    public function show($id){
        $peralatan = DataPeralatan::find($id);
        return response()->json($peralatan);
    }

    public function update(Request $request,$id){
        $peralatan = DataPeralatan::find($id);

        $field = [
            'id_kontrak' => ['required'],
            // 'harga' => ['required'],
            'kode' => ['required'],
            'register' => ['required'],
            // 'merek' => ['required'],
            // 'spek' => ['required'],
            'bahan' => ['required'],
            'tahun' => ['required'],
            // 'pabrik' => ['required'],
            // 'no_rangka' => ['required'],
            // 'no_mesin' => ['required'],
            // 'nopol' => ['required'],
            // 'no_bpkb' => ['required'],
            'asal' => ['required']
        ];

        $pesan = [
            'id_kontrak.required' => 'Nomor kontrak tidak boleh kosong <br />',
            // 'harga.required' => 'Harga tanah tidak boleh kosong <br />',
            'kode.required' => 'Kode tidak boleh kosong <br />',
            'register.required' => 'Nomor register tidak boleh kosong <br />',
            // 'merek.required' => 'Merek tidak boleh kosong <br />',
            // 'spek.required' => 'Spek tidak boleh kosong <br />',
            'bahan.required' => 'Bahan tidak boleh kosong <br />',
            'tahun.required' => 'Tahun tidak boleh kosong <br />',
            // 'pabrik.required' => 'Pabrik tidak boleh kosong <br />',
            // 'no_rangka.required' => 'No rangka tidak boleh kosong <br />',
            // 'no_mesin.required' => 'No mesin tidak boleh kosong <br />',
            // 'nopol.required' => 'No polisi tidak boleh kosong <br />',
            // 'no_bpkb.required' => 'No BPKP tidak boleh kosong <br />',
            'asal.required' => 'Asal usul anggaran tidak boleh kosong <br />'
        ];
        $this->validate($request, $field, $pesan);

        $data = [
            'id_kontrak' => $request->id_kontrak,
            // 'harga' => $request->harga,
            'kode' => $request->kode,
            'register' => $request->register,
            // 'merek' => $request->merek,
            // 'spek' => $request->spek,
            'bahan' => $request->bahan,
            'tahun' => $request->tahun,
            // 'pabrik' => $request->pabrik,
            // 'no_rangka' => $request->no_rangka,
            // 'no_mesin' => $request->no_mesin,
            // 'nopol' => $request->nopol,
            // 'no_bpkb' => $request->no_bpkb,
            'asal' => $request->asal,
        ];

        if($request->filled('harga')){
            $data['harga'] = $request->harga;
        }
        if($request->filled('merek')){
            $data['merek'] = $request->merek;
        }

        if($request->filled('spek')){
            $data['spek'] = $request->spek;
        }

        if($request->filled('pabrik')){
            $data['pabrik'] = $request->pabrik;
        }

        if($request->filled('no_rangka')){
            $data['no_rangka'] = $request->no_rangka;
        }

        if($request->filled('no_mesin')){
            $data['no_mesin'] = $request->no_mesin;
        }

        if($request->filled('nopol')){
            $data['nopol'] = $request->nopol;
        }

        if($request->filled('no_bpkb')){
            $data['no_bpkb'] = $request->no_bpkb;
        }

        if($request->filled('keterangan')){
            $data['keterangan'] = $request->keterangan;
        }

        $peralatan->update($data);
        return response()->json('data peralatan berhasil diubah',200);
    }

    public function destroy($id){
        $peralata = DataPeralatan::find($id);
        $peralata->delete();
        return response()->json('data peralatan berhasil dihapus',204);
    }

    public function validasi($id){
        $peralatan = DataPeralatan::find($id);
        if(Auth::user()->level == 'operator' || Auth::user()->level == 'bendahara'){
            $verif = ['status' => '1'];
            $respon = 'data peralatan berhasil dikirim ke admin Aset';
        }elseif(Auth::user()->level == 'aset'){
            $datakib = [
                'id_peralatan' => $peralatan->id,
                'kode_lokasi' => $peralatan->opd
            ];
            DataKibB::create($datakib);
            $verif = ['status' => '2'];
            $respon = 'data peralatan berhasil tercatat pada KIB B';
        }
        $peralatan->update($verif);
        return response()->json($respon,200);
    }

    public function tolak($id){
        $peralatan = DataPeralatan::find($id);
        $data = [
            'status' => '0'
        ];
        $peralatan->update($data);
        return response()->json('Data peralatan berhasil ditolak',200);
    }
}
