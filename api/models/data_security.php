<?php

class data_security
{
    public $ciphering = "AES-128-CTR";
    public $encryption_iv = (string)rand(1000000000000000, 9999999999999999);
    public $encryption_key = (string)rand(1000000000000000, 9999999999999999);
    public $encryption_iv_ = base64_encode($encryption_iv);
    public $encryption_key_ = base64_encode($encryption_key);

    function encrypt($data)
    {
        return openssl_encrypt($data, $this->ciphering, $this->encryption_key, 0, $this->encryption_iv);
    }

    function decrypt()
    {
    }
}
