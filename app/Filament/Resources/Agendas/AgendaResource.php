<?php

namespace App\Filament\Resources\Agendas;

use App\Filament\Resources\Agendas\Pages\ManageAgendas;
use App\Filament\Resources\Concerns\AdminResourceAccess;
use App\Filament\Support\ImageUpload;
use App\Filament\Support\LandingPagePreview;
use App\Models\Agenda;
use BackedEnum;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ReplicateAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AgendaResource extends Resource
{
    use AdminResourceAccess;

    protected static ?string $model = Agenda::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;

    protected static string|\UnitEnum|null $navigationGroup = 'Agenda';

    protected static ?string $navigationLabel = 'Semua Agenda';

    protected static ?string $modelLabel = 'Agenda';

    protected static ?string $pluralModelLabel = 'Agenda';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('title')
                ->label('Judul')
                ->required()
                ->maxLength(180)
                ->live(onBlur: true)
                ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug((string) $state))),
            TextInput::make('slug')
                ->required()
                ->unique(ignoreRecord: true)
                ->maxLength(200),
            Select::make('agenda_category_id')
                ->label('Kategori')
                ->relationship('category', 'name')
                ->searchable()
                ->preload(),
            Textarea::make('excerpt')
                ->label('Ringkasan')
                ->rows(3)
                ->maxLength(500),
            RichEditor::make('description')
                ->label('Deskripsi lengkap')
                ->columnSpanFull(),
            ImageUpload::make('cover_image_path', 'agendas', 'Cover Image')
                ->helperText('JPG, PNG, atau WEBP maksimal 2MB. File disimpan dengan nama acak.'),
            TextInput::make('location_name')->label('Nama lokasi')->maxLength(180),
            Textarea::make('location_address')->label('Alamat lokasi')->rows(3),
            TextInput::make('maps_url')->label('Maps URL')->url()->maxLength(2048),
            DateTimePicker::make('start_at')->label('Mulai'),
            DateTimePicker::make('end_at')->label('Selesai'),
            DateTimePicker::make('registration_start_at')->label('Pendaftaran mulai'),
            DateTimePicker::make('registration_end_at')->label('Pendaftaran selesai'),
            TextInput::make('target_audience')->label('Target peserta')->maxLength(180),
            TextInput::make('quota')->label('Kuota')->numeric()->minValue(1),
            Toggle::make('is_free')->label('Gratis')->default(true),
            TextInput::make('price')->label('Biaya')->numeric()->prefix('Rp')->minValue(0),
            Select::make('registration_type')
                ->label('Tipe pendaftaran')
                ->options([
                    'whatsapp' => 'WhatsApp',
                    'form' => 'Form internal',
                    'external_url' => 'External URL',
                ])
                ->required()
                ->default('whatsapp'),
            TextInput::make('registration_url')->label('URL pendaftaran')->url()->maxLength(2048),
            Textarea::make('whatsapp_template')->label('Template WhatsApp')->rows(3),
            Select::make('status')
                ->options([
                    'draft' => 'Draft',
                    'published' => 'Published',
                    'closed' => 'Closed',
                    'cancelled' => 'Cancelled',
                    'archived' => 'Archived',
                ])
                ->required()
                ->default('draft'),
            Toggle::make('is_featured')->label('Featured')->default(false),
            TextInput::make('sort_order')->label('Urutan')->numeric()->default(0),
            TextInput::make('meta_title')->label('SEO title')->maxLength(180),
            Textarea::make('meta_description')->label('SEO description')->rows(3),
            DateTimePicker::make('published_at')->label('Published at'),
            LandingPagePreview::formPreview('agenda'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->defaultSort('start_at')
            ->columns([
                ImageColumn::make('cover_image_url')
                    ->label('Cover')
                    ->getStateUsing(fn (Agenda $record): ?string => $record->cover_image_url)
                    ->imageSize(52)
                    ->square(),
                TextColumn::make('title')->label('Judul')->searchable()->sortable(),
                TextColumn::make('category.name')->label('Kategori')->badge()->sortable(),
                TextColumn::make('start_at')->label('Mulai')->dateTime('d M Y H:i')->sortable(),
                TextColumn::make('location_name')->label('Lokasi')->searchable(),
                TextColumn::make('status')->badge()->sortable(),
                IconColumn::make('is_featured')->label('Featured')->boolean(),
                TextColumn::make('registration_type')->label('Pendaftaran')->badge(),
                TextColumn::make('updated_at')->label('Diubah')->dateTime('d M Y H:i')->sortable(),
            ])
            ->filters([
                SelectFilter::make('agenda_category_id')
                    ->label('Kategori')
                    ->relationship('category', 'name'),
                SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'published' => 'Published',
                        'closed' => 'Closed',
                        'cancelled' => 'Cancelled',
                        'archived' => 'Archived',
                    ]),
                TernaryFilter::make('is_featured')->label('Featured'),
                Filter::make('upcoming')
                    ->label('Upcoming')
                    ->query(fn (Builder $query): Builder => $query->whereNotNull('start_at')->where('start_at', '>=', now()->startOfDay())),
                Filter::make('past')
                    ->label('Past')
                    ->query(fn (Builder $query): Builder => $query->whereNotNull('start_at')->where('start_at', '<', now()->startOfDay())),
            ])
            ->recordActions([
                LandingPagePreview::action('agenda'),
                ViewAction::make(),
                EditAction::make()
                    ->mutateDataUsing(fn (array $data): array => static::stampUpdatedBy($data)),
                ReplicateAction::make()
                    ->label('Duplicate')
                    ->excludeAttributes(['slug', 'created_by', 'updated_by', 'created_at', 'updated_at'])
                    ->beforeReplicaSaved(fn (Agenda $replica): Agenda => $replica->forceFill([
                        'title' => $replica->title . ' (Copy)',
                        'slug' => Str::slug($replica->title . ' copy ' . Str::ulid()),
                        'status' => 'draft',
                        'is_featured' => false,
                        'published_at' => null,
                        'created_by' => Auth::guard('admin')->id(),
                        'updated_by' => Auth::guard('admin')->id(),
                    ])),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('publish')
                        ->label('Publish')
                        ->icon(Heroicon::OutlinedCheckCircle)
                        ->action(fn (Collection $records): mixed => $records->each->update(['status' => 'published', 'published_at' => now()])),
                    BulkAction::make('archive')
                        ->label('Archive')
                        ->icon(Heroicon::OutlinedArchiveBox)
                        ->action(fn (Collection $records): mixed => $records->each->update(['status' => 'archived'])),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageAgendas::route('/'),
        ];
    }

    public static function stampCreatedBy(array $data): array
    {
        $data['created_by'] = Auth::guard('admin')->id();
        $data['updated_by'] = Auth::guard('admin')->id();

        return $data;
    }

    public static function stampUpdatedBy(array $data): array
    {
        $data['updated_by'] = Auth::guard('admin')->id();

        return $data;
    }
}
