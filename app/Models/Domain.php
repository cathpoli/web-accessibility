<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Domain extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Note: we can just put the domain on subscriptions table but since we need token for each domain
     *      we will opt for creating new model, also just in case we need to add more columns
     */

    protected $fillable = [
        'token',
        'name',
        'user_id',
        'product_id',
        'subscription_id',
        'widget_configurations',
        'preview_img',
        'is_whitelisted',
        'page_count',
        'next_payment_at',
        'cancelled_at',
    ];

    protected static function booted()
    {
        /** set domain token on create */
        static::created(function (Domain $domain) {
            $domain->update([
                'token' => Str::random(10) . $domain->created_at->format('mdY') . Str::random(10) . $domain->created_at->format('His') . Str::random(10)
            ]);
        });
    }

    public static function formatHostname($host)
    {
        $host = strtolower($host);

        /** remove www. from first 4 character of string */
        if (substr($host, 0, 4) !== 'www.') return $host;

        return substr($host, 4, strlen($host));
    }

    public static function defaultWidgetConfiguration()
    {
        return json_encode([
            'triggerColor' => null,
            'triggerPositionX' => null,
            'triggerPositionY' => null,
            'triggerIcon' => null,
            'triggerIconColor' => null,
            'widgetHeroTitleColor' => null,
            'themeColor' => null,
        ]);
    }

    public function getDecodedWidgetConfigurationAttribute()
    {
        $config = $this->widget_configurations ?? self::defaultWidgetConfiguration();

        return json_decode($config);
    }

    /**
     * Scopes
     */
    function scopeWhitelisted()
    {
        return $this->where('is_whitelisted', true);
    }

    function scopeActiveDomains()
    {
        return $this->whereNull('cancelled_at')->whereNull('deleted_at');
    }

    /**
     * Relationships
     */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class)->without(['items']);
    }

    public function subscriptionItems(): BelongsTo
    {
        return $this->belongsTo(Subscription::class);
    }
}
