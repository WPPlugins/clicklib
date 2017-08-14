<?php
/*
 Plugin Name: Click
 Plugin URI: http://www.benallfree.com/click
 Description: Core MVC platform
 Author: Ben Allfree
 Version: 1.1.2
 Author URI: http://www.benallfree.com
 Text Domain: libraries
 License: GPL
 Copyright 2011  Launchpoint Software Inc., (email ben@launchpointsoftware.com)
 */

require_once('bootstrap.php');
Click::init($plugin_fpath,true);


//$GLOBALS["{$parts['basename']}_manager"] = new ClickPlugin($plugin_fpath);


foreach(click_glob($plugin_fpath."/modules/*") as $m)
{
  $module_name = basename($m);
}

