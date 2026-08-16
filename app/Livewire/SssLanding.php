<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class SssLanding extends Component
{

    public $step = 1;
    public $page = 'landing';


    public function mount(): void
    {
        if (Auth::check()) {
            $this->redirect('/admin', navigate: true);
        }
    }

    public function render()
    {
        return view('livewire.register.registration');
    }
}
