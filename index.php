<?php
define('APP_DEBUG', true);
// Absolute path to your installation, ex: /var/www/mywebsite
$base_dir  = __DIR__;
# ex: /var/www
$doc_root  = preg_replace("!{$_SERVER['SCRIPT_NAME']}$!", '', $_SERVER['SCRIPT_FILENAME']);
# ex: '' or '/mywebsite'
$base_url  = preg_replace("!^{$doc_root}!", '', $base_dir);
$protocol  = empty($_SERVER['HTTPS']) ? 'http' : 'https';
$port      = $_SERVER['SERVER_PORT'];
$disp_port = ($protocol == 'http' && $port == 80 || $protocol == 'https' && $port == 443) ? '' : ":$port";
$domain    = $_SERVER['SERVER_NAME'];
# Ex: 'http://example.com', 'https://example.com/mywebsite', etc.
$full_url  = "{$protocol}://{$domain}{$disp_port}{$base_url}";

define("ROOT_PATH", $base_dir . DIRECTORY_SEPARATOR);
define("DOC_PATH", $doc_root . DIRECTORY_SEPARATOR);
define("BASE_URL", $base_url . DIRECTORY_SEPARATOR);
define("FULL_URL", $full_url . DIRECTORY_SEPARATOR);
define("UPLOAD_PATH", dirname($base_dir) . '/public' );

if ( gethostname() != 'cdfdc' ) {
  define('ESF_URL', "{$protocol}://{$domain}/c_esf/public/esf/");
  define('ZP_URL', "{$protocol}://{$domain}/c_esf/public/recruit/");
  define("UPLOAD_URL", "{$protocol}://{$domain}/c_esf/public");
} else {
  define("UPLOAD_URL", 'http://esf.0736fdc.com');
  define('ESF_URL', 'http://esf.0736fdc.com/');
  define('ZP_URL', 'http://zp.0736fdc.com/');
}


$sitelist = include ROOT_PATH."Conf" . DIRECTORY_SEPARATOR . "sitelist.php";
if (is_array($sitelist)) {
  foreach ($sitelist as $key => $site) {
    if ( strpos($full_url, $site['domain'])) {
      define("SITEID", $site['siteid']);
      define("DEFAULT_STYLE", $site['default_style']);
      define("TEMPLATE", $site['template']);
      define('IS_SITE',true);
      break;
    }
  }
}
if (!defined("IS_SITE")) {
  define("SITEID", 1);
  define("DEFAULT_STYLE", $sitelist[SITEID]['default_style']);
  define("TEMPLATE", $sitelist[SITEID]['template']);
}
/*$subDomain  = strtolower( substr( $_SERVER['HTTP_HOST'], 0, strpos( $_SERVER['HTTP_HOST'], '.' ) ) );
$subDomain = $subDomain ? $subDomain : 'www';
switch ($subDomain) {
  case '':
  break;
  case '':
  default:
  $siteid = 1;
  break;
}*/

require './Framework/ThinkPHP.php';