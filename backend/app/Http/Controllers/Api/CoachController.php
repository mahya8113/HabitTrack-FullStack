<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
class CoachController extends Controller {
 public function index(Request $r){$u=$r->user();$habits=$u->habits()->with(['entries'=>fn($q)=>$q->where('entry_date','>=',Carbon::today()->subDays(27)->toDateString())])->get();$items=[];
  foreach($habits as $h){$entries=$h->entries;$window=min(28,max(1,(int)$h->created_at->copy()->startOfDay()->diffInDays(Carbon::today())+1));if($window<7)continue;$done=$entries->where('status','completed')->count();$rate=$done/$window;$missed=max(0,$window-$entries->count());if($missed>=5){$items[]=['user_id'=>$u->id,'habit_id'=>$h->id,'kind'=>'reduce_friction','title'=>'کوچک‌تر کردن عادت: '.$h->name,'reason'=>"در {$window} روز اخیر {$missed} روز برای این عادت ثبت فعالیت ندارید؛ کاهش حجم یا انتخاب زمان مشخص می‌تواند شروع را آسان‌تر کند.",'payload'=>['suggested_minutes'=>5,'window_days'=>$window]];}if($rate>=.65){$items[]=['user_id'=>$u->id,'habit_id'=>$h->id,'kind'=>'reinforce','title'=>'تقویت عادت: '.$h->name,'reason'=>'نرخ ثبت این عادت نسبتاً خوب است؛ حفظ همان زمان و افزودن گام کوچک بعدی می‌تواند پایدارتر باشد.','payload'=>['completion_rate'=>round($rate*100)]];}}
  foreach($items as $item){$u->coachRecommendations()->firstOrCreate(['kind'=>$item['kind'],'habit_id'=>$item['habit_id'],'status'=>'pending'], $item);}
  return response()->json(['data'=>$u->coachRecommendations()->with('habit:id,name')->latest()->limit(50)->get(),'summary'=>['habits_analyzed'=>$habits->count(),'method'=>'rule_based','window_days'=>28]]);
 }
 public function decide(Request $r,int $id){$data=$r->validate(['decision'=>'required|in:accepted,rejected','feedback'=>'nullable|string|max:1000']);$rec=$r->user()->coachRecommendations()->findOrFail($id);if($rec->status!=='pending')return response()->json(['message'=>'این پیشنهاد قبلاً بررسی شده است.'],422);$rec->status=$data['decision'];$rec->outcome=['feedback'=>$data['feedback']??null];$rec->save();return response()->json(['recommendation'=>$rec]);}
 public function evaluate(Request $r,int $id){$rec=$r->user()->coachRecommendations()->findOrFail($id);if(!in_array($rec->status,['accepted','rejected']))return response()->json(['message'=>'ابتدا پیشنهاد را بپذیرید یا رد کنید.'],422);$rec->evaluated_at=now();$rec->status='evaluated';$rec->outcome=array_merge($rec->outcome??[],['evaluation_note'=>$r->validate(['evaluation_note'=>'nullable|string|max:1000'])['evaluation_note']??null]);$rec->save();return response()->json(['recommendation'=>$rec]);}
}
