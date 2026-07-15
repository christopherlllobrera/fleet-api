<?php

namespace App\Listeners;

use App\Models\User;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Database\Eloquent\Model;

class AuthenticationEventListener
{
    /**
     * Handle user login event.
     */
    public function handleLogin(Login $event): void
    {
        if ($event->user instanceof Model) {
            activity()
                ->useLog('Authentication')
                ->performedOn($event->user)
                ->causedBy($event->user)
                ->withProperties([
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ])
                ->log('User logged in');
        }
    }

    /**
     * Handle user logout event.
     */
    public function handleLogout(Logout $event): void
    {
        if ($event->user instanceof Model) {
            activity()
                ->useLog('Authentication')
                ->performedOn($event->user)
                ->causedBy($event->user)
                ->withProperties([
                    'ip_address' => request()->ip(),
                ])
                ->log('User logged out');
        }
    }

    /**
     * Handle failed login attempt.
     */
    public function handleFailedLogin(Failed $event): void
    {
        activity()
            ->useLog('Authentication')
            ->withProperties([
                'email' => $event->credentials['email'] ?? null,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ])
            ->log('Failed login attempt');
    }
}
