<?php

namespace App\Http\Controllers\Client\Billing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Stripe\StripeClient;

class BillingController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $defaultPaymentMethod =  $user->defaultPaymentMethod();

        return Inertia::render('Client/Billing/Index', [
            'default_payment_method' => $defaultPaymentMethod,
            'payment_methods' => $user->cards,
        ]);
    }

    public function invoices(Request $request)
    {
        $user = $request->user();
        $params = $request->all();
        $limit = $request->query('items', 10); // Retrieve the value of 'items' parameter from the request query parameters. Default to 10 if not present.
        //$invoices = $user->clientInvoices()->with('domain:id,name');
        $invoices = DB::table('invoices')
                    ->select(
                        'invoices.*',
                        'domains.name as domain_name',
                        DB::raw('CASE WHEN invoices.domain_id IS NULL THEN domains.id ELSE invoices.domain_id END domain_id')
                    )
                    ->join('subscriptions', 'subscriptions.stripe_id', '=', 'invoices.stripe_subscription')
                    ->join('domains', 'domains.subscription_id', '=', 'subscriptions.id')
                    ->where('invoices.user_id', $user->id);
        
        if ($params['search']) {
            $searchTerm = '%' . $params['search'] . '%';
            $invoices = $invoices->where(function ($query) use ($searchTerm) {
                $query->where('number', 'like', $searchTerm)
                    ->orWhere(function ($query) use ($searchTerm) {
                        $query->whereRaw("DATE_FORMAT(period_start, '%M %e, %Y') LIKE ?", [$searchTerm])
                            ->orWhereRaw("DATE_FORMAT(period_end, '%M %e, %Y') LIKE ?", [$searchTerm]);
                    })
                    ->orWhere('total', 'like', $searchTerm)
                    ->orWhere('subtotal', 'like', $searchTerm)
                    ->orWhere('status', 'like', $searchTerm)
                    ->orWhere('domains.name', 'like', $searchTerm);
            });
        }

        if ($params['items']) {
            $limit = intval($params['items']); // Convert the 'items' parameter to an integer
            $invoices = $invoices->limit($limit);
        }

        if ($params['sort'] === 'asc') {
            $orderByDirection = 'asc';
        } elseif ($params['sort'] === 'desc') {
            $orderByDirection = 'desc';
        }

        $orderBy = $params['orderBy'] ?? 'created_at'; // Default value if 'orderBy' parameter is not present

        if ($params['orderBy'] === 'domain_name') {
            $invoices = $invoices->orderBy('domains.name', $orderByDirection);
        } else {
            $invoices = $invoices->orderBy($orderBy, $orderByDirection);
        }

        $invoices = $invoices->paginate($limit);

        return json_encode($invoices, true);

    }
}
