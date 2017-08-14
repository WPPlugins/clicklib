<?

/*
This class is a connector between wordpress event hooks and Click events. It makes it possible to map a WP event to a Click event,
which in turn maps to files in the file system.
*/
class ClickEventDispatcher
{
  function __call($event_name, $args)
  {
    event($event_name);
  }
}

