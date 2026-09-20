<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class UncertaintyModel extends Model {
 protected $fillable=['code','parameter','model_name','coverage_factor','unit','active','notes'];
 public function components(){return $this->hasMany(UncertaintyComponent::class);}
}
