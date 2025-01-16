<?php

namespace App\Http\Controllers\Api\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

trait IssueTokenTrait
{

    public function issueToken(Request $request, $grantType, $scope = "")
    {

        $params = [
            'grant_type' => $grantType,
            'client_id' => '9caba8f4-e571-4837-9984-622818108536',
            'client_secret' => 'waXzrIVLUL8H6nPKUhU4nPtsqkJJwXGpMSvI0w71',
            'scope' => $scope
        ];

        if ($grantType !== 'social') {
            $params['username'] = $request->username ?: $request->email;
        }

        $http = new Client();

        $response = $http->request('POST',  $request->root() .'/oauth/token', [
            'form_params' => [
                'grant_type' => $grantType,
                'client_id' => '9cabaf53-4f08-499b-a111-9a27f43f6d54',
                'client_secret' => 'nFoj8tEFcvQVoZrlYcVHQ5CjqZAOEygp3GcWALry',
                'scope' => $scope,
                'username' => $request->username ?: $request->email,
                'password' => $request->password,
            ],
            'allow_redirects' => false,
            'http_errors' => false
        ]);
//        return $response->getBody()->getContents();
        $body =  json_decode((string) $response->getBody(), true);
        $body['status'] = $response->getStatusCode();
        return $body;

    }
}
