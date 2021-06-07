<?php namespace Atomino\Bundle\Debug;

class Telegram {

	public function __construct(private string $bot, private string|array $channels) {
		if (!is_array($this->channels)) $this->channels = [$this->channels];
	}

	public function send(string $message) {
		foreach ($this->channels as $channel) {
			file_get_contents('https://api.telegram.org/bot' . $this->bot . '/sendMessage?' . http_build_query(['chat_id' => $channel, 'text' => $message]));
		}
	}
}
