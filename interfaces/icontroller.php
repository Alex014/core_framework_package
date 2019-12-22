<?php
namespace core\framework\interfaces;

/**
 *
 * @author user
 */
interface icontroller {
    public function route();
    
    public function runController();
    
    public function run();
}
