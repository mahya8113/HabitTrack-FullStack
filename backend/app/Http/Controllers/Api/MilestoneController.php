<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;use App\Models\Goal;use App\Models\GoalMilestone;use Illuminate\Http\Request;
class MilestoneController extends Controller {
 public function index(Request $r, Goal $goal){abort_unless($goal->user_id===$r->user()->id,404);return response()->json(['data'=>$goal->milestones()->get()]);}
 public function store(Request $r, Goal $goal){abort_unless($goal->user_id===$r->user()->id,404);$d=$r->validate(['title'=>'required|string|max:180','due_at'=>'nullable|date','position'=>'nullable|integer|min:0']);$m=$goal->milestones()->create($d+['user_id'=>$r->user()->id]);return response()->json(['milestone'=>$m],201);}
 public function update(Request $r, Goal $goal, GoalMilestone $milestone){abort_unless($goal->user_id===$r->user()->id&&$milestone->goal_id===$goal->id,404);$d=$r->validate(['title'=>'sometimes|required|string|max:180','due_at'=>'nullable|date','position'=>'sometimes|integer|min:0','is_completed'=>'sometimes|boolean']);if(($d['is_completed']??false)&&!$milestone->is_completed)$d['completed_at']=now();if(array_key_exists('is_completed',$d)&&!$d['is_completed'])$d['completed_at']=null;$milestone->update($d);return response()->json(['milestone'=>$milestone->fresh()]);}
 public function destroy(Request $r,Goal $goal,GoalMilestone $milestone){abort_unless($goal->user_id===$r->user()->id&&$milestone->goal_id===$goal->id,404);$milestone->delete();return response()->noContent();}
}