<?php

namespace App\Http\Controllers\Client\Billing;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Inertia\Inertia;

class InvoiceController extends Controller
{
    public function show(Invoice $invoice, Request $request)
    {
        $user = $request->user();

        /** @return \Laravel\Cashier\Invoice */
        $invoice = $user->findInvoice($invoice->stripe_invoice);

        /** @return \Stripe\Invoice */
        // $stripeInvoice = $invoice->asStripeInvoice();

        return Inertia::render('Client/Billing/Invoices/Show', [
            'invoice' => $invoice
        ]);
    }

    public function download(Invoice $invoice, Request $request)
    {
        $user = $request->user();

        if (empty($invoice->getOriginal())) {
            $invoice = Invoice::where('stripe_invoice', $request->stripe_invoice_id)->first();
            
            /** @return \Symfony\Component\HttpFoundation\Response */
            return $user->downloadInvoice($invoice->stripe_invoice, [], $invoice->number);
        } else {
            /** @return \Symfony\Component\HttpFoundation\Response */
            return $user->downloadInvoice($invoice->stripe_invoice, [], $invoice->number);
        }  
    }
}
