<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class Application extends Model
{
    //
    use HasApiTokens, Notifiable;

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
