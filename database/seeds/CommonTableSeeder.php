<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class CommonTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {

        Model::unguard();
        // Roles
        DB::table('roles')->insert([
                [
                'name' => 'Client',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
                'status' => '1',
                'dels' => '0'
            ],
                [
                'name' => 'Deisigner',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
                'status' => '1',
                'dels' => '0'
            ], [
                'name' => 'Factory Owner',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
                'status' => '1',
                'dels' => '0'
            ], [
                'name' => 'Retailer',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
                'status' => '1',
                'dels' => '0'
            ]]
        );

        // University
        DB::table('university')->insert([
                [
                'name' => 'All India Institute Of Medical Sciences',
                'status' => '1',
                'dels' => '0',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ],
                [
                'name' => 'Amity University Delhi',
                'status' => '1',
                'dels' => '0',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ], [
                'name' => 'Apeejay Stya University',
                'status' => '1',
                'dels' => '0',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ], [
                'name' => 'Delhi Technological University',
                'status' => '1',
                'dels' => '0',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ], [
                'name' => 'Indian Law Institute',
                'status' => '1',
                'dels' => '0',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ],
                [
                'name' => 'Indira Gandhi National Open University',
                'status' => '1',
                'dels' => '0',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ], [
                'name' => 'Indraprastha University',
                'status' => '1',
                'dels' => '0',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ], [
                'name' => 'National Law University',
                'status' => '1',
                'dels' => '0',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ], [
                'name' => 'Shah University',
                'status' => '1',
                'dels' => '0',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ]]
        );

        // Category        
        DB::table('category')->insert([
                [
                'name' => 'Office Wear',
                'status' => '1',
                'dels' => '0',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ],
                [
                'name' => 'Sports Wear',
                'status' => '1',
                'dels' => '0',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ], [
                'name' => 'Classic',
                'status' => '1',
                'dels' => '0',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ], [
                'name' => 'Exotic',
                'status' => '1',
                'dels' => '0',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ], [
                'name' => 'Street',
                'status' => '1',
                'dels' => '0',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ],
                [
                'name' => 'Vintage',
                'status' => '1',
                'dels' => '0',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ], [
                'name' => 'Chic',
                'status' => '1',
                'dels' => '0',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ], [
                'name' => 'Arty',
                'status' => '1',
                'dels' => '0',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ], [
                'name' => 'Preppy',
                'status' => '1',
                'dels' => '0',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ], [
                'name' => 'Bohemian',
                'status' => '1',
                'dels' => '0',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ],
                [
                'name' => 'Goth',
                'status' => '1',
                'dels' => '0',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ], [
                'name' => 'Flamboyant',
                'status' => '1',
                'dels' => '0',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ], [
                'name' => 'Punk',
                'status' => '1',
                'dels' => '0',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ], [
                'name' => 'Rocker',
                'status' => '1',
                'dels' => '0',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ], [
                'name' => 'Tomboy',
                'status' => '1',
                'dels' => '0',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ]]
        );

        // Season
        DB::table('season')->insert([
                [
                'name' => 'Winter',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
                'status' => '1',
                'dels' => '0'
            ],
                [
                'name' => 'Spring',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
                'status' => '1',
                'dels' => '0'
            ], [
                'name' => 'Summer',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
                'status' => '1',
                'dels' => '0'
            ], [
                'name' => 'Autumn',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
                'status' => '1',
                'dels' => '0'
            ]]
        );

        // Designer Type
        DB::table('designertype')->insert([
                [
                'name' => 'Haute Couture Fashion Designer',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
                'status' => '1',
                'dels' => '0'
            ],
                [
                'name' => 'Prêt-a-porter Fashion Designer',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
                'status' => '1',
                'dels' => '0'
            ], [
                'name' => 'Mass Market Fashion Designer',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
                'status' => '1',
                'dels' => '0'
            ], [
                'name' => 'Footwear Designers',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
                'status' => '1',
                'dels' => '0'
            ], [
                'name' => 'Fashion Accessory Designer',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
                'status' => '1',
                'dels' => '0'
            ]]
        );
    }

}
