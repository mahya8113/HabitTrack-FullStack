<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;use App\Models\Goal;use Illuminate\Http\Request;use Illuminate\Validation\Rule;
class GoalController extends Controller {
 public function index(Request $r){return $r->user()->goals()->withCount('tasks')->latest()->paginate(30);}
 public function store(Request $r){$d=$r->validate(['title'=>'required|string|max:180','description'=>'nullable|string|max:3000','priority'=>['nullable',Rule::in(['low','medium','high'])],'status'=>['nullable',Rule::in(['active','completed','paused','archived'])],'progress'=>'nullable|integer|min:0|max:100','starts_at'=>'nullable|date','due_at'=>'nullable|date']);if(($d['status']??null)==='completed')$d['completed_at']=now();return response()->json($r->user()->goals()->create($d),201);}
 public function update(Request $r,Goal $goal){abort_unless($goal->user_id===$r->user()->id,404);$d=$r->validate(['title'=>'sometimes|required|string|max:180','description'=>'nullable|string|max:3000','priority'=>['nullable',Rule::in(['low','medium','high'])],'status'=>['nullable',Rule::in(['active','completed','paused','archived'])],'progress'=>'nullable|integer|min:0|max:100','starts_at'=>'nullable|date','due_at'=>'nullable|date']);if(array_key_exists('status',$d))$d['completed_at']=$d['status']==='completed'?($goal->completed_at??now()):null;$goal->update($d);if($goal->wasChanged('status')&&$goal->status==='completed'){$u=$r->user();\App\Support\Gamification::award($u,'goal:'.$goal->id,'goal_completed',50);\App\Support\Gamification::badge($u,'first_goal','اولین هدف','اولین هدفت را به پایان رساندی');}return $goal->fresh();}
 public function destroy(Request $r,Goal $goal){abort_unless($goal->user_id===$r->user()->id,404);$goal->delete();return response()->noContent();}
}
