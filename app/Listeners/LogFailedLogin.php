<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Failed;
use Spatie\Activitylog\Facades\Activity;

class LogFailedLogin
{
    public function handle(Failed $event): void
    {
        Activity::withProperties([
                'ip' => request()->ip(),
                'email' => $event->credentials['email'] ?? 'N/A',
                'user_agent' => request()->userAgent(),
            ])
            ->log('Failed login attempt');
    }
}
