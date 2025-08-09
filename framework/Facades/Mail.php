<?php
namespace Framework\Facades; class Mail extends Facade { protected static function getFacadeAccessor(){ return \Framework\Mail\Mailer::class; } }
