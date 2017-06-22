<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    //
    public function generateAuthToken()
    {
        $jwt = \Firebase\JWT::encode([
            'iss' => 'LeverateApiHelper',
            'sub' => $this->key,
            'iat' => time(),
            'exp' => time() + (5 * 60 * 60),
        ], 'w5yuCV2mQDVTGmn3');

        return $jwt;
    }
}
