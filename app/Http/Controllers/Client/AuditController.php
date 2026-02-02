<?php
namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\WebCrawl\WebCrawler;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Barryvdh\DomPDF\Facade\Pdf;

use function Termwind\render;
class AuditController extends Controller
{
    public function index()
    {
        return Inertia::render('Client/Audit/Index');
    }

    public function crawl(Request $request)
    {
        $url = $request->url;
        $crawler = new WebCrawler();
        $data = $crawler->crawl($url);

        return response()->json($data);
    }

    public function generateAuditPDF($domain)
    {
        $data = [
            'domain' => $domain,
        ];

        $pdf = Pdf::loadView('pdf.audit-report', $data);

        return $pdf->stream("Audit PDF Report - {$domain}.pdf");
        // return $pdf->download("Audit PDF Report - {$domain}.pdf", [
        //     'Content-Type' => 'application/pdf',
        //     'Content-Disposition' => "attachment; filename='Audit PDF Report - {$domain}.pdf'",
        // ]);
    }
}