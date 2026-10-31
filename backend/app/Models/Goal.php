<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Goal extends Model {
 protected $fillable=['user_id','title','description','priority','status','progress','starts_at','due_at','completed_at'];
 protected function casts(): array {return ['starts_at'=>'date','due_at'=>'date','completed_at'=>'datetime','progress'=>'integer'];}
 public function user(){return $this->belongsTo(User::class);}
 public function tasks(){return $this->hasMany(Task::class);}
 public function milestones(){return $this->hasMany(GoalMilestone::class)->orderBy('position');}
}
