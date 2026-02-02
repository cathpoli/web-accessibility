<?php

use App\Models\Domain;
use App\Models\Payment;
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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('number')->unique()->nullable();
            $table->string('stripe_invoice')->unique()->nullable();
            $table->string('stripe_customer')->unique()->nullable();
            $table->foreignIdFor(User::class)->nullable();
            $table->string('stripe_subscription')->unique()->nullable();
            $table->foreignIdFor(Domain::class)->nullable();
            $table->string('stripe_charge')->unique()->nullable();
            $table->foreignIdFor(Payment::class)->nullable();
            $table->timestamp('period_start')->nullable();
            $table->timestamp('period_end')->nullable();
            $table->decimal('subtotal')->default(0);
            $table->decimal('total')->default(0);
            $table->string('status'); // draft, open, paid, uncollectible, or void
            $table->softDeletes();
            $table->timestamps();

            /** commented as it causes `identifier name is too long` error */
            // $table->index(['stripe_invoice', 'number', 'stripe_customer', 'stripe_subscription', 'stripe_charge']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoices');
    }
};
