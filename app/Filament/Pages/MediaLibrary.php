<?php

namespace App\Filament\Pages;

use App\Models\Image;
use App\Models\ImageCategory;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;

class MediaLibrary extends Page implements HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-photo';
    
    protected static ?string $navigationGroup = 'Media Library';
    
    protected static ?int $navigationSort = 3;
    
    protected static ?string $title = 'Media Library';
    
    protected static ?string $slug = 'media-library';
    
    protected static string $view = 'filament.pages.media-library';
    
    public ?array $data = [];
    
    public function mount(): void
    {
        $this->form->fill();
    }
    
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('image_category_id')
                    ->label('Category')
                    ->options(ImageCategory::pluck('title', 'id'))
                    ->searchable()
                    ->preload(),
                FileUpload::make('image_path')
                    ->label('Upload Images')
                    ->image()
                    ->multiple()
                    ->reorderable()
                    ->directory('images')
                    ->visibility('public')
                    ->imageResizeMode('cover')
                    ->imageCropAspectRatio('16:9')
                    ->imageResizeTargetWidth('1920')
                    ->imageResizeTargetHeight('1080')
                    ->columnSpanFull(),
                TextInput::make('image_name')
                    ->label('Image Name')
                    ->required(),
                TextInput::make('image_alitext')
                    ->label('Alt Text'),
            ])
            ->statePath('data');
    }
    
    public function table(Table $table): Table
    {
        return $table
            ->query(Image::query())
            ->columns([
                ImageColumn::make('image_path')
                    ->square()
                    ->size(100),
                TextColumn::make('image_name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('category.title')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('category')
                    ->relationship('category', 'title')
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    \Filament\Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
    
    public function create(): void
    {
        $data = $this->form->getState();
        
        $categoryId = $data['image_category_id'] ?? null;
        $imagePaths = $data['image_path'] ?? [];
        $imageName = $data['image_name'] ?? 'Untitled';
        $imageAltText = $data['image_alitext'] ?? '';
        
        if (empty($imagePaths)) {
            return;
        }
        
        foreach ($imagePaths as $imagePath) {
            Image::create([
                'image_category_id' => $categoryId,
                'image_path' => $imagePath,
                'image_name' => $imageName,
                'image_alitext' => $imageAltText,
            ]);
        }
        
        $this->form->fill();
        
        // $this->notify('success', 'Images uploaded successfully');

        Notification::make()
            ->title('Images uploaded successfully')
            ->success()
            ->send();


    }
} 