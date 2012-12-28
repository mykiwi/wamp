<?php

// Page created by MyKiwi [Romain Gautier] <mail@romain.sh>
// http://github.com/mykiwi/wamp


// TODO
//   auto clear cache

// BUG
//   favicon chrome: not in 16x16 if bigger


$path_to_display = './';
$wamp_path       = 'C:/wamp';


$toolbox = array(
    'Regex tester'           => 'http://www.gethifi.com/tools/regex',
    'Regex memo'             => 'http://www.siteduzero.com/tutoriel-3-14616-les-classes-abregees.html#ss_part',
    'SublimeT... Packages'   => 'http://wbond.net/sublime_packages/community',
    'Composer Packages'      => 'https://packagist.org/',
    'Symfony'                => 'http://symfony.com/search?type=',
    'Stack Overflow'         => 'http://stackoverflow.com/',
    'Deviant Art Interfaces' => 'http://browse.deviantart.com/designs/?order=9',
    'Sprite generator'       => 'http://draeton.github.com/stitches/',
    'Subtle Patterns'        => 'http://subtlepatterns.com/',
    'Subtle Sans'            => 'http://www.subtlepatterns.com/subtlesans/',
    'Dafont top 100'         => 'http://www.dafont.com/fr/top.php?fpp=50',
    'Google Web Font'        => 'http://www.google.com/webfonts',
);


$preview_inside_dir = array(
    'screenshot',
    'preview',
);




$cache = array();
// cache
// end cache

$cache['toolbox']['icon']['img'] = 'iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAMAAAAoLQ9TAAAAA3NCSVQICAjb4U/gAAABO1BMVEXu7u7n5+fk5OTi4uLg4ODd3d3X19fV1dXU1NTS0tLPz8+7z+/MzMy6zu65ze65zu7Kysq3zO62zO3IyMjHx8e1yOiyyO2yyOzFxcXExMSyxue0xuexxefDw8OtxeuwxOXCwsLBwcGuxOWsw+q/v7+qweqqwuqrwuq+vr6nv+qmv+m7u7ukvumkvemivOi5ubm4uLicuOebuOeat+e0tLSYtuabtuaatuaXteaZteaatN6Xs+aVs+WTsuaTsuWRsOSrq6uLreKoqKinp6elpaWLqNijo6OFpt2CpNyAo92BotyAo9+dnZ18oNqbm5t4nt57nth7ntp4nt15ndp3nd6ZmZmYmJhym956mtJzm96WlpaVlZVwmNyTk5Nvl9lultuSkpKNjY2Li4uKioqIiIiHh4eGhoZQgtVKfNFdha6iAAAAaXRSTlMA//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////914ivwAAAACXBIWXMAAAsSAAALEgHS3X78AAAAH3RFWHRTb2Z0d2FyZQBNYWNyb21lZGlhIEZpcmV3b3JrcyA4tWjSeAAAAKFJREFUGJVjYIABASc/PwYkIODDxBCNLODEzGiQgCwQxsTlzJCYmAgXiGKVdHFxYEuB8dkTOIS1tRUVocaIWiWI8IiIKKikaoD50kYWrpwmKSkpsRC+lBk3t2NEMgtMu4wpr5aeuHcAjC9vzadjYyjn7w7lK9kK6tqZK4d4wBQECenZW6pHesEdFC9mbK0W7otwsqenqmpMILIn4tIzgpG4ADUpGMOpkOiuAAAAAElFTkSuQmCC';





// Do not edit this part

define('PATH_TO_DISPLAY',   $path_to_display);
define('WAMP_PATH',         realpath($wamp_path).'\\');


/**
 * Clean array 
 * @param array $array
 * @return array
 */
function clearRoot($array)
{
    foreach ($array AS &$item)
        $item = substr($item, strlen(PATH_TO_DISPLAY), -1);
    sort($array, SORT_FLAG_CASE | SORT_STRING);

    return $array;
}


/**
 * Clean alias array
 * @param array $array 
 * @return array
 */
function clearAlias($array)
{
    $alias = array();
    foreach ($array AS &$item)
    {
        $file = explode('/', $item);
        $alias[substr($file[count($file) - 1], 0, -1 * strlen('.conf'))] = $item;
    }

    return $alias;
}


/**
 * Do a request and return the head and the body in an array
 * @param string $url
 * @return array [head, body]
 */
function getRequest($url)
{
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($curl, CURLOPT_HEADER, 1);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_TIMEOUT, 5);
    $contents = curl_exec($curl);

    $header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
    $body = substr($contents, $header_size);

    $header = array();

    $header['http_code']    = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $header['content_type'] = curl_getinfo($curl, CURLINFO_CONTENT_TYPE);

    curl_close($curl);

    return array('header' => $header, 'body' => $body);
}


