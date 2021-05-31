<?php namespace Atomino\Bundle\RLogTail;

use Atomino\Carbon\Database\Connection;
use Atomino\Debug\DebugHandler;
use Atomino\Debug\ErrorHandlerInterface;
use function Atomino\debug;

class Debug extends \Atomino\Debug\Debug {

	public function __construct(private RLogTail $RLogTail) { parent::__construct(); }

	protected function handleUnknownType(string $channel, mixed $data) {
		$this->RLogTail->send('Unknown debug channel: ' . $channel, RLogTail::CHANNEL_ALERT);
	}

	#[DebugHandler(
		Connection::DEBUG_SQL,
		Connection::DEBUG_SQL_ERROR,
		\Atomino\Debug\Debug::DEBUG_ALERT,
		\Atomino\Debug\Debug::DEBUG_DUMP,
	)]
	protected function sql($data, string $channel) {
		$this->RLogTail->send($data, match ($channel){
			Connection::DEBUG_SQL => RLogTail::CHANNEL_SQL,
			Connection::DEBUG_SQL_ERROR => RLogTail::CHANNEL_SQL_ERROR,
			\Atomino\Debug\Debug::DEBUG_ALERT => RLogTail::CHANNEL_ALERT,
			\Atomino\Debug\Debug::DEBUG_DUMP => RLogTail::CHANNEL_DUMP,
		});
	}

	#[DebugHandler(ErrorHandlerInterface::DEBUG_ERROR)]
	protected function error($data) {
		if(array_key_exists('trace', $data)){
			$trace = $data['trace'];
			unset($data['trace']);
			foreach ($trace as &$item) unset($item['args']);
		}
		$this->RLogTail->send($data, RLogTail::CHANNEL_ERROR);
		if(isset($trace)) $this->RLogTail->send($trace, RLogTail::CHANNEL_TRACE);
	}

	#[DebugHandler(ErrorHandlerInterface::DEBUG_EXCEPTION)]
	protected function exception($data) {
		$trace = $data['trace'];
		unset($data['trace']);
		foreach ($trace as &$item) unset($item['args']);
		$this->RLogTail->send($data, RLogTail::CHANNEL_EXCEPTION);
		$this->RLogTail->send($trace, RLogTail::CHANNEL_TRACE);
	}

}


