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
                'location' =>  DB::raw("(ST_GeomFromText('POINT(-6.1930126 106.8377762)'))"),
                'description' => 'Airmas Asri Architects merupakan perusahaan yang bergerak di jasa arsitek di Jakarta Utara',
                'password' => bcrypt('qwerty123'),
                'account_number' => '000000000',
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
                'location' =>  DB::raw("(ST_GeomFromText('POINT(0.5320067 101.4456002)'))"),
                'description' => 'Angkasa architects adalah perusahaan yang bergerak di jasa arsitek yang berada di Pekanbaru',
                'password' => bcrypt('qwerty123'),
                'account_number' => '123456789',
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
                'location' =>  DB::raw("(ST_GeomFromText('POINT(0.5137487 101.4162767)'))"),
                'description' => 'DH INTERIOR adalah perusahaan yang bergerak di jasa desain interior yang berada di Pekanbaru',
                'password' => bcrypt('qwerty123'),
                'account_number' => '12332131312',
                'image_path' => 'https://res.cloudinary.com/dwrorg2la/image/upload/v1642066634/Example/dh.png',
                'thumbnail' => 'https://res.cloudinary.com/dwrorg2la/image/upload/v1642066581/Example/IMG_2585_ig-scaled.jpg',
                'city_id' => '9',
                'professional_type_id' => '2',
                'status_id' => '2'
            ],
            [
                'name' => 'SS Architect',
                'email' => 'john_interior@gmail.com',
                'phone_number' => '08456234567',
                'address' => 'Jl. Jenderal Sudirman no F2, Bandung',
                'location' =>  DB::raw("(ST_GeomFromText('POINT(-6.930001 107.609624)'))"),
                'description' => 'SS Architect melayani jasa desain rumah dengan berbagai gaya dengan harga terjangkau',
                'password' => bcrypt('qwerty123'),
                'account_number' => '99999999',
                'image_path' => 'https://res.cloudinary.com/dwrorg2la/image/upload/v1643549036/Example/SS-avatars.png',
                'thumbnail' => 'https://res.cloudinary.com/dwrorg2la/image/upload/v1643548842/Example/ss%20interior.jpg',
                'city_id' => '21',
                'professional_type_id' => '1',
                'status_id' => '2'
            ],
            [
                'name' => 'SF Interior',
                'email' => 'sf_interior@gmail.com',
                'phone_number' => '084564324324',
                'address' => 'Jl. Gajah Mada no 1, Jogjakarta',
                'location' =>  DB::raw("(ST_GeomFromText('POINT(-7.796998 110.385578)'))"),
                'description' => 'SF Interior melayani jasa desain interior rumah dengan berbagai gaya dengan harga terjangkau',
                'password' => bcrypt('qwerty123'),
                'account_number' => '88231318321',
                'image_path' => 'https://res.cloudinary.com/dwrorg2la/image/upload/v1643549126/Example/sf-avatars.png',
                'thumbnail' => 'https://res.cloudinary.com/dwrorg2la/image/upload/v1643549182/Example/sf-thumbnail.jpg',
                'city_id' => '26',
                'professional_type_id' => '2',
                'status_id' => '2'
            ],
            [
                'name' => 'DESIGN STUDIO',
                'email' => 'designstudio@gmail.com',
                'phone_number' => '08454654646',
                'address' => 'Jl. Tangkuban perahu no 86, Jakarta',
                'location' =>  DB::raw("(ST_GeomFromText('POINT(-6.157090 106.634437)'))"),
                'description' => 'Design Studio melayani jasa desain interior dan arsitektur rumah dengan berbagai gaya dengan harga terjangkau',
                'password' => bcrypt('qwerty123'),
                'account_number' => '883202032030',
                'image_path' => 'https://res.cloudinary.com/dwrorg2la/image/upload/v1643549254/Example/SD-avatars.png',
                'thumbnail' => 'https://res.cloudinary.com/dwrorg2la/image/upload/v1643549315/Example/SD-cover.jpg',
                'city_id' => '15',
                'professional_type_id' => '3',
                'status_id' => '2'
            ]
        ]);
    }
}
