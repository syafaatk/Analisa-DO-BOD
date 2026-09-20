<?php
namespace App\Http\Controllers;
use App\Models\ClientUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
class ClientAuthController extends Controller {
 public function showLogin(){return view('client.login');}
 public function login(Request $request){
  $d=$request->validate(['email'=>'required|email','password'=>'required|string']);
  $u=ClientUser::where('email',$d['email'])->where('active',true)->first();
  if(!$u || !Hash::check($d['password'],$u->password_hash)) return back()->withErrors(['email'=>'Email atau password salah.']);
  $request->session()->regenerate(); $request->session()->put('client_user',['id'=>$u->id,'name'=>$u->name,'email'=>$u->email,'client_id'=>$u->client_id,'laboratory_id'=>$u->laboratory_id]);
  return redirect()->route('client.dashboard');
 }
 public function logout(Request $request){$request->session()->forget('client_user');$request->session()->invalidate();$request->session()->regenerateToken();return redirect()->route('client.login');}
}