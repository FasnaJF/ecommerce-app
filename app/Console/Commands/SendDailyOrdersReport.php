<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Mail\DailyOrdersReport;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class SendDailyOrdersReport extends Command
{
    protected $signature = 'report:daily-orders';
    protected $description = 'Send daily orders report to admin';

    public function handle()
    {
        // Get orders from previous day
        $yesterday = Carbon::yesterday()->startOfDay();
        $today = Carbon::yesterday()->endOfDay();

        $orders = Order::with(['user', 'orderItems.product'])
            ->whereBetween('created_at', [$yesterday, $today])
            ->get();

        // Send email to admin
        Mail::to('admin@example.com')->queue(new DailyOrdersReport($orders));

        $this->info('Daily orders report sent.');
    }
}
