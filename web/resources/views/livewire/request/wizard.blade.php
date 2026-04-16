<div class="p-8 bg-white rounded-lg shadow-md w-full max-w-lg mx-auto">

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

    @php
        $btnPrev = 'inline-flex items-center px-4 py-2 border border-gray-300 bg-white rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 cursor-pointer';
        $btnNext = 'inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 cursor-pointer';
        $input   = 'mt-1 block w-full px-3 py-2 border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50';
    @endphp

    @if ($step == 1)
        <div id="step-1">
            <h1 class="text-2xl font-bold text-center text-gray-800 mb-6">{{ __('Welcome to Your Application') }}</h1>
            <div class="text-gray-600 space-y-4">
                <p>{{ __('This wizard will guide you through the required steps to submit your request for an Emotional Support Animal (ESA) letter.') }}</p>
                <p class="font-medium text-gray-700">{{ __('Your progress is saved automatically at each step.') }}</p>
            </div>
            <div class="flex justify-end mt-8">
                <button type="button" wire:click="saveAndContinue" class="{{ $btnNext }}">{{ __('Get Started') }}</button>
            </div>
        </div>
    @endif

    @if ($step == 2)
        <div id="step-2">
            <h1 class="text-2xl font-bold text-gray-800 mb-4">{{ __('Step 2: Your Name') }}</h1>
            <p class="text-gray-600 mb-6">{{ __('This name will be printed on your certificate.') }}</p>
            <form wire:submit="saveAndContinue">
                <div>
                    <label for="certificate_name" class="block text-sm font-medium text-gray-700">{{ __('Certificate Name') }}</label>
                    <input type="text" wire:model="certificate_name" id="certificate_name" class="{{ $input }}">
                    @error('certificate_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div class="flex justify-between items-center mt-8">
                    <button type="button" wire:click="previousStep" class="{{ $btnPrev }}">{{ __('Previous Step') }}</button>
                    <button type="submit" class="{{ $btnNext }}">{{ __('Next Step') }}</button>
                </div>
            </form>
        </div>
    @endif

    @if ($step == 3)
        <div id="step-3">
            <h1 class="text-2xl font-bold text-gray-800 mb-4">{{ __('Step 3: Select Your Pet(s)') }}</h1>
            <p class="text-gray-600 mb-6">{{ __('The pets listed here will be included in your request.') }}</p>
            <div class="space-y-4">
                @forelse($userPets as $pet)
                    <div wire:key="pet-{{ $pet['id'] }}" class="flex items-center justify-between p-4 border border-gray-200 rounded-md">
                        <div class="ml-3 text-sm">
                            <span class="font-medium text-gray-900">{{ $pet['name'] }}</span>
                            <p class="text-gray-500">{{ $pet['type'] }} - {{ $pet['breed'] ?? 'N/A' }}</p>
                        </div>
                        <div class="space-x-4">
                            <button type="button" wire:click="openEditPetModal({{ $pet['id'] }})" class="text-sm font-medium text-blue-600 hover:text-blue-900 cursor-pointer">{{ __('Edit') }}</button>
                            <button type="button" wire:click="openDeletePetModal({{ $pet['id'] }}, '{{ $pet['name'] }}')" class="text-sm font-medium text-red-600 hover:text-red-900 cursor-pointer">{{ __('Delete') }}</button>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-gray-500 p-4 border border-dashed rounded-md">{{ __('You have not added any pets yet.') }}</div>
                @endforelse
            </div>
            <div class="mt-4">
                <button type="button" wire:click="openAddPetModal" class="text-sm font-medium text-blue-600 hover:text-blue-900 cursor-pointer">+ {{ __('Add a Pet') }}</button>
            </div>
            <div class="flex justify-between items-center mt-8">
                <button type="button" wire:click="previousStep" class="{{ $btnPrev }}">{{ __('Previous Step') }}</button>
                <button type="button" wire:click="saveAndContinue" class="{{ $btnNext }} @if(empty($userPets)) opacity-50 cursor-not-allowed @endif" @if(empty($userPets)) disabled title="{{ __('You must add a pet to continue.') }}" @endif>{{ __('Next Step') }}</button>
            </div>
        </div>
    @endif

    @if ($step == 4)
        <div id="step-4">
            <h1 class="text-2xl font-bold text-gray-800 mb-4">{{ __('Step 4: Your Challenges') }}</h1>
            <p class="text-gray-600 mb-6">{{ __('Please select the challenges your emotional support animal helps you with.') }}</p>
            <form wire:submit="saveAndContinue">
                <div class="space-y-4">
                    @foreach($challengeOptions as $option)
                        <label for="challenge_{{ $option }}" class="flex items-center p-4 border border-gray-200 rounded-md has-[:checked]:bg-blue-50 has-[:checked]:border-blue-300 transition cursor-pointer">
                            <input type="checkbox" wire:model="problem_checkboxes" value="{{ $option }}" id="challenge_{{ $option }}" class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                            <span class="ml-3 text-sm font-medium text-gray-900">{{ __($option) }}</span>
                        </label>
                    @endforeach
                    @error('problem_checkboxes') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div class="flex justify-between items-center mt-8">
                    <button type="button" wire:click="previousStep" class="{{ $btnPrev }}">{{ __('Previous Step') }}</button>
                    <button type="submit" class="{{ $btnNext }}">{{ __('Next Step') }}</button>
                </div>
            </form>
        </div>
    @endif

    @if ($step == 5)
        <div id="step-5">
            <h1 class="text-2xl font-bold text-gray-800 mb-4">{{ __('Step 5: Your Description') }}</h1>
            <p class="text-gray-600 mb-6">{{ __('Briefly describe how your emotional support animal helps you.') }}</p>
            <form wire:submit="saveAndContinue">
                <div>
                    <label for="description" class="sr-only">{{ __('Description') }}</label>
                    <textarea wire:model="description" id="description" rows="6" class="{{ $input }}"></textarea>
                    @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div class="flex justify-between items-center mt-8">
                    <button type="button" wire:click="previousStep" class="{{ $btnPrev }}">{{ __('Previous Step') }}</button>
                    <button type="submit" class="{{ $btnNext }}">{{ __('Next Step') }}</button>
                </div>
            </form>
        </div>
    @endif

    @if ($step == 6)
        <div id="step-6">
            <h1 class="text-2xl font-bold text-gray-800 mb-4">{{ __('Step 6: Terms & Conditions') }}</h1>
            <p class="text-gray-600 mb-6">{{ __('Please read and accept the terms and conditions to proceed.') }}</p>

            <form wire:submit="saveAndContinue">

                <div class="h-48 overflow-y-scroll p-4 border border-gray-300 rounded-md bg-gray-50 text-sm text-gray-500">
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed cursus ante dapibus diam. Sed nisi. Nulla quis sem at nibh elementum imperdiet. Duis sagittis ipsum. Praesent mauris. Fusce nec tellus sed augue semper porta. Mauris massa. Vestibulum lacinia arcu eget nulla.</p>
                    <p class="mt-2">Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Curabitur sodales ligula in libero. Sed dignissim lacinia nunc. Curabitur tortor. Pellentesque nibh. Aenean quam. In scelerisque sem at dolor. Maecenas mattis. Sed convallis tristique sem. Proin ut ligula vel nunc egestas porttitor.</p>
                </div>

                <div class="mt-6">
                    <label for="terms_accepted" class="flex items-center cursor-pointer">
                        <input type="checkbox" wire:model="terms_accepted" id="terms_accepted" class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                        <span class="ml-2 text-sm text-gray-700">{{ __('I have read and agree to the terms and conditions.') }}</span>
                    </label>
                    @error('terms_accepted') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="flex justify-between items-center mt-8">
                    <button type="button" wire:click="previousStep" class="{{ $btnPrev }}">{{ __('Previous Step') }}</button>

                    <button type="submit"
                            wire:loading.attr="disabled"
                            wire:loading.class="opacity-75"
                            class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 cursor-pointer">

                        <svg wire:loading wire:target="saveAndContinue" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>

                        <span wire:loading wire:target="saveAndContinue">
                            {{ __('Submitting...') }}
                        </span>

                        <span wire:loading.remove wire:target="saveAndContinue">
                            {{ __('Submit Application') }}
                        </span>
                    </button>
                </div>
            </form>
        </div>
    @endif

    @if($showingAddPetModal)
        <div class="fixed inset-0 z-30 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" wire:click="closeAddPetModal"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="relative z-40 inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form wire:submit="savePet">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">{{ __('Add Pet Modal Title') }}</h3>
                            <div class="mt-4 space-y-4">
                                <div>
                                    <label for="pet_name" class="block text-sm font-medium text-gray-700">{{ __('Name') }}</label>
                                    <input type="text" wire:model="pet_name" id="pet_name" class="{{ $input }}">
                                    @error('pet_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="pet_type" class="block text-sm font-medium text-gray-700">{{ __('Type') }}</label>
                                    <input type="text" wire:model="pet_type" id="pet_type" class="{{ $input }}">
                                    @error('pet_type') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="pet_breed" class="block text-sm font-medium text-gray-700">{{ __('Breed') }}</label>
                                    <input type="text" wire:model="pet_breed" id="pet_breed" class="{{ $input }}">
                                    @error('pet_breed') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="pet_age" class="block text-sm font-medium text-gray-700">{{ __('Age (optional)') }}</label>
                                    <input type="number" wire:model="pet_age" id="pet_age" class="{{ $input }}">
                                    @error('pet_age') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="pet_notes" class="block text-sm font-medium text-gray-700">{{ __('Notes (optional)') }}</label>
                                    <textarea wire:model="pet_notes" id="pet_notes" rows="3" class="{{ $input }}"></textarea>
                                    @error('pet_notes') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 cursor-pointer sm:ml-3 sm:w-auto sm:text-sm">{{ __('Save Pet') }}</button>
                            <button type="button" wire:click="closeAddPetModal" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 cursor-pointer sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">{{ __('Cancel') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    @if($showingEditPetModal)
        <div class="fixed inset-0 z-30 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" wire:click="closeEditPetModal"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="relative z-40 inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form wire:submit="updatePet">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">{{ __('Edit Pet') }}</h3>
                            <div class="mt-4 space-y-4">
                                <div>
                                    <label for="edit_pet_name" class="block text-sm font-medium text-gray-700">{{ __('Name') }}</label>
                                    <input type="text" wire:model="edit_pet_name" id="edit_pet_name" class="{{ $input }}">
                                    @error('pet_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="edit_pet_type" class="block text-sm font-medium text-gray-700">{{ __('Type') }}</label>
                                    <input type="text" wire:model="edit_pet_type" id="edit_pet_type" class="{{ $input }}">
                                    @error('pet_type') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="edit_pet_breed" class="block text-sm font-medium text-gray-700">{{ __('Breed') }}</label>
                                    <input type="text" wire:model="edit_pet_breed" id="edit_pet_breed" class="{{ $input }}">
                                    @error('pet_breed') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="edit_pet_age" class="block text-sm font-medium text-gray-700">{{ __('Age (optional)') }}</label>
                                    <input type="number" wire:model="edit_pet_age" id="edit_pet_age" class="{{ $input }}">
                                    @error('pet_age') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="edit_pet_notes" class="block text-sm font-medium text-gray-700">{{ __('Notes (optional)') }}</label>
                                    <textarea wire:model="edit_pet_notes" id="edit_pet_notes" rows="3" class="{{ $input }}"></textarea>
                                    @error('pet_notes') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 cursor-pointer sm:ml-3 sm:w-auto sm:text-sm">{{ __('Save Changes') }}</button>
                            <button type="button" wire:click="closeEditPetModal" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 cursor-pointer sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">{{ __('Cancel') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    @if($showingDeletePetModal)
        <div class="fixed inset-0 z-30 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" wire:click="closeDeletePetModal"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="relative z-40 inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">{{ __('Delete Pet Confirmation') }}</h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">
                                        {{ __('Are you sure you want to delete this pet?') }} <strong>{{ $deletingPetName }}</strong>?
                                        {{ __('This action cannot be undone.') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button"
                                wire:click="deletePet"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 cursor-pointer sm:ml-3 sm:w-auto sm:text-sm">
                            {{ __('Confirm Delete') }}
                        </button>
                        <button type="button"
                                wire:click="closeDeletePetModal"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 cursor-pointer sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            {{ __('Cancel') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>
