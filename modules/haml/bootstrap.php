<?
$parser_src = CLICKLIB_HAML_FPATH . "/codegen/HamlLexer.class.plex";
$parser_dst = CLICKLIB_HAML_FPATH . "/codegen/HamlLexer.class.php";

if (is_newer($parser_src,$parser_dst))
{
  require_once 'LexerGenerator.php';
  ob_start();
  $lex = new PHP_LexerGenerator($parser_src);
  ob_get_clean();
}

Click::$meta['autoloads'][] = CLICKLIB_HAML_FPATH .'/codegen';
