<?php
namespace Framework\Facades; class Cache extends Facade { protected static function getFacadeAccessor(){ return \Framework\Cache\Cache::class; } }
