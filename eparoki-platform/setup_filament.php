<?php

$basePath = __DIR__;

function makeFile($path, $content) {
    global $basePath;
    $fullPath = $basePath . '/' . $path;
    $dir = dirname($fullPath);
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
    file_put_contents($fullPath, $content);
}

// ==========================================
// LANGKAH 2: AdminPanelProvider
// ==========================================
makeFile("app/Providers/Filament/AdminPanelProvider.php", '<?php
namespace App\Providers\Filament;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id("admin")
            ->path("admin")
            ->login()
            ->brandName("eKatolik — Santo Fidelis Parapat")
            ->colors([
                "primary" => "#7c3aed",
            ])
            ->darkMode(true)
            ->spa()
            ->navigationGroups([
                "Liturgi & Ibadah",
                "Data Umat",
                "Kepemimpinan",
                "Konten & Media",
                "Keuangan & Intensi",
                "Perangkat IoT",
            ])
            ->discoverResources(in: app_path("Filament/Resources"), for: "App\\\\Filament\\\\Resources")
            ->discoverPages(in: app_path("Filament/Pages"), for: "App\\\\Filament\\\\Pages")
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path("Filament/Widgets"), for: "App\\\\Filament\\\\Widgets")
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
');

// ==========================================
// LANGKAH 3: RESOURCES
// ==========================================

