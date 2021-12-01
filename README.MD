[![Latest Stable Version](http://poser.pugx.org/nsp-team/crypt-tool/v)](https://packagist.org/packages/nsp-team/crypt-tool)
[![Total Downloads](http://poser.pugx.org/nsp-team/crypt-tool/downloads)](https://packagist.org/packages/nsp-team/crypt-tool)
[![Latest Unstable Version](http://poser.pugx.org/nsp-team/crypt-tool/v/unstable)](https://packagist.org/packages/nsp-team/crypt-tool)
[![License](http://poser.pugx.org/nsp-team/crypt-tool/license)](https://packagist.org/packages/nsp-team/crypt-tool)
[![PHP Version Require](http://poser.pugx.org/nsp-team/crypt-tool/require/php)](https://packagist.org/packages/nsp-team/crypt-tool)

# Summary

A PHP library to work with http request. dependent on `https://github.com/Yurunsoft/YurunHttp/`

# Installation

This library is installable via `composer`

```bash
composer require nsp-team/simple-http
```

# Usage

```php
<?php
require dirname(__DIR__) . '/vendor/autoload.php';

use Yurun\Util\YurunHttp\Http\Psr7\UploadedFile;
use Yurun\Util\YurunHttp\Http\Psr7\Consts\MediaType;

$response = \NspTeam\HttpClient::create()->get('https://www.baidu.com');

$response = \NspTeam\HttpClient::create()
    ->withHeaders([
    'content-type' => 'application/json'
    ])
    // 同时支持POST参数、上传文件
    ->withContent([
    'lang' => 'php',
    'ver'  => 'any',
    'file' => new \Yurun\Util\YurunHttp\Http\Psr7\UploadedFile('1.txt', MediaType::TEXT_PLAIN, __FILE__),
    ])
    ->post('https://www.baidu.com');


$url = 'https://www.baidu.com'
$response = \NspTeam\HttpClient::create()->download(__DIR__ . '/save.*', $url); // 如果文件名设为save.*，.* 则代表自动识别扩展名
```
