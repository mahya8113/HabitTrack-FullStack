<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Task extends Model {
 protected $fillable=['user_id','goal_id','habit_id','title','description','priority','status','due_at','estimated_minutes','actual_minutes','completed_at'];
 protected function casts(): array {return ['due_at'=>'datetime','completed_at'=>'datetime'];}
 public function user(){return $this->belongsTo(User::class);}
 public function goal(){return $this->belongsTo(Goal::class);}
 public function habit(){return $this->belongsTo(Habit::class);}
}
