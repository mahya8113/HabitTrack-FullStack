<?php

namespace App\Support;

use App\Models\InAppNotification;
use App\Models\User;
use App\Models\UserBadge;
use App\Models\XpEvent;
use Illuminate\Support\Facades\DB;

/**
 * XP, levels, badges and in-app notifications.
 * Every write is idempotent (keyed by event_key / badge_key / dedupe_key),
 * so repeating the same action never awards XP or badges twice.
 */
class Gamification
{
    public static function award(User $user, string $eventKey, string $type, int $xp, array $metadata = []): void
    {
        if ($xp <= 0) {
            return;
        }

        DB::transaction(function () use ($user, $eventKey, $type, $xp, $metadata) {
            $exists = XpEvent::where('user_id', $user->id)->where('event_key', $eventKey)->exists();
            if ($exists) {
                return;
            }

            XpEvent::create([
                'user_id' => $user->id,
                'event_key' => $eventKey,
                'event_type' => $type,
                'xp' => $xp,
                'metadata' => $metadata ?: null,
            ]);

            $reward = $user->reward()->firstOrCreate([], ['xp_total' => 0, 'level' => 1]);
            $reward->xp_total += $xp;
            $reward->level = max(1, intdiv($reward->xp_total, 100) + 1);
            $reward->save();
        });
    }

    public static function badge(User $user, string $key, string $title, string $description): void
    {
        $badge = UserBadge::firstOrCreate(
            ['user_id' => $user->id, 'badge_key' => $key],
            ['title' => $title, 'description' => $description, 'earned_at' => now()]
        );

        if ($badge->wasRecentlyCreated) {
            self::notify($user, 'badge_earned', 'نشان جدید: '.$title, $description, 'badge:'.$key);
        }
    }

    public static function notify(User $user, string $type, string $title, ?string $body, string $dedupeKey, array $data = []): void
    {
        InAppNotification::firstOrCreate(
            ['user_id' => $user->id, 'dedupe_key' => $dedupeKey],
            ['type' => $type, 'title' => $title, 'body' => $body, 'data' => $data ?: null]
        );
    }
}
