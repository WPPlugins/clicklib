<?

function normalize_path($path)
{
  $parts = explode('/', $path);
  $new_path = array();
  foreach($parts as $part)
  {
    $skip = false;
    if ($part == "..")
    {
      array_pop($new_path);
      continue;
    }
    $new_path[] = $part;
  }
  $new_path = join('/',$new_path);
  return $new_path;
}


function ensure_writable_folder($path)
{
  $path = normalize_path($path);
  if (!file_exists($path))
  {
    if (!mkdir($path, 0775, true)) click_error("Failed to mkdir on $path");
    chmod($path,0775);
    if (!file_exists($path)) click_error("Failed to verify $path");
  }
}

function click_glob()
{
  $args = func_get_args();
  $res = call_user_func_array('glob', $args);
  if(!is_array($res)) $res = array();
  return $res;
}

function is_newer($src,$dst)
{
  if (!file_exists($dst)) return true;
  if(!file_exists($src)) return false;
  $ss = stat($src);
  $ds = stat($dst);
  $st = max($ss['mtime'], $ss['ctime']);
  $dt = max($ds['mtime'], $ds['ctime']);
  return $st>$dt;
}


function ftov($fpath)
{
  $path = substr($fpath, strlen(ROOT_FPATH));
  if (ROOT_VPATH)
  {
    $path = ROOT_VPATH . $path;
  }
  return $path;
}

function vpath($path)
{
  if (ROOT_VPATH)
  {
    $path = ROOT_VPATH . $path;
  }
  return $path;
}

function folderize()
{
  $args = func_get_args();
  for($i=0;$i<count($args);$i++) $args[$i] = strtolower(preg_replace("/[^A-Za-z0-9]/", '_', $args[$i]));
  return join('_',$args);
}

function clear_cache($fpath)
{
  if(!endswith($fpath, '/cache')) click_error("$fpath doesn't look like a cache path.");
  $cmd = "rm -rf $fpath";
  click_exec($cmd);
  ensure_writable_folder($fpath);
}

