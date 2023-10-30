<?php

namespace App\Http\Controllers\Pengaturan;

use App\Http\Controllers\Controller;
use App\Models\TtdSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TtdController extends Controller
{
    public function index(){
        $ttd = TtdSetting::where('id_opd','=',Auth::user()->id_opd);
        // if($ttd->count() < 1){
        //     $data = $ttd->count();
        // }else{
        //     $data = $ttd;
        // }
        $data = $ttd;
        return view('pengaturan.pb',[
            'title' => 'Pengaturan',
            'page' => 'PB',
            'data' => $data,
        ]);
    }

    public function show($id){
        $ttd = TtdSetting::where('id_opd','=',$id)->get();
        return response()->json($ttd);
    }

    public function store(Request $request){
        $field = [
            'nm_pimp' => ['required'],
            'nip' => ['required'],
            'pangkat' => ['required'],
            'golongan' => ['required']
        ];

        $pesan = [
            'nm_pimp.required' => 'Nama pimpinan tidak boleh kosong <br />',
            'nip.required' => 'NIP pimpinan tidak boleh kosong <br />',
            'pangkat.required' => 'Pangkat tidak boleh kosong <br />',
            'golongan.required' => 'Golongan tidak boleh kosong <br />'
        ];
        $this->validate($request, $field, $pesan);
        $data = [
            'nm_pimp' => $request->nm_pimp,
            'nip' => $request->nip,
            'id_opd' => Auth::user()->id_opd,
            'pangkat' => $request->pangkat,
            'golongan' => $request->golongan
        ];

        TtdSetting::create($data);
        return response()->json('TTd berhasil dibuat',200);
    }

    public function update(Request $request){
        $field = [
            'nm_pimp' => ['required'],
            'nip' => ['required'],
            'pangkat' => ['required'],
            'golongan' => ['required']
        ];

        $pesan = [
            'nm_pimp.required' => 'Nama pimpinan tidak boleh kosong <br />',
            'nip.required' => 'NIP pimpinan tidak boleh kosong <br />',
            'pangkat.required' => 'Pangkat tidak boleh kosong <br />',
            'golongan.required' => 'Golongan tidak boleh kosong <br />'
        ];
        $this->validate($request, $field, $pesan);
        $data = [
            'nm_pimp' => $request->nm_pimp,
            'nip' => $request->nip,
            'pangkat' => $request->pangkat,
            'golongan' => $request->golongan
        ];

        TtdSetting::where('id_opd','=',Auth::user()->id_opd)->update($data);
        return response()->json('TTd berhasil diubah',200);
    }
}
