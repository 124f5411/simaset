<?php

namespace App\Http\Controllers;

use App\Models\DataKontrak;
use App\Models\DataOpd;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KontrakController extends Controller
{
    public function index(){
        if(Auth::user()->level == 'aset'){
            return view('kontrak.aset.index',[
                'title' => 'Kontrak',
                'page' => 'Kontrak'
            ]);
        }

        if(Auth::user()->level == 'operator' || Auth::user()->level == 'bendahara'){
            return view('kontrak.opd.index',[
                'title' => 'Kontrak',
                'page' => 'Kontrak'
            ]);
        }

    }

    public function data_aset(){

    }

    public function data_opd(){
        $kontrak = DataKontrak::where([
            ['opd','=',Auth::user()->id_opd]
        ]);
        return datatables()->eloquent($kontrak)
                ->addIndexColumn()
                ->addColumn('tanggal', function($kontrak) {
                    return indo_dates($kontrak->t_kontrak);
                })
                ->addColumn('rincian', function($kontrak){
                    return '
                    <a href="'.route('kontrak.rincian.index',encrypt($kontrak->id)).'" class="btn btn-success btn-icon-split" title="Lihat Rincian">
                        <span class="icon text-white-50">
                            <i class="fas fa-eye"></i>
                        </span>
                        <span class="text">Rincian</span>
                    </a>
                    ';
                })
                ->addColumn('aksi', function($kontrak){
                    return '
                    <div class="btn-group">
                        <a href="javascript:void(0)" onclick="editKontrak(`'.route('kontrak.update',$kontrak->id).'`,'.$kontrak->id.')" class="btn btn-warning" title="Ubah Kontrak" ><i class="fas fa-edit"></i></a>
                        <a href="javascript:void(0)" onclick="hapusKontrak(`'.route('kontrak.destroy',$kontrak->id).'`)" class="btn btn-danger" title="Hapus Kontrak" ><i class="fas fa-trash"></i></a>
                    </div>
                    ';
                })
                ->rawColumns(['aksi','rincian'])
                ->make(true);
    }

    public function rincian($id){
        if(Auth::user()->level == 'aset'){
            return view('kontrak.aset.rincian',[
                'title' => 'Kontrak',
                'page' => 'Kontrak'
            ]);
        }

        if(Auth::user()->level == 'operator' || Auth::user()->level == 'bendahara'){
            return view('kontrak.opd.rincian',[
                'title' => 'Kontrak',
                'page' => 'Kontrak'
            ]);
        }
    }

    public function store(Request $request){
        $field = [
            'no_kontrak' => ['required'],
            'nm_kontrak' =>  ['required'],
            'tahun' =>  ['required'],
            't_kontrak' =>  ['required'],
        ];

        $pesan = [
            'no_kontrak.required' => 'Nomor kontrak tidak boleh kosong <br />',
            'nm_kontrak.required' => 'Judul kontrak tidak boleh kosong <br />',
            'tahun.required' => 'Tahun kontrak tidak boleh kosong <br />',
            't_kontrak.required' => 'Tanggal kontrak tidak boleh kosong <br />'
        ];
        // if(Auth::user()->level == 'admin'){
        //     $field['opd'] = ['required'];
        //     $pesan['opd.required'] = 'Instansi / OPD tidak boleh kosong <br />';
        // }
        $this->validate($request, $field, $pesan);
        $data = [
            'no_kontrak' => $request->no_kontrak,
            'nm_kontrak' => $request->nm_kontrak,
            'tahun' => $request->tahun,
            't_kontrak' => $request->t_kontrak,
            'opd' => Auth::user()->id_opd
        ];
        // if(Auth::user()->level == 'admin'){
        //     $data['opd'] = $request->opd;
        // }else{
        //     $data['opd'] = Auth::user()->id_opd;
        // }
        DataKontrak::create($data);
        return response()->json('Kontrak berhasil ditambahkan',200);
    }

    public function show($id){
        $kontrak = DataKontrak::find($id);
        return response()->json($kontrak);
    }

    public function update(Request $request,$id){
        $kontrak = DataKontrak::find($id);
        $field = [
            'no_kontrak' => ['required'],
            'nm_kontrak' =>  ['required'],
            'tahun' =>  ['required'],
            't_kontrak' =>  ['required'],
        ];

        $pesan = [
            'no_kontrak.required' => 'Nomor kontrak tidak boleh kosong <br />',
            'nm_kontrak.required' => 'Judul kontrak tidak boleh kosong <br />',
            'tahun.required' => 'Tahun kontrak tidak boleh kosong <br />',
            't_kontrak.required' => 'Tanggal kontrak tidak boleh kosong <br />'
        ];

        if(Auth::user()->level == 'admin'){
            $field['opd'] = ['required'];
            $pesan['opd.required'] = 'Instansi / OPD tidak boleh kosong <br />';
        }

        $this->validate($request, $field, $pesan);

        $data = [
            'opd' => (Auth::user()->level == 'admin') ? $request->opd : Auth::user()->id_opd,
            'no_kontrak' => $request->no_kontrak,
            'nm_kontrak' => $request->nm_kontrak,
            'tahun' => $request->tahun,
            't_kontrak' => $request->t_kontrak,
        ];

        $kontrak->update($data);
        return response()->json('Kontrak berhasil dibuah',200);
    }

    public function destroy($id){
        $q = 'DELETE data_kontrak, detail_kontrak FROM data_kontrak  JOIN detail_kontrak on data_kontrak.id = detail_kontrak.id_kontrak where data_kontrak.id = ?';
        DB::delete($q,[$id]);
        return response('Kontrak berhasil dihapus', 204);
    }
}
