<?php

namespace App\Livewire\Request;

use App\Services\ApiClient;
use Livewire\Component;
use App\Livewire\Dashboard;

class Wizard extends Component
{
    public array $requestData;
    public int $step;

    // --- Form Properties ---
    public string $certificate_name = '';
    public array $userPets = [];
    public array $problem_checkboxes = [];
    public string $description = '';
    public bool $terms_accepted = false;

    public array $challengeOptions = [
        'Anxiety', 'Depression', 'Stress', 'Panic Attacks', 'Social Phobia', 'Other'
    ];

    // Modal Add Pet
    public bool $showingAddPetModal = false;
    public string $pet_name = '';
    public string $pet_type = 'Dog';
    public ?string $pet_breed = null;
    public ?int $pet_age = null;
    public ?string $pet_notes = null;
    
    // Modal Edit Pet
    public bool $showingEditPetModal = false;
    public ?int $editingPetId = null;
    public string $edit_pet_name = '';
    public string $edit_pet_type = '';
    public ?string $edit_pet_breed = null;
    public ?int $edit_pet_age = null;
    public ?string $edit_pet_notes = null;

    public bool $showingDeletePetModal = false;
    public ?int $deletingPetId = null;
    public string $deletingPetName = '';

    public function mount(array $requestData)
    {
        $this->requestData = $requestData;
        $this->step = $this->requestData['wizard_step'] ?? 1;

        $this->certificate_name = $this->requestData['certificate_name'] 
                                ?? session('user_name', '');
        $this->problem_checkboxes = $this->requestData['problem_checkboxes'] ?? [];
        $this->description = $this->requestData['description'] ?? '';
        
        $this->terms_accepted = false;
    }

    public function loadUserPets(ApiClient $client)
    {
        $res = $client->get('/v1/pets');
        if ($res['ok'] ?? false) {
            $this->userPets = $res['data'];
        }
    }

    protected function validateStep()
    {
        if ($this->step == 2) {
            $this->validate(['certificate_name' => 'required|string|max:255']);
        }
        if ($this->step == 4) {
            $this->validate([
                'problem_checkboxes' => 'required|array|min:1'
            ], ['problem_checkboxes.min' => __('You must select at least one challenge.')]);
        }
        if ($this->step == 5) {
            $this->validate([
                'description' => 'required|string|min:20|max:5000'
            ], ['description.required' => __('This field is required.')]);
        }
        if ($this->step == 6) {
            $this->validate([
                'terms_accepted' => 'accepted'
            ], ['terms_accepted.accepted' => __('You must accept the terms to continue.')]);
        }
    }
    
    /**
     * Returns ONLY the payload for the Livewire PUT.
     */
    protected function collectStepData(ApiClient $client): array
    {
        if ($this->step == 2) {
            return ['certificate_name' => $this->certificate_name];
        }
        
        if ($this->step == 3) {
            return [];
        }

        if ($this->step == 4) {
            return ['problem_checkboxes' => $this->problem_checkboxes];
        }
        if ($this->step == 5) {
            return ['description' => $this->description];
        }
        if ($this->step == 6) {
            return [
                'terms_accepted_at' => now(),
                'status' => 'pending'
            ];
        }
        return [];
    }

    public function saveAndContinue()
    {
        $client = app(ApiClient::class);

        $this->validateStep();

        if ($this->step == 3) {
            $this->loadUserPets($client);
            $allPetIds = array_column($this->userPets, 'id');
            
            $client->authedPost("/v1/esa-request/{$this->requestData['id']}/pets", [
                'pet_ids' => $allPetIds
            ]);
        }
        
        $stepData = $this->collectStepData($client);

        if ($this->step != 6) {
            $stepData['wizard_step'] = $this->step + 1;
        }
        
        $res = $client->put("/v1/esa-request/{$this->requestData['id']}", $stepData);
        
        if (!($res['ok'] ?? false)) {
            session()->flash('error', $res['error'] ?? 'Failed to save progress..');
            return;
        }

        $this->requestData = $res['data'];
        
        if ($this->step == 6) {
            return $this->redirect(route('dashboard'), navigate: true); 
        } else {
            $this->step++;
        }
    }
    
    public function previousStep()
    {
        $client = app(ApiClient::class);

        if ($this->step > 1) {
            $this->step--;
            
            $client->put("/v1/esa-request/{$this->requestData['id']}", [
                'wizard_step' => $this->step
            ]);
        }
    }

