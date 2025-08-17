<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Lazy;


class ShowQueues extends Component
{
    public $showModal = false;

    public function render()
    {
        return view('public_pages.queue-board');
    }

    public function call_number(){
        $this->showModal = true;
        $this->dispatch('open-modal');
    }
    public function closeModal(){
        $this->showModal = false;
        // $this->dispatch('open-modal', id: 'edit-user');
    }



}
