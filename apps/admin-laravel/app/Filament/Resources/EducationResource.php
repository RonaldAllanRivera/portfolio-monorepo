<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EducationResource\Pages;
use App\Models\Education;
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

class EducationResource extends Resource
{
    protected static ?string $model = Education::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-academic-cap';

    protected static UnitEnum|string|null $navigationGroup = 'Portfolio';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\Hidden::make('user_id')
                    ->default(auth()->id())
                    ->required(),

                Fieldset::make('School Details')
                    ->schema([
                        Forms\Components\TextInput::make('school')
                            ->label('School')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('e.g., University of the Philippines'),

                        Forms\Components\TextInput::make('degree')
                            ->label('Degree')
                            ->maxLength(255)
                            ->placeholder('e.g., Bachelor of Science'),

                        Forms\Components\TextInput::make('field_of_study')
                            ->label('Field of study')
                            ->maxLength(255)
                            ->placeholder('e.g., Computer Science'),
                    ])
                    ->columns(3),

                Fieldset::make('Duration')
                    ->schema([
                        Forms\Components\Toggle::make('is_current')
                            ->label('Currently studying (or expected)')
                            ->reactive()
                            ->afterStateUpdated(fn ($state, callable $set) => $state ? $set('end_date', null) : null),

                        Forms\Components\DatePicker::make('start_date')
                            ->label('Start date')
                            ->required()
                            ->native(false)
                            ->displayFormat('M Y')
                            ->maxDate(now()),

                        Forms\Components\DatePicker::make('end_date')
                            ->label('End date (or expected)')
                            ->native(false)
                            ->displayFormat('M Y')
                            ->maxDate(now())
                            ->hidden(fn (callable $get) => $get('is_current')),
                    ])
                    ->columns(3),

                Fieldset::make('Academics & Activities')
                    ->schema([
                        Forms\Components\TextInput::make('grade')
                            ->label('Grade')
                            ->maxLength(100),

                        Forms\Components\Textarea::make('activities_and_societies')
                            ->label('Activities and societies')
                            ->rows(3)
                            ->placeholder('e.g., Programming Club, ACM, Hackathons'),
                    ])
                    ->columns(2),

                Fieldset::make('Description')
                    ->schema([
                        Forms\Components\RichEditor::make('description')
                            ->label('Description')
                            ->placeholder('Coursework, projects, awards, thesis, and highlights...')
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
                            ->label('Media')
                            ->multiple()
                            ->image()
                            ->imageEditor()
                            ->imageEditorAspectRatios(['16:9', '4:3', '1:1'])
                            ->disk('r2')
                            ->directory('educations/media')
                            ->visibility('public')
                            ->maxSize(5120)
                            ->helperText('Add media like images, documents, sites or presentations (max 5MB each).')
                            ->columnSpanFull(),
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
                Tables\Columns\TextColumn::make('school')
                    ->label('School')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::Bold),

                Tables\Columns\TextColumn::make('degree')
                    ->label('Degree')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('field_of_study')
                    ->label('Field of Study')
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('start_date')
                    ->label('Start')
                    ->date('M Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('end_date')
                    ->label('End')
                    ->date('M Y')
                    ->placeholder('Present')
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_current')
                    ->label('Current')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('gray'),

                Tables\Columns\TextColumn::make('grade')
                    ->label('Grade')
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
                Tables\Filters\TernaryFilter::make('is_current')
                    ->label('Currently studying')
                    ->placeholder('All')
                    ->trueLabel('Current only')
                    ->falseLabel('Completed only'),
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
            'index' => Pages\ListEducations::route('/'),
            'create' => Pages\CreateEducation::route('/create'),
            'edit' => Pages\EditEducation::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