/**
 * Find the favicon of an url and save it in cache
 * @param string $path 
 * @return string url
 */
function getFavicon($path)
{

    if (!isset($GLOBALS['cache'][md5($path.'/favicon.ico')]['icon']['img']))
    {
        $request = getRequest($path.'/favicon.ico');
        if ($request['header']['http_code'] == 200 && substr($request['header']['content_type'], 0, 6) == 'image/')
        {
            put_in_cache(array(md5($path.'/favicon.ico') => array('icon' => array('img' => base64_encode($request['body'])))));
            return '?get_img='.md5($path.'/favicon.ico').'&type=icon';
        }
        else
        {
            $url = explode('/', $path);
            if ($url[2] != 'localhost')
            {
                $request = getRequest('http://'.$url[2].'/favicon.ico');
                if ($request['header']['http_code'] == 200 && substr($request['header']['content_type'], 0, 6) == 'image/')
                {
                    put_in_cache(array(md5($path.'/favicon.ico') => array('icon' => array('img' => base64_encode($request['body'])))));
                    return '?get_img='.md5($path.'/favicon.ico').'&type=icon';
                }
                else if ($request['header']['http_code'] == 302)
                {
                    var_dump($request);
                }
            }
            put_in_cache(array(md5($path.'/favicon.ico') => array('icon' => array('img' => ''))));
            return '?get_img=toolbox&type=icon';
        }
    }
    else if ('' == $GLOBALS['cache'][md5($path.'/favicon.ico')]['icon']['img'])
        return '?get_img=toolbox&type=icon';
    return '?get_img='.md5($path.'/favicon.ico').'&type=icon';
}


/**
 * Get thumbnail for a directory inside www
 * @param string $path 
 * @return string url
 */
function getPreview($path)
{
    if ($thumbnails = glob(PATH_TO_DISPLAY.$path.'.*')) {
        if (false !== getimagesize($thumbnails[0]))
            return $thumbnails[0];
    }

    foreach ($GLOBALS['preview_inside_dir'] AS $preview)
    {
        if ($thumbnails = glob(PATH_TO_DISPLAY.$path.'/'.$preview.'*'))
            if (false !== getimagesize($thumbnails[0]))
                return $thumbnails[0];
    }

    if (!isset($GLOBALS['cache'][md5($path)]['dir']['img']))
    {
        $img = base64_encode(file_get_contents('http://fakeimg.pl/350x200/444/fff/?text='.urlencode($path)));
        put_in_cache(array(md5($path) => array('dir' => array('img' => $img))));
    }

    return '?get_img='.md5($path).'&type=dir';
}


/**
 * Get thumbnail for the virtual host
 * @param type $vhost 
 * @return type
 */
function getVhostPreview($vhost)
{
    if ($thumbnails = glob(substr($vhost['vhost'], 0, -5).'.*g')) { // png or jpg
        foreach ($thumbnails AS $thumbnail)
            if (false !== getimagesize($thumbnail))
                return '?get_external_img='.realpath($thumbnail);
    }

    foreach ($GLOBALS['preview_inside_dir'] AS $preview)
    {
        if ($thumbnails = glob($vhost['path'].'/'.$preview.'*'))
            if (false !== getimagesize($thumbnails[0]))
                return $vhost['url'].'/'.$thumbnails[0];
    }

    if (!isset($GLOBALS['cache'][md5($vhost['url'])]['dir']['img']))
    {
        $img = base64_encode(file_get_contents('http://fakeimg.pl/350x200/444/fff/?text='.urlencode($vhost['name'])));
        put_in_cache(array(md5($vhost['url']) => array('dir' => array('img' => $img))));
    }

    return '?get_img='.md5($vhost['url']).'&type=dir';
}


/**
 * Get size of path
 * @param string $path directory or file
 * @return int         size in byte
 */
function getDirectorySize($path)
{
    $totalsize  = 0;
    $totalcount = 0;
    $dircount   = 0;

    if ($handle = opendir ($path))
    {
        while (false !== ($file = readdir($handle)))
        {
            $nextpath = $path . '/' . $file;
            if ($file != '.' && $file != '..' && !is_link($nextpath))
            {
                if (is_dir($nextpath))
                {
                    $result = getDirectorySize($nextpath);
                    $totalsize += $result;
                }
                else if (is_file($nextpath))
                    $totalsize += filesize($nextpath);
            }
        }
    }
    closedir($handle);
    
    return $totalsize;
}


