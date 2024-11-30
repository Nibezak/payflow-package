<?php

namespace Payflow\Admin\Filament\Resources\OrderResource\Pages;

use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions;
use Filament\Forms;
use Filament\Notifications\Notification;
use Payflow\Admin\Filament\Resources\OrderResource;
use Payflow\Admin\Support\Pages\BaseEditRecord;

class EditOrder extends BaseEditRecord
{
    protected static string $resource = OrderResource::class;

    protected function getDefaultHeaderActions(): array
    {
        return [
            Actions\Action::make('payment related actions')
                ->color('gray')
                ->url('#'),
            Actions\Action::make('update_status')
                ->label(__('payflowpanel::order.action.update_status.label'))
                ->form([
                    Forms\Components\Select::make('status')
                        ->label(__('payflowpanel::order.form.status.label'))
                        ->default($this->record->status)
                        ->options(fn () => collect(config('payflow.orders.statuses', []))
                            ->mapWithKeys(fn ($data, $status) => [$status => $data['label']]))
                        ->required(),
                    Forms\Components\Placeholder::make('additional content and mailer'),
                ])
                ->modalWidth('md')
                ->slideOver()
                ->action(fn ($record, $data) => $record
                    ->update([
                        'status' => $data['status'],
                    ]))
                ->after(fn () => Notification::make()->title(__('payflowpanel::order.action.update_status.notification'))->success()->send()),
            Actions\Action::make('download_pdf')
                ->label(__('payflowpanel::order.action.download_order_pdf.label'))
                ->action(function () {
                    Notification::make()->title(__('payflowpanel::order.action.download_order_pdf.notification'))->success()->send();

                    return response()->streamDownload(function () {
                        echo Pdf::loadView('payflowpanel::pdf.order', [
                            'record' => $this->record,
                        ])->stream();
                    }, name: "Order-{$this->record->reference}.pdf");
                }),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
