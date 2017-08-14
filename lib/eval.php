<?

function eval_php($__path__, $__data__=array(), $capture=false)
{
  extract($__data__);
  if($capture) ob_start();
  require($__path__);
  if($capture)
  {
    $__s__ = ob_get_contents();
    ob_end_clean();
  }
  return $__s__;
}
