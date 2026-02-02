<?php
namespace App;

use GuzzleHttp\Client;
use DOMDocument;

class WebCrawler
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function crawl($url)
    {
        try {
            $response = $this->client->get($url);
            $html = (string) $response->getBody();

            $data = $this->extractData($html);

            return $data;
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    protected function extractData($html)
    {
        $doc = new DOMDocument();
        @$doc->loadHTML($html); // Suppress warnings

        $title = $doc->getElementsByTagName('title')->item(0)->nodeValue;

        $headings = [];
        $headingElements = $doc->getElementsByTagName('h2');
        
        foreach ($headingElements as $headingElement) {
            $headings[] = $headingElement->nodeValue;
        }

        return [
            'title' => $title,
            'headings' => $headings,
        ];
    }
}
