<?php

use function Pest\Laravel\{get};

uses(\Payflow\Tests\Admin\Feature\Filament\TestCase::class)
    ->group('resource.discount');

beforeEach(function () {
    $this->asStaff();
});

it('can render discount limitations page', function () {
    $record = \Payflow\Models\Discount::factory()->create();

    \Payflow\Models\Channel::factory()->create(['default' => true]);

    get(\Payflow\Admin\Filament\Resources\DiscountResource::getUrl('limitations', [
        'record' => $record,
    ]))->assertSuccessful();
});