    // --- Pet Validation Rules ---
    protected function petRules(): array
    {
        return [
            'pet_name'  => ['required', 'string', 'max:255'],
            'pet_type'  => ['required', 'string', 'max:255'],
            'pet_breed' => ['nullable', 'string', 'max:255'],
            'pet_age'   => ['nullable', 'integer', 'min:0', 'max:100'],
            'pet_notes' => ['nullable', 'string'],
        ];
    }
    protected function validationAttributes(): array
    {
        return [
            'pet_name' => 'nome', 'pet_type' => 'tipo',
            'edit_pet_name' => 'nome', 'edit_pet_type' => 'tipo',
        ];
    }
    
    public function openAddPetModal()
    {
        $this->reset(['pet_name', 'pet_type', 'pet_breed', 'pet_age', 'pet_notes']);
        $this->resetErrorBag();
        $this->showingAddPetModal = true;
    }
    public function closeAddPetModal() { $this->showingAddPetModal = false; }

    public function savePet()
    {
        $client = app(ApiClient::class);
        $validatedData = $this->validate($this->petRules());
        $petData = [
            'name' => $validatedData['pet_name'], 'type' => $validatedData['pet_type'],
            'breed' => $validatedData['pet_breed'], 'age' => $validatedData['pet_age'],
            'notes' => $validatedData['pet_notes'],
        ];
        $res = $client->authedPost('/v1/pets', $petData);
        if (!($res['ok'] ?? false)) {
            session()->flash('error', $res['error'] ?? 'Não foi possível salvar o pet.');
            return;
        }
        $this->loadUserPets($client);
        $this->closeAddPetModal();
    }

    public function openEditPetModal(int $petId)
    {
        $client = app(ApiClient::class);
        $pet = $client->get("/v1/pets/{$petId}");
        
        if (!($pet['ok'] ?? false)) {
            session()->flash('error', $pet['error'] ?? "We were unable to load the pet's data.");
            return;
        }
        $petData = $pet['data'];

        $this->editingPetId = $petData['id'];
        $this->edit_pet_name = $petData['name'];
        $this->edit_pet_type = $petData['type'];
        $this->edit_pet_breed = $petData['breed'];
        $this->edit_pet_age = $petData['age'];
        $this->edit_pet_notes = $petData['notes'];

        $this->resetErrorBag();
        $this->showingEditPetModal = true;
    }
    public function closeEditPetModal() { $this->showingEditPetModal = false; }
    
    public function updatePet()
    {
        $client = app(ApiClient::class);
        $this->pet_name = $this->edit_pet_name;
        $this->pet_type = $this->edit_pet_type;
        $this->pet_breed = $this->edit_pet_breed;
        $this->pet_age = $this->edit_pet_age;
        $this->pet_notes = $this->edit_pet_notes;
        
        $validatedData = $this->validate($this->petRules());
        
        $res = $client->put("/v1/pets/{$this->editingPetId}", [
            'name' => $validatedData['pet_name'],
            'type' => $validatedData['pet_type'],
            'breed' => $validatedData['pet_breed'],
            'age' => $validatedData['pet_age'],
            'notes' => $validatedData['pet_notes'],
        ]);

        if (!($res['ok'] ?? false)) {
            session()->flash('error', $res['error'] ?? 'It was not possible to update the pet.');
            return;
        }
        
        session()->flash('success', __('Pet updated successfully!'));
        $this->loadUserPets($client);
        $this->closeEditPetModal();
    }

    public function openDeletePetModal(int $petId, string $petName)
    {
        $this->deletingPetId = $petId;
        $this->deletingPetName = $petName;
        $this->showingDeletePetModal = true;
    }
    public function closeDeletePetModal() { $this->showingDeletePetModal = false; }
    
    public function deletePet()
    {
        $client = app(ApiClient::class);
        $res = $client->delete("/v1/pets/{$this->deletingPetId}");

        if (!($res['ok'] ?? false)) {
            session()->flash('error', $res['error'] ?? 'It was not possible to delete the pet.');
            return;
        }
        
        session()->flash('success', __('Pet deleted successfully!'));
        $this->loadUserPets($client);
        $this->closeDeletePetModal();
    }
    
    public function render(ApiClient $client)
    {
        if ($this->step == 3) {
            $this->loadUserPets($client);
        }
        
        return view('livewire.request.wizard');
    }
}