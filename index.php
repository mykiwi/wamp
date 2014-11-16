<?php
/*
 * Copyright (c) 2012 Romain Gautier
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

// Page created by Romqin [Romain Gautier] <mail@romain.sh>
// http://github.com/romqin/wamp


// TODO
//   auto clear cache

// BUG
//   favicon chrome: not in 16x16 if bigger


$path_to_display = './';
$wamp_path       = 'C:/wamp';

$dirs_to_ignore  = array(
    '.',
    '..',
);
$vhost_to_ignore = array(
    'localhost',
);

//$custom_vhost_path_config = '/etc/apache2/vhosts';

$toolbox = array(
    'phpinfo()'              => '?phpinfo',
    'Regex tester'           => 'http://regex101.com/',
    'SublimeT... Packages'   => 'http://wbond.net/sublime_packages/community',
    'Composer Packages'      => 'https://packagist.org/',
    'Bower Pacakges'         => 'http://bower.io/search/',
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
define('IS_WINDOWS',        preg_match('#^(winnt|cygwin)$#i', PHP_OS));


/**
 * Clean array
 * @param  array $array
 * @param  array $lirst
 * @return array
 */
function clearRoot($array, $list)
{
    foreach ($array as $k => &$item) {
        $item = substr($item, strlen(PATH_TO_DISPLAY), -1);
        if (in_array($item, $list)) {
            unset($array[$k]);
        }
    }
    sort($array, SORT_FLAG_CASE | SORT_STRING);

    return $array;
}


/**
 * Clean alias array
 * @param  array $array
 * @return array
 */
function clearAlias($array)
{
    $alias = array();
    foreach ($array as &$item) {
        $file = explode('/', $item);
        $alias[substr($file[count($file) - 1], 0, -1 * strlen('.conf'))] = $item;
    }

    return $alias;
}


/**
 * Do a request and return the head and the body in an array
 * @param  string $url
 * @return array       [head, body]
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

    return array(
        'header' => $header,
        'body'   => $body,
    );
}


/**
 * Find the favicon of an url and save it in cache
 * @param  string $path
 * @return string       url
 */
function getFavicon($path)
{
    if (!isset($GLOBALS['cache'][md5($path.'/favicon.ico')]['icon']['img'])) {
        $request = getRequest($path.'/favicon.ico');
        if ($request['header']['http_code'] == 200 && substr($request['header']['content_type'], 0, 6) == 'image/') {
            put_in_cache(array(md5($path.'/favicon.ico') => array('icon' => array('img' => base64_encode($request['body'])))));

            return '?get_img='.md5($path.'/favicon.ico').'&type=icon';
        } else {
            $url = explode('/', $path);
            if (!isset($url[2])) {
                return '?get_img=toolbox&type=icon';
            } elseif ($url[2] != 'localhost') {
                $request = getRequest('http://'.$url[2].'/favicon.ico');
                if ($request['header']['http_code'] == 200 && substr($request['header']['content_type'], 0, 6) == 'image/') {
                    put_in_cache(array(md5($path.'/favicon.ico') => array('icon' => array('img' => base64_encode($request['body'])))));

                    return '?get_img='.md5($path.'/favicon.ico').'&type=icon';
                } elseif ($request['header']['http_code'] == 302) {
                    //var_dump($request);
                }
            }
            put_in_cache(array(md5($path.'/favicon.ico') => array('icon' => array('img' => ''))));

            return '?get_img=toolbox&type=icon';
        }
    } elseif ('' == $GLOBALS['cache'][md5($path.'/favicon.ico')]['icon']['img']) {
        return '?get_img=toolbox&type=icon';
    }

    return '?get_img='.md5($path.'/favicon.ico').'&type=icon';
}


/**
 * Get thumbnail for a directory inside www
 * @param  string $path
 * @return string       url
 */
