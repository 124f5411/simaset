<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfilController extends Controller
{
    public function index(){
        return redirect()->route('dashboard');
    }

    public function password(Request $request,$id){
        $user = User::find($id);
        $field = [
            'password' => 'required|confirmed|min:8'
        ];

        $pesan = [
            'password.required' => 'Password tidak boleh kosong <br />',
            'password.confirmed' => 'Password konfirmasi tidak cocok <br />',
            'password.min' => 'Password minimal 8 karakter <br />'
        ];
        $this->validate($request, $field, $pesan);

        $data = [
            'password' => Hash::make($request->password)
        ];
        $user->update($data);

        return response()->json('Berhasil ubah password', 200);

    }
}
