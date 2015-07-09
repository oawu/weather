<?php
if (!defined('PHP_VERSION_ID') || PHP_VERSION_ID < 50300)
	die('PHP ActiveRecord requires PHP 5.3 or higher');

define('PHP_ACTIVERECORD_VERSION_ID','1.0');

if (!defined('PHP_ACTIVERECORD_AUTOLOAD_PREPEND'))
	define('PHP_ACTIVERECORD_AUTOLOAD_PREPEND',true);

require 'lib/Singleton.php';
require 'lib/Config.php';
require 'lib/Utils.php';
require 'lib/DateTime.php';
require 'lib/Model.php';
require 'lib/Table.php';
require 'lib/ConnectionManager.php';
require 'lib/Connection.php';
require 'lib/SQLBuilder.php';
require 'lib/Reflections.php';
require 'lib/Inflector.php';
require 'lib/CallBack.php';
require 'lib/Exceptions.php';
require 'lib/Cache.php';

if (!defined('PHP_ACTIVERECORD_AUTOLOAD_DISABLE'))
	spl_autoload_register('activerecord_autoload',false,PHP_ACTIVERECORD_AUTOLOAD_PREPEND);

if ( ! defined('DS'))
    define('DS', DIRECTORY_SEPARATOR);

function activerecord_autoload($class_name)
{
	// $path = ActiveRecord\Config::instance()->get_model_directory();
  $paths = ActiveRecord\Config::instance()->get_model_directorise();
	if ($paths) {
    foreach ($paths as $path) {
      $root = realpath(isset($path) ? $path : '.');

      if (($namespaces = ActiveRecord\get_namespaces($class_name)))
      {
        $class_name = array_pop($namespaces);
        $directories = array();

        foreach ($namespaces as $directory)
          $directories[] = $directory;

        $root .= DS . implode($directories, DS);
      }

        $file_name = "{$class_name}.php";
      $file = $root.DS.$file_name;

      if (file_exists($file)) {
        require $file;
      } else {
        $modules_path = APPPATH.'modules';
        if (is_dir($modules_path)) {
          $modules = scandir(realpath($modules_path));
          foreach ($modules as $module) {
            $full_path = $modules_path.DS.$module.DS.'models'.DS.$file_name;
            if ($module != '.' && $module != '..' && file_exists($full_path)) {
              require $full_path;
            }
          }
        }
      }
    }
  }
  if (ENVIRONMENT === 'production') {
    $cfg_ar = ActiveRecord\Config::instance ();
    $cfg_ar->set_cache ("OrmCache://localhost");
  }
}