<?php

namespace App\OAuth;

use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Client\AmoCRMApiClientFactory;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Token\AccessTokenInterface;

class ApiClientService {

    function getApiClient(AccessToken $accessToken): AmoCRMApiClient {
        $oAuthConfig = new OAuthConfig();
        $oAuthService = new OAuthService();

        $apiClientFactory = new AmoCRMApiClientFactory($oAuthConfig, $oAuthService);
        $apiClient = $apiClientFactory->make();
        $apiClient->setAccountBaseDomain(getenv('SUBDOMAIN'));

        $apiClient->setAccessToken($accessToken)
            ->setAccountBaseDomain($accessToken->getValues()['baseDomain'])
            ->onAccessTokenRefresh(
                function (AccessTokenInterface $accessToken, string $baseDomain) {
                    saveToken(
                        [
                            'accessToken' => $accessToken->getToken(),
                            'refreshToken' => $accessToken->getRefreshToken(),
                            'expires' => $accessToken->getExpires(),
                            'baseDomain' => $baseDomain,
                        ]
                    );
                }
            );

        return $apiClient;
    }
}