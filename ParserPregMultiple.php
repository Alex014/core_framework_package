<?php
namespace core\framework;


use core\framework\_;
use core\framework\interfaces\iparser;

/**
 * Description of Parser
 *
 * @author user
 */
class ParserPregMultiple implements iparser {
    private $routes_list;
    private $show404;
    
    public function __construct($routes_list, $show404 = true) {
        $this->show404 = $show404;
        $this->routes_list = $routes_list;
    }
    
    private function _find_route($route) {
        foreach ($this->routes_list as $routes) {
            foreach ($routes as $rt => $controller) {
                if(!is_array($controller) && ($rt == $route)) {
                    return $controller;
                }
            }
        }
        
        return false;
    }


    public function getParsedRoute($route, &$params) {
        if(empty($route)) {
            $controller = $this->_find_route('default');

            if(empty($controller))
                throw new \Exception('Route "default" not found !');
            
            return $controller;
        }
        
        foreach ($this->routes_list as $routes) {
            foreach ($routes['routes'] as $preg => $controller) {
                if(preg_match('/^'.$preg.'$/i', $route, $params)) {
                    return $controller;
                }
            }
        }
        
        if($this->show404) {
            $controller404 = $this->_find_route('404');
            
            if(empty($controller404))
                throw new \Exception('Route 404 not found !');
            
            return $controller404;
        }
        else {
            return false;
        }
    }
    
    public function getRoute($route) {
        return $this->routes[$route];
    }
}
