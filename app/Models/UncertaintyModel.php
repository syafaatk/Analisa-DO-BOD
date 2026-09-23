<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Models\Concerns\BelongsToLaboratory;
class UncertaintyModel extends Model {
 use HasUuids;
 use BelongsToLaboratory;
 protected $fillable=['laboratory_id','code','parameter','model_name','coverage_factor','unit','active','notes'];
 public function components(){return $this->hasMany(UncertaintyComponent::class);}
}
