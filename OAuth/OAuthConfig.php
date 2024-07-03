<?php

namespace App\OAuth;

use AmoCRM\OAuth\OAuthConfigInterface;

class OAuthConfig implements OAuthConfigInterface{

    public function getIntegrationId(): string
    {
        return getenv("CLIENT_ID");
    }

    public function getSecretKey(): string
    {
        return getenv("CLIENT_SECRET");
    }

    public function getRedirectDomain(): string
    {
        return getenv("REDIRECT_URI");
    }
}
