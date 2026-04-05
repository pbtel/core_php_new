<?php

class Model_Request
{
    public function get($key, $value = null)
    {
        if(array_key_exists($key, $_GET)) {
            return $_GET[$key];
        }
        return $value;
    }

    public function post($key, $value = null)
    {
        if(array_key_exists($key, $_POST)) {
            return $_POST[$key];
        }
        return $value;
    }

    public function isPost()
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }


}