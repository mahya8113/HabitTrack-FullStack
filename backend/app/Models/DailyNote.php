<?php
namespace App\Models; use Illuminate\Database\Eloquent\Model;
class DailyNote extends Model {protected $fillable=['user_id','note_date','content'];protected function casts():array{return ['note_date'=>'date'];}}