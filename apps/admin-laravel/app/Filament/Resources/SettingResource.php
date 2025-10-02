<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SettingResource\Pages;
use App\Models\Setting;
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

class SettingResource extends Resource
{
    protected static ?string $model = Setting::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static UnitEnum|string|null $navigationGroup = 'Configuration';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\Hidden::make('user_id')
                    ->default(auth()->id())
                    ->required(),

                Fieldset::make('Profile')
                    ->schema([
                        Forms\Components\TextInput::make('headline')
                            ->label('Headline')
                            ->maxLength(255)
                            ->placeholder('e.g., Senior Full Stack Developer'),

                        Forms\Components\RichEditor::make('about_me')
                            ->label('About Me')
                            ->placeholder('Tell something about yourself...')
                            ->toolbarButtons(['bold', 'italic', 'bulletList', 'orderedList', 'link', 'undo', 'redo'])
                            ->columnSpanFull(),
                    ])
                    ->columns(1),

                Fieldset::make('Media')
                    ->schema([
                        Forms\Components\FileUpload::make('logo')
                            ->label('Upload Logo')
                            ->image()
                            ->imageEditor()
                            ->imageEditorAspectRatios(['1:1', '4:3', '16:9'])
                            ->disk('public')
                            ->directory('settings/logo')
                            ->visibility('public')
                            ->maxSize(4096),

                        Forms\Components\FileUpload::make('favicon')
                            ->label('Upload Favicon')
                            ->image()
                            ->disk('public')
                            ->directory('settings/favicon')
                            ->visibility('public')
                            ->maxSize(1024),

                        Forms\Components\FileUpload::make('profile_picture')
                            ->label('Profile Picture')
                            ->image()
                            ->imageEditor()
                            ->imageEditorAspectRatios(['1:1'])
                            ->rules(['image', 'dimensions:max_width=800,max_height=800'])
                            ->helperText('Square image. Maximum 800x800 pixels.')
                            ->disk('public')
                            ->directory('settings/profile')
                            ->visibility('public')
                            ->maxSize(2048),
                    ])
                    ->columns(2),

                Fieldset::make('SEO')
                    ->schema([
                        Forms\Components\TextInput::make('seo_title')
                            ->label('SEO Title')
                            ->maxLength(255),

                        Forms\Components\Textarea::make('seo_description')
                            ->label('SEO Description')
                            ->rows(3)
                            ->maxLength(1000),

                        Forms\Components\TagsInput::make('seo_keywords')
                            ->label('SEO Keywords')
                            ->placeholder('Add keywords...')
                            ->helperText('Press Enter to add each keyword.')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Fieldset::make('Contact Info')
                    ->schema([
                        Forms\Components\TextInput::make('contact_email')
                            ->label('Email')
                            ->email()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('contact_phone')
                            ->label('Phone')
                            ->maxLength(50),

                        Forms\Components\TextInput::make('contact_whatsapp')
                            ->label('WhatsApp')
                            ->maxLength(50),
                    ])
                    ->columns(3),

                Fieldset::make('Social Links')
                    ->schema([
                        Forms\Components\TextInput::make('github_url')->label('GitHub')->url()->maxLength(2048),
                        Forms\Components\TextInput::make('linkedin_url')->label('LinkedIn')->url()->maxLength(2048),
                        Forms\Components\TextInput::make('twitter_url')->label('Twitter/X')->url()->maxLength(2048),
                        Forms\Components\TextInput::make('youtube_url')->label('YouTube')->url()->maxLength(2048),
                        Forms\Components\TextInput::make('dribbble_url')->label('Dribbble')->url()->maxLength(2048),
                        Forms\Components\TextInput::make('behance_url')->label('Behance')->url()->maxLength(2048),
                    ])
                    ->columns(3),

                Fieldset::make('Personal Information')
                    ->schema([
                        Forms\Components\DatePicker::make('date_of_birth')
                            ->label('Date of Birth')
                            ->native(false)
                            ->displayFormat('Y-m-d'),

                        Forms\Components\Select::make('gender')
                            ->label('Gender')
                            ->options([
                                'Male' => 'Male',
                                'Female' => 'Female',
                                'Non-binary' => 'Non-binary',
                                'Prefer not to say' => 'Prefer not to say',
                            ])
                            ->native(false)
                            ->searchable(),

                        Forms\Components\Select::make('marital_status')
                            ->label('Marital Status')
                            ->options([
                                'Single' => 'Single',
                                'Married' => 'Married',
                                'Divorced' => 'Divorced',
                                'Widowed' => 'Widowed',
                                'Prefer not to say' => 'Prefer not to say',
                            ])
                            ->native(false)
                            ->searchable(),

                        Forms\Components\Select::make('nationality')
                            ->label('Nationality')
                            ->options([
                                'Filipino' => 'Filipino',
                                'American' => 'American',
                                'Canadian' => 'Canadian',
                                'British' => 'British',
                                'Australian' => 'Australian',
                                'Other' => 'Other',
                            ])
                            ->native(false)
                            ->searchable(),
                    ])
                    ->columns(2),

                Fieldset::make('Address')
                    ->schema([
                        Forms\Components\TextInput::make('address_line1')->label('Address line 1')->maxLength(255),
                        Forms\Components\TextInput::make('address_line2')->label('Address line 2')->maxLength(255),
                        Forms\Components\TextInput::make('address_city')->label('City')->maxLength(120),
                        Forms\Components\TextInput::make('address_state')->label('State/Province')->maxLength(120),
                        Forms\Components\TextInput::make('address_postal_code')->label('Postal Code')->maxLength(20),
                        Forms\Components\TextInput::make('address_country')->label('Country')->maxLength(120),
                    ])
                    ->columns(3),

                Fieldset::make('Availability')
                    ->schema([
                        Forms\Components\Toggle::make('open_to_work')
                            ->label('Open to work')
                            ->default(true),

                        Forms\Components\TextInput::make('hourly_rate')
                            ->label('Hourly rate')
                            ->numeric()
                            ->prefix('USD')
                            ->helperText('Approximate rate for freelance/contract work'),

                        Forms\Components\TagsInput::make('preferred_roles')
                            ->label('Preferred roles')
                            ->placeholder('e.g., Full Stack, Backend, DevOps')
                            ->helperText('Press Enter to add each role')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

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
                Tables\Columns\TextColumn::make('headline')
                    ->label('Headline')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::Bold),

                Tables\Columns\TextColumn::make('seo_title')
                    ->label('SEO Title')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('contact_email')
                    ->label('Email')
                    ->toggleable(),

                Tables\Columns\IconColumn::make('open_to_work')
                    ->label('Open to work')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('gray'),

                Tables\Columns\TextColumn::make('nationality')
                    ->label('Nationality')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('date_of_birth')
                    ->label('DOB')
                    ->date('Y-m-d')
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
            'index' => Pages\ListSettings::route('/'),
            'create' => Pages\CreateSetting::route('/create'),
            'edit' => Pages\EditSetting::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
