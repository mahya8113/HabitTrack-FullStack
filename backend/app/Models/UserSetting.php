<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class UserSetting extends Model {protected $fillable=['theme','accent_color','locale','timezone','day_start_hour','notification_preferences','privacy_preferences','accessibility_preferences'];protected function casts():array{return ['notification_preferences'=>'array','privacy_preferences'=>'array','accessibility_preferences'=>'array','day_start_hour'=>'integer'];}}