// 1. LiturgicalCalendarResource
makeFile("app/Filament/Resources/LiturgicalCalendarResource.php", '<?php
namespace App\Filament\Resources;
use App\Filament\Resources\LiturgicalCalendarResource\Pages;
use App\Models\LiturgicalCalendar;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class LiturgicalCalendarResource extends Resource
{
    protected static ?string $model = LiturgicalCalendar::class;
    protected static ?string $navigationIcon = "heroicon-o-calendar";
    protected static ?string $navigationGroup = "Liturgi & Ibadah";
    protected static ?string $navigationLabel = "Kalender Liturgi";
    protected static ?string $recordTitleAttribute = "title";

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make("year")->numeric()->required()->default(date("Y")),
                Forms\Components\Select::make("month")->options([1=>"Januari",2=>"Februari",3=>"Maret",4=>"April",5=>"Mei",6=>"Juni",7=>"Juli",8=>"Agustus",9=>"September",10=>"Oktober",11=>"November",12=>"Desember"])->required(),
                Forms\Components\Select::make("week_number")->options([1=>"Minggu 1",2=>"Minggu 2",3=>"Minggu 3",4=>"Minggu 4",5=>"Minggu 5"])->required(),
                Forms\Components\TextInput::make("title")->required()->placeholder("Minggu Paskah II"),
                Forms\Components\Textarea::make("description")->nullable()->rows(4)->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make("year")->sortable(),
                Tables\Columns\TextColumn::make("month")->badge(),
                Tables\Columns\TextColumn::make("week_number")->badge()->formatStateUsing(fn ($state) => "Minggu ".$state),
                Tables\Columns\TextColumn::make("title")->searchable(),
                Tables\Columns\TextColumn::make("created_at")->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make("year")->options([2024=>2024,2025=>2025,2026=>2026]),
                Tables\Filters\SelectFilter::make("month")->options([1=>"Januari",2=>"Februari",3=>"Maret",4=>"April",5=>"Mei",6=>"Juni",7=>"Juli",8=>"Agustus",9=>"September",10=>"Oktober",11=>"November",12=>"Desember"]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()->requiresConfirmation(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            "index" => Pages\ListLiturgicalCalendars::route("/"),
            "create" => Pages\CreateLiturgicalCalendar::route("/create"),
            "edit" => Pages\EditLiturgicalCalendar::route("/{record}/edit"),
        ];
    }
}
');
makeFile("app/Filament/Resources/LiturgicalCalendarResource/Pages/ListLiturgicalCalendars.php", '<?php namespace App\Filament\Resources\LiturgicalCalendarResource\Pages; use App\Filament\Resources\LiturgicalCalendarResource; use Filament\Actions; use Filament\Resources\Pages\ListRecords; class ListLiturgicalCalendars extends ListRecords { protected static string $resource = LiturgicalCalendarResource::class; protected function getHeaderActions(): array { return [Actions\CreateAction::make()]; } }');
makeFile("app/Filament/Resources/LiturgicalCalendarResource/Pages/CreateLiturgicalCalendar.php", '<?php namespace App\Filament\Resources\LiturgicalCalendarResource\Pages; use App\Filament\Resources\LiturgicalCalendarResource; use Filament\Actions; use Filament\Resources\Pages\CreateRecord; class CreateLiturgicalCalendar extends CreateRecord { protected static string $resource = LiturgicalCalendarResource::class; }');
makeFile("app/Filament/Resources/LiturgicalCalendarResource/Pages/EditLiturgicalCalendar.php", '<?php namespace App\Filament\Resources\LiturgicalCalendarResource\Pages; use App\Filament\Resources\LiturgicalCalendarResource; use Filament\Actions; use Filament\Resources\Pages\EditRecord; class EditLiturgicalCalendar extends EditRecord { protected static string $resource = LiturgicalCalendarResource::class; protected function getHeaderActions(): array { return [Actions\DeleteAction::make()]; } }');

// 2. JadwalIbadahResource
makeFile("app/Filament/Resources/JadwalIbadahResource.php", '<?php
namespace App\Filament\Resources;
use App\Filament\Resources\JadwalIbadahResource\Pages;
use App\Models\JadwalIbadah;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class JadwalIbadahResource extends Resource
{
    protected static ?string $model = JadwalIbadah::class;
    protected static ?string $navigationIcon = "heroicon-o-clock";
    protected static ?string $navigationGroup = "Liturgi & Ibadah";
    protected static ?string $navigationLabel = "Jadwal Ibadah";

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make("calendar_id")->relationship("calendar", "title")->required()->searchable(),
                Forms\Components\Select::make("day_of_week")->options(["Senin"=>"Senin","Selasa"=>"Selasa","Rabu"=>"Rabu","Kamis"=>"Kamis","Jumat"=>"Jumat","Sabtu"=>"Sabtu","Minggu"=>"Minggu"])->required(),
                Forms\Components\TimePicker::make("time")->required(),
                Forms\Components\Select::make("type")->options(["Misa Harian"=>"Misa Harian","Misa Minggu"=>"Misa Minggu","Misa Vigili"=>"Misa Vigili","Ibadah Doa Rosario"=>"Ibadah Doa Rosario","Novena"=>"Novena","Adorasi Ekaristi"=>"Adorasi Ekaristi"])->required(),
                Forms\Components\TextInput::make("celebrant")->nullable()->placeholder("Rm. Fidelis Tambunan"),
                Forms\Components\TextInput::make("location")->default("Gereja Santo Fidelis Parapat"),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make("calendar.title")->label("Calendar"),
                Tables\Columns\TextColumn::make("day_of_week")->badge()->color(fn ($state) => match ($state) { "Minggu" => "danger", "Sabtu" => "warning", default => "primary" }),
                Tables\Columns\TextColumn::make("time")->time("H:i")->label("Jam"),
                Tables\Columns\TextColumn::make("type")->badge(),
                Tables\Columns\TextColumn::make("celebrant"),
                Tables\Columns\TextColumn::make("location"),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make("day_of_week")->options(["Senin"=>"Senin","Selasa"=>"Selasa","Rabu"=>"Rabu","Kamis"=>"Kamis","Jumat"=>"Jumat","Sabtu"=>"Sabtu","Minggu"=>"Minggu"]),
                Tables\Filters\SelectFilter::make("type")->options(["Misa Harian"=>"Misa Harian","Misa Minggu"=>"Misa Minggu","Misa Vigili"=>"Misa Vigili","Ibadah Doa Rosario"=>"Ibadah Doa Rosario","Novena"=>"Novena","Adorasi Ekaristi"=>"Adorasi Ekaristi"]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            "index" => Pages\ListJadwalIbadahs::route("/"),
            "create" => Pages\CreateJadwalIbadah::route("/create"),
            "edit" => Pages\EditJadwalIbadah::route("/{record}/edit"),
        ];
    }
}
');
makeFile("app/Filament/Resources/JadwalIbadahResource/Pages/ListJadwalIbadahs.php", '<?php namespace App\Filament\Resources\JadwalIbadahResource\Pages; use App\Filament\Resources\JadwalIbadahResource; use Filament\Actions; use Filament\Resources\Pages\ListRecords; class ListJadwalIbadahs extends ListRecords { protected static string $resource = JadwalIbadahResource::class; protected function getHeaderActions(): array { return [Actions\CreateAction::make()]; } }');
makeFile("app/Filament/Resources/JadwalIbadahResource/Pages/CreateJadwalIbadah.php", '<?php namespace App\Filament\Resources\JadwalIbadahResource\Pages; use App\Filament\Resources\JadwalIbadahResource; use Filament\Actions; use Filament\Resources\Pages\CreateRecord; class CreateJadwalIbadah extends CreateRecord { protected static string $resource = JadwalIbadahResource::class; }');
makeFile("app/Filament/Resources/JadwalIbadahResource/Pages/EditJadwalIbadah.php", '<?php namespace App\Filament\Resources\JadwalIbadahResource\Pages; use App\Filament\Resources\JadwalIbadahResource; use Filament\Actions; use Filament\Resources\Pages\EditRecord; class EditJadwalIbadah extends EditRecord { protected static string $resource = JadwalIbadahResource::class; protected function getHeaderActions(): array { return [Actions\DeleteAction::make()]; } }');

// 3. LingkunganResource
makeFile("app/Filament/Resources/LingkunganResource.php", '<?php
namespace App\Filament\Resources;
use App\Filament\Resources\LingkunganResource\Pages;
use App\Models\Lingkungan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class LingkunganResource extends Resource
{
    protected static ?string $model = Lingkungan::class;
    protected static ?string $navigationIcon = "heroicon-o-home";
    protected static ?string $navigationGroup = "Data Umat";
    protected static ?string $navigationLabel = "Lingkungan";
    protected static ?string $recordTitleAttribute = "name";

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make("name")->required()->placeholder("Lingkungan Santo Antonius"),
                Forms\Components\TextInput::make("patron_saint")->nullable(),
                Forms\Components\Textarea::make("description")->nullable()->rows(3),
                Forms\Components\FileUpload::make("photo")->directory("lingkungan")->image()->maxSize(2048),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make("photo")->circular(),
                Tables\Columns\TextColumn::make("name")->searchable()->sortable(),
                Tables\Columns\TextColumn::make("patron_saint"),
                Tables\Columns\TextColumn::make("kepala_keluarga_count")->counts("kepalaKeluarga")->label("Jumlah KK"),
                Tables\Columns\TextColumn::make("created_at")->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                Tables\Actions\Action::make("lihat_kk")->label("Lihat Daftar KK")->icon("heroicon-o-users")->url(fn (Lingkungan $record): string => KepalaKeluargaResource::getUrl("index", ["tableFilters" => ["lingkungan_id" => ["value" => $record->id]]])),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            "index" => Pages\ListLingkungans::route("/"),
            "create" => Pages\CreateLingkungan::route("/create"),
            "edit" => Pages\EditLingkungan::route("/{record}/edit"),
        ];
    }
}
');
makeFile("app/Filament/Resources/LingkunganResource/Pages/ListLingkungans.php", '<?php namespace App\Filament\Resources\LingkunganResource\Pages; use App\Filament\Resources\LingkunganResource; use Filament\Actions; use Filament\Resources\Pages\ListRecords; class ListLingkungans extends ListRecords { protected static string $resource = LingkunganResource::class; protected function getHeaderActions(): array { return [Actions\CreateAction::make()]; } }');
makeFile("app/Filament/Resources/LingkunganResource/Pages/CreateLingkungan.php", '<?php namespace App\Filament\Resources\LingkunganResource\Pages; use App\Filament\Resources\LingkunganResource; use Filament\Actions; use Filament\Resources\Pages\CreateRecord; class CreateLingkungan extends CreateRecord { protected static string $resource = LingkunganResource::class; }');
makeFile("app/Filament/Resources/LingkunganResource/Pages/EditLingkungan.php", '<?php namespace App\Filament\Resources\LingkunganResource\Pages; use App\Filament\Resources\LingkunganResource; use Filament\Actions; use Filament\Resources\Pages\EditRecord; class EditLingkungan extends EditRecord { protected static string $resource = LingkunganResource::class; protected function getHeaderActions(): array { return [Actions\DeleteAction::make()]; } }');

