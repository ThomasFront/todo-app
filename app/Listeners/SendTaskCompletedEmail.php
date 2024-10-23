<?php

namespace App\Listeners;

use App\Events\TaskCompleted;
use App\Mail\TaskCompletedMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendTaskCompletedEmail
{
    /**
     * Handle the event.
     *
     * @param TaskCompleted $event
     * @return void
     */
    public function handle(TaskCompleted $event)
    {
        $adminEmail = config('mail.admin_email');
        Mail::to($adminEmail)->send(new TaskCompletedMail($event->task));
    }
}
