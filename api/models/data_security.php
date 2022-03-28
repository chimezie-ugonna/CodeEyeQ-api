<?php
class data_security
{
    public $ciphering = "AES-128-CTR";
    public $encryption_iv;
    public $encryption_key;
    public $encryption_iv_;
    public $encryption_key_;

    function __construct()
    {
        $this->encryption_iv = strval(rand(1000000000000000, 9999999999999999));
        $this->encryption_key = strval(rand(1000000000000000, 9999999999999999));
        $this->encryption_iv_ = base64_encode($this->encryption_iv);
        $this->encryption_key_ = base64_encode($this->encryption_key);
    }

    function encrypt($data)
    {
        return openssl_encrypt($data, $this->ciphering, $this->encryption_key, 0, $this->encryption_iv);
    }

    function decrypt()
    {
    }
}
