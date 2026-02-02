<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Domain;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

use function Termwind\render;

class DashboardController extends Controller
{
    public function dashboard(Request $request) : Response
    {

        $products = Product::active()->orderBy('price')->get();

        return Inertia::render('Client/Dashboard', [
            'plans' => $products,
        ]);
    }

    public function domains(Request $request)
    {
        $params = $request->all();
        $limit = $request->query('limit', 10); // Retrieve the value of 'size' parameter from the request query parameters. Default to 10 if not present.
        $user = $request->user();

        // member can see the domains of the owner 
        if (isset($user->owner->id)) {
            $userIds[] = $user->owner->id;
        }

        $userIds[] = $request->user()->id;

        $domains = Domain::with(['subscription:id,stripe_status', 'product:id,name'])
                    ->whereIn('user_id', $userIds);

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
}
