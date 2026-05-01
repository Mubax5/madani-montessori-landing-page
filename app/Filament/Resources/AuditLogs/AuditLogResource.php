<?php

namespace App\Filament\Resources\AuditLogs;

use App\Filament\Resources\AuditLogs\Pages\ManageAuditLogs;
use App\Filament\Resources\Concerns\AdminResourceAccess;
use App\Models\AuditLog;
use BackedEnum;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class AuditLogResource extends Resource
{
    use AdminResourceAccess;

    protected static ?string $model = AuditLog::class;

    protected static string $permissionScope = 'audit';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static string|\UnitEnum|null $navigationGroup = 'Aktivitas';

    protected static ?string $navigationLabel = 'Audit Log';

    protected static ?string $modelLabel = 'Audit Log';

    protected static ?string $pluralModelLabel = 'Audit Log';

    protected static ?string $recordTitleAttribute = 'module';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(Model $record): bool
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
            TextInput::make('adminUser.name')->label('Admin')->disabled(),
            TextInput::make('action')->label('Aksi')->disabled(),
            TextInput::make('module')->label('Modul')->disabled(),
            TextInput::make('record_id')->label('Record ID')->disabled(),
            Textarea::make('old_data')->label('Data lama')->disabled()->formatStateUsing(fn ($state): ?string => blank($state) ? null : json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)),
            Textarea::make('new_data')->label('Data baru')->disabled()->formatStateUsing(fn ($state): ?string => blank($state) ? null : json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)),
            TextInput::make('ip_address')->label('IP')->disabled(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('module')
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('created_at')->label('Waktu')->dateTime('d M Y H:i:s')->sortable(),
                TextColumn::make('adminUser.name')->label('Admin')->searchable(),
                TextColumn::make('action')->label('Aksi')->badge(),
                TextColumn::make('module')->label('Modul')->searchable(),
                TextColumn::make('record_id')->label('Record'),
                TextColumn::make('ip_address')->label('IP'),
            ])
            ->recordActions([
                ViewAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageAuditLogs::route('/'),
        ];
    }
}
