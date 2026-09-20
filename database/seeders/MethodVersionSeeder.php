<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class MethodVersionSeeder extends Seeder
{
 public function run(): void {
  DB::table('method_versions')->upsert([
   ['code'=>'DO-SNI-0698914-2004','name'=>'Dissolved Oxygen — Titration','standard'=>'SNI 06-6989.14-2004','version'=>'2004','active'=>true,'notes'=>'Metode titrasi/yodometri modifikasi azida.','created_at'=>now(),'updated_at'=>now()],
   ['code'=>'BOD-SNI-0698972-2009','name'=>'Biochemical Oxygen Demand 5 days','standard'=>'SNI 6989.72:2009','version'=>'2009','active'=>true,'notes'=>'Metode BOD5.','created_at'=>now(),'updated_at'=>now()]
  ],['code'],['name','standard','version','active','notes','updated_at']);
 }
}
