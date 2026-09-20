<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
class LabAuth {
 public function handle(Request $request, Closure $next) {
  $user=$request->session()->get('lab_user');
  if(!$user || empty($user['id']) || !$user['active']) return redirect()->route('login')->with('error','Silakan login terlebih dahulu.');
  if(($user['role']??null)==='super_admin') { $request->session()->forget('lab_id'); return $next($request); }
  if(empty($user['laboratory_id'])) return redirect()->route('login')->with('error','Akun belum terhubung ke laboratorium.');
  if(!$request->session()->has('lab_id')) $request->session()->put('lab_id',$user['laboratory_id']);
  if((string)$request->session()->get('lab_id') !== (string)$user['laboratory_id']) { $request->session()->forget(['lab_id','lab_user']); return redirect()->route('login')->with('error','Sesi laboratorium tidak valid.'); }
  return $next($request);
 }
}