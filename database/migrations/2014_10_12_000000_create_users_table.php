<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('users', function (Blueprint $table) {
            $table->id();            
            $table->string('username');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('gender', ['male', 'female', 'others'])->nullable();
            $table->longText('address')->nullable();
            $table->string('companyname')->nullable();
            $table->string('country')->nullable();
            $table->string('state')->nullable();
            $table->string('zipcode', 10)->default(0);
            $table->string('mobile', 12)->default(0);
            $table->enum('fk_roles_id', ['1', '2'])->default('2');
            $table->string('email_otp', 12)->default(0);
            $table->string('mobile_otp', 12)->default(0);
            $table->enum('activationstatus', ['0', '1'])->default('0');
            $table->enum('status', ['0', '1'])->default('0');
            $table->enum('dels', ['0', '1'])->default('0');
            $table->string('devicetoken')->nullable();
            $table->enum('iosuser', ['0', '1'])->default('0');
            $table->enum('androiduser', ['0', '1'])->default('0');
            $table->enum('webuser', ['0', '1'])->default('0');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('users');
    }

}
