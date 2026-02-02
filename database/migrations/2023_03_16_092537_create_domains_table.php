<?php

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Laravel\Cashier\Subscription;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('domains', function (Blueprint $table) {
            $table->id();
            $table->string('token', 50)->unique()->nullable();
            $table->string('name', 100)->nullable();
            $table->foreignIdFor(User::class)->nullable();
            $table->foreignIdFor(Product::class)->nullable();
            $table->foreignIdFor(Subscription::class)->nullable();
            $table->timestamp('next_payment_at')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('domains');
    }
};
