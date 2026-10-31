<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HabitEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function __invoke(Request $r)
    {
        $u = $r->user();
        $today = Carbon::today();

        $habits = $u->habits()
            ->where('is_active', true)
            ->with(['entries' => fn ($q) => $q->whereDate('entry_date', $today)])
            ->get();

        $completed = $habits->filter(
            fn ($h) => $h->entries->contains(fn ($e) => $e->status === 'completed')
        )->count();

        $tasks = $u->tasks();
        $focus = $u->focusSessions()->whereDate('started_at', $today);

        $streak = $this->streakDays($u->id, $today);
        if ($streak >= 7) {
            \App\Support\Gamification::badge($u, 'streak_7', 'استمرار ۷ روزه', '۷ روز پشت‌سرهم عادت ثبت کردی');
        }

        return response()->json([
            'date' => $today->toDateString(),
            'habits' => [
                'active' => $habits->count(),
                'completed' => $completed,
                'completion_rate' => $habits->count() ? round($completed / $habits->count() * 100) : 0,
                'items' => $habits,
            ],
            'streak_days' => $streak,
            'week' => $this->lastSevenDays($u->id, $today),
            'tasks' => [
                'pending' => (clone $tasks)->where('status', 'pending')->count(),
                'completed_today' => (clone $tasks)->where('status', 'completed')->whereDate('completed_at', $today)->count(),
            ],
            'goals' => [
                'active' => $u->goals()->where('status', 'active')->count(),
                'completed' => $u->goals()->where('status', 'completed')->count(),
                'average_progress' => (int) round((float) $u->goals()->where('status', 'active')->avg('progress')),
            ],
            'focus' => [
                'sessions_today' => $focus->count(),
                'minutes_today' => (int) (clone $focus)->sum('actual_minutes'),
            ],
        ]);
    }

    /**
     * Consecutive days (ending today, or yesterday if today has no completed entry yet)
     * on which at least one habit was completed.
     */
    private function streakDays(int $userId, Carbon $today): int
    {
        $days = HabitEntry::query()
            ->where('user_id', $userId)
            ->where('status', 'completed')
            ->where('entry_date', '>=', $today->copy()->subDays(365)->toDateString())
            ->distinct()
            ->pluck('entry_date')
            ->map(fn ($d) => substr((string) $d, 0, 10))
            ->flip()
            ->all();

        $cursor = $today->copy();
        if (! isset($days[$cursor->toDateString()])) {
            $cursor->subDay();
        }

        $streak = 0;
        while (isset($days[$cursor->toDateString()]) && $streak < 366) {
            $streak++;
            $cursor->subDay();
        }

        return $streak;
    }

    /** Completed-entry counts for the last seven days, oldest first. */
    private function lastSevenDays(int $userId, Carbon $today): array
    {
        $start = $today->copy()->subDays(6);

        $counts = HabitEntry::query()
            ->where('user_id', $userId)
            ->where('status', 'completed')
            ->whereBetween('entry_date', [$start->toDateString(), $today->toDateString()])
            ->selectRaw('entry_date, count(*) as total')
            ->groupBy('entry_date')
            ->pluck('total', 'entry_date')
            ->mapWithKeys(fn ($total, $date) => [substr((string) $date, 0, 10) => (int) $total])
            ->all();

        $week = [];
        for ($i = 0; $i < 7; $i++) {
            $date = $start->copy()->addDays($i)->toDateString();
            $week[] = ['date' => $date, 'completed' => $counts[$date] ?? 0];
        }

        return $week;
    }
}
