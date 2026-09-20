<?php
namespace App\Http\Controllers;
use App\Models\Sample;
use App\Models\Reagent;
use App\Models\Instrument;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
class MasterController extends Controller {
 public function samples(){return view('masters.samples.index',['samples'=>Sample::latest()->paginate(15)]);}
 public function storeSample(Request $r){$data=$r->validate(['sample_code'=>['required','string','max:100',Rule::unique('samples','sample_code')->where(fn($q)=>$q->where('laboratory_id',session('lab_id')))],'sample_name'=>'nullable|string|max:255','matrix'=>'nullable|string|max:100','received_at'=>'nullable|date','customer'=>'nullable|string|max:255','notes'=>'nullable|string']); Sample::create($data); return back()->with('success','Sample berhasil disimpan.');}
 public function deleteSample(Sample $sample){$sample->delete(); return back()->with('success','Sample dihapus.');}
 public function reagents(){return view('masters.reagents.index',['reagents'=>Reagent::latest()->paginate(15)]);}
 public function storeReagent(Request $r){$data=$r->validate(['code'=>['required','string','max:100',Rule::unique('reagents','code')->where(fn($q)=>$q->where('laboratory_id',session('lab_id')))],'name'=>'required|string|max:255','lot_number'=>'nullable|string|max:100','concentration'=>'nullable|numeric','unit'=>'nullable|string|max:50','expiry_date'=>'nullable|date']); Reagent::create($data); return back()->with('success','Reagen berhasil disimpan.');}
 public function deleteReagent(Reagent $reagent){$reagent->delete(); return back()->with('success','Reagen dihapus.');}
 public function instruments(){return view('masters.instruments.index',['instruments'=>Instrument::latest()->paginate(15)]);}
 public function storeInstrument(Request $r){$data=$r->validate(['code'=>['required','string','max:100',Rule::unique('instruments','code')->where(fn($q)=>$q->where('laboratory_id',session('lab_id')))],'name'=>'required|string|max:255','serial_number'=>'nullable|string|max:100','calibration_due'=>'nullable|date','status'=>'required|in:active,maintenance,expired']); Instrument::create($data); return back()->with('success','Instrumen berhasil disimpan.');}
 public function deleteInstrument(Instrument $instrument){$instrument->delete(); return back()->with('success','Instrumen dihapus.');}
}