// 4. KepalaKeluargaResource
makeFile("app/Filament/Resources/KepalaKeluargaResource.php", '<?php
namespace App\Filament\Resources;
use App\Filament\Resources\KepalaKeluargaResource\Pages;
use App\Models\KepalaKeluarga;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class KepalaKeluargaResource extends Resource
{
    protected static ?string $model = KepalaKeluarga::class;
    protected static ?string $navigationIcon = "heroicon-o-users";
    protected static ?string $navigationGroup = "Data Umat";
    protected static ?string $navigationLabel = "Kepala Keluarga";
    protected static ?string $recordTitleAttribute = "nama_kk";

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make("lingkungan_id")->relationship("lingkungan", "name")->required()->searchable(),
                Forms\Components\TextInput::make("nama_kk")->required()->placeholder("Keluarga Marihot Siahaan"),
                Forms\Components\Textarea::make("alamat")->nullable()->rows(2),
                Forms\Components\TextInput::make("jumlah_anggota")->numeric()->default(1),
                Forms\Components\TextInput::make("no_telp")->nullable()->placeholder("08xx-xxxx-xxxx"),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make("nama_kk")->searchable()->sortable(),
                Tables\Columns\TextColumn::make("lingkungan.name")->badge(),
                Tables\Columns\TextColumn::make("alamat")->limit(50),
                Tables\Columns\TextColumn::make("jumlah_anggota"),
                Tables\Columns\TextColumn::make("no_telp"),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make("lingkungan_id")->relationship("lingkungan", "name"),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            "index" => Pages\ListKepalaKeluargas::route("/"),
            "create" => Pages\CreateKepalaKeluarga::route("/create"),
            "edit" => Pages\EditKepalaKeluarga::route("/{record}/edit"),
        ];
    }
}
');
makeFile("app/Filament/Resources/KepalaKeluargaResource/Pages/ListKepalaKeluargas.php", '<?php namespace App\Filament\Resources\KepalaKeluargaResource\Pages; use App\Filament\Resources\KepalaKeluargaResource; use Filament\Actions; use Filament\Resources\Pages\ListRecords; class ListKepalaKeluargas extends ListRecords { protected static string $resource = KepalaKeluargaResource::class; protected function getHeaderActions(): array { return [Actions\CreateAction::make()]; } }');
makeFile("app/Filament/Resources/KepalaKeluargaResource/Pages/CreateKepalaKeluarga.php", '<?php namespace App\Filament\Resources\KepalaKeluargaResource\Pages; use App\Filament\Resources\KepalaKeluargaResource; use Filament\Actions; use Filament\Resources\Pages\CreateRecord; class CreateKepalaKeluarga extends CreateRecord { protected static string $resource = KepalaKeluargaResource::class; }');
makeFile("app/Filament/Resources/KepalaKeluargaResource/Pages/EditKepalaKeluarga.php", '<?php namespace App\Filament\Resources\KepalaKeluargaResource\Pages; use App\Filament\Resources\KepalaKeluargaResource; use Filament\Actions; use Filament\Resources\Pages\EditRecord; class EditKepalaKeluarga extends EditRecord { protected static string $resource = KepalaKeluargaResource::class; protected function getHeaderActions(): array { return [Actions\DeleteAction::make()]; } }');

