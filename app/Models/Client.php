<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Models\Concerns\BelongsToLaboratory;
class Client extends Model { use HasUuids, BelongsToLaboratory; protected $fillable=['laboratory_id','code','name','address','phone','email','active']; public function users(){return $this->hasMany(ClientUser::class);} public function analyses(){return $this->hasMany(AnalysisRun::class);} }