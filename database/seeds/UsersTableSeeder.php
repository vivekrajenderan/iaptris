<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class UsersTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {

        Model::unguard();
        DB::table('users')->insert(
                array(                    
                    'username' => 'admin',
                    'email' => 'admin@collarweb.com',
                    'status' => '1',
                    'gender' => 'male',
                    'password' => bcrypt('admin123'),
                    'fk_roles_id' => '1',
                    'address' => '',
                    'country' => '',
                    'state' => '',
                    'companyname' => 'Iaptris',
                    'zipcode' => '',
                    'mobile' => '',
                    'created_at' => date("Y-m-d H:i:s")
                )
        );
    }

}