// 5. PastorResource
makeFile("app/Filament/Resources/PastorResource.php", '<?php
namespace App\Filament\Resources;
use App\Filament\Resources\PastorResource\Pages;
use App\Models\Pastor;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PastorResource extends Resource
{
    protected static ?string $model = Pastor::class;
    protected static ?string $navigationIcon = "heroicon-o-user-circle";
    protected static ?string $navigationGroup = "Kepemimpinan";
    protected static ?string $navigationLabel = "Pastor Paroki";
    protected static ?string $recordTitleAttribute = "name";

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make("name")->required()->placeholder("Rm. Fidelis Tambunan, OFMCap"),
                Forms\Components\FileUpload::make("photo")->directory("pastors")->image()->maxSize(2048)->avatar(),
                Forms\Components\TextInput::make("period_start")->numeric()->required()->placeholder("2021"),
                Forms\Components\TextInput::make("period_end")->numeric()->nullable()->placeholder("Kosongkan jika masih aktif"),
                Forms\Components\Toggle::make("is_active")->label("Pastor Aktif Saat Ini")->hint("Hanya 1 pastor yang boleh aktif"),
                Forms\Components\RichEditor::make("biography")->nullable()->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make("photo")->circular()->size(40),
                Tables\Columns\TextColumn::make("name")->searchable(),
                Tables\Columns\TextColumn::make("period_start"),
                Tables\Columns\TextColumn::make("period_end")->formatStateUsing(fn ($state) => $state ?? "Sekarang"),
                Tables\Columns\IconColumn::make("is_active")->boolean(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            "index" => Pages\ListPastors::route("/"),
            "create" => Pages\CreatePastor::route("/create"),
            "edit" => Pages\EditPastor::route("/{record}/edit"),
        ];
    }
}
');
makeFile("app/Filament/Resources/PastorResource/Pages/ListPastors.php", '<?php namespace App\Filament\Resources\PastorResource\Pages; use App\Filament\Resources\PastorResource; use Filament\Actions; use Filament\Resources\Pages\ListRecords; class ListPastors extends ListRecords { protected static string $resource = PastorResource::class; protected function getHeaderActions(): array { return [Actions\CreateAction::make()]; } }');
makeFile("app/Filament/Resources/PastorResource/Pages/CreatePastor.php", '<?php namespace App\Filament\Resources\PastorResource\Pages; use App\Filament\Resources\PastorResource; use Filament\Actions; use Filament\Resources\Pages\CreateRecord; class CreatePastor extends CreateRecord { protected static string $resource = PastorResource::class; }');
makeFile("app/Filament/Resources/PastorResource/Pages/EditPastor.php", '<?php namespace App\Filament\Resources\PastorResource\Pages; use App\Filament\Resources\PastorResource; use Filament\Actions; use Filament\Resources\Pages\EditRecord; class EditPastor extends EditRecord { protected static string $resource = PastorResource::class; protected function getHeaderActions(): array { return [Actions\DeleteAction::make()]; } }');

