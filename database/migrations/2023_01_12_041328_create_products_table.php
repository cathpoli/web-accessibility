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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->string('slug', 50)->unique();
            $table->string('stripe_product')->unique();
            $table->string('stripe_default_price')->unique();
            $table->decimal('price'); // 0 for free trial
            $table->string('interval', 20)->nullable(); // yearly, monthly, etc
            $table->longText('description')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index(['slug', 'stripe_product', 'stripe_default_price']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
};
