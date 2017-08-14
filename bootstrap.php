<?
global $__CLICK_VERSION_REQUIRED, $__CLICK_VERSION;

$__CLICK_VERSION_REQUIRED="1.0.0";
if($__CLICK_VERSION)
{
  if($__CLICK_VERSION!=$__CLICK_VERSION_REQUIRED)
  {
    click_error("This module requires Click {$__CLICK_VERSION_REQUIRED} but {$__CLICK_VERSION} is loaded insated.");
  }
} else {
  $__CLICK_VERSION = $__CLICK_VERSION_REQUIRED;
  if(!session_id()) session_start();
  
  require_once('classes/Click.class.php');
  require_once('lib/autoload.php');
  spl_autoload_register('__click_autoload');
  require_once('lib/file.php');
  
  $pieces = parse_url(site_url());
  
  define('ROOT_FPATH', realpath($_SERVER['DOCUMENT_ROOT']).$pieces['path']); // DOCUMENT_ROOT doesn't detect if WP is in a subfolder
  define('ROOT_VPATH', normalize_path($_SERVER['SCRIPT_NAME']."/../.."));
  define('CLICK_FPATH', dirname(__FILE__));
  define('CLICK_VPATH', ftov(CLICK_FPATH));
  Click::$meta['autoloads'] = array(CLICK_FPATH."/classes");
  
  foreach(click_glob(dirname(__FILE__)."/lib/*.php") as $fname)
  {
    require_once($fname);
  }
  
  Click::init(dirname(__FILE__),false);
}
