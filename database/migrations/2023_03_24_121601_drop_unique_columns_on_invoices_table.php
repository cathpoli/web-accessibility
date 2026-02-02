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
        /** Got this from `DB::select("SHOW INDEXES FROM invoices")` */
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropUnique('invoices_stripe_subscription_unique');
            $table->dropUnique('invoices_stripe_charge_unique');

            $table->index('stripe_invoice');
            $table->index('stripe_customer');
            $table->index('stripe_subscription');
            $table->index('stripe_charge');
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
