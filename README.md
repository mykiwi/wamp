# [Personal Homepage WAMP](https://github.com/MyKiwi/wamp)

![Personal Homepage WAMP](http://www.werox.fr/image/preview/1617x1036/139.png)

## Features:

  * Display www directories
  * Display virtual host
  * Custom thumbnails
  * Example for create a new virtual host
  * Alias with favicon stored in cache
  * Toolbox with favicon stored in cache

## Quick Start

You can configure and customize the script with this variables:
```php
<?php
$path_to_display = './';
$wamp_path       = 'C:/wamp';

$toolbox = array(
    'Name' => 'http://url.to.go',
);

// custom thumbnails to search
$preview_inside_dir = array(
    'screenshot',
    'preview',
);
```

## Custom Thumbnails

### www

You have 2 ways to customize the thumbnail:

1. Put the image in /www with the same name of the directory<br>
2. Put the image inside the directory, with a name like in ```$preview_inside_thumbnails```<br>

### virtual host

You have 2 ways to customize the thumbnail:

1. Put the image next to the virtual host conf, with the same name<br>
2. Put the image inside the directory, with a name like in ```$preview_inside_thumbnails```<br>


## Special Thanks
 * [Twitter Bootstrap](http://twitter.github.com/bootstrap/)
 * [Fake images please?](http://fakeimg.pl/)

