<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DailyOrdersReport extends Mailable
{
    use Queueable, SerializesModels;

    public $orders;

    public function __construct($orders)
    {
        $this->orders = $orders;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Daily Orders Report',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.daily_orders_report',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