/**
 * Convert size in small number
 * @param int $a_bytes 
 * @return string
 */
function format_bytes($a_bytes)
{
    if ($a_bytes < 1024)
        return $a_bytes .' o';
    elseif ($a_bytes < 1048576)
        return round($a_bytes / 1024) .' Ko';
    elseif ($a_bytes < 1073741824)
        return round($a_bytes / 1048576) . ' Mo';
    elseif ($a_bytes < 1099511627776)
        return round($a_bytes / 1073741824) . ' Go';
    elseif ($a_bytes < 1125899906842624)
        return round($a_bytes / 1099511627776) .' To';
    elseif ($a_bytes < 1152921504606846976)
        return round($a_bytes / 1125899906842624) .' Po';
    elseif ($a_bytes < 1180591620717411303424)
        return round($a_bytes / 1152921504606846976) .' Eo';
    elseif ($a_bytes < 1208925819614629174706176)
        return round($a_bytes / 1180591620717411303424) .' Zo';
    else
        return round($a_bytes / 1208925819614629174706176) .' Yo';
}


/**
 * Get size of path and convert it
 * @param string $path 
 * @return string
 */
function getSizeFromCache($path)
{
    return format_bytes(getDirectorySize($path));
}



/**
 * Get the url of an alias from the conf file
 * @param string $path 
 * @return string url
 */
function getAliasUrl($path)
{
    $handle = @fopen($path, 'r');
    if ($handle)
    {
        while (($buffer = fgets($handle)) !== false)
        {
            if (preg_match('#alias (.*) ".*"#i', $buffer, $match))
            {
                fclose($handle);
                return ($match[1]);
            }
        }
        fclose($handle);
    }

    return null;
}


/**
 * Virtual host is enable in apache? and set the variable $vhost_include_not_define
 * @param string $httpd_conf path of httpd.conf
 * @return bool
 */
function vhostIsEnable($httpd_conf)
{
    $return = true;

    $handle = fopen($httpd_conf, 'r');
    if ($handle)
    {
        while (($buffer = fgets($handle)) !== false)
        {
            $buffer = trim($buffer);
            if (preg_match('#LoadModule vhost_alias_module modules/mod_vhost_alias.so#i', $buffer, $match))
            {
                if (substr($buffer, 0, 1) == '#')
                    $return = false;
            }
            else if ($buffer == 'Include "'.WAMP_PATH.'vhost\*.conf"')
                $GLOBALS['vhost_include_not_define'] = false;
        }
        fclose($handle);
    }
    return $return;
}


/**
 * Parse vhost conf and return the url
 * @param string $vhost path 
 * @return string url
 */
function getVhostUrl($vhost)
{
    $handle = fopen($vhost, 'r');
    if ($handle)
    {
        while (($buffer = fgets($handle)) !== false)
        {
            if (preg_match('#ServerName (.*)#i', $buffer, $match))
            {
                fclose($handle);

                return 'http://'.$match[1].'/';
            }
        }
        fclose($handle);
    }

    return null;
}


/**
 * Generate the string cache
 * @param array   $array 
 * @param string  $cache 
 * @param string  $base  
 * @return string
 */
function generateCache($array, $cache = '', $base = '')
{
    if ($cache == '')
        $base = '$cache';
    else
        $cache .= PHP_EOL;

    foreach ($array AS $key=>$content)
    {
        if (!is_array($content))
            $cache .= sprintf('%s[\'%s\'] = \'%s\';%s', $base, $key, $content, PHP_EOL);
        else
        {
            $cache   .= sprintf('%s[\'%s\'] = array();', $base, $key);
            $new_base = sprintf('%s[\'%s\']', $base, $key);
            $cache    = generateCache($content, $cache, $new_base);
        }
    }

    return $cache;
}


/**
 * Overwrite this file and add the cache
 * @param array $content to put in cache
 */
function put_in_cache($content)
{
    $file       = file_get_contents(__FILE__);
    $file_begin = substr($file, 0, strpos($file, '//'.' end cache'));
    $file_end   = substr($file, strpos($file, '//'.' end cache'));

    $new_cache = generateCache($content);

    file_put_contents(__FILE__, $file_begin.$new_cache.$file_end);
}


/**
 * Get document root of a virtual host
 * @param string $vhost path 
 * @return string root
 */
function getVhostPath($vhost)
{
    $handle = fopen($vhost, 'r');
    if ($handle)
    {
        while (($buffer = fgets($handle)) !== false)
        {
            if (preg_match('#DocumentRoot (.*)#i', $buffer, $match))
            {
                fclose($handle);
                return $match[1];
            }
        }
        fclose($handle);
    }
    return null;
}




