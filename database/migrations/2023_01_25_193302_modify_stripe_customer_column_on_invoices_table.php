<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /** Note: This doesn't work; unique constraint is dropped on 2023_02_23_001812_drop_unique_constraint_on_stripe_customer_column_on_invoices_table */
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('stripe_customer')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
