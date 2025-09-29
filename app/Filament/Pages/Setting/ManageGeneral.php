<?php

namespace App\Filament\Pages\Setting;

use App\Rules\YoutubeUrl;
use App\Services\FileService;
use App\Settings\GeneralSettings;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\SettingsPage;
use Filament\Support\Facades\FilamentView;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;
use Riodwanto\FilamentAceEditor\AceEditor;

use function Filament\Support\is_app_url;

class ManageGeneral extends SettingsPage
{
    use HasPageShield;
    protected static string $settings = GeneralSettings::class;

    protected static ?int $navigationSort = 99;
    protected static ?string $navigationIcon = 'fluentui-settings-20';

    /**
     * @var array<string, mixed> | null
     */
    public ?array $data = [];

    public string $themePath = '';

    public string $twConfigPath = '';

    public function mount(): void
    {
        $this->themePath = resource_path('css/filament/admin/theme.css');
        $this->twConfigPath = resource_path('css/filament/admin/tailwind.config.js');

        $this->fillForm();
    }

    protected function fillForm(): void
    {
        $settings = app(static::getSettings());

        $data = $this->mutateFormDataBeforeFill($settings->toArray());

        $fileService = new FileService;

        $data['theme-editor'] = $fileService->readfile($this->themePath);

        $data['tw-config-editor'] = $fileService->readfile($this->twConfigPath);

        $this->form->fill($data);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Site')
                    ->label(fn () => __('page.general_settings.sections.site'))
                    ->description(fn () => __('page.general_settings.sections.site.description'))
                    ->icon('fluentui-web-asset-24-o')
                    ->schema([
                        Forms\Components\Grid::make()->schema([
                            Forms\Components\TextInput::make('brand_name')
                                ->label(fn () => __('page.general_settings.fields.brand_name'))
                                ->required(),
                            Forms\Components\Select::make('site_active')
                                ->label(fn () => __('page.general_settings.fields.site_active'))
                                ->options([
                                    0 => "Not Active",
                                    1 => "Active",
                                ])
                                ->native(false)
                                ->required(),
                        ]),
                        Forms\Components\Grid::make()->schema([
                            Forms\Components\Grid::make(2)->schema([
                                Forms\Components\TextInput::make('brand_logoHeight')
                                    ->label(fn () => __('page.general_settings.fields.brand_logoHeight'))
                                    ->required(),

                                TextInput::make('site_youtube')
                                    ->label('YouTube Video Link')
                                    ->rules([new YoutubeUrl()])
                                    ->label('YouTube Video Link')
                                    ->placeholder('Paste YouTube link here...')
                                    ->required(),

                            ])
                            ->columnSpanFull(),
                            Forms\Components\Grid::make(3)->schema([
                                Forms\Components\FileUpload::make('brand_logo')
                                    ->label(fn () => __('page.general_settings.fields.brand_logo'))
                                    ->image()
                                    ->directory('sites')
                                    ->visibility('public')
                                    ->moveFiles()
                                    ->columnSpan(2)
                                    ->required(),

                                Forms\Components\Grid::make()->schema([
                                    Forms\Components\FileUpload::make('site_favicon')
                                        ->label(fn () => __('page.general_settings.fields.site_favicon'))
                                        ->image()
                                        ->directory('sites')
                                        ->visibility('public')
                                        ->hint('test')
                                        ->moveFiles()
                                        ->columnSpanFull()
                                        ->acceptedFileTypes(['image/x-icon', 'image/vnd.microsoft.icon'])
                                        ->required(),
                                        Section::make('How to create site favicon?')
                                            ->schema([
                                                Placeholder::make('')
                                                    ->columnSpanFull()
                                                    ->content(new HtmlString(view('public_pages.instruction')->render()))
                                                // ...
                                            ])
                                            ->collapsed(true)
                                            ->columnSpanFull()
                                            ->collapsible()

                                ])->columnSpan(1)
                            ])
                            ->columnSpanFull(),



                        ])->columns(4),


                    ]),
                Forms\Components\Tabs::make('Tabs')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Color Palette')
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        Forms\Components\ColorPicker::make('site_theme.primary')
                                            ->label(fn () => __('page.general_settings.fields.primary'))
                                            ->helperText('The main brand color used for highlights and important elements across the queue board and kiosks')->rgb(),
                                        Forms\Components\ColorPicker::make('site_theme.secondary')
                                            ->label(fn () => __('page.general_settings.fields.secondary'))
                                            ->helperText('A supporting color used for accents, buttons, or complementary design elements')->rgb(),

                                        Forms\Components\ColorPicker::make('site_theme.gray')
                                            ->label(fn () => __('page.general_settings.fields.gray'))
                                            ->helperText('A neutral color used for text, borders, or backgrounds that don’t need emphasis')->rgb(),

                                        Forms\Components\ColorPicker::make('site_theme.success')
                                            ->label(fn () => __('page.general_settings.fields.success'))
                                            ->helperText('Indicates positive actions or completed transactions (e.g., successful queue updates)')->rgb(),

                                        Forms\Components\ColorPicker::make('site_theme.danger')
                                            ->label(fn () => __('page.general_settings.fields.danger'))
                                            ->helperText('Used for errors, warnings, or critical alerts that need attention')->rgb(),
                                        Forms\Components\ColorPicker::make('site_theme.info')
                                            ->label(fn () => __('page.general_settings.fields.info'))
                                            ->helperText('Highlights informational messages or status updates for users')->rgb(),

                                        Forms\Components\ColorPicker::make('site_theme.warning')
                                            ->label(fn () => __('page.general_settings.fields.warning'))
                                            ->helperText('Signals caution or pending actions that require user awareness')->rgb(),
                                    ])

                            ])
                            ->columns(3),
                        Forms\Components\Tabs\Tab::make('Code Editor')
                            ->hidden(fn () => auth()->user()->hasRole('super_admin') == false)
                            ->schema([
                                Forms\Components\Grid::make()->schema([
                                    AceEditor::make('theme-editor')
                                        ->label('theme.css')
                                        ->mode('css')
                                        ->height('24rem'),
                                    AceEditor::make('tw-config-editor')
                                        ->label('tailwind.config.js')
                                        ->height('24rem')
                                ])
                            ]),
                    ])
                    ->persistTabInQueryString()
                    ->columnSpanFull(),
            ])
            ->columns(3)
            ->statePath('data');
    }

    public function save(): void
    {
        try {
            $data = $this->mutateFormDataBeforeSave($this->form->getState());

            $settings = app(static::getSettings());

            $settings->fill($data);
            $settings->save();

            $fileService = new FileService;
            $fileService->writeFile($this->themePath, $data['theme-editor']);
            $fileService->writeFile($this->twConfigPath, $data['tw-config-editor']);

            Notification::make()
                ->title('Settings updated.')
                ->success()
                ->send();

            $this->redirect(static::getUrl(), navigate: FilamentView::hasSpaMode() && is_app_url(static::getUrl()));
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public static function getNavigationGroup(): ?string
    {
        return __("menu.nav_group.settings");
    }

    public static function getNavigationLabel(): string
    {
        return __("page.general_settings.navigationLabel");
    }

    public function getTitle(): string|Htmlable
    {
        return __("page.general_settings.title");
    }

    public function getHeading(): string|Htmlable
    {
        return __("page.general_settings.heading");
    }

    public function getSubheading(): string|Htmlable|null
    {
        return __("page.general_settings.subheading");
    }
}
