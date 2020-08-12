<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;


class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
		Model::unguard();
		DB::table('users')->insert(
                    array(
                        'name'=>'admin',
                        'email' => 'admin@gmail.com',
                        'status' => 'verified',
                        'password'=>bcrypt('admin123'),
                        'fk_usertypes_id'=>'1',
                        'dob'=>'1992-03-07',
                        'address'=>'No.1,South Street',
                        'country'=>'India',
                        'state'=>'Tamilnadu',
                        'companyname'=>'BFT',
                        'zipcode'=>62564,
                        'phone'=>9878657634
                    )
            );
	
    }
}
