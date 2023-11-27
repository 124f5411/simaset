<?php

namespace App\Http\Controllers;

use App\Models\Penyedia;
use Illuminate\Http\Request;

class PenyediaController extends Controller
{
    public function index(){
        return view('penyedia.index',[
            'title' => 'Penyedia',
            'page' => ''
        ]);
    }

    public function data(){
        $penyedia = Penyedia::query();
        return datatables()->eloquent($penyedia)
                ->addIndexColumn()
                ->addColumn('aksi', function($penyedia){
                    return '
                    <div class="btn-group">
                        <a href="javascript:void(0)" onclick="editPenyedia(`'.route('penyedia.update',$penyedia->id).'`,'.$penyedia->id.')" class="btn btn-sm btn-warning" ><i class="fas fa-edit"></i></a>
                        <a href="javascript:void(0)" onclick="hapusPenyedia(`'.route('penyedia.destroy',$penyedia->id).'`)" class="btn btn-sm btn-danger" ><i class="fas fa-trash"></i></i></a>
                    </div>
                    ';
                })
                ->rawColumns(['aksi'])
                ->make(true);
    }

    public function store(Request $request){
        $field = [
            'nm_penyedia' => ['required'],
            'pimpinan' => ['required'],
            'telp' => ['required', 'numeric', 'digits_between:11,12'],
            'alamat' => ['required'],
            'email' => ['required', 'email:dns', 'unique:penyedia'],
        ];

        $pesan = [
            'nm_penyedia.required' => 'Nama penyedia tidak boleh kosong <br />',
            'pimpinan.required' => 'Nama pimpinan penyedia tidak boleh kosong <br />',
            'telp.required' => 'Telp penyedia tidak boleh kosong <br />',
            'telp.numeric' => 'Telp penyedia harus berupa angka <br />',
            'telp.digits_between' => 'Telp penyedia harus 11 atau 12 digit <br />',
            'alamat.required' => 'Alamat penyedia tidak boleh kosong <br />',
            'email.required' => 'Email penyedia tidak boleh kosong <br />',
            'email.email' => 'Email penyedia harus valid <br />',
            'email.unique' => 'Email penyedia telah terdaftar <br />',
        ];
        $this->validate($request, $field, $pesan);

        Penyedia::create($request->all());
        return response()->json('Data penyedia berhasil ditambahkan',200);
    }

    public function show($id){
        $penyedia = Penyedia::find($id);
        return response()->json($penyedia);
    }

    public function update(Request $request, $id){
        $penyedia = Penyedia::find($id);

        $field = [
            'nm_penyedia' => ['required'],
            'pimpinan' => ['required'],
            'telp' => ['required', 'numeric', 'digits_between:11,12'],
            'alamat' => ['required'],
            'email' => ['required', 'email:dns', 'unique:penyedia,email,'.$id],
        ];

        $pesan = [
            'nm_penyedia.required' => 'Nama penyedia tidak boleh kosong <br />',
            'pimpinan.required' => 'Nama pimpinan penyedia tidak boleh kosong <br />',
            'telp.required' => 'Telp penyedia tidak boleh kosong <br />',
            'telp.numeric' => 'Telp penyedia harus berupa angka <br />',
            'telp.digits_between' => 'Telp penyedia harus 11 atau 12 digit <br />',
            'alamat.required' => 'Alamat penyedia tidak boleh kosong <br />',
            'email.required' => 'Email penyedia tidak boleh kosong <br />',
            'email.email' => 'Email penyedia harus valid <br />',
            'email.unique' => 'Email penyedia telah terdaftar <br />',
        ];
        $this->validate($request, $field, $pesan);
        $penyedia->update($request->all());
        return response()->json('Data penyedia berhasil diubah',200);
    }

    public function destroy($id){
        $penyedia = Penyedia::find($id);
        $penyedia->delete();
        return response('Data penyedia berhasil dihapus', 204);
    }
}
