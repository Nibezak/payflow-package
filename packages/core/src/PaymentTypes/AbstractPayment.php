<?php

namespace Payflow\PaymentTypes;

use Payflow\Base\DataTransferObjects\PaymentChecks;
use Payflow\Base\PaymentTypeInterface;
use Payflow\Models\Cart;
use Payflow\Models\Order;
use Payflow\Models\Transaction;

abstract class AbstractPayment implements PaymentTypeInterface
{
    /**
     * The instance of the cart.
     */
    protected ?Cart $cart = null;

    /**
     * The instance of the order.
     */
    protected ?Order $order = null;

    /**
     * Any config for this payment provider.
     */
    protected array $config = [];

    /**
     * Data storage.
     */
    protected array $data = [];

    /**
     * {@inheritDoc}
     */
    public function cart(Cart $cart): self
    {
        $this->cart = $cart;
        $this->order = null;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function order(Order $order): self
    {
        $this->order = $order;
        $this->cart = null;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function withData(array $data): self
    {
        $this->data = $data;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function setConfig(array $config): self
    {
        $this->config = $config;

        return $this;
    }

    public function getPaymentChecks(Transaction $transaction): PaymentChecks
    {
        return new PaymentChecks;
    }
}
