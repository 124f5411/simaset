<?php

namespace App\Http\Controllers;

use App\Models\UsulanSsh;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(){
        $data = [
            'ssh' => [
                'usulan' =>$this->usulan('ssh'),
                'proses' => $this->proses('ssh'),
                'terima' => $this->terima('ssh'),
                'tolak' => $this->tolak('ssh')
            ],
            'sbu' => [
                'usulan' =>$this->usulan('sbu'),
                'proses' => $this->proses('sbu'),
                'terima' => $this->terima('sbu'),
                'tolak' => $this->tolak('sbu')
            ],
            'asb' => [
                'usulan' =>$this->usulan('asb'),
                'proses' => $this->proses('asb'),
                'terima' => $this->terima('asb'),
                'tolak' => $this->tolak('asb')
            ],
            'hspk' => [
                'usulan' =>$this->usulan('hspk'),
                'proses' => $this->proses('hspk'),
                'terima' => $this->terima('hspk'),
                'tolak' => $this->tolak('hspk')
            ]
        ];
        return view('dashboard.index',[
            'title' => 'Dashboard',
            'page' => 'Dashboard',
            'data' => $data
        ]);
    }

    protected function usulan($j){
        $id_kelompok = [
            'ssh' => '1',
            'sbu' => '2',
            'asb' => '3',
            'hspk' => '4'
        ];
        if(Auth::user()->level == 'operator' || Auth::user()->level == 'bendahara'){
            $usulan = UsulanSsh::join('_data_ssh','usulan_ssh.id','=','_data_ssh.id_usulan')
                                ->where('_data_ssh.id_kelompok','=',$id_kelompok[$j])
                                ->where('usulan_ssh.id_opd','=',Auth::user()->id_opd)
                                ->get();
        }

        if(Auth::user()->level == 'aset' || Auth::user()->level == 'admin'){
            $usulan = UsulanSsh::join('_data_ssh','usulan_ssh.id','=','_data_ssh.id_usulan')
                                ->where('_data_ssh.id_kelompok','=',$id_kelompok[$j])
                                ->get();
        }
        return $usulan->count();

    }

    protected function proses($j){
        $id_kelompok = [
            'ssh' => '1',
            'sbu' => '2',
            'asb' => '3',
            'hspk' => '4'
        ];
        if(Auth::user()->level == 'operator' || Auth::user()->level == 'bendahara'){
            $proses = UsulanSsh::join('_data_ssh','usulan_ssh.id','=','_data_ssh.id_usulan')
                                ->where('_data_ssh.id_kelompok','=',$id_kelompok[$j])
                                ->where('_data_ssh.status','=','1')
                                ->where('usulan_ssh.id_opd','=',Auth::user()->id_opd)
                                ->get();
        }

        if(Auth::user()->level == 'aset' || Auth::user()->level == 'admin'){
            $proses = UsulanSsh::join('_data_ssh','usulan_ssh.id','=','_data_ssh.id_usulan')
                                ->where('_data_ssh.id_kelompok','=',$id_kelompok[$j])
                                ->where('_data_ssh.status','=','1')
                                ->get();
        }
        return $proses->count();
    }

    protected function terima($j){
        $id_kelompok = [
            'ssh' => '1',
            'sbu' => '2',
            'asb' => '3',
            'hspk' => '4'
        ];
        if(Auth::user()->level == 'operator' || Auth::user()->level == 'bendahara'){
            $terima = UsulanSsh::join('_data_ssh','usulan_ssh.id','=','_data_ssh.id_usulan')
                                ->where('_data_ssh.id_kelompok','=',$id_kelompok[$j])
                                ->where('_data_ssh.status','=','2')
                                ->where('usulan_ssh.id_opd','=',Auth::user()->id_opd)
                                ->get();
        }

        if(Auth::user()->level == 'aset' || Auth::user()->level == 'admin'){
            $terima = UsulanSsh::join('_data_ssh','usulan_ssh.id','=','_data_ssh.id_usulan')
                                ->where('_data_ssh.id_kelompok','=',$id_kelompok[$j])
                                ->where('_data_ssh.status','=','2')
                                ->get();
        }
        return $terima->count();
    }

    protected function tolak($j){
        $id_kelompok = [
            'ssh' => '1',
            'sbu' => '2',
            'asb' => '3',
            'hspk' => '4'
        ];
        if(Auth::user()->level == 'operator' || Auth::user()->level == 'bendahara'){
            $tolak = UsulanSsh::join('_data_ssh','usulan_ssh.id','=','_data_ssh.id_usulan')
                                ->where('_data_ssh.id_kelompok','=',$id_kelompok[$j])
                                ->where('_data_ssh.status','=','3')
                                ->where('usulan_ssh.id_opd','=',Auth::user()->id_opd)
                                ->get();
        }

        if(Auth::user()->level == 'aset' || Auth::user()->level == 'admin'){
            $tolak = UsulanSsh::join('_data_ssh','usulan_ssh.id','=','_data_ssh.id_usulan')
                                ->where('_data_ssh.id_kelompok','=',$id_kelompok[$j])
                                ->where('_data_ssh.status','=','3')
                                ->get();
        }
        return $tolak->count();
    }

    public function keluar(Request $request){
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('main');
    }
}
