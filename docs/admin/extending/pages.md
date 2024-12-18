# Extending Pages

## Writing Extensions

There are three extension types Payflow provides, these are for Create, Edit and Listing pages.

You will want to place the extension class in your application. A sensible location might be `App\Payflow\MyCreateExtension`.

Once created you will need to register the extension, typically in your app service provider.


## CreatePageExtension

An example of extending a create page.

```php
use Filament\Actions;
use Payflow\Admin\Support\Extending\CreatePageExtension;
use Payflow\Admin\Filament\Widgets;

class MyCreateExtension extends CreatePageExtension
{
    public function heading($title): string
    {
        return $title . ' - Example';
    }

    public function subheading($title): string
    {
        return $title . ' - Example';
    }
    
    public function getTabs(array $tabs): array
    {
        return [
            ...$tabs,
            'review' => Tab::make('Review')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'review')),
        ];
    }

    public function headerWidgets(array $widgets): array
    {
        $widgets = [
            ...$widgets,
            Widgets\Dashboard\Orders\OrderStatsOverview::make(),
        ];

        return $widgets;
    }

    public function headerActions(array $actions): array
    {
        $actions = [
            ...$actions,
            Actions\Action::make('Cancel'),
        ];

        return $actions;
    }

    public function formActions(array $actions): array
    {
        $actions = [
            ...$actions,
            Actions\Action::make('Create and Edit'),
        ];

        return $actions;
    }

    public function footerWidgets(array $widgets): array
    {
        $widgets = [
            ...$widgets,
            Widgets\Dashboard\Orders\LatestOrdersTable::make(),
        ];

        return $widgets;
    }

    public function beforeCreate(array $data): array
    {
        $data['model_code'] .= 'ABC';
        
        return $data;
    }

    public function beforeCreation(array $data): array
    {
        return $data;
    }

    public function afterCreation(Model $record, array $data): Model
    {
        return $record;
    }
}

// Typically placed in your AppServiceProvider file...
PayflowPanel::extensions([
    \Payflow\Admin\Filament\Resources\CustomerGroupResource\Pages\CreateCustomerGroup::class => MyCreateExtension::class,
]);
```

## EditPageExtension

An example of extending an edit page.

```php
use Filament\Actions;
use Payflow\Admin\Support\Extending\EditPageExtension;
use Payflow\Admin\Filament\Widgets;

class MyEditExtension extends EditPageExtension
{
    public function heading($title): string
    {
        return $title . ' - Example';
    }

    public function subheading($title): string
    {
        return $title . ' - Example';
    }

    public function headerWidgets(array $widgets): array
    {
        $widgets = [
            ...$widgets,
            Widgets\Dashboard\Orders\OrderStatsOverview::make(),
        ];

        return $widgets;
    }

    public function headerActions(array $actions): array
    {
        $actions = [
            ...$actions,
            Actions\ActionGroup::make([
                Actions\Action::make('View on Storefront'),
                Actions\Action::make('Copy Link'),
                Actions\Action::make('Duplicate'),
            ])
        ];

        return $actions;
    }

    public function formActions(array $actions): array
    {
        $actions = [
            ...$actions,
            Actions\Action::make('Update and Edit'),
        ];

        return $actions;
    }

     public function footerWidgets(array $widgets): array
    {
        $widgets = [
            ...$widgets,
            Widgets\Dashboard\Orders\LatestOrdersTable::make(),
        ];

        return $widgets;
    }

    public function beforeFill(array $data): array
    {
        $data['model_code'] .= 'ABC';

        return $data;
    }

    public function beforeSave(array $data): array
    {
        return $data;
    }

    public function beforeUpdate(array $data, Model $record): array
    {
        return $data;
    }

    public function afterUpdate(Model $record, array $data): Model
    {
        return $record;
    }
    
    public function relationManagers(array $managers): array
    {
        return $managers;
    }
}

// Typically placed in your AppServiceProvider file...
PayflowPanel::extensions([
    \Payflow\Admin\Filament\Resources\ProductResource\Pages\EditProduct::class => MyEditExtension::class,
]);
```

