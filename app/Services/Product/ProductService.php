<?php

namespace App\Services\Product;
use App\Models\Product;


class ProductService
{
    public static function getPlan($noOfPages)
    {
        $plans = [];
        if ($noOfPages <= 1000) {
            return self::getCopperPlan();
        } else if ($noOfPages >= 1000 && $noOfPages <= 10000) {
            return self::gerBronzePlan();
        } else if ($noOfPages >= 10001 && $noOfPages <= 100000) {
            return self::getSilverPlan();
        } else if ($noOfPages >= 100001 && $noOfPages <= 500000) {
            return self::getGoldPlan();
        } else if ($noOfPages >= 500001 && $noOfPages <= 1000000) {
            return self::getDiamondPlan();
        } else if ($noOfPages >= 1000001) {
            return self::getPlatinumPlan();
        } else {
            return self::getCopperPlan();
        }

        return self::getCopperPlan();
    }

    private  static function getCopperPlan()
    {
        $plans = Product::active()
                ->whereIn('slug', ['monthly-copper', 'yearly-copper'])
                ->orderBy('price')->get();

        return $plans;
    }

    private static function gerBronzePlan()
    {
        $plans = Product::active()
                ->whereIn('slug', ['monthly-bronze', 'yearly-bronze'])
                ->orderBy('price')->get();

        return $plans;
    }

    private static function getSilverPlan()
    {
        $plans = Product::active()
                ->whereIn('slug', ['monthly-silver', 'yearly-silver'])
                ->orderBy('price')->get();

        return $plans;
    }

    private static function getGoldPlan()
    {
        $plans = Product::active()
                ->whereIn('slug', ['monthly-gold', 'yearly-gold'])
                ->orderBy('price')->get();

        return $plans;
    }

    private static function getDiamondPlan()
    {
        $plans = Product::active()
                ->whereIn('slug', ['monthly-diamond', 'yearly-diamond'])
                ->orderBy('price')->get();

        return $plans;
    }

    private static function getPlatinumPlan()
    {
        $plans = Product::active()
                ->whereIn('slug', ['monthly-platinum', 'yearly-platinum'])
                ->orderBy('price')->get();

        return $plans;
    }

}