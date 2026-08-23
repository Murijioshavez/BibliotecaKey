<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Loan;

class LoanApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $loanId;

    public function __construct($loanId)
    {
        $this->loanId = $loanId;
    }

    public function build()
    {
        // Cargar el préstamo aquí, dentro del job
        $loan = Loan::with('user', 'book')->findOrFail($this->loanId);

        return $this->subject('📚 Préstamo aprobado')
                    ->view('emails.loan_approved')
                    ->with(['loan' => $loan]);
    }
}
