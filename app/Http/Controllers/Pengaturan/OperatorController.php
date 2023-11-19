<?php

namespace App\Http\Controllers\Pengaturan;

use App\Http\Controllers\Controller;
use App\Models\DataOpd;
use App\Models\KodeOpd;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class OperatorController extends Controller
{
    public function index(){
        $instansi = KodeOpd::all();
        return view('pengaturan.operator',[
            'title' => 'Pengaturan',
            'page' => 'Operator',
            'drops' => $instansi
        ]);
    }

    public function data(){
        if(Auth::user()->level == 'admin'){
            $admin = User::where('level','=','operator');
        }elseif(Auth::user()->level == 'bendahara'){
            // $admin = User::where('level','=','operator')->where('id_opd','=',Auth::user()->id_opd)->get();
            $admin = User::where([
                ['level','=','operator'],
                ['id_opd','=',Auth::user()->id_opd]
            ]);
        }
        return datatables()->eloquent($admin)
                ->addIndexColumn()
                ->addColumn('instansi', function($admin) {
                    return getValue("nm_opd","kode_opd"," id = ".$admin->id_opd);
                })
                ->addColumn('aksi', function($admin){
                    return '
                            <div class="btn-group">
                                <a href="javascript:void(0)" onclick="editAdmin(`'.route('operator.update',$admin->id).'`,'.$admin->id.')" class="btn btn-warning" ><i class="fas fa-edit"></i></a>
                                <a href="javascript:void(0)" onclick="hapusAdmin(`'.route('operator.destroy',$admin->id).'`)" class="btn btn-danger" ><i class="fas fa-trash"></i></i></a>
                            </div>
                            ';
                })
                ->rawColumns(['aksi'])
                ->make(true);
    }

    public function show($id){
        $admin = User::find($id);
        return response()->json($admin);
    }

    public function store(Request $request){

        $field = [
            'name' => ['required'],
            // 'id_opd' => ['required'],
            'email' => ['required', 'email:dns', 'unique:users'],
            'nip' => ['required', 'numeric', 'min:18','unique:users'],
            'password' => ['required', 'min:8']
        ];

        $pesan = [
            'name.required' => 'Nama tidak boleh kosong <br />',
            // 'id_opd.required' => 'Mohon pilih instansi <br />',
            'email.required' => 'Email tidak boleh kosong <br />',
            'email.email' => 'Email harus valid <br />',
            'email.unique' => 'Email telah terdaftar <br />',
            'nip.required' => 'NIP tidak boleh kosong <br />',
            'nip.numeric' => 'NIP harus berupa angka <br />',
            'nip.min' => 'NIP minimal 18 digit <br />',
            'nip.unique' => 'NIP telah terdaftar <br />',
            'password.required' => 'Password tidak boleh kosong <br />',
            'password.min' => 'Password minimal 8 karakter <br />',
        ];
        if(Auth::user()->level == 'admin'){
            $field['id_opd'] = ['required'];
            $pesan['id_opd.required'] = 'Mohon pilih instansi <br />';
        }
        $this->validate($request, $field, $pesan);
        $data = [
            'name' => $request->name,
            'id_opd' => (Auth::user()->level == 'admin') ? $request->id_opd : Auth::user()->id_opd,
            'email' => $request->email,
            'nip' => $request->nip,
            'password' => Hash::make($request->password),
            'level' => 'operator'
        ];
        User::create($data);
        return response()->json('user operator berhasil ditambahkan',200);
    }

    public function update(Request $request,$id){
        $admin = User::find($id);
        $field = [
            'name' => ['required'],
            'email' => ['required', 'email:dns', 'unique:users,email,'.$admin->id],
            'nip' => ['required', 'numeric', 'min:18','unique:users,nip,'.$admin->id]
        ];

        $pesan = [
            'name.required' => 'Nama tidak boleh kosong <br />',
            'email.required' => 'Email tidak boleh kosong <br />',
            'email.email' => 'Email harus valid <br />',
            'email.unique' => 'Email telah terdaftar <br />',
            'nip.required' => 'NIP tidak boleh kosong <br />',
            'nip.numeric' => 'NIP harus berupa angka <br />',
            'nip.min' => 'NIP minimal 18 digit <br />',
            'nip.unique' => 'NIP telah terdaftar <br />'
        ];
        if($request->filled('password')){
            $field['password'] = ['min:8'];
            $pesan['password.min'] = 'Password minimal 8 karakter <br />';
        }
        if(Auth::user()->level == 'admin'){
            $field['id_opd'] = ['required'];
            $pesan['id_opd.required'] = 'Mohon pilih instansi <br />';
        }
        $this->validate($request, $field, $pesan);
        $data = [
            'name' => $request->name,
            'id_opd' => (Auth::user()->level == 'admin') ? $request->id_opd : Auth::user()->id_opd,
            'email' => $request->email,
            'nip' => $request->nip
        ];
        if($request->filled('password')){
            $data['password'] = Hash::make($request->password);
        }
        $admin->update($data);
        return response()->json('user operator berhasil diubah',200);
    }

    public function destroy($id){
        $admin = User::find($id);
        $admin->delete();
        return response('user operator berhasil dihapus', 204);
    }
}
