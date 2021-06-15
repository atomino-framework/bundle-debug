<?php namespace Atomino\Bundle\Debug;

use Atomino\Carbon\Database\Connection;
use Atomino\Core\Debug\Channel;
use Atomino\Core\Debug\ErrorHandlerInterface;
use function Atomino\Core\Debug;

class DebugHandler extends \Atomino\Core\Debug\DebugHandler {

	public function __construct(private RLogTail $RLogTail) { parent::__construct(); }

	#[Channel(
		Connection::DEBUG_SQL,
		Connection::DEBUG_SQL_ERROR,
		\Atomino\Core\Debug\DebugHandler::DEBUG_ALERT,
		\Atomino\Core\Debug\DebugHandler::DEBUG_DUMP,
	)]
	protected function dump($data, string $channel) {
		$this->RLogTail->send($data, match ($channel){
			Connection::DEBUG_SQL => RLogTail::CHANNEL_SQL,
			Connection::DEBUG_SQL_ERROR => RLogTail::CHANNEL_SQL_ERROR,
			\Atomino\Core\Debug\DebugHandler::DEBUG_ALERT => RLogTail::CHANNEL_ALERT,
			\Atomino\Core\Debug\DebugHandler::DEBUG_DUMP => RLogTail::CHANNEL_DUMP,
		});
	}

	#[Channel(ErrorHandlerInterface::DEBUG_ERROR)]
	protected function error($data) {
		if(array_key_exists('trace', $data)){
			$trace = $data['trace'];
			unset($data['trace']);
			foreach ($trace as &$item) unset($item['args']);
		}
		$this->RLogTail->send($data, RLogTail::CHANNEL_ERROR);
		if(isset($trace)) $this->RLogTail->send($trace, RLogTail::CHANNEL_TRACE);
	}

	#[Channel(ErrorHandlerInterface::DEBUG_EXCEPTION)]
	protected function exception($data) {
		$trace = $data['trace'];
		unset($data['trace']);
		foreach ($trace as &$item) unset($item['args']);
		$this->RLogTail->send($data, RLogTail::CHANNEL_EXCEPTION);
		$this->RLogTail->send($trace, RLogTail::CHANNEL_TRACE);
	}

}


