<?php

namespace App\Console\Commands;
use App\Models\Loan;
use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\LoanReminderMail;

class SendLoanReminders extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-loan-reminders';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Enviar recordatorios a los estudiantes acerca de sus prestamos';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::today();

        //Prestamos que vencen maniana
        $soonLoans = Loan::with('user', 'book')
        ->whereDate('fecha_vencimiento', $today->copy()->addDay())
        ->whereNull('returned_at')
        ->get();

        foreach ($soonLoans as $loan) {
            Mail::to($loan->user->email)
            ->bcc('mauricio.chavez@keyinstitute.edu.sv')
            ->send(new LoanReminderMail($loan, 'expired'));
        }

        $this->info('Recordatorios enviadoos correctamente');
    }
}
