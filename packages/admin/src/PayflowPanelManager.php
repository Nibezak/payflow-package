<?php
namespace Payflow\Admin;

use Filament\Facades\Filament;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\UserMenuItem;
use Filament\Panel;
use Filament\Navigation\MenuItem;
use Filament\Support\Assets\Css;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentColor;
use Filament\Support\Facades\FilamentIcon;
use Filament\Tables\Table;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Route;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Payflow\Admin\Filament\AvatarProviders\GravatarProvider;
use Payflow\Admin\Filament\Pages;
use Payflow\Admin\Filament\Resources;
use Payflow\Admin\Filament\Widgets\Dashboard\Orders\AverageOrderValueChart;
use Payflow\Admin\Filament\Widgets\Dashboard\Orders\LatestOrdersTable;
use Payflow\Admin\Filament\Widgets\Dashboard\Orders\NewVsReturningCustomersChart;
use Payflow\Admin\Filament\Widgets\Dashboard\Orders\OrdersSalesChart;
use Payflow\Admin\Filament\Widgets\Dashboard\Orders\OrderStatsOverview;
use Payflow\Admin\Filament\Widgets\Dashboard\Orders\OrderTotalsChart;
use Payflow\Admin\Filament\Widgets\Dashboard\Orders\PopularProductsTable;
use Payflow\Admin\Http\Controllers\DownloadPdfController;
use Payflow\Admin\Support\Facades\PayflowAccessControl;

class PayflowPanelManager
{
    protected ?\Closure $closure = null;

    protected array $extensions = [];

    protected string $panelId = 'payflow';

    protected static $resources = [
        Resources\ActivityResource::class,
        Resources\ChannelResource::class,
        Resources\CollectionGroupResource::class,
        Resources\CollectionResource::class,
        Resources\CurrencyResource::class,
        Resources\CustomerGroupResource::class,
        Resources\CustomerResource::class,
        Resources\DiscountResource::class,
        Resources\OrderResource::class,
        Resources\ProductResource::class,
        Resources\ProductTypeResource::class,
        Resources\StaffResource::class,
        Resources\TagResource::class,
        Resources\TaxClassResource::class,
        Resources\TaxZoneResource::class,
        Resources\TaxRateResource::class,
    ];

    protected static $pages = [
        Pages\Dashboard::class,
    ];

    protected static $widgets = [
        OrderStatsOverview::class,
        OrderTotalsChart::class,
        // OrdersSalesChart::class,
        // AverageOrderValueChart::class,
        NewVsReturningCustomersChart::class,
        PopularProductsTable::class,
        // LatestOrdersTable::class,
    ];

    public function register(): self
    {
        $panel = $this->defaultPanel();

        if ($this->closure instanceof \Closure) {
            $fn = $this->closure;
            $panel = $fn($panel);
        }

        Filament::registerPanel($panel);

        FilamentIcon::register([
            // Filament
            'panels::topbar.global-search.field' => 'lucide-search',
            'actions::view-action' => 'lucide-eye',
            'actions::edit-action' => 'lucide-edit',
            'actions::delete-action' => 'lucide-trash-2',
            'actions::make-collection-root-action' => 'lucide-corner-left-up',

            // Payflow
            'payflow::activity' => 'lucide-activity',
            'payflow::attributes' => 'lucide-pencil-ruler',
            'payflow::availability' => 'lucide-calendar',
            'payflow::basic-information' => 'lucide-edit',
            'payflow::channels' => 'lucide-store',
            'payflow::collections' => 'lucide-blocks',
            'payflow::sub-collection' => 'lucide-square-stack',
            'payflow::move-collection' => 'lucide-move',
            'payflow::currencies' => 'lucide-circle-dollar-sign',
            'payflow::customers' => 'lucide-users',
            'payflow::customer-groups' => 'lucide-users',
            'payflow::dashboard' => 'lucide-bar-chart-big',
            'payflow::discounts' => 'lucide-percent-circle',
            'payflow::discount-limitations' => 'lucide-list-x',
            'payflow::info' => 'lucide-info',
            'payflow::languages' => 'lucide-languages',
            'payflow::media' => 'lucide-image',
            'payflow::orders' => 'lucide-inbox',
            'payflow::product-pricing' => 'lucide-coins',
            'payflow::product-associations' => 'lucide-cable',
            'payflow::product-inventory' => 'lucide-combine',
            'payflow::product-options' => 'lucide-list',
            'payflow::product-shipping' => 'lucide-truck',
            'payflow::products' => 'lucide-tag',
            'payflow::staff' => 'lucide-shield',
            'payflow::tags' => 'lucide-tags',
            'payflow::tax' => 'lucide-landmark',
            'payflow::urls' => 'lucide-globe',
            'payflow::product-identifiers' => 'lucide-package-search',
            'payflow::reorder' => 'lucide-grip-vertical',
            'payflow::chevron-right' => 'lucide-chevron-right',
            'payflow::image-placeholder' => 'lucide-image',
            'payflow::trending-up' => 'lucide-trending-up',
            'payflow::trending-down' => 'lucide-trending-down',
            'payflow::exclamation-circle' => 'lucide-alert-circle',
        ]);

        FilamentColor::register([
            'chartPrimary' => Color::Blue,
            'chartSecondary' => Color::Green,
        ]);

        if (app('request')->is($panel->getPath().'*')) {
            app('config')->set('livewire.inject_assets', true);
        }

        Table::configureUsing(function (Table $table): void {
            $table
                ->paginationPageOptions([10, 25, 50, 100])
                ->defaultPaginationPageOption(25);
        });

        return $this;
    }

