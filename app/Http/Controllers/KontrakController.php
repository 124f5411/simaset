<?php

namespace App\Http\Controllers;

use App\Models\DataKontrak;
use App\Models\DataOpd;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KontrakController extends Controller
{
    public function index(){
        $instansi = DataOpd::all();
        return view('kontrak.index',[
            'title' => 'Kontrak',
            'page' => 'Kontrak',
            'drops' => $instansi
        ]);
    }

    public function data(){
        if(Auth::user()->level == 'admin'){
            $kontrak = DataKontrak::all();
        }else{
            $kontrak = DataKontrak::where([
                ['opd','=',Auth::user()->id_opd]
            ])->get();
        }
        return datatables()->of($kontrak)
                ->addIndexColumn()
                ->addColumn('tanggal', function($kontrak) {
                    return indo_date($kontrak->t_kontrak);
                })
                ->addColumn('instansi',function($kontrak) {
                    return getValue("opd"," data_opd","id = ".$kontrak->opd);
                })
                ->addColumn('aksi', function($kontrak){
                    return '
                    <div class="btn-group">
                        <a href="javascript:void(0)" onclick="editKontrak(`'.route('kontrak.update',$kontrak->id).'`,'.$kontrak->id.')" class="btn btn-warning" ><i class="fas fa-edit"></i></a>
                        <a href="javascript:void(0)" onclick="hapusKontrak(`'.route('kontrak.destroy',$kontrak->id).'`)" class="btn btn-danger" ><i class="fas fa-trash"></i></i></a>
                    </div>
                    ';
                })
                ->rawColumns(['aksi'])
                ->make(true);

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
        if(Auth::user()->level == 'admin'){
            $field['opd'] = ['required'];
            $pesan['opd.required'] = 'Instansi / OPD tidak boleh kosong <br />';
        }
        $this->validate($request, $field, $pesan);
        $data = [
            'no_kontrak' => $request->no_kontrak,
            'nm_kontrak' => $request->nm_kontrak,
            'tahun' => $request->tahun,
            't_kontrak' => $request->t_kontrak,
        ];
        if(Auth::user()->level == 'admin'){
            $data['opd'] = $request->opd;
        }else{
            $data['opd'] = Auth::user()->id_opd;
        }
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
        $kontrak = DataKontrak::find($id);
        $kontrak->delete();
        return response('Kontrak berhasil dihapus', 204);
    }
}
