<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderStatusChangedNotification extends Notification
{
    use Queueable;

    protected int $orderId;
    protected string $previousStatus;
    protected string $newStatus;

    public function __construct(int $orderId, string $previousStatus, string $newStatus)
    {
        $this->orderId = $orderId;
        $this->previousStatus = $previousStatus;
        $this->newStatus = $newStatus;
    }


    public function via($notifiable)
    {
        return ['database'];
    }


    public function toDatabase($notifiable)
    {
        return [
            'order_id' => $this->orderId,
            'previous_status' => $this->previousStatus,
            'new_status' => $this->newStatus,
            'message' => "Your order #{$this->orderId} status has changed from '{$this->previousStatus}' to '{$this->newStatus}'.",
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
