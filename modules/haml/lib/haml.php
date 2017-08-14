<?

function eval_haml($path, &$data=array(), $capture = false)
{

  if(!file_exists($path)) click_error("File $path does not exist for HAMLfication.");
  $unique_name = folderize(ftov($path));
  $php_path = CLICKLIB_HAML_CACHE_FPATH."/$unique_name.php";
  if (is_newer($path, $php_path))
  {
    haml_to_php($path, $php_path);
  }
  return eval_php($php_path,$data,$capture);
}


function haml_to_php($src)
{
  $unique_name = folderize(ftov($src));
  $dst = CLICKLIB_HAML_CACHE_FPATH."/$unique_name.php";
  if (!is_newer($src, $dst)) return $dst;

  $lex = new HamlLexer();
  $lex->N = 0;
  $lex->data = file_get_contents($src);
  $s = $lex->render_to_string();
  file_put_contents($dst, $s);
  return $dst;
}

function str_to_haml($s)
{
  $lex = new HamlLexer();
  $lex->N = 0;
  $lex->data = $s;
  $s = $lex->render_to_string();
  return $s;
}