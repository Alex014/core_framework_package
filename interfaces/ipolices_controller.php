<?php
namespace core\framework\interfaces;

/**
 *
 * @author user
 */
interface ipolices_controller {
    
    /**
     * has the User access to controller with specific params
     * @param type $controller
     * @param type $params
     */
    public function access($controller, $params);
}
