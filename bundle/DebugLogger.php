<?php namespace Atomino\Bundle\Debug;

use Atomino\Carbon\Database\Connection;
use Atomino\Core\Debug\Channel;
use Atomino\Core\Debug\ErrorHandlerInterface;
use Monolog\Logger;
use function Atomino\Core\Debug;

class DebugLogger implements \Atomino\Core\Debug\DebugHandlerInterface {

	public function __construct(private Logger $logger) {	}

	public function handle(mixed $data, string $channel, int $level) {
		$this->logger->withName($channel)->debug("", ["payload"=>$data]);
	}

}


