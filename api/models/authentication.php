<?php
require_once "../vendor/autoload.php";

use Firebase\JWT\JWT;
use Firebase\JWT\key;

class authentication
{
    private $key = "hash('sha512', 'qeyeedoc')";

    function encode($user_id)
    {
        $payload = [
            'iss' => 'https://codeeyeq.herokuapp.com/api',
            'aud' => 'https://codeeyeq.herokuapp.com',
            'iat' => time(),
            'user_id' => $user_id
        ];
        return JWT::encode($payload, $this->key, 'HS512');
    }

    function decode($token)
    {
        try {
            return json_decode(json_encode(JWT::decode($token, new Key($this->key, 'HS512'))), true);
        } catch (\Exception $e) {
            return FALSE;
        }
    }
}
