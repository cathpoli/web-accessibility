<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use App\Models\Domain;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Rules\ValidateDomainName;
use App\Rules\ValidateIfHasScheme;
use Inertia\Inertia;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;

class ShopifyController extends Controller
{
    // The URL where Shopify will redirect after app installation
    public function install(Request $request)
    {
        try {
            $this->validate($request, [
                "shop" => [
                    'bail',
                    'required',
                    new ValidateIfHasScheme,
                    new ValidateDomainName,
                    'regex:/^([a-zA-Z0-9]([-a-zA-Z0-9]{0,61}[a-zA-Z0-9])?\.)?([a-zA-Z0-9]{1,2}([-a-zA-Z0-9]{0,252}[a-zA-Z0-9])?)\.([a-zA-Z]{2,63})$/',
                    'unique:domains,name',
                ],
            ]);

            // Extract the shop domain from the request
            $shopDomain = $request->input('shop');
            $scopes = 'read_orders,write_products';
            $redirectUri = env('APP_URL') . "/shopify/generate_token";
            
            // Build install/approval URL to redirect to
            $installUrl = "https://" . $shopDomain . "/admin/oauth/authorize?client_id=" . env('SHOPIFY_CLIENT_ID') . "&scopes=" . $scopes . "&redirect_uri=" . urlencode($redirectUri);

            header("Location: " . $installUrl);

            die();
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->errors();
            $result['message'] = "Input Error: " . (string) reset($errors)[0];
            return response()->json($result, 400);
        } catch (\Exception $e) {
            report($e);
            $result['message'] = $e->getMessage();
        }

        return response()->json($result, 500);
    }

    public function generateToken(Request $request)
    {
        try {
            $this->validate($request, [
                "shop" => [
                    'bail',
                    'required',
                    new ValidateIfHasScheme,
                    new ValidateDomainName,
                    'regex:/^([a-zA-Z0-9]([-a-zA-Z0-9]{0,61}[a-zA-Z0-9])?\.)?([a-zA-Z0-9]{1,2}([-a-zA-Z0-9]{0,252}[a-zA-Z0-9])?)\.([a-zA-Z]{2,63})$/',
                    'unique:domains,name',
                ],
                "code" => 'required',
            ]);

            // Extract the shop domain from the request
            $shopDomain = $request->input('shop');

            // Make an API request to Shopify to get an access token
            // You'll need the API key and API secret obtained during app creation
            $response = Http::post("https://$shopDomain/admin/oauth/access_token", [
                'client_id' => env('SHOPIFY_CLIENT_ID'),
                'client_secret' => env('SHOPIFY_CLIENT_SECRET'),
                'code' => $request->input('code'),
            ]);

            $accessToken = $response->json()['access_token'];

            $shopInfo = $this->getShopInfo($shopDomain, $accessToken);

            $user = User::create([
                'name'              => $shopInfo['shop_owner'],
                'email'             => $shopInfo['customer_email'],
                'billing_address'   => $shopInfo['address1'],
                'billing_address_2' => $shopInfo['address2'],
                'billing_address_3' => $shopInfo['address2'],
                'city'              => $shopInfo['city'],
                'state'             => null,
                'zip'               => $shopInfo['zip'],
                'country'           => $shopInfo['country'],
                'phone_number'      => null,
                'password'          => Hash::make(Str::random(10)),
            ]);

            $domain = Domain::create();
            $domain->name = Domain::formatHostname($shopDomain);
            $domain->user_id = $user->id;
            $domain->save();

            event(new Registered($user));

            Auth::login($user);

            return redirect(RouteServiceProvider::HOME);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->errors();
            $result['message'] = "Input Error: " . (string) reset($errors)[0];
            return response()->json($result, 400);
        } catch (\Exception $e) {
            report($e);
            $result['message'] = $e->getMessage();
        }

        // return response()->json($result, 500);
    }

    // Handle Shopify webhooks
    public function webhook(Request $request)
    {
        // Verify the webhook's authenticity by checking the HMAC header
        $hmacHeader = $request->header('X-Shopify-Hmac-SHA256');
        $data = $request->getContent();
        $calculatedHmac = hash_hmac('sha256', $data, env('SHOPIFY_API_SECRET'));

        if ($hmacHeader !== $calculatedHmac) {
            return response('Unauthorized', 401);
        }

        // Handle the webhook data
        // You can create a user or perform any other actions based on the webhook data

        return response('Webhook received', 200);
    }

    private function getShopInfo($shopDomain, $accessToken)
    {

        // Define the Shopify API endpoint for fetching shop data
        $apiEndpoint = "https://$shopDomain/admin/api/2021-10/shop.json"; // Use the appropriate API version

        // Make an authenticated API request to Shopify
        $response = Http::withHeaders([
            'X-Shopify-Access-Token' => $accessToken,
        ])->get($apiEndpoint);

        // Check if the request was successful
        if ($response->successful()) {
            $shopInfo = $response->json()['shop'];

            // Process or display the shop information as needed
            return $shopInfo;
        } else {
            // Handle the error if the request was not successful
            return [];
        }
    }

    public function redirect(Request $request)
    {
        // Verify the webhook's authenticity by checking the HMAC header
        $hmacHeader = $request->header('X-Shopify-Hmac-SHA256');
        $data = $request->getContent();

        dd($data);
    }
}
