<?php

namespace App\Http\Controllers;

use App\Models\DetailRincianUsulan;
use App\Models\UsulanSsh;
use Illuminate\Http\Request;

class MonitorController extends Controller
{
    public function index($any){
        $view = [
            'ssh' => 'monitor.ssh',
            'asb' => 'monitor.asb',
            'sbu' => 'monitor.sbu',
            'hspk' => 'monitor.hspk'
        ];
        $page = [
            'ssh' => 'PANTAUSSH',
            'asb' => 'PANTAUASB',
            'sbu' => 'PANTAUSBU',
            'hspk' => 'PANTAUHSPK'
        ];


        return view($view[$any],[
            'title' => 'MONITORING',
            'page' => $page[$any]
        ]);
    }

    public function getData($any){
        $id_kelompok = [
            'ssh' => '1',
            'sbu' => '2',
            'asb' => '3',
            'hspk' => '4'
        ];

        $data = UsulanSsh::select(
            'usulan_ssh.*','_data_ssh.id as id_ssh','_data_ssh.id_kode',
            '_data_ssh.id_rekening','_data_ssh.id_usulan','_data_ssh.uraian',
            '_data_ssh.spesifikasi','_data_ssh.id_satuan','_data_ssh.harga',
            '_data_ssh.status as status_ssh','_data_ssh.keterangan','_data_ssh.id_kelompok'
            )
            ->join('_data_ssh','usulan_ssh.id','=','_data_ssh.id_usulan')
            ->where('_data_ssh.id_kelompok','=',$id_kelompok[$any])
            ->get();
        return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('opd', function($data){
                    return getValue("opd","data_opd"," id = ".$data->id_opd);
                })
                ->addColumn('kode_barang',function($data){
                    return getValue("kode_barang","referensi_kode_barang"," id = ".$data->id_kode);
                })
                ->addColumn('nama_barang',function($data){
                    return getValue("uraian","referensi_kode_barang"," id = ".$data->id_kode);
                })
                ->addColumn('satuan', function($data){
                    return getValue("nm_satuan","data_satuan"," id = ".$data->id_satuan);
                })
                ->addColumn('tkdn', function($data){
                    return (!is_null($data->tkdn)) ? $data->tkdn : "";
                })
                ->addColumn('rekening',function($data){
                    $details = DetailRincianUsulan::where('id_ssh','=',$data->id_ssh)->get();
                    $show = "";
                    foreach($details as $detail){
                        $show .= getValue("kode_akun","referensi_rekening_belanja","id = ".$detail->kode_akun).'<br>';
                    }
                    return $show;
                })
                ->addColumn('status_ssh', function($data){
                    $status = [
                        '0' => 'Proses OPD',
                        '1' => 'Proses ASET',
                        '2' => 'Diterima'
                    ];
                    return $status[$data->status_ssh];
                })
                ->rawColumns(['rekening'])
                ->make(true);


    }
}
