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

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Name') }}</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Email') }}</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Roles') }}</th>
                    <th scope="col" class="relative px-6 py-3"><span class="sr-only">{{ __('Actions') }}</span></th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($users as $user)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $user['name'] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user['email'] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            @foreach($user['roles'] as $role)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                             {{ $role == 'admin' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800' }}">
                                    {{ $role }}
                                </span>
                            @endforeach
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <button type="button" 
                                    wire:click="editUser({{ $user['id'] }})" 
                                    class="text-blue-600 hover:text-blue-900">
                                {{ __('Edit') }}
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                            {{ __('No users found.') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($showingEditModal)
        <div class="fixed inset-0 z-10 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" 
                     aria-hidden="true" 
                     wire:click="closeEditModal"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                
                <div class="relative z-20 inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    
                    <form wire:submit="updateUser">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                {{ __('Edit User') }}: {{ $form['name'] ?? '' }}
                            </h3>
                            
                            <div class="mt-4 border-t border-gray-200 pt-4">
                                <h4 class="text-md font-medium text-gray-700">{{ __('User Information') }}</h4>
                                <div class="mt-2 space-y-4">
                                    <div>
                                        <label for="name" class="block text-sm font-medium text-gray-700">{{ __('Name') }}</label>
                                        <input type="text" wire:model="form.name" id="name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                        @error('form.name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label for="email" class="block text-sm font-medium text-gray-700">{{ __('Email') }}</label>
                                        <input type="email" wire:model="form.email" id="email" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                        @error('form.email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label for="password" class="block text-sm font-medium text-gray-700">{{ __('Password (optional)') }}</label>
                                        <input type="password" wire:model="form.password" id="password" 
                                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" 
                                               placeholder="{{ __('Leave blank to keep unchanged') }}"
                                               autocomplete="new-password">
                                        @error('form.password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4 border-t border-gray-200 pt-4">
                                <h4 class="text-md font-medium text-gray-700">{{ __('Account Status') }}</h4>
                                <div class="mt-2 flex items-center">
                                    <input type="checkbox" wire:model="form.is_banned" id="is_banned" 
                                           class="h-4 w-4 text-red-600 border-gray-300 rounded
                                                  @if($form['id'] === session('auth_id')) opacity-50 cursor-not-allowed @endif"
                                           @if($form['id'] === session('auth_id')) disabled @endif>
                                           
                                    <label for="is_banned" 
                                           class="ml-2 block text-sm text-gray-900
                                                  @if($form['id'] === session('auth_id')) opacity-50 @endif">
                                        {{ __('User is Banned?') }}
                                    </label>
                                </div>
                                @error('form.is_banned') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div class="mt-4 border-t border-gray-200 pt-4">
                                <h4 class="text-md font-medium text-gray-700">{{ __('Assign Roles') }}</h4>
                                <div class="mt-2 space-y-2">
                                    @foreach($allRoles as $role)
                                    <div class="flex items-center">
                                        <input type="checkbox" 
                                               wire:model="form.roles" 
                                               value="{{ $role }}" 
                                               id="role_{{ $role }}" 
                                               class="h-4 w-4 text-blue-600 border-gray-300 rounded
                                                      @if($form['id'] === session('auth_id')) opacity-50 cursor-not-allowed @endif"
                                               @if($form['id'] === session('auth_id')) disabled @endif>
                                               
                                        <label for="role_{{ $role }}" 
                                               class="ml-2 block text-sm text-gray-900 capitalize
                                                      @if($form['id'] === session('auth_id')) opacity-50 @endif">
                                            {{ $role }}
                                        </label>
                                    </div>
                                    @endforeach
                                    @error('form.roles') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>

                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit" 
                                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 sm:ml-3 sm:w-auto sm:text-sm">
                                {{ __('Save Changes') }}
                            </button>
                            <button type="button" 
                                    wire:click="closeEditModal" 
                                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                {{ __('Cancel') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>