<?php

namespace App\Http\Controllers\Input;

use App\Http\Controllers\Controller;
use App\Models\DataSatuan;
use App\Models\dataSsh;
use App\Models\DetailRincianUsulan;
use App\Models\KelompokSsh;
use App\Models\KodeBarang;
use App\Models\RekeningBelanja;
use App\Models\TtdSetting;
use App\Models\UsulanSsh;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class RincianController extends Controller
{
    public function index($id){
        $usulan = UsulanSsh::find(decrypt($id));
        $kode_barang = KodeBarang::whereIn('kelompok',['1','2','3','4'])->get();
        $rekening = RekeningBelanja::all();
        $satuan = DataSatuan::all();

        return view('input.rincian',[
            'title' => 'Input',
            'page' => 'Usulan',
            'drops' => [
                'kode_barang' => $kode_barang,
                'rekening' => $rekening,
                'satuan' => $satuan
            ],
            'usulan_status' => $usulan->status
        ]);

    }

    public function data($id){
        $rincian = dataSsh::where('id_usulan','=',$id)->get();
        return datatables()->of($rincian)
                ->addIndexColumn()
                ->addColumn('uraian_id',function($rincian) {
                    return getValue("uraian","referensi_kode_barang","id = ".$rincian->id_kode);
                })
                ->addColumn('kode_barang',function($rincian) {
                    return getValue("kode_barang","referensi_kode_barang","id = ".$rincian->id_kode);
                })
                ->addColumn('rekening_belanja',function($rincian) {
                    $details = DetailRincianUsulan::where('id_ssh','=',$rincian->id)->get();
                    $show = '
                        <a href="javascript:void(0)" onclick="addRekening(`'.route('rincian.rekening.store',$rincian->id).'`)" class="btn btn-sm btn-primary btn-icon-split mb-2">
                            <span class="icon text-white-50">
                                <i class="fas fa-plus-circle"></i>
                            </span>
                            <span class="text">Rekening</span>
                        </a><br>
                        ';
                    if($details->count() > 0){
                        foreach($details as $detail){
                            $show .='
                            <a href="javascript:void(0)" onclick="hapusRekening(`'.route('rincian.rekening.destroy',$detail->id).'`)" class="btn btn-sm btn-danger btn-icon-split mb-2">
                                <span class="text">'.getValue("kode_akun","referensi_rekening_belanja","id = ".$detail->kode_akun).'</span>
                                <span class="icon text-white-50">
                                    <i class="fas fa-trash"></i>
                                </span>
                            </a>
                            ';
                        }
                    }else{
                        $show .= "";
                    }
                    // return getValue("kode_akun","referensi_rekening_belanja","id = ".$rincian->id_rekening);
                    return $show;
                })
                ->addColumn('satuan',function($rincian){
                    return getValue("nm_satuan","data_satuan","id = ".$rincian->id_satuan);
                })
                ->addColumn('harga',function($rincian) {
                    return "Rp. ".number_format($rincian->harga, 2, ",", ".");
                })
                ->addColumn('aksi', function($rincian){
                    $aksi = [
                        '0' =>
                                '
                                <div class="btn-group">
                                    <a href="javascript:void(0)" onclick="editSsh(`'.route('rincian.update',$rincian->id).'`,'.$rincian->id.')" class="btn btn-sm btn-warning" title="Ubah" ><i class="fas fa-edit"></i></a>
                                    <a href="javascript:void(0)" onclick="hapusSsh(`'.route('rincian.destroy',$rincian->id).'`)" class="btn btn-sm btn-danger" title="Hapus"><i class="fas fa-trash"></i></a>
                                </div>
                                ',
                        '1' => 'Proccesed',
                        '2' => 'Valid'
                    ];
                    return $aksi[$rincian->status];
                })
                ->addColumn('jenis', function($rincian){
                    return getValue("kelompok","_kelompok_ssh"," id = ".$rincian->id_kelompok);
                })
                ->rawColumns(['aksi','rekening_belanja'])
                ->make(true);
    }

    public function store(Request $request,$id){
        $id_kelompok = getValue("kelompok","referensi_kode_barang"," id = ".$request->id_kode);
        $field = [
            'id_kode' => ['required'],
            'id_rekening' => ['required'],
            'uraian' => ['required'],
            'spesifikasi' => ['required'],
            'id_satuan' => ['required'],
            'harga' => ['required'],
            'tkdn' => ['required']
        ];

        $pesan = [
            'id_kode.required' => 'Barang tidak boleh kosong <br />',
            'id_rekening.required' => 'Rekening belanja tidak boleh kosong <br />',
            'uraian.required' => 'Uraian tidak boleh kosong <br />',
            'spesifikasi.required' => 'Spesifikasi tidak boleh kosong <br />',
            'id_satuan.required' => 'Satuan tidak boleh kosong <br />',
            'harga.required' => 'Harga tidak boleh kosong <br />',
            'tkdn.required' => 'T K D N tidak boleh kosong <br />',
        ];
        $this->validate($request, $field, $pesan);
        $data = [
            'id_kode' => $request->id_kode,
            // 'id_rekening' => $request->id_rekening,
            'id_usulan' => $id,
            'spesifikasi' => $request->spesifikasi,
            'uraian' => $request->uraian,
            'harga' => $request->harga,
            'tkdn' => $request->tkdn,
            'id_satuan' => $request->id_satuan,
            'id_kelompok' => $id_kelompok,
            'status' => '0'
        ];

        $insert = dataSsh::create($data);

        foreach($request->id_rekening as $rekening){
            DetailRincianUsulan::create([
                'id_ssh' => $insert->id,
                'kode_akun' => $rekening
            ]);
        }

        return response()->json('Rincian Usulan berhasil ditambahkan',200);
    }

    public function show($id){
        $rincian = dataSsh::find($id);
        return response()->json($rincian);
    }

    public function showRekening($id){
        $details = DetailRincianUsulan::where('id_ssh','=',$id)->get();
        return response()->json($details);
    }

    public function update(Request $request,$id){
        $rincian = dataSsh::find($id);
        $id_kelompok = getValue("kelompok","referensi_kode_barang"," id = ".$request->id_kode);
        $field = [
            'id_kode' => ['required'],
            // 'id_rekening' => ['required'],
            'spesifikasi' => ['required'],
            'uraian' => ['required'],
            'id_satuan' => ['required'],
            'harga' => ['required'],
            'tkdn' => ['required']
        ];

        $pesan = [
            'id_kode.required' => 'Barang tidak boleh kosong <br />',
            // 'id_rekening.required' => 'Rekening belanja tidak boleh kosong <br />',
            'spesifikasi.required' => 'Spesifikasi tidak boleh kosong <br />',
            'id_satuan.required' => 'Satuan tidak boleh kosong <br />',
            'uraian.required' => 'Uraian tidak boleh kosong <br />',
            'harga.required' => 'Harga tidak boleh kosong <br />',
            'tkdn.required' => 'T K D N tidak boleh kosong <br />',
        ];
        $this->validate($request, $field, $pesan);
        $data = [
            'id_kode' => $request->id_kode,
            // 'id_rekening' => $request->id_rekening,
            'spesifikasi' => $request->spesifikasi,
            'uraian' => $request->uraian,
            'harga' => $request->harga,
            'tkdn' => $request->tkdn,
            'id_kelompok' => $id_kelompok,
            'id_satuan' => $request->id_satuan
        ];

        $rincian->update($data);
        return response()->json('Rincian usulan berhasil diubah',200);
    }

    public function export($id){
        $rincian = dataSsh::where('id_usulan','=',decrypt($id))->get();
        $usulan = UsulanSsh::find(decrypt($id));
        $jenis = ($usulan->induk_perubahan == "1") ? "induk" : "perubahan";
        $ttd = TtdSetting::where('id_opd','=',$usulan->id_opd)->first();
        $opd = getValue("opd","data_opd"," id =".$usulan->id_opd);
        $data = [
            'tahun' => $usulan->tahun,
            'instansi' => "PEMERINTAH PROVINSI PAPUA BARAT DAYA",
            'title' => "USULAN ".strtoupper($jenis)." TAHUN ANGGARAN",
            'rincian' => $rincian,
            'ttd' => $ttd,
            'opd' => $opd,
            'id_ssh' => $rincian[0]->id
        ];
        $pdf = PDF::loadView('pdf.usulan.rincian',$data);
        $pdf->setPaper('legal', 'landscape');
        return $pdf->stream('usulan-'.$jenis.'-'.Auth::user()->id_opd.'-TA-'.$usulan->tahun.'-' . date('Y-m-d H:i:s') . '.pdf');
    }

    public function destroy($id){
        $usulan = dataSsh::find($id);
        $usulan->delete();
        return response()->json('Rincian usulan berhasil dihapus', 204);
    }

    public function detailStore(Request $request,$id){
        $field = [
            'id_rekenings' => ['required'],
        ];

        $pesan = [
            'id_rekenings.required' => 'Rekening belanja tidak boleh kosong <br />',
        ];

        $this->validate($request, $field, $pesan);

        foreach($request->id_rekenings as $rekening){
            DetailRincianUsulan::create([
                'id_ssh' => $id,
                'kode_akun' => $rekening
            ]);
        }
        return response()->json('Rekening belanja berhasil ditambahkan',200);
    }

    public function detailDestroy($id){
        $detail = DetailRincianUsulan::find($id);
        $detail->delete();
        return response()->json('Rekening belanja berhasil dihapus', 204);
    }



}
