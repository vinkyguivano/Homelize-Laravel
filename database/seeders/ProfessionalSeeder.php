<?php

namespace Database\Seeders;

use App\Models\Professional;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProfessionalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('professionals')->insert([
            [
                'name' => 'Airmas Asri Architects',
                'email' => 'airmasasri@gmail.com',
                'phone_number' => '08123123123',
                'address' => 'Jl. Cikini IV No.6, RT.15/RW.5, Cikini, Kec. Menteng',
                'location' =>  DB::raw("(GeomFromText('POINT(40.71727401 -74.00898606)'))"),
                'description' => 'Airmas Asri Architects adalah perusahaan yang bergerak di jasa arsitek',
                'password' => 'qwerty123',
                'account_number' => '10712312312',
                'image_path' => 'https://res.cloudinary.com/dwrorg2la/image/upload/v1642066172/Example/T2K5ZMzFeNahoC9VIPxuJ8yxYA5sCeAl6agVNFqRirZxTXjsDH.jpg',
                'thumbnail' => 'https://res.cloudinary.com/dwrorg2la/image/upload/v1642066130/Example/Optimized-Cover-Image-3-1.jpg',
                'city_id' => '12',
                'professional_type_id' => '1',
                'status_id' => '2'
            ],
            [
                'name' => 'Angkasa Architects & Interior',
                'email' => 'angkasaarchitects@gmail.com',
                'phone_number' => '081243234',
                'address' => 'Jl. Prof. Moh. Yamin No.47, Kota Baru',
                'location' =>  DB::raw("(GeomFromText('POINT(40.71727401 -74.00898606)'))"),
                'description' => 'Angkasa architects adalah perusahaan yang bergerak di jasa arsitek yang berada di Pekanbaru',
                'password' => 'qwerty123',
                'account_number' => '0812313',
                'image_path' => 'https://res.cloudinary.com/dwrorg2la/image/upload/v1642066363/Example/3094.jpg',
                'thumbnail' => 'https://res.cloudinary.com/dwrorg2la/image/upload/v1642066327/Example/download.jpg',
                'city_id' => '9',
                'professional_type_id' => '3',
                'status_id' => '2'
            ],
            [
                'name' => 'DH Interior',
                'email' => 'dhinterior@gmail.com',
                'phone_number' => '08124323324',
                'address' => 'Jl. Fajar No.49, Labuh Baru Bar., Kec. Payung Sekaki',
                'location' =>  DB::raw("(GeomFromText('POINT(40.71727401 -74.00898606)'))"),
                'description' => 'DH INTERIOR adalah perusahaan yang bergerak di jasa desain interior yang berada di Pekanbaru',
                'password' => 'qwerty123',
                'account_number' => '061231312',
                'image_path' => 'https://res.cloudinary.com/dwrorg2la/image/upload/v1642066634/Example/dh.png',
                'thumbnail' => 'https://res.cloudinary.com/dwrorg2la/image/upload/v1642066581/Example/IMG_2585_ig-scaled.jpg',
                'city_id' => '9',
                'professional_type_id' => '2',
                'status_id' => '2'
            ],
        ]);
    }
}
