<x-filament-panels::page>
    <div class="space-y-6">
        <div class="p-4 bg-white rounded-xl shadow dark:bg-gray-800">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Upload Images</h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Upload one or more images to your media library.</p>
            
            <form wire:submit.prevent="create" class="mt-4 space-y-4">
                {{ $this->form }}
                
                <div class="flex justify-end">
                    <x-filament::button type="submit">
                        Upload Images
                    </x-filament::button>
                </div>
            </form>
        </div>
        
        <div class="p-4 bg-white rounded-xl shadow dark:bg-gray-800">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Media Library</h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Browse and manage your uploaded images.</p>
            
            <div class="mt-4">
                {{ $this->table }}
            </div>
        </div>
    </div>
</x-filament-panels::page> 