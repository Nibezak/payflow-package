
# Overview

The Payflow Panel is highly customizable, you can add and change the behaviour of existing Filament resources. This might be useful if you wish to add a button for
additional custom functionality. 

##  Extending Pages

To extend a page you need to create and register an extension.

### Extending edit resource

For example, the code below will register a custom extension called `MyEditExtension` for the `EditProduct` Filament page.

```php
use Payflow\Admin\Support\Facades\PayflowPanel;
use Payflow\Panel\Filament\Resources\ProductResource\Pages\EditProduct;
use App\Admin\Filament\Resources\Pages\MyEditExtension;

PayflowPanel::extensions([
    EditProduct::class => MyEditExtension::class,
]);

```

### Extending list resource

For example, the code below will register a custom extension called `MyListExtension` for the `ListProduct` Filament page.

```php
use Payflow\Admin\Support\Facades\PayflowPanel;
use Payflow\Panel\Filament\Resources\ProductResource\Pages\ListProduct;
use App\Admin\Filament\Resources\Pages\MyEditExtension;

PayflowPanel::extensions([
    ListProduct::class => MyListExtension::class,
]);

```

##  Extending Resources
Much like extending pages, to extend a resource you need to create and register an extension.

For example, the code below will register a custom extension called `MyProductResourceExtension` for the `ProductResource` Filament resource.

```php
use Payflow\Admin\Support\Facades\PayflowPanel;
use Payflow\Panel\Filament\Resources\ProductResource;
use App\Admin\Filament\Resources\MyProductResourceExtension;

PayflowPanel::extensions([
    ProductResource::class => MyProductResourceExtension::class,
]);

```

## Extendable resources

All Payflow panel resources are extendable. This means you can now add your own functionality or change out existing behaviour.

```php
use Payflow\Panel\Filament\Resources\ActivityResource;
use Payflow\Panel\Filament\Resources\ChannelResource;
use Payflow\Panel\Filament\Resources\CollectionGroupResource;
use Payflow\Panel\Filament\Resources\CollectionResource;
use Payflow\Panel\Filament\Resources\CurrencyResource;
use Payflow\Panel\Filament\Resources\CustomerGroupResource;
use Payflow\Panel\Filament\Resources\CustomerResource;
use Payflow\Panel\Filament\Resources\DiscountResource;
use Payflow\Panel\Filament\Resources\LanguageReousrce;
use Payflow\Panel\Filament\Resources\OrderResource;
use Payflow\Panel\Filament\Resources\ProductOptionrResource;
use Payflow\Panel\Filament\Resources\ProductResource;
use Payflow\Panel\Filament\Resources\ProductResource;
use Payflow\Panel\Filament\Resources\ProductTypeResource;
<!-- use Payflow\Panel\Filament\Resources\StaffResource; -->
use Payflow\Panel\Filament\Resources\TagResource;
use Payflow\Panel\Filament\Resources\TaxClassResource;
use Payflow\Panel\Filament\Resources\TaxZoneResource;
```
