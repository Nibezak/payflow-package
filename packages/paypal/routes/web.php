<?php

Route::group([
    'prefix' => 'api/paypal',
    'middleware' => ['web'],
], function ($router) {
    $router->post('order', \Payflow\Paypal\Http\Controllers\GetPaypalOrderController::class)->name('post.paypal.order');
});
