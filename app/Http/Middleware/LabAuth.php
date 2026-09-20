<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
class LabAuth {
 public function handle(Request $request, Closure $next) {
  $user=$request->session()->get('lab_user');
  if(!$user || empty($user['id']) || empty($user['laboratory_id']) || !$user['active']) return redirect()->route('login')->with('error','Silakan login terlebih dahulu.');
  if(!$request->session()->has('lab_id')) $request->session()->put('lab_id',$user['laboratory_id']);
  if((int)$request->session()->get('lab_id') !== (int)$user['laboratory_id']) { $request->session()->forget(['lab_id','lab_user']); return redirect()->route('login')->with('error','Sesi laboratorium tidak valid.'); }
  return $next($request);
 }
}