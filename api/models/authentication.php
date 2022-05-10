<?php
class authentication
{
    private $key;

    function __construct()
    {
        $this->key = $_SERVER["TOKEN_KEY"];
    }

    function encode($user_id)
    {
        $payload = [
            'iss' => 'https://codeeyeq.herokuapp.com/api',
            'aud' => 'https://codeeyeq.herokuapp.com',
            'iat' => time(),
            'user_id' => $user_id
        ];
        return Firebase\JWT\JWT::encode($payload, $this->key, 'HS512');
    }

    function decode($token)
    {
        try {
            return (array) (Firebase\JWT\JWT::decode($token, new Firebase\JWT\key($this->key, 'HS512')));
        } catch (\Exception $e) {
            echo $e;
            return false;
        }
    }
}
