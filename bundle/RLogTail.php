<?php namespace Atomino\Bundle\RLogTail;

use Symfony\Component\HttpFoundation\Request;
use function Atomino\debug;

class RLogTail {

	const CHANNEL_SQL = 'sql';
	const CHANNEL_DUMP = 'dump';
	const CHANNEL_ALERT = 'alert';
	const CHANNEL_SQL_ERROR = 'sql_error';
	const CHANNEL_ERROR = 'error';
	const CHANNEL_EXCEPTION = 'exception';
	const CHANNEL_TRACE = 'trace';
	const CHANNEL_REQUEST = 'request';


	private array $buffer = [];
	private $unixSocketConnection;

	public function __construct(private string $connection, private string $address) {
		$request = Request::createFromGlobals();
		$this->send([
			"host"   => $request->getHost(),
			"method" => $request->getMethod(),
			"path"   => $request->getPathInfo(),
			"query"  => $request->getQueryString(),
		], self::CHANNEL_REQUEST);
	}

	public function register() { register_shutdown_function(fn() => $this->flush()); }

	public function flush() {
		$this->buffer[0]["data"]["runtime"] = (ceil((microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"]) * 100000) / 100) . 'ms';
		foreach ($this->buffer as $message) {
			if ($this->connection === 'http') $this->sendHttp($message);
			if ($this->connection === 'unix-socket') $this->sendUnixSocket($message);
		}
	}

	public function send($data, $channel) {
		$t = microtime(true);
		$micro = sprintf("%06d", ($t - floor($t)) * 1000000);
		$d = new \DateTime(date('Y-m-d H:i:s.' . $micro, $t));
		$time = date("H:") . $d->format("i:s.u");

		$message = [
			"channel" => $channel,
			"data"    => $data,
			"header"  => ["time" => $time,],
		];

		$this->buffer[] = $message;

	}

	protected function sendUnixSocket($message) {
		$message = json_encode($message);
		if (!is_resource($this->unixSocketConnection)) {
			try {
				$this->unixSocketConnection = stream_socket_client('unix://' . $this->address, $errorCode, $errorMessage, 12);
			} catch (\Exception $e) {
			}
		}
		if (is_resource($this->unixSocketConnection)) {
			$socket = (new \Socket\Raw\Factory())->createUnix();
			$socket->connect($this->address);
			$socket->write($message);
			$socket->close();
		}
	}

	protected function sendHttp(mixed $message) {
		$message = json_encode($message);
		[$address, $port] = explode(':', $this->address);
		try {
			$fp = @fsockopen($address, $port, $errno, $errstr, 30);
			if ($fp) {
				$out = "POST / HTTP/1.1\r\n";
				$out .= "Host: " . $address . "\r\n";
				$out .= "Content-Type: application/json\r\n";
				$out .= "Content-Length: " . strlen($message) . "\r\n";
				$out .= "Connection: Close\r\n\r\n";
				if (isset($message)) $out .= $message;
				fwrite($fp, $out);
				fclose($fp);
			}
		} catch (\Exception $ex) {
		}
	}

}