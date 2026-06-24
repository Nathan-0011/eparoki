<?php

$timestamp = date('Y_m_d_His');
$filename = "database/migrations/{$timestamp}_create_parish_profiles_table.php";

$content = '<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(\'parish_profiles\', function (Blueprint $table) {
            $table->id();
            $table->string(\'name\')->default(\'Paroki Santo Fidelis Parapat\');
            $table->string(\'diocese\')->default(\'Keuskupan Agung Medan\');
            $table->text(\'address\')->nullable();
            $table->string(\'phone\')->nullable()->default(\'(0625) 41XXX\');
            $table->string(\'email\')->nullable()->default(\'sekretariat@ekatolik-parapat.id\');
            $table->string(\'website\')->nullable();
            $table->text(\'description\')->nullable();
            $table->string(\'logo\')->nullable();
            $table->year(\'established_year\')->nullable();
            $table->string(\'feast_day\')->nullable();
            $table->string(\'google_maps_url\')->nullable();
            $table->text(\'google_maps_embed\')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(\'parish_profiles\');
    }
};
';

file_put_contents(__DIR__ . '/' . $filename, $content);
echo "Migration created: $filename\n";
