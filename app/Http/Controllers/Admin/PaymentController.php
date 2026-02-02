<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('Admin/Payments/Index');
    }

    public function list(Request $request)
    {
        $params = $request->all();
        $limit = $request->query('size', 10); // Retrieve the value of 'size' parameter from the request query parameters. Default to 10 if not present.
        $payments = Payment::with('user:id,name');

        if ($params['search']) {
            $payments = $payments->where('stripe_invoice', 'like', '%' .  $params['search'] . '%')
                ->orWhere('amount', 'like', '%' . $params['search'] . '%')
                ->orWhereHas('user', function ($query) use ($params) {
                    $query->where('name', 'like', '%' . $params['search'] . '%');
            });
        }

        if ($params['items']) {
            $limit = intval($params['items']); // Convert the 'items' parameter to an integer
            $payments = $payments->limit($limit);
        }

        if ($params['sort'] === 'asc') {
            $orderByDirection = 'asc';
        } elseif ($params['sort'] === 'desc') {
            $orderByDirection = 'desc';
        }

        $orderBy = $params['orderBy'] ?? 'created_at'; // Default value if 'orderBy' parameter is not present
        
        if ($params['orderBy'] === 'user_name') {
            $payments = $payments->leftJoin('users', 'payments.user_id', '=', 'users.id')
                ->select('payments.*', 'users.name AS user_name')
                ->orderBy($orderBy, $orderByDirection);
        } else {
            $payments = $payments->orderBy($orderBy, $orderByDirection);
        }

        $payments = $payments->paginate($limit);

        return json_encode($payments, true);
    }
}
