<?php

namespace App\Http\Controllers\Import;

use App\Http\Controllers\Controller;
use App\Imports\KodeBarangKontrakImport;
use App\Models\KodeBarangKontrak;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel as FacadesExcel;

class KodeBarangKontrakController extends Controller
{
    public function index(){
        return view('import.kontrak',[
            'title' => 'Import',
            'page' => 'Kode Barang Kontrak'
        ]);
    }

    public function list(){
        $kelompok = KodeBarangKontrak::select('kode','nama')->whereRaw('length(kode) = 3')->orderBy('kode','ASC')->get();
        return view('referensi.kodeBarangKontrak',[
            'title' => 'Referensi',
            'page' => 'Kode Barang Kontrak',
            'dropdown' => [
                'kelompok' => $kelompok
            ],
        ]);
    }

    public function dropdownKelompok(){
        $kelompok = KodeBarangKontrak::select('kode','nama')->whereRaw('length(kode) = 3')->orderBy('kode','ASC')->get();
        return response()->json($kelompok);
    }

    protected function getData(){
        $kode = KodeBarangKontrak::whereRaw('length(kode) = 18')->orderBy('kode','ASC');
        return $kode;
    }

    public function data(){
        $kode = $this->getData();
        return datatables()->eloquent($kode)
                ->addIndexColumn()
                ->addColumn('aksi', function($kode){
                    return '
                    <div class="btn-group">
                    <a href="javascript:void(0)" onclick="editKode(`'.route('kode.barang.kontrak.update',$kode->id).'`,'.$kode->id.',`semua`)" class="btn btn-sm btn-warning" ><i class="fas fa-edit"></i></a>
                    <a href="javascript:void(0)" onclick="hapusKode(`'.route('kode.barang.kontrak.destroy',$kode->id).'`)" class="btn btn-sm btn-danger" ><i class="fas fa-trash"></i></i></a>
                    </div>
                    ';
                })
                ->rawColumns(['aksi'])
                ->make(true);
    }

    public function getKelompok(){
        $kode = KodeBarangKontrak::whereRaw('length(kode) = 3')->orderBy('kode','ASC');
        return datatables()->eloquent($kode)
                    ->addIndexColumn()
                    ->addColumn('aksi', function($kode){
                        return '
                        <div class="btn-group">
                        <a href="javascript:void(0)" onclick="editKode(`'.route('kode.barang.kontrak.update',$kode->id).'`,'.$kode->id.',`kelompok`)" class="btn btn-sm btn-warning" ><i class="fas fa-edit"></i></a>
                        <a href="javascript:void(0)" onclick="hapusKelompok(`'.route('kode.barang.kontrak.destroy',$kode->id).'`)" class="btn btn-sm btn-danger" ><i class="fas fa-trash"></i></i></a>
                        </div>
                        ';
                    })
                    ->rawColumns(['aksi'])
                    ->make(true);
    }

    public function getJenis(){
        $kode = KodeBarangKontrak::whereRaw('length(kode) = 5')->orderBy('kode','ASC');
        return datatables()->eloquent($kode)
                    ->addIndexColumn()
                    ->addColumn('aksi', function($kode){
                        return '
                        <div class="btn-group">
                        <a href="javascript:void(0)" onclick="editKode(`'.route('kode.barang.kontrak.update',$kode->id).'`,'.$kode->id.',`jenis`)" class="btn btn-sm btn-warning" ><i class="fas fa-edit"></i></a>
                        <a href="javascript:void(0)" onclick="hapusJenis(`'.route('kode.barang.kontrak.destroy',$kode->id).'`)" class="btn btn-sm btn-danger" ><i class="fas fa-trash"></i></i></a>
                        </div>
                        ';
                    })
                    ->rawColumns(['aksi'])
                    ->make(true);
    }

