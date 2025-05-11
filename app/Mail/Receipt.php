<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Transaction;

class Receipt extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Transaction $transaction) {}

    public function envelope(): Envelope
    {
        $this->transaction->loadMissing('event');

        return new Envelope(
            subject: 'Your Receipt for ' . $this->transaction->event->title,
        );
    }

    public function content(): Content
    {
        $this->transaction->loadMissing('tickets');

        return new Content(
            markdown: 'mail.receipt',
        );
    }

}
