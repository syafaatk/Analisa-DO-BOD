<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
class LabUser extends Model {
 protected $table='lab_users';
 protected $fillable=['name','email','role','password_hash','active'];
 protected $hidden=['password_hash'];
 protected $casts=['active'=>'boolean'];
 public function setPassword(string $password): void {$this->password_hash=Hash::make($password);}
 public function hasRole(string|array $roles): bool {return in_array($this->role,(array)$roles,true);}
}
