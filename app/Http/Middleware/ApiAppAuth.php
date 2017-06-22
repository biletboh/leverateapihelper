<?php

namespace App\Http\Middleware;

use Closure;

class ApiAppAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $authToken = $request->bearerToken();

        try {
            $this->payloadIsValid(
                $payload = (array) JWT::decode($authToken, 'w5yuCV2mQDVTGmn3', ['HS256'])
            );

            $app = Application::whereKey($payload['sub'])->firstOrFail();
        } catch (\Firebase\JWT\ExpiredException $e) {
            return response(array('error'=>'token_expired'), 401);
        } catch (\Throwable $e) {
            return response(array('error'=>'token_invalid'), 401);
        }

        if (! $app->is_active) {
            return response(array('error'=>'app_inactive'), 403);
        }

        $request->merge(['__authenticatedApp' => $app]);

        return $next($request);
    }
    private function payloadIsValid($payload)
    {
        $validator = Validator::make($payload, [
            'iss' => 'required|in:LeverateApiHelper',
            'sub' => 'required',
        ]);

        if (! $validator->passes()) {
            throw new \InvalidArgumentException;
        }
    }
}
