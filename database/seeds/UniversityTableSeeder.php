<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class UniversityTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {

        Model::unguard();
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
    }

}
