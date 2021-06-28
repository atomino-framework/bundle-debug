<?php namespace Atomino\Bundle\Debug\ChannelFormatter;

use Atomino\Bundle\Debug\ChannelFormatterInterface;
use Codedungeon\PHPCliColors\Color;

class ExceptionChannelFormatter implements ChannelFormatterInterface {
	public function format(string $channel, mixed $payload, string $datetime): string {
		
		$text = Color::LIGHT_YELLOW . Color::BG_LIGHT_RED . Color::BOLD . " " . $payload['type'] . " " . Color::RESET . ' ';
		$text .= Color::LIGHT_RED_ALT . $payload["message"] . "\n";
		$text .= Color::LIGHT_YELLOW . $payload["file"] . COLOR::YELLOW . ' @ ' . $payload['line'];
		return $text;
	}
}
