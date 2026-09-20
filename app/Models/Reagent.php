<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\BelongsToLaboratory;
class Reagent extends Model {
 use BelongsToLaboratory;
 protected $fillable=['laboratory_id','code','name','lot_number','concentration','unit','expiry_date'];
 protected $casts=['concentration'=>'decimal:8','expiry_date'=>'date'];
}
