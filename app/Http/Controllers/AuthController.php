<?php
namespace App\Http\Controllers;
use App\Models\LabUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
class AuthController extends Controller {
 public function showLogin(){if(session()->has('lab_user')) return redirect()->route('analysis.index'); return view('auth.login');}
 public function login(Request $r){
  $data=$r->validate(['email'=>'required|email','password'=>'required|string']);
  $u=LabUser::where('email',$data['email'])->first();
  if(!$u || !$u->active || !$u->password_hash || !Hash::check($data['password'],$u->password_hash)) return back()->withInput($r->only('email'))->with('error','Email/password salah atau akun tidak aktif.');
  $r->session()->regenerate();
  $r->session()->put('lab_user',['id'=>$u->id,'name'=>$u->name,'email'=>$u->email,'role'=>$u->role,'active'=>$u->active,'laboratory_id'=>$u->laboratory_id,'laboratory_name'=>$u->laboratory?->name]);
  return redirect()->intended(route('analysis.index'));
 }
 public function logout(Request $r){$r->session()->forget('lab_user');$r->session()->invalidate();$r->session()->regenerateToken();return redirect()->route('login');}
}
