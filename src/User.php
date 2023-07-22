<?php

namespace Michalsn\CodeIgniterPassage;

use CodeIgniter\I18n\Time;
use Exception;
use Michalsn\CodeIgniterPassage\Exceptions\PassageException;

class User
{
    /**
     * The Passage API URL.
     */
    private string $apiURL = 'https://api.passage.id/v1/apps/';

    /**
     * User constructor.
     *
     * @param string $appId  The Passage application ID.
     * @param string $apiKey The Passage API key.
     */
    public function __construct(private string $appId, private string $apiKey)
    {
    }

    /**
     * Retrieve the list of devices for a user.
     *
     * @param string $userId The user ID.
     *
     * @return array The list of user devices.
     */
    public function listDevices(string $userId): array
    {
        // Construct the URL for the Passage API endpoint
        $url = $this->apiURL . $this->appId . '/users/' . $userId . '/devices';

        // Set the headers for the API request
        $headers = [
            'Authorization' => 'Bearer ' . $this->apiKey,
        ];

        // Send the HTTP GET request to the Passage API
        $response = service('curlrequest')->request('get', $url, ['headers' => $headers]);

        // Extract the 'devices' array from the JSON response
        $responseData = json_decode($response->getBody(), true);

        return $responseData['devices'];

        // Return the list of user devices
    }

    /**
     * Revoke a device for a user.
     *
     * @param string $userId   The user ID.
     * @param string $deviceId The device ID.
     *
     * @return bool True if the device revocation was successful; otherwise, false.
     */
    public function revokeDevice(string $userId, string $deviceId): bool
    {
        // Construct the URL for the Passage API endpoint
        $url = $this->apiURL . $this->appId . '/users/' . $userId . '/devices/' . $deviceId;

        // Set the headers for the API request
        $headers = [
            'Authorization' => 'Bearer ' . $this->apiKey,
        ];

        // Send the HTTP DELETE request to the Passage API
        $response = service('curlrequest')->request('delete', $url, ['headers' => $headers]);

        // Check if the request was successful
        if ($response->getStatusCode() === 200) {
            return true;
        }
        // Throw a PassageException or handle the failure as needed
        throw PassageException::forFailedToRevokeDevice();

    }

    /**
     * Revoke a device for a user.
     *
     * @param string $userId The user ID.
     *
     * @return bool True if the sign-out was successful; otherwise, false.
     */
    public function signOut(string $userId): bool
    {
        // Construct the URL for the Passage API endpoint
        $url = $this->apiURL . $this->appId . '/users/' . $userId . '/tokens';

        // Set the headers for the API request
        $headers = [
            'Authorization' => 'Bearer ' . $this->apiKey,
        ];

        // Send the HTTP DELETE request to the Passage API
        $response = service('curlrequest')->request('delete', $url, ['headers' => $headers]);

        // Check if the request was successful
        if ($response->getStatusCode() === 200) {
            return true;
        }
        // Throw a PassageException or handle the failure as needed
        throw PassageException::forFailedToRevokeRefreshTokens();

    }

    /**
     * Get information about a user.
     *
     * @param string $userId The user ID.
     *
     * @return array The user information.
     *
     * @throws Exception
     */
    public function get(string $userId): array
    {
        // Construct the URL for the Passage API endpoint
        $url = $this->apiURL . $this->appId . '/users/' . $userId;

        // Set the headers for the API request
        $headers = [
            'Authorization' => 'Bearer ' . $this->apiKey,
        ];

        // Send the HTTP GET request to the Passage API
        $response = service('curlrequest')->request('get', $url, ['headers' => $headers]);

        // Check if the request was successful
        if ($response->getStatusCode() === 200) {
            $responseData = json_decode($response->getBody(), true);
            $user         = $responseData['user'];

            // Parse created_at and last_login_at fields into Time objects
            $user['created_at']    = Time::parse($user['created_at']);
            $user['last_login_at'] = Time::parse($user['last_login_at']);

            return $user;
        }
        // Throw a PassageException or handle the failure as needed
        throw PassageException::forFailedToRetrieveUserInformation();
    }

    /**
     * Deactivate a user.
     *
     * @param string $userId The user ID.
     *
     * @return array The deactivated user information.
     *
     * @throws Exception
     */
    public function deactivate(string $userId): array
    {
        // Construct the URL for the Passage API endpoint
        $url = $this->apiURL . $this->appId . '/users/' . $userId . '/deactivate';

        // Set the headers for the API request
        $headers = [
            'Authorization' => 'Bearer ' . $this->apiKey,
        ];

        // Send the HTTP PATCH request to the Passage API
        $response = service('curlrequest')->request('patch', $url, ['headers' => $headers]);

        // Check if the request was successful
        if ($response->getStatusCode() === 200) {
            $responseData = json_decode($response->getBody(), true);
            $user         = $responseData['user'];

            // Parse created_at and last_login_at fields into Time objects
            $user['created_at']    = Time::parse($user['created_at']);
            $user['last_login_at'] = Time::parse($user['last_login_at']);

            return $user;
        }
        // Throw a PassageException or handle the failure as needed
        throw PassageException::forFailedToDeactivateUser();

    }

