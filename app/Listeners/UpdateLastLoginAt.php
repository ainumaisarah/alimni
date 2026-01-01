<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;

class UpdateLastLoginAt
{
    public function handle(Login $event)
    {
        // Directly assign and save to bypass mass assignment issues
        $event->user->last_login_at = now();
        $event->user->save();
    }
}
