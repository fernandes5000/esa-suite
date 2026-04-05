<div class="p-6 bg-white shadow rounded-lg">
    <h2 class="text-xl font-bold mb-4 text-gray-800">Pending Approvals Queue</h2>

    @if (session()->has('success'))
        <div class="bg-green-100 text-green-800 p-3 rounded mb-4 border border-green-200">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-100 text-red-800 p-3 rounded mb-4 border border-red-200">
            {{ session('error') }}
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Certificate Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($requests as $req)
                    <tr wire:key="req-{{ $req['id'] }}" class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            #{{ $req['id'] }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $req['user']['name'] ?? 'Unknown' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $req['certificate_name'] }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ \Carbon\Carbon::parse($req['created_at'])->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <button wire:click="openReviewModal({{ json_encode($req) }})" 
                                    class="text-blue-600 hover:text-blue-900 font-bold">
                                Review Application &rarr;
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-gray-500 italic">
                            No pending requests found. Good job!
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($showingReviewModal && $selectedRequest)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" 
                     aria-hidden="true" 
                     wire:click="closeReviewModal"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                
                <div class="relative inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                    
                    <div class="bg-gray-50 px-4 py-3 border-b border-gray-200 flex justify-between items-center">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            Review Application #{{ $selectedRequest['id'] }}
                        </h3>
                        <button wire:click="closeReviewModal" class="text-gray-400 hover:text-gray-500">
                            <span class="sr-only">Close</span>
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="px-4 py-5 sm:p-6 space-y-6 max-h-[70vh] overflow-y-auto">
                        
                        <div>
                            <h4 class="text-sm font-bold text-gray-500 uppercase tracking-wide mb-2">Applicant Information</h4>
                            <div class="grid grid-cols-2 gap-4 bg-gray-50 p-3 rounded-md">
                                <div>
                                    <span class="text-xs text-gray-500 block">Account Name</span>
                                    <span class="text-sm font-medium text-gray-900">{{ $selectedRequest['user']['name'] ?? 'N/A' }}</span>
                                </div>
                                <div>
                                    <span class="text-xs text-gray-500 block">Certificate Name</span>
                                    <span class="text-sm font-medium text-gray-900">{{ $selectedRequest['certificate_name'] }}</span>
                                </div>
                                <div>
                                    <span class="text-xs text-gray-500 block">Email</span>
                                    <span class="text-sm text-gray-900">{{ $selectedRequest['user']['email'] ?? 'N/A' }}</span>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h4 class="text-sm font-bold text-gray-500 uppercase tracking-wide mb-2">Emotional Support Animals</h4>
                            @if(!empty($selectedRequest['pets']))
                                <ul class="border border-gray-200 rounded-md divide-y divide-gray-200">
                                    @foreach($selectedRequest['pets'] as $pet)
                                        <li class="pl-3 pr-4 py-3 flex items-center justify-between text-sm">
                                            <div class="w-0 flex-1 flex items-center">
                                                <span class="font-medium text-gray-900 truncate">{{ $pet['name'] }}</span>
                                                <span class="text-gray-500 ml-2">({{ $pet['type'] }} - {{ $pet['breed'] ?? 'N/A' }})</span>
                                            </div>
                                            <div class="ml-4 flex-shrink-0">
                                                <span class="text-xs text-gray-500">Age: {{ $pet['age'] ?? '?' }}</span>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-sm text-gray-500 italic">No pets listed.</p>
                            @endif
                        </div>

                        <div>
                            <h4 class="text-sm font-bold text-gray-500 uppercase tracking-wide mb-2">Reported Challenges</h4>
                            <div class="flex flex-wrap gap-2">
                                @foreach($selectedRequest['problem_checkboxes'] ?? [] as $problem)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        {{ $problem }}
                                    </span>
                                @endforeach
                            </div>
                        </div>

                        <div>
                            <h4 class="text-sm font-bold text-gray-500 uppercase tracking-wide mb-2">Personal Statement</h4>
                            <div class="bg-gray-50 p-4 rounded-md border border-gray-200">
                                <p class="text-sm text-gray-700 whitespace-pre-line">{{ $selectedRequest['description'] }}</p>
                            </div>
                        </div>

                    </div>

                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-200">
                        
                        <button type="button" 
                                wire:click="approve"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm transition">
                            Approve Request
                        </button>
                        
                        <button type="button" 
                                wire:click="reject"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition">
                            Reject
                        </button>
                        
                        <button type="button" 
                                wire:click="closeReviewModal"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:w-auto sm:text-sm transition">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>