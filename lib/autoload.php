<?

function __click_autoload($class_name)
{
  foreach(Click::$meta['autoloads'] as $fpath)
  {
    $fname = $fpath."/$class_name.class.php";
    if(!file_exists($fname)) continue;
    include($fname);
  }
}