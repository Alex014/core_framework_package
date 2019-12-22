<?php
namespace core\framework;

use core\framework\_;
use core\framework\interfaces\iviewer;

/**
 * Description of Viewer
 *
 * @author user
 */
class Viewer implements iviewer {
    
    public function __construct() {
        _::set('_objects.view', $this);
    }
    
    public function view($template, $params = '') {
        if(!empty($params))
            extract($params);
       
        require __DIR__.'/../'.$template.'.php';
    }
    
    public function render($template, $params = '') {
        if(!empty($params))
            extract($params);
        
        ob_start();
        require __DIR__.'/../'.$template.'.php';
        $contents = ob_get_contents();
        ob_clean();
        
        return $contents;
    }
}
