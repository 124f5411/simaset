<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MainController extends Controller
{
    public function index(){
        return view('main.index');
    }

    public function c_auth(Request $request){
        $field = [
            'nip' => ['required', 'numeric', 'digits:18'],
            'password' => ['required','min:8']
        ];

        $pesan = [
            'nip.required' => 'NIP harus diisi <br>',
            'nip.numeric' => 'NIP harus berupa angka <br>',
            'nip.digits' => 'NIP harus 18 <br>',
            'nip.unique' => 'NIP telah terdaftar <br>',
            'password.required' => 'Password harus diisi <br>',
            'password.min' => 'Password Minimal 8 Karakter <br>'
        ];
        $credentials = $this->validate($request, $field, $pesan);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return response()->json('Berhasil Login',200);
        }
        return response()->json(['errors' => ['gagal' => 'Nip atau password salah!']],422);
    }
}
