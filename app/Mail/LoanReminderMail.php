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


    public function build(){
        $subject = $this->type === 'soon'
        ? 'Tu préstamo está a punto de vencer'
        : 'Tu préstamo ha vencido';

        $body = $this->type === 'soon'
        ? "Hola {$this->loan->user->name}\n\nTu préstamo del libro '{$this->loan->book->title}' vence el {$this->loan->fecha_vencimiento->format('d/m/Y')}"
        : "Hola {$this->loan->user->name}\n\nTu préstamo del libro '{$this->loan->book->title}' ha vencido el {$this->loan->fecha_vencimiento->format('d/m/Y')}";


        return $this->subject($subject)
        ->bcc('maria.aparicio@keyinstitute.edu.sv')
        ->text('emails.loan_reminder', ['body' => $body]);
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

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'view.name',
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
