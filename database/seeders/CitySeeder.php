<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //jumlah 38
        DB::table('cities')->insert([
            [
                'name' => 'Banda Aceh',
                'province_name' => 'Aceh'
            ],
            [
                'name' => 'Sabang',
                'province_name' => 'Aceh'
            ],
            [
                'name' => 'Lhokseumawe',
                'province_name' => 'Aceh'
            ],
            [
                'name' => 'Medan',
                'province_name' => 'Sumatera Utara'
            ],
            [
                'name' => 'Pematang Siantar',
                'province_name' => 'Sumatera Utara'
            ],
            [
                'name' => 'Binjai',
                'province_name' => 'Sumatera Utara'
            ],
            [
                'name' => 'Padang',
                'province_name' => 'Sumatera Barat'
            ],
            [
                'name' => 'Bukit Tinggi',
                'province_name' => 'Sumatera Barat'
            ],
            [
                'name' => 'Pekanbaru',
                'province_name' => 'Riau'
            ],
            [
                'name' => 'Dumai',
                'province_name' => 'Riau'
            ],
            [
                'name' => 'Jambi',
                'province_name' => 'Jambi'
            ],
            [
                'name' => 'Jakarta Utara',
                'province_name' => 'DKI Jakarta'
            ],
            [
                'name' => 'Jakarta Timur',
                'province_name' => 'DKI Jakarta'
            ],
            [
                'name' => 'Jakarta Barat',
                'province_name' => 'DKI Jakarta'
            ],
            [
                'name' => 'Jakarta Selatan',
                'province_name' => 'DKI Jakarta'
            ],
            [
                'name' => 'Jakarta Pusat',
                'province_name' => 'DKI Jakarta'
            ],
            [
                'name' => 'Tangerang',
                'province_name' => 'Banten'
            ],
            [
                'name' => 'Serang',
                'province_name' => 'Banten'
            ],
            [
                'name' => 'Tangerang',
                'province_name' => 'Banten'
            ],
            [
                'name' => 'Depok',
                'province_name' => 'Jawa Barat',
            ],
            [
                'name' => 'Bandung',
                'province_name' => 'Jawa Barat',
            ],
            [
                'name' => 'Bogor',
                'province_name' => 'Jawa Barat',
            ],
            [
                'name' => 'Bekasi',
                'province_name' => 'Jawa Barat',
            ],
            [
                'name' => 'Semarang',
                'province_name' => 'Jawa Tengah'
            ],
            [
                'name' => 'Magelang',
                'province_name' => 'Jawa Tengah'
            ],
            [
                'name' => 'Yogyakarta',
                'province_name' => 'DI Yogyakarta'
            ],
            [
                'name' => 'Denpasar',
                'province_name' => 'Bali'
            ],
            [
                'name' => 'Pontianak',
                'province_name' => 'Kalimantan Barat'
            ],
            [
                'name' => 'Singkawang',
                'province_name' => 'Kalimantan Barat'
            ],
            [
                'name' => 'Palangka Raya',
                'province_name' => 'Kalimantan Tengah'
            ],
            [
                'name' => 'Banjarmasin',
                'province_name' => 'Kalimantan Selatan'
            ],
            [
                'name' => 'Banjarbaru',
                'province_name' => 'Kalimantan Selatan'
            ],
            [
                'name' => 'Tarakan',
                'province_name' => 'Kalimantan utara'
            ],
            [
                'name' => 'Manado',
                'province_name' => 'Sulawesi Utara'
            ],
            [
                'name' => 'Kotamobagu',
                'province_name' => 'Sulawesi Utara'
            ],
            [
                'name' => 'Palembang',
                'province_name' => 'Sumatra Selatan'
            ],
            [
                'name' => 'Prabumulih',
                'province_name' => 'Sumatra Selatan'
            ],
            [
                'name' => 'Palu',
                'province_name' => 'Sulawesi Tengah'
            ],
        ]);
    }
}
