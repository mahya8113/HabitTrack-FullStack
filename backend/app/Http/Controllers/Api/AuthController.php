<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
class AuthController extends Controller {
 public function register(Request $r){$d=$r->validate(['name'=>'required|string|max:100','email'=>'required|email|max:255|unique:users,email','password'=>'required|string|min:10|confirmed']);$u=User::create($d);return response()->json(['user'=>$u,'token'=>$u->createToken('habittrack-web')->plainTextToken],201);}
 public function login(Request $r){$d=$r->validate(['email'=>'required|email','password'=>'required|string']);$u=User::where('email',$d['email'])->first();if(!$u||!Hash::check($d['password'],$u->password))throw ValidationException::withMessages(['email'=>['The supplied credentials are invalid.']]);return response()->json(['user'=>$u,'token'=>$u->createToken('habittrack-web')->plainTextToken]);}
 public function me(Request $r){return response()->json(['user'=>$r->user()]);}
 public function updateProfile(Request $r){$d=$r->validate(['name'=>'sometimes|required|string|max:100','email'=>'sometimes|required|email|max:255|unique:users,email,'.$r->user()->id]);$r->user()->fill($d)->save();return response()->json(['user'=>$r->user()->fresh()]);}
 public function logout(Request $r){$r->user()->currentAccessToken()?->delete();return response()->json(['message'=>'Logged out']);}
}
