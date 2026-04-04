<?php
require_once 'app/controllers/Core/Front.php';
require_once 'app/models/Request.php';
require_once 'app/controllers/Product.php';

class Boot
{
    public static function init()
    {
        echo 222;

        $request = new Model_Request();

        $controllerName = $request->get('c', 'index');
        $controllerName = 'Controller_' . ucfirst($controllerName);
        $controller = new $controllerName();
        $controller->dispatch();

    }
}