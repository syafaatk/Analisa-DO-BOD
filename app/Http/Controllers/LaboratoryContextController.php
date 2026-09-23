<?php
namespace App\Http\Controllers;

use App\Models\Laboratory;
use Illuminate\Http\Request;

class LaboratoryContextController extends Controller
{
    public function switch(Request $request, Laboratory $laboratory)
    {
        abort_unless($request->session()->get('lab_user.role') === 'super_admin', 403);
        abort_unless($laboratory->active, 403, 'Laboratorium tidak aktif.');

        $request->session()->put('lab_id', $laboratory->id);
        $request->session()->put('laboratory_name', $laboratory->name);

        return redirect()->route('analysis.index')->with('success', 'Konteks laboratorium aktif: '.$laboratory->name);
    }

    public function clear(Request $request)
    {
        abort_unless($request->session()->get('lab_user.role') === 'super_admin', 403);
        $request->session()->forget(['lab_id', 'laboratory_name']);

        return redirect()->route('superadmin.laboratories')->with('success', 'Kembali ke Platform Super Admin.');
    }
}
