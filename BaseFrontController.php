<?php
namespace core\framework;


use core\framework\_;
use core\framework\interfaces\irouter;
use core\framework\interfaces\iparser;
use core\framework\interfaces\icontroller;
use core\framework\BaseLayout;

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
    
    public function __construct(irouter $router, iparser $parser, BaseLayout $layout) {
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
        if(is_array($this->controller)) {
            $route403 = $this->parser->getRoute('403');
            
            $allow = true;

            //All conrollers excluding last 
            for($i = 0; $i < count($this->controller)-1; $i++) {
                if(!_::createAndCall($this->controller[$i], $this->params, array('layout' => $this->layout))) {
                    if(empty($route403))
                        throw new \Exception('Route 404 not found !');
                    else
                        _::createAndCall($route403, $this->params, array('layout' => $this->layout));
                    
                    //Deny to run last controller and exist loop
                    $allow = false;
                    break;
                }
            }
            
            //Run last controller in array
            if($allow)
                _::createAndCall($this->controller[count($this->controller)-1], $this->params, array('layout' => $this->layout));
        }
        else {
            _::createAndCall($this->controller, $this->params, array('layout' => $this->layout));
        }
        
        $this->controller_object = _::$object;
    }
    
    public function run() {
        $this->route();
        $this->runController();
    }
}
