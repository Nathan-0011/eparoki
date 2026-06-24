<?php

namespace App\Filament\Pages;

use App\Models\ParishProfile;
use App\Models\Pastor;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\FileUpload;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

class ProfilParoki extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-building-library';
    protected static string $view = 'filament.pages.profil-paroki';
    protected static ?string $title = 'Profil Paroki';
    
    // Matikan group agar muncul di root sidebar
    protected static ?string $navigationGroup = null;

    public ?array $data = [];
    public $pastorAktif;
    public $profile;

    public function mount(): void
    {
        // Ambil profil (jika belum ada, method di model ini otomatis membuatnya)
        $this->profile = ParishProfile::getProfile();
        
        // Isi form dengan data yang ada di database
        $this->form->fill($this->profile->toArray());
        
        // Data pastor aktif untuk ditampilkan di preview
        $this->pastorAktif = Pastor::active()->first();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Dasar')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('name')
                                ->label('Nama Paroki')
                                ->required(),
                            TextInput::make('diocese')
                                ->label('Keuskupan')
                                ->required(),
                            TextInput::make('established_year')
                                ->label('Tahun Berdiri')
                                ->numeric()
                                ->placeholder('1952')
                                ->nullable(),
                            TextInput::make('feast_day')
                                ->label('Hari Pesta Pelindung')
                                ->placeholder('24 April')
                                ->nullable(),
                            TextInput::make('phone')
                                ->label('Nomor Telepon')
                                ->placeholder('(0625) 41234')
                                ->nullable(),
                            TextInput::make('email')
                                ->label('Email Paroki')
                                ->email()
                                ->nullable(),
                            TextInput::make('website')
                                ->label('Website')
                                ->url()
                                ->placeholder('https://')
                                ->nullable(),
                        ]),
                    ]),

                Section::make('Alamat & Lokasi')
                    ->schema([
                        Textarea::make('address')
                            ->label('Alamat Lengkap')
                            ->rows(2)
                            ->required(),
                        TextInput::make('google_maps_url')
                            ->label('Link Google Maps')
                            ->url()
                            ->nullable(),
                        Textarea::make('google_maps_embed')
                            ->label('Embed Google Maps (iframe)')
                            ->hint('Salin kode iframe dari Google Maps > Share > Embed a map')
                            ->rows(3)
                            ->nullable(),
                    ]),

                Section::make('Tentang Paroki')
                    ->schema([
                        RichEditor::make('description')
                            ->label('Sejarah & Deskripsi Paroki')
                            ->toolbarButtons([
                                'bold', 'italic', 'bulletList', 'orderedList'
                            ])
                            ->nullable(),
                    ]),

                Section::make('Logo & Identitas')
                    ->schema([
                        Grid::make(2)->schema([
                            FileUpload::make('logo')
                                ->label('Logo Paroki')
                                ->directory('parish')
                                ->image()
                                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                ->maxSize(2048)
                                ->imagePreviewHeight('100')
                                ->nullable(),
                        ]),
                    ]),
            ])
            ->statePath('data');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('save')
                ->label('Simpan Perubahan')
                ->action('save')
                ->color('primary')
                ->icon('heroicon-o-check'),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();

        // Update database
        $this->profile->update($data);

        // Notifikasi pop-up hijau di kanan atas
        Notification::make()
            ->title('Berhasil disimpan')
            ->body('Data profil paroki telah diperbarui.')
            ->success()
            ->send();
            
        // Refresh profil
        $this->profile->refresh();
    }
}
