<?php

namespace App\Filament\Widgets;

use Filament\Forms\Components\Toggle;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Widgets\Widget;


class StaffDashboard extends Widget implements HasForms
{
    use \Filament\Forms\Concerns\InteractsWithForms;

    protected static ?string $pollingInterval = null;
    protected static string $view = 'filament.widgets.staff-dashboard';
    protected int | string | array $columnSpan = 'full';
    public $station;
    public $transaction;
    public $status;

    public $queue_number;
    public $queue_status;
    public $clent_name;
    public $service_name;
    public $required_documents;

    public $nextQueues;


    public function mount(){

        $this->nextQueues = [
            [
                'queue_number' => 'A1024',
                'client_name' => 'Jane Doe',
                'queue_status' => 'Waiting',
                'queue_status2' => 'Completed',
            ],
            [
                'queue_number' => 'B2048',
                'client_name' => 'John Smith',
                'queue_status' => 'Waiting',
                'queue_status2' => 'Skipped',

            ],
            [
                'queue_number' => 'C3096',
                'client_name' => 'Alice Johnson',
                'queue_status' => 'Waiting',
                'queue_status2' => 'Completed',

            ],
            [
                'queue_number' => 'A1024',
                'client_name' => 'Jane Doe',
                'queue_status' => 'Waiting',
                'queue_status2' => 'Completed',
            ],
            [
                'queue_number' => 'B2048',
                'client_name' => 'John Smith',
                'queue_status' => 'Waiting',
                'queue_status2' => 'Skipped',

            ],
            [
                'queue_number' => 'C3096',
                'client_name' => 'Alice Johnson',
                'queue_status' => 'Waiting',
                'queue_status2' => 'Completed',

            ],
            [
                'queue_number' => 'A1024',
                'client_name' => 'Jane Doe',
                'queue_status' => 'Waiting',
                'queue_status2' => 'Completed',
            ],
            [
                'queue_number' => 'B2048',
                'client_name' => 'John Smith',
                'queue_status' => 'Waiting',
                'queue_status2' => 'Skipped',

            ],
            [
                'queue_number' => 'C3096',
                'client_name' => 'Alice Johnson',
                'queue_status' => 'Waiting',
                'queue_status2' => 'Completed',

            ],
        ];

        $this->transaction = auth()->user()->transactions()->first();
        $this->station = auth()->user()->stations()->first();
        $this->status =  $this->station?->status;

        $this->queue_number = '4737';
        $this->queue_status = 'In Progress';
        $this->required_documents = $this->transaction?->required_documents;

    }
    protected function getForms(): array
    {
        return [
            'form1',
        ];
    }
    public function updated($property_name)
    {
        if($property_name == 'status'){
            $this->station->status = $this->{$property_name};
            $this->station->save();
        }
    }
    public function form1(Form $form): Form
    {
        return $form
            ->schema([
                Toggle::make('status')
                    ->label('')
                    ->reactive()
                    ->default($this->status)
                    ->onColor('success')
                    ->offColor('danger')
                    ->onIcon('heroicon-m-bolt')
            ]);
    }

    public static function canView(): bool
    {
        return auth()->user()?->hasRole('staff');
    }
}
