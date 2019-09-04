<?php


namespace App\Http\Controllers\Api;

use Auth;
use Dingo\Api\Routing\Helpers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\Api\AuthorizationRequest;
use Zend\Diactoros\Response as Psr7Response;
use Psr\Http\Message\ServerRequestInterface;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\AuthorizationServer;
use App\Models\User;

class AuthorizationsController extends Controller
{
    use Helpers;

    public function store(AuthorizationRequest $originRequest, AuthorizationServer $server, ServerRequestInterface $serverRequest)
    {
        try {
            return $server->respondToAccessTokenRequest($serverRequest, new Psr7Response)
                ->withStatus(201);
        } catch (OAuthServerException $e) {
            return $this->response->errorUnauthorized($e->getMessage());
        }
    }

    public function update(AuthorizationServer $server, ServerRequestInterface $serverRequest)
    {
        try {
            return $server->respondToAccessTokenRequest($serverRequest, new Psr7Response);
        } catch (OAuthServerException $e) {
            return $this->response->errorUnauthorized($e->getMessage());
        }
    }

    public function destroy()
    {
        if (!empty($this->auth->user())) {
            $this->auth->user()->token()->revoke();
            return $this->response->noContent();
        } else {
            return $this->response->errorUnauthorized('Token 无效');
        }
    }

}
