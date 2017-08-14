<?

class Click
{
  static $meta = array();
  static $loaded = array();
  static $event_dispatcher = null;

  static function init($plugin_fpath, $eager_load_modules = false)
  {
    if(!self::$event_dispatcher) self::$event_dispatcher = new ClickEventDispatcher();
    $plugin_name = basename($plugin_fpath);
    
    self::$meta['plugins'][$plugin_name] = array(
      'uname'=>strtoupper($plugin_name),
      'fpath'=>$plugin_fpath,
      'vpath'=>ftov($plugin_fpath),
      'settings'=>Click::load_settings($plugin_name),
    );
    $uname = strtoupper($plugin_name);
    define("{$uname}_FPATH", $plugin_fpath);
    define("{$uname}_VPATH", ftov($plugin_fpath));
    if($eager_load_modules)
    { 
      foreach(click_glob($plugin_fpath."/modules/*") as $fpath)
      {
        $module_name = basename($fpath);
        self::load("{$plugin_name}.{$module_name}");
      }
    }
  }
  
  static function load_settings($plugin_name)
  {
    $key = $plugin_name."_settings";
    $settings = get_option($key);
    if(!$settings)
    {
      add_option($key, array());
      $settings = get_option($key);
    }
    $settings = (object)$settings;
    return $settings;
  }
  
  static function save_all_settings()
  {
    foreach(Click::$meta['plugins'] as $plugin_name=>$plugin_data)
    {
      $key = $plugin_name."_settings";
      update_option($key, get_object_vars($plugin_data['settings']));
    }
  }
  
  static function load($module_name)
  {
    list($plugin_name, $module_name) = explode('.',$module_name);
    if(!$module_name) click_error("To load a module, you must specify in the format plugin_name.module_name.");
    $p = &self::$meta['plugins'][$plugin_name];
    if(isset($p['modules'][$module_name])) return;
      
    if(!isset(Click::$meta['plugins'][$plugin_name]['settings']->$module_name)) Click::$meta['plugins'][$plugin_name]['settings']->$module_name = (object)array();
    
    $fpath = $p['fpath']."/modules/{$module_name}";
    $p['modules'][$module_name] = array(
      'fpath'=>$fpath,
      'vpath'=>ftov($fpath),
      'uname'=>strtoupper($module_name),
    );
    $m = &$p['modules'][$module_name];
    define("{$p['uname']}_{$m['uname']}_FPATH", $m['fpath']);
    define("{$p['uname']}_{$m['uname']}_VPATH", ftov($m['vpath']));
    define("{$p['uname']}_{$m['uname']}_CACHE_FPATH", $m['fpath']."/cache");
    define("{$p['uname']}_{$m['uname']}_CACHE_VPATH", ftov($m['vpath']."/cache"));

    ensure_writable_folder($m['fpath']."/cache");

    global $current_user, $wpdb;
    $settings = Click::$meta['plugins'][$plugin_name]['settings'];
    foreach(click_glob($fpath."/lib/*.php") as $php)
    {
      require_once($php);
    }
    
    $files = array(
      'bootstrap.php',
      'routes.php',
    );
    $routes = array();
    foreach($files as $f)
    {
      $load = $fpath."/$f";
      if(file_exists($load)) 
      {
        require($load);
      }
    }
    $route_controlled_hooks = array();
    foreach($routes as $r)
    {
      list($path, $hook, $helper, $is_ssl_required) = $r;
      list($path_regex,$keys)= self::path_to_regex($path);
      Click::$meta['route_controlled_hooks'][$path_regex]['keys'] = $keys;
      Click::$meta['route_controlled_hooks'][$path_regex]['listeners'][$plugin_name][$module_name][] = $hook;
      $route_controlled_hooks[$plugin_name][$module_name][$hook] = true;
    }
    

    $hooks = array('callbacks', 'views');
    $seen = array();
    foreach($hooks as $hook)
    {
      foreach(click_glob($m['fpath']."/$hook/*.*") as $f)
      {
        $parts = pathinfo($f);
        $event_name = $parts['filename'];
        if(isset($route_controlled_hooks[$plugin_name][$module_name][$event_name])) continue;
        if(!$seen[$event_name])
        {
          $seen[$event_name] = true;
          add_action($event_name, array(self::$event_dispatcher, $event_name));
          self::$meta['open_hooks'][$event_name][] = array($plugin_name, $module_name);
        }
      }
    }
  }
  
  static function path_to_regex($path)
  {
    $parts = explode("/", $path);
    $keys=array();
    foreach($parts as &$part)
    {
      if (startswith($part, ':'))
      {
        $url_part = '?';
        $key = substr($part, 1);
        $keys[] = $key;
        $part = "(?P<$key>[^\/]+?)";
      } elseif ($part=='*') {
        $part = "(.*?)";
      } else {
        $part = preg_quote($part);
      }
    }
    $pattern = join("\\/",$parts);
    $pattern = $__click['app_routing_prefix'] . $pattern;
    $pattern = "/^$pattern\$/";  
    return array($pattern,$keys);
  }
  
  static function request()
  {
    $params = &Click::$meta['request']['params'];
    $path = array($params['page']);
    if($params['_c']) $path[] = $params['_c'];
    $path = join('/',$path);
    foreach(Click::$meta['route_controlled_hooks'] as $regex=>$route_info)
    {
      if(!preg_match($regex, $path, $matches)) continue;
      foreach($route_info['keys'] as $k)
      {
        if($params[$k]) click_error("Key $k in URL route $regex would mask \$param key.");
        $params[$k] = $matches[$k];
      }
      foreach($route_info['listeners'] as $plugin_name=>$module_info)
      {
        foreach($module_info as $module_name=>$hook_names)
        {
          foreach($hook_names as $hook_name)
          {
            _event($plugin_name, $module_name, $hook_name);
          }
        }
      }
    }
  }
}

register_shutdown_function(array('Click', 'save_all_settings'));
