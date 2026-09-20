<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use App\Models\ClientUser;
class ClientAuth {
 public function handle(Request $request,Closure $next){
  $u=$request->session()->get('client_user');
  if(!$u || empty($u['id']) || empty($u['client_id'])) return redirect()->route('client.login');
  $user=ClientUser::find($u['id']);
  if(!$user || !$user->active) { $request->session()->forget('client_user'); return redirect()->route('client.login'); }
  $request->session()->put('client_user',['id'=>$user->id,'name'=>$user->name,'email'=>$user->email,'client_id'=>$user->client_id,'laboratory_id'=>$user->laboratory_id]);
  return $next($request);
 }
}