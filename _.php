<?php
namespace core\framework;

class _ {
    
    public static $path_di = __DIR__.'/../../../_/';
    private static $data;
    
    public static $path_autoload = __DIR__.'/../';
    public static $path_autoload_psr4 = __DIR__.'/../';
    
    public static $object;
    
    public static function autoload() {
        $loader = function($className) {
            $path = explode('\\', $className);
            $path = implode('/', $path);
            $fileName = _::$path_autoload.$path.'.php';

            if(file_exists($fileName)) {
                require_once $fileName;
                return true;
            }
            else {
                return false;
            }
        };
    
        spl_autoload_register($loader, true, false);
        
        self::autoload_composer();
    }
    
    
    public static function autoload_psr4() {
        $loader = function($className) {
            $path = explode('\\', $className);
            $path = implode('/', $path);
            $fileName = _::$path_autoload_psr4.$path.'.php';

            if(file_exists($fileName)) {
                require_once $fileName;
                return true;
            }
            else {
                return false;
            }
        };
    
        spl_autoload_register($loader, true, false);
    }
    
    /**
     * Load classes instaled by Composer.
     * Compatible with Symfony and Laravel
     */
    public static function autoload_composer() {
        require _::$path_autoload_psr4.'autoload.php';
    }
   
    /**
     * Has the container element ?
     * @param type $name
     * @return boolean
     */
    public static function exists($name) {
        if(isset(self::$data[$name])) {
            return true;
        }
        else {
            $content = self::get_config($name);
            return ($content !== false);
        }
    }
   
    /**
     * Set dependency injection path
     * @param type $path_di
     */
    public static function init($path_di) {
        $path_di = rtrim($str, '/').'/';
        self::$path_di = $path_di;
    }
    
    /**
     * Has the container element ?
     * @param type $name
     * @return type
     */
    public static function has($name) {
        return self::has($name);
    }
    /**
     * Return the element $name from the container
     * @param type $name
     * @return boolean
     */
    public static function get($name) {
        if(isset(self::$data[$name])) {
            $content = self::$data[$name];
        }
        else {
            $content = self::get_config($name);
            if($content === false) return false;
            
            self::$data[$name] = $content;
        }
        
        if(is_callable(self::$data[$name])) {
            return self::$data[$name]();
        } else {
            return self::$data[$name];
        }
    }
    
    //TODO: getByPreg
    public static function getByPreg($path, $string, &$found) {
        $routes = self::get_config($path);
        //...
    }
    
    /**
     * Create or update the element $name in the container
     * @param type $name
     * @param type $value
     */
    public static function set($name, $value) {
        self::$data[$name] = $value;
    }
    
    /**
     * Create the object from classname and return it
     * @param type $classname full classpath with namespaces (example: \core\Viewer)
     * @param type $params - params parsed to class constructor
     * @return boolean|\classname
     */
    public static function create($classname, $params = '') {
        if(is_array($classname)) {
            $classname = implode('\\', $classname);
            $classname = '\\'.$classname;
            $object = new $classname();
            
            if(!empty($params))
                foreach ($params as $key => $value) {
                    $object->$key = $value;
                }
            
            return $object;
        }
        elseif(is_string($classname)) {
            if(substr($classname, 0,1) != '\\') $classname = '\\'.$classname;
            self::$object = new $classname();
            
            if(!empty($params))
                foreach ($params as $key => $value) {
                    self::$object->$key = $value;
                }
            
            return self::$object;
        }
        else {
            return false;
        }
    }
    
    /**
     *  Create the object from classname and lunch the method
     * @param type $claspath full classpath with namespaces and method (example: \site\modules\main\menu\about , where new \site\modules\main\menu() - class and ->about() - method)
     * @param type $params - params parsed to class method
     * @param type $params_create - params parsed to class constructor
     * @return boolean
     */
    public static function createAndCall($claspath, $params, $params_create = '') {
        if(is_array($claspath)) {
            $method = $claspath[count($claspath) - 1];
            unset($claspath[count($claspath) - 1]);
            $classname = implode('\\', $claspath);
            $classname = '\\'.$classname;
            self::$object = new $classname();
            
            if(!empty($params_create))
                foreach ($params_create as $key => $value) {
                    self::$object->$key = $value;
                }
            
            return call_user_func_array(array(self::$object, $method), $params);
        }
        elseif(is_string($claspath)) {
            $claspath = explode('\\', $claspath);
            $method = $claspath[count($claspath) - 1];
            unset($claspath[count($claspath) - 1]);
            $classname = implode('\\', $claspath);
            if(substr($classname, 0,1) != '\\') 
                    $classname = '\\'.$classname;
            self::$object = new $classname();
            
            if(!empty($params_create))
                foreach ($params_create as $key => $value) {
                    self::$object->$key = $value;
                }
            
            return call_user_func_array(array(self::$object, $method), $params);
        }
        else {
            return false;
        }
        
        
    }    
  
    /**
     * Extracting module name
     * @param type $config_path 
     */
    private static function get_module_name($config_path) {
        $path = explode('/', $config_path);
        return $path[count($path) - 1];
    }

    /**
     * Extracting module path
     * @param type $config_path 
     */
    private static function get_module_path($config_path) {
        $path = explode('/', $config_path);
        unset($path[count($path) - 1]);
        return implode('/', $path);
    }

    public static function get_config($config_path) {
        $module_name = self::get_module_name($config_path);
        $module_path = self::get_module_path($config_path);
        //module name
        $cp = explode('.', $module_name);
        $module_name = $cp[0];
        //return config
        if(substr(self::$path_di, strlen(self::$path_di) - 1, 1) != '/')
            self::$path_di .= '/';
        
        $filename = self::$path_di. $module_path.'/'.$module_name.'.php';
        
        if(file_exists($filename))
            $content = include(self::$path_di. $module_path.'/'.$module_name.'.php');
        else
            return false;
        
        if(!is_array($content) && !is_string($content)) return false;
        
        if(count($cp) > 1) {
            for($i = 1; $i < count($cp); $i++) {
                if(isset($content[$cp[$i]])) {
                    $content = $content[$cp[$i]];
                }
                else {
                    return false;
                }
            }
        }
        
        return $content;
    }
}