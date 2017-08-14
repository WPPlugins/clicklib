<?

add_action('admin_footer', 'print_flash');
$__click['flash_messages'] = array();
if(isset($_SESSION['flash_messages']))
{
  $__click['flash_messages'] = $_SESSION['flash_messages'];
  unset($_SESSION['flash_messages']);
}

function flash($msg)
{ 
  global $__click;
  $__click['flash_messages'][] = $msg;
}

function flash_next($msg)
{
  global $__click;
  $_SESSION['flash_messages'][] = $msg;
}

function print_flash()
{
  session_write_close();
  global $__click;
  if(!$__click['flash_messages']) return;
  $args = array('messages'=>$__click['flash_messages']);
  echo eval_haml(CLICK_FPATH."/templates/flash.haml", $args, true);
}