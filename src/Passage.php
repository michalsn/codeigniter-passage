<?php

namespace Michalsn\CodeIgniterPassage;

use CodeIgniter\HTTP\IncomingRequest;
use Exception;
use Firebase\JWT\CachedKeySet;
use Firebase\JWT\JWT;
use Michalsn\CodeIgniterPassage\Config\Passage as PassageConfig;
use Michalsn\CodeIgniterPassage\Exceptions\PassageException;

class Passage
{
    /**
     * The Passage API URL.
     */
    private string $authURL = 'https://auth.passage.id/v1/apps/';

    /**
     * The Passage API URL.
     */
    private string $apiURL = 'https://api.passage.id/v1/apps/';

    /**
     * The Passage application ID.
     */
    private string $appId;

    /**
     * The Passage API key.
     */
    private string $apiKey;

    /**
     * The JWKS (JSON Web Key Set) for authentication.
     */
    private CachedKeySet $jwks;

    /**
     * The authentication strategy.
     */
    private string $authStrategy;

    /**
     * The User object for accessing User-related functionality.
     *
     * @var User
     */
    public $user;

    /**
     * Create a new Passage instance.
     */
    public function __construct(private PassageConfig $config)
    {
        // Store the app ID and API key in private variables
        $this->appId  = $this->config->appId;
        $this->apiKey = $this->config->apiKey;

        // Initialize the JWKS URL and authentication strategy
        $this->authStrategy = strtoupper($this->config->authStrategy);

        // Initialize the User object
        $this->user = new User($this->appId, $this->apiKey);

        $jwksUrl    = sprintf('%s%s/.well-known/jwks.json', $this->authURL, $this->appId);
        $this->jwks = createRemoteJWKSet($jwksUrl);
    }

    /**
     * Get App Info about an app
     *
     * @return array Passage App object
     */
    public function getApp(): array
    {
        // Construct the URL for the Passage API endpoint
        $url = $this->apiURL . $this->appId;

        // Set the headers for the API request
        $headers = [
            'Authorization' => 'Bearer ' . $this->apiKey,
        ];

        // Send the HTTP GET request to the Passage API
        $response = service('curlrequest')->request('get', $url, ['headers' => $headers]);

        // Check if the request was successful
        if ($response->getStatusCode() === 200) {
            $responseData = json_decode($response->getBody(), true);

            return $responseData['app'];
        }

        // Throw a PassageError or handle the failure as needed
        throw PassageException::forCouldNotFetchApp();
    }

    /**
     * Create a magic link for user authentication.
     *
     * @return array The generated magic link data
     */
    public function createMagicLink(string $email, string $redirectUrl): array
    {
        // Construct the URL for the Passage API endpoint
        $url = $this->apiURL . $this->appId . '/magic-links';

        // Set the headers for the API request
        $headers = [
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type'  => 'application/json',
        ];

        // Prepare the data payload for the API request
        $data = [
            'email'        => $email,
            'redirect_url' => $redirectUrl,
        ];

        // Send the HTTP POST request to the Passage API
        $response = service('curlrequest')->request('post', $url, ['headers' => $headers, 'json' => $data]);

        // Extract the 'magic_link' key from the JSON response
        $responseData = json_decode($response->getBody(), true);

        return $responseData['magic_link'];
        // Return the magic link
    }

    /**
     * Authenticate request with a cookie, or header. If no authentication
     * strategy is given, authenticate the request via cookie (default
     * authentication strategy).
     *
     * @param IncomingRequest $request Laravel request
     *
     * @return string UserID of the Passage user
     */
    public function authenticateRequest(IncomingRequest $request): string
    {
        if ($this->authStrategy === 'HEADER') {
            return $this->authenticateRequestWithHeader($request);
        }

        return $this->authenticateRequestWithCookie($request);
    }

    /**
     * Authenticate a request via the HTTP header.
     *
     * @param IncomingRequest $request CodeIgniter request
     *
     * @return string User ID for Passage User
     */
    private function authenticateRequestWithHeader(IncomingRequest $request): string
    {
        $authorization = $request->header('Authorization');
        if (! $authorization) {
            throw PassageException::forHeaderAuthorizationNotFound();
        }
        $authToken = explode(' ', $authorization)[1];
        $userId    = $this->validAuthToken($authToken);
        if ($userId) {
            return $userId;
        }

        throw PassageException::forInvalidAuthToken();
    }

    /**
     * Authenticate request via cookie.
     *
     * @param IncomingRequest $request The HTTP request object
     *
     * @return string User ID for Passage User
     */
    private function authenticateRequestWithCookie(IncomingRequest $request): string
    {
        $passageAuthToken = $request->getCookie('psg_auth_token');

        if ($passageAuthToken) {
            $userID = $this->validAuthToken($passageAuthToken);

            if ($userID) {
                return $userID;
            }

            throw PassageException::forInvalidAuthToken();
        }

        throw PassageException::forCookieForAuthorizationNotFound();
    }

    /**
     * Determine if the provided token is valid when compared with its
     * respective public key.
     *
     * @param string $token The authentication token
     *
     * @return string|null The sub claim if the JWT can be verified, or null
     */
    private function validAuthToken($token): ?string
    {
        try {
            $decodedHeader = JWT::urlsafeB64Decode(explode('.', $token)[0]);
            $header        = json_decode($decodedHeader, true);
            $kid           = $header['kid'];

            if (! $kid) {
                // If the 'kid' is missing, the token cannot be verified
                return null;
            }

            $decodedToken = JWT::decode($token, $this->jwks);
            $userId       = $decodedToken->sub;

            if ($userId) {
                // If the 'sub' claim exists, return it as a string
                return (string) $userId;
            }

            // If the 'sub' claim is missing, the token cannot be verified
            return null;
        } catch (Exception) {
            // An exception occurred during token verification
            return null;
        }
    }

    public function refreshToken(string $refreshToken)
    {
        // Construct the URL for the Passage API endpoint
        $url = $this->apiURL . $this->appId . '/tokens';

        // Set the headers for the API request
        $headers = [
            'Content-Type' => 'application/json',
        ];

        // Create the request payload
        $payload = [
            'refresh_token' => $refreshToken,
        ];

        // Send the HTTP POST request to the Passage API
        $response = service('curlrequest')->request('post', $url, ['headers' => $headers, 'json' => $payload]);

        // Check if the request was successful
        if ($response->getStatusCode() === 201) {
            $responseData = json_decode($response->getBody(), true);

            return $responseData['auth_result'];
        }

        // Throw a PassageException or handle the failure as needed
        throw PassageException::forFailedToRefreshToken();
    }
}
