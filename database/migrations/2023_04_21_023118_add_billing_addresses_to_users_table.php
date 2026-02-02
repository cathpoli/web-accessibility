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
            $table->string('billing_address_2')->after('billing_address')->nullable();
            $table->string('billing_address_3')->after('billing_address_2')->nullable();
            $table->string('city')->after('billing_address_3')->nullable();
            $table->string('state_zip')->after('city')->nullable();
            $table->string('country')->after('state_zip')->nullable();
        });
    }
};
