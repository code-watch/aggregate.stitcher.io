<?php

namespace App\Http\Controllers\Auth;

use App\Services\OAuth\TwitterHttpClient;

final class TwitterOAuthController
{
    public function __invoke(TwitterHttpClient $client)
    {
        return $client->authorize();
    }
}
