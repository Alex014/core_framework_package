<?php
namespace core\framework;

use core\framework\_;

/**
 * Description of BaseLayout
 *
 * @author user
 */
class BaseLayout {
    protected $viewer;
    
    public function __construct($viewer) {
        _::set('_objects.layout', $this);
        $this->viewer = $viewer;
    }
}