## ListPageExtension

An example of extending a list page.

```php
use Filament\Actions;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\Paginator;
use Payflow\Admin\Support\Extending\ListPageExtension;
use Payflow\Admin\Filament\Widgets;

class MyListExtension extends ListPageExtension
{
    public function heading($title): string
    {
        return $title . ' - Example';
    }

    public function subheading($title): string
    {
        return $title . ' - Example';
    }

    public function headerWidgets(array $widgets): array
    {
        $widgets = [
            ...$widgets,
            Widgets\Dashboard\Orders\OrderStatsOverview::make(),
        ];

        return $widgets;
    }

    public function headerActions(array $actions): array
    {
        $actions = [
            ...$actions,
            Actions\ActionGroup::make([
                Actions\Action::make('View on Storefront'),
                Actions\Action::make('Copy Link'),
                Actions\Action::make('Duplicate'),
            ]),
        ];

        return $actions;
    }
    
    public function paginateTableQuery(Builder $query, int $perPage = 25): Paginator
    {
        return $query->paginate($perPage);
    }

    public function footerWidgets(array $widgets): array
    {
        $widgets = [
            ...$widgets,
            Widgets\Dashboard\Orders\LatestOrdersTable::make(),
        ];

        return $widgets;
    }
}

// Typically placed in your AppServiceProvider file...
PayflowPanel::extensions([
    \Payflow\Admin\Filament\Resources\ProductResource\Pages\ListProducts::class => MyListExtension::class,
]);
```

## ViewPageExtension

An example of extending a view page.

```php
use Filament\Actions;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;
use Payflow\Admin\Support\Extending\ViewPageExtension;
use Payflow\Admin\Filament\Widgets;

class MyViewExtension extends ViewPageExtension
{
    public function headerWidgets(array $widgets): array
    {
        $widgets = [
            ...$widgets,
            Widgets\Dashboard\Orders\OrderStatsOverview::make(),
        ];

        return $widgets;
    }

    public function heading($title): string
    {
        return $title . ' - Example';
    }

    public function subheading($title): string
    {
        return $title . ' - Example';
    }
    
    public function headerActions(array $actions): array
    {
        $actions = [
            ...$actions,
            Actions\ActionGroup::make([
                Actions\Action::make('Download PDF')
            ])
        ];

        return $actions;
    }

    public function extendsInfolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            ...$infolist->getComponents(true),
            TextEntry::make('custom_title'),
        ]);
    }
    
    public function footerWidgets(array $widgets): array
    {
        $widgets = [
            ...$widgets,
            Widgets\Dashboard\Orders\LatestOrdersTable::make(),
        ];

        return $widgets;
    }
}

// Typically placed in your AppServiceProvider file...
PayflowPanel::extensions([
    \Payflow\Admin\Filament\Resources\OrderResource\Pages\ManageOrder::class => MyViewExtension::class,
]);
```

## RelationPageExtension

An example of extending a relation page.

```php
use Filament\Actions;
use Payflow\Admin\Support\Extending\RelationPageExtension;

class MyRelationExtension extends RelationPageExtension
{
    public function heading($title): string
    {
        return $title . ' - Example';
    }

    public function subheading($title): string
    {
        return $title . ' - Example';
    }
    
    public function headerActions(array $actions): array
    {
        $actions = [
            ...$actions,
            Actions\ActionGroup::make([
                Actions\Action::make('Download PDF')
            ])
        ];

        return $actions;
    }
}

// Typically placed in your AppServiceProvider file...
PayflowPanel::extensions([
    \Payflow\Admin\Filament\Resources\ProductResource\Pages\ManageProductMedia::class => MyRelationExtension::class,
]);
```

## Extending Pages In Addons

If you are building an addon for Payflow, you may need to take a slightly different approach when modifying forms, etc.

For example, you cannot assume the contents of a form, so you may need to take an approach such as this...

```php
    public function extendForm(Form $form): Form
    {
        $form->schema([
            ...$form->getComponents(true),  // Gets the currently registered components
            TextInput::make('model_code'),
        ]);
        return $form;
    }
```