    public function getObjek(){
        $kode = KodeBarangKontrak::whereRaw('length(kode) = 8')->orderBy('kode','ASC');
        return datatables()->eloquent($kode)
                    ->addIndexColumn()
                    ->addColumn('aksi', function($kode){
                        return '
                        <div class="btn-group">
                            <a href="javascript:void(0)" onclick="editKode(`'.route('kode.barang.kontrak.update',$kode->id).'`,'.$kode->id.',`objek`)" class="btn btn-sm btn-warning" ><i class="fas fa-edit"></i></a>
                            <a href="javascript:void(0)" onclick="hapusObjek(`'.route('kode.barang.kontrak.destroy',$kode->id).'`)" class="btn btn-sm btn-danger" ><i class="fas fa-trash"></i></i></a>
                        </div>
                        ';
                    })
                    ->rawColumns(['aksi'])
                    ->make(true);
    }

    public function getRincian(){
        $kode = KodeBarangKontrak::whereRaw('length(kode) = 11')->orderBy('kode','ASC');
        return datatables()->eloquent($kode)
                    ->addIndexColumn()
                    ->addColumn('aksi', function($kode){
                        return '
                        <div class="btn-group">
                        <a href="javascript:void(0)" onclick="editKode(`'.route('kode.barang.kontrak.update',$kode->id).'`,'.$kode->id.',`rincian`)" class="btn btn-sm btn-warning" ><i class="fas fa-edit"></i></a>
                        <a href="javascript:void(0)" onclick="hapusRincian(`'.route('kode.barang.kontrak.destroy',$kode->id).'`)" class="btn btn-sm btn-danger" ><i class="fas fa-trash"></i></i></a>
                        </div>
                        ';
                    })
                    ->rawColumns(['aksi'])
                    ->make(true);
    }

    public function getSubRincian(){
        $kode = KodeBarangKontrak::whereRaw('length(kode) = 14')->orderBy('kode','ASC');
        return datatables()->eloquent($kode)
                    ->addIndexColumn()
                    ->addColumn('aksi', function($kode){
                        return '
                        <div class="btn-group">
                        <a href="javascript:void(0)" onclick="editKode(`'.route('kode.barang.kontrak.update',$kode->id).'`,'.$kode->id.',`subRincian`)" class="btn btn-sm btn-warning" ><i class="fas fa-edit"></i></a>
                        <a href="javascript:void(0)" onclick="hapusSubRincian(`'.route('kode.barang.kontrak.destroy',$kode->id).'`)" class="btn btn-sm btn-danger" ><i class="fas fa-trash"></i></i></a>
                        </div>
                        ';
                    })
                    ->rawColumns(['aksi'])
                    ->make(true);
    }

    public function show($id){
        $kode = KodeBarangKontrak::find($id);
        return response()->json($kode);
    }

    public function store(Request $request){
        $field = [
            'kode' => ['required'],
            'nama' =>  ['required'],
        ];

        $pesan = [
            'kode.required' => 'Kode barang tidak boleh kosong <br />',
            'nama.required' => 'Uraian tidak boleh kosong <br />'
        ];

        $this->validate($request, $field, $pesan);
        $data = [
            'kode' => $request->kode,
            'nama' => $request->nama
        ];

        KodeBarangKontrak::create($data);
        return response()->json('Kode berhasil ditambahkan',200);
    }

    public function update(Request $request, $id){
        $kode = KodeBarangKontrak::find($id);
        $field = [
            'nama' =>  ['required'],
        ];

        $pesan = [
            'nama.required' => 'Uraian tidak boleh kosong <br />'
        ];

        $this->validate($request, $field, $pesan);
        $data = [
            'nama' => $request->nama
        ];
        $kode->update($data);
        return response()->json('Kode berhasil diubah',200);
    }

    public function destroy($id){
        $kode = KodeBarangKontrak::find($id);
        $kode->delete();
        return response('Kode berhasil dihapus', 204);
    }

    public function import(Request $request){
        FacadesExcel::import(new KodeBarangKontrakImport,$request->file('dok_kode'));

    return redirect()->route('import.kontrak.index')->with('message', 'Berhasil import!');

    }
}
