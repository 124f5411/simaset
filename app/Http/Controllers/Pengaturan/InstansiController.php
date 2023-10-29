<?php

namespace App\Http\Controllers\Pengaturan;

use App\Http\Controllers\Controller;
use App\Models\DataOpd;
use Illuminate\Http\Request;

class InstansiController extends Controller
{
    public function index(){
        $instansi = DataOpd::all();
        return view('pengaturan.instansi',[
            'title' => 'Pengaturan',
            'page' => 'Instansi',
            'drops' => $instansi
        ]);
    }

    public function data(){
        $instansi = DataOpd::all();
        return datatables()->of($instansi)
                ->addIndexColumn()
                ->addColumn('parent', function($instansi){
                    $instansi =  (!is_null($instansi->parent)) ? getValue("opd"," data_opd", "id = ".$instansi->parent):"";
                    return $instansi;
                })
                ->addColumn('aksi', function($instansi){
                    return '
                            <div class="btn-group">
                                <a href="javascript:void(0)" onclick="editInstansi(`'.route('instansi.update',$instansi->id).'`,'.$instansi->id.')" class="btn btn-warning" ><i class="fas fa-edit"></i></a>
                                <a href="javascript:void(0)" onclick="hapusInstansi(`'.route('instansi.destroy',$instansi->id).'`)" class="btn btn-danger" ><i class="fas fa-trash"></i></i></a>
                            </div>
                            ';
                })
                ->addColumn('detail', function($instansi){
                    return '
                            <div class="btn-group">
                                <a href="'.route('tanah.detail',encrypt($instansi->id)).'"  class="btn btn-success btn-icon-split float-right" >
                                    <span class="icon text-white-50">
                                        <i class="fas fa-info-circle"></i>
                                    </span>
                                    <span class="text">Detail</span>
                                </a>
                            </div>
                            ';
                })
                ->rawColumns(['aksi','detail'])
                ->make(true);

    }

    public function store(Request $request){
        $field = [
            'opd' => ['required']
        ];

        $pesan = [
            'opd.required' => 'Nama instansi tidak boleh kosong <br />',
        ];
        $this->validate($request, $field, $pesan);

        $data = [
            'opd' => $request->opd
        ];
        if($request->filled('parent')){
            $data['parent'] = $request->parent;
        }else{
            $data['parent'] = NULL;
        }

        DataOpd::create($data);
        return response()->json('data instansi berhasil ditambahkan',200);
    }

    public function show($id){
        $instansi = DataOpd::find($id);
        return response()->json($instansi);
    }

    public function update(Request $request, $id){
        $instansi = DataOpd::find($id);
        $field = [
            'opd' => ['required']
        ];

        $pesan = [
            'opd.required' => 'Nama instansi tidak boleh kosong <br />',
        ];
        $this->validate($request, $field, $pesan);

        $data = [
            'opd' => $request->opd
        ];
        if($request->filled('parent')){
            $data['parent'] = $request->parent;
        }else{
            $data['parent'] = NULL;
        }
        $instansi->update($data);
        return response()->json('data instansi berhasil diubah',200);
    }

    public function destroy($id){
        $instansi = DataOpd::find($id);
        $instansi->delete();
        return response('data instansi berhasil dihapus', 204);
    }
}
