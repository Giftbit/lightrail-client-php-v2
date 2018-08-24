<?php

namespace Lightrail;

class LightrailShopperTokenFactory
{
    /**
     * Generate a shopper token that can be used to make Lightrail calls
     * restricted to that particular shopper.  The shopper can be defined by the
     * contactId, or an empty string for anonymous.
     *
     * eg: `generateShopperToken("user-12345");`
     * eg: `generateShopperToken("user-12345", array("validityInSeconds" => 43200);`
     *
     * @param $contactId string The ID of the contact
     * @param $options array A GenerateShopperTokenOptions object
     *
     * @return $shopperToken string The shopper token
     *
     * @throws \Exception if library is not properly configured (with API key & shared secret) or if missing parameter
     */
    public static function generate($contactId, $options = array())
    {
        if ( ! isset(Lightrail::$apiKey) || empty(Lightrail::$apiKey)) {
            throw new \Exception("Lightrail.apiKey is empty or not set.");
        }
        if ( ! isset(Lightrail::$sharedSecret) || empty(Lightrail::$sharedSecret)) {
            throw new \Exception('Lightrail.sharedSecret is not set.');
        }

        if (isset($contactId)) {
            $g = array('coi' => $contactId);
        } else {
            throw new \Exception("Must provide a contact ID or an empty string for anonymous");
        }

        $validityInSeconds = 43200;
        $metadata          = null;
        if (is_array($options)) {
            if (isset($options['validityInSeconds'])) {
                $validityInSeconds = $options['validityInSeconds'];
            }
            if (isset($options['metadata'])) {
                $metadata = $options['metadata'];
            }
        }

        if ($validityInSeconds <= 0) {
            throw new \Exception("validityInSeconds must be > 0");
        }

        $payload = explode('.', Lightrail::$apiKey);
        $payload = json_decode(base64_decode($payload[1]), true);

        $iat   = time();
        $token = array(
            'g'     => array(
                           'gui' => $payload['g']['gui'],
                           'gmi' => $payload['g']['gmi'],
                           'tmi' => $payload['g']['tmi'],
                       ) + $g,
            'iat'   => $iat,
            'exp'   => $iat + $validityInSeconds,
            'iss'   => "MERCHANT",
            'roles' => ['shopper'],
        );
        if ( ! is_null($metadata)) {
            $token['metadata'] = $metadata;
        }

        $jwt = \Firebase\JWT\JWT::encode($token, Lightrail::$sharedSecret, 'HS256');

        return $jwt;
    }
}
