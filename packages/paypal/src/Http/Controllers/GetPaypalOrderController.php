<?php

namespace Payflow\Paypal\Http\Controllers;

use Illuminate\Routing\Controller;
use Payflow\Facades\CartSession;
use Payflow\Paypal\Facades\Paypal;

class GetPaypalOrderController extends Controller
{
    public function __invoke()
    {
        $cart = CartSession::current();

        return response()->json(
            Paypal::buildInitialOrder($cart)
        );
    }
}
