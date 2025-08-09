<?php
namespace Framework\Queue;
interface Queue { public function push(string $payload, string $queue='default'); public function pop(string $queue='default'); }
