<?php

namespace App\Http\Controllers;

use App\Models\DetailKontrak;
use App\Models\HakTanah;
use App\Models\KodeBarang;
use App\Models\StatusTanah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RincianKontrakController extends Controller
{
    public function index($id){
        $jenis = KodeBarang::whereNotNull('kib')->get();
        $hak = HakTanah::all();
        $status_tanah = StatusTanah::all();
        return view('kontrak.opd.rincian',[
            'title' => 'Kontrak',
            'page' => 'Rincian',
            'drops' => [
                'jenis' => $jenis,
                'hak' => $hak,
                'status_tanah' => $status_tanah
            ]
        ]);
    }

    protected function getData($id){
        $detail = DetailKontrak::select('detail_kontrak.*','data_kontrak.id as kontrak_id','data_kontrak.no_kontrak',
        'data_kontrak.nm_kontrak','data_kontrak.tahun as kontrak_tahun','data_kontrak.t_kontrak','data_kontrak.opd')
        ->join('data_kontrak','detail_kontrak.id_kontrak','=','data_kontrak.id')
        ->where([
            ['data_kontrak.id','=',$id],
            ['data_kontrak.opd','=',Auth::user()->id_opd]
        ]);

        return $detail;
    }

    public function data($id){
        $detail = $this->getData(decrypt($id));
        return datatables()->eloquent($detail)
                    ->addIndexColumn()
                    ->addColumn('jenis_aset',function($detail){
                        return (!is_null($detail->kode)) ? getValue("nama","ref_barang_kontrak","kode = '$detail->kode' ") : "";
                    })
                    ->addColumn('details', function($detail){
                        $form = (!is_null($detail->kode)) ? getValue("kib","ref_barang_kontrak","kode = '$detail->kode' ") : "";
                        return '
                        <a href="javascript:void(0)" onclick="addDetails(`'.route('kontrak.detail.update',$detail->id).'`,`'.$form.'`,'.$detail->id.')"
                        class="btn btn-sm btn-success btn-icon-split" title="Isi Detail">
                            <span class="icon text-white-50">
                                <i class="fas fa-pen-square"></i>
                            </span>
                            <span class="text">Detail</span>
                        </a>
                        ';
                    })
                    ->addColumn('kib', function($detail){
                        return (!is_null($detail->kode)) ? getValue("kib","ref_barang_kontrak","kode = '$detail->kode' ") : "";
                    })
                    ->addColumn('harga', function($detail){
                        return number_format($detail->harga, 2, ",", ".");
                    })
                    ->addColumn('aksi', function($detail){
                        return '
                        <div class="btn-group">
                            <a href="javascript:void(0)" onclick="editRincian(`'.route('kontrak.detail.update',$detail->id).'`,'.$detail->id.')" class="btn btn-sm btn-warning" title="Ubah Kontrak" ><i class="fas fa-edit"></i></a>
                            <a href="javascript:void(0)" onclick="hapusRincian(`'.route('kontrak.detail.destroy',$detail->id).'`)" class="btn btn-sm btn-danger" title="Hapus Kontrak" ><i class="fas fa-trash"></i></a>
                        </div>
                        ';
                    })
                    ->rawColumns(['aksi','details'])
                    ->make(true);

    }

    public function store(Request $request,$id){
        $field = [
            'kelompok' => ['required'],
            'kode' => ['required'],
            'jumlah' =>  ['required'],
            'asal' =>  ['required'],
            'harga' =>  ['required'],
        ];

        $pesan = [
            'kelompok.required' => 'Silahkan pilih objek barang <br />',
            'kode.required' => 'Kode Aset tidak boleh kosong <br />',
            'jumlah.required' => 'Jumlah barang tidak boleh kosong <br />',
            'asal.required' => 'Aasl Usul dana tidak boleh kosong <br />',
            'harga.required' => 'Harga tidak boleh kosong <br />'
        ];
        $this->validate($request, $field, $pesan);
        for ($i=0; $i < $request->jumlah ; $i++) {
            $data = [
                'id_kontrak' => decrypt($id),
                'kode' => $request->kode,
                'register' => $this->kodeRegister($request->kode,$id),
                'asal' => $request->asal,
                'harga' => $request->harga
            ];
            DetailKontrak::create($data);
        }

        return response()->json('Rincian Kontrak berhasil ditambahkan',200);
    }


    public function show($id){
        $detail = DetailKontrak::find($id);
        return response()->json($detail);
    }

    protected function kodeRegister($kode,$id_kontrak){
        // dd($kode);
        $register = DetailKontrak::select('detail_kontrak.register')
            ->join('data_kontrak','detail_kontrak.id_kontrak','=','data_kontrak.id')
            ->where([
                ['data_kontrak.id','=',decrypt($id_kontrak)],
                ['detail_kontrak.kode','=',$kode],
                ['data_kontrak.opd','=',Auth::user()->id_opd]
            ])->get();

            if($register->count() == 0){
                $reg = '000000';
            }else{
                $reg = $register->max('register');
            }

            $urut = (int) substr($reg, 0, 6);
            $urut++;
            $regis = sprintf("%06s", $urut);
        return $regis;
    }


    public function getRegister($kode,$id_kontrak){
        $register = DetailKontrak::select('detail_kontrak.register')
            ->join('data_kontrak','detail_kontrak.id_kontrak','=','data_kontrak.id')
            ->where([
                ['data_kontrak.id','=',decrypt($id_kontrak)],
                ['detail_kontrak.kode','=',$kode],
                ['data_kontrak.opd','=',Auth::user()->id_opd]
            ])->get();

        if($register->count() == 0){
            $reg = '000000';
        }else{
            $reg = $register[0]->max('register');
        }

        $urut = (int) substr($reg, 1, 6);
		$urut++;
		$regis = sprintf("%06s", $urut);
        return response()->json($regis);
    }

    public function update(Request $request,$id){
        $detail = DetailKontrak::find($id);
        $detail->update($request->all());
        return response()->json('Detail Kontrak berhasil dibuah',200);

    }

    public function destroy($id){
        $detail = DetailKontrak::find($id);
        $detail->delete();
        return response('Detail Kontrak berhasil dihapus', 204);
    }
}
