<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;use Illuminate\Http\Request;use App\Models\FocusSession;
class FocusSessionController extends Controller {
 public function index(Request $r){return $r->user()->focusSessions()->latest()->paginate(30);}
 public function store(Request $r){$d=$r->validate(['task_id'=>'nullable|integer|exists:tasks,id','habit_id'=>'nullable|integer|exists:habits,id','started_at'=>'nullable|date','planned_minutes'=>'required|integer|min:1|max:600','note'=>'nullable|string|max:2000']);if(!empty($d['task_id']))abort_unless($r->user()->tasks()->whereKey($d['task_id'])->exists(),422);if(!empty($d['habit_id']))abort_unless($r->user()->habits()->whereKey($d['habit_id'])->exists(),422);return response()->json($r->user()->focusSessions()->create([...$d,'started_at'=>$d['started_at']??now(),'status'=>'running']),201);}
 public function finish(Request $r,FocusSession $session){abort_unless($session->user_id===$r->user()->id,404);$d=$r->validate(['actual_minutes'=>'required|integer|min:0|max:600','status'=>'nullable|in:completed,cancelled']);$session->update([...$d,'ended_at'=>now(),'status'=>$d['status']??'completed']);$s=$session->fresh();if($s->status==='completed'&&$s->actual_minutes>0){$u=$r->user();\App\Support\Gamification::award($u,'focus:'.$s->id,'focus_completed',min(50,(int)$s->actual_minutes));\App\Support\Gamification::badge($u,'first_focus','اولین تمرکز','اولین جلسه تمرکز را کامل کردی');}return $s;}
}
