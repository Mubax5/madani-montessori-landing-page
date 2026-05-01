<?php

namespace App\Filament\Resources\Programs;

use App\Filament\Resources\Concerns\AdminResourceAccess;
use App\Filament\Resources\Programs\Pages\ManagePrograms;
use App\Filament\Support\LandingPagePreview;
use App\Models\Program;
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
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProgramResource extends Resource
{
    use AdminResourceAccess;

    protected static ?string $model = Program::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAcademicCap;

    protected static string|\UnitEnum|null $navigationGroup = 'Program';

    protected static ?string $navigationLabel = 'Program Sekolah';

    protected static ?string $modelLabel = 'Program Sekolah';

    protected static ?string $pluralModelLabel = 'Program Sekolah';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('program_type')
                    ->label('Tipe program')
                    ->options([
                        'kb' => 'KB',
                        'tk_a' => 'TK A',
                        'tk_b' => 'TK B',
                        'tk_c' => 'TK C',
                    ])
                    ->required(),
                Select::make('category')
                    ->label('Kategori')
                    ->options([
                        'reguler' => 'Reguler',
                        'half_day' => 'Half-day',
                        'full_day' => 'Full-day',
                    ])
                    ->required()
                    ->default('reguler'),
                TextInput::make('name')
                    ->label('Nama')
                    ->required()
                    ->maxLength(150),
                Textarea::make('description')
                    ->label('Deskripsi')
                    ->rows(4),
                TextInput::make('age_range')
                    ->label('Rentang usia')
                    ->maxLength(100),
                TextInput::make('duration')
                    ->label('Durasi')
                    ->maxLength(100),
                TextInput::make('sort_order')
                    ->label('Urutan')
                    ->numeric()
                    ->default(0),
                Toggle::make('is_active')
                    ->label('Aktif')
                    ->default(true),
                LandingPagePreview::formPreview('program-sekolah'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('program_type')
                    ->label('Tipe')
                    ->badge(),
                TextColumn::make('category')
                    ->label('Kategori')
                    ->badge(),
                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
                TextColumn::make('sort_order')
                    ->label('Urutan')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                LandingPagePreview::action('program-sekolah'),
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
            'index' => ManagePrograms::route('/'),
        ];
    }
}
