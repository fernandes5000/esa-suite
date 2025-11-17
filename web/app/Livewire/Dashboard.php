<?php

namespace App\Livewire;

use App\Services\ApiClient;
use Livewire\Component;
use Livewire\Attributes\On;

class Dashboard extends Component
{
    public ?array $activeRequest = null;
    public string $viewMode = '';

    public function refreshData(ApiClient $client)
    {
        $res = $client->get('/v1/esa-request/active');

        if (!($res['ok'] ?? false)) {
            session()->flash('error', $res['error'] ?? 'Failed to load dashboard.');
            $this->activeRequest = null;
            return;
        }
        
        $this->activeRequest = $res['data'];

        match ($this->activeRequest['status']) {
            'draft' => $this->viewMode = 'wizard',
            'pending', 'reviewing', 'approved', 'rejected' => $this->viewMode = 'status',
            default => $this->viewMode = 'status',
        };

        if ($this->activeRequest['status'] === 'pending') {
            session()->flash('success', __('Application Submitted!'));
        }
    }

    public function mount(ApiClient $client)
    {
        $this->refreshData($client);
    }

    public function render()
    {
        return view('livewire.dashboard')
            ->layout('layouts.app', ['header' => __('Dashboard')]);
    }
}