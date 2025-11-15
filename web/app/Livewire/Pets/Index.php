<?php

namespace App\Livewire\Pets;

use Livewire\Component;
use App\Services\ApiClient;

class Index extends Component
{
    public array $pets = [];

    public function mount(ApiClient $client)
    {
        $res = $client->get('/v1/pets');

        if ($res['ok'] ?? false) {
            $this->pets = $res['data'] ?? [];
        } else {
            $this->pets = [];
        }
    }

    public function render()
    {
        return view('livewire.pets.index');
    }
}