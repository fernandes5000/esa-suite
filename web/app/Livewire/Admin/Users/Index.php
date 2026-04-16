<?php

namespace App\Livewire\Admin\Users;

use App\Services\ApiClient;
use Livewire\Component;

class Index extends Component
{
    public array $users = [];
    public bool $showingEditModal = false;
    
    public array $allRoles = [];
    
    public array $form = [];

    protected function rules(): array
    {
        return [
            'form.name' => ['required', 'string', 'max:255'],
            'form.email' => ['required', 'email'],
            'form.password' => ['nullable', 'string', 'min:8'],
            'form.roles' => ['required', 'array'],
            'form.roles.*' => ['string'],
            'form.is_banned' => ['required', 'boolean'],
        ];
    }

    public function mount(ApiClient $client)
    {
        $res = $client->get('/v1/admin/users');

        if ($res['ok'] ?? false) {
            $this->users = $res['data']['data'] ?? $res['data'] ?? [];
        } else {
            $this->users = [];
            session()->flash('error', $res['error'] ?? __('Failed to load users.'));
        }
    }
    
    public function editUser(ApiClient $client, int $userId)
    {
        $this->resetForm();

        $res = $client->get("/v1/admin/users/{$userId}");

        if (!($res['ok'] ?? false)) {
            session()->flash('error', $res['error'] ?? 'Failed to load user data.');
            return;
        }

        $this->form = $res['data']['user'];
        $this->form['password'] = '';
        
        $this->allRoles = $res['data']['all_roles'];

        $this->showingEditModal = true;
    }

    public function updateUser(ApiClient $client)
    {
        $this->validate();

        $res = $client->put("/v1/admin/users/{$this->form['id']}", $this->form);

        if (!($res['ok'] ?? false)) {
            session()->flash('error', $res['error'] ?? 'Failed to update user.');
            return;
        }

        $this->closeEditModal();
        session()->flash('success', __('User updated successfully!'));

        $this->users = collect($this->users)->map(function ($user) use ($res) {
            if ($user['id'] === $res['data']['id']) {
                return $res['data'];
            }
            return $user;
        })->all();
    }

    public function closeEditModal()
    {
        $this->showingEditModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->form = [];
        $this->allRoles = [];
        $this->resetErrorBag();
    }

    public function render()
    {
        return view('livewire.admin.users.index')
            ->layout('layouts.app', ['header' => __('User Management')]);
    }
}