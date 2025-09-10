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
    protected $signature = 'loans:send-reminders';


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
        $tomorrow = Carbon::tomorrow();

        // prestamos que vencen mañana y aún no devueltos
        $soonLoans = Loan::with('user', 'book')
            ->whereDate('fecha_vencimiento', $tomorrow)
            ->whereNull('returned_at')
            ->whereNull('reminder_sent_at') // <- evita duplicados
            ->get();

        foreach ($soonLoans as $loan) {
            // enviar el correo
            Mail::to($loan->user->email)
                ->bcc('maria.aparicio@keyinstitute.edu.sv')
                ->send(new LoanReminderMail($loan, 'expired'));

            // marcar como enviado
            $loan->forceFill([
                'reminder_sent_at' => now(),
            ])->save();
        }

        $this->info("Se enviaron {$soonLoans->count()} recordatorios ✔");
    }
}
