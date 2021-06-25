<?php namespace Atomino\Bundle\Debug;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;

class HttpHandler extends AbstractProcessingHandler {

	public function __construct(private string $url, $level = Logger::DEBUG) { parent::__construct($level, true); }

	protected function write(array $record): void {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $record['formatted']);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_exec($ch);
		curl_close($ch);
	}

}