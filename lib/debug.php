<?

function dprint($s,$shouldExit=true)
{
  echo "<pre>";
  ob_start();
  var_dump($s);
  $out = ob_get_contents();
  ob_end_clean();
  echo htmlentities($out);
  echo "</pre>";
  if ($shouldExit) click_error('Development stop');
}


function click_error($err, $data=null)
{
  if ($data)
  {
    $err = $err."<br/><pre>".htmlentities(s_var_export($data))."</pre>";
  }
  echo( "<table>");
  foreach(debug_backtrace() as $trace)
  {
    echo( "<tr>");
    echo( "<td>");
    if (array_key_exists('file', $trace)) echo( htmlentities($trace['file']));
    echo( "</td>");
    echo( "<td>");
    if (array_key_exists('line', $trace)) echo( htmlentities($trace['line']));
    echo( "</td>");
    echo( "<td>");
    if (array_key_exists('function', $trace)) echo( htmlentities($trace['function']));
    echo( "</td>");
    echo( "</tr>");
  }
  echo( "</table>");
  trigger_error($err, E_USER_ERROR);
}

function s_var_export($v)
{
  ob_start();
  var_export($v);
  $s = ob_get_contents();
  ob_end_clean();
  return $s;
}