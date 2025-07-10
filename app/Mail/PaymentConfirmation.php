<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Transaction;

class PaymentConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $transaction;
    public $participant;
    public $event;

    /**
     * Create a new message instance.
     */
    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
        $this->participant = $transaction->participant;
        $this->event = $transaction->event;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Payment Confirmation - Order #' . $this->transaction->id)
                    ->view('emails.payment-confirmation')
                    ->text('emails.payment-confirmation-text')
                    ->with([
                        'transaction' => $this->transaction,
                        'participant' => $this->participant,
                        'event' => $this->event,
                    ]);
    }
}
