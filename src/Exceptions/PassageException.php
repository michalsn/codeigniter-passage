<?php

namespace Michalsn\CodeIgniterPassage\Exceptions;

use RuntimeException;

final class PassageException extends RuntimeException
{
    public static function forCouldNotFetchApp(): static
    {
        return new self(lang('Passage.couldNotFetchApp'));
    }

    public static function forHeaderAuthorizationNotFound(): static
    {
        return new self(lang('Passage.headerAuthorizationNotFound'));
    }

    public static function forInvalidAuthToken(): static
    {
        return new self(lang('Passage.invalidAuthToken'));
    }

    public static function forCookieForAuthorizationNotFound(): static
    {
        return new self(lang('Passage.cookieForAuthorizationNotFound'));
    }

    public static function forFailedToRevokeDevice(): static
    {
        return new self(lang('Passage.failedToRevokeDevice'));
    }

    public static function forFailedToRevokeRefreshTokens(): static
    {
        return new self(lang('Passage.failedToRevokeRefreshTokens'));
    }

    public static function forFailedToRetrieveUserInformation(): static
    {
        return new self(lang('Passage.failedToRetrieveUserInformation'));
    }

    public static function forFailedToDeactivateUser(): static
    {
        return new self(lang('Passage.failedToDeactivateUser'));
    }

    public static function forFailedToActivateUser(): static
    {
        return new self(lang('Passage.failedToActivateUser'));
    }

    public static function forFailedToDeleteUser(): static
    {
        return new self(lang('Passage.failedToDeleteUser'));
    }

    public static function forMissingEmailOrPhone(): static
    {
        return new self(lang('Passage.missingEmailOrPhone'));
    }

    public static function forFailedToCreateUser(): static
    {
        return new self(lang('Passage.failedToCreateUser'));
    }

    public static function forFailedToUpdateUser(): static
    {
        return new self(lang('Passage.failedToUpdateUser'));
    }

    public static function forFailedToRefreshToken(): static
    {
        return new self(lang('Passage.failedToRefreshToken'));
    }
}
