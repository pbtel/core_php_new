<?php
require_once 'app/Boot.php';
class Mage
{
    public static function init()
    {
        Boot::init();
    }
}

Mage::init();