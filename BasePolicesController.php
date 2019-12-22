<?php
namespace core\framework;


use core\framework\_;
use core\framework\interfaces\icontroller;
use core\framework\interfaces\ipolices_controller;

/**
 * Fasade for Base Controller responsible for user security polices
 *
 * @author user
 */
class BasePolicesController implements ipolices_controller {
    
    public static $base_controller;
    private static $self;
    
    public function __construct(icontroller $base_controller) {
        self::$base_controller = $base_controller;
    }
    
    public static function create(icontroller $base_controller) {
        if(!empty($base_controller)) {
            self::$base_controller = $base_controller;
            self::$self = new BasePolicesController($base_controller);
            return self::$self;
        } else {
            throw new \Exception('Empty $base_controller param');
        }
        
    }
    
    public function access($controller, $params) {
        return true;
    }
    
    public function run() {
        self::$base_controller->route();
        
        if($this->access(self::$base_controller->controller, self::$base_controller->params)) {
            self::$base_controller->runController();
            return true;
        }
        else {
            self::$base_controller->controller = self::$base_controller->parser->getRoute('403');
            
            if(empty( self::$base_controller->controller))
                throw \Exception('Route 403 not found !');
            
            self::$base_controller->runController();
            return false;
        }
    }
}