// 6. BphMemberResource
makeFile("app/Filament/Resources/BphMemberResource.php", '<?php
namespace App\Filament\Resources;
use App\Filament\Resources\BphMemberResource\Pages;
use App\Models\BphMember;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BphMemberResource extends Resource
{
    protected static ?string $model = BphMember::class;
    protected static ?string $navigationIcon = "heroicon-o-identification";
    protected static ?string $navigationGroup = "Kepemimpinan";
    protected static ?string $navigationLabel = "Anggota BPH";

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make("pastor_id")->relationship("pastor", "name")->required(),
                Forms\Components\TextInput::make("name")->required(),
                Forms\Components\Select::make("position")->options(["Pastor Paroki"=>"Pastor Paroki","Wakil Pastor"=>"Wakil Pastor","Ketua Dewan Pastoral"=>"Ketua Dewan Pastoral","Sekretaris"=>"Sekretaris","Bendahara I"=>"Bendahara I","Bendahara II"=>"Bendahara II","Koordinator Liturgi"=>"Koordinator Liturgi","Koordinator Sosial"=>"Koordinator Sosial"])->required(),
                Forms\Components\FileUpload::make("photo")->directory("bph")->image()->maxSize(2048),
                Forms\Components\TextInput::make("period_start")->numeric()->required(),
                Forms\Components\TextInput::make("period_end")->numeric()->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make("photo")->circular(),
                Tables\Columns\TextColumn::make("name")->searchable(),
                Tables\Columns\TextColumn::make("position")->badge(),
                Tables\Columns\TextColumn::make("pastor.name"),
                Tables\Columns\TextColumn::make("period_start"),
                Tables\Columns\TextColumn::make("period_end"),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            "index" => Pages\ListBphMembers::route("/"),
            "create" => Pages\CreateBphMember::route("/create"),
            "edit" => Pages\EditBphMember::route("/{record}/edit"),
        ];
    }
}
');
makeFile("app/Filament/Resources/BphMemberResource/Pages/ListBphMembers.php", '<?php namespace App\Filament\Resources\BphMemberResource\Pages; use App\Filament\Resources\BphMemberResource; use Filament\Actions; use Filament\Resources\Pages\ListRecords; class ListBphMembers extends ListRecords { protected static string $resource = BphMemberResource::class; protected function getHeaderActions(): array { return [Actions\CreateAction::make()]; } }');
makeFile("app/Filament/Resources/BphMemberResource/Pages/CreateBphMember.php", '<?php namespace App\Filament\Resources\BphMemberResource\Pages; use App\Filament\Resources\BphMemberResource; use Filament\Actions; use Filament\Resources\Pages\CreateRecord; class CreateBphMember extends CreateRecord { protected static string $resource = BphMemberResource::class; }');
makeFile("app/Filament/Resources/BphMemberResource/Pages/EditBphMember.php", '<?php namespace App\Filament\Resources\BphMemberResource\Pages; use App\Filament\Resources\BphMemberResource; use Filament\Actions; use Filament\Resources\Pages\EditRecord; class EditBphMember extends EditRecord { protected static string $resource = BphMemberResource::class; protected function getHeaderActions(): array { return [Actions\DeleteAction::make()]; } }');

