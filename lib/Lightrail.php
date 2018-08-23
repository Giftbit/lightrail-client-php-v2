<?php

namespace Lightrail;

class Lightrail
{
    public static $apiKey;
    public static $sharedSecret;

    public static $API_BASE = 'https://api.lightrail.com/v1/';

    public static function setSharedSecret($theSharedSecret)
    {
        self::$sharedSecret = $theSharedSecret;
    }

    public static function setApiKey($theApiKey)
    {
        self::$apiKey = $theApiKey;
    }

    public static function checkApiKey()
    {
        if ( ! isset(self::$apiKey)) {
            throw new Exception('Lightrail::$apiKey not set.');
        }
        if (empty(self::$apiKey)) {
            throw new Exception('Lightrail::$apiKey is empty.');
        }
    }
}
