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
            $table->string('gender')->nullable();
            $table->longText('address')->nullable();
            $table->string('companyname')->nullable();
            $table->string('companyemail')->nullable();
            $table->string('state')->nullable();
            $table->string('city')->nullable();
            $table->string('zipcode', 10)->nullable();
            $table->string('mobile', 12)->nullable();
            $table->string('qualification', 100)->nullable();
            $table->string('certificate', 250)->nullable();
            $table->integer('fk_roles_id');
            $table->integer('fk_university_id');
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
