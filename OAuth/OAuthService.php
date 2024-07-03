<?php

namespace App\OAuth;

use AmoCRM\OAuth\OAuthServiceInterface;
use League\OAuth2\Client\Token\AccessTokenInterface;

class OAuthService implements OAuthServiceInterface {
    public function saveOAuthToken(
        AccessTokenInterface $accessToken,
        string $baseDomain
    ): void {
        // TODO: Implement saveOAuthToken() method.
    }
}
