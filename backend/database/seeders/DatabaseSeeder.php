<?php
namespace Database\Seeders;
use App\Models\User;use Illuminate\Database\Seeder;use Illuminate\Support\Facades\Hash;
class DatabaseSeeder extends Seeder {
 public function run(): void {
  $u=User::firstOrCreate(['email'=>'demo@habittrack.local'],['name'=>'Demo User','password'=>Hash::make('HabitTrackDemo!234')]);
  if($u->habits()->count()===0){foreach([['Read Quran','Mindfulness','#2dd4bf','book-open'],['Coding Practice','Learning','#8b5cf6','code'],['Exercise','Health','#fb7185','activity'],['Drink Water','Health','#38bdf8','droplet'],['Learn English','Learning','#f59e0b','languages']] as [$name,$category,$color,$icon]){$u->habits()->create(['name'=>$name,'category'=>$category,'color'=>$color,'icon'=>$icon,'frequency'=>'daily','target_value'=>1,'target_unit'=>'times','started_at'=>now()->toDateString()]);}}
  if($u->goals()->count()===0){$u->goals()->create(['title'=>'Build a strong portfolio','description'=>'Complete and document three production-style projects','priority'=>'high','progress'=>45,'status'=>'active','due_at'=>now()->addMonth()->toDateString()]);}
  if($u->tasks()->count()===0){$u->tasks()->create(['title'=>'Review today’s plan','priority'=>'medium','status'=>'pending','estimated_minutes'=>15]);}
 }
}
