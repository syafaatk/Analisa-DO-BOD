<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Instrument extends Model {
 protected $fillable=['code','name','serial_number','calibration_due','status'];
 protected $casts=['calibration_due'=>'date'];
}
