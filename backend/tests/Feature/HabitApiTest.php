<?php
namespace Tests\Feature;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
class HabitApiTest extends TestCase {
 use RefreshDatabase;
 public function test_authenticated_user_can_create_and_list_habits(): void {
  $user=User::factory()->create();
  $response=$this->actingAs($user)->postJson('/api/habits',['name'=>'Read for 10 minutes','frequency'=>'daily','category'=>'Learning']);
  $response->assertCreated()->assertJsonPath('name','Read for 10 minutes');
  $this->actingAs($user)->getJson('/api/habits')->assertOk()->assertJsonFragment(['name'=>'Read for 10 minutes']);
 }
 public function test_user_cannot_view_another_users_habit(): void {
  $one=User::factory()->create();$two=User::factory()->create();
  $habit=$one->habits()->create(['name'=>'Private habit']);
  $this->actingAs($two)->getJson('/api/habits/'.$habit->id)->assertNotFound();
 }
}
