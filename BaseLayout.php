<?php
namespace core\framework;

use core\framework\_;
use core\framework\interfaces\iviewer;

/**
 * Description of BaseLayout
 *
 * @author user
 */
class BaseLayout {
    protected $viewer;
    
    public function __construct(iviewer $viewer) {
        _::set('_objects.layout', $this);
        $this->viewer = $viewer;
    }
}
