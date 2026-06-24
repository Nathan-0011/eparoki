<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Banner;
class BannerSeeder extends Seeder {
    public function run() {
        Banner::create(["title" => "Paskah 2025", "image_path" => "banners/placeholder.jpg", "start_date" => now()->subDays(5), "end_date" => now()->addDays(10)]);
        Banner::create(["title" => "Bulan Maria", "image_path" => "banners/placeholder.jpg", "start_date" => now()->subDays(2), "end_date" => now()->addDays(20)]);
        Banner::create(["title" => "Kegiatan OMK", "image_path" => "banners/placeholder.jpg"]);
    }
}