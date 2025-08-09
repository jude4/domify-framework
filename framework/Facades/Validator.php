<?php
namespace Framework\Facades; class Validator extends Facade { protected static function getFacadeAccessor(){ return \Illuminate\Validation\Factory::class; } }
