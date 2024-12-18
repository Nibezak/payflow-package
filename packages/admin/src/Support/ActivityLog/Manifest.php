<?php

namespace Payflow\Admin\Support\ActivityLog;

use Illuminate\Support\Collection;
use Payflow\Admin\Support\ActivityLog\Orders\Address;
use Payflow\Admin\Support\ActivityLog\Orders\Capture;
use Payflow\Admin\Support\ActivityLog\Orders\EmailNotification;
use Payflow\Admin\Support\ActivityLog\Orders\Intent;
use Payflow\Admin\Support\ActivityLog\Orders\Refund;
use Payflow\Admin\Support\ActivityLog\Orders\StatusUpdate;
use Payflow\Models\Order;
use Payflow\Models\Product;
use Payflow\Models\ProductVariant;

class Manifest
{
    /**
     * The events to watch and render.
     *
     * @var array
     */
    public $events = [
        Order::class => [
            Comment::class,
            StatusUpdate::class,
            Capture::class,
            Intent::class,
            Refund::class,
            EmailNotification::class,
            Address::class,
            TagsUpdate::class,
        ],
        Product::class => [
            Comment::class,
        ],
        ProductVariant::class => [
            Comment::class,
        ],
    ];

    /**
     * Add an activity log render.
     *
     * @return self
     */
    public function addRender(string $subject, string $renderer)
    {
        if (empty($this->events[$subject])) {
            $this->events[$subject] = [];
        }

        $this->events[$subject][] = $renderer;

        return $this;
    }

    /**
     * Return the items from a given subject.
     *
     * @param  string  $classname
     * @return Collection
     */
    public function getItems($subject)
    {
        return collect($this->events[$subject] ?? [])
            ->merge([
                Update::class,
                Create::class,
            ])->map(function ($subject) {
                $class = new $subject;

                return [
                    'event' => $class->getEvent(),
                    'class' => $class,
                ];
            });
    }
}
