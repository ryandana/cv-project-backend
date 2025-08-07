<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BlogResource\Pages;
use App\Models\Blog;
use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Resource;

class BlogResource extends Resource
{
    protected static ?string $model = Blog::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('slug')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),

                Forms\Components\Textarea::make('excerpt')
                    ->required(),

                Forms\Components\RichEditor::make('content')
                    ->required(),

                Forms\Components\FileUpload::make('image')   // ← use 'image'
                    ->disk('public')
                    ->directory('blog-images')
                    ->visibility('public')
                    ->image()
                    ->preserveFilenames(),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image_url')
                    ->label('Image')
                    ->rounded(),

                Tables\Columns\TextColumn::make('title')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
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
