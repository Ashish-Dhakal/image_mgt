<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    <div
        x-data="{
            selectedImages: @entangle($getStatePath()),
            images: @js($getImages()),
            selectedImagesData: @js($getSelectedImages()),
            isMultiple: @js($isMultiple()),
            isSearchable: @js($isSearchable()),
            isPreloaded: @js($isPreloaded()),
            showPreview: @js($shouldShowPreview()),
            searchQuery: '',
            isModalOpen: false,
            currentPage: 1,
            perPage: 30,
            totalPages: 1,
            tempSelectedImage: null,
            
            init() {
                this.totalPages = Math.ceil(this.images.length / this.perPage);
                this.$watch('selectedImages', (value) => {
                    if (value) {
                        if (this.isMultiple) {
                            this.selectedImagesData = this.images.filter(img => value.includes(img.id));
                        } else {
                            this.selectedImagesData = this.images.filter(img => img.id === value);
                        }
                    } else {
                        this.selectedImagesData = [];
                    }
                });
            },
            
            get filteredImages() {
                let filtered = this.images;
                if (this.searchQuery) {
                    filtered = filtered.filter(image => 
                        image.name.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                        (image.alt && image.alt.toLowerCase().includes(this.searchQuery.toLowerCase()))
                    );
                }
                const start = (this.currentPage - 1) * this.perPage;
                return filtered.slice(start, start + this.perPage);
            },
            
            get totalFilteredImages() {
                if (!this.searchQuery) return this.images.length;
                return this.images.filter(image => 
                    image.name.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                    (image.alt && image.alt.toLowerCase().includes(this.searchQuery.toLowerCase()))
                ).length;
            },
            
            selectImage(image) {
                this.tempSelectedImage = image;
            },
            
            confirmSelection() {
                if (this.tempSelectedImage) {
                    this.selectedImages = this.tempSelectedImage.id;
                    this.closeModal();
                }
            },
            
            isSelected(image) {
                return this.tempSelectedImage?.id === image.id;
            },
            
            openModal() {
                this.isModalOpen = true;
                this.currentPage = 1;
                this.tempSelectedImage = this.selectedImagesData[0] || null;
            },
            
            closeModal() {
                this.isModalOpen = false;
                this.tempSelectedImage = null;
            },
            
            nextPage() {
                if (this.currentPage < this.totalPages) {
                    this.currentPage++;
                }
            },
            
            previousPage() {
                if (this.currentPage > 1) {
                    this.currentPage--;
                }
            }
        }"
        class="space-y-4"
    >
        <!-- Preview Section -->
        <div class="flex items-start space-x-4">
            <div class="flex-1">
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                    <template x-if="selectedImagesData.length > 0">
                        <div class="relative group">
                            <img 
                                :src="selectedImagesData[0]?.url" 
                                :alt="selectedImagesData[0]?.alt"
                                class="w-full h-32 object-cover rounded-lg"
                            >
                            <button
                                @click="selectedImages = null"
                                class="absolute top-1 right-1 p-1 bg-red-500 text-white rounded-full opacity-0 group-hover:opacity-100 transition-opacity"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
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

        <!-- Full Screen Modal -->
        <div
            x-show="isModalOpen"
            class="fixed inset-0 z-50 overflow-hidden"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
        >
            <div class="absolute inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

            <div class="fixed inset-0 z-10 overflow-y-auto">
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                    <div
                        class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-0 sm:w-full sm:max-w-full h-screen"
                        x-transition:enter="ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave="ease-in duration-200"
                        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    >
                        <div class="bg-white h-full flex flex-col">
                            <!-- Header -->
                            <div class="px-4 py-3 border-b border-gray-200">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-lg font-medium text-gray-900">Select Image</h3>
                                    <button
                                        @click="closeModal()"
                                        type="button"
                                        class="rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-none"
                                    >
                                        <span class="sr-only">Close</span>
                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Search and Filters -->
                            <div class="px-4 py-3 border-b border-gray-200">
                                <div class="flex items-center space-x-4">
                                    <div class="flex-1">
                                        <input
                                            type="text"
                                            x-model="searchQuery"
                                            placeholder="Search images..."
                                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm"
                                        >
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        <span x-text="totalFilteredImages"></span> images found
                                    </div>
                                </div>
                            </div>

                            <!-- Image Grid -->
                            <div class="flex-1 overflow-y-auto p-4">
                                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 2xl:grid-cols-7 gap-4">
                                    <template x-for="image in filteredImages" :key="image.id">
                                        <div
                                            @click="selectImage(image)"
                                            class="relative cursor-pointer group"
                                            :class="{ 'ring-2 ring-primary-500': isSelected(image) }"
                                        >
                                            <img
                                                :src="image.url"
                                                :alt="image.alt"
                                                class="w-full h-40 object-cover rounded-lg"
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

                            <!-- Footer with Select Button -->
                            <div class="px-4 py-3 border-t border-gray-200">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1 flex justify-between sm:hidden">
                                        <button
                                            @click="previousPage()"
                                            :disabled="currentPage === 1"
                                            class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                                        >
                                            Previous
                                        </button>
                                        <button
                                            @click="nextPage()"
                                            :disabled="currentPage === totalPages"
                                            class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                                        >
                                            Next
                                        </button>
                                    </div>
                                    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                                        <div>
                                            <p class="text-sm text-gray-700">
                                                Showing
                                                <span class="font-medium" x-text="(currentPage - 1) * perPage + 1"></span>
                                                to
                                                <span class="font-medium" x-text="Math.min(currentPage * perPage, totalFilteredImages)"></span>
                                                of
                                                <span class="font-medium" x-text="totalFilteredImages"></span>
                                                results
                                            </p>
                                        </div>
                                        <div class="flex items-center space-x-4">
                                            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                                                <button
                                                    @click="previousPage()"
                                                    :disabled="currentPage === 1"
                                                    class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
                                                >
                                                    <span class="sr-only">Previous</span>
                                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                                <button
                                                    @click="nextPage()"
                                                    :disabled="currentPage === totalPages"
                                                    class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
                                                >
                                                    <span class="sr-only">Next</span>
                                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                            </nav>
                                            <button
                                                @click="confirmSelection()"
                                                :disabled="!tempSelectedImage"
                                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 disabled:opacity-50 disabled:cursor-not-allowed"
                                            >
                                                Select Image
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-dynamic-component> 