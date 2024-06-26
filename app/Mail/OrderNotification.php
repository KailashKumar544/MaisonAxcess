<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Order;

class OrderNotification extends Mailable
{
    use Queueable, SerializesModels;
    public $order, $logoUrl, $service_provider_name;
    /**
     * Create a new message instance.
     */
    public function __construct(Order $order, $logoUrl, $service_provider_name)
    {
        $this->order = $order;
        $this->logoUrl = $logoUrl;
        $this->service_provider_name = $service_provider_name;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = 'Order Notification';
    
        // Check if the payment method is 'paypal'
        if ($this->order->payment_info === 'cod') {
            $subject = 'New Quote Notification';
        } else {
            $subject = 'New Order Notification';
        }

        return new Envelope(subject: $subject);
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.new_order_notification',
            with: ['order' => $this->order],
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
