<?php

namespace App\Http\Controllers\Referensi;

use App\Http\Controllers\Controller;
use App\Imports\KodeBarangImport;
use App\Models\KelompokSsh;
use App\Models\KodeBarang;
use App\Models\MasterKib;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Facades\Excel as FacadesExcel;
use Yajra\DataTables\DataTables;

class KodeBarangController extends Controller
{
    public function index(){
        $master = MasterKib::all();
        $kelompok = KelompokSsh::all();
        return view('referensi.kodeBarang',[
            'title' => 'Referensi',
            'page' => 'Kode',
            'drops' => [
                'master' => $master,
                'kelompok' => $kelompok
            ]
        ]);
    }

    public function data(){
        $kode = KodeBarang::query();
                // ->select('referensi_kode_barang.*','_kelompok_ssh.id as id_kelompok')
                // ->join('_kelompok_ssh','referensi_kode_barang.kelompok','=','_kelompok_ssh.id');
        return datatables()->of($kode)
            ->addIndexColumn()
            ->addColumn('aksi', function($kode){
                return '
                <div class="btn-group">
                    <a href="javascript:void(0)" onclick="editKode(`'.route('kode_barang.update',$kode->id).'`,'.$kode->id.')" class="btn btn-warning" ><i class="fas fa-edit"></i></a>
                    <a href="javascript:void(0)" onclick="hapusKode(`'.route('kode_barang.destroy',$kode->id).'`)" class="btn btn-danger" ><i class="fas fa-trash"></i></i></a>
                </div>
                ';
            })
            ->rawColumns(['aksi','nm_kelompok'])
            ->make(true);
    }

    public function show($id){
        $kode = KodeBarang::find($id);
        return response()->json($kode);
    }

    public function store(Request $request){
        $field = [
            'kode_barang' => ['required'],
            'uraian' => ['required'],
            'kelompok' => ['required']
        ];

        $pesan = [
            'kode_barang.required' => 'Kode Barang tidak boleh kosong <br />',
            'uraian.required' => 'Uraian tidak boleh kosong <br />',
            'kelompok.required' => 'Mohon pilih kelompok barang <br />'
        ];
        $this->validate($request, $field, $pesan);

        $data = [
            'kode_barang' => $request->kode_barang,
            'uraian' => $request->uraian,
            'kelompok' => $request->kelompok
        ];

        if($request->filled('kib')){
            $data['kib'] = $request->kib;
        }

        KodeBarang::create($data);
        return response()->json('kode barang berhasil ditambahkan',200);
    }

    public function update(Request $request,$id){
        $kode = KodeBarang::find($id);
        $field = [
            'kode_barang' => ['required'],
            'uraian' => ['required'],
            'kelompok' => ['required']
        ];

        $pesan = [
            'kode_barang.required' => 'Kode Barang tidak boleh kosong <br />',
            'uraian.required' => 'Uraian tidak boleh kosong <br />',
            'kelompok.required' => 'Mohon pilih kelompok barang <br />'
        ];
        $this->validate($request, $field, $pesan);

        $data = [
            'kode_barang' => $request->kode_barang,
            'uraian' => $request->uraian,
            'kelompok' => $request->kelompok
        ];

        if($request->filled('kib')){
            $data['kib'] = $request->kib;
        }

        $kode->update($data);
        return response()->json('kode barang berhasil diubah',200);
    }

    public function destroy($id){
        $kode = KodeBarang::find($id);
        $kode->delete();
        return response()->json('kode barang berhasil dihapus',204);
    }

    public function import(Request $request){

        // $data = $request->file('dok_kode');
        // $namafile = $data->getClientOriginalName();
        // $data->move(public_path('KodeBarang'),$namafile);
        // Excel::import(new KodeBarangImport,$request->file('dok_kode'));
        FacadesExcel::import(new KodeBarangImport,$request->file('dok_kode'));

        return response()->json('Kode barang berhasil diimport', 200);

    }
}
