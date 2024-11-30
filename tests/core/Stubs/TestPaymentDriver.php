<?php

namespace Payflow\Tests\Core\Stubs;

use Payflow\Base\DataTransferObjects\PaymentAuthorize;
use Payflow\Base\DataTransferObjects\PaymentCapture;
use Payflow\Base\DataTransferObjects\PaymentRefund;
use Payflow\Models\Transaction;
use Payflow\PaymentTypes\AbstractPayment;

class TestPaymentDriver extends AbstractPayment
{
    /**
     * {@inheritDoc}
     */
    public function authorize(): ?PaymentAuthorize
    {
        return new PaymentAuthorize(true);
    }

    /**
     * {@inheritDoc}
     */
    public function refund(Transaction $transaction, int $amount = 0, $notes = null): PaymentRefund
    {
        return new PaymentRefund(true);
    }

    /**
     * {@inheritDoc}
     */
    public function capture(Transaction $transaction, $amount = 0): PaymentCapture
    {
        return new PaymentCapture(true);
    }
}
