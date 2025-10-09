<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CertificationResource\Pages;
use App\Models\Certification;
use Filament\Actions;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Fieldset;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Support\Enums\FontWeight;
use BackedEnum;
use UnitEnum;
use Illuminate\Support\HtmlString;
use Filament\Schemas\Components\Utilities\Get;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Closure;

class CertificationResource extends Resource
{
    protected static ?string $model = Certification::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-check-badge';

    protected static UnitEnum|string|null $navigationGroup = 'Portfolio';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\Hidden::make('user_id')
                    ->default(auth()->id())
                    ->required(),

                Fieldset::make('Certification Details')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Name')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('e.g., AWS Certified Solutions Architect – Associate'),

                        Forms\Components\Select::make('organization_id')
                            ->label('Issuing Organization')
                            ->relationship('organization', 'name')
                            ->searchable()
                            ->preload()
                            ->native(false)
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')
                                    ->label('Name')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('website')
                                    ->label('Website URL')
                                    ->url()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('sort_order')
                                    ->numeric()
                                    ->default(0)
                                    ->label('Sort order'),
                            ])
                            ->helperText('Examples: LinkedIn, Udemy, Microsoft, AWS, NVIDIA, etc.'),
                    ])
                    ->columns(2),

                Fieldset::make('Validity')
                    ->schema([
                        Forms\Components\DatePicker::make('issue_date')
                            ->label('Issue date')
                            ->native(false)
                            ->displayFormat('M d, Y')
                            ->maxDate(now()),

                        Forms\Components\DatePicker::make('expiration_date')
                            ->label('Expiration date')
                            ->native(false)
                            ->displayFormat('M d, Y')
                            ->helperText('Leave empty if this certification does not expire.'),
                    ])
                    ->columns(2),

                Fieldset::make('Credentials')
                    ->schema([
                        Forms\Components\TextInput::make('credential_id')
                            ->label('Credential ID')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('credential_url')
                            ->label('Credential URL')
                            ->url()
                            ->maxLength(2048),
                    ])
                    ->columns(2),

                Fieldset::make('Skills')
                    ->schema([
                        Forms\Components\Select::make('skills')
                            ->label('Skills')
                            ->relationship('skills', 'name')
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->native(false)
                            ->helperText('Search and select skills. Type a new name to create it quickly.')
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')
                                    ->label('Name')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('category')
                                    ->label('Category')
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('sort_order')
                                    ->numeric()
                                    ->default(0)
                                    ->label('Sort order'),
                            ])
                            ->columnSpanFull(),
                    ]),

                Fieldset::make('Media')
                    ->schema([
                        Forms\Components\FileUpload::make('media')
                            ->label('Media')
                            ->multiple()
                            ->acceptedFileTypes(['image/*', 'application/pdf'])
                            ->disk('r2')
                            ->directory('certifications/media')
                            ->visibility('public')
                            ->openable()
                            ->downloadable()
                            ->previewable()
                            ->maxSize(5120)
                            ->helperText('Add images or PDFs (max 5MB each).')
                            ->columnSpanFull(),
                    ]),

                Fieldset::make('PDF Preview')
                    ->schema([
                        Forms\Components\Placeholder::make('pdf_previews')
                            ->label('')
                            ->reactive()
                            ->columnSpanFull()
                            ->visible(fn (Get $get): bool => collect($get('media') ?? [])
                                ->contains(fn ($p) => Str::of((string) $p)->lower()->endsWith('.pdf')))
                            ->content(function (Get $get): HtmlString {
                                $urls = collect($get('media') ?? [])
                                    ->filter(fn ($p) => Str::of((string) $p)->lower()->endsWith('.pdf'))
                                    ->map(function ($p) {
                                        $disk = Storage::disk('r2');
                                        try {
                                            return $disk->temporaryUrl($p, now()->addMinutes(15));
                                        } catch (\Throwable $e) {
                                            return $disk->url($p);
                                        }
                                    })
                                    ->values()
                                    ->all();

                                return new HtmlString(view('filament/certifications/pdf-previews', [
                                    'urls' => $urls,
                                ])->render());
                            }),
                    ]),

                Fieldset::make('Display Order')
                    ->schema([
                        Forms\Components\TextInput::make('sort_order')
                            ->label('Sort order')
                            ->numeric()
                            ->default(0)
                            ->helperText('Lower numbers appear first.'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::Bold),

                Tables\Columns\TextColumn::make('organization.name')
                    ->label('Organization')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('issue_date')
                    ->label('Issued')
                    ->date('M d, Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('expiration_date')
                    ->label('Expires')
                    ->date('M d, Y')
                    ->placeholder('—')
                    ->sortable(),

                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Order')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('sort_order', 'asc')
            ->filters([])
            ->actions([
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCertifications::route('/'),
            'create' => Pages\CreateCertification::route('/create'),
            'edit' => Pages\EditCertification::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
