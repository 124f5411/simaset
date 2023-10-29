<?php

namespace App\Http\Controllers\Pengaturan;

use App\Http\Controllers\Controller;
use App\Models\DataOpd;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function index(){
        $instansi = DataOpd::all();
        return view('pengaturan.admin',[
            'title' => 'Pengaturan',
            'page' => 'Admin',
            'drops' => $instansi
        ]);
    }

    public function data(){
        $admin = User::where('level','=','admin')->get();
        return datatables()->of($admin)
                ->addIndexColumn()
                ->addColumn('instansi', function($admin) {
                    return getValue("opd","data_opd"," id = ".$admin->id_opd);
                })
                ->addColumn('aksi', function($admin){
                    return '
                            <div class="btn-group">
                                <a href="javascript:void(0)" onclick="editAdmin(`'.route('admin.update',$admin->id).'`,'.$admin->id.')" class="btn btn-warning" ><i class="fas fa-edit"></i></a>
                                <a href="javascript:void(0)" onclick="hapusAdmin(`'.route('admin.destroy',$admin->id).'`)" class="btn btn-danger" ><i class="fas fa-trash"></i></i></a>
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
            'id_opd' => ['required'],
            'email' => ['required', 'email:dns', 'unique:users'],
            'nip' => ['required', 'numeric', 'min:18','unique:users'],
            'password' => ['required', 'min:8']
        ];

        $pesan = [
            'name.required' => 'Nama tidak boleh kosong <br />',
            'id_opd.required' => 'Mohon pilih instansi <br />',
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
        $this->validate($request, $field, $pesan);
        $data = [
            'name' => $request->name,
            'id_opd' => $request->id_opd,
            'email' => $request->email,
            'nip' => $request->nip,
            'password' => Hash::make($request->password),
            'level' => 'admin'
        ];
        User::create($data);
        return response()->json('user admin berhasil ditambahkan',200);
    }

    public function update(Request $request,$id){
        $admin = User::find($id);
        $field = [
            'name' => ['required'],
            'id_opd' => ['required'],
            'email' => ['required', 'email:dns', 'unique:users,email,'.$admin->id],
            'nip' => ['required', 'numeric', 'min:18','unique:users,nip,'.$admin->id]
        ];

        $pesan = [
            'name.required' => 'Nama tidak boleh kosong <br />',
            'id_opd.required' => 'Mohon pilih instansi <br />',
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
        $this->validate($request, $field, $pesan);
        $data = [
            'name' => $request->name,
            'id_opd' => $request->id_opd,
            'email' => $request->email,
            'nip' => $request->nip
        ];
        if($request->filled('password')){
            $data['password'] = Hash::make($request->password);
        }
        $admin->update($data);
        return response()->json('user admin berhasil diubah',200);
    }

    public function destroy($id){
        $admin = User::find($id);
        $admin->delete();
        return response('user admin berhasil dihapus', 204);
    }
}
