<?php

namespace App\Filament\Resources\ImageCategoryResource\Pages;

use App\Filament\Resources\ImageCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditImageCategory extends EditRecord
{
    protected static string $resource = ImageCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
