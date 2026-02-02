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
        Schema::table('users', function (Blueprint $table) {
            $table->string('website_url')->after('password')->nullable();
            $table->string('billing_address')->after('website_url')->nullable();
            $table->string('phone_number')->after('billing_address')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('website_url');
            $table->dropColumn('billing_address');
            $table->dropColumn('phone_number');
        });
    }
};
