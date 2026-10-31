<?php
namespace App\Models; use Illuminate\Database\Eloquent\Model;
class UserReward extends Model {protected $fillable=['user_id','xp_total','level'];protected function casts():array{return ['xp_total'=>'integer','level'=>'integer'];}}