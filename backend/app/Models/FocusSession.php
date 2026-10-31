<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class FocusSession extends Model {
 protected $fillable=['user_id','task_id','habit_id','started_at','ended_at','planned_minutes','actual_minutes','status','note'];
 protected function casts(): array {return ['started_at'=>'datetime','ended_at'=>'datetime'];}
 public function user(){return $this->belongsTo(User::class);}
}
