<?php
namespace core\framework;


use core\framework\_;
use core\framework\interfaces\irouter;

/**
 * Description of RouterGet
 *
 * @author user
 */
class RouterGet implements irouter {
    public function __construct() {
        //...
    }
    
    public function getRouteString() {
        foreach ($_GET as $key => $value) {
            return $key;
        }
    }
}
