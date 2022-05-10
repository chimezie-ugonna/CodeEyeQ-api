<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class authentication
{
    private $token_key;

    function __construct()
    {
        $this->token_key = $_SERVER["TOKEN_KEY"];
    }

    function encode($user_id)
    {
        $payload = [
            'iss' => 'https://codeeyeq.herokuapp.com/api',
            'aud' => 'https://codeeyeq.herokuapp.com',
            'iat' => time(),
            'user_id' => $user_id
        ];
        return JWT::encode($payload, $this->token_key, 'HS512');
    }

    function decode($token)
    {
        try {
            return (array) (JWT::decode($token, new Key($this->token_key, 'HS512')));
        } catch (\Exception $e) {
            echo $e;
            return false;
        }
    }
}
