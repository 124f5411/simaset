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

        $tahun = UsulanSsh::select('tahun')->groupBy('tahun')->get();
        $jenis  = UsulanSsh::select('induk_perubahan')->groupBy('induk_perubahan')->get();
        return view($view[$any],[
            'title' => 'MONITORING',
            'page' => $page[$any],
            'drops' => [
                'tahun' => $tahun,
                'jenis' => $jenis
            ],
        ]);
    }

    public function getData(Request $request,$any){
        $id_kelompok = [
            'ssh' => '1',
            'sbu' => '2',
            'asb' => '3',
            'hspk' => '4'
        ];

        $data = UsulanSsh::select(
            'usulan_ssh.*','_data_ssh.id as id_ssh','_data_ssh.id_kode',
            '_data_ssh.rek_1',
            '_data_ssh.rek_2',
            '_data_ssh.rek_3',
            '_data_ssh.rek_4',
            '_data_ssh.rek_5',
            '_data_ssh.rek_6',
            '_data_ssh.rek_7',
            '_data_ssh.rek_8',
            '_data_ssh.rek_9',
            '_data_ssh.rek_10',
            '_data_ssh.tkdn',
            '_data_ssh.id_usulan','_data_ssh.uraian',
            '_data_ssh.spesifikasi','_data_ssh.id_satuan','_data_ssh.harga',
            '_data_ssh.status as status_ssh','_data_ssh.keterangan','_data_ssh.id_kelompok'
            )
            ->join('_data_ssh','usulan_ssh.id','=','_data_ssh.id_usulan')
            ->where('_data_ssh.id_kelompok','=',$id_kelompok[$any]);
        return datatables()->eloquent($data)
                ->addIndexColumn()
                ->addColumn('opd', function($data){
                    return getValue("opd","data_opd"," id = ".$data->id_opd);
                })
                ->addColumn('kode_barang',function($data){
                    return getValue("kode_barang","referensi_kode_barang"," id = ".$data->id_kode);
                })
                ->addColumn('usulan',function($data){
                    return ($data->induk_perubahan == "1") ? "Induk" : "Perubahan";
                })
                ->addColumn('harga',function($data) {
                    return number_format($data->harga, 2, ",", ".");
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
                ->addColumn('rek_1',function($data){
                    return (!is_null($data->rek_1)) ? getValue("kode_akun","referensi_rekening_belanja","id = ".$data->rek_1) : "";
                })
                ->addColumn('rek_2',function($data){
                    return (!is_null($data->rek_2)) ? getValue("kode_akun","referensi_rekening_belanja","id = ".$data->rek_2) : "";
                })
                ->addColumn('rek_3',function($data){
                    return (!is_null($data->rek_3)) ? getValue("kode_akun","referensi_rekening_belanja","id = ".$data->rek_3) : "";
                })
                ->addColumn('rek_4',function($data){
                    return (!is_null($data->rek_4)) ? getValue("kode_akun","referensi_rekening_belanja","id = ".$data->rek_4) : "";
                })
                ->addColumn('rek_5',function($data){
                    return (!is_null($data->rek_5)) ? getValue("kode_akun","referensi_rekening_belanja","id = ".$data->rek_5) : "";
                })
                ->addColumn('rek_6',function($data){
                    return (!is_null($data->rek_6)) ? getValue("kode_akun","referensi_rekening_belanja","id = ".$data->rek_6) : "";
                })
                ->addColumn('rek_7',function($data){
                    return (!is_null($data->rek_7)) ? getValue("kode_akun","referensi_rekening_belanja","id = ".$data->rek_7) : "";
                })
                ->addColumn('rek_8',function($data){
                    return (!is_null($data->rek_8)) ? getValue("kode_akun","referensi_rekening_belanja","id = ".$data->rek_8) : "";
                })
                ->addColumn('rek_9',function($data){
                    return (!is_null($data->rek_9)) ? getValue("kode_akun","referensi_rekening_belanja","id = ".$data->rek_9) : "";
                })
                ->addColumn('rek_10',function($data){
                    return (!is_null($data->rek_10)) ? getValue("kode_akun","referensi_rekening_belanja","id = ".$data->rek_10) : "";
                })
                ->addColumn('status_ssh', function($data){
                    $status = [
                        '0' => 'Proses OPD',
                        '1' => 'Proses ASET',
                        '2' => 'Diterima'
                    ];
                    return $status[$data->status_ssh];
                })
                ->filter( function($instance) use ($request){
                    if (!empty($request->get('tahun'))) {
                        $instance->where('usulan_ssh.tahun','LIKE','%'.$request->get('tahun').'%');
                    }
                    if ($request->get('usulan') == '1' || $request->get('usulan') == '2') {
                        $instance->where('usulan_ssh.induk_perubahan','=',$request->get('usulan'));
                    }

                })
                ->rawColumns(['rekening'])
                ->make(true);


    }
}
