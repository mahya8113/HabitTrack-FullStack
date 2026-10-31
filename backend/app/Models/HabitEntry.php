<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class HabitEntry extends Model {
 protected $fillable=['habit_id','user_id','entry_date','status','value','duration_minutes','note','completed_at'];
 protected function casts(): array {return ['entry_date'=>'date','completed_at'=>'datetime','value'=>'float'];}
 public function habit(){return $this->belongsTo(Habit::class);}
 // Send the calendar date as YYYY-MM-DD so clients can compare it with a plain local date.
 protected function serializeDate(\DateTimeInterface $date): string {return $date->format('Y-m-d');}
}
