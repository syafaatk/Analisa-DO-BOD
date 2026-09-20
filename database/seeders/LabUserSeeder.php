<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\LabUser;
use App\Models\Laboratory;
class LabUserSeeder extends Seeder {
 public function run(): void {
  $lab=Laboratory::firstOrCreate(['code'=>'LAB-DEFAULT'],['name'=>env('LAB_NAME','Default Laboratory'),'active'=>true]);
  $email=env('LAB_ADMIN_EMAIL','admin@lab.local');
  $password=env('LAB_ADMIN_PASSWORD','Admin@12345');
  $u=LabUser::firstOrNew(['email'=>$email]);
  $u->laboratory_id=$lab->id;
  $u->name=env('LAB_ADMIN_NAME','Laboratory Administrator');
  $u->role='admin'; $u->active=true; $u->setPassword($password); $u->save();
 }
}
