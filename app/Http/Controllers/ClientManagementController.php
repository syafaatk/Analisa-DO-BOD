<?php
namespace App\Http\Controllers;
use App\Models\Client;
use App\Models\ClientUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
class ClientManagementController extends Controller {
 public function index(){return view('admin/clients/index',['clients'=>Client::withCount('users')->latest()->paginate(20)]);}
 public function store(Request $r){
  $d=$r->validate(['code'=>['required','string','max:50',Rule::unique('clients','code')->where(fn($q)=>$q->where('laboratory_id',session('lab_id')))],'name'=>'required|string|max:255','address'=>'nullable|string','phone'=>'nullable|string|max:50','email'=>'nullable|email','admin_name'=>'required|string|max:255','admin_email'=>'required|email','admin_password'=>'required|string|min:8']);
  $client=Client::create(collect($d)->only(['code','name','address','phone','email'])->all());
  ClientUser::create(['laboratory_id'=>session('lab_id'),'client_id'=>$client->id,'name'=>$d['admin_name'],'email'=>$d['admin_email'],'password_hash'=>Hash::make($d['admin_password']),'role'=>'client_admin','active'=>true]);
  return back()->with('success','Perusahaan dan user portal berhasil dibuat.');
 }
}