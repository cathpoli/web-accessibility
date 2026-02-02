<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Cashier\Billable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, Billable;

    public const ADMIN_EMAIL = 'admin@aceada.com';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'parent_id',
        'name',
        'email',
        'type',
        'password',
        'billing_address',
        'billing_address',
        'billing_address_2',
        'billing_address_3',
        'city',
        'state',
        'zip',
        'country',
        'phone_number',
        'sso_token',
        'status',
        'tapfiliate_customer_id',
        'deleted_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'sso_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected static function booted()
    {
        /** set user sso token on create */
        static::created(function (User $user) {
            $user->update(['sso_token' => Str::random(30)]);
        });
    }

    /**
     * Scopes
     */

    public function scopeClients(Builder $query)
    {
        return $query->where('type', 'client');
    }

    public function scopePayingClients(Builder $query)
    {
        return $query->clients()->whereHas('subscriptions', function($q) {
            $q->where('stripe_status', 'active');
        });
    }

    public function scopeAdmins(Builder $query)
    {
        return $query->where('type', 'admin');
    }

    /**
     * Note: invoices() method is already used on \Laravel\Cashier\Concerns\ManagesInvoices
     * that returns \Laravel\Cashier\Invoice[]
     *
     * @return App\Models\Invoice
     */
    public function clientInvoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function cards()
    {
        return $this->hasMany(Card::class);
    }

    public function domains()
    {
        return $this->hasMany(Domain::class);
    }

    public function members()
    {
        return $this->hasMany(User::class, 'parent_id');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function followupemails()
    {
        return $this->hasMany(FollowupEmail::class);
    }
}
