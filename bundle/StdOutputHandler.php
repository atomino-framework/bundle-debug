<?php namespace Atomino\Bundle\Debug;

use Atomino\Core\Application;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;

class StdOutputHandler extends AbstractProcessingHandler {

	public function __construct($level = Logger::DEBUG) { parent::__construct($level, true); }

	protected function write(array $record): void {
		fwrite($fp = fopen("php://stdout", "w"), $record['formatted']."\n");
		fclose($fp);
	}

}