# Developing Add-ons

When creating add-on packages for Payflow you may wish to add new screens and functionality to the Filament panel.

To achieve this you will want to create a Filament plugin in your package. With Filament plugins you can add additional
resources, pages and widgets. See https://filamentphp.com/docs/3.x/panels/plugins for more information.

## Registering Filament plugins

Add-on packages should not try to register a Filament plugin automatically in the Payflow panel. Instead, installation 
instructions should be provided.

Below is an example of how a plugin should be registered to the Payflow admin panel, typically in your Laravel app 
service provider.

```php
use Payflow\Admin\Support\Facades\PayflowPanel;

PayflowPanel::panel(fn($panel) => $panel->plugin(new ReviewsPlugin()))
    ->register();
```
