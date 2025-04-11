<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    <div
        x-data="{
            selectedImages: @entangle($getStatePath()),
            images: @js($getImages()),
            isMultiple: @js($isMultiple()),
            isSearchable: @js($isSearchable()),
            isPreloaded: @js($isPreloaded()),
            searchQuery: '',
            isModalOpen: false,
            
            get filteredImages() {
                if (!this.searchQuery) return this.images;
                return this.images.filter(image => 
                    image.name.toLowerCase().includes(this.searchQuery.toLowerCase())
                );
            },
            
            toggleImage(image) {
                if (this.isMultiple) {
                    if (!this.selectedImages) this.selectedImages = [];
                    const index = this.selectedImages.indexOf(image.id);
                    if (index === -1) {
                        this.selectedImages.push(image.id);
                    } else {
                        this.selectedImages.splice(index, 1);
                    }
                } else {
                    this.selectedImages = image.id;
                }
            },
            
            isSelected(image) {
                if (this.isMultiple) {
                    return this.selectedImages?.includes(image.id) ?? false;
                }
                return this.selectedImages === image.id;
            },
            
            openModal() {
                this.isModalOpen = true;
            },
            
            closeModal() {
                this.isModalOpen = false;
            }
        }"
        class="space-y-4"
    >
        <div class="flex items-center space-x-4">
            <div class="flex-1">
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                    <template x-if="selectedImages">
                        <template x-if="isMultiple">
                            <template x-for="imageId in selectedImages" :key="imageId">
                                <div class="relative group">
                                    <img 
                                        :src="images.find(img => img.id === imageId)?.url" 
                                        :alt="images.find(img => img.id === imageId)?.alt"
                                        class="w-full h-24 object-cover rounded-lg"
                                    >
                                    <button
                                        @click="toggleImage(images.find(img => img.id === imageId))"
                                        class="absolute top-1 right-1 p-1 bg-red-500 text-white rounded-full opacity-0 group-hover:opacity-100 transition-opacity"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </template>
                        </template>
                        <template x-if="!isMultiple && selectedImages">
                            <div class="relative group">
                                <img 
                                    :src="images.find(img => img.id === selectedImages)?.url" 
                                    :alt="images.find(img => img.id === selectedImages)?.alt"
                                    class="w-full h-24 object-cover rounded-lg"
                                >
                                <button
                                    @click="toggleImage(images.find(img => img.id === selectedImages))"
                                    class="absolute top-1 right-1 p-1 bg-red-500 text-white rounded-full opacity-0 group-hover:opacity-100 transition-opacity"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </template>
                    </template>
                </div>
            </div>
            <button
                @click="openModal()"
                type="button"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
            >
                Browse Media
            </button>
        </div>

        <!-- Modal -->
        <div
            x-show="isModalOpen"
            class="fixed inset-0 z-50 overflow-y-auto"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
        >
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div
                    x-show="isModalOpen"
                    class="fixed inset-0 transition-opacity"
                    aria-hidden="true"
                    @click="closeModal()"
                >
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>

                <div
                    class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full"
                    x-show="isModalOpen"
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                >
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                                    Select Image
                                </h3>
                                
                                <div class="space-y-4">
                                    <div x-show="isSearchable" class="relative">
                                        <input
                                            type="text"
                                            x-model="searchQuery"
                                            placeholder="Search images..."
                                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm"
                                        >
                                    </div>
                                    
                                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                                        <template x-for="image in filteredImages" :key="image.id">
                                            <div
                                                @click="toggleImage(image)"
                                                class="relative cursor-pointer group"
                                                :class="{ 'ring-2 ring-primary-500': isSelected(image) }"
                                            >
                                                <img
                                                    :src="image.url"
                                                    :alt="image.alt"
                                                    class="w-full h-24 object-cover rounded-lg"
                                                >
                                                <div
                                                    class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity"
                                                    :class="{ 'opacity-100': isSelected(image) }"
                                                >
                                                    <div class="text-white text-sm">
                                                        <template x-if="isSelected(image)">
                                                            <span>Selected</span>
                                                        </template>
                                                        <template x-if="!isSelected(image)">
                                                            <span>Click to select</span>
                                                        </template>
                                                    </div>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button
                            type="button"
                            @click="closeModal()"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary-600 text-base font-medium text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:ml-3 sm:w-auto sm:text-sm"
                        >
                            Done
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-dynamic-component> 