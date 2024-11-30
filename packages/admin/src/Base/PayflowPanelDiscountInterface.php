<?php

namespace Payflow\Admin\Base;

interface PayflowPanelDiscountInterface
{
    /**
     * Return the schema to use in the Payflow admin panel
     */
    public function payflowPanelSchema(): array;

    /**
     * Mutate the model data before displaying it in the admin form.
     */
    public function payflowPanelOnFill(array $data): array;

    /**
     * Mutate the form data before saving it to the discount model.
     */
    public function payflowPanelOnSave(array $data): array;
}
