<?php

namespace App\Filament\Forms\Components;

use App\Models\Image;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\Select;
use Filament\Support\RawJs;
use Illuminate\Support\Facades\Storage;

class MediaPicker extends Field
{
    protected string $view = 'filament.forms.components.media-picker';
    
    protected bool $isMultiple = false;
    
    protected bool $isSearchable = false;
    
    protected bool $isPreloaded = false;
    
    protected ?int $categoryId = null;
    
    protected bool $shouldShowPreview = true;
    
    public function multiple(bool $isMultiple = true): static
    {
        $this->isMultiple = $isMultiple;
        
        return $this;
    }
    
    public function searchable(bool $isSearchable = true): static
    {
        $this->isSearchable = $isSearchable;
        
        return $this;
    }
    
    public function preload(bool $isPreloaded = true): static
    {
        $this->isPreloaded = $isPreloaded;
        
        return $this;
    }
    
    public function category(?int $categoryId): static
    {
        $this->categoryId = $categoryId;
        
        return $this;
    }
    
    public function showPreview(bool $shouldShowPreview = true): static
    {
        $this->shouldShowPreview = $shouldShowPreview;
        
        return $this;
    }
    
    public function isMultiple(): bool
    {
        return $this->isMultiple;
    }
    
    public function isSearchable(): bool
    {
        return $this->isSearchable;
    }
    
    public function isPreloaded(): bool
    {
        return $this->isPreloaded;
    }
    
    public function getCategoryId(): ?int
    {
        return $this->categoryId;
    }
    
    public function shouldShowPreview(): bool
    {
        return $this->shouldShowPreview;
    }
    
    public function getImages(): array
    {
        $query = Image::query();
        
        if ($this->categoryId) {
            $query->where('image_category_id', $this->categoryId);
        }
        
        return $query->get()
            ->map(fn (Image $image) => [
                'id' => $image->id,
                'name' => $image->image_name,
                'url' => Storage::url($image->image_path),
                'alt' => $image->image_alitext,
            ])
            ->toArray();
    }
    
    public function getSelectedImage(): ?array
    {
        if (!$this->getState()) {
            return null;
        }

        $imageId = $this->isMultiple() ? $this->getState()[0] ?? null : $this->getState();
        
        if (!$imageId) {
            return null;
        }

        $image = Image::find($imageId);
        
        if (!$image) {
            return null;
        }

        return [
            'id' => $image->id,
            'name' => $image->image_name,
            'url' => Storage::url($image->image_path),
            'alt' => $image->image_alitext,
        ];
    }
} 