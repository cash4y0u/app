<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('customer_id');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->decimal('amount', 8, 2);
            $table->decimal('amount_provision', 8, 2);
            $table->decimal('amount_rate', 8, 2);
            $table->decimal('amount_total', 8, 2);
            $table->decimal('amount_profit', 8, 2);
            $table->integer('split');
            $table->string('cycle');
            $table->double('rate', 8, 2);
            $table->double('rate_daily', 8, 2);
            $table->date('maturity');
            $table->enum('status', ['open', 'settled'])->default('open');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contracts');
    }
}
