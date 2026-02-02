<?php

use App\Models\Coupon;
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
        Schema::table('coupons', function (Blueprint $table) {
            $table->string('type')->after('name')->nullable();
            $table->smallInteger('period_off')->after('amount_off')->nullable();
        });

        Schema::table('coupons', function (Blueprint $table) {
            $table->decimal('percent_off')->nullable()->default(null)->change();
            $table->decimal('amount_off')->nullable()->default(null)->change();
        });

        // set null values
        foreach (Coupon::all() as $key => $coupon) {
            if ($coupon->percent_off == 0) $coupon->update(['percent_off' => null]);
            if ($coupon->amount_off == 0) $coupon->update(['amount_off' => null]);
        }

        // set type column value
        foreach (Coupon::all() as $key => $coupon) {
            if ($coupon->percent_off !== NULL) $coupon->update(['type' => 'percent_off']);
            if ($coupon->amount_off !== NULL) $coupon->update(['type' => 'amount_off']);
            if ($coupon->period_off !== NULL) $coupon->update(['type' => 'period_off']);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('coupons', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->dropColumn('period_off');
        });
    }
};
