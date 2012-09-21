<?php
// index.php
require_once 'app/bootstrap.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use StoreBundle\Order;

$request = Request::createFromGlobals();

$uri = $request->getPathInfo();
$base = '';
if ($uri == '/') {
    $response = list_action();
} elseif ($uri == '/bill' && $request->query->has('id')) {
    $base = '../';
    $response = bill_action($request->query->get('id'));
} else {
    $html = '<html><body><h1>Page Not Found</h1></body></html>';
    $response = new Response($html, 404);
}

// echo the headers and send the response
$response->send();