// 7. BannerResource
makeFile("app/Filament/Resources/BannerResource.php", '<?php
namespace App\Filament\Resources;
use App\Filament\Resources\BannerResource\Pages;
use App\Models\Banner;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BannerResource extends Resource
{
    protected static ?string $model = Banner::class;
    protected static ?string $navigationIcon = "heroicon-o-photo";
    protected static ?string $navigationGroup = "Konten & Media";
    protected static ?string $navigationLabel = "Banner Kegiatan";
    protected static ?string $recordTitleAttribute = "title";

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make("title")->required()->placeholder("Selamat Paskah 2025"),
                Forms\Components\FileUpload::make("image_path")->directory("banners")->image()->maxSize(5120)->imageEditor()->required(),
                Forms\Components\Toggle::make("is_active")->default(true)->label("Tampilkan di Aplikasi"),
                Forms\Components\DatePicker::make("start_date")->nullable()->label("Mulai Tampil"),
                Forms\Components\DatePicker::make("end_date")->nullable()->label("Berhenti Tampil"),
                Forms\Components\TextInput::make("order")->numeric()->default(0)->hint("Urutan tampil di carousel, angka kecil = tampil lebih dulu"),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make("image_path")->height(50)->width(80),
                Tables\Columns\TextColumn::make("title")->searchable(),
                Tables\Columns\ToggleColumn::make("is_active"),
                Tables\Columns\TextColumn::make("start_date")->date(),
                Tables\Columns\TextColumn::make("end_date")->date(),
                Tables\Columns\TextColumn::make("order")->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            "index" => Pages\ListBanners::route("/"),
            "create" => Pages\CreateBanner::route("/create"),
            "edit" => Pages\EditBanner::route("/{record}/edit"),
        ];
    }
}
');
makeFile("app/Filament/Resources/BannerResource/Pages/ListBanners.php", '<?php namespace App\Filament\Resources\BannerResource\Pages; use App\Filament\Resources\BannerResource; use Filament\Actions; use Filament\Resources\Pages\ListRecords; class ListBanners extends ListRecords { protected static string $resource = BannerResource::class; protected function getHeaderActions(): array { return [Actions\CreateAction::make()]; } }');
makeFile("app/Filament/Resources/BannerResource/Pages/CreateBanner.php", '<?php namespace App\Filament\Resources\BannerResource\Pages; use App\Filament\Resources\BannerResource; use Filament\Actions; use Filament\Resources\Pages\CreateRecord; class CreateBanner extends CreateRecord { protected static string $resource = BannerResource::class; }');
makeFile("app/Filament/Resources/BannerResource/Pages/EditBanner.php", '<?php namespace App\Filament\Resources\BannerResource\Pages; use App\Filament\Resources\BannerResource; use Filament\Actions; use Filament\Resources\Pages\EditRecord; class EditBanner extends EditRecord { protected static string $resource = BannerResource::class; protected function getHeaderActions(): array { return [Actions\DeleteAction::make()]; } }');

// 8. IntensiMisaResource
makeFile("app/Filament/Resources/IntensiMisaResource.php", '<?php
namespace App\Filament\Resources;
use App\Filament\Resources\IntensiMisaResource\Pages;
use App\Models\IntensiMisa;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Artisan;

class IntensiMisaResource extends Resource
{
    protected static ?string $model = IntensiMisa::class;
    protected static ?string $navigationIcon = "heroicon-o-heart";
    protected static ?string $navigationGroup = "Keuangan & Intensi";
    protected static ?string $navigationLabel = "Intensi Misa";
    protected static ?string $recordTitleAttribute = "family_name";

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make("family_name")->required()->placeholder("Keluarga Jonathan Simamora"),
                Forms\Components\TextInput::make("amount")->numeric()->nullable()->prefix("Rp")->placeholder("500000"),
                Forms\Components\Textarea::make("description")->required()->rows(3)->placeholder("Intensi syukur atas..."),
                Forms\Components\DatePicker::make("week_date")->required()->default(now()->startOfWeek())->hint("Tanggal Senin dari minggu intensi ini"),
                Forms\Components\Toggle::make("is_archived")->default(false)->label("Sudah Diarsipkan"),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make("family_name")->searchable(),
                Tables\Columns\TextColumn::make("amount")->money("IDR"),
                Tables\Columns\TextColumn::make("description")->limit(60),
                Tables\Columns\TextColumn::make("week_date")->badge()->date("d M Y")->label("Minggu"),
                Tables\Columns\IconColumn::make("is_archived")->boolean(),
            ])
            ->filters([
                Tables\Filters\Filter::make("is_archived")->query(fn ($query) => $query->where("is_archived", true)),
                Tables\Filters\Filter::make("week_date")->form([Forms\Components\DatePicker::make("week_date")])->query(function ($query, array $data) { return $data["week_date"] ? $query->whereDate("week_date", $data["week_date"]) : $query; }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            "index" => Pages\ListIntensiMisas::route("/"),
            "create" => Pages\CreateIntensiMisa::route("/create"),
            "edit" => Pages\EditIntensiMisa::route("/{record}/edit"),
        ];
    }
}
');
makeFile("app/Filament/Resources/IntensiMisaResource/Pages/ListIntensiMisas.php", '<?php namespace App\Filament\Resources\IntensiMisaResource\Pages; use App\Filament\Resources\IntensiMisaResource; use Filament\Actions; use Filament\Resources\Pages\ListRecords; use Illuminate\Support\Facades\Artisan; use Filament\Notifications\Notification; class ListIntensiMisas extends ListRecords { protected static string $resource = IntensiMisaResource::class; protected function getHeaderActions(): array { return [Actions\Action::make("reset_minggu")->label("Reset Minggu Ini")->color("danger")->requiresConfirmation()->action(function() { Artisan::call("intensi:reset"); Notification::make()->title("Intensi berhasil diarsipkan")->success()->send(); }), Actions\CreateAction::make()]; } }');
makeFile("app/Filament/Resources/IntensiMisaResource/Pages/CreateIntensiMisa.php", '<?php namespace App\Filament\Resources\IntensiMisaResource\Pages; use App\Filament\Resources\IntensiMisaResource; use Filament\Actions; use Filament\Resources\Pages\CreateRecord; class CreateIntensiMisa extends CreateRecord { protected static string $resource = IntensiMisaResource::class; }');
makeFile("app/Filament/Resources/IntensiMisaResource/Pages/EditIntensiMisa.php", '<?php namespace App\Filament\Resources\IntensiMisaResource\Pages; use App\Filament\Resources\IntensiMisaResource; use Filament\Actions; use Filament\Resources\Pages\EditRecord; class EditIntensiMisa extends EditRecord { protected static string $resource = IntensiMisaResource::class; protected function getHeaderActions(): array { return [Actions\DeleteAction::make()]; } }');

