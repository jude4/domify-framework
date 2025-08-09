<?php
namespace Framework\Facades; class Auth extends Facade { protected static function getFacadeAccessor(){ return \Framework\Auth\Auth::class; } }
