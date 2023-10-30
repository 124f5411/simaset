<?php

namespace App\Http\Controllers\Referensi;

use App\Http\Controllers\Controller;
use App\Imports\RekeningBelanjaImport;
use App\Models\RekeningBelanja;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel as FacadesExcel;

class RekeningBelanjaController extends Controller
{
    public function index(){
        return view('referensi.rekeningBelanja',[
            'title' => 'Referensi',
            'page' => 'Rekening',
        ]);
    }

    public function data(){
        $rekening = RekeningBelanja::query();
                // ->select('referensi_kode_barang.*','_kelompok_ssh.id as id_kelompok')
                // ->join('_kelompok_ssh','referensi_kode_barang.kelompok','=','_kelompok_ssh.id');
        return datatables()->of($rekening)
            ->addIndexColumn()
            ->addColumn('aksi', function($rekening){
                return '
                <div class="btn-group">
                    <a href="javascript:void(0)" onclick="editRekening(`'.route('rekening_belanja.update',$rekening->id).'`,'.$rekening->id.')" class="btn btn-warning" ><i class="fas fa-edit"></i></a>
                    <a href="javascript:void(0)" onclick="hapusRekening(`'.route('rekening_belanja.destroy',$rekening->id).'`)" class="btn btn-danger" ><i class="fas fa-trash"></i></i></a>
                </div>
                ';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function show($id){
        $rekening = RekeningBelanja::find($id);
        return response()->json($rekening);
    }

    public function store(Request $request){
        $field = [
            'kode_akun' => ['required'],
            'nm_akun' => ['required']
        ];

        $pesan = [
            'kode_akun.required' => 'Kode Akun tidak boleh kosong <br />',
            'nm_akun.required' => 'Nama akun tidak boleh kosong <br />'
        ];
        $this->validate($request, $field, $pesan);

        $data = [
            'kode_akun' => $request->kode_akun,
            'nm_akun' => $request->uraian
        ];

        RekeningBelanja::create($data);
        return response()->json('Rekening belanja berhasil ditambahkan',200);
    }

    public function update(Request $request,$id){
        $rekening = RekeningBelanja::find($id);
        $field = [
            'kode_akun' => ['required'],
            'nm_akun' => ['required']
        ];

        $pesan = [
            'kode_akun.required' => 'Kode Akun tidak boleh kosong <br />',
            'nm_akun.required' => 'Nama akun tidak boleh kosong <br />'
        ];
        $this->validate($request, $field, $pesan);

        $data = [
            'kode_akun' => $request->kode_akun,
            'nm_akun' => $request->uraian
        ];

        $rekening->update($data);
        return response()->json('Rekening belanja berhasil diubah',200);
    }

    public function destroy($id){
        $rekening = RekeningBelanja::find($id);
        $rekening->delete();
        return response()->json('Rekening belanja berhasil dihapus',204);
    }

    public function import(Request $request){
        FacadesExcel::import(new RekeningBelanjaImport,$request->file('dok_rekening'));

        return response()->json('Rekening belanja berhasil diimport', 200);

    }
}
