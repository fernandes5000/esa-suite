<?php

namespace App\Livewire\Pets;

use App\Services\ApiClient;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Index extends Component
{
    public array $pets = [];
    public bool $showingAddPetModal = false;

    public string $name = '';
    public string $type = 'Dog';
    public string $breed = '';
    public ?int $age = null;
    public string $notes = '';

    protected function rules(): array
    {
        return [
            'name'  => ['required', 'string', 'max:255'],
            'type'  => ['required', 'string', 'max:255'],
            'breed' => ['nullable', 'string', 'max:255'],
            'age'   => ['nullable', 'integer', 'min:0', 'max:100'],
            'notes' => ['nullable', 'string'],
        ];
    }

    public function mount(ApiClient $client)
    {
        $res = $client->get('/v1/pets');

        if ($res['ok'] ?? false) {
            $this->pets = $res['data'] ?? [];
        } else {
            $this->pets = [];
            session()->flash('error', $res['error'] ?? __('Failed to load pets.'));
        }
    }

    public function openAddPetModal()
    {
        $this->resetForm();
        $this->showingAddPetModal = true;
    }

    public function closeAddPetModal()
    {
        $this->showingAddPetModal = false;
    }

    public function resetForm()
    {
        $this->reset(['name', 'type', 'breed', 'age', 'notes']);
        $this->resetErrorBag();
    }

    public function savePet(ApiClient $client)
    {
        $validatedData = $this->validate();

        $res = $client->authedPost('/v1/pets', $validatedData);

        if (!($res['ok'] ?? false)) {
            session()->flash('error', $res['error'] ?? __('Could not save pet.'));
            return;
        }

        $this->pets[] = $res['data'];
        $this->closeAddPetModal();
        
        session()->flash('success', __('Pet added successfully!'));
    }

    public function render()
    {
        return view('livewire.pets.index')
            ->layout('layouts.app', ['header' => __('My Pets')]);
    }
}