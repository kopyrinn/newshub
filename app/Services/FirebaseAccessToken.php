<?php

namespace App\Services;

use Google\Auth\Credentials\ServiceAccountCredentials;
use RuntimeException;

final class FirebaseAccessToken
{
    private const SCOPE = 'https://www.googleapis.com/auth/firebase.messaging';

    public function get(array $serviceAccount): string
    {
        $credentials = new ServiceAccountCredentials(self::SCOPE, $serviceAccount);
        $token = $credentials->fetchAuthToken();

        if (! isset($token['access_token']) || ! is_string($token['access_token'])) {
            throw new RuntimeException('Google did not return an FCM access token.');
        }

        return $token['access_token'];
    }
}