// page get
if (isset($_GET['get_img']) && isset($_GET['type']))
{
    header("Content-type: image/png");
    if (!isset($GLOBALS['cache'][$_GET['get_img']][$_GET['type']]['img']))
        die(base64_decode($GLOBALS['cache']['toolbox']['icon']['img']));
    die(base64_decode($GLOBALS['cache'][$_GET['get_img']][$_GET['type']]['img']));
}

if (isset($_GET['get_external_img']))
{
    header("Content-type: image/png");
    die(file_get_contents($_GET['get_external_img']));
}

if (isset($_GET['get_size']))
    die(@getSizeFromCache($_GET['get_size']));

if (isset($_GET['phpinfo']))
    die(phpinfo());
// end page get




// main code www
$root = clearRoot(glob(PATH_TO_DISPLAY.'*/'));
foreach ($root AS &$path)
{
    $structure = array();
    $structure['name'] = $path;
    $structure['url']  = PATH_TO_DISPLAY.$path;
    $structure['img']  = getPreview($path);
    $path = $structure;
}


// main code vhost
$vhost_include_not_define = true;
if (($apache_conf = glob(WAMP_PATH.'bin/apache/apache*/conf/httpd.conf')) > 0)
{
    $apache_conf = realpath($apache_conf[0]);
    if (($vhostIsEnable = vhostIsEnable($apache_conf)))
    {
        $vhosts = glob(WAMP_PATH.'vhost/*.conf');

        foreach ($vhosts AS &$vhost)
        {
            $structure = array();

            $vhost_path = explode('\\', realpath($vhost));
            $structure['name'] = substr($vhost_path[count($vhost_path) - 1], 0, -5);

            $structure['vhost']= $vhost;
            $structure['path'] = getVhostPath($vhost);
            $structure['url']  = getVhostUrl($vhost);
            $structure['img']  = getVhostPreview($structure);
            $vhost = $structure;
        }
    }
}


// main code alias
$alias = clearAlias(glob(WAMP_PATH.'alias/*.conf'));
foreach ($alias AS $name=>&$path)
{
    $structure = array();
    $structure['name'] = $name;
    $structure['url']  = getAliasUrl($path);
    $structure['img']  = getFavicon('http://localhost/'.$name);
    $path = $structure;
}
sort($alias);


// main code toolbox
foreach ($toolbox AS $name=>&$url)
{
    $structure = array();
    $structure['name'] = $name;
    $structure['url']  = $url;
    $structure['img']  = getFavicon($url);
    $url = $structure;
}


// main code server
preg_match("([0-9\.]+)", apache_get_version(), $match);
$apache_version = $match[0];

$php_version = phpversion();

preg_match("([0-9\.]+)", mysql_get_server_info(), $match);
$mysql_version = $match[0];

