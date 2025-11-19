<?php

namespace App\Livewire\Therapist;

use App\Services\ApiClient;
use Livewire\Component;

class RequestQueue extends Component
{
    public array $requests = [];
    
    public bool $showingReviewModal = false;
    public ?array $selectedRequest = null;

    public function mount(ApiClient $client)
    {
        $this->loadRequests($client);
    }

    public function loadRequests(ApiClient $client)
    {
        $res = $client->get('/v1/therapist/requests');
        $this->requests = $res['ok'] ? $res['data'] : [];
    }

    public function openReviewModal($request)
    {
        $this->selectedRequest = $request;
        $this->showingReviewModal = true;
    }

    public function closeReviewModal()
    {
        $this->showingReviewModal = false;
        $this->selectedRequest = null;
    }

    public function approve()
    {
        $client = app(ApiClient::class);

        if (!$this->selectedRequest) return;

        $id = $this->selectedRequest['id'];
        
        $res = $client->authedPost("/v1/therapist/requests/{$id}/approve");
        
        if ($res['ok']) {
            session()->flash('success', 'Request Approved Successfully!');
            $this->closeReviewModal();
            
            $this->loadRequests($client); 
            
        } else {
            session()->flash('error', $res['error'] ?? 'Error approving request.');
        }
    }

    public function reject()
    {
        if (!$this->selectedRequest) return;
        
        $this->closeReviewModal();
    }

    public function render()
    {
        return view('livewire.therapist.request-queue');
    }
}