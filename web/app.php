<?php
/**
 * 本番用アプリケーション
 */
use Symfony\Component\HttpFoundation\Request;

/** @var \Composer\Autoload\ClassLoader $loader */
$loader = require __DIR__.'/../app/autoload.php';
include_once __DIR__.'/../var/bootstrap.php.cache';

$kernel = new AppKernel('prod', false);
$kernel->loadClassCache();

// TODO: ** 以下のリバースプロキシを有効にすると、アプリケーションからキャッシュ可能なレスポンスが返された場合、すぐにキャッシュされる
//$kernel = new AppCache($kernel);

// When using the HttpCache, you need to call the method in your front controller instead of relying on the configuration parameter
// TODO ** HttpCacheを利用する場合、設定値の代わりにfrontControllerの中のmethodを呼び出す必要があります。
// ** POSTリクエストにおいて、リクエストパラメータ _method が、意図した HTTP メソッドとして使われるかどうかを決定します。
//Request::enableHttpMethodParameterOverride();

$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