function getPreview($path)
{
    if ($thumbnails = glob(PATH_TO_DISPLAY.$path.'.*')) {
        if (false !== getimagesize($thumbnails[0])) {
            return $thumbnails[0];
        }
    }

    foreach ($GLOBALS['preview_inside_dir'] as $preview) {
        if ($thumbnails = glob(PATH_TO_DISPLAY.$path.'/'.$preview.'*')) {
            if (false !== getimagesize($thumbnails[0])) {
                return $thumbnails[0];
            }
        }
    }

    if (!isset($GLOBALS['cache'][md5($path)]['dir']['img'])) {
        $img = base64_encode(file_get_contents('http://fakeimg.pl/350x200/444/fff/?text='.urlencode($path)));
        put_in_cache(array(md5($path) => array('dir' => array('img' => $img))));
    }

    return '?get_img='.md5($path).'&type=dir';
}


/**
 * Get thumbnail for the virtual host
 * @param  type $vhost
 * @return type
 */
function getVhostPreview($vhost)
{
    if ($thumbnails = glob($vhost['path_config'].'/'.$vhost['name'].'.*g')) { // png or jpg
        foreach ($thumbnails as $thumbnail) {
            if (false !== getimagesize($thumbnail)) {
                return '?get_external_img='.realpath($thumbnail);
            }
        }
    }

    foreach ($GLOBALS['preview_inside_dir'] as $preview) {
        if ($thumbnails = glob($vhost['path'].'/'.$preview.'*')) {
            if (false !== getimagesize($thumbnails[0])) {

                return $vhost['url'].'/'.substr($thumbnails[0], strlen($vhost['path']));
            }
        }
    }

    if (!isset($GLOBALS['cache'][md5($vhost['url'])]['dir']['img'])) {
        $img = base64_encode(file_get_contents('http://fakeimg.pl/350x200/444/fff/?text='.urlencode($vhost['name'])));
        put_in_cache(array(md5($vhost['url']) => array('dir' => array('img' => $img))));
    }

    return '?get_img='.md5($vhost['url']).'&type=dir';
}


/**
 * Get size of path
 * @param  string $path directory or file
 * @return int          size in byte
 */
function getDirectorySize($path)
{
    $totalsize  = 0;

    if ($handle = opendir($path)) {
        while (false !== ($file = readdir($handle))) {
            $nextpath = $path.'/'.$file;
            if ($file != '.' && $file != '..' && !is_link($nextpath)) {
                if (is_dir($nextpath)) {
                    $result = getDirectorySize($nextpath);
                    $totalsize += $result;
                } elseif (is_file($nextpath)) {
                    $totalsize += filesize($nextpath);
                }
            }
        }
    }
    closedir($handle);

    return $totalsize;
}


/**
 * Convert size in small number
 * @param  int    $a_bytes
 * @return string
 */
function format_bytes($a_bytes)
{
    if ($a_bytes < 1024) {
        return $a_bytes.' o';
    } elseif ($a_bytes < 1048576) {
        return round($a_bytes / 1024).' Ko';
    } elseif ($a_bytes < 1073741824) {
        return round($a_bytes / 1048576).' Mo';
    } elseif ($a_bytes < 1099511627776) {
        return round($a_bytes / 1073741824).' Go';
    } elseif ($a_bytes < 1125899906842624) {
        return round($a_bytes / 1099511627776).' To';
    } elseif ($a_bytes < 1152921504606846976) {
        return round($a_bytes / 1125899906842624).' Po';
    } elseif ($a_bytes < 1180591620717411303424) {
        return round($a_bytes / 1152921504606846976).' Eo';
    } elseif ($a_bytes < 1208925819614629174706176) {
        return round($a_bytes / 1180591620717411303424).' Zo';
    } else {
        return round($a_bytes / 1208925819614629174706176).' Yo';
    }
}


/**
 * Get size of path and convert it
 * @param  string $path
 * @return string
 */
function getSizeFromCache($path)
{
    return format_bytes(getDirectorySize($path));
}


/**
 * Get the url of an alias from the conf file
 * @param  string $path
 * @return string       url
 */
