<?php

namespace App\Filament\Resources;
use App\Filament\Resources\ExperienceResource\Pages;
use App\Models\Experience;
use Filament\Actions;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Fieldset;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Support\Enums\FontWeight;
use UnitEnum;
use BackedEnum;

class ExperienceResource extends Resource
{
    protected static ?string $model = Experience::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-briefcase';

    protected static UnitEnum|string|null $navigationGroup = 'Portfolio';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\Hidden::make('user_id')
                    ->default(auth()->id())
                    ->required(),

                Fieldset::make('Basic Information')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Job Title')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('e.g., Senior Full Stack Developer'),

                        Forms\Components\Select::make('employment_type')
                            ->label('Employment Type')
                            ->options([
                                'Full-time' => 'Full-time',
                                'Part-time' => 'Part-time',
                                'Self-employed' => 'Self-employed',
                                'Freelance' => 'Freelance',
                                'Contract' => 'Contract',
                                'Internship' => 'Internship',
                                'Apprenticeship' => 'Apprenticeship',
                                'Seasonal' => 'Seasonal',
                            ])
                            ->required()
                            ->native(false),

                        Forms\Components\TextInput::make('company_name')
                            ->label('Company or Organization')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('e.g., Tech Corp Inc.'),
                    ])
                    ->columns(2),

                Fieldset::make('Duration')
                    ->schema([
                        Forms\Components\Toggle::make('is_current')
                            ->label('I am currently working in this role')
                            ->reactive()
                            ->afterStateUpdated(fn ($state, callable $set) => $state ? $set('end_date', null) : null),

                        Forms\Components\DatePicker::make('start_date')
                            ->label('Start Date')
                            ->required()
                            ->native(false)
                            ->displayFormat('M Y')
                            ->maxDate(now()),

                        Forms\Components\DatePicker::make('end_date')
                            ->label('End Date')
                            ->native(false)
                            ->displayFormat('M Y')
                            ->maxDate(now())
                            ->hidden(fn (callable $get) => $get('is_current'))
                            ->requiredUnless('is_current', true),
                    ])
                    ->columns(3),

                Fieldset::make('Location')
                    ->schema([
                        Forms\Components\TextInput::make('location')
                            ->label('Location')
                            ->maxLength(255)
                            ->placeholder('e.g., Manila, Philippines'),

                        Forms\Components\Select::make('location_type')
                            ->label('Location Type')
                            ->options([
                                'On-site' => 'On-site',
                                'Hybrid' => 'Hybrid',
                                'Remote' => 'Remote',
                            ])
                            ->native(false),
                    ])
                    ->columns(2),

                Fieldset::make('Description & Details')
                    ->schema([
                        Forms\Components\TextInput::make('profile_headline')
                            ->label('Profile Headline')
                            ->maxLength(255)
                            ->placeholder('Brief headline for this experience')
                            ->helperText('Optional: A short tagline that appears with this experience'),

                        Forms\Components\RichEditor::make('description')
                            ->label('Description')
                            ->placeholder('Describe your responsibilities, achievements, and key contributions...')
                            ->toolbarButtons(['bold', 'italic', 'bulletList', 'orderedList', 'link', 'undo', 'redo'])
                            ->columnSpanFull(),
                    ]),

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
                            ->label('Media Files')
                            ->multiple()
                            ->image()
                            ->imageEditor()
                            ->imageEditorAspectRatios(['16:9', '4:3', '1:1'])
                            ->disk('public')
                            ->directory('experiences/media')
                            ->visibility('public')
                            ->maxSize(5120)
                            ->helperText('Upload images, documents, or presentations (max 5MB each)')
                            ->columnSpanFull(),
                    ]),

                Fieldset::make('Display Order')
                    ->schema([
                        Forms\Components\TextInput::make('sort_order')
                            ->label('Sort Order')
                            ->numeric()
                            ->default(0)
                            ->helperText('Lower numbers appear first. Use this to manually order your experiences.'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Title')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::Bold),

                Tables\Columns\TextColumn::make('company_name')
                    ->label('Company')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('employment_type')
                    ->label('Type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Full-time' => 'success',
                        'Part-time' => 'info',
                        'Contract', 'Freelance' => 'warning',
                        'Internship' => 'gray',
                        default => 'primary',
                    }),

                Tables\Columns\TextColumn::make('start_date')
                    ->label('Started')
                    ->date('M Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('end_date')
                    ->label('Ended')
                    ->date('M Y')
                    ->sortable()
                    ->placeholder('Present'),

                Tables\Columns\IconColumn::make('is_current')
                    ->label('Current')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('gray'),

                Tables\Columns\TextColumn::make('location_type')
                    ->label('Location Type')
                    ->badge()
                    ->toggleable(),

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
            ->filters([
                Tables\Filters\SelectFilter::make('employment_type')
                    ->label('Employment Type')
                    ->options([
                        'Full-time' => 'Full-time',
                        'Part-time' => 'Part-time',
                        'Contract' => 'Contract',
                        'Freelance' => 'Freelance',
                        'Internship' => 'Internship',
                    ]),

                Tables\Filters\TernaryFilter::make('is_current')
                    ->label('Current Position')
                    ->placeholder('All')
                    ->trueLabel('Current only')
                    ->falseLabel('Past only'),

                Tables\Filters\SelectFilter::make('location_type')
                    ->label('Location Type')
                    ->options([
                        'On-site' => 'On-site',
                        'Hybrid' => 'Hybrid',
                        'Remote' => 'Remote',
                    ]),
            ])
            ->actions([
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->reorderable('sort_order');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListExperiences::route('/'),
            'create' => Pages\CreateExperience::route('/create'),
            'edit' => Pages\EditExperience::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