// 9. SongNumberResource
makeFile("app/Filament/Resources/SongNumberResource.php", '<?php
namespace App\Filament\Resources;
use App\Filament\Resources\SongNumberResource\Pages;
use App\Models\SongNumber;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SongNumberResource extends Resource
{
    protected static ?string $model = SongNumber::class;
    protected static ?string $navigationIcon = "heroicon-o-musical-note";
    protected static ?string $navigationGroup = "Perangkat IoT";
    protected static ?string $navigationLabel = "Nomor Lagu";

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make("song_number")->required()->maxLength(4)->numeric()->placeholder("2100"),
                Forms\Components\TextInput::make("device_id")->nullable()->placeholder("display-01"),
                Forms\Components\Select::make("status")->options(["pending"=>"pending", "sent"=>"sent", "failed"=>"failed"])->default("pending"),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make("song_number")->badge()->size(Tables\Columns\TextColumn\TextColumnSize::Large),
                Tables\Columns\TextColumn::make("status")->badge()->color(fn ($state) => match($state){"pending"=>"warning","sent"=>"success","failed"=>"danger",default=>"primary"}),
                Tables\Columns\TextColumn::make("device_id"),
                Tables\Columns\TextColumn::make("sent_at")->dateTime()->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            "index" => Pages\ListSongNumbers::route("/"),
            "create" => Pages\CreateSongNumber::route("/create"),
            "edit" => Pages\EditSongNumber::route("/{record}/edit"),
        ];
    }
}
');
makeFile("app/Filament/Resources/SongNumberResource/Pages/ListSongNumbers.php", '<?php namespace App\Filament\Resources\SongNumberResource\Pages; use App\Filament\Resources\SongNumberResource; use Filament\Actions; use Filament\Resources\Pages\ListRecords; use App\Filament\Widgets\LatestSongWidget; class ListSongNumbers extends ListRecords { protected static string $resource = SongNumberResource::class; protected function getHeaderActions(): array { return [Actions\CreateAction::make()]; } protected function getHeaderWidgets(): array { return [LatestSongWidget::class]; } }');
makeFile("app/Filament/Resources/SongNumberResource/Pages/CreateSongNumber.php", '<?php namespace App\Filament\Resources\SongNumberResource\Pages; use App\Filament\Resources\SongNumberResource; use Filament\Actions; use Filament\Resources\Pages\CreateRecord; class CreateSongNumber extends CreateRecord { protected static string $resource = SongNumberResource::class; }');
makeFile("app/Filament/Resources/SongNumberResource/Pages/EditSongNumber.php", '<?php namespace App\Filament\Resources\SongNumberResource\Pages; use App\Filament\Resources\SongNumberResource; use Filament\Actions; use Filament\Resources\Pages\EditRecord; class EditSongNumber extends EditRecord { protected static string $resource = SongNumberResource::class; protected function getHeaderActions(): array { return [Actions\DeleteAction::make()]; } }');

// ==========================================
// LANGKAH 4: WIDGETS
// ==========================================
makeFile("app/Filament/Widgets/StatsOverview.php", '<?php
namespace App\Filament\Widgets;
use App\Models\Lingkungan;
use App\Models\KepalaKeluarga;
use App\Models\IntensiMisa;
use App\Models\JadwalIbadah;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make("Total Lingkungan", Lingkungan::count())
                ->icon("heroicon-o-home"),
            Stat::make("Total Kepala Keluarga", KepalaKeluarga::count())
                ->icon("heroicon-o-users"),
            Stat::make("Intensi Misa Minggu Ini", IntensiMisa::currentWeek()->count())
                ->icon("heroicon-o-heart"),
            Stat::make("Jadwal Ibadah Hari Ini", JadwalIbadah::where("day_of_week", array_values(["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"])[date("w")])->count())
                ->icon("heroicon-o-clock"),
        ];
    }
}
');

