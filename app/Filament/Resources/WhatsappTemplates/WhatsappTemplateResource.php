<?php

namespace App\Filament\Resources\WhatsappTemplates;

use App\Filament\Resources\Concerns\AdminResourceAccess;
use App\Filament\Resources\WhatsappTemplates\Pages\ManageWhatsappTemplates;
use App\Filament\Support\LandingPagePreview;
use App\Models\WhatsappTemplate;
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

class WhatsappTemplateResource extends Resource
{
    use AdminResourceAccess;

    protected static ?string $model = WhatsappTemplate::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleLeftRight;

    protected static string|\UnitEnum|null $navigationGroup = 'Komunikasi';

    protected static ?string $navigationLabel = 'WhatsApp Template';

    protected static ?string $modelLabel = 'WhatsApp Template';

    protected static ?string $pluralModelLabel = 'WhatsApp Template';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')->label('Nama')->required()->maxLength(150),
            TextInput::make('template_key')->label('Key')->required()->unique(ignoreRecord: true)->maxLength(100),
            Textarea::make('message')->label('Pesan')->required()->rows(6),
            Toggle::make('is_active')->label('Aktif')->default(true),
            LandingPagePreview::formPreview('kontak'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')->label('Nama')->searchable()->sortable(),
                TextColumn::make('template_key')->label('Key')->searchable(),
                IconColumn::make('is_active')->label('Aktif')->boolean(),
                TextColumn::make('updated_at')->label('Diubah')->dateTime('d M Y H:i')->sortable(),
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
            'index' => ManageWhatsappTemplates::route('/'),
        ];
    }
}
