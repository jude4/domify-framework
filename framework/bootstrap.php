<?php
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Events\Dispatcher as EventsDispatcher;
use Illuminate\Container\Container as IlluminateContainer;
use Illuminate\Validation\Factory as ValidatorFactory;
use Illuminate\Translation\Translator;
use Illuminate\Translation\ArrayLoader;

require __DIR__ . '/../vendor/autoload.php';

if (file_exists(__DIR__ . '/../.env')) (Dotenv\Dotenv::createImmutable(__DIR__ . '/../'))->load();

if (!isset($GLOBALS['app_container'])) $GLOBALS['app_container'] = new Framework\Container();

$capsule = new Capsule;
$driver = getenv('DB_DRIVER') ?: 'sqlite';
$database = getenv('DB_DATABASE') ?: __DIR__ . '/../database/database.sqlite';
if ($driver === 'sqlite') {
    $capsule->addConnection(['driver'=>'sqlite','database'=>$database,'prefix'=>'']);
} else {
    $capsule->addConnection(['driver'=>$driver,'host'=>getenv('DB_HOST')?:'127.0.0.1','database'=>getenv('DB_DATABASE')?:'db','username'=>getenv('DB_USERNAME')?:'root','password'=>getenv('DB_PASSWORD')?:'','charset'=>'utf8mb4','collation'=>'utf8mb4_unicode_ci','prefix'=>'']);
}

$illuminateContainer = new IlluminateContainer;
$events = new EventsDispatcher($illuminateContainer);
$capsule->setEventDispatcher($events);
$capsule->setAsGlobal();
$capsule->bootEloquent();

$GLOBALS['app_container']->set(Illuminate\Database\Capsule\Manager::class, function($c) use ($capsule){ return $capsule; });
$GLOBALS['app_container']->set('db', function($c) use ($capsule){ return $capsule->getConnection(); });
$GLOBALS['app_container']->set(Illuminate\Events\Dispatcher::class, function($c) use ($events){ return $events; });

$loader = new ArrayLoader();
$translator = new Translator($loader, 'en');
$validatorFactory = new ValidatorFactory($translator);
$GLOBALS['app_container']->set(Illuminate\Validation\Factory::class, function($c) use ($validatorFactory){ return $validatorFactory; });

$GLOBALS['app_container']->set(Framework\Cache\Cache::class, function($c){ return new Framework\Cache\FileCache(__DIR__ . '/../storage/cache'); });
$GLOBALS['app_container']->set(Framework\Mail\Mailer::class, function($c){ return new Framework\Mail\Mailer(require __DIR__ . '/../config/mail.php'); });
$GLOBALS['app_container']->set(Framework\Queue\Queue::class, function($c){ return new Framework\Queue\DatabaseQueue($c->get('db')); });
$GLOBALS['app_container']->set(Framework\Auth\Auth::class, function($c){ return new Framework\Auth\Auth($c->get(\App\Infrastructure\Models\UserModel::class)); });

if (!function_exists('event')) {
    function event($event) { return $GLOBALS['app_container']->get(Illuminate\Events\Dispatcher::class)->dispatch($event); }
}

$providersFile = __DIR__ . '/../config/providers.php';
if (file_exists($providersFile)) {
    $providers = require $providersFile;
    foreach ($providers as $providerClass) { if (class_exists($providerClass)) { $p = new $providerClass($GLOBALS['app_container']); if (method_exists($p,'register')) $p->register(); } }
    foreach ($providers as $providerClass) { if (class_exists($providerClass)) { $p = new $providerClass($GLOBALS['app_container']); if (method_exists($p,'boot')) $p->boot(); } }
}
