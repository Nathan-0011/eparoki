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

// 1. .env
makeFile('.env', 'APP_NAME=eKatolik
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_TIMEZONE=Asia/Jakarta
APP_URL=http://localhost:8000

APP_LOCALE=id
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=id_ID
APP_MAINTENANCE_DRIVER=file
BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=eparoki_db
DB_USERNAME=root
DB_PASSWORD=

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync

CACHE_STORE=database
CACHE_PREFIX=

MEMCACHED_HOST=127.0.0.1

REDIS_CLIENT=phpredis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="hello@ekatolik-parapat.id"
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

GOOGLE_CLIENT_ID=your_google_client_id_here
GOOGLE_CLIENT_SECRET=your_google_client_secret_here
GOOGLE_REDIRECT_URI=http://localhost:8000/api/auth/google/callback

SANCTUM_STATEFUL_DOMAINS=localhost,127.0.0.1

VITE_APP_NAME="${APP_NAME}"
');

// 2. MIGRATIONS
makeFile('database/migrations/0001_01_01_000000_create_users_table.php', '<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create("users", function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("email")->unique();
            $table->string("password");
            $table->enum("role", ["superadmin", "admin"])->default("admin");
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create("password_reset_tokens", function (Blueprint $table) {
            $table->string("email")->primary();
            $table->string("token");
            $table->timestamp("created_at")->nullable();
        });

        Schema::create("sessions", function (Blueprint $table) {
            $table->string("id")->primary();
            $table->foreignId("user_id")->nullable()->index();
            $table->string("ip_address", 45)->nullable();
            $table->text("user_agent")->nullable();
            $table->longText("payload");
            $table->integer("last_activity")->index();
        });
    }

    public function down(): void {
        Schema::dropIfExists("users");
        Schema::dropIfExists("password_reset_tokens");
        Schema::dropIfExists("sessions");
    }
};');

makeFile('database/migrations/2025_01_01_000001_create_lingkungan_table.php', '<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up() {
        Schema::create("lingkungan", function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("patron_saint")->nullable();
            $table->text("description")->nullable();
            $table->string("photo")->nullable();
            $table->timestamps();
        });
    }
    public function down() { Schema::dropIfExists("lingkungan"); }
};');

makeFile('database/migrations/2025_01_01_000002_create_umat_users_table.php', '<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up() {
        Schema::create("umat_users", function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("email")->unique();
            $table->string("google_id")->unique()->nullable();
            $table->string("avatar")->nullable();
            $table->foreignId("lingkungan_id")->nullable()->constrained("lingkungan")->nullOnDelete();
            $table->timestamps();
        });
    }
    public function down() { Schema::dropIfExists("umat_users"); }
};');

makeFile('database/migrations/2025_01_01_000003_create_kepala_keluarga_table.php', '<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up() {
        Schema::create("kepala_keluarga", function (Blueprint $table) {
            $table->id();
            $table->foreignId("lingkungan_id")->constrained("lingkungan")->cascadeOnDelete();
            $table->string("nama_kk");
            $table->text("alamat")->nullable();
            $table->integer("jumlah_anggota")->default(1);
            $table->string("no_telp")->nullable();
            $table->timestamps();
        });
    }
    public function down() { Schema::dropIfExists("kepala_keluarga"); }
};');

makeFile('database/migrations/2025_01_01_000004_create_liturgical_calendars_table.php', '<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up() {
        Schema::create("liturgical_calendars", function (Blueprint $table) {
            $table->id();
            $table->integer("year");
            $table->tinyInteger("month");
            $table->tinyInteger("week_number");
            $table->string("title");
            $table->text("description")->nullable();
            $table->timestamps();
            $table->unique(["year", "month", "week_number"], "cal_ymw_unique");
        });
    }
    public function down() { Schema::dropIfExists("liturgical_calendars"); }
};');

