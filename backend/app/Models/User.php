<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
class User extends Authenticatable {
 use HasApiTokens, HasFactory, Notifiable;
 protected $fillable=['name','email','password'];
 protected $hidden=['password','remember_token'];
 protected function casts(): array { return ['email_verified_at'=>'datetime','password'=>'hashed']; }
 public function habits(){return $this->hasMany(Habit::class);}
 public function goals(){return $this->hasMany(Goal::class);}
 public function tasks(){return $this->hasMany(Task::class);}
 public function focusSessions(){return $this->hasMany(FocusSession::class);}
 public function settings(){return $this->hasOne(UserSetting::class);}
 public function inAppNotifications(){return $this->hasMany(InAppNotification::class);}
 public function coachRecommendations(){return $this->hasMany(CoachRecommendation::class);}
 public function dailyNotes(){return $this->hasMany(DailyNote::class);}
 public function reward(){return $this->hasOne(UserReward::class);}
 public function xpEvents(){return $this->hasMany(XpEvent::class);}
 public function badges(){return $this->hasMany(UserBadge::class);}
 public function milestones(){return $this->hasMany(GoalMilestone::class);}
}
