<?php

namespace App\Filament\Resources\PageSections;

use App\Filament\Resources\Concerns\AdminResourceAccess;
use App\Filament\Resources\PageSections\Pages\ManagePageSections;
use App\Filament\Support\LandingPagePreview;
use App\Models\MediaAsset;
use App\Models\PageSection;
use App\Rules\InternalOrAllowedExternalUrl;
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

class PageSectionResource extends Resource
{
    use AdminResourceAccess;

    protected static ?string $model = PageSection::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSquares2x2;

    protected static string|\UnitEnum|null $navigationGroup = 'Konten Website';

    protected static ?string $navigationLabel = 'Section';

    protected static ?string $modelLabel = 'Section';

    protected static ?string $pluralModelLabel = 'Section Halaman';

    protected static ?string $recordTitleAttribute = 'section_name';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('page_id')
                ->label('Halaman')
                ->relationship('page', 'title')
                ->required()
                ->searchable()
                ->preload(),
            Select::make('image_id')
                ->label('Gambar')
                ->relationship('image', 'alt_text')
                ->getOptionLabelFromRecordUsing(fn (MediaAsset $record): string => LandingPagePreview::mediaOption($record))
                ->allowHtml()
                ->live()
                ->searchable()
                ->preload(),
            LandingPagePreview::mediaThumbnail('image_id'),
            TextInput::make('section_key')
                ->label('Key')
                ->required()
                ->maxLength(120),
            TextInput::make('section_name')
                ->label('Nama section')
                ->required()
                ->maxLength(180),
            TextInput::make('heading')
                ->label('Heading')
                ->maxLength(255),
            Textarea::make('subheading')
                ->label('Subheading')
                ->rows(3),
            Textarea::make('body')
                ->label('Body HTML')
                ->rows(5),
            Textarea::make('payload')
                ->label('Payload JSON')
                ->rows(8)
                ->rules(['nullable', 'json'])
                ->formatStateUsing(fn ($state): ?string => blank($state) ? null : json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))
                ->dehydrateStateUsing(fn (?string $state): ?array => filled($state) ? json_decode($state, true) : null),
            TextInput::make('cta_label')
                ->label('CTA label')
                ->maxLength(120),
            TextInput::make('cta_url')
                ->label('CTA URL')
                ->rules(['nullable', new InternalOrAllowedExternalUrl])
                ->maxLength(255),
            TextInput::make('sort_order')
                ->label('Urutan')
                ->numeric()
                ->default(0),
            Toggle::make('is_active')
                ->label('Aktif')
                ->default(true),
            LandingPagePreview::formPreview(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('section_name')
            ->columns([
                ImageColumn::make('image_final_url')
                    ->label('Gambar')
                    ->getStateUsing(fn (PageSection $record): ?string => $record->image_final_url)
                    ->defaultImageUrl(MediaUrl::placeholderDataUri('Section'))
                    ->imageSize(48)
                    ->square(),
                TextColumn::make('page.title')->label('Halaman')->sortable()->searchable(),
                TextColumn::make('section_name')->label('Section')->searchable(),
                TextColumn::make('section_key')->label('Key')->searchable(),
                IconColumn::make('is_active')->label('Aktif')->boolean(),
                TextColumn::make('sort_order')->label('Urutan')->sortable(),
            ])
            ->recordActions([
                LandingPagePreview::action(),
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
            'index' => ManagePageSections::route('/'),
        ];
    }
}
