<?php

namespace App\Filament\Resources\BimbelPackages;

use App\Filament\Resources\BimbelPackages\Pages\ManageBimbelPackages;
use App\Filament\Resources\Concerns\AdminResourceAccess;
use App\Filament\Support\LandingPagePreview;
use App\Models\BimbelPackage;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class BimbelPackageResource extends Resource
{
    use AdminResourceAccess;

    protected static ?string $model = BimbelPackage::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBookOpen;

    protected static string|\UnitEnum|null $navigationGroup = 'Program';

    protected static ?string $navigationLabel = 'Bimbel';

    protected static ?string $modelLabel = 'Paket Bimbel';

    protected static ?string $pluralModelLabel = 'Paket Bimbel';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')
                ->label('Nama')
                ->required()
                ->maxLength(150)
                ->live(onBlur: true)
                ->afterStateUpdated(fn ($state, callable $set): mixed => $set('slug', Str::slug((string) $state))),
            TextInput::make('slug')
                ->required()
                ->unique(ignoreRecord: true)
                ->maxLength(150),
            Textarea::make('description')->label('Deskripsi')->rows(4),
            TextInput::make('target')->label('Target peserta')->maxLength(180),
            TextInput::make('cta_label')->label('CTA label')->maxLength(120),
            Textarea::make('cta_message')->label('Pesan WhatsApp')->rows(4),
            Toggle::make('is_active')->label('Aktif')->default(true),
            LandingPagePreview::formPreview('bimbel'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')->label('Nama')->searchable()->sortable(),
                TextColumn::make('slug')->searchable(),
                TextColumn::make('target')->label('Target')->searchable(),
                IconColumn::make('is_active')->label('Aktif')->boolean(),
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
            'index' => ManageBimbelPackages::route('/'),
        ];
    }
}
