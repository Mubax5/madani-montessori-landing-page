<?php

namespace App\Filament\Resources\FeaturedPrograms;

use App\Filament\Resources\Concerns\AdminResourceAccess;
use App\Filament\Resources\FeaturedPrograms\Pages\ManageFeaturedPrograms;
use App\Filament\Support\LandingPagePreview;
use App\Models\FeaturedProgram;
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

class FeaturedProgramResource extends Resource
{
    use AdminResourceAccess;

    protected static ?string $model = FeaturedProgram::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSparkles;

    protected static string|\UnitEnum|null $navigationGroup = 'Program';

    protected static ?string $navigationLabel = 'Program Unggulan';

    protected static ?string $modelLabel = 'Program Unggulan';

    protected static ?string $pluralModelLabel = 'Program Unggulan';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('title')->label('Judul')->required()->maxLength(180),
            Textarea::make('description')->label('Deskripsi')->rows(4),
            TextInput::make('icon')->label('Ikon')->maxLength(100),
            TextInput::make('sort_order')->label('Urutan')->numeric()->default(0),
            Toggle::make('is_active')->label('Aktif')->default(true),
            LandingPagePreview::formPreview('program-unggulan'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                TextColumn::make('title')->label('Judul')->searchable()->sortable(),
                TextColumn::make('icon')->label('Ikon'),
                IconColumn::make('is_active')->label('Aktif')->boolean(),
                TextColumn::make('sort_order')->label('Urutan')->sortable(),
            ])
            ->recordActions([
                LandingPagePreview::action('program-unggulan'),
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
            'index' => ManageFeaturedPrograms::route('/'),
        ];
    }
}
