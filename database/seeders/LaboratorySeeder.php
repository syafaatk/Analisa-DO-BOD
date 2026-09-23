<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Laboratory;
class LaboratorySeeder extends Seeder {
 public function run(): void { Laboratory::firstOrCreate(['code'=>'LAB-DEFAULT'],['name'=>env('LAB_NAME','Default Laboratory'),'active'=>true]); }
}