<?php

namespace App\Livewire\Request;

use Livewire\Component;

class Status extends Component
{
    public array $requestData;

    public function render()
    {
        return view('livewire.request.status');
    }
}