<div class="p-8 bg-white rounded-lg shadow-md w-full max-w-lg mx-auto">
        
    <h1 class="text-2xl font-bold text-center text-gray-800 mb-6">
        {{ __('Your Request Status') }}
    </h1>

    <div class="mb-4">
        <span class="text-sm font-medium text-gray-500">{{ __('Status') }}:</span>
        
        @php
            $status = $requestData['status'];
            $statusClass = match($status) {
                'approved' => 'bg-green-100 text-green-800',
                'rejected' => 'bg-red-100 text-red-800',
                'reviewing' => 'bg-yellow-100 text-yellow-800',
                default => 'bg-blue-100 text-blue-800',
            };
        @endphp
        
        <span class="px-3 py-1 inline-flex text-md leading-5 font-semibold rounded-full {{ $statusClass }}">
            {{ __($status) }}
        </span>
    </div>

    <div class="text-center text-gray-600">
        @switch($status)
            @case('pending')
                <p>{{ __('Your application is pending submission.') }}</p>
                @break
            @case('reviewing')
                <p>{{ __('Your request is currently being reviewed by a licensed professional.') }}</p>
                @break
            @case('approved')
                <p class="mb-4">{{ __('Congratulations! Your request has been approved.') }}</p>
                
                <button onclick="window.open('{{ config('services.api.public_url') }}/v1/esa-request/{{ $requestData['id'] }}/download?token={{ session('api_token') }}', '_blank')"
                        class="mt-4 px-4 py-2 bg-green-600 text-white rounded-md shadow-sm hover:bg-green-700 transition">
                    Download Certificate (PDF)
                </button>
            @break
            @case('rejected')
                <p>{{ __('We are sorry, your request was not approved.') }}</p>
                @break
        @endswitch
    </div>
</div>