<?php
namespace App\Http\Controllers;
use App\Services\ReportDataService;
class ReportController extends Controller
{
 public function __construct(private ReportDataService $service){}
 public function show(int $id){return response()->json($this->service->result($id));}
}
