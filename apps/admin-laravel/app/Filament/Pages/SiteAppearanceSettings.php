<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Actions;
use Filament\Forms;
use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Storage;
use BackedEnum;
use UnitEnum;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Fieldset;

class SiteAppearanceSettings extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-paint-brush';

    protected static UnitEnum|string|null $navigationGroup = 'Configuration';

    protected static ?string $title = 'Site Appearance';

    protected static ?string $navigationLabel = 'Appearance';

    protected static ?int $navigationSort = 10;

    protected string $view = 'filament.pages.site-appearance-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $setting = Setting::ordered()->first();

        $this->data = $setting
            ? $setting->only([
                'active_public_template',
                'brand_primary_color',
                'brand_secondary_color',
                'logo',
                'favicon',
                'seo_title',
                'seo_description',
            ])
            : [];

        $this->form->fill($this->data);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Fieldset::make('Appearance')
                    ->schema([
                        Forms\Components\Select::make('active_public_template')
                            ->label('Active Public Template')
                            ->options(fn () => collect(config('templates.templates', []))
                                ->mapWithKeys(fn ($tpl) => [($tpl['slug'] ?? '') => ($tpl['name'] ?? ($tpl['slug'] ?? ''))])
                                ->all())
                            ->native(false)
                            ->searchable()
                            ->helperText('Choose which public template Next.js should render.'),

                        Forms\Components\ColorPicker::make('brand_primary_color')
                            ->label('Primary Color')
                            ->helperText('Primary brand color used by public templates.'),

                        Forms\Components\ColorPicker::make('brand_secondary_color')
                            ->label('Secondary Color')
                            ->helperText('Secondary brand color used by public templates.'),
                    ])
                    ->columns(3),

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
                    ])
                    ->columns(2),
            ])
            ->statePath('data');
    }

    public function getHeaderActions(): array
    {
        return [
            Actions\Action::make('preview')
                ->label('Preview')
                ->color('gray')
                ->icon('heroicon-o-eye')
                ->action('preview')
                ->extraAttributes(['style' => 'margin-right: .5rem;'])
                ->requiresConfirmation(false),

            Actions\Action::make('save')
                ->label('Save')
                ->color('primary')
                ->icon('heroicon-o-check')
                ->action('save'),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $setting = Setting::ordered()->first();
        if (! $setting) {
            $setting = new Setting();
            $setting->user_id = auth()->id();
        }

        $setting->fill([
            'active_public_template' => $data['active_public_template'] ?? null,
            'brand_primary_color' => $data['brand_primary_color'] ?? null,
            'brand_secondary_color' => $data['brand_secondary_color'] ?? null,
            'logo' => $data['logo'] ?? $setting->logo,
            'favicon' => $data['favicon'] ?? $setting->favicon,
            'seo_title' => $data['seo_title'] ?? null,
            'seo_description' => $data['seo_description'] ?? null,
        ]);

        $setting->save();

        Notification::make()
            ->title('Appearance settings saved')
            ->success()
            ->send();
    }

    public function preview(): void
    {
        $data = $this->form->getState();
        $slug = $data['active_public_template'] ?? 'classic';
        $base = env('PUBLIC_SITE_URL', 'http://allanwebdesign.com.2025.test');
        $url = rtrim($base, '/') . '/?template=' . urlencode($slug);

        // Open in new tab by redirecting away
        $this->redirect($url, navigate: false);
    }
}
