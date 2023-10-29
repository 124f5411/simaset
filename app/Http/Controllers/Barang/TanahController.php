<?php

namespace App\Http\Controllers\Barang;

use App\Http\Controllers\Controller;
use App\Models\DataJenis;
use App\Models\DataKibA;
use App\Models\DataKontrak;
use App\Models\DataOpd;
use App\Models\DataTanah;
use App\Models\HakTanah;
use App\Models\KodeBarang;
use App\Models\MasterKib;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TanahController extends Controller
{

    public function index(){
        // $view = (Auth::user()->level == 'admin') ? 'barang.tanah.index' : 'barang.tanah.userindex';

        if(Auth::user()->level == 'admin'){
            return view('barang.tanah.index',[
                'title' => 'Barang',
                'page' => 'Tanah',
            ]);
        }else{
            $master = MasterKib::where('kib','=','A')->get();
            // $jenis = DataJenis::where('id_master','=',$master[0]->id)->get();
            $jenis = KodeBarang::where('kib','=','A')->get();
            $kontrak = DataKontrak::where('opd','=',Auth::user()->id_opd)->get();
            $hak = HakTanah::all();
            $instansi = DataOpd::all();
            return view('barang.tanah.userindex',[
                'title' => 'Barang',
                'page' => 'Tanah',
                'drops' => [
                    'jenis' => $jenis,
                    'kontrak' => $kontrak,
                    'hak' => $hak,
                    'instansi' => $instansi
                ]
            ]);
        }
    }

    public function detail($id){
        // $tanah = DataTanah::where('opd','=',decrypt($id))->get();
        $master = MasterKib::where('kib','=','A')->get();
        $jenis = DataJenis::where('id_master','=',$master[0]->id)->get();
        $kontrak = DataKontrak::where('opd','=',decrypt($id))->get();
        $hak = HakTanah::all();
        return view('barang.tanah.detail',[
            'title' => 'Barang',
            'page' => 'Tanah',
            'drops' => [
                    'jenis' => $jenis,
                    'kontrak' => $kontrak,
                    'hak' => $hak
            ],
            'kode_tanah' => $master[0]->kode
        ]);
    }

    public function data_all(){
        $tanah = DataTanah::whereIn('status',['1','2'])->get();
        return datatables()->of($tanah)
                ->addIndexColumn()
                ->addColumn('q_opd',function($tanah) {
                    return getValue("opd","data_opd","id = ".$tanah->opd);
                })
                ->addColumn('jenis', function($tanah) {
                    return getValue("uraian"," referensi_kode_barang"," kode_barang = '$tanah->kode'");
                })
                ->addColumn('hak', function($tanah) {
                    return getValue("hak","jns_hak"," id = ".$tanah->id_hak);
                })
                ->addColumn('aksi', function($tanah){

                    if($tanah->status == '1'){
                        return '
                            <div class="btn-group">
                                <a href="javascript:void(0)" onclick="verifTanah(`'.route('tanah.validasi',$tanah->id).'`)" class="btn btn-sm btn-primary" title="Validasi" ><i class="fas fa-paper-plane"></i></a>
                                <a href="javascript:void(0)" onclick="tolakTanah(`'.route('tanah.reject',$tanah->id).'`)" class="btn btn-sm btn-danger" title="Tolak"><i class="fas fa-redo"></i></a>
                            </div>
                            ';
                    }elseif($tanah->status == '2'){
                        return '
                        <div class="btn-group">
                            <a href="javascript:void(0)" onclick="editTanah(`'.route('tanah.update',$tanah->id).'`,'.$tanah->id.')" class="btn btn-sm btn-warning" title="Ubah" ><i class="fas fa-edit"></i></a>
                            <a href="javascript:void(0)" onclick="hapusTanah(`'.route('tanah.destroy',$tanah->id).'`)" class="btn btn-sm btn-danger" title="Hapus" ><i class="fas fa-trash"></i></a>
                        </div>
                        ';
                    }

                })
                ->rawColumns(['aksi'])
                ->make(true);
    }

    public function data($id){
        $tanah = DataTanah::where('opd','=',decrypt($id))->get();
        return datatables()->of($tanah)
                ->addIndexColumn()
                ->addColumn('q_opd',function($tanah) {
                    return getValue("opd","data_opd","id = ".$tanah->opd);
                })
                ->addColumn('jenis', function($tanah) {
                    return getValue("uraian","referensi_kode_barang","kode_barang = '$tanah->kode'");
                })
                ->addColumn('hak', function($tanah) {
                    return getValue("hak","jns_hak"," id = ".$tanah->id_hak);
                })
                ->addColumn('aksi', function($tanah){
                    if($tanah->status == '0'){
                        return '
                            <div class="btn-group">
                                <a href="javascript:void(0)" onclick="editTanah(`'.route('tanah.update',$tanah->id).'`,'.$tanah->id.')" class="btn btn-sm btn-warning" title="Ubah" ><i class="fas fa-edit"></i></a>
                                <a href="javascript:void(0)" onclick="hapusTanah(`'.route('tanah.destroy',$tanah->id).'`)" class="btn btn-sm btn-danger" title="Hapus"><i class="fas fa-trash"></i></a>
                                <a href="javascript:void(0)" onclick="verifTanah(`'.route('tanah.validasi',$tanah->id).'`)" class="btn btn-sm btn-primary" title="Validasi"><i class="fas fa-paper-plane"></i></a>
                            </div>
                            ';
                    }
                    if($tanah->status == '1'){
                        return 'Proccesed';
                    }
                    if($tanah->status == '2'){
                        return 'Valid';
                    }

                })
                ->rawColumns(['aksi'])
                ->make(true);
    }

    public function getRegister($kode,$id_opd){
        // dd($id_opd);
        $register = DataTanah::where([
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

    public function store(Request $request,$id){
        $field = [
            'id_jenis' => ['required'],
            'harga' => ['required'],
            'kode' => ['required'],
            'register' => ['required'],
            'luas' => ['required'],
            'tahun' => ['required'],
            'id_hak' => ['required'],
            'penggunaan' => ['required'],
            'asal' => ['required'],
            'alamat' => ['required']
        ];

        $pesan = [
            'id_jenis.required' => 'Nama atau jenis tanah tidak boleh kosong <br />',
            'id_kontrak.required' => 'Nomor kontrak tidak boleh kosong <br />',
            'tgl_sertifikat.required' => 'Tanggal sertifikat tidak boleh kosong <br />',
            'no_sertifikat.required' => 'Nomor sertifikat tidak boleh kosong <br />',
            'harga.required' => 'Harga tanah tidak boleh kosong <br />',
            'kode.required' => 'Kode tidak boleh kosong <br />',
            'register.required' => 'Nomor register tidak boleh kosong <br />',
            'luas.required' => 'Luas tanah tidak boleh kosong <br />',
            'tahun.required' => 'Tahun tidak boleh kosong <br />',
            'id_hak.required' => 'Hak tanah tidak boleh kosong <br />',
            'penggunaan.required' => 'Penggunaan tidak boleh kosong <br />',
            'asal.required' => 'Asal anggaran tidak boleh kosong <br />',
            'alamat.required' => 'Alamat atau lokasi tanah tidak boleh kosong <br />'
        ];
        $this->validate($request, $field, $pesan);
        $data = [
            'opd' => decrypt($id),
            'kode' => $request->kode,
            'register' => $request->register,
            'luas' => $request->luas,
            'tahun' => $request->tahun,
            'alamat' => $request->alamat,
            'id_hak' => $request->id_hak,
            'penggunaan' => $request->penggunaan,
            'asal' => $request->asal,
            'harga' => $request->harga,
            'keterangan' => $request->keterangan,
            'status' => '0'
        ];
        if($request->filled('id_kontrak')){
            $data['id_kontrak'] =$request->id_kontrak;
        }

        if($request->filled('tgl_sertifikat')){
            $data['tgl_sertifikat'] = $request->tgl_sertifikat;
        }

        if($request->filled('no_sertifikat')){
            $data['no_sertifikat'] = $request->no_sertifikat;
        }

        if($request->filled('keterangan')){
            $data['keterangan'] = $request->keterangan;
        }

        DataTanah::create($data);
        return response()->json('data tanah berhasil ditambahkan',200);
    }

    public function show($id){
        $tanah = DataTanah::find($id);
        // $tanah = DataTanah::selet('data_tanah.*','referensi_kode_barang.uraian')
        //                     ->join('referensi_kode_barang','data_tanah.kode','=','referensi_kode_barang.kode_barang')
        //                     ->where('data_tanah.id','=',$id);
        return response()->json($tanah);
    }

    public function update(Request $request, $id){
        $tanah = DataTanah::find($id);

        $field = [
            // 'id_jenis' => ['required'],
            'harga' => ['required'],
            // 'keterangan' => ['required'],
            'kode' => ['required'],
            'register' => ['required'],
            'luas' => ['required'],
            'tahun' => ['required'],
            'id_hak' => ['required'],
            'penggunaan' => ['required'],
            'asal' => ['required'],
            'alamat' => ['required']
        ];

        $pesan = [
            // 'id_jenis.required' => 'Nama atau jenis tanah tidak boleh kosong <br />',
            'id_kontrak.required' => 'Nomor kontrak tidak boleh kosong <br />',
            'tgl_sertifikat.required' => 'Tanggal sertifikat tidak boleh kosong <br />',
            'no_sertifikat.required' => 'Nomor sertifikat tidak boleh kosong <br />',
            'harga.required' => 'Harga tanah tidak boleh kosong <br />',
            // 'keterangan.required' => 'Keterangan tidak boleh kosong <br />',
            // 'kode.required' => 'Kode tidak boleh kosong <br />',
            // 'register.required' => 'Nomor register tidak boleh kosong <br />',
            'luas.required' => 'Luas tanah tidak boleh kosong <br />',
            'tahun.required' => 'Tahun tidak boleh kosong <br />',
            'id_hak.required' => 'Hak tanah tidak boleh kosong <br />',
            'penggunaan.required' => 'Penggunaan tidak boleh kosong <br />',
            'asal.required' => 'Asal anggaran tidak boleh kosong <br />',
            'alamat.required' => 'Alamat atau lokasi tanah tidak boleh kosong <br />'
        ];
        $this->validate($request, $field, $pesan);
        $data = [
            // 'kode' => $request->kode,
            // 'register' => $request->register,
            // 'id_jenis' => $request->id_jenis,
            'luas' => $request->luas,
            'tahun' => $request->tahun,
            'alamat' => $request->alamat,
            'id_hak' => $request->id_hak,
            'penggunaan' => $request->penggunaan,
            'asal' => $request->asal,
            'harga' => $request->harga,
            // 'keterangan' => $request->keterangan
        ];
        if($request->filled('id_kontrak')){
            $data['id_kontrak'] =$request->id_kontrak;
        }else{
            $data['id_kontrak'] = NULL;
        }

        if($request->filled('tgl_sertifikat')){
            $data['tgl_sertifikat'] = $request->tgl_sertifikat;
        }

        if($request->filled('no_sertifikat')){
            $data['no_sertifikat'] = $request->no_sertifikat;
        }

        if($request->filled('keterangan')){
            $data['keterangan'] = $request->keterangan;
        }else{
            $data['keterangan'] = NULL;
        }

        $tanah->update($data);
        return response()->json('data tanah berhasil diubah',200);
    }

    public function destroy($id){
        $tanah = DataTanah::find($id);
        $tanah->delete();
        return response()->json('data tanah berhasil dihapus',204);
    }

    public function validasi($id){
        $tanah = DataTanah::find($id);
        if(Auth::user()->level == 'operator' || Auth::user()->level == 'bendahara'){
            $verif = ['status' => '1'];
            $respon = 'data tanah berhasil dikirim ke admin Aset';
        }elseif(Auth::user()->level == 'aset'){
            $datakib = [
                'id_tanah' => $tanah->id,
                'kode_lokasi' => $tanah->opd
            ];
            DataKibA::create($datakib);
            $verif = ['status' => '2'];
            $respon = 'data tanah berhasil tercatat pada KIB A';
        }
        $tanah->update($verif);
        return response()->json($respon,200);
    }

    public function tolak($id){
        $tanah = DataTanah::find($id);
        $data = [
            'status' => '0'
        ];
        $tanah->update($data);
        return response()->json('Data tanah berhasil ditolak',200);
    }
}
