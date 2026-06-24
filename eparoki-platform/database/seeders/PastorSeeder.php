<?php
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
}