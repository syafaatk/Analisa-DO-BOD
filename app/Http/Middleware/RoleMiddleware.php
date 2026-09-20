<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
class RoleMiddleware {
 public function handle(Request $request, Closure $next, ...$roles) {
  $user=$request->session()->get('lab_user');
  if(!$user || !in_array($user['role']??'', $roles, true)) abort(403,'Anda tidak memiliki akses ke menu ini.');
  return $next($request);
 }
}
