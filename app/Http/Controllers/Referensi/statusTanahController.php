<?php

namespace App\Http\Controllers\Referensi;

use App\Http\Controllers\Controller;
use App\Models\StatusTanah;
use Illuminate\Http\Request;

class statusTanahController extends Controller
{
    public function index(){
        return view('referensi.status',[
            'title' => 'Referensi',
            'page' => 'Status'
        ]);
    }

    public function data(){
        $status = StatusTanah::all();
        return datatables()->of($status)
        ->addIndexColumn()
                ->addColumn('aksi', function($status){
                    return '
                    <div class="btn-group">
                        <a href="javascript:void(0)" onclick="editStatus(`'.route('status_tanah.update',$status->id).'`,'.$status->id.')" class="btn btn-warning" ><i class="fas fa-edit"></i></a>
                        <a href="javascript:void(0)" onclick="hapusStatus(`'.route('status_tanah.destroy',$status->id).'`)" class="btn btn-danger" ><i class="fas fa-trash"></i></i></a>
                    </div>
                    ';
                })
                ->rawColumns(['aksi'])
                ->make(true);
    }

    public function store(Request $request){
        $field = [
            'status' => ['required']
        ];

        $pesan = [
            'status.required' => 'Status tanah tidak boleh kosong <br />'
        ];
        $this->validate($request, $field, $pesan);


        StatusTanah::create($request->all());
        return response()->json('status tanah berhasil ditambahkan',200);
    }

    public function show($id){
        $status = StatusTanah::find($id);
        return response()->json($status);

    }

    public function update(Request $request, $id){
        $status = StatusTanah::find($id);
        $field = [
            'status' => ['required']
        ];

        $pesan = [
            'status.required' => 'Status tanah tidak boleh kosong <br />'
        ];
        $this->validate($request, $field, $pesan);
        $status->update($request->all());
        return response()->json('status tanah berhasil diubah',200);
    }

    public function destroy($id){
        $status = StatusTanah::find($id);
        $status->delete();
        return response('status tanah berhasil dihapus', 204);
    }
}
