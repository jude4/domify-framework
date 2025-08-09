<?php
namespace Framework;
use Psr\Container\ContainerInterface;
class Container implements ContainerInterface { private array $bindings=[]; private array $instances=[]; public function set(string $id, callable $resolver): void{ $this->bindings[$id]=$resolver; } public function get($id){ if(isset($this->instances[$id])) return $this->instances[$id]; if(!isset($this->bindings[$id])) throw new \Exception("Not found: $id"); $obj = call_user_func($this->bindings[$id], $this); $this->instances[$id]=$obj; return $obj; } public function has($id): bool{ return isset($this->bindings[$id])||isset($this->instances[$id]); }}
