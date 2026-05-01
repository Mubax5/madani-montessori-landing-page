<?php

namespace App\Filament\Resources\Leads;

use App\Filament\Resources\Concerns\AdminResourceAccess;
use App\Filament\Resources\Leads\Pages\ManageLeads;
use App\Filament\Support\LandingPagePreview;
use App\Models\Lead;
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

class LeadResource extends Resource
{
    use AdminResourceAccess;

    protected static ?string $model = Lead::class;

    protected static string $permissionScope = 'leads';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedInboxStack;

    protected static string|\UnitEnum|null $navigationGroup = 'Pendaftaran';

    protected static ?string $navigationLabel = 'Leads';

    protected static ?string $modelLabel = 'Lead';

    protected static ?string $pluralModelLabel = 'Leads Pendaftaran';

    protected static ?string $recordTitleAttribute = 'parent_name';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('parent_name')->label('Nama orang tua')->required()->maxLength(150),
            TextInput::make('child_name')->label('Nama anak')->required()->maxLength(150),
            TextInput::make('child_age')->label('Usia anak')->numeric(),
            Select::make('selected_program')
                ->label('Program')
                ->options([
                    'KB' => 'KB',
                    'TK A' => 'TK A',
                    'TK B' => 'TK B',
                    'TK C' => 'TK C',
                    'Bimbel' => 'Bimbel',
                    'Training & Parenting' => 'Training & Parenting',
                ])
                ->required(),
            TextInput::make('whatsapp_number')->label('Nomor WhatsApp')->required()->maxLength(30),
            Textarea::make('note')->label('Catatan')->rows(4),
            TextInput::make('source_page')->label('Sumber')->maxLength(100),
            Select::make('status')
                ->options([
                    'baru' => 'Baru',
                    'dihubungi' => 'Dihubungi',
                    'follow_up' => 'Follow up',
                    'terdaftar' => 'Terdaftar',
                    'batal' => 'Batal',
                ])
                ->required()
                ->default('baru'),
            LandingPagePreview::formPreview('kontak'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('parent_name')
            ->columns([
                TextColumn::make('parent_name')->label('Orang tua')->searchable()->sortable(),
                TextColumn::make('child_name')->label('Anak')->searchable(),
                TextColumn::make('selected_program')->label('Program')->badge(),
                TextColumn::make('whatsapp_number')->label('WhatsApp')->searchable(),
                TextColumn::make('status')->badge()->sortable(),
                TextColumn::make('created_at')->label('Masuk')->dateTime('d M Y H:i')->sortable(),
            ])
            ->recordActions([
                LandingPagePreview::action('kontak'),
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
            'index' => ManageLeads::route('/'),
        ];
    }
}
