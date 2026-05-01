<?php

namespace App\Filament\Resources\Faqs;

use App\Filament\Resources\Concerns\AdminResourceAccess;
use App\Filament\Resources\Faqs\Pages\ManageFaqs;
use App\Filament\Support\LandingPagePreview;
use App\Models\Faq;
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

class FaqResource extends Resource
{
    use AdminResourceAccess;

    protected static ?string $model = Faq::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedQuestionMarkCircle;

    protected static string|\UnitEnum|null $navigationGroup = 'Komunikasi';

    protected static ?string $navigationLabel = 'FAQ';

    protected static ?string $modelLabel = 'FAQ';

    protected static ?string $pluralModelLabel = 'FAQ';

    protected static ?string $recordTitleAttribute = 'question';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('page_scope')
                ->label('Halaman')
                ->options([
                    'home' => 'Beranda',
                    'tentang' => 'Tentang',
                    'program-sekolah' => 'Program Sekolah',
                    'program-unggulan' => 'Program Unggulan',
                    'bimbel' => 'Bimbel',
                    'training-parenting' => 'Training & Parenting',
                    'galeri' => 'Galeri',
                    'kontak' => 'Kontak',
                ])
                ->required(),
            TextInput::make('question')->label('Pertanyaan')->required()->maxLength(255),
            Textarea::make('answer')->label('Jawaban')->required()->rows(4),
            TextInput::make('sort_order')->label('Urutan')->numeric()->default(0),
            Toggle::make('is_active')->label('Aktif')->default(true),
            LandingPagePreview::formPreview('home'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('question')
            ->columns([
                TextColumn::make('page_scope')->label('Halaman')->badge()->sortable(),
                TextColumn::make('question')->label('Pertanyaan')->searchable(),
                IconColumn::make('is_active')->label('Aktif')->boolean(),
                TextColumn::make('sort_order')->label('Urutan')->sortable(),
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
            'index' => ManageFaqs::route('/'),
        ];
    }
}
