<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void {
  Schema::create('user_settings', function(Blueprint $t){$t->id();$t->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();$t->string('theme')->default('dark');$t->string('accent_color')->default('violet');$t->string('locale')->default('fa');$t->string('timezone')->default('Asia/Tehran');$t->unsignedTinyInteger('day_start_hour')->default(0);$t->json('notification_preferences')->nullable();$t->json('privacy_preferences')->nullable();$t->json('accessibility_preferences')->nullable();$t->timestamps();});
  Schema::create('in_app_notifications', function(Blueprint $t){$t->id();$t->foreignId('user_id')->constrained()->cascadeOnDelete();$t->string('type',80);$t->string('title');$t->text('body')->nullable();$t->json('data')->nullable();$t->timestamp('scheduled_for')->nullable();$t->timestamp('read_at')->nullable();$t->string('dedupe_key')->nullable();$t->timestamps();$t->unique(['user_id','dedupe_key']);$t->index(['user_id','read_at','created_at']);});
  Schema::create('coach_recommendations', function(Blueprint $t){$t->id();$t->foreignId('user_id')->constrained()->cascadeOnDelete();$t->foreignId('habit_id')->nullable()->constrained()->nullOnDelete();$t->string('kind',80);$t->string('title');$t->text('reason');$t->json('payload')->nullable();$t->string('status')->default('pending');$t->timestamp('evaluated_at')->nullable();$t->json('outcome')->nullable();$t->timestamps();$t->index(['user_id','status','created_at']);});
  Schema::create('focus_missions', function(Blueprint $t){$t->id();$t->foreignId('user_id')->constrained()->cascadeOnDelete();$t->string('period');$t->string('key');$t->string('title');$t->unsignedInteger('target')->default(1);$t->unsignedInteger('progress')->default(0);$t->unsignedInteger('xp_reward')->default(10);$t->date('starts_on');$t->date('ends_on');$t->timestamps();$t->unique(['user_id','period','key','starts_on']);});
 }
 public function down(): void {Schema::dropIfExists('focus_missions');Schema::dropIfExists('coach_recommendations');Schema::dropIfExists('in_app_notifications');Schema::dropIfExists('user_settings');}
};
