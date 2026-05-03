<?php

namespace App\Filament\Resources\AgendaRegistrations;

use App\Filament\Resources\AgendaRegistrations\Pages\ManageAgendaRegistrations;
use App\Filament\Resources\Concerns\AdminResourceAccess;
use App\Models\AgendaRegistration;
use BackedEnum;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class AgendaRegistrationResource extends Resource
{
    use AdminResourceAccess;

    protected static ?string $model = AgendaRegistration::class;

    protected static string $permissionScope = 'leads';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTicket;

    protected static string|\UnitEnum|null $navigationGroup = 'Pendaftaran';

    protected static ?string $navigationLabel = 'Pendaftaran Agenda';

    protected static ?string $modelLabel = 'Pendaftaran Agenda';

    protected static ?string $pluralModelLabel = 'Pendaftaran Agenda';

    protected static ?string $recordTitleAttribute = 'parent_name';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function canDeleteAny(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('agenda_id')
                ->label('Agenda')
                ->relationship('agenda', 'title')
                ->disabled()
                ->dehydrated(false),
            TextInput::make('parent_name')->label('Nama orang tua')->disabled(),
            TextInput::make('child_name')->label('Nama anak')->disabled(),
            TextInput::make('child_age')->label('Usia anak')->disabled(),
            TextInput::make('whatsapp_number')->label('WhatsApp')->disabled(),
            TextInput::make('email')->label('Email')->disabled(),
            TextInput::make('participant_count')->label('Jumlah peserta')->disabled(),
            Textarea::make('note')->label('Catatan')->rows(4)->disabled(),
            Select::make('status')
                ->options([
                    'new' => 'New',
                    'contacted' => 'Contacted',
                    'confirmed' => 'Confirmed',
                    'cancelled' => 'Cancelled',
                ])
                ->required(),
            TextInput::make('source')->label('Source')->disabled(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('parent_name')
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('created_at')->label('Masuk')->dateTime('d M Y H:i')->sortable(),
                TextColumn::make('agenda.title')->label('Agenda')->searchable()->sortable(),
                TextColumn::make('parent_name')->label('Orang tua')->searchable(),
                TextColumn::make('child_name')->label('Anak')->searchable(),
                TextColumn::make('whatsapp_number')->label('WhatsApp')->searchable(),
                TextColumn::make('participant_count')->label('Peserta')->sortable(),
                TextColumn::make('status')->badge()->sortable(),
            ])
            ->filters([
                SelectFilter::make('agenda_id')
                    ->label('Agenda')
                    ->relationship('agenda', 'title'),
                SelectFilter::make('status')
                    ->options([
                        'new' => 'New',
                        'contacted' => 'Contacted',
                        'confirmed' => 'Confirmed',
                        'cancelled' => 'Cancelled',
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageAgendaRegistrations::route('/'),
        ];
    }
}
