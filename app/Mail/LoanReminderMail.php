<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Loan;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LoanReminderMail extends Mailable
{
    use Queueable, SerializesModels;
    public $loan;
    public $type;
    /**
     * Create a new message instance.
     */
    public function __construct(Loan $loan, string $type)
    {
        $this->loan = $loan;
        $this->type = $type;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Recordatorio de vencimiento del prestamo',
        );
    }
    public function build()
    {
        return $this->subject('Recordatorio: tu préstamo vence mañana')
            ->markdown('emails.loan_reminder', ['loan' => $this->loan]);
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.loan_reminder',
            with: [
                'loan' => $this->loan,
                'type' => $this->type,
                'user' => $this->loan->user,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
