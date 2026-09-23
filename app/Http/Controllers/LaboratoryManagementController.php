<?php
namespace App\Http\Controllers;
use App\Models\Laboratory;
use App\Models\LabUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class LaboratoryManagementController extends Controller {
 public function index(){return view('superadmin.laboratories.index',['laboratories'=>Laboratory::withCount('users')->latest()->paginate(20)]);}
 public function store(Request $r){
  $data=$r->validate(['code'=>'required|string|max:50|unique:laboratories,code','name'=>'required|string|max:255','address'=>'nullable|string','phone'=>'nullable|string|max:50','email'=>'nullable|email|max:255','admin_name'=>'required|string|max:255','admin_email'=>'required|email|max:255','admin_password'=>'required|string|min:8']);
  $lab=DB::transaction(function() use($data){$lab=Laboratory::create(['code'=>$data['code'],'name'=>$data['name'],'address'=>$data['address']??null,'phone'=>$data['phone']??null,'email'=>$data['email']??null,'active'=>true]);$u=new LabUser(['laboratory_id'=>$lab->id,'name'=>$data['admin_name'],'email'=>$data['admin_email'],'role'=>'admin','active'=>true]);$u->setPassword($data['admin_password']);$u->save();return $lab;});
  return back()->with('success',"Laboratorium {$lab->name} berhasil dibuat beserta adminnya.");
 }
 public function update(Request $r,Laboratory $laboratory){
  $data=$r->validate(['code'=>'required|string|max:50|unique:laboratories,code,'.$laboratory->id,'name'=>'required|string|max:255','address'=>'nullable|string','phone'=>'nullable|string|max:50','email'=>'nullable|email|max:255','active'=>'required|boolean']);
  $laboratory->update($data);return back()->with('success','Laboratorium diperbarui.');
 }
}