<?php

namespace App\Http\Middleware;

use Closure;
use GuzzleHttp\Client;
use Symfony\Component\HttpFoundation\Response;

class ReCaptcha
{

    public function __construct()
    {
        $this->client
            = new Client(['base_uri' => config('recaptcha.base_url')]);
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (isset($request['token']) && $request['token'] && $this->verify($request['token'])) {
            return $next($request);
        }
        return response()->json(['message' => '无权访问'], Response::HTTP_FORBIDDEN);
    }

    public function verify($token = '')
    {
        $request = [
            'secret' => config('recaptcha.secret'),
            'response' => $token
        ];
        $r = $this->client->request('POST', 'siteverify',
            ['form_params' => $request]);

        $result = json_decode((string)$r->getBody(), true);
        if ($result['success']) {
            return true;
        }

        return false;
    }
}
