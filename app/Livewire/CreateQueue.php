<?php

namespace App\Livewire;

use App\Models\Queue;
use App\Models\Station;
use App\Models\Transaction;
use Carbon\Carbon;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Illuminate\Support\HtmlString;
use Livewire\Component;

class CreateQueue extends Component implements HasForms
{
    use InteractsWithForms;

    public $stations;
    public $selected_station;


    public $transactions;
    public $selected_transaction;

    public $viewPath;
    public $current_page = [];

    public $formData;

    public $queue_details;


    public ?array $data = [];

    public function mount(): void
    {
        $this->current_page = $this->getPageDetails(1);
        $this->stations = Station::all();
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Name')
                    ->placeholder('Input Name')
                    ->maxLength(8)
                    ->extraInputAttributes(['class' => 'queue-form-input'])
                    ->extraAttributes(['class' => 'queue-form-label'])
                    ->required(),

                TextInput::make('mobile')
                    ->tel() // or
                    ->minLength(11)
                    ->maxLength(12)
                    ->label('Number (optional)')
                    ->helperText('Mobile number must start with (09)')
                    ->extraInputAttributes(['class' => 'queue-form-input'])
                    ->extraAttributes(['class' => 'queue-form-label'])
                    ->placeholder('09XX XXX XXXX'),

                Section::make('')
                    ->hiddenLabel(true)
                    ->columns(1)
                    ->schema([
                        Radio::make('priority_type')
                            ->label('For Priority Lane (Check One)')
                            ->helperText(new HtmlString('<span style="color:red;!important" class="text-xs italic">Select this option only if you qualify for the priority lane. Ineligible selections will result in returning to the regular queue and being placed last.</span>'))
                            ->extraAttributes(['class' => 'queue-form-label'])
                            ->extraInputAttributes(['class' => 'queue-form-input'])
                            ->options([
                                1 => 'Senior Citizen (Nakakatanda)',
                                2 => 'Person with Disability (PWD) (May Kapansanan)',
                                3 => 'Pregnant Woman (Buntis)',
                            ])
                    ]),
                // ...
            ])
            ->statePath('data');
    }

    public function back_button(){
        $this->getPageDetails($this->current_page['page_prev_page']);
    }


    public function gotoSubmenu($station){
        $station = Station::find($station);
        $this->selected_station = $station;
        $this->transactions = $station->transactions()->get();
        $this->current_page = $this->getPageDetails(2);
    }


    public function gotoViewTransaction($transaction){
        $transaction = Transaction::find($transaction);
        $this->selected_transaction = $transaction;
        $this->current_page = $this->getPageDetails(3);
    }




    public function proceedConfirmation()
    {
        $this->form->validate();
        $this->current_page = $this->getPageDetails(5);
    }

    public function generate_queue(){
        $today = Carbon::today();

        // Get the transaction (must have a 'code' column like 'BP', 'C', etc.)
        $transaction = $this->selected_transaction;
        $stationCode = $transaction->station->code;

        // Get last queue for this station today
        $lastQueue = Queue::whereDate('created_at', $today)
            ->whereHas('transaction.station', function ($query) use ($stationCode) {
                $query->where('code', $stationCode);
            })
            ->orderBy('id', 'desc')
            ->first();
        if ($lastQueue) {
            $lastNumber = (int) $lastQueue->queue_number;
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }
        $queueNumber = str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        $this->queue_details = Queue::create([
            'queue_number' => $queueNumber,
            'transaction_id' => $transaction->id,
            'name' => $this->form->getState()['name'],
            'mobile_num' => $this->form->getState()['mobile'],
            'priority_type' => $this->form->getState()['priority_type'],
        ]);
        $this->current_page = $this->getPageDetails(6);
    }

    public function render()
    {
        return view('public_pages.tablet-view');
    }

    public function getPageDetails($page_number){
        switch ($page_number) {
            case 1:
                $this->current_page = [
                    'page_header' => 'Welcome',
                    'page_description' => 'Pumili ng serbisyo para makuha ang iyong numero.',
                    'page_prev_page' => null,
                    'page_show_prev_button' => false,
                    'display_header_text' => true,
                    'target_blade' => 'welcome_screen',
                ];
                break;
            case 2:
                $this->current_page = [
                    'page_header' => $this->selected_station->name,
                    'page_description' => 'Pumili ng serbisyo para makuha ang iyong numero.',
                    'page_prev_page' => 1,
                    'page_show_prev_button' => true,
                    'display_header_text' => true,
                    'target_blade' => 'submenu_screen',
                ];
                break;

            case 3:
                $this->current_page = [
                    'page_header' => null,
                    'page_description' => null,
                    'page_prev_page' => 2,
                    'page_show_prev_button' => true,
                    'display_header_text' => false,
                    'target_blade' => 'view-transaction_screen',
                ];
                break;
            case 4:
                $this->current_page = [
                    'page_header' => null,
                    'page_description' => null,
                    'page_prev_page' => 3,
                    'page_show_prev_button' => true,
                    'display_header_text' => false,
                    'target_blade' => 'queue_form',
                ];
                break;
            case 5:
                $this->current_page = [
                    'page_header' => 'Queue Confirmation',
                    'page_description' => 'I-verify ang iyong impormasyon at kumpirmahin ang iyong numero.',
                    'page_prev_page' => 4,
                    'page_show_prev_button' => true,
                    'display_header_text' => false,
                    'target_blade' => 'queue_confirmation',
                ];
                break;
            case 6:
                $this->current_page = [
                    'page_header' => 'Queue Information',
                    'page_description' => 'I-verify ang iyong impormasyon at kumpirmahin ang iyong numero.',
                    'page_prev_page' => 5,
                    'page_show_prev_button' => false,
                    'display_header_text' => false,
                    'target_blade' => 'queue_info',
                ];
                break;
            default:
                $this->current_page = [
                    'page_header' => 'Welcome',
                    'page_description' => 'Pumili ng serbisyo para makuha ang iyong numero.',
                    'page_prev_page' => null,
                    'page_show_prev_button' => false,
                    'display_header_text' => true,
                    'target_blade' => 'welcome_screen',
                ];
        }
        $this->viewPath = 'public_pages.tablet-views.' . $this->current_page['target_blade'];
        return $this->current_page;
    }
}
