# Extending The Panel

You may customise the Filament panel when registering it in your app service provider.

We provide a handy function which gives you direct access to the panel to change its properties.

For example, the following would change the panel's URL to `/admin` rather than the default `/payflow`.

```php
use Payflow\Admin\Support\Facades\PayflowPanel;

PayflowPanel::panel(fn($panel) => $panel->path('admin'))
    ->extensions([
        // ...
    ])
    ->register();
```
