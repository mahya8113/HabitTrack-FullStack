<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Habit extends Model {
 protected $fillable=['user_id','name','description','category','icon','color','frequency','days_of_week','target_value','target_unit','duration_minutes','reminder_time','is_active','started_at'];
 protected function casts(): array {return ['days_of_week'=>'array','is_active'=>'boolean','started_at'=>'date','target_value'=>'float'];}
 public function user(){return $this->belongsTo(User::class);}
 public function entries(){return $this->hasMany(HabitEntry::class);}
}
