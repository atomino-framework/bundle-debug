<?php namespace Atomino\Bundle\Debug;

use Atomino\Debug\DebugHandler;

class Alert extends \Atomino\Debug\Debug {

	public function __construct(private Telegram $telegram) { parent::__construct(); }
	protected function handleUnknownType(string $channel, mixed $data) {}

	#[DebugHandler(		\Atomino\Debug\Debug::DEBUG_ALERT	)]
	protected function alert($data) {
		if(!is_scalar($data)) $data = json_encode($data, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
		$this->telegram->send($data);
	}
}


