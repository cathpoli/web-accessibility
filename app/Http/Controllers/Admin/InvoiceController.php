<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('Admin/Invoices/Index');
    }

    public function list(Request $request)
    {
        $params = $request->all();
        $limit = $request->query('size', 10); // Retrieve the value of 'size' parameter from the request query parameters. Default to 10 if not present.
        // $invoices = Invoice::with(['user:id,name', 'subscription:id,name', 'domain:id,name']);
        $invoices = DB::table('invoices')
                    ->select(
                        'invoices.*',
                        'domains.name as domain_name',
                        'users.name',
                        DB::raw('CASE WHEN invoices.domain_id IS NULL THEN domains.id ELSE invoices.domain_id END domain_id')
                    )
                    ->join('subscriptions', 'subscriptions.stripe_id', '=', 'invoices.stripe_subscription')
                    ->join('domains', 'domains.subscription_id', '=', 'subscriptions.id')
                    ->join('users', 'users.id', '=', 'invoices.user_id');
    
        if ($params['search']) {
            $searchTerm = '%' . $params['search'] . '%';
            $invoices = $invoices->where(function ($query) use ($searchTerm) {
                $query->where('number', 'like', $searchTerm)
                    ->orWhere('name', 'like', $searchTerm)
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
    
        $orderBy = $params['orderBy'] ?? 'id'; // Default value if 'orderBy' parameter is not present

        if ($params['orderBy'] === 'user_name') {
            $invoices = $invoices->orderBy('name', $orderByDirection);
        } elseif ($params['orderBy'] === 'domain_name') {
            $invoices = $invoices->orderBy('domains.name', $orderByDirection);
        } else {
            $invoices = $invoices->orderBy($orderBy, $orderByDirection);
        }
    
        $invoices = $invoices->paginate($limit);
    
        return response()->json($invoices);
    }

}
