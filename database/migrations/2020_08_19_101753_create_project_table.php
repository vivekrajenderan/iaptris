<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('project', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('fk_category_id');
            $table->integer('fk_season_id');
            $table->integer('fk_designertype_id');
            $table->enum('stylefor', ['men', 'women', 'boy', 'girl'])->default('men');
            $table->string('brandname', 250)->nullable();
            $table->string('brandimage', 250)->nullable();
            $table->string('deliverytime', 250)->nullable();
            $table->enum('designbudget', ['regular', 'urgent'])->default('regular');
            $table->enum('status', ['0', '1'])->default('0');
            $table->enum('dels', ['0', '1'])->default('0');
            $table->integer('createdBy');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('project');
    }

}
