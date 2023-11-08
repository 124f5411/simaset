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
use SimpleSoftwareIO\QrCode\Facades\QrCode;

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
        $rincian = dataSsh::where('id_usulan','=',$id);
        return datatables()->of($rincian)
                ->addIndexColumn()
                ->addColumn('uraian_id',function($rincian) {
                    return getValue("uraian","referensi_kode_barang","id = ".$rincian->id_kode);
                })
                ->addColumn('kode_barang',function($rincian) {
                    return getValue("kode_barang","referensi_kode_barang","id = ".$rincian->id_kode);
                })
                ->addColumn('satuan',function($rincian){
                    return getValue("nm_satuan","data_satuan","id = ".$rincian->id_satuan);
                })
                ->addColumn('rek_1',function($rincian){
                    return (!is_null($rincian->rek_1)) ? getValue("kode_akun","referensi_rekening_belanja","id = ".$rincian->rek_1) : "";
                })
                ->addColumn('rek_2',function($rincian){
                    return (!is_null($rincian->rek_2)) ? getValue("kode_akun","referensi_rekening_belanja","id = ".$rincian->rek_2) : "";
                })
                ->addColumn('rek_3',function($rincian){
                    return (!is_null($rincian->rek_3)) ? getValue("kode_akun","referensi_rekening_belanja","id = ".$rincian->rek_3) : "";
                })
                ->addColumn('rek_4',function($rincian){
                    return (!is_null($rincian->rek_4)) ? getValue("kode_akun","referensi_rekening_belanja","id = ".$rincian->rek_4) : "";
                })
                ->addColumn('rek_5',function($rincian){
                    return (!is_null($rincian->rek_5)) ? getValue("kode_akun","referensi_rekening_belanja","id = ".$rincian->rek_5) : "";
                })
                ->addColumn('rek_6',function($rincian){
                    return (!is_null($rincian->rek_6)) ? getValue("kode_akun","referensi_rekening_belanja","id = ".$rincian->rek_6) : "";
                })
                ->addColumn('rek_7',function($rincian){
                    return (!is_null($rincian->rek_7)) ? getValue("kode_akun","referensi_rekening_belanja","id = ".$rincian->rek_7) : "";
                })
                ->addColumn('rek_8',function($rincian){
                    return (!is_null($rincian->rek_8)) ? getValue("kode_akun","referensi_rekening_belanja","id = ".$rincian->rek_8) : "";
                })
                ->addColumn('rek_9',function($rincian){
                    return (!is_null($rincian->rek_9)) ? getValue("kode_akun","referensi_rekening_belanja","id = ".$rincian->rek_9) : "";
                })
                ->addColumn('rek_10',function($rincian){
                    return (!is_null($rincian->rek_10)) ? getValue("kode_akun","referensi_rekening_belanja","id = ".$rincian->rek_10) : "";
                })
                ->addColumn('harga',function($rincian) {
                    return number_format($rincian->harga, 2, ",", ".");
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
            'id_usulan' => $id,
            'spesifikasi' => $request->spesifikasi,
            'uraian' => $request->uraian,
            'harga' => $request->harga,
            'tkdn' => $request->tkdn,
            'id_satuan' => $request->id_satuan,
            'id_kelompok' => $id_kelompok,
            'rek_1' => $request->rek_1,
            'rek_2' => $request->rek_2,
            'rek_3' => $request->rek_3,
            'rek_4' => $request->rek_4,
            'rek_5' => $request->rek_5,
            'rek_6' => $request->rek_6,
            'rek_7' => $request->rek_7,
            'rek_8' => $request->rek_8,
            'rek_9' => $request->rek_9,
            'rek_10' => $request->rek_10,
            'status' => '0'
        ];

        dataSsh::create($data);
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
            'spesifikasi' => ['required'],
            'uraian' => ['required'],
            'id_satuan' => ['required'],
            'harga' => ['required'],
            'tkdn' => ['required']
        ];

        $pesan = [
            'id_kode.required' => 'Barang tidak boleh kosong <br />',
            'spesifikasi.required' => 'Spesifikasi tidak boleh kosong <br />',
            'id_satuan.required' => 'Satuan tidak boleh kosong <br />',
            'uraian.required' => 'Uraian tidak boleh kosong <br />',
            'harga.required' => 'Harga tidak boleh kosong <br />',
            'tkdn.required' => 'T K D N tidak boleh kosong <br />',
        ];
        $this->validate($request, $field, $pesan);
        $data = [
            'id_kode' => $request->id_kode,
            'spesifikasi' => $request->spesifikasi,
            'uraian' => $request->uraian,
            'harga' => $request->harga,
            'tkdn' => $request->tkdn,
            'id_kelompok' => $id_kelompok,
            'id_satuan' => $request->id_satuan,
            'rek_1' => $request->rek_1,
            'rek_2' => $request->rek_2,
            'rek_3' => $request->rek_3,
            'rek_4' => $request->rek_4,
            'rek_5' => $request->rek_5,
            'rek_6' => $request->rek_6,
            'rek_7' => $request->rek_7,
            'rek_8' => $request->rek_8,
            'rek_9' => $request->rek_9,
            'rek_10' => $request->rek_10,
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
        $qrcode = base64_encode(QrCode::format('svg')->size(100)->errorCorrection('H')->generate(route('rincian.export',$id)));
        $data = [
            'tahun' => $usulan->tahun,
            'instansi' => "PEMERINTAH PROVINSI PAPUA BARAT DAYA",
            'title' => "USULAN ".strtoupper($jenis)." TAHUN ANGGARAN",
            'rincian' => $rincian,
            'ttd' => $ttd,
            'opd' => $opd,
            'id_ssh' => $rincian[0]->id,
            'qrcode' => $qrcode,

        ];
        $pdf = PDF::loadView('pdf.usulan.rincian',$data);
        $pdf->setPaper('legal', 'landscape');
        $pdf->render();
        $pdf->get_canvas()->page_text(10, 20, "Halaman {PAGE_NUM} dari {PAGE_COUNT}", null, 10, array(0, 0, 0));
        return $pdf->stream('usulan-'.$jenis.'-'.$usulan->id_opd.'-TA-'.$usulan->tahun.'-' . date('Y-m-d H:i:s') . '.pdf');
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
