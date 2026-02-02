<?php

use App\Models\User;
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
        Schema::create('cards', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class);
            $table->string('stripe_payment_method')->unique();
            $table->string('brand', 20);
            $table->string('last4', 4);
            $table->string('exp_month', 2);
            $table->string('exp_year', 4);
            $table->softDeletes();
            $table->timestamps();

            $table->index(['stripe_payment_method']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cards');
    }
};
