<?php

namespace App\Http\Controllers\Barang;

use App\Http\Controllers\Controller;
use App\Models\DataJenis;
use App\Models\DataKdp;
use App\Models\DataKibF;
use App\Models\DataKontrak;
use App\Models\DataOpd;
use App\Models\KodeBarang;
use App\Models\MasterKib;
use App\Models\StatusTanah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KdpController extends Controller
{
    public function index(){
        // $view = (Auth::user()->level == 'admin') ? 'barang.tanah.index' : 'barang.tanah.userindex';
        $master = MasterKib::where('kib','=','B')->get();
        $jenis = DataJenis::where('id_master','=',$master[0]->id)->get();
        $kontrak = DataKontrak::where('opd','=',Auth::user()->id_opd)->get();
        $kode_barang = KodeBarang::where('kib','=','F')->get();
        $status_tanah = StatusTanah::all();
        $instansi = DataOpd::all();
        return view('barang.kdp.index',[
            'title' => 'Barang',
            'page' => 'KDP',
            'drops' => [
                'jenis' => $jenis,
                'kontrak' => $kontrak,
                'instansi' => $instansi,
                'kode_barang' => $kode_barang,
                'status_tanah' => $status_tanah
            ]
        ]);

    }

    public function data(){
        if(Auth::user()->level == 'aset'){
            $kdp = DataKdp::whereIn('status',['1','2'])->get();
        }elseif((Auth::user()->level == 'operator' || Auth::user()->level == 'bendahara')){
            $kdp = DataKdp::where('opd','=',Auth::user()->id_opd)->get();
        }

        // dd($kdp);

        return datatables()->of($kdp)
                ->addIndexColumn()
                ->addColumn('q_opd',function($kdp) {
                    return getValue("opd","data_opd","id = ".$kdp->opd);
                })
                ->addColumn('nm_barang', function($kdp){
                    return getValue("uraian","referensi_kode_barang","kode_barang = '$kdp->kode'");
                })
                ->addColumn('status_tanah', function($kdp){
                    return (!empty($kdp->id_status_tanah)) ? getValue("status","status_tanah"," id = ".$kdp->id_status_tanah) : "";
                })
                ->addColumn('tgl_dokumen', function($kdp){
                    return (!empty($kdp->tgl_dokumen)) ? indo_dates($kdp->tgl_dokumen) : "";
                })
                ->addColumn('t_mulai',function($kdp){
                    return (!empty($kdp->t_mulai)) ? indo_dates($kdp->t_mulai) : "";
                })
                ->addColumn('beton', function($kdp){
                    return ($kdp->beton) ? "Ya" : "tidak";
                })
                ->addColumn('tingkat', function($kdp){
                    return ($kdp->tingkat) ? "Ya" : "tidak";
                })
                ->addColumn('aksi', function($kdp){
                    if(Auth::user()->level == 'aset'){
                        if($kdp->status == '1'){
                            $aksi = '
                            <div class="btn-group">
                            <a href="javascript:void(0)" onclick="verifKdp(`'.route('kdp.validasi',$kdp->id).'`)" class="btn btn-sm btn-primary" title="Validasi"><i class="fas fa-paper-plane"></i></a>
                            <a href="javascript:void(0)" onclick="tolakKdp(`'.route('kdp.reject',$kdp->id).'`)" class="btn btn-sm btn-danger" title="Tolak"><i class="fas fa-redo"></i></a>
                            </div>
                            ';
                        }
                        if($kdp->status == '2'){
                            $aksi = '
                            <div class="btn-group">
                                <a href="javascript:void(0)" onclick="editKdp(`'.route('kdp.update',$kdp->id).'`,'.$kdp->id.')" class="btn btn-sm btn-warning" title="Ubah" ><i class="fas fa-edit"></i></a>
                                <a href="javascript:void(0)" onclick="hapusKdp(`'.route('kdp.destroy',$kdp->id).'`)" class="btn btn-sm btn-danger" title="Hapus"><i class="fas fa-trash"></i></a>
                            </div>
                            ';
                        }
                    }
                    if((Auth::user()->level == 'operator' || Auth::user()->level == 'bendahara')){
                        if($kdp->status == '0'){
                            $aksi = '
                            <div class="btn-group">
                                <a href="javascript:void(0)" onclick="editKdp(`'.route('kdp.update',$kdp->id).'`,'.$kdp->id.')" class="btn btn-sm btn-warning" title="Ubah" ><i class="fas fa-edit"></i></a>
                                <a href="javascript:void(0)" onclick="hapusKdp(`'.route('kdp.destroy',$kdp->id).'`)" class="btn btn-sm btn-danger" title="Hapus"><i class="fas fa-trash"></i></a>
                                <a href="javascript:void(0)" onclick="verifKdp(`'.route('kdp.validasi',$kdp->id).'`)" class="btn btn-sm btn-primary" title="Validasi"><i class="fas fa-paper-plane"></i></a>
                            </div>
                            ';
                        }
                        if($kdp->status == '1'){
                            $aksi = 'Proccesed';
                        }

                        if($kdp->status == '2'){
                            $aksi = 'Valid';
                        }
                    }
                    return $aksi;

                })
                ->rawColumns(['aksi'])
                ->make(true);
    }

    public function getRegister($kode,$id_opd){
        $register = DataKdp::where([
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
            'harga' => ['required'],
            'kode' => ['required'],
            'register' => ['required'],
            'tingkat' => ['required'],
            'beton' => ['required'],
            'tgl_dokumen' => ['required'],
            'no_dokumen' => ['required'],
            'asal' => ['required'],
            't_mulai' => ['required'],
            'harga' => ['required']
        ];

        $pesan = [
            'id_kontrak.required' => 'Nomor kontrak tidak boleh kosong <br />',
            'harga.required' => 'Harga tanah tidak boleh kosong <br />',
            'kode.required' => 'Kode tidak boleh kosong <br />',
            'register.required' => 'Nomor register tidak boleh kosong <br />',
            'tingkat.required' => 'Bertingkat harus dipilih <br />',
            'beton.required' => 'Beton harus dipilih <br />',
            'tgl_dokumen.required' => 'Tanggal dokumen tidak boleh kosong <br />',
            'no_dokumen.required' => 'Nomor dokumen tidak boleh kosong <br />',
            't_mulai.required' => 'Tanggal Mulai tidak boleh kosong <br />',
            'harga.required' => 'Nilai kontrak tidak boleh kosong <br />',
            'asal.required' => 'Asal usul anggaran tidak boleh kosong <br />'
        ];
        $this->validate($request, $field, $pesan);
        $data = [
            'opd' => Auth::user()->id_opd,
            'id_kontrak' => $request->id_kontrak,
            'harga' => $request->harga,
            'kode' => $request->kode,
            'register' => $request->register,
            'tingkat' => $request->tingkat,
            'beton' => $request->beton,
            'tgl_dokumen' => $request->tgl_dokumen,
            'no_dokumen' => $request->no_dokumen,
            't_mulai' => $request->t_mulai,
            'asal' => $request->asal,
            'status' => '0'
        ];

        if($request->filled('alamat')){
            $data['alamat'] = $request->alamat;
        }
        if($request->filled('luas')){
            $data['luas'] = $request->luas;
        }

        if($request->filled('id_status_tanah')){
            $data['id_status_tanah'] = $request->id_status_tanah;
        }

        if($request->filled('alamat')){
            $data['alamat'] = $request->alamat;
        }

        if($request->filled('bangunan')){
            $data['bangunan'] = $request->bangunan;
        }

        if($request->filled('kode_tanah')){
            $data['kode_tanah'] = $request->kode_tanah;
        }

        if($request->filled('keterangan')){
            $data['keterangan'] = $request->keterangan;
        }

        DataKdp::create($data);
        return response()->json('Data konstruksi dalam perkerjaan berhasil ditambahkan',200);
    }

    public function getKontrak($id,$id_opd){
        $kontrak = DataKontrak::where('id','=',$id)->where('opd','=',decrypt($id_opd))->get();
        return response()->json($kontrak);
    }

    public function show($id){
        $kdp = DataKdp::find($id);
        return response()->json($kdp);
    }

    public function update(Request $request,$id){
        $kdp = DataKdp::find($id);
        $field = [
            'id_kontrak' => ['required'],
            'harga' => ['required'],
            'kode' => ['required'],
            'register' => ['required'],
            'tingkat' => ['required'],
            'beton' => ['required'],
            'tgl_dokumen' => ['required'],
            'no_dokumen' => ['required'],
            'asal' => ['required'],
            't_mulai' => ['required'],
            'harga' => ['required']
        ];

        $pesan = [
            'id_kontrak.required' => 'Nomor kontrak tidak boleh kosong <br />',
            'harga.required' => 'Harga tanah tidak boleh kosong <br />',
            'kode.required' => 'Kode tidak boleh kosong <br />',
            'register.required' => 'Nomor register tidak boleh kosong <br />',
            'tingkat.required' => 'Bertingkat harus dipilih <br />',
            'beton.required' => 'Beton harus dipilih <br />',
            'tgl_dokumen.required' => 'Tanggal dokumen tidak boleh kosong <br />',
            'no_dokumen.required' => 'Nomor dokumen tidak boleh kosong <br />',
            't_mulai.required' => 'Tanggal Mulai tidak boleh kosong <br />',
            'harga.required' => 'Nilai kontrak tidak boleh kosong <br />',
            'asal.required' => 'Asal usul anggaran tidak boleh kosong <br />'
        ];
        $this->validate($request, $field, $pesan);
        $data = [
            'opd' => Auth::user()->id_opd,
            'id_kontrak' => $request->id_kontrak,
            'harga' => $request->harga,
            'kode' => $request->kode,
            'register' => $request->register,
            'tingkat' => $request->tingkat,
            'beton' => $request->beton,
            'tgl_dokumen' => $request->tgl_dokumen,
            'no_dokumen' => $request->no_dokumen,
            't_mulai' => $request->t_mulai,
            'asal' => $request->asal,
            'status' => '0'
        ];

        if($request->filled('alamat')){
            $data['alamat'] = $request->alamat;
        }
        if($request->filled('luas')){
            $data['luas'] = $request->luas;
        }

        if($request->filled('id_status_tanah')){
            $data['id_status_tanah'] = $request->id_status_tanah;
        }

        if($request->filled('alamat')){
            $data['alamat'] = $request->alamat;
        }

        if($request->filled('bangunan')){
            $data['bangunan'] = $request->bangunan;
        }

        if($request->filled('kode_tanah')){
            $data['kode_tanah'] = $request->kode_tanah;
        }

        if($request->filled('keterangan')){
            $data['keterangan'] = $request->keterangan;
        }

        $kdp->update($data);
        return response()->json('Data konstruksi dalam perkerjaan berhasil diubah',200);
    }

    public function destroy($id){
        $kdp = DataKdp::find($id);
        $kdp->delete();
        return response()->json('Data konstruksi dalam perkerjaan berhasil dihapus',204);
    }

    public function validasi($id){
        $kdp = DataKdp::find($id);
        if(Auth::user()->level == 'operator' || Auth::user()->level == 'bendahara'){
            $verif = ['status' => '1'];
            $respon = 'data peralatan berhasil dikirim ke admin Aset';
        }elseif(Auth::user()->level == 'aset'){
            $datakib = [
                'id_konstruksi' => $kdp->id,
                'kode_lokasi' => $kdp->opd
            ];
            DataKibF::create($datakib);
            $verif = ['status' => '2'];
            $respon = 'Data konstruksi dalam perkerjaan berhasil tercatat pada KIB F';
        }
        $kdp->update($verif);
        return response()->json($respon,200);
    }

    public function tolak($id){
        $kdp = DataKdp::find($id);
        $data = [
            'status' => '0'
        ];
        $kdp->update($data);
        return response()->json('Data konstruksi dalam perkerjaan berhasil ditolak',200);
    }
}
