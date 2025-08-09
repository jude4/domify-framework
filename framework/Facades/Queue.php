<?php
namespace Framework\Facades; class Queue extends Facade { protected static function getFacadeAccessor(){ return \Framework\Queue\Queue::class; } }
