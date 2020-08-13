<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class RolesTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {

        Model::unguard();
        DB::table('roles')->insert([[
        'name' => 'admin',
        'status' => '1',
        'dels' => '0'
            ], [
                'name' => 'client',
                'status' => '1',
                'dels' => '0'
            ],
                [
                'name' => 'designer',
                'status' => '1',
                'dels' => '0'
            ]]
        );
    }

}
