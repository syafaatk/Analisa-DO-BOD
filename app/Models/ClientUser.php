<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
class ClientUser extends Model { use HasUuids; protected $fillable=['laboratory_id','client_id','name','email','password_hash','role','active']; protected $hidden=['password_hash']; protected $casts=['active'=>'boolean']; public function client(){return $this->belongsTo(Client::class);} }