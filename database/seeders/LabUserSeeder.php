<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\LabUser;
class LabUserSeeder extends Seeder {
 public function run(): void {
  $email=env('LAB_ADMIN_EMAIL','admin@lab.local');
  $password=env('LAB_ADMIN_PASSWORD','Admin@12345');
  $u=LabUser::firstOrNew(['email'=>$email]);
  $u->name=env('LAB_ADMIN_NAME','Laboratory Administrator');
  $u->role='admin'; $u->active=true; $u->setPassword($password); $u->save();
 }
}