makeFile('database/migrations/2025_01_01_000005_create_jadwal_ibadah_table.php', '<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up() {
        Schema::create("jadwal_ibadah", function (Blueprint $table) {
            $table->id();
            $table->foreignId("calendar_id")->constrained("liturgical_calendars")->cascadeOnDelete();
            $table->enum("day_of_week", ["Senin","Selasa","Rabu","Kamis","Jumat","Sabtu","Minggu"]);
            $table->time("time");
            $table->string("type");
            $table->string("celebrant")->nullable();
            $table->string("location")->default("Gereja Santo Fidelis Parapat");
            $table->timestamps();
        });
    }
    public function down() { Schema::dropIfExists("jadwal_ibadah"); }
};');

makeFile('database/migrations/2025_01_01_000006_create_pastors_table.php', '<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up() {
        Schema::create("pastors", function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("photo")->nullable();
            $table->year("period_start");
            $table->year("period_end")->nullable();
            $table->text("biography")->nullable();
            $table->boolean("is_active")->default(false);
            $table->timestamps();
        });
    }
    public function down() { Schema::dropIfExists("pastors"); }
};');

makeFile('database/migrations/2025_01_01_000007_create_bph_members_table.php', '<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up() {
        Schema::create("bph_members", function (Blueprint $table) {
            $table->id();
            $table->foreignId("pastor_id")->constrained("pastors")->cascadeOnDelete();
            $table->string("name");
            $table->string("position");
            $table->string("photo")->nullable();
            $table->year("period_start");
            $table->year("period_end")->nullable();
            $table->timestamps();
        });
    }
    public function down() { Schema::dropIfExists("bph_members"); }
};');

makeFile('database/migrations/2025_01_01_000008_create_banners_table.php', '<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up() {
        Schema::create("banners", function (Blueprint $table) {
            $table->id();
            $table->string("title");
            $table->string("image_path");
            $table->boolean("is_active")->default(true);
            $table->date("start_date")->nullable();
            $table->date("end_date")->nullable();
            $table->integer("order")->default(0);
            $table->timestamps();
        });
    }
    public function down() { Schema::dropIfExists("banners"); }
};');

makeFile('database/migrations/2025_01_01_000009_create_intensi_misa_table.php', '<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up() {
        Schema::create("intensi_misa", function (Blueprint $table) {
            $table->id();
            $table->string("family_name");
            $table->bigInteger("amount")->nullable();
            $table->text("description");
            $table->date("week_date");
            $table->boolean("is_archived")->default(false);
            $table->timestamps();
        });
    }
    public function down() { Schema::dropIfExists("intensi_misa"); }
};');

makeFile('database/migrations/2025_01_01_000010_create_song_numbers_table.php', '<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up() {
        Schema::create("song_numbers", function (Blueprint $table) {
            $table->id();
            $table->string("song_number", 4);
            $table->timestamp("sent_at")->useCurrent();
            $table->string("device_id")->nullable();
            $table->enum("status", ["pending","sent","failed"])->default("pending");
            $table->timestamps();
        });
    }
    public function down() { Schema::dropIfExists("song_numbers"); }
};');

