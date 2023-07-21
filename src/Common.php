<?php

use Firebase\JWT\CachedKeySet;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\HttpFactory;
use Michalsn\CodeIgniterPassage\Config\Passage;
use Phpfastcache\CacheManager;

/**
 * Fetches the JWKS (JSON Web Key Set) from the provided URL.
 *
 * @param string $url The URL to fetch the JWKS from.
 *
 * @return CachedKeySet The JWKS data.
 */
function createRemoteJWKSet($url): CachedKeySet
{
    // Create an HTTP client (can be any PSR-7 compatible HTTP client)
    $httpClient = new Client();

    // Create an HTTP request factory (can be any PSR-17 compatible HTTP request factory)
    $httpFactory = new HttpFactory();

    // Create a cache item pool (can be any PSR-6 compatible cache item pool)
    $cacheItemPool = CacheManager::getInstance('files');

    return new CachedKeySet(
        $url,
        $httpClient,
        $httpFactory,
        $cacheItemPool,
        null, // $expiresAfter int seconds to set the JWKS to expire
        true  // $rateLimit    true to enable rate limit of 10 RPS on lookup of invalid keys
    );
}

/**
 * Return Passage Id for current context.
 */
function passageId(?string $id = null)
{
    $config = config(Passage::class);

    if ($id === null) {
        return $config->getUser();
    }

    $config->setUser($id);
}
