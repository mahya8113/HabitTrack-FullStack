<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\{AuthController,HabitController,HabitEntryController,GoalController,TaskController,FocusSessionController,DashboardController,AnalyticsController,SettingsController,NotificationController,CoachController,MilestoneController,RewardController,ReportController,DailyNoteController};

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);
    });
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/dashboard', DashboardController::class);
    Route::patch('/auth/profile', [AuthController::class, 'updateProfile']);
    Route::get('/settings', [SettingsController::class, 'show']);
    Route::put('/settings', [SettingsController::class, 'update']);
    Route::put('/settings/password', [SettingsController::class, 'changePassword']);
    Route::delete('/account', [SettingsController::class, 'deleteAccount']);

    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::post('/notifications/read-all', [NotificationController::class, 'readAll']);
    Route::patch('/notifications/{id}/read', [NotificationController::class, 'read']);
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy']);

    Route::get('/coach/recommendations', [CoachController::class, 'index']);
    Route::post('/coach/recommendations/{id}/decision', [CoachController::class, 'decide']);
    Route::post('/coach/recommendations/{id}/evaluate', [CoachController::class, 'evaluate']);

    Route::get('/rewards', [RewardController::class, 'index']);
    Route::post('/rewards/award', [RewardController::class, 'award']);
    Route::get('/reports/summary', [ReportController::class, 'summary']);
    Route::get('/reports/habits.csv', [ReportController::class, 'csv']);
    Route::get('/backup/export', [ReportController::class, 'backup']);
    Route::get('/daily-note', [DailyNoteController::class, 'show']);
    Route::put('/daily-note', [DailyNoteController::class, 'save']);

    Route::get('/analytics', AnalyticsController::class);
    Route::apiResource('habits', HabitController::class);
    Route::put('/habits/{habit}/entries', [HabitEntryController::class, 'upsert']);
    Route::get('/entries', [HabitEntryController::class, 'index']);
    Route::apiResource('goals', GoalController::class)->except(['show']);
    Route::get('/goals/{goal}/milestones', [MilestoneController::class, 'index']);
    Route::post('/goals/{goal}/milestones', [MilestoneController::class, 'store']);
    Route::patch('/goals/{goal}/milestones/{milestone}', [MilestoneController::class, 'update']);
    Route::delete('/goals/{goal}/milestones/{milestone}', [MilestoneController::class, 'destroy']);
    Route::apiResource('tasks', TaskController::class)->except(['show']);
    Route::get('/focus-sessions', [FocusSessionController::class, 'index']);
    Route::post('/focus-sessions', [FocusSessionController::class, 'store']);
    Route::post('/focus-sessions/{session}/finish', [FocusSessionController::class, 'finish']);
});
