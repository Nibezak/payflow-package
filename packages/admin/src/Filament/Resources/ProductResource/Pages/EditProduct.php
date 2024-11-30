<?php

namespace Payflow\Admin\Filament\Resources\ProductResource\Pages;

use Filament\Actions;
use Filament\Forms;
use Filament\Support\Facades\FilamentIcon;
use Payflow\Admin\Filament\Resources\ProductResource;
use Payflow\Admin\Support\Actions\Products\ForceDeleteProductAction;
use Payflow\Admin\Support\Pages\BaseEditRecord;

class EditProduct extends BaseEditRecord
{
    protected static string $resource = ProductResource::class;

    public static bool $formActionsAreSticky = true;

    public function getTitle(): string
    {
        return __('payflowpanel::product.pages.edit.title');
    }

    public static function getNavigationLabel(): string
    {
        return __('payflowpanel::product.pages.edit.title');
    }

    public static function getNavigationIcon(): ?string
    {
        return FilamentIcon::resolve('payflow::basic-information');
    }

    protected function getDefaultHeaderActions(): array
    {
        return [
            Actions\EditAction::make('update_status')
                ->label(
                    __('payflowpanel::product.actions.edit_status.label')
                )
                ->modalHeading(
                    __('payflowpanel::product.actions.edit_status.heading')
                )
                ->record(
                    $this->record
                )->form([
                    Forms\Components\Radio::make('status')->options([
                        'published' => __('payflowpanel::product.form.status.options.published.label'),
                        'draft' => __('payflowpanel::product.form.status.options.draft.label'),
                    ])
                        ->descriptions([
                            'published' => __('payflowpanel::product.form.status.options.published.description'),
                            'draft' => __('payflowpanel::product.form.status.options.draft.description'),
                        ])->live(),
                ]),
            Actions\DeleteAction::make(),
            ForceDeleteProductAction::make(),
            Actions\RestoreAction::make(),
        ];
    }

    public function getRelationManagers(): array
    {
        return [];
    }
}
