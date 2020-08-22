<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectpaymentTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('projectpayment', function (Blueprint $table) {
            $table->id();
            $table->integer('fk_project_id');
            $table->string('txnid');
            $table->text('payid');
            $table->decimal('amount', 8, 2);
            $table->string('message')->nullable('');
            $table->string('txnstatus');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('projectpayment');
    }

}
