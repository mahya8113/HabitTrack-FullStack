<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Habit;
use App\Models\HabitEntry;
use Illuminate\Http\Request;
class HabitEntryController extends Controller {
 public function index(Request $r){return $r->user()->load(['habits.entries'=>fn($q)=>$q->orderByDesc('entry_date')->limit(90)])->habits->pluck('entries')->flatten();}
 public function upsert(Request $r,Habit $habit){abort_unless($habit->user_id===$r->user()->id,404);$d=$r->validate(['entry_date'=>'required|date','status'=>'required|in:completed,partial,missed','value'=>'nullable|numeric|min:0','duration_minutes'=>'nullable|integer|min:0|max:1440','note'=>'nullable|string|max:2000']);$entry=HabitEntry::updateOrCreate(['habit_id'=>$habit->id,'entry_date'=>$d['entry_date']],['user_id'=>$r->user()->id,...$d,'completed_at'=>($d['status']==='completed'?now():null)]);if($entry->status==='completed'){$u=$r->user();\App\Support\Gamification::award($u,'habit:'.$entry->id,'habit_completed',10,['habit_id'=>$habit->id]);\App\Support\Gamification::badge($u,'first_habit','اولین عادت','اولین عادت خود را ثبت کردی');}return response()->json($entry);}
}
