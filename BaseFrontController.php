<?php
namespace core\framework;


use core\framework\_;
use core\framework\interfaces\icontroller;

/**
 * Description of FrontController
 *
 * @author user
 */
class BaseFrontController implements icontroller {
    public $router;
    public $parser;
    public $layout;
    
    public $controller;
    public $params;
    
    public function __construct($router, $parser, $layout) {
        $this->router = $router;
        $this->parser = $parser;
        $this->layout = $layout;
    }
    
    public function route() {
        $this->params = array();
        $route = $this->router->getRouteString();
        $this->controller = $this->parser->getParsedRoute($route, $this->params);
    }
    
    public function runController() {
        $result = \_::createAndCall($this->controller, $this->params, array('layout' => $this->layout));
        
        $this->controller_object = \_::$object;
    }
    
    public function run() {
        $this->route();
        $this->runController();
    }
}
