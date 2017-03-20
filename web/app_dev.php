<?php
/**
 * 開発用アプリケーション
 * ローカルIPからのアクセスは開発環境でのみ許容する
 */
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Debug\Debug;

// If you don't want to setup permissions the proper way, just uncomment the following PHP line
// read http://symfony.com/doc/current/setup.html#checking-symfony-application-configuration-and-setup
// for more information
//umask(0000);

// ** 以下のコードによりローカルIPからのアクセスは開発環境でのみ許容する
// This check prevents access to debug front controllers that are deployed by accident to production servers.
// Feel free to remove this, extend it, or make something more sophisticated.
if (isset($_SERVER['HTTP_CLIENT_IP'])
    || isset($_SERVER['HTTP_X_FORWARDED_FOR'])
    || !(in_array(@$_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1']) || php_sapi_name() === 'cli-server')
) {
    header('HTTP/1.0 403 Forbidden');
    exit('You are not allowed to access this file. Check '.basename(__FILE__).' for more information.');
}

/** @var \Composer\Autoload\ClassLoader $loader */
$loader = require __DIR__.'/../app/autoload.php';
Debug::enable();

$kernel = new AppKernel('dev', true);       // カーネルの実体化
$kernel->loadClassCache();
$request = Request::createFromGlobals();    // Requestの準備
$response = $kernel->handle($request);      // リクエストを処理して結果を受け取る
$response->send();                          // レスポンスをクライアントへ送信
$kernel->terminate($request, $response);    // 終了
