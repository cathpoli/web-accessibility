<?php

return [
    /**
     * Puppeteer navigation timeout
     *
     * References:
     * https://spatie.be/docs/browsershot/v2/miscellaneous-options/setting-the-timeout
     * https://pptr.dev/api/puppeteer.waittimeoutoptions/#properties
     */
    'timeout' => env('BROWSERSHOT_TIMEOUT', 120),
];