function getAliasUrl($path)
{
    $handle = @fopen($path, 'r');
    if ($handle) {
        while (($buffer = fgets($handle)) !== false) {
            if (preg_match('#alias (.*) ".*"#i', $buffer, $match)) {
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
 * @param  string $httpd_conf path of httpd.conf
 * @return bool
 */
function vhostIsEnable($httpd_conf)
{
    $return = true;

    $handle = @fopen($httpd_conf, 'r');
    if ($handle) {
        while (($buffer = fgets($handle)) !== false) {
            $buffer = trim($buffer);
            if (preg_match('#LoadModule vhost_alias_module modules/mod_vhost_alias.so#i', $buffer, $match)) {
                if (substr($buffer, 0, 1) == '#') {
                    $return = false;
                }
            } elseif ($buffer == 'Include "'.WAMP_PATH.'vhost\*.conf"') {
                $GLOBALS['vhost_include_not_define'] = false;
            }
        }
        fclose($handle);
    }

    return $return;
}


/**
 * Parse vhost conf and return the url
 * @param  string $vhost config
 * @return string        url
 */
function getVhostUrl($vhost)
{
    if (preg_match('#ServerName\s+(.*)#i', $vhost, $match)) {

        return 'http://'.$match[1].'/';
    }

    return null;
}


/**
 * Generate the string cache
 * @param  array  $array
 * @param  string $cache
 * @param  string $base
 * @return string
 */
function generateCache($array, $cache = '', $base = '')
{
    if ($cache == '') {
        $base = '$cache';
    } else {
        $cache .= PHP_EOL;
    }

    foreach ($array as $key => $content) {
        if (!is_array($content)) {
            $cache .= sprintf('%s[\'%s\'] = \'%s\';%s', $base, $key, $content, PHP_EOL);
        } else {
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
 * @param  string $vhost config
 * @return string        root
 */
function getVhostPath($vhost)
{
    if (preg_match('#DocumentRoot "?([^"]*)"#i', $vhost, $match)) {

        return $match[1];
    }

    return null;
}


function getVhosts($vhost_config_path)
{
    $vhost_config_path = realpath($vhost_config_path);
    $content = file_get_contents($vhost_config_path);

    preg_match_all('#<VirtualHost[^>]+>(.+)<\/VirtualHost>#sU', $content, $matches);

    $structure = array();
    foreach ($matches[1] as $vhost) {

        $v = array(
            'url'         => getVhostUrl($vhost),
            'path'        => getVhostPath($vhost),
            'path_config' => $vhost_config_path,
        );
        $v['name'] = substr($v['url'], 7, -1);
        $v['img']  = getVhostPreview($v);

        if (isset($GLOBALS['vhost_to_ignore']) && in_array($v['name'], $GLOBALS['vhost_to_ignore'])) {
            continue;
        }

        $structure[] = $v;
    }

    return $structure;
}




// page get
if (isset($_GET['get_img']) && isset($_GET['type'])) {
    header("Content-type: image/png");
    if (!isset($GLOBALS['cache'][$_GET['get_img']][$_GET['type']]['img'])) {
        echo ase64_decode($GLOBALS['cache']['toolbox']['icon']['img']);

        return;
    }
    echo base64_decode($GLOBALS['cache'][$_GET['get_img']][$_GET['type']]['img']);

    return;
}

if (isset($_GET['get_external_img'])) {
    header("Content-type: image/png");
    echo file_get_contents($_GET['get_external_img']);

    return;
}

if (isset($_GET['get_size'])) {
    echo @getSizeFromCache($_GET['get_size']);

    return;
}

if (isset($_GET['phpinfo'])) {
    phpinfo();

    return;
}
// end page get




$root  = clearRoot(glob(PATH_TO_DISPLAY.'*/'), $dirs_to_ignore);
foreach ($root as &$path) {
    $structure = array();
    $structure['name'] = $path;
    $structure['url']  = PATH_TO_DISPLAY.$path;
    $structure['img']  = getPreview($path);
    $path = $structure;
}


// main code vhost
$vhost_include_not_define = true;
if (($apache_conf = glob(WAMP_PATH.'bin/apache/apache*/conf/httpd.conf')) > 0 || $custom_vhost_path_config) {
    $apache_conf = realpath(isset($apache_conf[0]) ? $apache_conf[0] : null);
    if (($vhostIsEnable = (vhostIsEnable($apache_conf) || isset($custom_vhost_path_config)))) {
        $vhosts_path = isset($custom_vhost_path_config)
            ? array($custom_vhost_path_config)
            : glob(WAMP_PATH.'vhost/*.conf');

        $vhosts = array();

        foreach ($vhosts_path as &$vhost) {
            $vhosts = array_merge($vhosts, getVhosts($vhost, $vhost_to_ignore));
        }

// use apache_get_version
        if (!IS_WINDOWS) {
            $vhost_default_conf = <<<VHOST
NameVirtualHost *:80
<VirtualHost *:80>
    ServerName  PROJECT.localhost
    ServerAlias PROJECT.localhost.com

    DocumentRoot "%sPATH"
    <Directory   "%sPATH">
        Options Indexes FollowSymLinks ExecCGI Includes
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
VHOST;
            $vhost_path_config = '/etc/apache2/extra/httpd-vhosts.conf';
            $slash = '/';
        } else {
            $vhost_default_conf = <<<VHOST
<VirtualHost *:80>
    ServerName  PROJECT.localhost
    ServerAlias PROJECT.localhost.com

    DocumentRoot "%sPATH"
    <directory   "%sPATH">
        allow from all
    </directory>
</VirtualHost>
VHOST;
            $vhost_path_config = WAMP_PATH.'vhost\<strong class="project">PROJECT</strong>.conf';
            $slash = '\\';
        }
        $vhost_default_conf = sprintf($vhost_default_conf, realpath($path_to_display).$slash, realpath($path_to_display).$slash);
        $vhost_default_conf = htmlentities($vhost_default_conf);
        $vhost_default_conf = preg_replace('#PROJECT#', '<strong class="project">PROJECT</strong>', $vhost_default_conf);
        $vhost_default_conf = preg_replace('#PATH#', '<strong class="path">PATH</strong>', $vhost_default_conf);
    }
}

$hosts_path = '/etc/hosts';
if (IS_WINDOWS) {
    $hosts_path = 'C:\Windows\System32\drivers\etc\hosts';
}

// main code alias
$alias = clearAlias(glob(WAMP_PATH.'alias/*.conf'));
foreach ($alias as $name => &$path) {
    $structure = array();
    $structure['name'] = $name;
    $structure['url']  = getAliasUrl($path);
    $structure['img']  = getFavicon('http://localhost/'.$name);
    $path = $structure;
}
sort($alias);


// main code toolbox
foreach ($toolbox as $name => &$url) {
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

preg_match("([0-9\.]+)", @mysql_get_server_info(), $match);
$mysql_version = isset($match[0])
    ? $match[0]
    : null;

?>
<!DOCTYPE html>
<html>
    <head>
        <title>localhost</title>
        <link href=".bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href=".bootstrap/css/application.css" rel="stylesheet">
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    </head>
    <body>
        <div id="page">
            <div class="container">
                <div class="row">
                    <div class="span12">

                        <!-- www -->
                        <span class="label label-info" style="margin-top:11px;margin-bottom:10px">www</span>
                        <div class="row-fluid">
                            <?php foreach ($root as $nb => &$dir): ?>
                                <?php if ($nb % 4 == 0): ?>
                                    <?php if ($nb != 0): ?>
                                        </ul>
                                    <?php endif; ?>
                                    <ul class="thumbnails">
                                <?php endif; ?>
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
                        <?php if ($vhostIsEnable): ?>
                        <span class="label label-inverse" style="margin-top:11px;margin-bottom:10px">vhost</span>
                        <div class="row-fluid">
                            <?php foreach ($vhosts as $nb => &$vhost): ?>
                                <?php if ($nb % 4 == 0): ?>
                                    <?php if ($nb != 0): ?>
                                        </ul>
                                    <?php endif; ?>
                                    <ul class="thumbnails">
                                <?php endif; ?>
                                <li class="span3">
                                    <a href="<?php echo $vhost['url']; ?>" class="thumbnail" style="background:white">
                                        <img src="<?php echo $vhost['img']; ?>">
                                    </a>
                                    <div class="name"><?php echo substr($vhost['name'], 0, 20).(strlen($vhost['name']) > 20 ? '...' : ''); ?></div>
                                    <div class="space" data-path="<?php echo $vhost['path']; ?>"></div>
                                </li>
                            <?php endforeach; ?>
                            </ul>

                            <span id="vhost-example-button" class="label label-warning" style="cursor:help;float:right">Example vhost</span>
                            <div id="vhost-example" style="<?php if ($vhostIsEnable): ?>display:none<?php endif; ?>">
                                <pre><?php echo($vhost_default_conf) ?></pre>
                                <?php if (IS_WINDOWS && $vhost_include_not_define): ?>
                                    <small>Add <code>Include "<?php echo WAMP_PATH; ?>vhost\*.conf"</code> in <code><?php echo sprintf('%s<strong>%s</strong>', substr($apache_conf, 0, -10), substr($apache_conf, -10)); ?></code></small><br/>
                                <?php endif; ?>
                                <small>You must write this conf inside <code><?php echo isset($custom_vhost_path_config) ? $custom_vhost_path_config : $vhost_path_config; ?></code> and create a directory <code><?php echo realpath($path_to_display).$slash; ?><strong class='path'>PATH</strong></code></small><br/>
                                <small>Don't forget to add your domains in <code><?php echo $hosts_path; ?></code> like <code>127.0.0.1 <strong class='project'>PROJECT</strong>.localhost.com <strong class='project'>PROJECT</strong>.localhost</code>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- infos -->
                        <span class="label label-info" style="margin-top:11px;margin-bottom:10px">server</span>
                        <div class="row-fluid" style="padding-left:20px">
                            <div class="span2">
                                <span class="label">Apache</span> <span class="label label-warning"><?php echo $apache_version; ?></span><br/>
                                <span class="label">PHP</span>    <span class="label label-warning"><?php echo $php_version;    ?></span><br/>
                                <span class="label">MySQL</span>  <span class="label label-warning"><?php echo $mysql_version ?: 'unknown';  ?></span>
                            </div>
                            <div class="span10" style="margin-left:-20px">
                                <span class="label label-inverse">PHP extensions</span>
                                <?php foreach (get_loaded_extensions() as $extension): ?>
                                    <span class="label"><?php echo $extension; ?></span>
                                <?php endforeach; ?>
                            </div>
                        </div>

                    </div>
                    <div class="span2">

                        <!-- alias -->
                        <?php if (!empty($alias)): ?>
                            <span class="label label-inverse" style="margin-top:11px;margin-bottom:10px">alias</span>
                            <div class="row-fluid" style="padding-left:5px">
                                <?php foreach ($alias as $item): ?>
                                    <a href="<?php echo $item['url']; ?>">
                                        <i class="icon-test" style="background:url(<?php echo $item['img']; ?>);background-size:16px 16px"></i> <?php echo $item['name']; ?>
                                    </a><br/>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <!-- toolbox -->
                        <span class="label label-info" style="margin-top:11px;margin-bottom:10px">toolbox</span>
                        <div class="row-fluid" style="padding-left:5px">
                            <?php foreach ($toolbox as $item): ?>
                                <a href="<?php echo $item['url']; ?>" target="_blank">
                                    <i class="icon-test" style="background:url(<?php echo $item['img']; ?>);background-size:16px 16px"></i> <?php echo $item['name']; ?>
                                </a><br/>
                            <?php endforeach; ?>
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