    public function panel(\Closure $closure): self
    {
        $this->closure = $closure;

        return $this;
    }

    public function getPanel(): Panel
    {
        return Filament::getPanel($this->panelId);
    }

    protected function defaultPanel(): Panel
    {
        $brandAsset = function ($asset) {
            $vendorPath = 'vendor/payflowpanel/';

            if (file_exists(public_path($vendorPath.$asset))) {
                return asset($vendorPath.$asset);
            } else {
                $type = str($asset)
                    ->endsWith('.png') ? 'image/png' : 'image/svg+xml';

                return "data:{$type};base64,".base64_encode(file_get_contents(__DIR__.'/../public/'.$asset));
            }
        };

        $panelMiddleware = [
            EncryptCookies::class,
            AddQueuedCookiesToResponse::class,
            StartSession::class,
            AuthenticateSession::class,
            ShareErrorsFromSession::class,
            VerifyCsrfToken::class,
            SubstituteBindings::class,
            DisableBladeIconComponents::class,
            DispatchServingFilamentEvent::class,
        ];

        if (config('payflow.panel.pdf_rendering', 'download') == 'stream') {
            Route::get('payflow/pdf/download', DownloadPdfController::class)
                ->name('payflow.pdf.download')->middleware($panelMiddleware);
        }

        return Panel::make()
            ->spa()
            ->default()
            ->id($this->panelId)
            ->brandName('Payflow')
            ->brandLogo($brandAsset('payflow-logo.svg'))
            ->darkModeBrandLogo($brandAsset('payflow-logo-dark.svg'))
            ->favicon($brandAsset('payflow-icon.png'))
            ->brandLogoHeight('2rem')
            ->path('payflow')
            ->authGuard('staff')
            ->defaultAvatarProvider(GravatarProvider::class)
            ->userMenuItems([  
                UserMenuItem::make()
                    ->label('Account & Permissions')  
                    ->url('/staff')  // Absolute path to avoid double prefix
                    ->icon('heroicon-o-lock-closed'), 
            
                UserMenuItem::make()
                    ->label('Sandbox')  
                    ->url('/sandbox')  // Absolute path to avoid double prefix
                    ->icon('heroicon-o-cube-transparent'),

                    UserMenuItem::make()
                    ->label('Activity Logs')  
                    ->url('/activities')  // Absolute path to avoid double prefix
                    ->icon('lucide-activity'),
            
                UserMenuItem::make()
                    ->label('Settings & API-keys')  
                    ->url('/api-keys')  // Absolute path to avoid double prefix
                    ->icon('heroicon-o-key'),  
            ])            
            ->login()
            ->registration()
            ->colors([
                'primary' => Color::rgb('rgb(96, 165, 250)'),
            ])
            ->font('Poppins')
            ->middleware($panelMiddleware)
            ->assets([
                Css::make('payflow-panel', __DIR__.'/../resources/dist/payflow-panel.css'),
            ], 'payflowphp/panel')
            ->pages(
                static::getPages()
            )
            ->resources(
                static::getResources()
            )
            ->discoverClusters(
                in: realpath(__DIR__.'/Filament/Clusters'),
                for: 'Payflow\Admin\Filament\Clusters'
            )
            ->widgets(
                static::getWidgets()
            )
            ->authMiddleware([
                Authenticate::class,
            ])
            ->plugins([
                \Leandrocfe\FilamentApexCharts\FilamentApexChartsPlugin::make(),
            ])
            ->discoverLivewireComponents(__DIR__.'/Livewire', 'Payflow\\Admin\\Livewire')
            ->livewireComponents([
                Resources\OrderResource\Pages\Components\OrderItemsTable::class,
                \Payflow\Admin\Filament\Resources\CollectionGroupResource\Widgets\CollectionTreeView::class,
            ])
            ->navigationGroups([
                'Sales',
                'Catalog',
                NavigationGroup::make()
                    ->label('Utilities')
                    ->collapsed(),
            ])->sidebarCollapsibleOnDesktop();
    }

    public function extensions(array $extensions): self
    {
        foreach ($extensions as $class => $extension) {
            $this->extensions[$class][] = new $extension;
        }

        return $this;
    }

    /**
     * @return array<class-string<\Filament\Resources\Resource>>
     */
    public static function getResources(): array
    {
        return static::$resources;
    }

    /**
     * @return array<class-string<\Filament\Pages\Page>>
     */
    public static function getPages(): array
    {
        return static::$pages;
    }

    /**
     * @return array<class-string<\Filament\Widgets\Widget>>
     */
    public static function getWidgets(): array
    {
        return static::$widgets;
    }

    public function useRoleAsAdmin(string|array $roleHandle): self
    {
        PayflowAccessControl::useRoleAsAdmin($roleHandle);

        return $this;
    }

    public function callHook(string $class, ?object $caller, string $hookName, ...$args): mixed
    {
        if (isset($this->extensions[$class])) {
            foreach ($this->extensions[$class] as $extension) {
                if (method_exists($extension, $hookName)) {
                    $extension->setCaller($caller);
                    $args[0] = $extension->{$hookName}(...$args);
                }
            }
        }

        return $args[0];
    }
}
