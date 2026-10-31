<?php

namespace App\Support;

use App\Models\User;

/**
 * Creates due-task and habit-reminder notifications on demand (no scheduler needed).
 * Respects the user's notification preferences and never creates the same notification twice per day.
 */
class NotificationSync
{
    public static function sync(User $user): void
    {
        $now = now();
        $today = $now->toDateString();
        $prefs = $user->settings?->notification_preferences ?? [];
        $enabled = fn (string $key) => ($prefs[$key] ?? true) !== false;

        if ($enabled('task_deadlines')) {
            $user->tasks()
                ->whereIn('status', ['pending', 'in_progress'])
                ->whereNotNull('due_at')
                ->where('due_at', '<=', $now->copy()->endOfDay())
                ->get()
                ->each(function ($task) use ($user, $now, $today) {
                    $body = $task->due_at->lt($now) ? 'مهلت این وظیفه گذشته است.' : 'امروز مهلت دارد.';
                    Gamification::notify($user, 'task_due', 'مهلت وظیفه: '.$task->title, $body, "task-due:{$task->id}:{$today}", ['task_id' => $task->id]);
                });
        }

        if ($enabled('habit_reminders')) {
            $time = $now->format('H:i:s');
            $user->habits()
                ->where('is_active', true)
                ->whereNotNull('reminder_time')
                ->where('reminder_time', '<=', $time)
                ->whereDoesntHave('entries', fn ($q) => $q->whereDate('entry_date', $today)->where('status', 'completed'))
                ->get()
                ->each(function ($habit) use ($user, $today) {
                    Gamification::notify($user, 'habit_reminder', 'یادآوری عادت: '.$habit->name, 'امروز هنوز ثبت نشده است.', "habit-reminder:{$habit->id}:{$today}", ['habit_id' => $habit->id]);
                });
        }
    }
}
