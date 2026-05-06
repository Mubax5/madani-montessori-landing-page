<?php

namespace App\Filament\Resources\NavigationItems;

use App\Filament\Resources\Concerns\AdminResourceAccess;
use App\Filament\Resources\NavigationItems\Pages\ManageNavigationItems;
use App\Filament\Support\LandingPagePreview;
use App\Models\NavigationItem;
use App\Rules\InternalOrAllowedExternalUrl;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class NavigationItemResource extends Resource
{
    use AdminResourceAccess;

    protected static ?string $model = NavigationItem::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBars3;

    protected static string|\UnitEnum|null $navigationGroup = 'Konten Website';

    protected static ?string $navigationLabel = 'Navigation';

    protected static ?string $modelLabel = 'Navigation';

    protected static ?string $pluralModelLabel = 'Navigation';

    protected static ?string $recordTitleAttribute = 'label';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('parent_id')
                ->label('Parent')
                ->relationship('parent', 'label')
                ->searchable()
                ->preload(),
            TextInput::make('label')->label('Label')->required()->maxLength(120),
            TextInput::make('url')->label('URL')->required()->rules(['required', new InternalOrAllowedExternalUrl])->maxLength(255),
            Select::make('location')
                ->label('Lokasi')
                ->options([
                    'header' => 'Header',
                    'footer' => 'Footer',
                ])
                ->required()
                ->default('header'),
            TextInput::make('sort_order')->label('Urutan')->numeric()->default(0),
            Toggle::make('is_active')->label('Aktif')->default(true),
            LandingPagePreview::formPreview('home'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('label')
            ->columns([
                TextColumn::make('label')->label('Label')->searchable()->sortable(),
                TextColumn::make('url')->label('URL')->searchable(),
                TextColumn::make('location')->label('Lokasi')->badge(),
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
            'index' => ManageNavigationItems::route('/'),
        ];
    }
}
