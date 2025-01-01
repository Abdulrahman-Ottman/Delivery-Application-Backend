<?php

namespace App\Jobs;

use App\Models\Order;
use App\Notifications\OrderStatusChangedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class UpdateOrderStatus implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    protected $orderId;

    public function __construct($orderId)
    {
        $this->orderId = $orderId;
    }
    /**
     * Execute the job.
     */
    public function handle()
    {
        $order = Order::find($this->orderId);

        if ($order) {
            $order->status = 'delivered';
            $order->save();
            $order->user->notify(new OrderStatusChangedNotification($order->id , 'on_way' , 'delivered'));
        }
    }
}
