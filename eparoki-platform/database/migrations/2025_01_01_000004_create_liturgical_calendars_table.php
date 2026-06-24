<?php
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
};