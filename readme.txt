=== Click ===
Contributors: benallfree
Tags: developer, library, mvc, haml, sass, activerecord
Requires at least: 3.1.2
Tested up to: 3.1.2
Stable tag: 1.1.2

Click is an MVC library for developers with advanced support for HAML, SASS, ActiveRecord, and other modern programming methodologies.

== Description ==

Click makes it easy to implement an MVC pattern in WordPress. Your plugins will be more organized, stable, and standards-compliant. It has special trappings for easily hooking into WordPress events. Make widgets, dedicated admin pages, and more.

Features:

* Dynamic loading of optional Click modules like HAML
* Efficient overhead - if Click is not used, Click is not loaded
* HTML tag builders
* Controller base class
* Clean organization of controllers and views
* datetime functions
* Debugging functions
* File functions
* Form builder functions
* HTTP, HTML, Request, and URL helper functions
* ActiveRecord
* String manipulation
* Support for HAML views
* Web Services REST architecture 

== Installation ==

Click can be installed as a stand-alone WP plugin or as a subfolder of your plugin. If you ship your plugin, you must include a copy of Click with it as a subfolder so users don't have to install this developer plugin. If the user is running multiple plugins that ship with Click, only the first one will be loaded.

Please remember that WordPress.org SVN does not support svn:externals. 

To install Click as a separate plugin:

    cd wp-content/plugins
    svn checkout http://plugins.svn.wordpress.org/clicklib/trunk clicklib
    
To install in a subfolder of your own plugin:

    cd wp-content/plugins/my_plugin
    svn checkout http://plugins.svn.wordpress.org/clicklib/trunk clicklib
    find clicklib -name .svn | xargs rm -rf    # remove all the .svn folders
    svn add clicklib
    
Now, create your plugin PHP file as follows:

wp-content/plugins/my_plugin/my_plugin.php

    <?php
    /*
     -- plugin meta info, you know the drill --
    */

    $plugin_fpath = dirname(__FILE__);
    $lib = 'clicklib/click.php';
    if(file_exists($plugin_fpath."/$lib")) require($lib); else require($plugin_fpath."/../$lib");

That will load first from the main clicklib plugin, or from the local subfolder copy if the main plugin is missing.
    
That's it!


== Changelog ==

= 1.1.2 =
* FIXED: click fails to create cache folders when WP is in a subfolder
* FIXED: click miscalculates paths when WP is in subfolder

= 1.1.0 =
* Major refactoring
* Support for modules within plugins
* Added ActiveRecord

= 1.0.0 =
* Initial commit
