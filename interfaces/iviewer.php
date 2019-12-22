<?php
namespace core\framework\interfaces;

/**
 *
 * @author user
 */
interface iviewer {    
    public function view($template, $params = '');
    public function render($template, $params = '');
}
