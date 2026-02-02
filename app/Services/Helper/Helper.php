<?php

namespace App\Services\Helper;
use SimpleXMLElement;
use Illuminate\Support\Facades\Log;


class Helper
{
    public static function getSitePageCount($domain)
    {
        try {
            $content = file_get_contents('https://' . $domain .'/sitemap.xml');
            $siteMaps = new SimpleXmlElement($content);
            $pageCount = 0;
            foreach ($siteMaps as $site) {
                $loc = (array)$site->loc;
                $url = $loc[0];
                $pages = new SimpleXmlElement(file_get_contents($url));
                $pageCount += $pages->count();
            }

            return (int)$pageCount;
        } catch (\Throwable $th) {
            Log::error('Something went wrong accessing the sitemap of ' . $domain);

            return 0;
        }
    }

    public static function getPlanPages()
    {
        return [
            'yearly-copper' => ['from' => 0, 'to' => 1000],
            'monthly-copper' => ['from' => 0, 'to' => 1000],
            'yearly-bronze' => ['from' => 1001, 'to' => 10000],
            'monthly-bronze' => ['from' => 1001, 'to' => 10000],
            'yearly-silver' => ['from' => 10001, 'to' => 100000],
            'monthly-silver' => ['from' => 10001, 'to' => 100000],
            'yearly-gold' => ['from' => 100001, 'to' => 500000],
            'monthly-gold' => ['from' => 100001, 'to' => 500000],
            'yearly-diamond' => ['from' => 500001, 'to' => 1000000],
            'monthly-diamond' => ['from' => 500001, 'to' => 1000000],
            'yearly-platinum' => ['from' => 1000001, 'to' => 10000000],
            'monthly-platinum' => ['from' => 1000001, 'to' => 10000000],
        ];
    }

    public static function getOptimalPlan($plan)
    {
        switch ($plan) {
            case 'copper':
                return 'bronze';
                break;
            case 'bronze':
                return 'silver';
                break;
            case 'silver':
                return 'gold';
                break;
            case 'gold':
                return 'platinum';
                break;
            
            default:
                return null;
                break;
        }
    }
}