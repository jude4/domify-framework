<?php
namespace Framework\Facades;
abstract class Facade { protected static function getFacadeAccessor(){ return null; } public static function __callStatic($method,$args){ $container=$GLOBALS['app_container']; $accessor=static::getFacadeAccessor(); $instance=$container->get($accessor); return $instance->$method(...$args); } }
