<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
class Laboratory extends Model {
 use HasUuids;
 protected $fillable=['code','name','active','address','phone','email'];
 protected $casts=['active'=>'boolean'];
 public function users(){return $this->hasMany(LabUser::class,'laboratory_id');}
}