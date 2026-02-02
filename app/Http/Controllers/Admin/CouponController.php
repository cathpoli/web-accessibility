<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Coupons\StorePeriodOffRequest;
use App\Http\Requests\Coupons\StoreRequest;
use App\Models\Coupon;
use App\Models\CouponProduct;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response as FacadesResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;
use Stripe\StripeClient;

class CouponController extends Controller
{
    public function index(Request $request) : Response
    {
        // $coupons = Coupon::with('products')->paginate(10);
        return Inertia::render('Admin/Coupons/Index');
    }

    public function list(Request $request)
    {
        $params = $request->all();
        $limit = $request->query('size', 10); // Retrieve the value of 'size' parameter from the request query parameters. Default to 10 if not present.
        $coupons = Coupon::with('products');
        
        if ($params['search']) {
            $searchTerm = $params['search'];
            
            // 'all' here pertains to Applicable Products
            if ($searchTerm !== 'all') {
                // Determine search conditions based on the search term
                if (strpos($params['search'], '%') !== false) {
                    // Disregard the sign and search only in the percent_off column
                    $searchTerm = str_replace('%', '', $searchTerm);
                    $coupons = $coupons->where('percent_off', '=', $searchTerm);
                } elseif (strpos($params['search'], '$') !== false) {
                    // Disregard the sign and search only in the amount_off column
                    $searchTerm = str_replace('$', '', $searchTerm);
                    $coupons = $coupons->where('amount_off', '=', $searchTerm);
                } elseif (strpos($params['search'], 'months') !== false) {
                    // Disregard 'months' and search only in the period_off column
                    $searchTerm = str_replace('months', '', $searchTerm);
                    $coupons = $coupons->where('period_off', '=', $searchTerm);
                } else {
                    // Search in multiple columns including related products
                    $searchTerm = '%' . $searchTerm . '%';
                    $coupons = $coupons->where(function ($query) use ($searchTerm) {
                        $query->where('name', 'like', $searchTerm)
                            ->orWhere('code', 'like', $searchTerm)
                            ->orWhere('percent_off', 'like', $searchTerm)
                            ->orWhere('amount_off', 'like', $searchTerm)
                            ->orWhere('period_off', 'like', $searchTerm)
                            ->orWhere('duration', 'like', $searchTerm)
                            ->orWhere('times_redeemed', 'like', $searchTerm)
                            ->orWhereHas('products', function ($query) use ($searchTerm) {
                                $query->where('name', 'like', $searchTerm)
                                    ->orWhere('price', 'like', $searchTerm)
                                    ->orWhere('interval', 'like', $searchTerm);
                            });
                    });
                }
            } else {
                // When search term is "all", filter the data.
                $coupons = $coupons->where(function ($query) {
                    $query->where('type', '!=', 'period_off')
                        ->whereDoesntHave('products');
                });
            }
        }
        if ($params['items']) {
            $limit = intval($params['items']); // Convert the 'items' parameter to an integer
            $coupons = $coupons->limit($limit);
        }
        if ($params['sort'] === 'asc') {
            $orderByDirection = 'asc';
        } elseif ($params['sort'] === 'desc') {
            $orderByDirection = 'desc';
        }

        $orderBy = $params['orderBy'] ?? 'created_at'; // Default value if 'orderBy' parameter is not present
        
        if ($params['orderBy'] === 'type') {
            $coupons = $coupons->orderBy('type', $orderByDirection) // Order by type first.
                ->orderByRaw("CASE
                    WHEN type = 'percent_off' THEN percent_off
                    WHEN type = 'amount_off' THEN amount_off
                    WHEN type = 'period_off' THEN period_off
                    ELSE 0
                    END " . $orderByDirection); // Then order by value.
        } elseif ($params['orderBy'] === 'product_name') {
            $coupons = $coupons->leftJoin('coupon_product', 'coupons.id', '=', 'coupon_product.coupon_id')
                               ->leftJoin('products', 'coupon_product.product_id', '=', 'products.id')
                               ->select('coupons.*', 'products.name as product_name')
                               ->orderBy('product_name', $orderByDirection);
        } else {
            $coupons = $coupons->orderBy($orderBy, $orderByDirection);
        }

        $coupons = $coupons->paginate($limit);

        return json_encode($coupons, true);
    }

    public function create(Request $request) : Response
    {
        return Inertia::render('Admin/Coupons/Create', [
            'products' => Product::active()->get(),
        ]);
    }

    public function store(StoreRequest $request)
    {
        $secretKey = config('cashier.secret');
        $stripe = new StripeClient($secretKey);

        try {
            $params = [
                'name' => $request->name,
                'duration' => $request->duration,
                'currency' => 'usd',
            ];

            // custom code
            if ($request->id) {
                $params = Arr::prepend($params, $request->id, 'id');
            }

            // discount
            $off = $request->type === 'percent_off' ? $request->percent_off : $request->discount_amount * 100;
            $params = Arr::prepend($params, (int) $off, $request->type);

            if ($request->duration === 'repeating') {
                $params = Arr::prepend($params, (int) $request->number_of_months, 'duration_in_months');
            }

            // product specific coupon
            if ($request->applies_to_checkbox) {
                $appliesTo['products'][] = $request->applies_to;
                $params = Arr::prepend($params, $appliesTo, 'applies_to');
            }

            /** @return \Stripe\Coupon */
            $stripeCoupon = $stripe->coupons->create($params);

            $coupon = new Coupon();
            $coupon->code = $stripeCoupon->id;
            $coupon->name = $stripeCoupon->name;
            $coupon->type = $stripeCoupon->percent_off != null ? 'percent_off' : 'amount_off';
            $coupon->percent_off = $stripeCoupon->percent_off ?? null;
            $coupon->amount_off = $stripeCoupon->amount_off / 100 ?? null;
            $coupon->duration = $stripeCoupon->duration;
            $coupon->times_redeemed = $stripeCoupon->times_redeemed;
            $coupon->save();

            if ($request->applies_to_checkbox) {
                $product = Product::where('stripe_product', $request->applies_to)->first();

                if ($product) {
                    $pivot = new CouponProduct();
                    $pivot->coupon_id = $coupon->id;
                    $pivot->product_id = $product->id;
                    $pivot->save();
                }
            }

        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            Log::error($th->getTraceAsString());

            // return FacadesResponse::json(['success' => false, 'message' => 'Something went wrong.']);
            throw ValidationException::withMessages(['name' => 'Something went wrong']);
        }

        return Redirect::to('/admin/coupons/index');
    }

    public function storePeriodOff(StorePeriodOffRequest $request)
    {
        // generate id if not specified
        if ($request->id == '' || $request->id == null) {
            $request->merge(['id' => Coupon::generateUniqueCouponCode()]);
        }

        $coupon = new Coupon;
        $coupon->code = $request->id;
        $coupon->name = $request->name;
        $coupon->type = 'period_off';
        $coupon->period_off = $request->period_off;
        $coupon->duration = 'once';
        $coupon->save();

        return Redirect::to('/admin/coupons/index');
    }

    public function destroy(Coupon $coupon)
    {
        $secretKey = config('cashier.secret');
        $stripe = new StripeClient($secretKey);

        try {
            /** @return \Stripe\Coupon */
            $stripeCoupon = $stripe->coupons->delete($coupon->code);
            $coupon->delete();

        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            return FacadesResponse::json(['success' => false, 'message' => 'Something went wrong.']);
        }

        return FacadesResponse::json(['success' => true, 'coupons' => Coupon::all()]);
    }

    public function validateCoupon(Request $request)
    {
        $product = Product::find($request->plan_id);
        $coupon = Coupon::firstWhere('code', $request->coupon);

        $validator = Validator::make($request->all(), [
            'plan_id' => 'required|exists:products,id',
            'coupon' => 'nullable|exists:coupons,code',
        ], [
            'coupon.exists' => 'Coupon does not exists.'
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        if ($coupon && ($coupon->type === 'period_off' && $product->interval !== 'year')) {
            throw ValidationException::withMessages(['coupon' => 'Coupon is only valid for annual plans.']);
        }

        return FacadesResponse::json(['success' => true]);
    }
}
