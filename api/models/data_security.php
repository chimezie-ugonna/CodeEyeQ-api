<?php
class data_security
{
    public $ciphering = "AES-128-CTR";
    public $encryption_iv;
    public $encryption_key;
    public $decryption_iv;
    public $decryption_key;

    function __construct()
    {
        $this->encryption_iv = strval(rand(1000000000000000, 9999999999999999));
        $this->encryption_key = strval(rand(1000000000000000, 9999999999999999));
        $this->decryption_iv = base64_encode($this->encryption_iv);
        $this->decryption_key = base64_encode($this->encryption_key);
    }

    function encrypt($data)
    {
        return openssl_encrypt($data, $this->ciphering, $this->encryption_key, 0, $this->encryption_iv);
    }

    function decrypt($decryption_key, $decryption_iv, $data)
    {
        return openssl_decrypt($data, $this->ciphering, base64_decode($decryption_key), 0, base64_decode($decryption_iv));
    }
}
