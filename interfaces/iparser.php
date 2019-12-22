<?php
namespace core\framework\interfaces;

/**
 *
 * @author user
 */
interface iparser {
    public function getParsedRoute($route, &$params);
    public function getRoute($route);
}
