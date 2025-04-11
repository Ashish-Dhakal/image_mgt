@php
    $imageUrl = Storage::url($image->image_path);
@endphp

<div class="space-y-2">
    <div class="relative aspect-video overflow-hidden rounded-lg bg-gray-100">
        <img 
            src="{{ $imageUrl }}" 
            alt="{{ $image->image_alitext ?? $image->image_name }}"
            class="absolute inset-0 h-full w-full object-cover"
        >
    </div>
    <div class="text-sm text-gray-500">
        <p class="font-medium text-gray-900">{{ $image->image_name }}</p>
        @if($image->image_alitext)
            <p class="mt-1">{{ $image->image_alitext }}</p>
        @endif
    </div>
</div> 