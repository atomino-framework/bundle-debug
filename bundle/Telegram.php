<?php namespace Atomino\Bundle\Debug;

use Symfony\Component\HttpClient\HttpClient;

class Telegram {

	public function __construct(private string $bot, private string $channel) { }

	public function send(string $message) {
		HttpClient::create()->request('GET', 'https://api.telegram.org/bot' . $this->bot . '/sendMessage?' . http_build_query(['chat_id' => $this->channel, 'text' => $message]));
	}
}
