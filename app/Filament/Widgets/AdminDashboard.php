<?php

namespace App\Filament\Widgets;

use App\Models\Station;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Widgets\Widget;


class AdminDashboard extends Widget implements HasForms
{
    use \Filament\Forms\Concerns\InteractsWithForms;
    protected static ?int $sort = 3;

    protected static ?string $pollingInterval = null;
    protected static string $view = 'filament.widgets.admin-dashboard';
    protected int | string | array $columnSpan = 'full';
    public $station;
    public $stations;
    public $transaction;
    public $status;

    public $queue_number;
    public $queue_status;
    public $clent_name;
    public $service_name;
    public $required_documents;

    public $nextQueues;


    public function mount(){
        $this->stations = Station::all();

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
        return !auth()->user()?->hasRole('staff');
    }
}
