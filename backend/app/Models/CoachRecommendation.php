<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class CoachRecommendation extends Model {protected $fillable=['user_id','habit_id','kind','title','reason','payload','status','evaluated_at','outcome'];protected function casts():array{return ['payload'=>'array','outcome'=>'array','evaluated_at'=>'datetime'];}public function habit(){return $this->belongsTo(Habit::class);}}
