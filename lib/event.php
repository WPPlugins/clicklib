<?

function event($hook_name, $event_args=array())
{
  $data = array();
  $m = &Click::$meta;
  if(!isset($m['open_hooks'][$hook_name])) return $data;
  $e = &$m['open_hooks'][$hook_name];
  foreach($e as $plugin_name=>$plugin_data)
  {
    list($plugin_name, $module_name) = $plugin_data;
    $data[$plugin_name][$module_name] = call_user_func('_event', $plugin_name, $module_name, $hook_name);
  }
  return $data;
}

function _event($plugin_name, $module_name, $hook_name)
{
  global $current_user, $wpdb;
  
  $engines = array('haml', 'php');
  $folders = array('callbacks', 'views');
  $this_module_fpath = Click::$meta['plugins'][$plugin_name]['modules'][$module_name]['fpath'];
  $this_module_vpath = Click::$meta['plugins'][$plugin_name]['modules'][$module_name]['vpath'];
  extract(Click::$meta['request']);
  $settings = Click::$meta['plugins'][$plugin_name]['settings'];
  foreach($folders as $folder)
  {
    foreach($engines as $e)
    {
      $fpath = $this_module_fpath."/$folder/$hook_name.$e";
      if(!file_exists($fpath)) continue;
      if($e!='php')
      {
        Click::load("clicklib.$e");
        $fpath = call_user_func("{$e}_to_php", $fpath);
      }
      require($fpath);
    }
  }
  return array();
}