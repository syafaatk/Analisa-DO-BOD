<?php
namespace App\Http\Controllers;
use App\Models\Sample;
use Illuminate\Http\Request;
class SampleController extends Controller
{
 public function index(){return response()->json(Sample::latest()->paginate(20));}
 public function store(Request $request){
  $data=$request->validate(['sample_code'=>'required|string|max:100|unique:samples,sample_code','sample_name'=>'nullable|string|max:255','matrix'=>'nullable|string|max:100','received_at'=>'nullable|date','customer'=>'nullable|string|max:255','notes'=>'nullable|string']);
  return response()->json(Sample::create($data),201);
 }
 public function show(Sample $sample){return response()->json($sample->load('analysisRuns'));}
}
