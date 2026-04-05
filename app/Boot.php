<?php
require_once 'app/models/Core/Request.php';

class Boot
{
    public static function init()
    {
        $request = new Model_Request();

        $controllerName = $request->get('c', 'product');
        
        // Build controller file path: app/controllers/{Ucfirst}.php
        $controllerFile = 'app/controllers/' . ucfirst($controllerName) . '.php';
        
        if (!file_exists($controllerFile)) {
            die("Controller file not found: " . $controllerFile);
        }

        require_once $controllerFile;

        // Build controller class name: Controller_{Ucfirst}
        $controllerClass = 'Controller_' . ucfirst($controllerName);

        if (!class_exists($controllerClass)) {
            die("Controller class not found: " . $controllerClass);
        }

        $controller = new $controllerClass();
        $controller->dispatch();
    }
}