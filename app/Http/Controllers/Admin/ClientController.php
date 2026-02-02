<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Models\Coupon;
use App\Models\Domain;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Stripe\StripeClient;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('Admin/Clients/Index');
    }

    public function list(Request $request)
    {
        $params = $request->all();
        $limit = $request->query('size', 10); // Retrieve the value of 'size' parameter from the request query parameters. Default to 10 if not present.
        $clients = User::withCount('domains')->where('parent_id', 0);
        
        if ($params['search']) {
            $searchTerm = '%' . $params['search'] . '%';
            $clients = $clients->where(function ($query) use ($searchTerm) {
                $query->where('name', 'like', $searchTerm)
                    ->orWhere('email', 'like', $searchTerm);
            });
        }

        if ($params['items']) {
            $limit = intval($params['items']); // Convert the 'items' parameter to an integer
            $clients = $clients->limit($limit);
        }

        if ($params['sort'] === 'asc') {
            $orderByDirection = 'asc';
        } elseif ($params['sort'] === 'desc') {
            $orderByDirection = 'desc';
        }

        $orderBy = $params['orderBy'] ?? 'id'; // Default value if 'orderBy' parameter is not present

        $clients = $clients->orderBy($orderBy, $orderByDirection);

        $clients = $clients->paginate($limit);

        return json_encode($clients, true);
    }

    public function show(Request $request): Response
    {
        return Inertia::render('Admin/Clients/Show', [
            'client' => User::where('id', $request->id)->firstOrFail(),
        ]);
    }

    public function domainList(Request $request)
    {
        $params = $request->all();
        $limit = $request->query('limit', 10); // Retrieve the value of 'size' parameter from the request query parameters. Default to 10 if not present.

        $domains = Domain::with(['subscription:id,stripe_status', 'product:id,name'])
                    ->where('user_id', $params['user_id']);

        if ($params['search']) {
            $domains = $domains->where('name', 'LIKE', '%' . $params['search'] . '%');
        }

        if ($params['sort'] === 'asc') {
            $orderByDirection = 'asc';
        } elseif ($params['sort'] === 'desc') {
            $orderByDirection = 'desc';
        }
    
        $orderBy = $params['orderBy'] ?? 'id'; // Default value if 'orderBy' parameter is not present

        if ($params['orderBy'] === 'name') {
            $domains = $domains->orderBy('name', $orderByDirection);
        } else {
            $domains = $domains->orderBy($orderBy, $orderByDirection);
        }
        
        $domains = $domains->paginate($limit);

        return json_encode($domains, true);
    }

    public function memberList(Request $request)
    {
        $params = $request->all();
        $limit = $request->query('limit', 10); // Retrieve the value of 'size' parameter from the request query parameters. Default to 10 if not present.

        $members = User::where('parent_id', $params['user_id']);

        if ($params['search']) {
            $members = $members->where('name', 'LIKE', '%' . $params['search'] . '%');
        }

        if ($params['sort'] === 'asc') {
            $orderByDirection = 'asc';
        } elseif ($params['sort'] === 'desc') {
            $orderByDirection = 'desc';
        }
    
        $orderBy = $params['orderBy'] ?? 'id'; // Default value if 'orderBy' parameter is not present

        if ($params['orderBy'] === 'name') {
            $members = $members->orderBy('name', $orderByDirection);
        } else {
            $members = $members->orderBy($orderBy, $orderByDirection);
        }
        
        $members = $members->paginate($limit);

        return json_encode($members, true);
    }

    public function domainDetails(Domain $domain, Request $request) : Response
    {
        $secretKey = config('cashier.secret');
        $stripe = new StripeClient($secretKey);
        $coupon = [];
        $params = $request->query();
        $limit = $request->query('size', 10); // Retrieve the value of 'size' parameter from the request query parameters. Default to 10 if not present.
        $orderBy = $params['orderBy'] ?? 'created_at'; // Default value if 'orderBy' parameter is not present
        $orderByDirection = $params['sort'] ?? 'desc'; // Default value if 'orderBy' parameter is not present

        /** @return \Stripe\Subscription */
        $subscription = $stripe->subscriptions->retrieve($domain->subscription->stripe_id);

        $paymentMethod = Card::select('id', 'brand', 'last4', 'exp_month', 'exp_year', 'billing_name')
                                ->where('stripe_payment_method', $subscription->default_payment_method)
                                ->first();

        $invoicesQuery = Invoice::select('id', 'number', 'period_start', 'period_end', 'subtotal', 'total', 'status', 'stripe_invoice')
                                ->where('stripe_subscription', $subscription->id);

        if ($params && isset($params['sort']) && ($params['sort'] == 'asc' || $params['sort'] == 'desc')) {
            $invoicesQuery->orderBy($orderBy, $orderByDirection);
        }
                        
        $invoices = $invoicesQuery->paginate($limit);

        if ($subscription->discount) {
            $coupon = Coupon::where('code', $subscription->discount->coupon->id)->first();
        }    

        return Inertia::render('Admin/Clients/Subscription', [
            'domain' => $domain->load('product:id,name,price,interval'),
            'subscription' => $subscription,
            'paymentMethod' => $paymentMethod,
            'invoices' => $invoices,
            'plans' => Product::active()->orderBy('price')->get(),
            'coupon' => $coupon,
        ]);
    }
}
