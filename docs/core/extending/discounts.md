
# Discounts

## Overview

If you want to add additional functionality to Discounts, you can register your own custom discount types.

## Registering a discount type.

```php
use Payflow\Facades\Discounts;

Discounts::addType(MyCustomDiscountType::class);
```


```php
<?php

namespace App\DiscountTypes;

use Payflow\Models\Cart;
use Payflow\DiscountTypes\AbstractDiscountType;

class MyCustomDiscountType extends AbstractDiscountType
{
    /**
     * Return the name of the discount.
     *
     * @return string
     */
    public function getName(): string
    {
        return 'Custom Discount Type';
    }

    /**
     * Called just before cart totals are calculated.
     *
     * @return Cart
     */
    public function apply(Cart $cart): Cart
    {
        // ...
        return $cart;
    }
}
```


## Adding form fields for your discount in the admin panel

If you require fields in the Payflow admin for your discount type, ensure your discount implements `Payflow\Admin\Base\PayflowPanelDiscountInterface`. You will need to provide the `payflowPanelSchema`, `payflowPanelOnFill` and `payflowPanelOnSave` methods.

```php
<?php

namespace App\DiscountTypes;

use Payflow\Admin\Base\PayflowPanelDiscountInterface;
use Payflow\DiscountTypes\AbstractDiscountType;
use Filament\Forms;

class MyCustomDiscountType extends AbstractDiscountType implements PayflowPanelDiscountInterface
{
    /**
     * Return the schema to use in the Payflow admin panel
     */
    public function payflowPanelSchema(): array
    {
        return [
            Forms\Components\TextInput::make('data.my_field')
               ->label('My label')
               ->required(),
        ];
    }

    /**
     * Mutate the model data before displaying it in the admin form.
     */
    public function payflowPanelOnFill(array $data): array
    {
        // optionally do something with $data
        return $data;
    }

    /**
     * Mutate the form data before saving it to the discount model.
     */
    public function payflowPanelOnSave(array $data): array
    {
        // optionally do something with $data
        return $data;
    }
}
```