?>
<!DOCTYPE html>
<html>
    <head>
        <title>localhost</title>
        <link href=".bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href=".bootstrap/css/application.css" rel="stylesheet">
    </head>
    <body>
        <div id="page">
            <a id="altf4" title="an ALT F4 product" href="http://altf4.me"></a>
            <div class="container">
                <div class="row">
                    <div class="span12">

                        <!-- www -->
                        <span class="label label-info" style="margin-top:11px;margin-bottom:10px">www</span>
                        <div class="row-fluid">
                            <?php foreach ($root AS $nb=>&$dir): ?>
                            <?php if ($nb % 4 == 0) echo ($nb != 0 ? '</ul>' : '').'<ul class="thumbnails">'; ?> 
                                <li class="span3">
                                    <a href="<?php echo $dir['url']; ?>" class="thumbnail" style="background:white">
                                        <img src="<?php echo $dir['img']; ?>">
                                    </a>
                                    <div class="name"><?php echo substr($dir['name'], 0, 20).(strlen($dir['name']) > 20 ? '...' : ''); ?></div>
                                    <div class="space" data-path="<?php echo PATH_TO_DISPLAY.$dir['name']; ?>"></div>
                                </li>
                            <?php endforeach; ?>
                            </ul>
                        </div>

                        <!-- vhost -->
                        <span class="label label-inverse" style="margin-top:11px;margin-bottom:10px">vhost</span>
                        <div class="row-fluid">
                            <?php 
                            if ($vhostIsEnable)
                            {
                                foreach ($vhosts AS $nb=>&$vhost): ?>
                                <?php if ($nb % 4 == 0) echo ($nb != 0 ? '</ul>' : '').'<ul class="thumbnails">'; ?> 
                                    <li class="span3">
                                        <a href="<?php echo $vhost['url']; ?>" class="thumbnail" style="background:white">
                                            <img src="<?php echo $vhost['img']; ?>">
                                        </a>
                                        <div class="name"><?php echo substr($vhost['name'], 0, 20).(strlen($vhost['name']) > 20 ? '...' : ''); ?></div>
                                        <div class="space" data-path="<?php echo $vhost['path']; ?>"></div>
                                    </li>
                                <?php endforeach; ?>
                                </ul>
                                <?php
                            }

                            // Example
                            $vhost_default_conf = "&lt;VirtualHost *:80>\n\tServerName <strong style='color:#D14'>PROJECT</strong>.localhost.com\n\tServerAlias <strong style='color:#D14'>PROJECT</strong>.localhost\n\n\tDocumentRoot ".strtolower(WAMP_PATH)."www_<strong style='color:#D14'>PROJECT</strong>\n\t&lt;directory ".strtolower(WAMP_PATH)."www_<strong style='color:#D14'>PROJECT</strong>>\n\t\tallow from all\n\t&lt;/directory>\n\n\tErrorLog ".strtolower(WAMP_PATH)."logs\<strong style='color:#D14'>PROJECT</strong>_apache_error.log\n&lt;/VirtualHost>";
                            ?>
                            <span id="vhost-example-button" class="label label-warning" style="cursor:help;float:right">Example vhost</span>
                            <div id="vhost-example" style="<?php if ($vhostIsEnable): ?>display:none<?php endif; ?>">
                                <pre><?php echo ($vhost_default_conf) ?></pre>
                                <?php if ($vhost_include_not_define): ?>
                                    <small>Add <code>Include "<?php echo WAMP_PATH; ?>vhost\*.conf"</code> in <code><?php echo sprintf('%s<strong>%s</strong>', substr($apache_conf, 0, -10), substr($apache_conf, -10)); ?></code></small><br/>
                                <?php endif; ?>
                                <small>You must write this conf inside <code><?php echo WAMP_PATH ?>vhost\<strong>PROJECT</strong>.conf</code> and create a directory <code><?php echo WAMP_PATH ?>www_<strong>PROJECT</strong></code></small><br/>
                                <small>Don't forget to add your domains in <code>C:\Windows\System32\drivers\etc\hosts</code> like <code>127.0.0.1 <strong>PROJECT</strong>.localhost.com <strong>PROJECT</strong>.localhost</code>
                            </div>
                        </div>

                        <!-- infos -->
                        <span class="label label-info" style="margin-top:11px;margin-bottom:10px">server</span>
                        <div class="row-fluid" style="padding-left:20px">
                            <div class="span2">
                                <span class="label">Apache</span> <span class="label label-warning"><?php echo $apache_version; ?></span><br/>
                                <span class="label">PHP</span>    <span class="label label-warning"><?php echo $php_version;    ?></span><br/>
                                <span class="label">MySQL</span>  <span class="label label-warning"><?php echo $mysql_version;  ?></span>
                            </div>
                            <div class="span10" style="margin-left:-20px">
                                <span class="label label-inverse">PHP extensions</span>
                                <?php foreach (get_loaded_extensions() AS $extension): ?>
                                    <span class="label"><?php echo $extension; ?></span> 
                                <?php endforeach; ?>
                            </div>
                        </div>

                    </div>
                    <div class="span2">

                        <!-- alias -->
                        <span class="label label-inverse" style="margin-top:11px;margin-bottom:10px">alias</span>
                        <div class="row-fluid" style="padding-left:5px">
                            <?php
                            foreach ($alias AS $item)
                                echo sprintf('<a href="%s"><i class="icon-test" style="background:url(%s);"></i> %s</a><br/>', $item['url'], $item['img'], $item['name']);
                            ?>
                        </div>

                        <!-- toolbox -->
                        <span class="label label-info" style="margin-top:11px;margin-bottom:10px">toolbox</span>
                        <div class="row-fluid" style="padding-left:5px">
                            <?php
                            foreach ($toolbox AS $item)
                                echo sprintf('<a href="%s" target="_blank"><i class="icon-test" style="background:url(%s);"></i> %s</a><br/>', $item['url'], $item['img'], $item['name']);
                            ?>
                        </div>

                    </div>
                </div>
            </div>

        </div>
        <script src=".bootstrap/js/jquery.min.js"></script>
        <script src=".bootstrap/js/bootstrap.min.js"></script>
        <script src=".bootstrap/js/application.js"></script>
    </body>
</html>
