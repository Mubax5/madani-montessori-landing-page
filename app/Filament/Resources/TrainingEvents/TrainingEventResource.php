<?php

namespace App\Filament\Resources\TrainingEvents;

use App\Filament\Resources\Concerns\AdminResourceAccess;
use App\Filament\Resources\TrainingEvents\Pages\ManageTrainingEvents;
use App\Filament\Support\LandingPagePreview;
use App\Models\TrainingEvent;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TrainingEventResource extends Resource
{
    use AdminResourceAccess;

    protected static ?string $model = TrainingEvent::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;

    protected static string|\UnitEnum|null $navigationGroup = 'Training';

    protected static ?string $navigationLabel = 'Jadwal Training';

    protected static ?string $modelLabel = 'Jadwal Training';

    protected static ?string $pluralModelLabel = 'Jadwal Training';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('title')->label('Judul')->required()->maxLength(180),
            TextInput::make('topic')->label('Topik')->maxLength(180),
            Select::make('target_audience')
                ->label('Target')
                ->options([
                    'guru' => 'Guru',
                    'orang_tua' => 'Orang tua',
                    'guru_dan_orang_tua' => 'Guru dan orang tua',
                ])
                ->required()
                ->default('guru_dan_orang_tua'),
            DatePicker::make('event_date')->label('Tanggal'),
            TextInput::make('event_time')->label('Jam')->maxLength(80),
            Textarea::make('description')->label('Deskripsi')->rows(4),
            Select::make('status')
                ->options([
                    'draft' => 'Draft',
                    'published' => 'Published',
                    'closed' => 'Closed',
                ])
                ->required()
                ->default('draft'),
            TextInput::make('sort_order')->label('Urutan')->numeric()->default(0),
            LandingPagePreview::formPreview('training-parenting'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                TextColumn::make('title')->label('Judul')->searchable()->sortable(),
                TextColumn::make('topic')->label('Topik')->searchable(),
                TextColumn::make('event_date')->label('Tanggal')->date('d M Y')->sortable(),
                TextColumn::make('status')->badge(),
                TextColumn::make('sort_order')->label('Urutan')->sortable(),
            ])
            ->recordActions([
                LandingPagePreview::action('training-parenting'),
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
            'index' => ManageTrainingEvents::route('/'),
        ];
    }
}
