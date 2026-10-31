<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
class NotificationController extends Controller {
 public function index(Request $r){\App\Support\NotificationSync::sync($r->user());$q=$r->user()->inAppNotifications()->latest();if($r->boolean('unread'))$q->whereNull('read_at');return response()->json(['data'=>$q->paginate(min(100,max(1,(int)$r->input('per_page',20))))]);}
 public function read(Request $r,int $id){$n=$r->user()->inAppNotifications()->findOrFail($id);$n->read_at=now();$n->save();return response()->json(['notification'=>$n]);}
 public function readAll(Request $r){$count=$r->user()->inAppNotifications()->whereNull('read_at')->update(['read_at'=>now()]);return response()->json(['updated'=>$count]);}
 public function destroy(Request $r,int $id){$r->user()->inAppNotifications()->findOrFail($id)->delete();return response()->noContent();}
}
