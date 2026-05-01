<?php

namespace App\Filament\Resources\SiteSettings;

use App\Filament\Resources\Concerns\AdminResourceAccess;
use App\Filament\Resources\SiteSettings\Pages\ManageSiteSettings;
use App\Filament\Support\LandingPagePreview;
use App\Models\SiteSetting;
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

class SiteSettingResource extends Resource
{
    use AdminResourceAccess;

    protected static ?string $model = SiteSetting::class;

    protected static string $permissionScope = 'settings';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static string|\UnitEnum|null $navigationGroup = 'Pengaturan';

    protected static ?string $navigationLabel = 'Website';

    protected static ?string $modelLabel = 'Setting';

    protected static ?string $pluralModelLabel = 'Pengaturan Website';

    protected static ?string $recordTitleAttribute = 'setting_key';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('setting_key')->label('Key')->required()->unique(ignoreRecord: true)->maxLength(120),
            Select::make('setting_type')
                ->label('Tipe')
                ->options([
                    'text' => 'Text',
                    'textarea' => 'Textarea',
                    'image' => 'Image',
                    'url' => 'URL',
                    'json' => 'JSON',
                    'color' => 'Color',
                ])
                ->required()
                ->default('text'),
            Textarea::make('setting_value')->label('Value')->rows(5),
            LandingPagePreview::formPreview('home'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('setting_key')
            ->columns([
                TextColumn::make('setting_key')->label('Key')->searchable()->sortable(),
                TextColumn::make('setting_type')->label('Tipe')->badge(),
                TextColumn::make('setting_value')->label('Value')->limit(60)->searchable(),
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
            'index' => ManageSiteSettings::route('/'),
        ];
    }
}
