<?

class ClickWebService
{
  var $gateway = '';
  function __construct($gateway, &$default_params=array())
  {
    $this->gateway = $gateway;
    $this->default_params = &$default_params;
  }
  function load_panel($key, $message="Fetching content...", $qs=array(), $loader_vpath='__internal__')
  {
    if($loader_vpath=="__internal__") $loader_vpath = CLICK_VPATH."/assets/images/loading.gif";
    $qs = $this->prep_qs($key, $qs);
    $qs['p'] = "render_$key";
    $args = array(
      'key'=>$key,
      'gateway'=>$this->gateway,
      'message'=>$message,
      'qs'=>http_build_query($qs),
      'loader_vpath'=>$loader_vpath,
    );
    return eval_haml(CLICK_FPATH."/templates/ajax_panel.haml", $args);
  }
  
  function fire_and_forget($key, $qs=array())
  {
    $qs = $this->prep_qs($key, $qs);
    $args = array(
      'gateway'=>$this->gateway,
      'qs'=>http_build_query($qs),
    );
    return eval_haml(CLICK_FPATH."/templates/fire_and_forget.haml", $args);
  }


  function prep_qs($key, $qs=array())
  {
    $dp = $this->default_params;
    if(is_object($dp)) $dp = get_object_vars($dp);
    $qs = array_merge($dp, array(
      'action'=>$key,
      'args'=>$qs,
    ));
    return $qs;
  }
}
