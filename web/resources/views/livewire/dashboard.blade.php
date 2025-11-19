<div>
    @if (session()->has('error'))
        <div class="mb-4 p-3 bg-red-100 text-red-700 rounded-md">
            {{ session('error') }}
        </div>
    @endif
    
    @if (session()->has('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-md">
            {{ session('success') }}
        </div>
    @endif

    @if($viewMode === 'therapist_queue')
        
        <livewire:therapist.request-queue />

    @elseif($activeRequest)
        
        @if($viewMode === 'wizard')
            <livewire:request.wizard :requestData="$activeRequest" />
            
        @elseif($viewMode === 'status')
            <livewire:request.status :requestData="$activeRequest" />
            
        @endif
        
    @endif
</div>