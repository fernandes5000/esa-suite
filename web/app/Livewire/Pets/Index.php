<?php

namespace App\Livewire\Pets;

use Livewire\Component;
use App\Services\ApiClient;

class Index extends Component
{
    public array $pets = [];

    public function mount(ApiClient $client)
    {
        $token = session('api_token');

        $res = $client->withToken($token)->get('/api/v1/pets');

        $this->pets = $res->json('data') ?? [];
    }

    public function render()
    {
        return view('pets.index');
    }
}
