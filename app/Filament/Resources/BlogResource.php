<?php

namespace App\Filament\Resources;

use App\Models\Blog;
use Filament\Resources\Resource;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteBulkAction;
use App\Filament\Resources\BlogResource\Pages;

class BlogResource extends Resource
{
    protected static ?string $model = Blog::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')->required(),
                TextInput::make('slug')
                    ->required()
                    ->unique(ignoreRecord: true),
                FileUpload::make('image')
                    ->image()
                    ->directory('blogs') // ✅ tells Filament where to store and look for files
                    ->disk('public')     // ✅ uses the public disk
                    ->visibility('public')
                    ->imagePreviewHeight('auto'), // ✅ Prevent Livewire syncing until submit
                Textarea::make('excerpt')->required()->rows(3),
                RichEditor::make('content')->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image_url') // 👈 use the accessor
                    ->width(80)
                    ->height(80)
                    ->extraImgAttributes(['loading' => 'lazy']),
                TextColumn::make('title')->sortable()->searchable(),
                TextColumn::make('excerpt')->limit(50),
                TextColumn::make('created_at')->date(),
            ])
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListBlogs::route('/'),
            'create' => Pages\CreateBlog::route('/create'),
            'edit'   => Pages\EditBlog::route('/{record}/edit'),
        ];
    }
}
