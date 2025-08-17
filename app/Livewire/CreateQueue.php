<?php

namespace App\Livewire;

use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Livewire\Component;

class CreateQueue extends Component implements HasForms
{
    use InteractsWithForms;

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->label('Name')
                    ->placeholder('Enter Full Name')
                    ->extraInputAttributes(['class' => 'queue-form-input'])
                    ->extraAttributes(['class' => 'queue-form-label'])
                    ->required(),

                TextInput::make('mobile')
                    ->tel() // or
                    ->minLength(10)
                    ->maxLength(10)
                    ->label('Number (optional)')
                    ->helperText('Mobile number must start with +63')
                    ->extraInputAttributes(['class' => 'queue-form-input'])
                    ->extraAttributes(['class' => 'queue-form-label'])
                    ->placeholder('+63 9XX XXX XXXX'),

                Radio::make('priority_type')
                    ->label('For Priority Lane (Check One)')
                    ->extraAttributes(['class' => 'queue-form-label'])
                    ->extraInputAttributes(['class' => 'queue-form-input'])
                    ->options([
                        1 => 'Senior Citizen (Nakakatanda)',
                        2 => 'Person with Disability (PWD) (May Kapansanan)',
                        3 => 'Pregnant Woman (Buntis)',
                    ])

                // ...
            ])
            ->statePath('data');
    }

    public function create(): void
    {
        dd($this->form->getState());
    }


    public function render()
    {
        return view('public_pages.tablet-view');
    }
}
