<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
class LabAuth {
 public function handle(Request $request, Closure $next) {
  $user=$request->session()->get('lab_user');
  if(!$user || empty($user['id']) || !$user['active']) return redirect()->route('login')->with('error','Silakan login terlebih dahulu.');
  return $next($request);
 }
}
