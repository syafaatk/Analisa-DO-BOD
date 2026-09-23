<?php
namespace App\Http\Middleware;

use App\Models\Laboratory;
use Closure;
use Illuminate\Http\Request;

class LaboratoryContext
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->session()->get('lab_user');

        if (!$user || empty($user['id'])) {
            return redirect()->route('login');
        }

        $labId = $request->session()->get('lab_id');

        if (($user['role'] ?? null) === 'super_admin') {
            if (!$labId) {
                return redirect()->route('superadmin.laboratories')
                    ->with('info', 'Pilih laboratorium terlebih dahulu.');
            }

            $lab = Laboratory::whereKey($labId)->where('active', true)->first();
            if (!$lab) {
                $request->session()->forget(['lab_id', 'laboratory_name']);
                return redirect()->route('superadmin.laboratories')
                    ->with('error', 'Laboratorium yang dipilih tidak aktif atau tidak ditemukan.');
            }

            $request->session()->put('laboratory_name', $lab->name);
            return $next($request);
        }

        if (empty($user['laboratory_id']) || (string) $labId !== (string) $user['laboratory_id']) {
            return redirect()->route('login')->with('error', 'Konteks laboratorium tidak valid.');
        }

        return $next($request);
    }
}
