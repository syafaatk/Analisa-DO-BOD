<?php
namespace App\Http\Controllers;
use App\Models\LabUser;
use Illuminate\Http\Request;
class UserManagementController extends Controller {
 public function index(){return view('admin.users.index',['users'=>LabUser::latest()->paginate(20)]);}
 public function store(Request $r){
  $data=$r->validate(['name'=>'required|string|max:255','email'=>'required|email|max:255|unique:lab_users,email,NULL,id,laboratory_id,'.session('lab_id'),'role'=>'required|in:admin,supervisor,analyst,viewer','password'=>'required|string|min:8','active'=>'nullable|boolean']);
  $u=new LabUser(['name'=>$data['name'],'email'=>$data['email'],'role'=>$data['role'],'active'=>$r->boolean('active',true)]);$u->setPassword($data['password']);$u->save();
  return back()->with('success','User berhasil dibuat.');
 }
 public function update(Request $r,LabUser $user){
  $data=$r->validate(['name'=>'required|string|max:255','email'=>'required|email|max:255|unique:lab_users,email,'.$user->id,'role'=>'required|in:admin,supervisor,analyst,viewer','password'=>'nullable|string|min:8','active'=>'required|boolean']);
  $user->fill(['name'=>$data['name'],'email'=>$data['email'],'role'=>$data['role'],'active'=>$data['active']]);
  if(!empty($data['password'])) $user->setPassword($data['password']); $user->save();
  return back()->with('success','User berhasil diperbarui.');
 }
 public function destroy(Request $r,LabUser $user){
  if((int)$r->session()->get('lab_user.id')===(int)$user->id) return back()->with('error','User yang sedang login tidak dapat dihapus.');
  $user->delete(); return back()->with('success','User dihapus.');
 }
}
