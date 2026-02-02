<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Illuminate\Support\Str;

class AccountController extends Controller
{
    public function index(Request $request)
    {
        $auth = Auth::user();

        // Request URL params query
        $params = $request->query();
        $limit = $request->query('size', 10); // Retrieve the value of 'size' parameter from the request query parameters. Default to 10 if not present.
        $orderBy = $params['orderBy'] ?? 'created_at'; // Default value if 'orderBy' parameter is not present
        $orderByDirection = $params['sort'] ?? 'desc'; // Default value if 'orderBy' parameter is not present

        $members = $auth->members();

        if ($params && isset($params['status']) && ($params['status'] == 1 || $params['status'] == 0)) {
            $members->where('status', $params['status']);
        }

        if ($params && isset($params['sort']) && ($params['sort'] == 'asc' || $params['sort'] == 'desc')) {
            $members->orderBy($orderBy, $orderByDirection);
        }

        $members = $members->paginate($limit);

        return Inertia::render('Client/Account/Index', [
            'members' => $members,
            'status' => session('status'),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email:rfc,dns|max:255|unique:users,email,NULL,id,deleted_at,NULL:'.User::class, // rfc,dns reference: https://laravel.com/docs/9.x/validation#rule-email
        ]);

        // Check if the email is deleted, if it is deleted just update the data otherwise insert the data
        $user = User::onlyTrashed()
                ->where('email', $request->email)
                ->first();

        if ($user !== null) {
            $user->update([
                'parent_id' => Auth::user()->id,
                'type' => 'member',
                'name' => $request->name,
                'password' => Hash::make(Str::random(16)),
                'deleted_at' => NULL,
                'status' => 1,
            ]);
        } else {
            $user = User::create([
                'parent_id' => Auth::user()->id,
                'type' => 'member',
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make(Str::random(16)),
                'status' => 1,
            ]);
        }

        //$user->sendEmailVerificationNotification();

        //return redirect()->back()->with('status', 'Team member has been created successfully! Verification email has been sent.');
    }

    public function destroy(User $user, Request $request)
    {
        //Update status
        User::where('id', $request->id)->update(['status' => 0]);

        // Delete User
        $user = User::findOrFail($request->id);
        $user->delete();
    }

    public function updateMemberStatus(Request $request)
    {
        //Update status
        User::where('id', $request->id)->update(['status' => $request->status == 1 ? 0 : 1]);
    }
}