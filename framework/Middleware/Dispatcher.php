<?php
namespace Framework\Middleware;
class Dispatcher { public static function handle($request, array $middleware, $controller){ $pipeline = array_reduce(array_reverse($middleware), function($next,$m){ return function($req) use ($next,$m){ if(is_string($m) && class_exists($m)){ $inst = new $m(); return $inst->handle($req,$next); } elseif(is_callable($m)){ return $m($req,$next);} return $next($req); }; }, function($req) use ($controller){ return $controller($req); }); return $pipeline($request); } }
