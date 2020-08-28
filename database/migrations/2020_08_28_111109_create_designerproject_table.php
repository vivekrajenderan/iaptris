<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDesignerprojectTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('designerproject', function (Blueprint $table) {
            $table->id();
            $table->integer('fk_project_id');
            $table->integer('fk_user_id');
            $table->enum('status', ['0', '1'])->default('0');
            $table->enum('dels', ['0', '1'])->default('0');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('designerproject');
    }

}
