<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Reagent extends Model {
 protected $fillable=['code','name','lot_number','concentration','unit','expiry_date'];
 protected $casts=['concentration'=>'decimal:8','expiry_date'=>'date'];
}
