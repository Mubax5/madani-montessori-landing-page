<?php

namespace App\Filament\Resources\GalleryItems;

use App\Filament\Resources\Concerns\AdminResourceAccess;
use App\Filament\Resources\GalleryItems\Pages\ManageGalleryItems;
use App\Filament\Support\LandingPagePreview;
use App\Models\GalleryItem;
use App\Models\MediaAsset;
use App\Support\MediaUrl;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class GalleryItemResource extends Resource
{
    use AdminResourceAccess;

    protected static ?string $model = GalleryItem::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPhoto;

    protected static string|\UnitEnum|null $navigationGroup = 'Media';

    protected static ?string $navigationLabel = 'Galeri';

    protected static ?string $modelLabel = 'Galeri';

    protected static ?string $pluralModelLabel = 'Galeri';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('media_id')
                ->label('Media')
                ->relationship('media', 'alt_text')
                ->getOptionLabelFromRecordUsing(fn (MediaAsset $record): string => LandingPagePreview::mediaOption($record))
                ->allowHtml()
                ->live()
                ->required()
                ->searchable()
                ->preload(),
            LandingPagePreview::mediaThumbnail('media_id'),
            Select::make('category')
                ->label('Kategori')
                ->options([
                    'sekolah' => 'Sekolah',
                    'bimbel' => 'Bimbel',
                    'event' => 'Event',
                ])
                ->required()
                ->default('sekolah'),
            TextInput::make('title')->label('Judul')->maxLength(180),
            Textarea::make('description')->label('Deskripsi')->rows(3),
            Toggle::make('is_featured')->label('Featured')->default(false),
            TextInput::make('sort_order')->label('Urutan')->numeric()->default(0),
            Toggle::make('is_active')->label('Aktif')->default(true),
            LandingPagePreview::formPreview('galeri'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                ImageColumn::make('image_final_url')
                    ->label('Foto')
                    ->getStateUsing(fn (GalleryItem $record): ?string => $record->image_final_url)
                    ->defaultImageUrl(MediaUrl::placeholderDataUri('Galeri'))
                    ->imageSize(52)
                    ->square(),
                TextColumn::make('title')->label('Judul')->searchable(),
                TextColumn::make('category')->label('Kategori')->badge(),
                IconColumn::make('is_featured')->label('Featured')->boolean(),
                IconColumn::make('is_active')->label('Aktif')->boolean(),
                TextColumn::make('sort_order')->label('Urutan')->sortable(),
            ])
            ->recordActions([
                LandingPagePreview::action('galeri'),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageGalleryItems::route('/'),
        ];
    }
}