makeFile("app/Filament/Widgets/LatestIntensiMisa.php", '<?php
namespace App\Filament\Widgets;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Models\IntensiMisa;

class LatestIntensiMisa extends BaseWidget
{
    protected int | string | array $columnSpan = "full";
    public function table(Table $table): Table
    {
        return $table
            ->query(IntensiMisa::currentWeek()->latest()->limit(5))
            ->columns([
                Tables\Columns\TextColumn::make("family_name")->label("Keluarga"),
                Tables\Columns\TextColumn::make("amount")->money("IDR")->label("Nominal"),
                Tables\Columns\TextColumn::make("description")->limit(50)->label("Keterangan"),
            ]);
    }
}
');

makeFile("app/Filament/Widgets/JadwalHariIni.php", '<?php
namespace App\Filament\Widgets;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Models\JadwalIbadah;

class JadwalHariIni extends BaseWidget
{
    protected int | string | array $columnSpan = "full";
    public function table(Table $table): Table
    {
        return $table
            ->query(JadwalIbadah::where("day_of_week", array_values(["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"])[date("w")]))
            ->columns([
                Tables\Columns\TextColumn::make("time")->time("H:i")->label("Waktu"),
                Tables\Columns\TextColumn::make("type")->badge()->label("Jenis Ibadah"),
                Tables\Columns\TextColumn::make("celebrant")->label("Selebran"),
            ]);
    }
}
');

makeFile("app/Filament/Widgets/BannerAktif.php", '<?php
namespace App\Filament\Widgets;
use App\Models\Banner;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class BannerAktif extends BaseWidget
{
    protected function getStats(): array
    {
        $count = Banner::active()->count();
        return [
            Stat::make("Banner Aktif", $count)
                ->color($count > 0 ? "success" : "danger")
                ->icon("heroicon-o-photo"),
        ];
    }
}
');

makeFile("app/Filament/Widgets/LatestSongWidget.php", '<?php
namespace App\Filament\Widgets;
use App\Models\SongNumber;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class LatestSongWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $latest = SongNumber::latest()->first();
        return [
            Stat::make("Nomor Lagu Terakhir", $latest ? $latest->song_number : "Belum Ada")
                ->description("Status: " . ($latest ? $latest->status : "-"))
                ->color("success")
                ->icon("heroicon-o-musical-note"),
        ];
    }
}
');

// ==========================================
// LANGKAH 5: CUSTOM PAGES
// ==========================================
makeFile("app/Filament/Pages/ProfilParoki.php", '<?php
namespace App\Filament\Pages;
use Filament\Pages\Page;
use App\Models\Pastor;

class ProfilParoki extends Page
{
    protected static ?string $navigationIcon = "heroicon-o-building-library";
    protected static string $view = "filament.pages.profil-paroki";
    protected static ?string $title = "Profil Paroki";

    public $pastorAktif;

    public function mount()
    {
        $this->pastorAktif = Pastor::active()->first();
    }
}
');

makeFile("resources/views/filament/pages/profil-paroki.blade.php", '
<x-filament-panels::page>
    <div class="space-y-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
            <h2 class="text-2xl font-bold mb-4">Paroki Santo Fidelis Parapat</h2>
            <p><strong>Keuskupan:</strong> Keuskupan Agung Medan</p>
            <p><strong>Alamat:</strong> Jl. Gereja, Parapat, Kec. Girsang Sipangan Bolon, Kabupaten Simalungun, Sumatera Utara</p>
            <p><strong>Email:</strong> sekretariat@ekatolik-parapat.id</p>
            <p><strong>Telepon:</strong> (0625) 41XXX</p>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
            <h3 class="text-xl font-bold mb-4">Pastor Paroki Saat Ini</h3>
            @if($pastorAktif)
                <div class="flex items-center space-x-4">
                    @if($pastorAktif->photo)
                        <img src="{{ Storage::url($pastorAktif->photo) }}" class="w-16 h-16 rounded-full object-cover">
                    @else
                        <div class="w-16 h-16 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                            <span class="text-gray-500 heroicon-o-user"></span>
                        </div>
                    @endif
                    <div>
                        <p class="font-bold text-lg">{{ $pastorAktif->name }}</p>
                        <p class="text-sm text-gray-500">Mulai bertugas: {{ $pastorAktif->period_start }}</p>
                    </div>
                </div>
            @else
                <p class="text-gray-500">Belum ada data pastor aktif yang diset.</p>
            @endif
        </div>
    </div>
</x-filament-panels::page>
');

echo "Filament setup generated successfully.\n";
