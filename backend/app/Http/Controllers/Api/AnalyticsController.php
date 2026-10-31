<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
class AnalyticsController extends Controller
{
 public function __invoke(Request $r)
 {
  $d=$r->validate(['from'=>'nullable|date','to'=>'nullable|date|after_or_equal:from','category'=>'nullable|string|max:80']);
  $from=Carbon::parse($d['from']??today()->subDays(29))->startOfDay();
  $to=Carbon::parse($d['to']??today())->endOfDay();
  $query=$r->user()->habits();
  if(!empty($d['category'])) $query->where('category',$d['category']);
  $habits=$query->with(['entries'=>fn($q)=>$q->whereBetween('entry_date',[$from->toDateString(),$to->toDateString()])])->get();
  $entries=$habits->flatMap->entries;
  $days=[]; $cursor=$from->copy();
  while($cursor->lte($to)&&count($days)<400){
   $date=$cursor->toDateString();
   $dayEntries=$entries->filter(fn($e)=>$e->entry_date->toDateString()===$date);
   $days[]=['date'=>$date,'completed'=>$dayEntries->where('status','completed')->count(),'partial'=>$dayEntries->where('status','partial')->count(),'missed'=>$dayEntries->where('status','missed')->count()];
   $cursor->addDay();
  }
  $completed=$entries->where('status','completed')->count();
  $perHabit=$habits->map(function($h){$done=$h->entries->where('status','completed')->count();$count=$h->entries->count();return ['id'=>$h->id,'name'=>$h->name,'category'=>$h->category,'completed'=>$done,'entries'=>$count,'completion_rate'=>$count?round($done/$count*100,1):0];})->values();
  $weekday=[];
  foreach($entries as $e){$i=(int)$e->entry_date->dayOfWeek;if(!isset($weekday[$i]))$weekday[$i]=['weekday'=>$i,'completed'=>0,'entries'=>0,'rate'=>0];$weekday[$i]['entries']++;if($e->status==='completed')$weekday[$i]['completed']++;}
  foreach($weekday as &$v){$v['rate']=$v['entries']?round($v['completed']/$v['entries']*100,1):0;}unset($v);
  usort($weekday,fn($a,$b)=>$b['rate']<=>$a['rate']);
  $previousFrom=$from->copy()->subDays(max(1,$from->diffInDays($to)+1));$previousTo=$from->copy()->subDay();
  $previousCompleted=$r->user()->habits()->withCount(['entries'=>fn($q)=>$q->whereBetween('entry_date',[$previousFrom->toDateString(),$previousTo->toDateString()])->where('status','completed')])->get()->sum('entries_count');
  return response()->json([
   'range'=>['from'=>$from->toDateString(),'to'=>$to->toDateString()],
   'last_7_days'=>array_slice($days,-7),'days'=>$days,'total_completed'=>$completed,
   'completion_rate'=>$entries->count()?round($completed/$entries->count()*100,1):0,
   'focus_minutes'=>(int)$r->user()->focusSessions()->whereBetween('started_at',[$from,$to])->sum('actual_minutes'),
   'goals'=>$r->user()->goals()->select('id','title','progress','status','due_at')->get(),
   'habits'=>$perHabit,'best_weekday'=>$weekday[0]??null,
   'comparison'=>['previous_completed'=>(int)$previousCompleted]
  ]);
 }
}
