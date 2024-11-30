<?php

namespace Payflow\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Payflow\Admin\Models\Staff;
use Payflow\Facades\DB;
use Payflow\FieldTypes\TranslatedText;
use Payflow\Models\Attribute;
use Payflow\Models\AttributeGroup;
use Payflow\Models\Channel;
use Payflow\Models\Collection;
use Payflow\Models\CollectionGroup;
use Payflow\Models\Country;
use Payflow\Models\Currency;
use Payflow\Models\CustomerGroup;
use Payflow\Models\Language;
use Payflow\Models\Product;
use Payflow\Models\ProductType;
use Payflow\Models\TaxClass;

use function Laravel\Prompts\confirm;

class InstallPayflow extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payflow:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the Payflow';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->components->info('Installing Payflow...');

        $this->components->info('Publishing configuration...');

        if (! $this->configExists('payflow')) {
            $this->publishConfiguration();
        } else {
            if ($this->shouldOverwriteConfig()) {
                $this->components->info('Overwriting configuration file...');
                $this->publishConfiguration(forcePublish: true);
            } else {
                $this->components->info('Existing configuration was not overwritten');
            }
        }

        if (confirm('Run database migrations?')) {
            $this->call('migrate');
        }

        DB::transaction(function () {

            if (class_exists(Staff::class) && ! Staff::whereAdmin(true)->exists()) {
                $this->components->info('First create a payflow admin user');
                $this->call('payflow:create-admin');
            }

            if (! Country::count()) {
                $this->components->info('Importing countries');
                $this->call('payflow:import:address-data');
            }

            if (! Channel::whereDefault(true)->exists()) {
                $this->components->info('Setting up default channel');

                Channel::create([
                    'name' => 'Webstore',
                    'handle' => 'webstore',
                    'default' => true,
                    'url' => 'http://localhost',
                ]);
            }

            if (! Language::count()) {
                $this->components->info('Adding default language');

                Language::create([
                    'code' => 'en',
                    'name' => 'English',
                    'default' => true,
                ]);
            }

            if (! Currency::whereDefault(true)->exists()) {
                $this->components->info('Adding a default currency (USD)');

                Currency::create([
                    'code' => 'USD',
                    'name' => 'US Dollar',
                    'exchange_rate' => 1,
                    'decimal_places' => 2,
                    'default' => true,
                    'enabled' => true,
                ]);
            }

            if (! CustomerGroup::whereDefault(true)->exists()) {
                $this->components->info('Adding a default customer group.');

                CustomerGroup::create([
                    'name' => 'Retail',
                    'handle' => 'retail',
                    'default' => true,
                ]);
            }

            if (! CollectionGroup::count()) {
                $this->components->info('Adding an initial collection group');

                CollectionGroup::create([
                    'name' => 'Main',
                    'handle' => 'main',
                ]);
            }

            if (! TaxClass::count()) {
                $this->components->info('Adding a default tax class.');

                TaxClass::create([
                    'name' => 'Default Tax Class',
                    'default' => true,
                ]);
            }

            if (! Attribute::count()) {
                $this->components->info('Setting up initial attributes');

                $group = AttributeGroup::create([
                    'attributable_type' => Product::morphName(),
                    'name' => collect([
                        'en' => 'Details',
                    ]),
                    'handle' => 'details',
                    'position' => 1,
                ]);

                $collectionGroup = AttributeGroup::create([
                    'attributable_type' => Collection::morphName(),
                    'name' => collect([
                        'en' => 'Details',
                    ]),
                    'handle' => 'collection_details',
                    'position' => 1,
                ]);

                Attribute::create([
                    'attribute_type' => 'product',
                    'attribute_group_id' => $group->id,
                    'position' => 1,
                    'name' => [
                        'en' => 'Name',
                    ],
                    'handle' => 'name',
                    'section' => 'main',
                    'type' => TranslatedText::class,
                    'required' => true,
                    'default_value' => null,
                    'configuration' => [
                        'richtext' => false,
                    ],
                    'system' => true,
                    'description' => [
                        'en' => '',
                    ],
                ]);

                Attribute::create([
                    'attribute_type' => 'collection',
                    'attribute_group_id' => $collectionGroup->id,
                    'position' => 1,
                    'name' => [
                        'en' => 'Name',
                    ],
                    'handle' => 'name',
                    'section' => 'main',
                    'type' => TranslatedText::class,
                    'required' => true,
                    'default_value' => null,
                    'configuration' => [
                        'richtext' => false,
                    ],
                    'system' => true,
                    'description' => [
                        'en' => '',
                    ],
                ]);

                Attribute::create([
                    'attribute_type' => 'product',
                    'attribute_group_id' => $group->id,
                    'position' => 2,
                    'name' => [
                        'en' => 'Description',
                    ],
                    'handle' => 'description',
                    'section' => 'main',
                    'type' => TranslatedText::class,
                    'required' => false,
                    'default_value' => null,
                    'configuration' => [
                        'richtext' => true,
                    ],
                    'system' => false,
                    'description' => [
                        'en' => '',
                    ],
                ]);

                Attribute::create([
                    'attribute_type' => 'collection',
                    'attribute_group_id' => $collectionGroup->id,
                    'position' => 2,
                    'name' => [
                        'en' => 'Description',
                    ],
                    'handle' => 'description',
                    'section' => 'main',
                    'type' => TranslatedText::class,
                    'required' => false,
                    'default_value' => null,
                    'configuration' => [
                        'richtext' => true,
                    ],
                    'system' => false,
                    'description' => [
                        'en' => '',
                    ],
                ]);
            }

            if (! ProductType::count()) {
                $this->components->info('Adding a product type.');

                $type = ProductType::create([
                    'name' => 'Stock',
                ]);

                $type->mappedAttributes()->attach(
                    Attribute::whereAttributeType(
                        Product::morphName()
                    )->get()->pluck('id')
                );
            }
        });

        $this->components->info('Publishing Filament assets');
        $this->call('filament:assets');

        $this->components->info('Payflow is now installed 🚀');

        if (confirm('Would you like to show some love by giving us a star on GitHub?')) {
            match (PHP_OS_FAMILY) {
                'Darwin' => exec('open https://github.com/payflowphp/payflow'),
                'Linux' => exec('xdg-open https://github.com/payflowphp/payflow'),
                'Windows' => exec('start https://github.com/payflowphp/payflow'),
            };

            $this->components->info('Thank you!');
        }
    }

    /**
     * Checks if config exists given a filename.
     */
    private function configExists(string $fileName): bool
    {
        if (! File::isDirectory(config_path($fileName))) {
            return false;
        }

        return ! empty(File::allFiles(config_path($fileName)));
    }

    /**
     * Returns a prompt if config exists and ask to override it.
     */
    private function shouldOverwriteConfig(): bool
    {
        return confirm(
            'Config file already exists. Do you want to overwrite it?',
            false
        );
    }

    /**
     * Publishes configuration for the Service Provider.
     */
    private function publishConfiguration(bool $forcePublish = false): void
    {
        $params = [
            '--tag' => 'payflow',
        ];

        if ($forcePublish === true) {
            $params['--force'] = true;
        }

        $this->call('vendor:publish', $params);
    }
}
