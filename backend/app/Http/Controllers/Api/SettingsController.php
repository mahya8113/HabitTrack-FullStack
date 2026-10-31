<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
class SettingsController extends Controller {
 public function show(Request $r){return response()->json(['settings'=>$r->user()->settings()->firstOrCreate([],[]) ]);}
 public function update(Request $r){$data=$r->validate(['theme'=>['sometimes',Rule::in(['dark','light','system'])],'accent_color'=>'sometimes|string|max:30','locale'=>'sometimes|string|max:10','timezone'=>'sometimes|timezone','day_start_hour'=>'sometimes|integer|min:0|max:23','notification_preferences'=>'sometimes|array','privacy_preferences'=>'sometimes|array','accessibility_preferences'=>'sometimes|array']);$settings=$r->user()->settings()->firstOrCreate([],[]);$settings->fill($data)->save();return response()->json(['settings'=>$settings->fresh()]);}
 public function changePassword(Request $r){$data=$r->validate(['current_password'=>'required|string','password'=>'required|string|min:10|confirmed']);if(!Hash::check($data['current_password'],$r->user()->password))return response()->json(['message'=>'رمز عبور فعلی نادرست است.'],422);$r->user()->password=$data['password'];$r->user()->save();$r->user()->tokens()->delete();return response()->json(['message'=>'رمز عبور تغییر کرد؛ دوباره وارد شوید.']);}
 public function deleteAccount(Request $r){$r->validate(['password'=>'required|string']);if(!Hash::check($r->password,$r->user()->password))return response()->json(['message'=>'رمز عبور نادرست است.'],422);$u=$r->user();$u->tokens()->delete();$u->delete();return response()->json(['message'=>'حساب کاربری حذف شد.']);}
}
