<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Mail\DailyOrdersReport;
use App\Models\Order;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schedule;
use Carbon\Carbon;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


Schedule::call(function () {

    $yesterday = Carbon::yesterday()->startOfDay();
    $orders = Order::with(['user', 'orderItems.product'])
        ->whereBetween('created_at', [$yesterday, Carbon::yesterday()->endOfDay()])
        ->get();

    Mail::to('admin@example.com')->queue(new DailyOrdersReport($orders));
})->dailyAt('07:00');  // run every day at 07:00