<?php
// bootstrap.php
require_once __DIR__.'/../vendor/FirePHPCore/FirePHP.class.php';
require_once 'model.php';
require_once 'controllers.php';
require_once __DIR__.'/../vendor/symfony/Component/ClassLoader/UniversalClassLoader.php';
ob_start();
$firephp = FirePHP::getInstance(true);

$loader = new Symfony\Component\ClassLoader\UniversalClassLoader();
$loader->registerNamespaces(array(
    'Symfony' => __DIR__.'/../vendor',
    'BillingBundle' => __DIR__
));

$loader->register();
