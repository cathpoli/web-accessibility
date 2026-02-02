<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Mail\FeedbackMail;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class FeedbackController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('Client/Feedback');
    }

    public function send(Request $request)
    {
        $request->validate([
            'email' => 'required|email:rfc,dns',
            'subject' => 'required|max:255',
            'body' => 'required|max:255',
            'recaptcha_token' => 'required',
        ], [
            'recaptcha_token.required' => 'Please verify that you are not a robot.'
        ]);

        try {
            $recipient = config('mail.from.address');
            Mail::to($recipient)->send(new FeedbackMail($request->subject, $request->body, $request->email));

            return Redirect::to('/feedback');

        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            throw ValidationException::withMessages(['message' => 'Something went wrong.',]);
        }
    }
}
