<?php
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../framework/bootstrap.php';
use Framework\Router; use Framework\Middleware\Dispatcher as MiddlewareDispatcher;
foreach (glob(__DIR__ . '/../app/Domain/*/Routes/*.php') as $file) require $file;
$method = $_SERVER['REQUEST_METHOD']; $path = strtok($_SERVER['REQUEST_URI'], '?');
foreach (Router::all() as $route){ if($route->method===$method && $route->path===$path){ $handler=$route->handler; $container=$GLOBALS['app_container']; if(is_array($handler)){ [$class,$m]=$handler; $controller = $container->has($class)?$container->get($class):new $class($container); $request = new class{ public function getParsedBody(){ return array_merge($_POST, json_decode(file_get_contents('php://input'), true) ?: []); } }; $middleware = property_exists($route,'middlewareList')?($route->middlewareList ?? []):[]; $callable = function($req) use ($controller,$m){ return $controller->$m($req); }; $response = MiddlewareDispatcher::handle($request,$middleware,$callable); header('Content-Type: application/json'); echo json_encode($response); exit; } } }
http_response_code(404); echo json_encode(['error'=>'Not found']);
