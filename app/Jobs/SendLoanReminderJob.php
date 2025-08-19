<?php

namespace App\Jobs;

use App\Mail\LoanReminderMail;
use App\Models\Loan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendLoanReminderJob implements ShouldQueue
{
    use Queueable;
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public int $loanId) {}

    /**
     * Execute the job.
     */
     public function handle(): void
    {
        $loan = Loan::with('user')->find($this->loanId);
        if (! $loan || ! $loan->user?->email) return;

        if ($loan->reminder_sent_at) return; // ya enviado

        Mail::to($loan->user->email)->send(new LoanReminderMail($loan, 'expired'));

        $loan->forceFill(['reminder_sent_at' => now()])->save();
    }

}
