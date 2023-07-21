<?php

namespace Michalsn\CodeIgniterPassage\Config;

use CodeIgniter\Config\BaseConfig;

class Passage extends BaseConfig
{
    /**
     * Application ID
     */
    public string $appId = '';

    /**
     * You have to generate one.
     * Go to: Settings -> API Keys
     */
    public string $apiKey = '';

    /**
     * Auth strategy: COOKIE, HEADER
     */
    public string $authStrategy = 'COOKIE';

    /**
     * Leave as null. It might be populated during the request.
     */
    private ?string $userId = null;

    public function setUser(string $id): void
    {
        $this->userId = $id;
    }

    public function getUser(): string
    {
        return $this->userId;
    }
}
