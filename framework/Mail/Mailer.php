<?php
namespace Framework\Mail;
class Mailer { private array $config; public function __construct(array $config){ $this->config=$config; } public function to(string $to): self{ $this->to=$to; return $this;} public function subject(string $s): self{ $this->subject=$s; return $this;} public function body(string $b): self{ $this->body=$b; return $this;} public function send(): bool{ $headers='From: '.($this->config['from']??'noreply@example.com')."\r\n"; return mail($this->to,$this->subject,$this->body,$headers);} }
