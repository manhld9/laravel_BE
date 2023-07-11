<?php

namespace App\Listeners;

use App\Events\VerifyEmailProcessed;
use App\Mail\VerifyEmail;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use Throwable;

class SendVerifyEmailNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(VerifyEmailProcessed $event): void
    {
        $user = User::findOrFail($event->userId);
        if (isset($user))
        {
            Mail::to($user->email)->send(new VerifyEmail($user));
        }
    }

    public function failed(VerifyEmailProcessed $event, Throwable $exception): void
    {

    }
}
