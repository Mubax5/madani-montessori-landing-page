<?php

namespace App\Filament\Resources\BimbelPackageItems;

use App\Filament\Resources\BimbelPackageItems\Pages\ManageBimbelPackageItems;
use App\Filament\Resources\Concerns\AdminResourceAccess;
use App\Filament\Support\LandingPagePreview;
use App\Models\BimbelPackageItem;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BimbelPackageItemResource extends Resource
{
    use AdminResourceAccess;

    protected static ?string $model = BimbelPackageItem::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedListBullet;

    protected static string|\UnitEnum|null $navigationGroup = 'Program';

    protected static ?string $navigationLabel = 'Item Bimbel';

    protected static ?string $modelLabel = 'Item Bimbel';

    protected static ?string $pluralModelLabel = 'Item Bimbel';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('package_id')
                ->label('Paket')
                ->relationship('package', 'name')
                ->required()
                ->searchable()
                ->preload(),
            TextInput::make('title')->label('Judul')->required()->maxLength(150),
            Textarea::make('description')->label('Deskripsi')->rows(3),
            TextInput::make('sort_order')->label('Urutan')->numeric()->default(0),
            LandingPagePreview::formPreview('bimbel'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                TextColumn::make('package.name')->label('Paket')->searchable()->sortable(),
                TextColumn::make('title')->label('Judul')->searchable(),
                TextColumn::make('sort_order')->label('Urutan')->sortable(),
            ])
            ->recordActions([
                LandingPagePreview::action('bimbel'),
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
            'index' => ManageBimbelPackageItems::route('/'),
        ];
    }
}
