<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Habit;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
class HabitController extends Controller {
 public function index(Request $r){return $r->user()->habits()->with(['entries'=>fn($q)=>$q->whereDate('entry_date',today())])->latest()->paginate(30);}
 public function store(Request $r){$d=$r->validate(['name'=>'required|string|max:120','description'=>'nullable|string|max:3000','category'=>'nullable|string|max:80','icon'=>'nullable|string|max:40','color'=>'nullable|string|max:20','frequency'=>['nullable',Rule::in(['daily','weekly','custom'])],'days_of_week'=>'nullable|array','days_of_week.*'=>'integer|min:0|max:6','target_value'=>'nullable|numeric|min:0.01|max:1000000','target_unit'=>'nullable|string|max:30','duration_minutes'=>'nullable|integer|min:1|max:1440','reminder_time'=>'nullable|date_format:H:i','is_active'=>'sometimes|boolean','started_at'=>'nullable|date']);return response()->json($r->user()->habits()->create($d),201);}
 public function show(Request $r,Habit $habit){$this->own($r,$habit);return $habit->load('entries');}
 public function update(Request $r,Habit $habit){$this->own($r,$habit);$d=$r->validate(['name'=>'sometimes|required|string|max:120','description'=>'nullable|string|max:3000','category'=>'nullable|string|max:80','icon'=>'nullable|string|max:40','color'=>'nullable|string|max:20','frequency'=>['nullable',Rule::in(['daily','weekly','custom'])],'days_of_week'=>'nullable|array','days_of_week.*'=>'integer|min:0|max:6','target_value'=>'nullable|numeric|min:0.01|max:1000000','target_unit'=>'nullable|string|max:30','duration_minutes'=>'nullable|integer|min:1|max:1440','reminder_time'=>'nullable|date_format:H:i','is_active'=>'sometimes|boolean','started_at'=>'nullable|date']);$habit->update($d);return $habit->fresh();}
 public function destroy(Request $r,Habit $habit){$this->own($r,$habit);$habit->delete();return response()->noContent();}
 private function own(Request $r,Habit $h):void{abort_unless($h->user_id===$r->user()->id,404);}
}