// 3. MODELS
makeFile("app/Models/User.php", '<?php
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable {
    use HasApiTokens, Notifiable, HasRoles;
    protected $fillable = ["name", "email", "password", "role"];
    protected $hidden = ["password", "remember_token"];
    protected $casts = ["password" => "hashed"];
    public function scopeAdmin($query) { return $query->where("role", "admin")->orWhere("role", "superadmin"); }
}');

makeFile("app/Models/UmatUser.php", '<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

class UmatUser extends Authenticatable {
    use HasApiTokens;
    protected $fillable = ["name", "email", "google_id", "avatar", "lingkungan_id"];
    public function lingkungan() { return $this->belongsTo(Lingkungan::class); }
}');

makeFile("app/Models/Lingkungan.php", '<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Lingkungan extends Model {
    protected $table = "lingkungan";
    protected $fillable = ["name", "patron_saint", "description", "photo"];
    public function kepalaKeluarga() { return $this->hasMany(KepalaKeluarga::class); }
}');

makeFile("app/Models/KepalaKeluarga.php", '<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class KepalaKeluarga extends Model {
    protected $table = "kepala_keluarga";
    protected $fillable = ["lingkungan_id", "nama_kk", "alamat", "jumlah_anggota", "no_telp"];
    public function lingkungan() { return $this->belongsTo(Lingkungan::class); }
}');

makeFile("app/Models/LiturgicalCalendar.php", '<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class LiturgicalCalendar extends Model {
    protected $fillable = ["year", "month", "week_number", "title", "description"];
    public function jadwalIbadah() { return $this->hasMany(JadwalIbadah::class, "calendar_id"); }
    public function scopeByYearMonth($query, $year, $month) {
        return $query->where("year", $year)->where("month", $month);
    }
}');

makeFile("app/Models/JadwalIbadah.php", '<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class JadwalIbadah extends Model {
    protected $table = "jadwal_ibadah";
    protected $fillable = ["calendar_id", "day_of_week", "time", "type", "celebrant", "location"];
    public function calendar() { return $this->belongsTo(LiturgicalCalendar::class, "calendar_id"); }
}');

makeFile("app/Models/Pastor.php", '<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Pastor extends Model {
    protected $fillable = ["name", "photo", "period_start", "period_end", "biography", "is_active"];
    protected $casts = ["is_active" => "boolean"];
    public function bphMembers() { return $this->hasMany(BphMember::class); }
    public function scopeActive($query) { return $query->where("is_active", true); }
}');

makeFile("app/Models/BphMember.php", '<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class BphMember extends Model {
    protected $fillable = ["pastor_id", "name", "position", "photo", "period_start", "period_end"];
    public function pastor() { return $this->belongsTo(Pastor::class); }
}');

makeFile("app/Models/Banner.php", '<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Banner extends Model {
    protected $fillable = ["title", "image_path", "is_active", "start_date", "end_date", "order"];
    protected $casts = ["is_active" => "boolean", "start_date" => "date", "end_date" => "date"];
    public function scopeActive($query) {
        return $query->where("is_active", true)
            ->where(function($q) {
                $q->whereNull("start_date")->orWhere("start_date", "<=", now());
            })
            ->where(function($q) {
                $q->whereNull("end_date")->orWhere("end_date", ">=", now());
            });
    }
}');

makeFile("app/Models/IntensiMisa.php", '<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class IntensiMisa extends Model {
    protected $table = "intensi_misa";
    protected $fillable = ["family_name", "amount", "description", "week_date", "is_archived"];
    protected $casts = ["is_archived" => "boolean", "week_date" => "date"];
    public function scopeCurrentWeek($query) { return $query->where("is_archived", false); }
}');

makeFile("app/Models/SongNumber.php", '<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class SongNumber extends Model {
    protected $fillable = ["song_number", "sent_at", "device_id", "status"];
    protected $casts = ["sent_at" => "datetime"];
    public function scopeLatest($query) { return $query->orderBy("sent_at", "desc"); }
}');


// 4. API RESOURCES
$resources = ["LiturgicalCalendarResource", "JadwalIbadahResource", "LingkunganResource", "KepalaKeluargaResource", "PastorResource", "BphMemberResource", "BannerResource", "IntensiMisaResource", "SongNumberResource"];
foreach ($resources as $res) {
    makeFile("app/Http/Resources/{$res}.php", "<?php
namespace App\Http\Resources;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class {$res} extends JsonResource {
    public function toArray(Request \$request): array {
        return parent::toArray(\$request);
    }
}");
}

// 5. HELPER
makeFile("app/Helpers/ApiResponse.php", '<?php
namespace App\Helpers;

class ApiResponse {
    public static function success($data = null, $message = "Data berhasil diambil", $meta = null, $code = 200) {
        $response = ["success" => true, "message" => $message, "data" => $data];
        if ($meta) { $response["meta"] = $meta; }
        return response()->json($response, $code);
    }
    public static function error($message = "Terjadi kesalahan", $errors = null, $code = 400) {
        $response = ["success" => false, "message" => $message];
        if ($errors) { $response["errors"] = $errors; }
        return response()->json($response, $code);
    }
}');

// 6. CONTROLLERS
makeFile("app/Http/Controllers/Api/AuthController.php", '<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UmatUser;
use App\Helpers\ApiResponse;
use Laravel\Socialite\Facades\Socialite;
use Exception;

class AuthController extends Controller {
    public function googleLogin(Request $request) {
        try {
            $request->validate(["token" => "required"]);
            // Untuk validasi token dari mobile, gunakan library google-auth atau request manual
            // Contoh simpel jika menggunakan socialite (biasanya untuk web):
            // $googleUser = Socialite::driver("google")->userFromToken($request->token);
            
            // Simulasi data untuk keperluan test:
            $email = "umat@example.com";
            $name = "Umat User";
            $google_id = "123456789";
            
            $user = UmatUser::updateOrCreate(
                ["email" => $email],
                ["name" => $name, "google_id" => $google_id, "avatar" => ""]
            );
            $token = $user->createToken("umat-token")->plainTextToken;
            return ApiResponse::success(["user" => $user, "token" => $token], "Login sukses");
        } catch (Exception $e) {
            return ApiResponse::error("Login gagal: " . $e->getMessage(), null, 500);
        }
    }
    public function logout(Request $request) {
        try {
            $request->user()->currentAccessToken()->delete();
            return ApiResponse::success(null, "Logout berhasil");
        } catch (Exception $e) {
            return ApiResponse::error("Logout gagal", null, 500);
        }
    }
}');

makeFile("app/Http/Controllers/Api/CalendarController.php", '<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\LiturgicalCalendar;
use App\Http\Resources\LiturgicalCalendarResource;
use App\Helpers\ApiResponse;
use Exception;

class CalendarController extends Controller {
    public function index($year, $month) {
        try {
            $data = LiturgicalCalendar::byYearMonth($year, $month)->get();
            return ApiResponse::success(LiturgicalCalendarResource::collection($data), "Data diambil", ["total" => $data->count()]);
        } catch (Exception $e) { return ApiResponse::error($e->getMessage(), null, 500); }
    }
    public function show($year, $month, $week) {
        try {
            $data = LiturgicalCalendar::where("year", $year)->where("month", $month)->where("week_number", $week)->with("jadwalIbadah")->first();
            if (!$data) return ApiResponse::error("Tidak ditemukan", null, 404);
            return ApiResponse::success(new LiturgicalCalendarResource($data));
        } catch (Exception $e) { return ApiResponse::error($e->getMessage(), null, 500); }
    }
}');

makeFile("app/Http/Controllers/Api/JadwalIbadahController.php", '<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\JadwalIbadah;
use App\Http\Resources\JadwalIbadahResource;
use App\Helpers\ApiResponse;
use Exception;

class JadwalIbadahController extends Controller {
    public function index($calendar_id) {
        try {
            $data = JadwalIbadah::where("calendar_id", $calendar_id)->get();
            return ApiResponse::success(JadwalIbadahResource::collection($data), "Data diambil", ["total" => $data->count()]);
        } catch (Exception $e) { return ApiResponse::error($e->getMessage(), null, 500); }
    }
}');

makeFile("app/Http/Controllers/Api/LingkunganController.php", '<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Lingkungan;
use App\Models\KepalaKeluarga;
use App\Http\Resources\LingkunganResource;
use App\Http\Resources\KepalaKeluargaResource;
use App\Helpers\ApiResponse;
use Exception;

class LingkunganController extends Controller {
    public function index() {
        try {
            $data = Lingkungan::all();
            return ApiResponse::success(LingkunganResource::collection($data), "Data diambil", ["total" => $data->count()]);
        } catch (Exception $e) { return ApiResponse::error($e->getMessage(), null, 500); }
    }
    public function show($id) {
        try {
            $data = Lingkungan::find($id);
            if (!$data) return ApiResponse::error("Tidak ditemukan", null, 404);
            return ApiResponse::success(new LingkunganResource($data));
        } catch (Exception $e) { return ApiResponse::error($e->getMessage(), null, 500); }
    }
    public function kepalakeluarga($id) {
        try {
            $data = KepalaKeluarga::where("lingkungan_id", $id)->get();
            return ApiResponse::success(KepalaKeluargaResource::collection($data), "Data diambil", ["total" => $data->count()]);
        } catch (Exception $e) { return ApiResponse::error($e->getMessage(), null, 500); }
    }
}');

makeFile("app/Http/Controllers/Api/PastorController.php", '<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Pastor;
use App\Http\Resources\PastorResource;
use App\Helpers\ApiResponse;
use Exception;

class PastorController extends Controller {
    public function index() {
        try {
            $data = Pastor::orderBy("period_start", "desc")->get();
            return ApiResponse::success(PastorResource::collection($data), "Data diambil", ["total" => $data->count()]);
        } catch (Exception $e) { return ApiResponse::error($e->getMessage(), null, 500); }
    }
    public function show($id) {
        try {
            $data = Pastor::with("bphMembers")->find($id);
            if (!$data) return ApiResponse::error("Tidak ditemukan", null, 404);
            return ApiResponse::success(new PastorResource($data));
        } catch (Exception $e) { return ApiResponse::error($e->getMessage(), null, 500); }
    }
    public function active() {
        try {
            $data = Pastor::active()->with("bphMembers")->first();
            if (!$data) return ApiResponse::error("Tidak ada pastor aktif", null, 404);
            return ApiResponse::success(new PastorResource($data));
        } catch (Exception $e) { return ApiResponse::error($e->getMessage(), null, 500); }
    }
}');

makeFile("app/Http/Controllers/Api/BannerController.php", '<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Http\Resources\BannerResource;
use App\Helpers\ApiResponse;
use Exception;

class BannerController extends Controller {
    public function index() {
        try {
            $data = Banner::active()->orderBy("order")->get();
            return ApiResponse::success(BannerResource::collection($data), "Data diambil", ["total" => $data->count()]);
        } catch (Exception $e) { return ApiResponse::error($e->getMessage(), null, 500); }
    }
}');

makeFile("app/Http/Controllers/Api/IntensiMisaController.php", '<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\IntensiMisa;
use App\Http\Resources\IntensiMisaResource;
use App\Helpers\ApiResponse;
use Exception;

class IntensiMisaController extends Controller {
    public function index() {
        try {
            $data = IntensiMisa::currentWeek()->get();
            return ApiResponse::success(IntensiMisaResource::collection($data), "Data diambil", ["total" => $data->count()]);
        } catch (Exception $e) { return ApiResponse::error($e->getMessage(), null, 500); }
    }
}');

makeFile("app/Http/Controllers/Api/SongNumberController.php", '<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SongNumber;
use App\Http\Resources\SongNumberResource;
use App\Helpers\ApiResponse;
use Exception;

class SongNumberController extends Controller {
    public function latest() {
        try {
            $data = SongNumber::latest()->first();
            if (!$data) return ApiResponse::error("Belum ada lagu", null, 404);
            return ApiResponse::success(new SongNumberResource($data));
        } catch (Exception $e) { return ApiResponse::error($e->getMessage(), null, 500); }
    }
    public function store(Request $request) {
        try {
            $request->validate(["song_number" => "required|string|max:4"]);
            $song = SongNumber::create([
                "song_number" => $request->song_number,
                "sent_at" => now(),
                "status" => "sent"
            ]);
            // (Logika publish ke MQTT bisa ditambahkan di sini)
            
            return ApiResponse::success(new SongNumberResource($song), "Lagu dikirim");
        } catch (Exception $e) { return ApiResponse::error($e->getMessage(), null, 500); }
    }
}');

// 7. ROUTES
makeFile("routes/api.php", '<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CalendarController;
use App\Http\Controllers\Api\JadwalIbadahController;
use App\Http\Controllers\Api\LingkunganController;
use App\Http\Controllers\Api\PastorController;
use App\Http\Controllers\Api\BannerController;
use App\Http\Controllers\Api\IntensiMisaController;
use App\Http\Controllers\Api\SongNumberController;

// Public routes
Route::get("/banners", [BannerController::class, "index"]);
Route::get("/calendar/{year}/{month}", [CalendarController::class, "index"]);
Route::get("/calendar/{year}/{month}/{week}", [CalendarController::class, "show"]);
Route::get("/jadwal/{calendar_id}", [JadwalIbadahController::class, "index"]);
Route::get("/lingkungan", [LingkunganController::class, "index"]);
Route::get("/lingkungan/{id}", [LingkunganController::class, "show"]);
Route::get("/lingkungan/{id}/kk", [LingkunganController::class, "kepalakeluarga"]);
Route::get("/pastors", [PastorController::class, "index"]);
Route::get("/pastors/active", [PastorController::class, "active"]);
Route::get("/pastors/{id}", [PastorController::class, "show"]);
Route::get("/intensi-misa", [IntensiMisaController::class, "index"]);
Route::get("/song-number/latest", [SongNumberController::class, "latest"]);

// Auth public
Route::post("/auth/google", [AuthController::class, "googleLogin"]);

// Protected
Route::middleware("auth:sanctum")->group(function () {
    Route::post("/auth/logout", [AuthController::class, "logout"]);
    Route::post("/song-number", [SongNumberController::class, "store"]);
});
');

// 8. COMMAND
makeFile("app/Console/Commands/ResetIntensiMisa.php", '<?php
namespace App\Console\Commands;
use Illuminate\Console\Command;
use App\Models\IntensiMisa;

class ResetIntensiMisa extends Command {
    protected $signature = "intensi:reset";
    protected $description = "Reset intensi misa minggu lalu (set is_archived=true)";
    public function handle() {
        $lastWeekMonday = now()->subWeek()->startOfWeek()->toDateString();
        $count = IntensiMisa::where("week_date", "<=", $lastWeekMonday)
            ->where("is_archived", false)
            ->update(["is_archived" => true]);
        $this->info("Berhasil mengarsipkan {$count} intensi misa.");
    }
}');

// 9. SCHEDULER (routes/console.php)
makeFile("routes/console.php", '<?php
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command("inspire", function () {
    $this->comment(Inspiring::quote());
})->purpose("Display an inspiring quote")->hourly();

Schedule::command("intensi:reset")->weeklyOn(1, "00:00")->timezone("Asia/Jakarta");
');

// 10. SEEDERS
makeFile("database/seeders/DatabaseSeeder.php", '<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {
    public function run(): void {
        $this->call([
            UserSeeder::class,
            LingkunganSeeder::class,
            KepalaKeluargaSeeder::class,
            PastorSeeder::class,
            LiturgicalCalendarSeeder::class,
            JadwalIbadahSeeder::class,
            BannerSeeder::class,
            IntensiMisaSeeder::class
        ]);
    }
}');

makeFile("database/seeders/UserSeeder.php", '<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
class UserSeeder extends Seeder {
    public function run() {
        User::create(["name" => "Administrator", "email" => "admin@eparoki.id", "password" => Hash::make("admin123"), "role" => "superadmin"]);
    }
}');

makeFile("database/seeders/LingkunganSeeder.php", '<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Lingkungan;
class LingkunganSeeder extends Seeder {
    public function run() {
        $santos = ["Santo Antonius", "Santo Petrus", "Santa Maria", "Santo Yosef", "Santo Mikael", "Santo Yohanes", "Santo Paulus", "Santa Teresia", "Santo Fransiskus", "Santo Stefanus"];
        foreach($santos as $s) {
            Lingkungan::create(["name" => "Lingkungan ".$s, "patron_saint" => $s]);
        }
    }
}');

makeFile("database/seeders/KepalaKeluargaSeeder.php", '<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\KepalaKeluarga;
use App\Models\Lingkungan;
class KepalaKeluargaSeeder extends Seeder {
    public function run() {
        $lingkungans = Lingkungan::all();
        $margas = ["Siahaan", "Situmorang", "Simamora", "Hutapea", "Nainggolan", "Sinaga", "Silalahi", "Manurung", "Pasaribu", "Sitorus"];
        $namas = ["Marihot", "Poltak", "Budi", "Johannes", "Parlindungan", "Sahat", "Togu", "Binsar", "Halomoan", "Togar"];
        
        foreach($lingkungans as $ling) {
            for($i=0; $i<10; $i++) {
                KepalaKeluarga::create([
                    "lingkungan_id" => $ling->id,
                    "nama_kk" => "Keluarga " . $namas[array_rand($namas)] . " " . $margas[array_rand($margas)],
                    "jumlah_anggota" => rand(2, 6)
                ]);
            }
        }
    }
}');

makeFile("database/seeders/PastorSeeder.php", '<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Pastor;
use App\Models\BphMember;
class PastorSeeder extends Seeder {
    public function run() {
        Pastor::create(["name" => "Rm. Antonius Sibuea, OFMCap", "period_start" => 2010, "period_end" => 2015, "is_active" => false]);
        Pastor::create(["name" => "Rm. Benediktus Manurung, Pr", "period_start" => 2015, "period_end" => 2021, "is_active" => false]);
        $p3 = Pastor::create(["name" => "Rm. Fidelis Tambunan, OFMCap", "period_start" => 2021, "is_active" => true]);
        
        BphMember::create(["pastor_id" => $p3->id, "name" => "Rm. Fidelis Tambunan", "position" => "Pastor Paroki", "period_start" => 2021]);
        BphMember::create(["pastor_id" => $p3->id, "name" => "P. Yohanes", "position" => "Wakil Pastor", "period_start" => 2021]);
        BphMember::create(["pastor_id" => $p3->id, "name" => "Bpk. Poltak", "position" => "Ketua Dewan Pastoral", "period_start" => 2021]);
    }
}');

makeFile("database/seeders/LiturgicalCalendarSeeder.php", '<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\LiturgicalCalendar;
class LiturgicalCalendarSeeder extends Seeder {
    public function run() {
        for($m=1; $m<=12; $m++) {
            for($w=1; $w<=4; $w++) {
                LiturgicalCalendar::create([
                    "year" => 2025, "month" => $m, "week_number" => $w,
                    "title" => "Minggu Biasa Ke-" . (($m-1)*4 + $w)
                ]);
            }
        }
    }
}');

makeFile("database/seeders/JadwalIbadahSeeder.php", '<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\JadwalIbadah;
use App\Models\LiturgicalCalendar;
class JadwalIbadahSeeder extends Seeder {
    public function run() {
        $cal = LiturgicalCalendar::first();
        if($cal) {
            JadwalIbadah::create(["calendar_id" => $cal->id, "day_of_week" => "Senin", "time" => "06:00", "type" => "Misa Harian"]);
            JadwalIbadah::create(["calendar_id" => $cal->id, "day_of_week" => "Sabtu", "time" => "17:00", "type" => "Misa Vigili"]);
            JadwalIbadah::create(["calendar_id" => $cal->id, "day_of_week" => "Minggu", "time" => "07:00", "type" => "Misa I"]);
            JadwalIbadah::create(["calendar_id" => $cal->id, "day_of_week" => "Minggu", "time" => "09:30", "type" => "Misa II"]);
        }
    }
}');

makeFile("database/seeders/BannerSeeder.php", '<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Banner;
class BannerSeeder extends Seeder {
    public function run() {
        Banner::create(["title" => "Paskah 2025", "image_path" => "banners/placeholder.jpg", "start_date" => now()->subDays(5), "end_date" => now()->addDays(10)]);
        Banner::create(["title" => "Bulan Maria", "image_path" => "banners/placeholder.jpg", "start_date" => now()->subDays(2), "end_date" => now()->addDays(20)]);
        Banner::create(["title" => "Kegiatan OMK", "image_path" => "banners/placeholder.jpg"]);
    }
}');

makeFile("database/seeders/IntensiMisaSeeder.php", '<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\IntensiMisa;
class IntensiMisaSeeder extends Seeder {
    public function run() {
        $weekDate = now()->startOfWeek();
        IntensiMisa::create(["family_name" => "Keluarga Jonathan Simamora", "amount" => 50000, "description" => "Syukur atas kesehatan", "week_date" => $weekDate]);
        IntensiMisa::create(["family_name" => "Keluarga Marisi Hutapea", "amount" => 100000, "description" => "Mohon kelancaran usaha", "week_date" => $weekDate]);
    }
}');

echo "Setup script finished.\n";
