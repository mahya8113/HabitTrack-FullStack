<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class InAppNotification extends Model {protected $table='in_app_notifications';protected $fillable=['user_id','type','title','body','data','scheduled_for','read_at','dedupe_key'];protected function casts():array{return ['data'=>'array','scheduled_for'=>'datetime','read_at'=>'datetime'];}}
