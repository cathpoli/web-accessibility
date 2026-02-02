<x-mail::message>
<h2 style="text-align: center; font-size: 19px; padding-bottom: 10px; font-family: Arial, Helvetica, sans-serif;">INVOICE PAYMENT SUCCESSFULLY</h2>

<p style="font-family: Arial, Helvetica, sans-serif;">Invoice No. <b>{{ $invoiceNumber }}</b> has been successfully paid. Current subscription period will end on {{ \Carbon\Carbon::parse($periodEnd)->format('jS \of F Y h:i A') }} ({{ $timezone }}).</p>

<p style="font-family: Arial, Helvetica, sans-serif;">To continue using AceADA, just click the link below.<br />
Thank you!
</p>

<a style="font-family: Arial, Helvetica, sans-serif; border-radius: 5px; text-decoration: none; color: white; font-weight: bold; text-align: center;margin: 20px 0; width: 100%; background-color: #1AD2C7; padding: 10px; display: block;" href="{{ config('app.url') }}">CONTINUE</a>

<p style="font-family: Arial, Helvetica, sans-serif;">Regards,<br />
<b>AceADA</b>
</p>
</x-mail::message>
