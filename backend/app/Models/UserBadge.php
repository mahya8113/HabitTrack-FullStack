<?php
namespace App\Models; use Illuminate\Database\Eloquent\Model;
class UserBadge extends Model {protected $fillable=['user_id','badge_key','title','description','earned_at'];protected function casts():array{return ['earned_at'=>'datetime'];}}