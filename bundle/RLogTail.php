<?php namespace Atomino\Bundle\RLogTail;

use Symfony\Component\HttpFoundation\Request;

class RLogTail {

	const CHANNEL_SQL = 'sql';
	const CHANNEL_DUMP = 'dump';
	const CHANNEL_ALERT = 'alert';
	const CHANNEL_SQL_ERROR = 'sql_error';
	const CHANNEL_ERROR = 'error';
	const CHANNEL_EXCEPTION = 'exception';
	const CHANNEL_TRACE = 'trace';

	private $unixSocketConnection;

	public function __construct(private string $connection, private string $address) { }

	public function send($data, $channel) {
		$request = Request::createFromGlobals();
		$message = json_encode([
			"channel"    => $channel,
			"data" => $data,
			"header"  => [
				"request" => [
					"host"   => $request->getSchemeAndHttpHost(),
					"method" => $request->getMethod(),
					"path"   => $request->getPathInfo(),
				],
			],
		]);

		if($this->connection === 'http') $this->sendHttp($message);
		if($this->connection === 'unix-socket') $this->sendUnixSocket($message);
	}

	protected function sendUnixSocket($message){
		if(!is_resource($this->unixSocketConnection)){
			try{
				$this->unixSocketConnection = stream_socket_client('unix://' . $this->address, $errorCode, $errorMessage, 12);
			}catch (\Exception $e){}
		}
		if(is_resource($this->unixSocketConnection)){
			$socket = (new \Socket\Raw\Factory())->createUnix();
			$socket->connect( $this->address);
			$socket->write($message);
			$socket->close();
		}
	}

	protected function sendHttp(mixed $message){
		list($address, $port) = explode(':', $this->address);
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