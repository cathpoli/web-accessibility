<?php

namespace App\Models;

use App\Models\Subscription;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    /** @return \Laravel\Cashier\Invoice */

    use HasFactory, SoftDeletes;

    protected $fillable = [
        'number',
        'stripe_invoice',
        'stripe_customer',
        'user_id',
        'stripe_subscription',
        'domain_id',
        'stripe_charge', // latest charge generated for this invoices; an invoices can have multiple payments
        'payment_id',
        'period_start',
        'period_end',
        'subtotal',
        'total',
        'status', // values: https://stripe.com/docs/api/invoices/object#invoice_object-status
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function domain()
    {
        return $this->belongsTo(Domain::class);
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class, 'stripe_subscription', 'stripe_id')->without(['items']);
    }
}
