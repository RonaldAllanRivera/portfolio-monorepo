<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjectResource\Pages;
use App\Models\Project;
use App\Models\Experience;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Fieldset;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Support\Enums\FontWeight;
use BackedEnum;
use UnitEnum;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-rectangle-stack';

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
                        Forms\Components\TextInput::make('name')
                            ->label('Project name')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('e.g., Portfolio Platform v2'),

                        Forms\Components\Select::make('experience_id')
                            ->label('Associated with (Company)')
                            ->options(fn () => Experience::query()
                                ->where('is_current', true)
                                ->orderBy('company_name')
                                ->pluck('company_name', 'id')
                                ->all())
                            ->searchable()
                            ->preload()
                            ->native(false)
                            ->placeholder('Optional: link to your current experience/company')
                            ->helperText('Dropdown lists your current experiences.'),
                    ])
                    ->columns(2),

                Fieldset::make('Duration')
                    ->schema([
                        Forms\Components\Toggle::make('is_current')
                            ->label('I am currently working on this project')
                            ->reactive()
                            ->afterStateUpdated(fn ($state, callable $set) => $state ? $set('end_date', null) : null),

                        Forms\Components\DatePicker::make('start_date')
                            ->label('Start date')
                            ->required()
                            ->native(false)
                            ->displayFormat('M Y')
                            ->maxDate(now()),

                        Forms\Components\DatePicker::make('end_date')
                            ->label('End date')
                            ->native(false)
                            ->displayFormat('M Y')
                            ->maxDate(now())
                            ->hidden(fn (callable $get) => $get('is_current'))
                            ->requiredUnless('is_current', true),
                    ])
                    ->columns(3),

                Fieldset::make('Description')
                    ->schema([
                        Forms\Components\RichEditor::make('description')
                            ->label('Description')
                            ->placeholder('Describe the project scope, stack, goals, and outcomes...')
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
                            ->directory('projects/media')
                            ->visibility('public')
                            ->maxSize(5120)
                            ->helperText('Upload images, screenshots, or docs (max 5MB each)')
                            ->columnSpanFull(),
                    ]),

                Fieldset::make('Links')
                    ->schema([
                        Forms\Components\Select::make('links')
                            ->label('Links')
                            ->relationship('links', 'label')
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->native(false)
                            ->helperText('Add reusable links like Repo, Live, Docs, Demo, etc.')
                            ->createOptionForm([
                                Forms\Components\TextInput::make('label')
                                    ->label('Label')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('url')
                                    ->label('URL')
                                    ->required()
                                    ->url()
                                    ->maxLength(2048),
                                Forms\Components\Select::make('type')
                                    ->label('Type')
                                    ->options([
                                        'Live' => 'Live',
                                        'Repo' => 'Repo',
                                        'Docs' => 'Docs',
                                        'Demo' => 'Demo',
                                        'Case Study' => 'Case Study',
                                        'Other' => 'Other',
                                    ])
                                    ->native(false),
                            ])
                            ->columnSpanFull(),
                    ]),

                Fieldset::make('Display Order')
                    ->schema([
                        Forms\Components\TextInput::make('sort_order')
                            ->label('Sort Order')
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
                    ->label('Project')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::Bold),

                Tables\Columns\TextColumn::make('experience.company_name')
                    ->label('Company')
                    ->toggleable()
                    ->searchable()
                    ->sortable(),

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
                    ->label('Current projects')
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
            'index' => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProject::route('/create'),
            'edit' => Pages\EditProject::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
