<?php
class response
{
    function send($code, $message, $data = array())
    {
        http_response_code($code);
        return json_encode(array("status" => $code, "message" => $message, "data" => $data));
    }
}
