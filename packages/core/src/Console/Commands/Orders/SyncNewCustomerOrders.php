<?php

namespace Payflow\Console\Commands\Orders;

use Illuminate\Console\Command;
use Payflow\Jobs\Orders\MarkAsNewCustomer;
use Payflow\Models\Order;

class SyncNewCustomerOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payflow:orders:sync-new';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates historic orders to whether they were a new customer or not.';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        Order::orderBy('id')->chunk(500, function ($orders) {
            foreach ($orders as $order) {
                MarkAsNewCustomer::dispatch($order->id);
            }
        });

        exit(self::SUCCESS);
    }
}