    /**
     * Activate a user.
     *
     * @param string $userId The user ID.
     *
     * @return array The activated user information.
     *
     * @throws Exception
     */
    public function activate(string $userId): array
    {
        // Construct the URL for the Passage API endpoint
        $url = $this->apiURL . $this->appId . '/users/' . $userId . '/activate';

        // Set the headers for the API request
        $headers = [
            'Authorization' => 'Bearer ' . $this->apiKey,
        ];

        // Send the HTTP PATCH request to the Passage API
        $response = service('curlrequest')->request('patch', $url, ['headers' => $headers]);

        // Check if the request was successful
        if ($response->getStatusCode() === 200) {
            $responseData = json_decode($response->getBody(), true);
            $user         = $responseData['user'];

            // Parse created_at and last_login_at fields into Time objects
            $user['created_at']    = Time::parse($user['created_at']);
            $user['last_login_at'] = Time::parse($user['last_login_at']);

            return $user;
        }
        // Throw a PassageException or handle the failure as needed
        throw PassageException::forFailedToActivateUser();

    }

    /**
     * Delete a user.
     *
     * @param string $userId The user ID.
     *
     * @return bool True if the user deletion was successful; otherwise, false.
     */
    public function delete(string $userId): bool
    {
        // Construct the URL for the Passage API endpoint
        $url = $this->apiURL . $this->appId . '/users/' . $userId;

        // Set the headers for the API request
        $headers = [
            'Authorization' => 'Bearer ' . $this->apiKey,
        ];

        // Send the HTTP DELETE request to the Passage API
        $response = service('curlrequest')->request('delete', $url, ['headers' => $headers]);

        // Check if the request was successful
        if ($response->getStatusCode() === 200) {
            return true;
        }
        // Throw a PassageException or handle the failure as needed
        throw PassageException::forFailedToDeleteUser();

    }

    /**
     * Create a new user.
     *
     * @param string|null $email The user's email address.
     * @param string|null $phone The user's phone number.
     *
     * @return array The created user information.
     *
     * @throws Exception
     */
    public function create(?string $email = null, ?string $phone = null): array
    {
        // Validate that at least email or phone is provided
        if (empty($email) && empty($phone)) {
            throw PassageException::forMissingEmailOrPhone();
        }

        // Construct the URL for the Passage API endpoint
        $url = $this->apiURL . $this->appId . '/users';

        // Set the headers for the API request
        $headers = [
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type'  => 'application/json',
        ];

        // Create the request payload
        $payload = [
            'email' => $email,
            'phone' => $phone,
        ];

        // Send the HTTP POST request to the Passage API
        $response = service('curlrequest')->request('post', $url, ['headers' => $headers, 'json' => $payload]);

        // Check if the request was successful
        if ($response->getStatusCode() === 200) {
            $responseData = json_decode($response->getBody(), true);
            $user         = $responseData['user'];

            // Parse created_at and last_login_at fields into Time objects
            $user['created_at']    = Time::parse($user['created_at']);
            $user['last_login_at'] = Time::parse($user['last_login_at']);

            return $user;
        }
        // Throw a PassageException or handle the failure as needed
        throw PassageException::forFailedToCreateUser();

    }

    /**
     * Update a user's information.
     *
     * @param string $userId The user ID.
     * @param array  $data   The updated user data.
     *
     * @return array The updated user information.
     *
     * @throws Exception
     */
    public function update(string $userId, array $data): array
    {
        // Construct the URL for the Passage API endpoint
        $url = $this->apiURL . $this->appId . '/users/' . $userId;

        // Set the headers for the API request
        $headers = [
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type'  => 'application/json',
        ];

        // Send the HTTP PATCH request to the Passage API
        $response = service('curlrequest')->request('patch', $url, ['headers' => $headers, 'json' => $data]);

        // Check if the request was successful
        if ($response->getStatusCode() === 200) {
            $responseData = json_decode($response->getBody(), true);
            $user         = $responseData['user'];

            // Parse created_at and last_login_at fields into Time objects
            $user['created_at']    = Time::parse($user['created_at']);
            $user['last_login_at'] = Time::parse($user['last_login_at']);

            return $user;
        }
        // Throw a PassageException or handle the failure as needed
        throw PassageException::forFailedToUpdateUser();

    }
}
