<?php
namespace App\Http\Controllers;
use App\Services\ReportDataService;
class ReportController extends Controller
{
 public function __construct(private ReportDataService $service){}
 public function show(int $id){return view('reports.analysis',['report'=>$this->service->result($id)]);}
}
