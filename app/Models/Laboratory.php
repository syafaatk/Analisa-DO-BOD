<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Laboratory extends Model {
 protected $fillable=['code','name','active','address','phone','email'];
 protected $casts=['active'=>'boolean'];
 public function users(){return $this->hasMany(LabUser::class,'laboratory_id');}
}