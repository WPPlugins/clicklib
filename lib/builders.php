<?

function link_to($text, $path)
{
  $attrs = array(
    'href'=>$path
  );
  $args = func_get_args();
  $s = splice_attrs($attrs, $args,2);
  $text = h($text);
	return "<a $s >$text</a>";
}

function button_to($text, $path)
{
  $attrs = array(
    'class'=>'button',
    'onclick'=>"document.location='".j($path)."';",
  );
  $args = func_get_args();
  $s = splice_attrs($attrs, $args,2);
  $text = __($text);
  $v = h($text);
  $path_js = j($path);
  $html = submit_tag($text, 'onclick', "document.location='$path_js';return false;");
  return $html;
}

function page_to($text, $path, $qs=null)
{
  return link_to($text, page_url($path, $qs));
}

function action_to($text, $action_name='', $other_qs_args=array())
{
  return link_to($text, action($action_name,$other_qs_args));
}

function page_url($path, $qs=null)
{
  $pieces = explode('/',$path);
  $page = array_shift($pieces);
  $path = join('/',$pieces);
  $args = array('page'=>$page);
  $args['_c'] = $path;
  if($qs) $args['_c']."?".http_build_query($qs);
  return action('', $args);
}

function action($name, $qs_args=array())
{
  $pieces = Url::explode(Click::$meta['request']['current_url']);
  if($name)
  {
    $pieces['query_params']['_clickaction'] = $name;
  } else {
   unset($pieces['query_params']['_clickaction']);
  }
  $pieces['query_params'] = array_merge($pieces['query_params'], $qs_args);
  $url = Url::implode($pieces);
  return $url;  
}


function mail_to($email, $text=null)
{
	$start=2;
	if ($text==null)
	{
		$text=$email;
		$start=1;
	}
  $attrs = array( );
  $args = func_get_args();
  $args = array_shift($args);
  $s = splice_attrs($attrs, $args,$start);
  $email = h($email);
  $text = __($text);
  return "<a href=\"mailto:$email\" $s>$text</a>";
}


function stylesheet($name)
{
  return "<link rel='stylesheet' href='".ROOT_VPATH."/css/$name.css'/>\n";
}

function call_to($display, $number)
{
  return link_to($display, "callto:+1".$number);
}