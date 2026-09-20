<?php
namespace App\Models\Concerns;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
trait BelongsToLaboratory {
 protected static function bootBelongsToLaboratory(): void {
  static::addGlobalScope('laboratory', function(Builder $builder){
   $labId=session('lab_id');
   if($labId) $builder->where($builder->getModel()->getTable().'.laboratory_id',$labId);
  });
  static::creating(function(Model $model){
   if(!$model->laboratory_id && session()->has('lab_id')) $model->laboratory_id=session('lab_id');
  });
 }
 public function laboratory(){return $this->belongsTo(\App\Models\Laboratory::class,'laboratory_id');}
}