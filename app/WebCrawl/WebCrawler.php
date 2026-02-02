<?php

namespace App\WebCrawl;

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

        //dd($doc);

        $title = $doc->getElementsByTagName('title')->item(0)->nodeValue;

        $headings = [];
        $headingElements = $doc->getElementsByTagName('h1');
        $buttonElements = $doc->getElementsByTagName('button');
        
        foreach ($buttonElements as $buttonElement) {
            //$headings[] = $buttonElement->attributes;
            //dd($buttonElement);
        }

        return [
            'html' => $html,
        ];
    }
}