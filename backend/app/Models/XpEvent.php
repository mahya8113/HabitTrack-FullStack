<?php
namespace App\Models; use Illuminate\Database\Eloquent\Model;
class XpEvent extends Model {protected $fillable=['user_id','event_key','event_type','xp','metadata'];protected function casts():array{return ['xp'=>'integer','metadata'=>'array'];}}