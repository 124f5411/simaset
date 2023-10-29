<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(){
        return view('dashboard.index',[
            'title' => 'Dashboard',
            'page' => 'Dashboard'
        ]);
    }

    public function keluar(Request $request){
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('main');
    }
}
