<?php

namespace Payflow\Opayo\Facades;

use Illuminate\Support\Facades\Facade;
use Payflow\Opayo\DataTransferObjects\AuthPayloadParameters;
use Payflow\Opayo\OpayoInterface;

/**
 * @method static getAuthPayload(AuthPayloadParameters $parameters): array
 * @method static getMerchantKey(): ?string
 * @method static api(): PendingRequest
 */
class Opayo extends Facade
{
    /**
     * Status for successful authorization.
     */
    const AUTH_SUCCESSFUL = 1;

    /**
     * Status if an order has already been placed.
     */
    const ALREADY_PLACED = 10;

    /**
     * Status when the payment requires Three D Secure authentication.
     */
    const THREED_AUTH = 20;

    /**
     * Status for when Three D Secure fails.
     */
    const THREED_SECURE_FAILED = 30;

    /**
     * Status for when authorization has failed.
     */
    const AUTH_FAILED = 40;

    /**
     * {@inheritdoc}
     */
    protected static function getFacadeAccessor()
    {
        return OpayoInterface::class;
    }
}
